<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 3:47 PM
 */
class JsonUtils
{
    public static function toJson($obj){
        return json_encode(MappingUtils::getObjectVars($obj));
    }

    public static function bindJson($json, $clazz){
        $arr = (array)json_decode($json);
        if(!$arr){
            throw new JsonProcessingException("Could not read JSON.");
        }
        return MappingUtils::bindObject($arr, $clazz);
    }
}
