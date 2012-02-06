<?php

require_once("./CLASS/dbsubdiscipline.class.php");
require_once("./CLASS/dbplace.class.php");

Class Course {
	protected $linkid;
	protected $courseid;
	protected $subdisciplineid;
	protected $subdiscipline;
	protected $hallid;
	protected $hall;
	protected $day;
	protected $daynumber;
	protected $beginhour;
	protected $endhour;
	protected $nbhour;
	protected $active;
	protected $tarification;

	protected $linkCourseSeasonid;
	protected $seasonid;
	protected $seasonLabel;

	protected $gymList;
	protected $trainerList;
	protected $seasonList;

	function __construct($linkid, $courseid, $subdisciplineid, $hallid, $day, $daynumber, $beginhour, $endhour, $nbhour, $active, $tarification, $seasonid)
	{
		$this->linkid = $linkid;
		$this->courseid = $courseid;
		$this->subdisciplineid = $subdisciplineid;
		$this->subdiscipline = null;
		$this->hallid = $hallid;
		$this->hall = null;
		$this->day = $day;
		$this->daynumber = $daynumber;
		$this->beginhour = $beginhour;
		$this->endhour = $endhour;
		$this->nbhour = $nbhour;
		$this->active = $active;
		$this->tarification = $tarification;

		$this->seasonid = $seasonid;
		$this->seasonLabel = null;

		$this->gymList = null;
		$this->trainerList = null;
		$this->seasonList = null;

		return $this;
	}

	public function withReader($line)
	{
		return (new Course($line['lcsid'], $line['courseid'], $line['subdisciplineid'], $line['hallid'], $line['day'], $line['daynumber'], $line['h_begin'], $line['h_end'], $line['nbhour'], $line['active'], $line['tarification'], $line['seasonid']));
	}

	/****************************** GETTER ******************************/
	function getID()
	{
		return $this->linkid;
	}

	function getLinkID()
	{
		return $this->linkid;
	}

	function getCourseID()
	{
		return $this->courseid;
	}
	
	function getSubDisciplineID()
	{
		return $this->subdisciplineid;
	}

	function getSubDiscipline()
	{
		if(empty($this->subdiscipline)) {
			$database = new DBSubDiscipline();
			$this->subdiscipline = $database->getSubDiscipline($this->subdisciplineid);
		}

		return $this->subdiscipline;
	}
	
	function getHallID()
	{
		return $this->hallid;
	}

	function getHall()
	{
		if(empty($this->hall)) {
			$database = new DBPlace();
			$this->hall = $database->getHall($this->hallid);
		}

		return $this->hall;
	}

	function getSeasonID()
	{
		return $this->seasonid;
	}
	
	function getDay()
	{
		return $this->day;
	}

	function getDayNumber()
	{
		return $this->daynumber;
	}

	function getBeginHour()
	{
		return $this->beginhour;
	}

	function getEndHour()
	{
		return $this->endhour;
	}

	function getNbHour()
	{
		return $this->nbhour;
	}

	function isActive()
	{
		return $this->active;
	}

	function getTarification()
	{
		return $this->tarification;
	}

	function getGymList()
	{
		if(empty($this->gymList)) {
			
		}

		return $this->gymList;
	}

	function getTrainerList()
	{
		if(empty($this->trainerList))	{
			
		}

		return $this->trainerList;
	}

	function getSeasonLabel()
	{
		if(empty($this->seasonLabel)) {
			require_once("./CLASS/dbcourse.class.php");
			$database = new DBCourse();
			$this->seasonLabel = $database->getSeasonLabel($this->seasonid);
		}

		return $this->seasonLabel;
	}

	/****************************** SETTER ******************************/
	function setID($id)
	{
		$this->id = $id;
	}
	
	/****************************** TOOLS ******************************/

}

?>