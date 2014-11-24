<?php
/**
 * User: Apropos (sir.apropos.of.nothing@gmail.com)
 * Date: 11/8/2014
 * Time: 5:36 PM
 */

class MappingConfiguration {

	const INCLUSION_NULL = 0;
	const INCLUSION_NOT_NULL = 1;

	private $inclusion = self::INCLUSION_NULL;

	private $debug = false;

	public function __construct(MappingConfiguration $config=null){
		if(!is_null($config)){
			$this->inclusion = $config->inclusion;
		}
	}

	public function withInclusion($inclusion){
		$config = clone $this;
		$config->inclusion = $inclusion;
		return $config;
	}

	function __clone() {
		return new MappingConfiguration($this);
	}

	/**
	 * @return int
	 */
	public function getInclusion() {
		return $this->inclusion;
	}

	/**
	 * @param int $inclusion
	 */
	public function setInclusion($inclusion) {
		$this->inclusion = $inclusion;
	}

	/**
	 * @return boolean
	 */
	public function isDebug() {
		return $this->debug;
	}

	/**
	 * @param boolean $debug
	 */
	public function setDebug($debug) {
		$this->debug = $debug;
	}
}