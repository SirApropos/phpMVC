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
		spl_autoload_register(function($name){
			/**
			 * @var ClassLoader $classLoader
			 */
			$classLoader = IOCContainer::getInstance()->resolve("ClassLoader");
			$timer = Timer::create("Autoloading $name","autoloading");
			if($classLoader->loadClass($name)){
				$timer->stop();
				return;
			}
			throw new AutoloadingException($name);
		});
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
	 * @return bool
	 */
	abstract function loadClass($name);

	/**
	 * @param $name
	 * @return bool
	 */
	public function classExists($name){
		return class_exists($name, false) || interface_exists($name, false) || trait_exists($name, false);
	}

}