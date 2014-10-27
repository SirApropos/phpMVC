<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 12:08 AM
 */
class MVCException extends Exception
{
	private $responseCode;

	function __construct($message="", $responseCode=500) {
		parent::__construct($message);
		$this->responseCode = $responseCode;
	}

	/**
	 * @return int
	 */
	public function getResponseCode() {
		return $this->responseCode;
	}

	/**
	 * @param int $responseCode
	 */
	public function setResponseCode($responseCode) {
		$this->responseCode = $responseCode;
	}
}