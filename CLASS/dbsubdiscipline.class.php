<?php

require_once("./CLASS/database.class.php");
require_once("./CLASS/objectsubdiscipline.class.php");

class DBSubDiscipline extends DB {
	
	function __construct()
	{
		parent::__construct();
	}

	public function getSubDisciplines($where=null, $order=null)
	{
		$query = "SELECT * FROM xtr_subdiscipline";

		if(!empty($where)) $query .= " WHERE ".$where;
		if(!empty($order)) $query .= " ORDER BY ".$order;
		else $query .= " ORDER BY title";

		$this->openConnection();
		// or mail($webmaster, "Extranet Error", "Discipline detail : ".$result."<br />".mysql_error(), $headers_basic);
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (SubDiscipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, SubDiscipline::withReader($line));
		
		return $objectList;
	}

	public function getSubDiscipline($subdisciplineid)
	{
		$query = "SELECT * FROM xtr_subdiscipline WHERE subdisciplineid = $subdisciplineid";
// echo "<br /><br /><br /><br />";
		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (SubDiscipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
		
		return SubDiscipline::withReader(mysql_fetch_array($result, MYSQL_ASSOC));
	}
	
	public function insertSubDiscipline($subdiscipline)
	{
		$query = "INSERT INTO xtr_subdiscipline (title, acronym, active, disciplineid) VALUES ('".$subdiscipline->getTitle()."', '".$subdiscipline->getAcronym()."', '".$subdiscipline->isActive()."', '".$subdiscipline->getDisciplineID()."')";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : INSERT FAILED (SubDiscipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
	}

	public function updateSubDiscipline($subdiscipline)
	{
		$query = "UPDATE xtr_subdiscipline SET title = '".$subdiscipline->getTitle()."', acronym = '".$subdiscipline->getAcronym()."', active = '".$subdiscipline->isActive()."', disciplineid = '".$subdiscipline->getDisciplineID()."' WHERE subdisciplineid = ".$subdiscipline->getID();
		// echo "<br /><br /><br />".$query;
		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : UPDATE FAILED (SubDiscipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
	}
		
}

?>