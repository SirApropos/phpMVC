<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/15/13
 * Time: 1:22 AM
 */

class ControllerMapping implements Mapping {
	/**
	 * @var RequestMapping[]
	 */
	private $mappings = [];

	private $exceptionHandlers = [];

	/**
	 * @var IOCContainer $container
	 */
	private $container;

	function __construct()
	{
		$this->container = IOCContainer::getInstance();
	}


	/**
	 * @param RequestMapping $mapping
	 */
	public function addMapping(RequestMapping $mapping){
		array_push($this->mappings, $mapping);
	}

	/**
	 * @return RequestMapping[]
	 */
	public function getMappings(){
		return $this->mappings;
	}

	/**
	 * @param $obj
	 * @return mixed
	 */
	public function bind(ReflectionClass $clazz, $obj)
	{
		$this->_bindRequestMappings($obj);
		$this->_bindExceptionHandlers($clazz, $obj);
	}

	/**
	 * @param ReflectionClass $clazz
	 * @param array $mapping
	 */
	private function _bindExceptionHandlers(ReflectionClass $clazz, array $mapping) {
		if(isset($mapping['exceptionHandlers'])){
			$handlers = [];
			foreach($mapping['exceptionHandlers'] as $handlerMethod => $exceptions){
				if(!is_array($exceptions)){
					$exceptions = [$exceptions];
				}
				$handlers[] = new ControllerExceptionHandler($handlerMethod, $exceptions);
			}
			$this->exceptionHandlers = array_merge($this->exceptionHandlers, $handlers);
		}
        if($parentClass = $clazz->getParentClass()){
            /**
             * @var $parent ControllerMapping
             */
            $parent = ReflectionUtils::getMapping($parentClass, "ControllerMapping");
            $this->exceptionHandlers = array_merge($this->exceptionHandlers, $parent->getExceptionHandlers());
        }

	}

	/**
	 * @param $mapping
	 * @throws ModelBindException
	 */
	private function _bindRequestMappings(array $mapping) {
		if (isset($mapping['methods'])) {
			foreach ($mapping['methods'] as $name => $method) {
				$requestMapping = MappingUtils::bindObject($method, "RequestMapping");
				/**
				 * @var RequestMapping $requestMapping
				 */
				if (is_null($requestMapping->getMethod())) {
					$requestMapping->setMethod($this->container->newInstance("RequestMethod"));
				}
				$requestMapping->getMethod()->setName($name);
				$this->addMapping($requestMapping);
			}
		}
	}

	/**
	 * @return array
	 */
	public function getExceptionHandlers() {
		return $this->exceptionHandlers;
	}

	/**
	 * @param array $exceptionHandlers
	 */
	public function setExceptionHandlers($exceptionHandlers) {
		$this->exceptionHandlers = $exceptionHandlers;
	}
}