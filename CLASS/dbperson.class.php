<?php

require_once("./CLASS/database.class.php");
require_once("./CLASS/objectperson.class.php");

class DBPerson extends DB {
	
	function __construct()
	{
		parent::__construct();
	}

	public function getPersons($where=null, $order=null)
	{
		$query = "SELECT * FROM xtr_person";

		if(!empty($where)) $query .= " WHERE ".$where;
		if(!empty($order)) $query .= " ORDER BY ".$order;
		else $query .= " ORDER BY lastname, firstname";

		$this->openConnection();
		// or mail($webmaster, "Extranet Error", "Discipline detail : ".$result."<br />".mysql_error(), $headers_basic);
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Discipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, Person::withReader($line));
		
		return $objectList;
	}

	public function getPerson($personid)
	{
		$query = "SELECT * FROM xtr_person WHERE personid = $personid";
// echo "<br /><br /><br /><br />";
		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Discipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
		
		return Person::withReader(mysql_fetch_array($result, MYSQL_ASSOC));
	}
	
// 	public function insertDiscipline($discipline)
// 	{
// 		$query = "INSERT INTO xtr_discipline (title, acronym, enable, responsableid) VALUES ('".$discipline->getTitle()."', '".$discipline->getAcronym()."', '".$discipline->isEnable()."', '".$discipline->getResponsableID()."')";

// 		$this->openConnection();
// 		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : INSERT FAILED (Discipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
// 		$this->closeConnection();
// 	}

	public function updatePerson($person)
	{
		$query = "UPDATE xtr_person SET lastname='".$person->getLastName()."', firstname='".$person->getFirstName()."', birth='".$person->getBirthDate()."', birthplace='".$person->getBirthPlace()."', sexe='".$person->getGender()."', niss='".$person->getNiss()."', address='".$person->getAddress()."', box='".$person->getBox()."', postal='".$person->getPostal()."', city='".$person->getCity()."', gsm='".$person->getGsm()."', phone='".$person->getPhone()."', email='".$person->getEmail()."', profession='".$person->getProfession()."', ffgid='".$person->getFfgID()."' WHERE personid = ".$person->getID();
		// echo "<br /><br /><br />".$query;
		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : UPDATE FAILED (Person) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
	}
		
}

?>