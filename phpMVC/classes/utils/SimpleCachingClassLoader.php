<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 9/3/13
 * Time: 10:42 PM
 */

class SimpleCachingClassLoader extends ClassLoader{
	private $cache;

	private $cachefile;

	function __construct($cachefile)
	{
		parent::__construct();

		$this->cachefile = $cachefile;
		if(is_null($cachefile) || !file_exists($cachefile)){
			$this->_buildCache();
		}else{
			$this->_loadCache();
		}
	}

	function loadClass($name)
	{
		$result = true;
		if(!$this->classExists($name)){
			if(!$this->loadInternal($name)){
				//Can't find the class. Try rebuilding the cache.
				$this->_buildCache();
				//Can we find it now?
				if(!$this->loadInternal($name)){
					$result = false;
				}
			}
		}
		return $result;
	}

	protected function loadInternal($name){
		$result = false;
		foreach($this->cache as $subcache){
			if(isset($subcache[$name])){
				$file = $subcache[$name];
				$result = true;
				if(file_exists($file)){
					include_once($file);
				}else{
					$this->_buildCache();
				}
			}
		}
		return $result;
	}

	protected function getDirectories(){
		return array("models" => $this->getConfig()->getModelDir(),
			"classes" => $this->getConfig()->getClassesDir(),
			"controllers" =>$this->getConfig()->getControllerDir(),
			"taglibs" => $this->getConfig()->getTaglibDir(),
			"views" => $this->getConfig()->getViewDir(),
			"base_dir" => $this->getConfig()->getBaseDir());
	}

	private function _buildCache(){
		$timer = Timer::create("Building Cache","caching");
		$cache = array();
		$directories = $this->getDirectories();
		foreach($directories as $key => $dir){
			$cache[$key] = $this->_loadClasses($dir);
		}
		if(!is_null($this->cachefile)){
			file_put_contents($this->cachefile, serialize($cache));
		}
		$this->cache = $cache;
		$timer->stop();
	}

	private function _loadCache(){
		$this->cache = unserialize(file_get_contents($this->cachefile));
	}

	/**
	 * @param $path
	 * @return array
	 */
	private function _loadClasses($path){
		$list_ignore = array(".","..");
		$classes = array();
		$dir = opendir($path);
		while($file = readdir($dir)){
			$filepath = $path.$file;
			if(in_array($file, $list_ignore)){
				continue;
			}
			if(is_dir($filepath)){
				foreach($this->_loadClasses($filepath."/") as $key => $value){
					$classes[$key] = $value;
				}
			}else{
				try{
					$contents = file_get_contents($filepath);
					$matches = array();
					preg_match_all("/(class|interface|trait) ([a-zA-Z0-9]+)/",
						$contents, $matches, PREG_SET_ORDER);
					foreach($matches as $match){
						$classes[$match[2]] = $filepath;
					}
				}catch(Exception $ex){}
			}
		}
		closedir($dir);
		return $classes;
	}
}