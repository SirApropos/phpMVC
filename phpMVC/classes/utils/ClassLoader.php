<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 9/3/13
 * Time: 7:31 PM
 */

abstract class ClassLoader {
	/**
	 * @var ClassLoader
	 */
	private static $classLoader;

	/**
	 * @param ClassLoader $classLoader
	 */
	protected function __construct(ClassLoader $classLoader){
		self::$classLoader = $classLoader;
	}

	/**
	 * @return ClassLoader
	 */
	public static function getInstance(){
		return self::$classLoader;
	}

	public function getClassName($filename){
		return preg_replace("/^(.+?)\..+/","$1",$filename);
	}

	/**
	 * @param $name
	 * @param null $path
	 * @return bool
	 */
	abstract function loadClass($name, $path=null);

	/**
	 * @param $name
	 * @return bool
	 */
	public function classExists($name){
		return class_exists($name, false) || interface_exists($name, false) || trait_exists($name, false);
	}

}