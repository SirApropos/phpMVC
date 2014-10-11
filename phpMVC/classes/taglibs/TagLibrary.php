<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/16/13
 * Time: 9:32 PM
 */

abstract class TagLibrary {

	function __construct()
	{
		TagLibraryManager::register($this);
	}

	private static $mapping;

	/**
	 * @return Mapping
	 */
	public function getMapping(){
		if(!isset(self::$mapping)){
			self::$mapping = ReflectionUtils::getMapping($this, "TagLibraryMapping");
		}
		return self::$mapping;
	}

	/**
	 * @return string
	 */
	public abstract function getNamespace();
}