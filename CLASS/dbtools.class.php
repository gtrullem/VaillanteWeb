<?php

require_once("./CLASS/database.class.php");

class DBTools extends DB {
	
	function __construct()
	{
		parent::__construct();
	}

	public function getreward($type, $level)
	{
		if($type == 1)
			$query = "SELECT value FROM xtr_reward WHERE status = 'trainer' AND level = '$level'";
		else
			$query = "SELECT value FROM xtr_reward WHERE status = 'judge' AND level = '$level'";

		$this->openConnection();
		// or mail($webmaster, "Extranet Error", "Discipline detail : ".$result."<br />".mysql_error(), $headers_basic);
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Discipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$line = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->closeConnection();
		
		return $line['value'];
	}

	public function getLabelIn($level)
	{
		$query = "SELECT label FROM xtr_userright WHERE scopein = 1 AND value = ".$level;

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (userright) !<br />$query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$status = mysql_fetch_array($result, MYSQL_NUM);
		$this->closeConnection();

		return $status[0];
	}

	public function getLabelOut($level)
	{
		$query = "SELECT label FROM xtr_userright WHERE scopeout = 1 AND value = ".$level;

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (userright) !<br />$query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$status = mysql_fetch_array($result, MYSQL_NUM);
		$this->closeConnection();

		return $status[0];
	}
}

?>