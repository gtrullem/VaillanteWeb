<?php

require_once("./CLASS/dbtools.class.php");

class MinifiedPerson {
	protected $personid;
	protected $lastname;
	protected $firstname;
	protected $phone;
	protected $gsm;
	protected $email;

	function __construct($personid, $lastname, $firstname, $phone, $gsm, $email)
	{
		$this->personid = $personid;
		$this->lastname = $lastname;
		$this->firstname = $firstname;
		$this->phone = $phone;
		$this->gsm = $gsm;
		$this->email = $email;

		return $this;
	}

	public static function withReader($line)
	{
		return (new MinifiedPerson($line['personid'], $line['lastname'], $line['firstname'], $line['phone'], $line['gsm'], $line['email']));
	}

	/*************************************** GETTER ***************************************/
	function getID()
	{
		return $this->personid;
	}

	function getLastname()
	{
		return $this->lastname;
	}

	function getFirstname()
	{
		return $this->firstname;
	}

	function getPhone()
	{
		return $this->phone;
	}

	function displayPhone()
	{
		$test = "/^02[0-9]{7}$/";
		if($this->phone != "")		
			if(preg_match($test, $this->phone))
				return substr($this->phone, 0, 2)."/".substr($this->phone, 2, 3).".".substr($this->phone, 5, 2).".".substr($this->phone, 7, 2);
			else
				return substr($this->phone, 0, 3)."/".substr($this->phone, 3, 2).".".substr($this->phone, 5, 2).".".substr($this->phone, 7, 2);
		return null;
	}

	function getGsm()
	{
		return $this->gsm;
	}

	function displayGsm()
	{
		if($this->gsm != "")
			return substr($this->gsm, 0, 4)."/".substr($this->gsm, 4, 2).".".substr($this->gsm, 6, 2).".".substr($this->gsm, 8, 2);
		return null;
		// return $this->gsm;
	}

	function getEmail()
	{
		if($this->email != "")
			return $this->email;
		return null;
	}

	/*************************************** SETTER ***************************************/
	protected function setID($personid)
	{
		$this->personid = $personid;
	}

	protected function setLastname($lastname)
	{
		$this->lastname = $lastname;
	}

	protected function setFirstname($firstname)
	{
		$this->firstname = $fisrtname;
	}

	protected function setGender($gender)
	{
		$this->gender = $gender;
	}

	protected function setPhone($phone)
	{
		$this->phone = $phone;
	}

	protected function setGsm($gsm)
	{
		$this->gsm = $gsm;
	}

	protected function setEmail($email)
	{
		$this->email = $email;
	}
}



/********************************************************************************************/
/*									PERSON CLASS 											*/
/********************************************************************************************/

class Person extends MinifiedPerson {
	protected $birth;
	protected $birthplace;
	protected $gender;
	protected $niss;
	protected $address;
	protected $box;
	protected $postal;
	protected $city;
	protected $profession;
	protected $ffgid;
	protected $addressbook;

	function __construct($personid, $lastname, $firstname, $phone, $gsm, $email, $birth, $birthplace, $gender, $niss, $address, $box, $postal, $city, $profession, $ffgid, $addressbook)
	{
		parent::__construct($personid, $lastname, $firstname, $phone, $gsm, $email);

		$this->birth = $birth;
		$this->birthplace = $birthplace;
		$this->gender = $gender;
		$this->niss = $niss;
		$this->address = $address;
		$this->box = $box;
		$this->postal = $postal;
		$this->city = $city;
		$this->profession = $profession;
		$this->ffgid = $ffgid;
		$this->addressbook = $addressbook;

		return $this;
	}

	public static function withReader($line)
	{
		return (new Person($line['personid'], $line['lastname'], $line['firstname'], $line['phone'], $line['gsm'], $line['email'], $line['birth'], $line['birthplace'], $line['gender'], $line['niss'], $line['address'], $line['box'], $line['postal'], $line['city'], $line['profession'], $line['ffgid'], $line['addressbook']));
	}

	/*************************************** GETTER ***************************************/
	function getBirthDate()
	{
		return $this->birth;
	}

	function displayBirthDate()
	{
		if($this->birth != "")
			return substr($this->birth, 8, 2)."/".substr($this->birth, 5, 2)."/".substr($this->birth, 0, 4);
		return null;
	}

	function getGender()
	{
		return $this->gender;
	}

	function displayGender()
	{
		if($this->gender == "M")
			return "Masculin";
		return "Féminin";
	}

	function getBirthPlace()
	{
		return $this->birthplace;
	}

	function getNiss()
	{
		return $this->niss;
	}

	function getAddress()
	{
		return $this->address;
	}

