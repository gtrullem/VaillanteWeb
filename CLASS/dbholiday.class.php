<?php

require_once("./CLASS/database.class.php");
require_once("./CLASS/objectholiday.class.php");

class DBHoliday extends DB {
	
	function __construct()
	{
		parent::__construct();
	}

	public function getHolidays($where=null, $order=null)
	{
		$query = "SELECT * FROM xtr_holiday";

		if(!empty($where)) $query .= " ".$where;
		if(!empty($order)) $query .= " ".$order;
		else $query .= " ORDER BY holidate_begin";

		$this->openConnection();
		// or mail($webmaster, "Extranet Error", "Discipline detail : ".$result."<br />".mysql_error(), $headers_basic);
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Holiday) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, Holiday::withReader($line));
		
		return $objectList;
	}

	public function getHoliday($holidayid)
	{
		$query = "SELECT * FROM xtr_holidaye WHERE holidayid = $holidayid";
		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Holiday) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
		
		return Holiday::withReader(mysql_fetch_array($result, MYSQL_ASSOC));
	}

	// public function getHalls($where=null, $order=null)
	// {
	// 	$query = "SELECT * FROM xtr_place WHERE isLocal = 1";

	// 	if(!empty($where)) $query .= " AND ".$where;
	// 	if(!empty($order)) $query .= " ORDER BY ".$order;
	// 	else $query .= " ORDER BY name";

	// 	$this->openConnection();
	// 	// or mail($webmaster, "Extranet Error", "Discipline detail : ".$result."<br />".mysql_error(), $headers_basic);
	// 	$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Discipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
	// 	$objectList = array();
	// 	while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
	// 		array_push($objectList, Place::withReader($line));
		
	// 	return $objectList;
	// }

	// public function getHall($placeid)
	// {
	// 	$query = "SELECT * FROM xtr_place WHERE isLocal = 1 AND placeid = $placeid";
	// 	$this->openConnection();
	// 	$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (place) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
	// 	$this->closeConnection();
		
	// 	return Place::withReader(mysql_fetch_array($result, MYSQL_ASSOC));
	// }
	
	public function insertHoliday($holiday)
	{
		$query = "INSERT INTO xtr_holiday (holidate_begin, holidate_end, information) VALUES ('".$holiday->getBeginDate()."', '".$holiday->getEndDate()."', '".$holiday->getInformation()."')";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : INSERT FAILED (Holiday) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);

		$result = mysql_query("SELECT LAST_INSERT_ID()", $this->db) or trigger_error("SQL ERROR : SELECT LAST_INSTER_ID (Holiday) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$result = mysql_fetch_array($result, MYSQL_NUM);

		$holiday->setID($result[0]);

		$hallID = $holiday->getHallID();
		$query = "INSERT INTO xtr_holidayforplace (holidayid, placeid) VALUE('";
		for($i = 0; $i < sizeof($hallID); $i++)
			$query .= $holiday->getID()."', '".$hallID[$i]."'), ";
		
		$query = substr($query, 0, -2);
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : INSERT FAILED (Holiday for place) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);

		$this->closeConnection();
	}
	
	// public function updatePlace($place)
	// {
	// 	$query = "UPDATE xtr_place SET name = '".$place->getName()."', address = '".$place->getAddress()."', postal = '".$place->getPostal()."', city = '".$place->getCity()."', country = '".$place->getCountry()."', nbkm = '".$place->getNbKm()."', isLocal = '".$place->isLocal()."' WHERE placeid = ".$place->getID();

	// 	$this->openConnection();
	// 	$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : UPDATE FAILED (Place) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
	// 	$this->closeConnection();
	// }
}

?>