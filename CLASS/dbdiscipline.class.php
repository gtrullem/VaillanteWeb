<?php

require_once("./CLASS/database.class.php");
require_once("./CLASS/objectdiscipline.class.php");

class DBDiscipline extends DB {
	
	function __construct()
	{
		parent::__construct();
	}

	public function getDisciplines($where=null, $order=null)
	{
		$query = "SELECT * FROM xtr_discipline";

		if(!empty($where)) $query .= " WHERE ".$where;
		if(!empty($order)) $query .= " ORDER BY ".$order;
		else $query .= " ORDER BY title";

		$this->openConnection();
		// or mail($webmaster, "Extranet Error", "Discipline detail : ".$result."<br />".mysql_error(), $headers_basic);
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Discipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, Discipline::withReader($line));
		
		return $objectList;
	}

	public function getDiscipline($disciplineid)
	{
		$query = "SELECT * FROM xtr_discipline WHERE disciplineid = $disciplineid";
// echo "<br /><br /><br /><br />";
		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Discipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
		
		return Discipline::withReader(mysql_fetch_array($result, MYSQL_ASSOC));
	}
	
	public function insertDiscipline($discipline)
	{
		$query = "INSERT INTO xtr_discipline (title, acronym, enable, responsableid) VALUES ('".$discipline->getTitle()."', '".$discipline->getAcronym()."', '".$discipline->isEnable()."', '".$discipline->getResponsableID()."')";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : INSERT FAILED (Discipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
	}

	public function updateDiscipline($discipline)
	{
		$query = "UPDATE xtr_discipline SET title = '".$discipline->getTitle()."', acronym = '".$discipline->getAcronym()."', active = '".$discipline->isActive()."', responsableid = '".$discipline->getResponsableID()."' WHERE disciplineid = ".$discipline->getID();
		// echo "<br /><br /><br />".$query;
		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : INSERT FAILED (Discipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
	}
		
}

?>