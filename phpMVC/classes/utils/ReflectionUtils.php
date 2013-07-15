<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 6:56 PM
 */
class ReflectionUtils
{

    /**
     * @param $obj
     * @param $name
     * @return ReflectionProperty
     */
    public static function getProperty($obj, $name){
        $field = self::getReflectionClass($obj)->getProperty($name);;
        $field->setAccessible(true);
        return $field;
    }

    public static function getPropertyValue($obj, $name){
        return self::getProperty($obj,$name)->getValue($obj);
    }

    public static function getMapping($obj){
        $clazz = self::getReflectionClass($obj);
        return $clazz->hasProperty("mapping") ? self::getPropertyValue($obj, "mapping") : null;
    }

    public static function setProperty($obj, $field, $value){
        $prop = self::getProperty($obj, $field);
        $prop->setValue($obj, $value);
    }

    /**
     * @param $obj
     * @return ReflectionClass
     */
    private static function getReflectionClass($obj){
        $clazz = is_object($obj) ? get_class($obj) : $obj;
        $clazz = new ReflectionClass($clazz);
        return $clazz;
    }
}
