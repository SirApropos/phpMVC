<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 8/19/13
 * Time: 12:27 AM
 */

interface DataSource {
	public function queryForObject($clazz, $query, array $params = null);
	public function query($query, array $params = null);
	public function fetchRow($query, array $params = null);
	public function fetchAssoc($query, array $params = null);
	public function insert($table, $obj);
	public function getLog();
	public function selectDb($db);

	/**
	 * @return boolean
	 */
	public function isConnected();
}