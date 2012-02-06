<?php

require_once("./CLASS/dbusers.class.php");
require_once("./CLASS/dbplace.class.php");

Class Event {
	protected $id;
	protected $placeid;
	protected $place;
	protected $contactid;
	protected $contact;
	protected $title;
	protected $information;
	protected $beginDate;
	protected $startTime;
	protected $endDate;
	protected $endTime;
	protected $eventType;
	protected $type;
	protected $price;

	function __construct($eventid, $placeid, $personid, $title, $information, $beginDate, $startTime, $endDate, $endTime, $eventType, $type, $eventPrice)
	{
		$this->id = $eventid;
		$this->placeid = $placeid;
		$this->place = null;
		$this->contactid = $personid;
		$this->contact = null;
		$this->title = $title;
		$this->information = $information;
		$this->beginDate = $beginDate;
		$this->startTime = $startTime;
		$this->endDate = $endDate;
		$this->endTime = $endTime;
		$this->eventType = $eventType;
		$this->type = $type;
		$this->price = $eventPrice;

		return $this;
	}

	public function withReader($line)
	{
		return (new Event($line['eventid'], $line['placeid'], $line['personid'], $line['title'], $line['information'], $line['dbegin'], $line['startTime'], $line['endDate'], $line['endTime'], $line['event_type'], $line['type'], $line['eventPrice']));
	}

	/****************************** GETTER ******************************/
	function getID()
	{
		return $this->id;
	}
	
	function getPlaceID()
	{
		return $this->placeid;
	}

	function getPlace()
	{
		if(empty($this->place)) {
			$database = new DBPlace();
			$this->place = $database->getPlace($this->placeid);
		}

		return $this->place;
	}

	function getContactID()
	{
		return $this->personid;
	}

	function getContact()
	{
		if(empty($this->contact)) {
			$database = new DBUsers();
			$this->contact = $database->getUser($this->contactid);
		}

		return $this->contact;
	}
	
	function getTitle()
	{
		return $this->title;
	}

	function getInformation()
	{
		return $this->information;
	}
	
	function getBeginDate()
	{
		return $this->beginDate;
	}

	function getStartTime()
	{
		return $this->startTime;
	}

	function getEndDate()
	{
		return $this->endDate;
	}

	function getEndTime()
	{
		return $this->endTime;
	}

	function getEventType()
	{
		return $this->eventType;
	}

	function getType()
	{
		return $this->type;
	}

	function getPrice()
	{
		return $this->price;
	}

	/****************************** SETTER ******************************/
	function setID($id)
	{
		$this->id = $id;
	}
	
	/****************************** TOOLS ******************************/
	public function displayBeginDate()
	{
		return substr($this->beginDate, 8, 2)."-".substr($this->beginDate, 5, 2)."-".substr($this->beginDate, 0, 4);
	}

	public function displayEndDate()
	{
		return substr($this->endDate, 8, 2)."-".substr($this->endDate, 5, 2)."-".substr($this->endDate, 0, 4);
	}
}

?>