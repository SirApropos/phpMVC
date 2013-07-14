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
        $clazz = is_object($obj) ? get_class($obj) : $obj;
        $clazz = new ReflectionClass($clazz);
        $field = $clazz->getProperty($name);;
        $field->setAccessible(true);
        return $field;
    }

    public static function getPropertyValue($obj, $name){
        return self::getProperty($obj,$name)->getValue($obj);
    }

    public static function getMapping($obj){
        return self::getPropertyValue($obj, "mapping");
    }

    public static function setProperty($obj, $field, $value){
        $prop = self::getProperty($obj, $field);
        $prop->setValue($obj, $field);
    }
}