	function getBox()
	{
		return $this->box;
	}

	function getPostal()
	{
		return $this->postal;
	}

	function getCity()
	{
		return $this->city;
	}

	function getFfgID()
	{
		return $this->ffgid;
	}

	function getProfession()
	{
		return $this->profession;
	}

	function IsBookmarked()
	{
		return $this->addressbook;
	}

	/*************************************** SETTER ***************************************/


	/**************************************** TOOLS ***************************************/
	
}



/********************************************************************************************/
/*									GYMNAST CLASS 											*/
/********************************************************************************************/

Class Gymnast extends Person {
	protected $responsableListId;
	protected $responsableList;
	protected $jumpList;
	protected $exerciceList;
	protected $resultList;
	protected $participateList;
}



/********************************************************************************************/
/*										USER CLASS 											*/
/********************************************************************************************/

Class User extends Person {
	protected $userid;
	protected $username;
	protected $password;
	protected $account;
	protected $trainerlevel;
	protected $judgelevel;
	protected $reward;
	protected $statusin;
	protected $statusout;
	protected $lastcnx;
	protected $lastFinished;
	// protected $courselisting;

	function __construct($personid, $lastname, $firstname, $phone, $gsm, $email, $birth, $birthplace, $gender, $niss, $address, $box, $postal, $city, $profession, $ffgid, $addressbook, $userid, $username, $password, $account, $trainerlevel, $judgelevel, $statusin, $statusout, $lastcnx, $lastFinished)
	{
		parent::__construct($personid, $lastname, $firstname, $phone, $gsm, $email, $birth, $birthplace, $gender, $niss, $address, $box, $postal, $city, $profession, $ffgid, $addressbook);

		$this->userid = $userid;
		$this->username = $username;
		$this->password = $password;
		$this->account = $account;
		$this->trainerlevel = $trainerlevel;
		$this->judgelevel = $judgelevel;
		$this->statusin = $statusin;
		$this->labelin = null;
		$this->statusout = $statusout;
		$this->labelout = null;
		$this->lastcnx = $lastcnx;
		$this->lastFinished = $lastFinished;
		$this->reward = null;

		// var_dump($this);

		return $this;
	}

	public static function withReader($line)
	{
		// echo "<br /><br />";
		// var_dump($line);
		return (new User($line['personid'], $line['lastname'], $line['firstname'], $line['phone'], $line['gsm'], $line['email'], $line['birth'], $line['birthplace'], $line['sexe'], $line['niss'], $line['address'], $line['box'], $line['postal'], $line['city'], $line['profession'], $line['ffgid'], $line['addressbook'], $line['userid'], $line['username'], $line['password'], $line['account'], $line['trainerlevel'], $line['judgelevel'], $line['status_in'], $line['status_out'], $line['lastcnx'], $line['lastfinished']));
	}

	/*************************************** GETTER ***************************************/
	function getUserID()
	{
		return $this->userid;
	}

	function getUserName()
	{
		return $this->username;
	}

	function getPassword()
	{
		return $this->password;
	}

	function getAccount()
	{
		return $this->account;
	}

	function displayAccount()
	{
		$temp = str_replace(" ", "", $this->account);
		return substr($temp, 4, 3)."-".substr($temp, 7, 7)."-".substr($temp, 14);
	}

	function getTrainerLevel()
	{
		return $this->trainerlevel;
	}

	function getJudgeLevel()
	{
		return $this->judgelevel;
	}

	function getStatusIn()
	{
		return $this->statusin;
	}

	function getStatusOut()
	{
		return $this->statusout;
	}

	function getLabelIn()
	{
		if(empty($this->labelin)) {
			$database = new DBTools();
			$this->labelin = $database->getLabelIn($this->statusin);
		}
		return $this->labelin;
	}

	function getLabelOut()
	{
		if(empty($this->labelout)) {
			$database = new DBTools();
			$this->labelout = $database->getLabelOut($this->statusout);
		}
		return $this->labelout;
	}

	function getLastCnx()
	{
		return "Le ".substr($this->lastcnx, 8, 2)."-".substr($this->lastcnx, 5, 2)."-".substr($this->lastcnx, 0, 4)." à ".substr($this->lastcnx, 11, 5);
	}

	function getLastFinished()
	{
		return ucwords(strftime("%B %Y", strtotime(date("F Y", mktime(0, 0, 0, intval(substr($this->lastFinished, 5, 2)), 0, substr($this->lastFinished, 0, 4))))));
	}

	function getReward()
	{
		if(empty($this->reward)) {
			$database = new DBTools();
			$this->reward = $database->getReward(1, $this->trainerlevel);
		}
		return $this->reward;
	}
}

?>