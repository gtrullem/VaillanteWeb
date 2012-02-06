<?php

require_once("./CLASS/database.class.php");
require_once("./CLASS/dbperson.class.php");
require_once("./CLASS/objectperson.class.php");

class DBUsers extends DB {
	
	function __construct()
	{
		parent::__construct();
	}

	public function getUsers($where=null, $order=null)
	{
		$query = "SELECT * FROM xtr_users, xtr_person WHERE xtr_users.personid = xtr_person.personid";

		if(!empty($where)) $query .= " AND ".$where;
		if(!empty($order)) $query .= " ORDER BY ".$order;
		else $query .= " ORDER BY lastname, firstname";

		$this->openConnection();
		// or mail($webmaster, "Extranet Error", "Discipline detail : ".$result."<br />".mysql_error(), $headers_basic);
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (User, Person) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, User::withReader($line));
		
		return $objectList;
	}

	public function getUser($userid)
	{
		$query = "SELECT * FROM xtr_users, xtr_person WHERE userid = $userid AND xtr_users.personid = xtr_person.personid";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (User, Person) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
		
		return User::withReader(mysql_fetch_array($result, MYSQL_ASSOC));
	}
	
// 	public function insertDiscipline($discipline)
// 	{
// 		$query = "INSERT INTO xtr_discipline (title, acronym, enable, responsableid) VALUES ('".$discipline->getTitle()."', '".$discipline->getAcronym()."', '".$discipline->isEnable()."', '".$discipline->getResponsableID()."')";

// 		$this->openConnection();
// 		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : INSERT FAILED (Discipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
// 		$this->closeConnection();
// 	}

	public function updateUser($user)
	{
		$database = new DBPerson();
		$database->updatePerson($user);

		$query = "UPDATE xtr_users SET username='".$user->getUserName()."', account='".$user->getAccount()."', trainerlevel='".$user->getTrainerLevel()."', judgelevel='".$user->getJudgeLevel()."', status_in='".$user->getStatusIn()."', status_out='".$user->getStatusOut()."' WHERE userid=".$user->getUserID();
		// echo "<br /><br /><br />".$query;
		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : UPDATE FAILED (User) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
	}
		
}

?>