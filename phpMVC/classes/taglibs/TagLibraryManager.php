<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/16/13
 * Time: 10:08 PM
 */

class TagLibraryManager {

    /**
     * @var TagLibrary[]
     */
    public static $taglibs = [];

    /**
     * @var bool
     */
    private static $loaded = false;

    function __construct()
    {
        if(!self::$loaded){
            $this->loadTagLibs(Config::$TAGLIB_DIR);
            self::$loaded = true;
        }
    }

    /**
     * @param string $path
     */
    public function loadTagLibs($path){
        $dir = opendir($path);
        while($file = readdir($dir)){
            if(preg_match("`\.php`",strtolower($file))){
                include($path.$file);
            }
        }
        closedir($dir);
    }

    /**
     * @return TagLibrary
     * @param string $namespace
     * @throws NoSuchTagLibraryException
     */
    public function resolve($namespace){
        //Make sure php knows it's a string.
        //For some reason this is necessary.
        $namespace = (string)$namespace;
        if(isset(self::$taglibs[$namespace])){
            return self::$taglibs[$namespace];
        }else{
            throw new NoSuchTagLibraryException("No Tag library found for namespace: ".$namespace);
        }
    }

    /**
     * @param TagLibrary $taglib
     */
    public static function register(TagLibrary $taglib){
        self::$taglibs[$taglib->getNamespace()] = $taglib;
    }
}