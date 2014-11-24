<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 8/19/13
 * Time: 12:30 AM
 */

class MysqliDataSource implements DataSource{
	/**
	 * @var mysqli
	 */
	private $mysqli;

	/**
	 * @var array
	 */
	private $queries = array();

	public function __construct($server=null, $username=null, $password=null, $db=null, $port = "3306"){
		if(!is_null($server)){
			$this->connect($server, $username, $password, $db, $port);
		}
	}

	/**
	 * @param null $server
	 * @param null $username
	 * @param null $password
	 * @param string $db
	 * @param int $port
	 */
	public function connect($server=null, $username=null, $password=null, $db="", $port=3306){
		$this->mysqli = new mysqli($server, $username, $password, $db, $port);
	}


	/**
	 * @param $clazz
	 * @param $query
	 * @param array $params
	 * @return object
	 */
	public function queryForObject($clazz, $query, array $params = null)
	{
		return MappingUtils::bindObject($this->fetchSingleAssoc($query, $params), $clazz);
	}

	public function queryForList($clazz, $query, array $params = null) {
		$result = [];
		$mysqli_result = $this->query($query, $params);
		while($row = $mysqli_result->fetch_assoc()){
			$result[] = MappingUtils::bindObject($row, $clazz);
		}
		return $result;
	}

	/**
	 * @param $query
	 * @param array $params
	 * @return mysqli_result
	 */
	public function query($query, array $params = null)
	{
		return $this->_executeQuery($query, $params);
	}

	/**
	 * @param $query
	 * @param array $params
	 * @return array
	 */
	public function fetchSingleRow($query, array $params = null)
	{
		return $this->_failOnNoResult($this->query($query, $params))->fetch_row();
	}

	/**
	 * @param $query
	 * @param array $params
	 * @return array
	 */
	public function fetchSingleAssoc($query, array $params = null)
	{
		return $this->_failOnNoResult($this->query($query, $params))->fetch_assoc();
	}

	/**
	 * @param mysqli_result $resource
	 * @return mysqli_result
	 * @throws NoResultException
	 */
	private function _failOnNoResult($resource){
		if(!$resource->num_rows > 0){
			throw new NoResultException();
		}
		return $resource;
	}

	/**
	 * @param $table
	 * @param $obj
	 * @return mysqli_result
	 */
	public function insert($table, $obj)
	{
		$query = "INSERT INTO `".$table.'` (`';
		$arr = is_array($obj) ? $obj : MappingUtils::getObjectVars($obj);
		$query .= implode("`,`",array_keys($arr));
		$query .= "`) VALUES(:".implode(",:",array_keys($arr));
		$query .= ")";
		return $this->query($query, $arr);
	}

	/**
	 * @return boolean
	 */
	public function isConnected()
	{
		return $this->mysqli->ping();
	}

	/**
	 * @param $query
	 * @param array $params
	 * @return mysqli_result
	 */
	private function _executeQuery($query, array $params=null){
		$this->_logQuery($query, $params);
		$i = sizeof($this->queries);
		$timer = Timer::create("Query $i", "queries");
		if(!is_null($params)){
			$result = $this->_executePreparedStatement($query, $params);
		}else{
			$result = $this->mysqli->query($query);
		}
		$timer->stop();
		return $result;
	}

	private function _executePreparedStatement($query, array $params){
		$replacers = array();
		$split = preg_split("/:((?:[a-zA-Z0-9]+[a-zA-Z0-9_]+)?[a-zA-Z0-9]+)/", $query, -1, PREG_SPLIT_DELIM_CAPTURE);
		$types = "";
		for($i=1;$i<sizeof($split);$i=$i+2) {
			$key = $split[$i];
			if (!isset($params[$key])) {
				continue;
			}
			$var = $params[$key];
			$types .= $this->_getType($var);
			array_push($replacers, $var);
			$split[$i] = "?";
		}
		$query = implode("",$split);
		$ps = $this->_prepareQuery($query);
		if(!$ps){
			die($this->mysqli->error);
		}
		array_unshift($replacers, $types);
		foreach($replacers as $key => $value){
			$args[$key] = &$replacers[$key];
		}

		call_user_func_array(array($ps, "bind_param"), $args);
		try {
			$ps->execute();
		}catch(Exception $ex){
			throw new DBException("An error occured when executing prepared statement: ".$ex->getMessage());
		}
		return $ps->get_result();
	}

	/**
	 * @param $var
	 * @return string
	 */
	private function _getType($var){
		if(is_int($var)){
			$type = "i";
		}else if(is_string($var)){
			$type = "s";
		}else if(is_numeric($var)){
			$type = "d";
		}else{
			$type = "b";
		}
		return $type;
	}

	/**
	 * @param $query
	 * @return mysqli_stmt
	 * @throws DBException
	 */
	private function _prepareQuery($query){
		$result = $this->mysqli->prepare($query);
		if(!$result){
			throw new DBException("An error occurred with the query: ".$query."\nError:".$this->mysqli->error);
		}
		return $result;
	}

	private function _logQuery($query, array $params = null){
		$log = $query;
		if(!is_null($params)){
			$paramString = @implode(", ",$params);
			$log .= " [".$paramString."]";
		}
		$this->queries[] = $log;
	}

	public function getLog()
	{
		return $this->queries;
	}

	public function selectDb($db){
		return $this->mysqli->select_db($db);
	}

	public function getInsertId(){
		return $this->mysqli->insert_id;
	}

}