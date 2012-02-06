<?php

require_once("./CLASS/database.class.php");
require_once("./CLASS/objectnews.class.php");

class DBNews extends DB {
	
	function __construct()
	{
		parent::__construct();
	}

	public function getNews($where=null, $order=null, $limit=null)
	{
		$query = "SELECT * FROM xtr_news";

		if(!empty($where)) $query .= " WHERE ".$where;
		if(!empty($limit)) $query .= " LIMIT ".$limit;
		if(!empty($order)) $query .= " ORDER BY ".$order;
		else $query .= " ORDER BY date DESC";

		$this->openConnection();
		// or mail($webmaster, "Extranet Error", "Discipline detail : ".$result."<br />".mysql_error(), $headers_basic);
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (News) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, News::withReader($line));
		
		return $objectList;
	}

	public function getNew($newsid)
	{
		$query = "SELECT * FROM xtr_news WHERE newsid = $newsid";
		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (News) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
		
		return News::withReader(mysql_fetch_array($result, MYSQL_ASSOC));
	}
	
	public function insertNews($news)
	{
		$query = "INSERT INTO xtr_news (userid, title, textbody, date, visible) VALUES ('".$news->getUserID()."', '".$news->getTitle()."', '".$news->getTextBody()."', '".$news->getTextDate()."', '".$news->isVisible()."')";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : INSERT FAILED (Place) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
	}
	
	public function updateNews($news)
	{
		$query = "UPDATE xtr_place SET name = '".$place->getName()."', address = '".$place->getAddress()."', postal = '".$place->getPostal()."', city = '".$place->getCity()."', country = '".$place->getCountry()."', nbkm = '".$place->getNbKm()."', isLocal = '".$place->isLocal()."' WHERE placeid = ".$place->getID();

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : UPDATE FAILED (Place) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
	}	
}

?>