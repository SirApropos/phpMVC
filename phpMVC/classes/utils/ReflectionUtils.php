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
        $field = self::getReflectionClass($obj)->getProperty($name);
        $field->setAccessible(true);
        return $field;
    }

    public static function getPropertyValue($obj, $name){
        return self::getProperty($obj,$name)->getValue($obj);
    }

    /**
     * @param $obj
     * @return Mapping
     */
    public static function getMapping($obj, $mappingClazz){
        if(!$mappingClazz instanceof ReflectionClass){
            $mappingClazz = new ReflectionClass($mappingClazz);
        }
        if($mappingClazz->implementsInterface("Mapping")){
            $result = $mappingClazz->newInstance();
            $clazz = self::getReflectionClass($obj);
            if($clazz->hasProperty("mapping")){
                $result->bind(self::getPropertyValue($obj,"mapping"));
            }
            return $result;
        }else{
            throw new IllegalArgumentException("Mapping class must implement Mapping interface.");
        }
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
