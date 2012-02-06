<?php

require_once("./CLASS/database.class.php");
require_once("./CLASS/objectplace.class.php");

class DBPlace extends DB {
	
	function __construct()
	{
		parent::__construct();
	}

	public function getPlaces($where=null, $order=null)
	{
		$query = "SELECT * FROM xtr_place";

		if(!empty($where)) $query .= " ".$where;
		if(!empty($order)) $query .= " ".$order;
		else $query .= " ORDER BY name";

		$this->openConnection();
		// or mail($webmaster, "Extranet Error", "Discipline detail : ".$result."<br />".mysql_error(), $headers_basic);
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Discipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, Place::withReader($line));
		
		return $objectList;
	}

	public function getPlace($placeid)
	{
		$query = "SELECT * FROM xtr_place WHERE placeid = $placeid";
		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (place) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
		
		return Place::withReader(mysql_fetch_array($result, MYSQL_ASSOC));
	}

	public function getHalls($where=null, $order=null)
	{
		$query = "SELECT * FROM xtr_place WHERE isLocal = 1";

		if(!empty($where)) $query .= " AND ".$where;
		if(!empty($order)) $query .= " ORDER BY ".$order;
		else $query .= " ORDER BY name";

		$this->openConnection();
		// or mail($webmaster, "Extranet Error", "Discipline detail : ".$result."<br />".mysql_error(), $headers_basic);
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Discipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, Place::withReader($line));
		
		return $objectList;
	}

	public function getHall($placeid)
	{
		$query = "SELECT * FROM xtr_place WHERE isLocal = 1 AND placeid = $placeid";
		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (place) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
		
		return Place::withReader(mysql_fetch_array($result, MYSQL_ASSOC));
	}
	
	public function insertPlace($place)
	{
		$query = "INSERT INTO xtr_place (name, address, postal, city, country, nbkm, isLocal) VALUES ('".$place->getName()."', '".$place->getAddress()."', '".$place->getPostal()."', '".$place->getCity()."', '".$place->getCountry()."', '".$place->getNbKm()."', '".$place->isLocal()."')";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : INSERT FAILED (Place) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
	}
	
	public function updatePlace($place)
	{
		$query = "UPDATE xtr_place SET name = '".$place->getName()."', address = '".$place->getAddress()."', postal = '".$place->getPostal()."', city = '".$place->getCity()."', country = '".$place->getCountry()."', nbkm = '".$place->getNbKm()."', isLocal = '".$place->isLocal()."' WHERE placeid = ".$place->getID();

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : UPDATE FAILED (Place) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
	}

	public function getHallForHoliday($hallid) // <vector> hallid
	{
		$query = "SELECT * FROM xtr_place WHERE placeid IN (".implode(",", $hallid).") ORDER BY name";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Place) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, Place::withReader($line));
		
		return $objectList;
	}

	public function getHallIDForHoliday($holidayid)
	{
		$query = "SELECT placeid FROM xtr_holidayforplace WHERE holidayid = $holidayid";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Holiday Place) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);

		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, $line['placeid']);

		$this->closeConnection();

		return $objectList;
	}
}

?>