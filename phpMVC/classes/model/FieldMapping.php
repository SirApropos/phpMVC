<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/15/13
 * Time: 2:03 AM
 */

class FieldMapping {
	/**
	 * @var string
	 */
	private $type;
	/**
	 * @var bool
	 */
	private $isArray;

	/**
	 * @var bool
	 */
	private $autowired;

	/**
	 * @param boolean $isArray
	 */
	public function setIsArray($isArray)
	{
		$this->isArray = $isArray;
	}

	/**
	 * @return boolean
	 */
	public function getIsArray()
	{
		return $this->isArray;
	}

	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param boolean $autowired
	 */
	public function setAutowired($autowired)
	{
		$this->autowired = $autowired;
	}

	/**
	 * @return boolean
	 */
	public function getAutowired()
	{
		return $this->autowired;
	}

}