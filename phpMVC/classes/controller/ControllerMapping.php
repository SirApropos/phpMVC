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

	/**
	 * @var array
	 */
	private $exceptionHandlers = [];

	/**
	 * @var array
	 */
	private $filters = [];

	/**
	 * @var String
	 */
	private $optionsMethod;

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
	 * @param ReflectionClass $clazz
	 * @param array $obj
	 * @return mixed|void
	 */
	public function bind(ReflectionClass $clazz, array $obj)
	{
		$this->_bindRequestMappings($obj);
		$this->_bindExceptionHandlers($clazz, $obj);
		if(isset($obj['optionsMethod']) && is_null($this->optionsMethod)) {
			$this->optionsMethod = $obj['optionsMethod'];
		}
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
			$this->exceptionHandlers = $this->mergeExceptionHandlers($this->exceptionHandlers, $handlers);
		}
        if($parentClass = $clazz->getParentClass()){
            /**
             * @var $parent ControllerMapping
             */
            $parent = ReflectionUtils::getMapping($parentClass, "ControllerMapping");
            $this->exceptionHandlers = $this->mergeExceptionHandlers($parent->getExceptionHandlers(), $this->exceptionHandlers);
        }

	}

	private function mergeExceptionHandlers(array $parent, array $child){
		foreach($parent as $handler){
			if(!in_array($handler, $child)){
				$child[] = $handler;
			}
		}
		return $child;
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

	/**
	 * @return array
	 */
	public function getFilters() {
		return $this->filters;
	}

	/**
	 * @param array $filters
	 */
	public function setFilters($filters) {
		$this->filters = $filters;
	}

	/**
	 * @return String
	 */
	public function getOptionsMethod() {
		return $this->optionsMethod;
	}

	/**
	 * @param String $optionsMethod
	 */
	public function setOptionsMethod($optionsMethod) {
		$this->optionsMethod = $optionsMethod;
	}
}