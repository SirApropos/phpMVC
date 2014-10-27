<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/15/13
 * Time: 1:49 AM
 */

class ModelMapping implements Mapping {

	/**
	 * @var FieldMapping[]
	 */
	private $mappings = [];
	/**
	 * @param $obj
	 * @return mixed
	 */
	function bind(ReflectionClass $clazz, $obj)
	{
		if(isset($obj['fields'])){
			foreach($obj['fields'] as $name => $mapping){
				$mapping = MappingUtils::bindObject($mapping, "FieldMapping");
				$this->mappings[$name] = $mapping;
			}
		}
	}

	/**
	 * @return FieldMapping[]
	 */
	public function getMappings()
	{
		return $this->mappings;
	}


}