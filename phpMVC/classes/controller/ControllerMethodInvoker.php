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
			],
			"invoker" => [
				"autowired" => true,
				"type" => "MethodInvoker"
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

	/**
	 * @var MethodInvoker
	 */
	private $invoker;

	function __construct()
	{
		array_push($this->mappers,new BasicMapper());
		array_push($this->mappers,new JsonMapper());
		array_push($this->mappers,new XmlMapper());
	}

	public function invoke(ControllerMethod $cmethod, HttpRequest $request, GrantedAuthority $authority){
		$timer = Timer::create("Invoker", "invoking");
		try {
			$this->_tryInvoke($cmethod, $request, $authority, $timer);
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
			try {
				$result = $handler->handle($controller, $ex);
				$this->_prepareResponse($result)->send();
			}catch(Exception $e){
				throw new Exception("Error occurred when invoking error handler: ".$e->getMessage(), $e->getCode(), $e);
			}
		}else{
			throw $ex;
		}
	}

	/**
	 * @param ControllerMapping $mapping
	 * @param ReflectionClass $exceptionClass
	 * @return null|ControllerExceptionHandler
	 * @throws MVCException
	 */
	private function _findExceptionHandler(ControllerMapping $mapping, ReflectionClass $exceptionClass) {
		$result = [];
		$handlers = $mapping->getExceptionHandlers();
		$handlers = $this->_findHandlers($handlers, $exceptionClass);
		$classes = [];
		if(sizeof($handlers) > 1){
			do{
				$classes[] = $exceptionClass;
			}while($exceptionClass = $exceptionClass->getParentClass());
			$classes = array_reverse($classes);
			foreach($classes as $class){
				$result = [];
				foreach($handlers as $handler)
				{
					if (!$handler->canHandle($class)) {
						$result[] = $handler;
					}
				}
				if(sizeof($result) < 2){
					break;
				}
			}
			if(sizeof($result) != 1){
				throw new MVCException("Ambiguous mapping found for exception: ".(is_object($exceptionClass) ? $exceptionClass->getName() : "Exception"));
			}
		}else{
			$result = $handlers;
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
	protected function checkSecurity(ControllerMethod $method, GrantedAuthority $authority){
		$security = $method->getMapping()->getSecurity();
		if (!is_null($security)) {
			$allowed_roles = $security->getAllowedRoles();
			if (!is_null($allowed_roles)) {
				//If allowed roles is defined, make sure the user has
				//a role that is allowed.
				$result = $this->_containsRole($authority, $allowed_roles);
			} else {
				//If allowed_roles is not defined, allow all.
				$result = true;
			}
			if ($result) {
				//Now, deny the request if user contains a role that is denied.
				$result = !$this->_containsRole($authority, $security->getDeniedRoles());
				return $result;
			}
		} else {
			//No security mapping defined. Allow the request.
			$result = true;
		}
		return $result;
	}

	protected  function checkMethods(ControllerMethod $method, HttpRequest $request){
		$result = true;
		$methods = $method->getMapping()->getAllowedMethods();
		if(is_array($methods) && !in_array($request->getMethod(), $methods)){
			$result = false;
		}
		return $result;
	}

	protected function collectFilters(ControllerMethod $controllerMethod){
		$result = [];
		$controllerFilters = $controllerMethod->getController();
		foreach(ReflectionUtils::getRecursiveProperties($controllerFilters, "mapping") as $mapping){
			if(isset($mapping['filters'])){
				$this->addFilters($result, $mapping['filters']);
			}
		}
		$this->addFilters($result, $controllerMethod->getMapping()->getFilters());
		return $result;
	}

	private function addFilters(array &$filters, $toAdd){
		if(is_array($toAdd)){
			foreach($toAdd as $filter => $config) {
				$filters[$filter] = $config;
			}
		}
	}

	protected function invokeFilters(ControllerMethod $controllerMethod) {
		$filters = $this->collectFilters($controllerMethod);
		$controller = $controllerMethod->getController();
		foreach($filters as $filter => $config) {
			$this->invoker->invoke($controller, $filter);
		}
	}

	/**
	 * @param ControllerMethod $cmethod
	 * @param HttpRequest $request
	 * @param GrantedAuthority $authority
	 * @param Timer $timer
	 * @throws HttpMethodNotAllowedException
	 * @throws InvalidGrantException
	 * @throws InvocationException
	 * @throws ModelBindException
	 */
	private function _tryInvoke(ControllerMethod $cmethod, HttpRequest $request, GrantedAuthority $authority, Timer &$timer) {
		if (!$this->checkSecurity($cmethod, $authority)) {
			throw new InvalidGrantException("Access Denied");
		}
		if(!$this->checkMethods($cmethod, $request)){
			if($request->getMethod() == HttpMethod::OPTIONS){
				$optionsMethod = $this->_getOptionsMethod($cmethod->getController());
				if(!is_null($optionsMethod)){
					$this->_invoke($optionsMethod, $request, $timer);
				}
				return;
			}else {
				throw new HttpMethodNotAllowedException();
			}
		}
		$this->_invoke($cmethod, $request, $timer);
	}

	/**
	 * @param Controller $controller
	 * @return ControllerMethod|null
	 * @throws IllegalArgumentException
	 * @throws MVCException
	 */
	private function _getOptionsMethod(Controller $controller){
		$result = null;
		/**
		 * @var ControllerMapping $mapping
		 */
		$mapping = ReflectionUtils::getMapping($controller, "ControllerMapping");
		$methodName = $mapping->getOptionsMethod();
		if(!is_null($methodName)) {
			$clazz = ReflectionUtils::getReflectionClass($controller);
			if($clazz->hasMethod($methodName)){
				$result = new ControllerMethod();
				$result->setController($controller);
				$result->setMapping(new RequestMapping());
				$result->getMapping()->setMethod(new RequestMethod());
				$result->getMapping()->getMethod()->setName($methodName);
			}else{
				throw new MVCException("Could not find options method: ".$clazz->getName()."::".$methodName);
			}
		}
		return $result;
	}

	/**
	 * @param $value
	 * @return HttpResponse
	 */
	private function _prepareResponse($value) {
		$response = $this->container->resolve("HttpResponse");
		if ($value instanceof View) {
			$response->setView($value);
		} else {
			$response->setView(new BasicView($value));
		}
		return $response;
	}

	/**
	 * @param ReflectionParameter $param
	 * @param $request
	 * @param $urlvars
	 * @return mixed|null|object
	 * @throws ModelBindException
	 */
	protected function satisfy(ControllerMethod $cmethod, ReflectionParameter $param, $request, $urlvars) {
		$value = null;
		$clazz = $param->getClass();
		if ($clazz) {
			if ($clazz->implementsInterface("Model") && !$this->container->contains($clazz->getName())) {
				return $this->bindRequestModel($cmethod, $param, $request, $clazz);
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
					throw new ModelBindException("Parameter ".$param->getName().
						" could not be satisfied and no default value was available.");
				}
			}
		}
		return $value;
	}

	/**
	 * @param ControllerMethod $cmethod
	 * @param $request
	 * @return array
	 */
	private function getRequestVars(ControllerMethod $cmethod, HttpRequest $request) {
		$pathParts = preg_split("`/`", $request->getPath());
		$mappingParts = preg_split("`/`", $cmethod->getPath());
		$urlvars = [];
		foreach ($mappingParts as $key => $part) {
			//Should always be the case, but let's make sure.
			//If they've left on a trailing slash, the length will be zero, so check for that.
			if (isset($pathParts[$key]) && strlen($pathParts[$key]) > 0) {
				$matches = [];
				if (preg_match('`\{(.+)\}`', $part, $matches)) {
					$replacer = str_replace($matches[0], "", $part);
					$urlvars[$matches[1]] = preg_replace("`^" . $replacer . "`", "", $pathParts[$key]);
				}
			}
		}
		return array_merge($urlvars);
	}

	/**
	 * @param ReflectionParameter $param
	 * @param $request
	 * @param $clazz
	 * @return mixed|null
	 * @throws ModelBindException
	 */
	protected function bindRequestModel(ControllerMethod $cmethod, ReflectionParameter $param, HttpRequest $request, $clazz) {
		$value = null;
		if ($request->getBody()) {
			$contentType = $cmethod->getMapping()->getAccepts();
			if(is_null($contentType)) {
				$contentType = $request->getHeaders()->getContentType();
			}
			try {
				foreach ($this->mappers as $mapper) {
					if ($mapper->canRead($contentType)) {
						$value = $mapper->read($request->getBody(), $clazz);
						break;
					}
				}
			}catch(Exception $ex){
				throw new ModelBindException("An exception occurred while reading object: ".$ex->getMessage());
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
		return $value;
	}

	/**
	 * @param ControllerMethod $cmethod
	 * @param HttpRequest $request
	 * @param Timer $timer
	 * @throws InvocationException
	 * @throws ModelBindException
	 */
	private function _invoke(ControllerMethod $cmethod, HttpRequest $request, Timer &$timer) {
		$this->invokeFilters($cmethod);
		$method = $cmethod->getMethod();
		$args = [];
		$params = $method->getParameters();
		$request = $this->container->resolve("HttpRequest");
		$vars = $this->getRequestVars($cmethod, $request);
		foreach ($params as $param) {
			try {
				$value = $this->satisfy($cmethod, $param, $request, $vars);
			} catch (ModelBindException $ex) {
				throw new ModelBindException("Could not satisfy dependency: " . $method->getDeclaringClass()->getName() . "::" .
					$method->getName() . '::$' . $param->getName() . ", root cause: ".$ex->getMessage());
			}
			array_push($args, $value);
		}
		$value = $method->invokeArgs($cmethod->getController(), $args);
		$response = $this->_prepareResponse($value);
		$timer->stop();
		$response->send();
	}

}
