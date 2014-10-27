<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/17/13
 * Time: 10:20 PM
 */

class TagLibraryMapping implements Mapping{
	private $mappings = [];
	/**
	 * @param $obj
	 * @return mixed
	 */
	function bind(ReflectionClass $class, $obj)
	{
		if(isset($obj["methods"])){
			$this->mappings = $obj["methods"];
		}
	}

	/**
	 * @return array
	 */
	function getMappings()
	{
		return $this->mappings;
	}

}