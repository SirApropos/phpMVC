<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 2:19 PM
 */
class ControllerMethodInvoker{

	public static $mapping = [
		"fields" => [
			"container" => [
				"autowired" => true,
				"type" => "IOCContainer"
			]
		]
	];
	/**
	 * @var Mapper[]
	 */
	private $mappers = [];

	/**
	 * @var IOCContainer
	 */
	private $container;

	function __construct()
	{
		array_push($this->mappers,new BasicMapper());
		array_push($this->mappers,new JsonMapper());
		array_push($this->mappers,new XmlMapper());
	}

	public function invoke(ControllerMethod $cmethod){
		$timer = Timer::create("Invoker", "invoking");
		try {
			if(!$this->canInvoke($cmethod)){
				throw new InvalidGrantException("Access Denied");
			}
			$method = $cmethod->getMethod();
			$args = [];
			$params = $method->getParameters();
			$request = $this->container->resolve("HttpRequest");
			$pathParts = preg_split("`/`", $request->getPath());
			$mappingParts = preg_split("`/`", $cmethod->getMapping()->getPath());
			$urlvars = [];
			foreach ($mappingParts as $key => $part) {
				//Should always be the case, but let's make sure.
				if (isset($pathParts[$key])) {
					$matches = [];
					if (preg_match('`\{(.+)\}`', $part, $matches)) {
						$replacer = str_replace($matches[0], "", $part);
						$urlvars[$matches[1]] = preg_replace("`^" . $replacer . "`", "", $pathParts[$key]);
					}
				}
			}
			foreach ($params as $param) {
				$clazz = $param->getClass();
				$value = null;
				if ($clazz) {
					if ($clazz->implementsInterface("Model") && !$this->container->contains($clazz->getName())) {
						if ($request->getBody()) {
							$contentType = $request->getHeaders()->getContentType();
							foreach ($this->mappers as $mapper) {
								if ($mapper->canRead($contentType)) {
									$value = $mapper->read($request->getBody(), $clazz);
									break;
								}
							}
							if (is_null($value)) {
								throw new ModelBindException("No object mapper available to read type: " . $contentType);
							}
						} else {
							if ($param->isOptional()) {
								$value = $param->getDefaultValue();
							} else {
								throw new ModelBindException("No request body provided.");
							}
						}
					} else {
						$value = $this->container->resolve($clazz->getName());
					}
				} else {
					$name = $param->getName();
					if (isset($urlvars[$name])) {
						$value = $urlvars[$name];
					} else {
						if ($param->isDefaultValueAvailable()) {
							$value = $param->getDefaultValue();
						} else {
							throw new ModelBindException("Could not satisfy dependency: " . $method->getDeclaringClass()->getName(). "::" .
								$method->getName() . '::$' . $name);
						}
					}
				}
				array_push($args, $value);
			}
			$response = $this->container->resolve("HttpResponse");
			$value = $method->invokeArgs($cmethod->getController(), $args);
			if ($value instanceof View) {
				$response->setView($value);
			} else {
				$response->setView(new BasicView($value));
			}
			$timer->stop();
			$response->send();
		}catch(Exception $ex){
			$timer->stop();
			$this->handleException($cmethod->getController(), $ex);
		}
	}

	protected function handleException(Controller $controller, Exception $ex){
		$exceptionClasses = array_reverse(ReflectionUtils::getRecursiveClasses($ex));
		$handler = null;
		/**
		 * @var $mapping ControllerMapping
		 */
		$mapping = ReflectionUtils::getMapping($controller, "ControllerMapping");
		foreach($exceptionClasses as $clazz){
			$handler = $this->_findExceptionHandler($mapping, $clazz);
			if(!is_null($handler)) {
				break;
			}
		}
		if(!is_null($handler)) {
			$handler->handle($controller, $ex);
		}else{
			throw $ex;
		}
	}

	/**
	 * @param ReflectionClass $class
	 * @return null|ControllerExceptionHandler
	 * @throws IllegalArgumentException
	 */
	private function _findExceptionHandler(ControllerMapping $mapping, ReflectionClass $exceptionClass) {
		$result = [];
		/**
		 * @var $exceptionClasses ReflectionClass[]
		 */
		$exceptionClasses = array_reverse(ReflectionUtils::getRecursiveClasses($exceptionClass));
		$handlers = $mapping->getExceptionHandlers();
		$handlers = $this->_findHandlers($handlers, $exceptionClass);
		if(sizeof($handlers > 1)){
			while(sizeof($result) == 0){
				$exceptionClass = $exceptionClass->getParentClass();
				foreach($handlers as $handler){
					if(!$handler->canHandle($exceptionClass)){
						$result[] = $handler;
					}
				}
			}
			if(sizeof($result) > 1){
				throw new MVCException("Ambiguous mapping found for exception: ".$exceptionClass->getName());
			}
		}
		return sizeof($result) > 0 ? $result[0] : null;
	}

    /**
     * @param array $handlers
     * @param ReflectionClass $exceptionClass
     * @return ControllerExceptionHandler[]
     */
    private function _findHandlers(array &$handlers,ReflectionClass $exceptionClass ){
        $result = [];
        /**
         * @var $handler ControllerExceptionHandler
         */
        foreach($handlers as $handler){
            if($handler->canHandle($exceptionClass)){
                $result[] = $handler;
            }
        }
        return $result;
    }

	private function _containsRole(GrantedAuthority $authority, $roles=null){
		$result = false;
		if(!is_null($roles)){
			if(!is_array($roles)){
				$roles = [$roles];
			}
			foreach($authority->getRoles() as $role){
				if(in_array($role, $roles)){
					$result = true;
					break;
				}
			}
		}
		return $result;
	}

	/**
	 * @param ControllerMethod $method
	 * @param GrantedAuthority $authority
	 * @return bool
	 */
	public function canInvoke(ControllerMethod $method, GrantedAuthority $authority=null){
		if(is_null($authority)){
			$authority = $this->container->resolve("GrantedAuthority");
		}
		$result = false;
		$security = $method->getMapping()->getSecurity();
		if(!is_null($security)){
			$allowed_roles = $security->getAllowedRoles();
			if(!is_null($allowed_roles)){
				//If allowed roles is defined, make sure the user has
				//a role that is allowed.
				$result = $this->_containsRole($authority, $allowed_roles);
			}else{
				//If allowed_roles is not defined, allow all.
				$result = true;
			}
			if($result){
				//Now, deny the request if user contains a role that is denied.
				$result = !$this->_containsRole($authority, $security->getDeniedRoles());
			}
		}else{
			//No security mapping defined. Allow the request.
			$result = true;
		}
		return $result;
	}

}
