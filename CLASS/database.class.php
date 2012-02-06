<?php

class DB {
	protected $servername;
	protected $dbusername;
	protected $dbpassword;
	protected $dbname;
	public $db;

	function __construct()
	{
		$this->servername = 'localhost';// 'mysql5-6.start';
		$this->dbusername = 'root'; //'lavailla_01';
		$this->dbpassword = 'root'; //'lavailla01';
		$this->dbname = 'laailla_01';
	}

	protected function openConnection()
	{
		$this->db = mysql_connect($this->servername,$this->dbusername,$this->dbpassword) or die("Could not connect to database : " . mysql_error());
		mysql_select_db($this->dbname, $this->db)  or trigger_error("Could not select database : ".mysql_error(), E_USER_ERROR);
		mysql_query("SET NAMES 'utf8'");
	}

	protected function closeConnection()
	{
		mysql_close($this->db);
	}

	// public function getLastID()
	// {
	// 	$this->openConnection();
	// 	// $lastId = mysql_fetch_array(mysql_query("SELECT @@IDENTITY"), MYSQL_NUM);
	// 	$lastId = mysql_fetch_array(mysql_query("SELECT LAST_INSERT_ID()"), MYSQL_NUM); 
	// 	$this->closeConnection();
	// 	return $lastId[0];
	// }

	protected function getPointer()
	{
		return $this->db;
	}

	protected function deleteRecord($tablename, $recordid)
	{
		$this->openConnection();
		mysql_query("DELETE FROM $tablename WHERE ".$tablename."id = $recordid");
		$this->closeConnection();
	}
}

?>