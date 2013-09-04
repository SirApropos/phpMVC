<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 9/3/13
 * Time: 10:42 PM
 */

class CachingClassLoader extends ClassLoader{
	private $cache;

	private $cachefile;

	function __construct($cachefile)
	{
		parent::__construct($this);
		$this->cachefile = $cachefile;
		if(!file_exists($cachefile)){
			$this->_buildCache();
		}else{
			$this->_loadCache();
		}
	}

	function loadClass($name, $path = null)
	{
		$result = true;
		if(!$this->classExists($name)){
			if(!$this->_loadInternal($name)){
				//Can't find the class. Try rebuilding the cache.
				$this->_buildCache();
				//Can we find it now?
				if(!$this->_loadInternal($name)){
					$result = false;
				}
			}
		}
		return $result;
	}

	private function _loadInternal($name){
		$result = false;
		foreach($this->cache as $subcache){
			if(isset($subcache[$name])){
				$result = true;
				include_once($subcache[$name]);
			}
		}
		return $result;
	}

	private function _buildCache(){
		$cache = array();
		$directories = array("models" => Config::$MODELS_DIR, "classes" => Config::$CLASSES_DIR,
			"controllers" => Config::$CONTROLLER_DIR, "taglibs" => Config::$TAGLIB_DIR);
		foreach($directories as $key => $dir){
			$cache[$key] = $this->_loadClasses($dir);
		}
		file_put_contents($this->cachefile, serialize($cache));
		$this->cache = $cache;
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