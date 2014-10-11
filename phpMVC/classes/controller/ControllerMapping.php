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
	function bind($obj)
	{
		if(isset($obj['methods'])){
			foreach($obj['methods'] as $name => $mapping){
				$requestMapping = MappingUtils::bindObject($mapping, "RequestMapping");
				/**
				 * @var RequestMapping $requestMapping
				 */
				if(is_null($requestMapping->getMethod())){
					$requestMapping->setMethod($this->container->newInstance("RequestMethod"));
				}
				$requestMapping->getMethod()->setName($name);
				$this->addMapping($requestMapping);
			}
		}
	}
}