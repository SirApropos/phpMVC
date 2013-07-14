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
}
Config::$BASE_PATH = "/mvc";
Config::$BASE_DIR = "./";
Config::$CONTROLLER_DIR = Config::$BASE_DIR."controllers/";
Config::$VIEW_DIR = Config::$BASE_DIR."views/";

