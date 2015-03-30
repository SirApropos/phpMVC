<?php
/**
 * User: Apropos (sir.apropos.of.nothing@gmail.com)
 * Date: 10/26/2014
 * Time: 9:28 PM
 */

class HttpUnauthorizedException extends HttpException{

	function __construct($message = "Unauthorized") {
		parent::__construct($message, 401);
	}

} 