<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/15/13
 * Time: 1:59 AM
 */

interface Mapping {
	/**
	 * @param $obj
	 * @return mixed
	 */
	function bind($obj);

	/**
	 * @return array
	 */
	function getMappings();
}