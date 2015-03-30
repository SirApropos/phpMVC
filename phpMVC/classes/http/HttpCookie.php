<?php
/**
 * User: Apropos (sir.apropos.of.nothing@gmail.com)
 * Date: 3/29/2015
 * Time: 7:24 PM
 */

class HttpCookie {
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $value;

	/**
	 * @var  int
	 */
	private $expire;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var string
	 */
	private $domain;

	/**
	 * @var boolean
	 */
	private $secure;

	/**
	 * @var boolean
	 */
	private $httpOnly;

	function __construct($name, $value, $expire=0, $path=null, $domain=null, $secure=false, $httpOnly=false) {
		$this->domain = $domain;
		$this->expire = $expire;
		$this->httpOnly = $httpOnly;
		$this->name = $name;
		$this->path = $path;
		$this->secure = $secure;
		$this->value = $value;
	}


	/**
	 * @return string
	 */
	public function getDomain() {
		return $this->domain;
	}

	/**
	 * @param string $domain
	 */
	public function setDomain($domain) {
		$this->domain = $domain;
	}

	/**
	 * @return int
	 */
	public function getExpire() {
		return $this->expire;
	}

	/**
	 * @param int $expire
	 */
	public function setExpire($expire) {
		$this->expire = $expire;
	}

	/**
	 * @return boolean
	 */
	public function isHttpOnly() {
		return $this->httpOnly;
	}

	/**
	 * @param boolean $httpOnly
	 */
	public function setHttpOnly($httpOnly) {
		$this->httpOnly = $httpOnly;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * @param string $path
	 */
	public function setPath($path) {
		$this->path = $path;
	}

	/**
	 * @return boolean
	 */
	public function isSecure() {
		return $this->secure;
	}

	/**
	 * @param boolean $secure
	 */
	public function setSecure($secure) {
		$this->secure = $secure;
	}

	/**
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param string $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}


} 