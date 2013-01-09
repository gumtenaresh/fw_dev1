<?php
class Dao1{

	public $conn;
	static $_instance;

	const SLOW_QUERY_LOG = true;
	const ENABLE_TRACE = false;
	const SLOW_QUERY_TIME = 1.0;

	/**
	 constructor
	 */
	private function __construct($dbname="") {

		//		error_log(__METHOD__ . ' : Constructer called');
		require_once(DOC_ROOT_PATH . '/libs/adodb/adodb.inc.php');

		$this->conn = &ADONewConnection(DB_DRIVER);
		$this->conn->debug = false;
		//$this->conn->debug = true;
		$dbname = empty($dbname)?DB_DATABASE1:$dbname;
		$this->conn->connect(DB_SERVER1, DB_USERNAME1, DB_PASSWORD1, $dbname) or die ('Cannot make connection!');

		//$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$this->conn->SetFetchMode(ADODB_FETCH_ASSOC); //Set fetch assoc by default
	}

	public static function getInstance() {
		if(!(self::$_instance instanceof self)) {
			//			error_log(__METHOD__ . ' : Constructor creating');
			self::$_instance = new self();  //Constructor should be called only once
		}else{
			//error_log(__METHOD__ . ' : Constructor reusing');
		}
		return self::$_instance;
	}

	private function __clone() {
		//keep it null, so that this object can't be created by cloning
	}

	function setFetchAssoc(){
		$this->conn->SetFetchMode(ADODB_FETCH_ASSOC);
	}

	function setFetchNum(){
		$this->conn->SetFetchMode(ADODB_FETCH_NUM);
	}

	function getConnection() {
		return $this->conn;
	}

	/**
	 executeQuery() : generic DAO function to execute the SQL query passed
	 */
	function executeQuery($sql) {
		if(self::SLOW_QUERY_LOG)  $time_start = microtime(true);

		$recordSet = $this->conn->Execute($sql);

		if(self::SLOW_QUERY_LOG){
			$time_end = microtime(true);
			$time = $time_end - $time_start;
		}

		if (!$recordSet)  {
			error_log($sql, 0, QUERY_LOG_PATH);
			error_log('Error: '.$this->conn->ErrorMsg(), 0, QUERY_LOG_PATH);
			if(self::ENABLE_TRACE)   $this->backTrace(debug_backtrace());
		} else {
			if(self::SLOW_QUERY_LOG && $time > self::SLOW_QUERY_TIME){
				error_log(' Slow Query [' . $time . ']: ' . $sql);
			}
			return $recordSet->GetArray();
		}
	}


	/**
	 executeUpdate() : generic DAO function to execute a DML statement like INSERT or UPDATE
	 */
	function executeUpdate($sql) {
		if(self::SLOW_QUERY_LOG)  $time_start = microtime(true);

		$bl = $this->conn->Execute($sql);

		if(self::SLOW_QUERY_LOG){
			$time_end = microtime(true);
			$time = $time_end - $time_start;
		}
		if ($bl === false) {
			error_log($sql, 0, QUERY_LOG_PATH);
			error_log('Error: '.$this->conn->ErrorMsg(), 0, QUERY_LOG_PATH);
			if(self::ENABLE_TRACE)   $this->backTrace(debug_backtrace());
			return false;
		} else {
			if(self::SLOW_QUERY_LOG && $time > self::SLOW_QUERY_TIME){
				error_log(' Slow Query [' . $time . ']: ' . $sql);
			}
			return true;
		}
	}

	/**
	 executePrepared() : generic DAO function to execute a prepared SQL statement
	 i.e. where parameters are given using question marks (?)
	 $parameters is the array of paramters to be passed to the SQL

	 NOTE: Prepared SQL statements can be INSERTs or UPDATEs only, no SELECTs!
	 */
	function executePrepared($sql, $parameters) {
		if(self::SLOW_QUERY_LOG)  $time_start = microtime(true);
		# prepare the SQL statement

		$statement = $this->conn->Prepare($sql);
		$bl = $this->conn->Execute($statement, $parameters);

		if(self::SLOW_QUERY_LOG){
			$time_end = microtime(true);
			$time = $time_end - $time_start;
		}

		if ($bl === false) {
			error_log($sql, 0, QUERY_LOG_PATH);//
			error_log('Error: '.$this->conn->ErrorMsg(), 0, QUERY_LOG_PATH);//
			if(self::ENABLE_TRACE)   $this->backTrace(debug_backtrace());
			return false;
		} else {
			if(self::SLOW_QUERY_LOG && $time > self::SLOW_QUERY_TIME){
				error_log(' Slow Query [' . $time . ']: ' . $sql. ' [' . serialize($parameters) . ']');
			}
			return true;
		}
	}

	function selectSql($sql, $parameters) {
		if(self::SLOW_QUERY_LOG)  $time_start = microtime(true);

		$statement = $this->conn->Prepare($sql);
		$recordSet = $this->conn->Execute($statement, $parameters);

		if(self::SLOW_QUERY_LOG){
			$time_end = microtime(true);
			$time = $time_end - $time_start;
		}

		if (!$recordSet)  {
			error_log($sql, 0, QUERY_LOG_PATH);
			error_log('Error: '.$this->conn->ErrorMsg(), 0, QUERY_LOG_PATH);
			if(self::ENABLE_TRACE)   $this->backTrace(debug_backtrace());

		} else {
			if(self::SLOW_QUERY_LOG && $time > self::SLOW_QUERY_TIME){
				error_log(' Slow Query [' . $time . ']: ' . $sql . ' [' . serialize($parameters) . ']');
			}
			return $recordSet->GetArray();
		}

	}


	/**
	 function getInsertid() to get the last insert id #Malathi#
	 */
	function getInsertId() {
		$insertid = '';
		$insertid = $this->conn->Insert_ID();
		if (!$insertid)  {
			error_log('Error: '.$this->conn->ErrorMsg(), 0, QUERY_LOG_PATH);
		} else {
			return $insertid;

		}
	}

	function getAffectedRows() {
		$rows = $this->conn->Affected_Rows();
		if ($rows === false)  {
			error_log('Error: '.$this->conn->ErrorMsg(), 0, QUERY_LOG_PATH);
		} else {
			return $rows;

		}
	}

	function backTrace($backTrace){
		for($i = 0, $cnt = count($backTrace); $i < $cnt; ++$i){
			error_log('Trace: ' . $backTrace[$i]['class'] . $backTrace[$i]['type'] . $backTrace[$i]['function'] . ' in file "' . $backTrace[$i]['file'] . '" Line: ' . $backTrace[$i]['line'] . ' Arguments: ' . serialize($backTrace[$i]['args']));
		}
	}
}