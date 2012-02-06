<?php

require_once("./CLASS/dbdiscipline.class.php");
require_once("./CLASS/dbcourse.class.php");

Class SubDiscipline {
	protected $id;
	protected $title;
	protected $acronym;
	protected $active;
	protected $disciplineid;
	protected $discipline;
	protected $courseList;

	function __construct($subdisciplineid, $title, $acronym, $active, $disciplineid)
	{
		$this->id = $subdisciplineid;
		$this->title = $title;
		$this->acronym = $acronym;
		$this->active = $active;
		$this->disciplineid = $disciplineid;
		$this->discipline = null;
		$this->courseList = null;

		return $this;
	}

	public function withReader($line)
	{
		return (new SubDiscipline($line['subdisciplineid'], $line['title'], $line['acronym'], $line['active'], $line['disciplineid']));
	}

	/****************************** GETTER ******************************/
	function getID()
	{
		return $this->id;
	}
	
	function getTitle()
	{
		return $this->title;
	}

	function getAcronym()
	{
		return $this->acronym;
	}

	function isActive()
	{
		return $this->active;
	}

	function getDisciplineID()
	{
		return $this->disciplineid;
	}

	function getDiscipline()
	{
		if(empty($this->discipline)) {
			$database = new DBDiscipline();
			$this->discipline = $database->getDiscipline($this->disciplineid);
		}

		return $this->discipline;
	}

	function getCourseList()
	{
		if(empty($this->courseList)) {
			// $database = new DBPerson());
			$this->courseList = "(A VENIR)";//$database->getHall($this->responsableid);
		}

		return $this->courseList;
	}

	/****************************** SETTER ******************************/
	function setID($id)
	{
		$this->id = $id;
	}
	
	/****************************** TOOLS ******************************/

}

?>