<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 11:07 PM
 */

class MappingUtils {
    /**
     * @param array $arr
     * @param $clazz
     * @return mixed
     */
    public static function bindObject(array $arr, $clazz){
        if($clazz instanceof ReflectionClass){
            $clazzName = $clazz->getName();
        }
        $mapping = ReflectionUtils::getMapping($clazzName);
        $fields = $mapping ? $mapping['fields'] : [];
        if(!$fields){
            $fields = [];
        }
        $obj = $clazz->newInstance();
        foreach($arr as $key => $value){
            if($fields[$key]){
                $value = self::bindObject($value, $fields[$key]);
            }
            ReflectionUtils::setProperty($obj, $key, $value);
        }
        return $obj;
    }
}