<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 3:47 PM
 */
trait JsonUtils
{
    public function toJson($obj){
        return json_encode($obj);
    }

    public function bindJson($json, $clazz){
        $arr = json_decode($json);
        return MappingUtils::bindObject($arr, $clazz);
    }
}
