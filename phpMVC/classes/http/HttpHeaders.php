<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 3:59 PM
 */
class HttpHeaders
{
	/**
	 * @var array
	 */
	private $headers = [];

	/**
	 * @param $key
	 * @param $value
	 */
	public function setValue($key, $value){
		$this->headers[$key] = [$value];
	}

	/**
	 * @param string $key
	 * @param string $value
	 */
	public function addValue($key, $value){
		$this->setupKey($key);
		array_push($this->headers[$key], $value);
	}

	private function setupKey($key){
		if(!$this->headers[$key]){
			$this->headers[$key] = [];
		}
	}

	/**
	 * @param string $key
	 * @return array
	 */
	public function getValues($key){
		return isset($this->headers[$key]) ? $this->headers[$key] : [];
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getFirstValue($key){
		$arr = $this->getValues($key);
		return (sizeof($arr) > 0) ? $arr[0] : null;
	}

	/**
	 * @return array
	 */
	public function getAllHeaders(){
		return $this->headers;
	}

	public function getContentType(){
		return $this->getFirstValue("Content-Type");
	}

	public function setContentType($value){
		$this->setValue("Content-Type", $value);
	}
}
