<?php
/**
 * User: Apropos (sir.apropos.of.nothing@gmail.com)
 * Date: 10/26/2014
 * Time: 1:52 PM
 */

class DBException extends MVCException{

	function __construct($msg) {
		parent::__construct("A database error occurred: ".$msg, 500);
	}
}