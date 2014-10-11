<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/14/13
 * Time: 1:05 AM
 */

class ModelBindException extends HttpBadRequestException {

	function __construct($message)
	{
		$this->setResponseCode(400);
		$this->message = "Could not bind model: ".$message;
	}
}