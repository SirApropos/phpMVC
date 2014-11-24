<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 11:22 PM
 */

class XmlMapper extends ObjectMapper {
	/**
	 * @param object $obj
	 * @return string
	 */
	public function write($obj, MappingConfiguration $config=null)
	{
		return XmlUtils::toXml($obj, $config);
	}

	/**
	 * @param $str
	 * @param ReflectionClass $clazz
	 * @return mixed
	 */
	public function read($str, ReflectionClass $clazz)
	{
		return XmlUtils::toXml($str, $clazz);
	}

	protected function getAllowedContentTypes() {
		return [ContentType::TEXT_HTML, ContentType::TEXT_XML];
	}
}