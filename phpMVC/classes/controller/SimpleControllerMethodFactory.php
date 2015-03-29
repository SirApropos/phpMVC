<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/12/13
 * Time: 11:50 PM
 */
class SimpleControllerMethodFactory implements ControllerMethodFactory
{
	/**
	 * @var array
	 */
	private $list_ignore = array(".","..");

	/**
	 * @var IOCContainer
	 */
	private $container;

	/**
	 * @var ClassLoader
	 */
	private $classLoader;

	/**
	 * @var MVCConfig
	 */
	private $config;

	function __construct()
	{
		$this->container = IOCContainer::getInstance();
		$this->classLoader = ClassLoader::getInstance();
		$this->config = MVCConfig::getInstance();
	}


	public function getControllerMethod(HttpRequest $request)
	{
		return $this->_findControllerMethod($this->config->getControllerDir(), $request->getPath());
	}

	private function _findControllerMethod($controllerDir, $requestPath){
		$dir = opendir($controllerDir);
		$result = null;
		while($file = readdir($dir)){
			if(!in_array($file, $this->list_ignore)){
				if(is_dir($file)){
					$result = $this->_findControllerMethod($controllerDir.$file."/", $requestPath);
				}else{
					$className = $this->classLoader->getClassName($file);
					$this->classLoader->loadClass($className);
					$result = $this->_findControllerMethodInClass($className, $requestPath);
					if(!is_null($result)){
						$result->setController($this->container->resolve($className));
					}
				}
			}
			if($result){
				break;
			}
		}
		closedir($dir);
		return $result;
	}

	private function _findControllerMethodInClass($className, $requestPath) {
		$timer = Timer::create("Controller $className", "controller");
		$clazz = new ReflectionClass($className);
		$method = null;
		if ($clazz->isInstantiable()) {
			$mappings = ReflectionUtils::getMapping($className, "ControllerMapping")->getMappings();
			$method = $this->_findControllerMethodByMapping($mappings, $requestPath);
		}
		$timer->stop();

		return $method;
	}


	/**
	 * @param $mappings
	 * @param $requestPath
	 * @return null
	 */
	private function _findControllerMethodByMapping($mappings, $requestPath) {
		$method = null;
		/**
		 * @var RequestMapping $mapping
		 */
		foreach ($mappings as $mapping) {
			$paths = $mapping->getPath();
			if (!is_array($paths)) {
				$paths = [$paths];
			}
			foreach ($paths as $path) {
				/**
				 * @var RequestMapping $mapping
				 */
				$regex = str_replace("`", "", $path);
				$regex = preg_replace("`/?\{[^\}]+\}`", "*", $regex);
				$regex = preg_replace("`([\[\]\{\}\.\(\)\?\*])`", "\\\\$1", $regex);
				$regex = str_replace("\\*\\*", ".*", $regex);
				$regex = str_replace("\\*", "/?([^/]*)", $regex);
				$regex = preg_replace("`^(.*/)$`", "$1?", $regex);
				$regex = '`^' . $regex . '$`';
				if (preg_match($regex, $requestPath)) {
					$method = new ControllerMethod();
					$method->setMapping($mapping);
					$method->setPath($path);
				}
			}
			if(!is_null($method)){
				break;
			}
		}
		return $method;
	}
}
