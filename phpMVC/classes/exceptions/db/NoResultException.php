<?php
/**
 * User: Apropos (sir.apropos.of.nothing@gmail.com)
 * Date: 10/26/2014
 * Time: 3:12 PM
 */

class NoResultException extends DBException{
	function __construct() {
		parent::__construct("No rows were returned.");
	}
} 