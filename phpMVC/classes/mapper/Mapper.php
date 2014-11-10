<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 11:19 PM
 */

interface Mapper {
	/**
	 * @param string $contentType
	 * @return bool
	 */
	public function canRead($contentType);

	/**
	 * @param string $contentType
	 * @return bool
	 */
	public function canWrite($contentType);

	/**
	 * @param $obj
	 * @param MappingConfiguration $config
	 * @return mixed
	 */
	public function write($obj, MappingConfiguration $config=null);

	/**
	 * @param $str
	 * @param ReflectionClass $clazz
	 * @return mixed
	 */
	public function read($str, ReflectionClass $clazz);
}