<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 12:42 AM
 */
trait ClassLoader
{
    private $list_ignore = [".","..",".htaccess","index.php"];

    function findClass($name, $path=null){
        if(!$path){
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
                $result = $this->findClass($name, $path.$file."/");
            }else{
                if($this->getClassName($file) == $name){
                    $this->loadClass($file, $path);
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

    function loadClass($file, $dir=null){
        if(!$dir){
            $dir = Config::$BASE_DIR;
        }
        $name = $this->getClassName($file);
        if(!$this->exists($name)){
            $path = $dir.$file;
            include($path);
            if(!$this->exists($name)){
                throw new AutoloadingException($name, $path);
            }
        }
    }

    function getClassName($filename){
        return preg_replace("/^(.+?)\..+/","$1",$filename);
    }

    function exists($name){
        return class_exists($name, false) || interface_exists($name, false) || trait_exists($name, false);
    }
}
