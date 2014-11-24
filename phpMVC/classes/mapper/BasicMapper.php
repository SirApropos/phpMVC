<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 11:22 PM
 */

class BasicMapper extends ObjectMapper{
	private static $allowed_types = [ContentType::TEXT_HTML, ContentType::TEXT_PLAIN,
									 ContentType::APPLICATION_X_WWW_FORM_URLENCODED, ""];


	public function write($obj, MappingConfiguration $config=null)
	{
		return http_build_query(MappingUtils::getObjectVars($obj, $config));
	}

	/**
	 * @param $str
	 * @param ReflectionClass $clazz
	 * @return mixed
	 */
	public function read($str, ReflectionClass $clazz)
	{
		$vars = [];
		parse_str($str, $vars);
		return MappingUtils::bindObject($vars, $clazz);
	}

	protected function getAllowedContentTypes() {
		return self::$allowed_types;
	}
}