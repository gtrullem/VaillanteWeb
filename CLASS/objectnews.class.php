<?php

Class News
{
	protected $newsid;
	protected $userid;
	protected $user;
	protected $disciplineid;
	protected $discipline;
	protected $title;
	protected $textbody;
	protected $textdate;
	protected $visible;

	function __construct($newsid, $userid, $disciplineid, $title, $textbody, $textdate, $visible)
	{
		$this->newsid = $newsid;
		$this->userid = $userid;
		$this->user = null;
		$this->disciplineid = $disciplineid;
		$this->discipline = null;
		$this->title = $title;
		$this->textbody = $textbody;
		$this->textdate = $textdate;
		$this->visible = $visible;

		return $this;
	}

	public static function withReader($line)
	{
		return new News($line['newsid'], $line['userid'], null, $line['title'], $line['textbody'], $line['date'], $line['visible']);
	}

	/****************************** GETTER ******************************/
	public function getID()
	{
		return $this->newsid;
	}

	public function getUserID()
	{
		return $this->userid;
	}

	public function getUser()
	{
		if(empty($this->user)) {
			$database = new DBUsers();
			$this->user = $database->getUser($this->userid);
		}

		return $this->user;
	}

	public function getDisciplineID()
	{
		return "to implement !!!";
	}

	public function getDsicipline()
	{
		return "to implement !!!";
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getTextBody()
	{
		return $this->textbody;
	}

	public function getTextDate()
	{
		return $this->textdate;
	}

	public function isVisible()
	{
		return $this->visible;
	}

	/****************************** SETTER ******************************/
}

?>