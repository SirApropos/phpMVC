<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 1:49 PM
 */
class HttpBadRequestException extends HttpException
{
	function __construct()
	{
		$this->setResponseCode(400);
		$this->message = "Bad Request";
	}}
