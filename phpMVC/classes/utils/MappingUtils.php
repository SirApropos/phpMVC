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
        $mapping = ReflectionUtils::getMapping($clazz);
        $fields = $mapping ? $mapping['fields'] : [];
        if(!$fields){
            $fields = [];
        }
        $obj = new $clazz();
        foreach($arr as $key => $value){
            if($fields[$key]){
                $value = self::bindObject($value, $fields[$key]);
            }
            ReflectionUtils::setProperty($obj, $key, $value);
        }
        return $obj;
    }
}