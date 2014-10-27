<?php
/**
 * User: Apropos (sir.apropos.of.nothing@gmail.com)
 * Date: 10/26/2014
 * Time: 9:27 PM
 */

class HttpForbiddenException extends HttpException {

	function __construct() {
		parent::__construct("Forbidden", 403);
	}

}