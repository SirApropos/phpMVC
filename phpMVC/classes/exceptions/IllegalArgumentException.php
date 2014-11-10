<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/15/13
 * Time: 2:01 AM
 */

class IllegalArgumentException extends MVCException{
	function __construct($msg) {
		parent::__construct($msg);
	}
}