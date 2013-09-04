<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/12/13
 * Time: 11:49 PM
 */
class Config
{
    public static $BASE_PATH;
    public static $BASE_DIR;

    public static $CONTROLLER_DIR;

    public static $VIEW_DIR;

    public static $CLASSES_DIR;

	public static $MODELS_DIR;

    public static $TAGLIB_DIR;
}
Config::$BASE_PATH = "/phpMVC/";
Config::$BASE_DIR = "./";
Config::$CONTROLLER_DIR = Config::$BASE_DIR."controllers/";
Config::$VIEW_DIR = Config::$BASE_DIR."views/";
Config::$CLASSES_DIR = Config::$BASE_DIR."classes/";
Config::$TAGLIB_DIR = Config::$CLASSES_DIR."taglibs/impl/";
Config::$MODELS_DIR = Config::$BASE_DIR."models/";
//include "./classes/utils/SimpleClassLoader.php";
//new SimpleClassLoader();
include "./classes/utils/CachingClassLoader.php";
new CachingClassLoader(Config::$BASE_DIR."classes.cache");