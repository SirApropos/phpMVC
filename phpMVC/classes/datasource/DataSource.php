<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 8/19/13
 * Time: 12:27 AM
 */

interface DataSource {
	/**
	 * @param $clazz
	 * @param $query
	 * @param array $params
	 * @return object
	 */
	public function queryForObject($clazz, $query, array $params = null);

	/**
	 * @param $clazz
	 * @param $query
	 * @param array $params
	 * @return object[]
	 */
	public function queryForList($clazz, $query, array $params = null);
	public function query($query, array $params = null);
	/**
	 * @param $query
	 * @param array $params
	 * @return array
	 */
	public function fetchSingleRow($query, array $params = null);
	/**
	 * @param $query
	 * @param array $params
	 * @return array
	 */
	public function fetchSingleAssoc($query, array $params = null);
	public function insert($table, $obj);
	public function getLog();
	public function selectDb($db);

	/**
	 * @return boolean
	 */
	public function isConnected();
}