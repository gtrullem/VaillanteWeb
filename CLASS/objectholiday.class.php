<?php

require_once("./CLASS/dbplace.class.php");

Class Holiday {
	protected $id;			// holidayid
	protected $begindate;	// holiday_begin
	protected $enddate;		// holiday_end
	protected $information;	// informations
	protected $hallid;		// list <placeid>
	protected $hall;		// liste <place>

	function __construct($holidayid, $begindate, $enddate, $information)
	{
		$this->id = $holidayid;
		$this->begindate = $begindate;
		$this->enddate = $enddate;
		$this->information = $information;
		$this->hallid = null;
		$this->hall = null;

		return $this;
	}

	public function withReader($line)
	{
		return (new Holiday($line['holidayid'], $line['holidate_begin'], $line['holidate_end'], $line['information']));
	}

	/****************************** GETTER ******************************/
	function getID()
	{
		return $this->id;
	}
	
	function getBeginDate()
	{
		return $this->begindate;
	}

	function getEndDate()
	{
		return $this->enddate;
	}
	
	function getInformation()
	{
		return $this->information;
	}

	function getHallID()
	{
		if(empty($this->hallid)) {
			$database = new DBPlace();
			$this->hallid = $database->getHallIDForHoliday($this->id);
		}

		return $this->hallid;
	}
	
	function getHall()
	{
		if(empty($this->hall)) {
			$database = new DBPlace();
			$this->hall = $database->getHallForHoliday($this->getHallID());
		}

		return $this->hall;
	}

	/****************************** SETTER ******************************/
	function setID($holidayid)
	{
		$this->id = $holidayid;
	}

	function setHallID($hallid)
	{
		// if(is_array($hallid)) {
		// 	for($i = 0; $i < sizeof($hallid); $i++)
		// } else
			$this->hallid = $hallid;
		
	}

	
	/****************************** TOOLS ******************************/
	function DisplayPlaces()
	{
		$string = "";
		foreach($this->getHall() as $place)
			$string .= $place->getName()."<br />";

		return substr($string, 0, -6);
	}

}

?>