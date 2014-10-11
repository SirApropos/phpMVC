<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/15/13
 * Time: 12:32 AM
 */

class XmlUtils {
	/**
	 * @param SimpleXMLElement $element
	 * @param array $arr
	 */
	private static function bindToElement(SimpleXMLElement $element, array $arr){
		foreach($arr as $key => $obj){
			if(is_numeric($key)){
				$key = "value";
			}
			$value = null;
			if(is_object($obj)){
				$value = MappingUtils::getObjectVars($obj);
			}else if(is_array($obj)){
				$value = $obj;
			}
			if(!is_null($value)){
				$node = $element->addChild($key);
				self::bindToElement($node, $value);
			}else{
				$element->addChild($key, $obj);
			}
		}
	}

	/**
	 * @param $obj
	 * @return string
	 */
	public static function toXml($obj){
		$xml = new SimpleXMLElement("<object/>");
		self::bindToElement($xml, MappingUtils::getObjectVars($obj),"object");
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