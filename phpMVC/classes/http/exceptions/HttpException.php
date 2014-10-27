<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 3:49 PM
 */
class HttpException extends MVCException
{
	function __construct($message, $responseCode=500) {
		parent::__construct($message, $responseCode);
		$this->responseCode = $responseCode;
	}
}
