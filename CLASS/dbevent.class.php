<?php

require_once("./CLASS/database.class.php");
require_once("./CLASS/objectevent.class.php");

class DBEvent extends DB {
	
	function __construct()
	{
		parent::__construct();
	}

	public function getEvents($where=null, $order=null)
	{
		$query = "SELECT * FROM xtr_event";

		if(!empty($where)) $query .= " ".$where;
		if(!empty($order)) $query .= " ".$order;
		else $query .= " ORDER BY title";

		$this->openConnection();
		// or mail($webmaster, "Extranet Error", "Discipline detail : ".$result."<br />".mysql_error(), $headers_basic);
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Event) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, Event::withReader($line));
		
		return $objectList;
	}

	public function getEventsFromSeason($season)
	{
		$query = "SELECT * FROM xtr_event WHERE dbegin <= '".substr($season, 5, 4)."-07-31' AND dend >= '".substr($season, 0, 4)."-08-01' ORDER BY xtr_event.dbegin";
		$this->openConnection();
		// or mail($webmaster, "Extranet Error", "Discipline detail : ".$result."<br />".mysql_error(), $headers_basic);
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Event) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, Event::withReader($line));
		
		return $objectList;
	}

	public function getEvent($eventid)
	{
		$query = "SELECT * FROM xtr_event WHERE eventid = $eventid";
		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (event) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
		
		return Event::withReader(mysql_fetch_array($result, MYSQL_ASSOC));
	}

	public function getHalls($where=null, $order=null)
	{
		$query = "SELECT * FROM xtr_event WHERE isLocal = 1";

		if(!empty($where)) $query .= " AND ".$where;
		if(!empty($order)) $query .= " ORDER BY ".$order;
		else $query .= " ORDER BY name";

		$this->openConnection();
		// or mail($webmaster, "Extranet Error", "Discipline detail : ".$result."<br />".mysql_error(), $headers_basic);
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Discipline) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, Event::withReader($line));
		
		return $objectList;
	}

	public function getHall($eventid)
	{
		$query = "SELECT * FROM xtr_event WHERE isLocal = 1 AND eventid = $eventid";
		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (event) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
		
		return Event::withReader(mysql_fetch_array($result, MYSQL_ASSOC));
	}
	
	public function insertEvent($event)
	{
		$query = "INSERT INTO xtr_event (name, address, postal, city, country, nbkm, isLocal) VALUES ('".$event->getName()."', '".$event->getAddress()."', '".$event->getPostal()."', '".$event->getCity()."', '".$event->getCountry()."', '".$event->getNbKm()."', '".$event->isLocal()."')";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : INSERT FAILED (Event) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
	}
	
	public function updateEvent($event)
	{
		$query = "UPDATE xtr_event SET name = '".$event->getName()."', address = '".$event->getAddress()."', postal = '".$event->getPostal()."', city = '".$event->getCity()."', country = '".$event->getCountry()."', nbkm = '".$event->getNbKm()."', isLocal = '".$event->isLocal()."' WHERE eventid = ".$event->getID();

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : UPDATE FAILED (Event) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
	}

	public function getHallForHoliday($hallid) // <vector> hallid
	{
		$query = "SELECT * FROM xtr_event WHERE eventid IN (".implode(",", $hallid).") ORDER BY name";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Event) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, Event::withReader($line));
		
		return $objectList;
	}

	public function getHallIDForHoliday($holidayid)
	{
		$query = "SELECT eventid FROM xtr_holidayforevent WHERE holidayid = $holidayid";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Holiday Event) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);

		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, $line['eventid']);

		$this->closeConnection();

		return $objectList;
	}
}

?>