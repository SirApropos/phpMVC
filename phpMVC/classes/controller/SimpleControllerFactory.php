<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/12/13
 * Time: 11:50 PM
 */
class SimpleControllerFactory implements ControllerFactory
{
	/**
	 * @var HttpRequest
	 */
	private $request;

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


	function getController(HttpRequest $request)
	{
		$this->request = $request;
		$method = $this->findController($this->config->getControllerDir());
		if(!$method){
			throw new HttpNotFoundException();
		}
		return $method;
	}

	private function findController($path){
		$dir = opendir($path);
		$result = null;
		while($file = readdir($dir)){
			if(!in_array($file, $this->list_ignore)){
				if(is_dir($file)){
					$result = $this->findController($path.$file."/");
				}else{
					$clazz = $this->classLoader->getClassName($file);
					$this->classLoader->loadClass($clazz);
					$result = $this->getControllerMethod($clazz);
				}
			}
			if($result){
				break;
			}
		}
		closedir($dir);
		return $result;
	}

	private function getControllerMethod($name) {
		$timer = Timer::create("Controller $name", "controller");
		$clazz = new ReflectionClass($name);
		$method = null;
		if ($clazz->isInstantiable()) {
			$mappings = ReflectionUtils::getMapping($name, "ControllerMapping")->getMappings();
			$method = $this->_findControllerMethod($mappings, $clazz);
		}
		$timer->stop();
		if(!is_null($method)){
			if(!$this->_isMethodAllowed($method)) {
				throw new HttpMethodNotAllowedException();
			}
		}
		return $method;
	}

	/**
	 * @param $mappings
	 * @param $clazz
	 * @return ControllerMethod
	 * @throws ModelBindException
	 */
	private function _findControllerMethod($mappings, $clazz) {
		$method = null;
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
				$regex = preg_replace("`\{[^\}]+\}`", "*", $regex);
				$regex = preg_replace("`([\[\]\{\}\.\(\)\?\*])`", "\\\\$1", $regex);
				$regex = str_replace("\\*\\*", ".*", $regex);
				$regex = str_replace("\\*", "[^/]*", $regex);
				$regex = "`^" . $regex . "$`";
				if (preg_match($regex, $this->request->getPath())) {
					$method = new ControllerMethod();
					$method->setController($this->container->newInstance($clazz));
					$method->setMapping($mapping);
					$method->setPath($path);
				}
			}
		}
		return $method;
	}

	/**
	 * @param ControllerMethod $method
	 * @return bool
	 */
	private function  _isMethodAllowed($method){
		$result = true;
		$allowed_methods = $method->getMapping()->getAllowedMethods();
		if (is_array($allowed_methods)) {
			if (!in_array($this->request->getMethod(), $allowed_methods)) {
				$result = false;
			}
		}
		return $result;
	}
}
