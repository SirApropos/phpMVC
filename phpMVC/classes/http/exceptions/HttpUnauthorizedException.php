<?php
/**
 * User: Apropos (sir.apropos.of.nothing@gmail.com)
 * Date: 10/26/2014
 * Time: 9:28 PM
 */

class HttpUnauthorizedException {

	function __construct() {
		parent::__construct("Unauthorized", 401);
	}

} 