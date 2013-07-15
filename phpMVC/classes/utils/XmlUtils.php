<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/15/13
 * Time: 12:32 AM
 */

class XmlUtils {
    public static function toXml($obj){
        $arr = (array)$obj;
        $xml = new SimpleXMLElement('<object/>');
        array_walk_recursive($obj, array ($xml, 'addChild'));
        return $xml->asXML();
    }

    public static function bindXml($xml, $clazz){
        $arr = (array)simplexml_load_string($xml);
        if(!$arr){
            throw new XmlProcessingException("Could not read XML.");
        }
        return MappingUtils::bindObject($arr, $clazz);
    }
}