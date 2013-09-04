<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 12:42 AM
 */
class SimpleClassLoader extends ClassLoader
{
    private $list_ignore = [".","..",".htaccess","index.php"];

	function __construct()
	{
		parent::__construct($this);
	}

	/**
	 * @param $name
	 * @param null $path
	 * @return bool
	 */
	function loadClass($name, $path=null){
        if(is_null($path)){
            $path = Config::$BASE_DIR;
        }
        $dir = opendir($path);
        $result = false;
        while($file = readdir($dir)){
            if(in_array($file,$this->list_ignore)){
                continue;
            }
            $filepath = $path.$file;
            if(is_dir($filepath)){
                $result = $this->loadClass($name, $path.$file."/");
            }else{
                if($this->getClassName($file) == $name){
                    $this->loadFile($file, $path);
                    $result = true;
                }
            }
            if($result){
                break;
            }
        }
        closedir($dir);
        return $result;
    }

    private function loadFile($file, $dir=null){
        if(!$dir){
            $dir = Config::$BASE_DIR;
        }
        $name = $this->getClassName($file);
        if(!$this->classExists($name)){
            $path = $dir.$file;
            include($path);
            if(!$this->classExists($name)){
                throw new AutoloadingException($name, $path);
            }
        }
    }
}
