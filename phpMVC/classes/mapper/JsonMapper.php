<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 11:21 PM
 */

class JsonMapper implements Mapper {
	/**
	 * @param string $contentType
	 * @return bool
	 */
	public function canRead($contentType)
	{
		return $contentType == ContentType::APPLICATION_JSON;
	}

	/**
	 * @param string $contentType
	 * @return bool
	 */
	public function canWrite($contentType)
	{
		return $this->canRead($contentType);
	}

	/**
	 * @param object $obj
	 * @return string
	 */
	public function write($obj)
	{
		return JsonUtils::toJson($obj);
	}

	/**
	 * @param $str
	 * @param ReflectionClass $clazz
	 * @return mixed
	 */
	public function read($str, ReflectionClass $clazz)
	{
		return JsonUtils::bindJson($str, $clazz);
	}


}