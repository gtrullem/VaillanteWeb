<?php

require_once("./CLASS/dbusers.class.php");

Class Discipline {
	protected $id;
	protected $title;
	protected $acronym;
	protected $active;
	protected $responsableid;
	protected $responsable;

	public function __construct($disciplineid, $title, $acronym, $active, $responsableid)
	{
		$this->id = $disciplineid;
		$this->title = $title;
		$this->acronym = $acronym;
		$this->active = $active;
		$this->responsableid = $responsableid;
		$this->responsable = null;

		return $this;
	}

	function withReader($line)
	{
		return (new Discipline($line['disciplineid'], $line['title'], $line['acronym'], $line['active'], $line['responsableid']));
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

	function getResponsableID()
	{
		return $this->responsableid;
	}

	function getResponsable()
	{
		if(empty($this->responsable)) {
			$database = new DBUsers();
			$this->responsable = $database->getUser($this->responsableid);
		}

		return $this->responsable;
	}

	/****************************** SETTER ******************************/
	function setID($id)
	{
		$this->id = $id;
	}
	
	/****************************** TOOLS ******************************/

}

?>