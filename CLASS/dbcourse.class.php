<?php

require_once("./CLASS/database.class.php");
require_once("./CLASS/objectcourse.class.php");

class DBCourse extends DB {
	
	function __construct()
	{
		parent::__construct();
	}

	public function getCourses($where=null, $order=null)
	{
		$query = "SELECT * FROM vw_course";

		if(!empty($where)) $query .= " WHERE ".$where;
		if(!empty($order)) $query .= " ORDER BY ".$order;
		else $query .= " ORDER BY daynumber, h_begin, h_end";

		$this->openConnection();
		// or mail($webmaster, "Extranet Error", "Discipline detail : ".$result."<br />".mysql_error(), $headers_basic);
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, Course::withReader($line));

		$this->closeConnection();
		
		return $objectList;
	}

	public function getCourseFromCourseID($courseid, $seasonid)
	{
		$query = "SELECT * FROM vw_course WHERE seasonid = $seasonid AND courseid = $course ORDER BY daynumber, h_begin, h_end ASC";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
		
		return Course::withReader(mysql_fetch_array($result, MYSQL_ASSOC));
	}

	public function getCourseFromSubDisciplineID($subdisciplineid, $seasonid)
	{
		$query = "SELECT * FROM vw_course WHERE seasonid = $seasonid AND subdisciplineid = $subdisciplineid ORDER BY daynumber, h_begin, h_end ASC";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, Course::withReader($line));

		$this->closeConnection();
		
		return $objectList;
	}

	public function getCourseFromLinkID($linkid)
	{
		$query = "SELECT * FROM vw_course WHERE lcsid = $linkid ORDER BY daynumber, h_begin, h_end ASC";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
		
		return Course::withReader(mysql_fetch_array($result, MYSQL_ASSOC));
	}

	public function getSeasonCourses($season)
	{
		$query = "SELECT * FROM vw_course WHERE seasonid = $season ORDER BY daynumber, h_begin, h_end ASC";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, Course::withReader($line));

		$this->closeConnection();
		
		return $objectList;
	}
	
	public function insertCourse($course)
	{
		$query = "INSERT INTO xtr_course (subdisciplineid, active, tarification) VALUES ('".$course->getSubDisciplineID()."','".$course->isActive()."', '".$course->getTarification()."')";

		$this->openConnection();
		
		$result = mysql_query($query, $this->db) or trigger_error("SQL ERROR : INSERT FAILED (course) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$result = mysql_query("SELECT LAST_INSERT_ID()", $this->db) or trigger_error("SQL ERROR : SELECT LAST ID FAILED (course) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);

		$courseid = mysql_fetch_array($result, MYSQL_NUM);
		$courseid = $courseid[0];

		$query = "INSERT INTO xtr_linkCourseSeason (courseid, seasonid, placeid, h_begin, h_end, nbhour, day, daynumber) VALUES ('$courseid', '".$course->getSeasonID()."', '".$course->getHallID()."', '".$course->getBeginHour()."', '".$course->getEndHour()."', '".$course->getNbHour()."', '".$course->getDay()."', '".$course->getDayNumber()."')";
		$result = mysql_query($query, $this->db) or trigger_error("SQL ERROR : INSERT FAILED (link) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);

		$result = mysql_query("SELECT LAST_INSERT_ID()", $this->db) or trigger_error("SQL ERROR : SELECT LAST ID FAILED (link) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);

		$linkid = mysql_fetch_array($result, MYSQL_NUM);
		$linkid = $linkid[0];

		$this->closeConnection();
		
		/* $course->setID($id[0]); /* et pas de return */

		return $linkid[0];
	}

	public function updateCourse($course)
	{
		$query = "UPDATE xtr_course SET hallid = '".$course->getHallID()."', day = '".$course->getDay()."', daynumber = '".$course->getDayNumber()."', h_begin = '".$course->getBeginHour()."', h_end = '".$course->getEndHour()."', nbhour  = '".$course->getNbHour()."', active = '".$course->isActive()."', tarification = '".$course->getTarification()."', subdisciplineid = '".$course->getSubDisciplineID()."' WHERE courseid = ".$course->getID();

		$this->openConnection();
		$result = mysql_query($query, $this->db) or trigger_error("SQL ERROR : UPDATE FAILED (course) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
	}

	public function deleteCourse($courseid)
	{
		// transaction ?

		$servername = 'mysql5-6.start';
		$dbusername = 'lavailla_01';
		$dbpassword = 'lavailla01';
		$dbname = 'lavailla_01';
		$connect = mysql_connect($servername,$dbusername,$dbpassword) or die("Could not connect to database : " . mysql_error());
		mysql_select_db($dbname, $connect)  or trigger_error("Could not select database : ".mysql_error(), E_USER_ERROR);
		mysql_query("SET NAMES 'utf8'");

		$query = "DELETE FROM xtr_isaffiliate WHERE courseid = $courseid";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : DELETE FAILED (isaffiliate) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$query = "DELETE FROM xtr_istrainer WHERE courseid = $courseid";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : DELETE FAILED (istrainer) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		mysql_close($connect);
		

		$query = "DELETE FROM xtr_course WHERE courseid = $courseid";
		$this->openConnection();
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : DELETE FAILED (course) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
        
//        echo "res1 : ".$result1."<br />";
//        echo "res2 : ".$result2."<br />";
//         if (!($result1 && $result2)) { 
//            mysql_query("ROLLBACK", $connect); 
//         } else { 
//            mysql_query("COMMIT", $connect);
//            echo "<p align=\"center\" class=\"goodalert\">Cours supprimé.</p>";
	}
	
	public function getSeasonLabel($seasonid)
	{
		$query = "SELECT seasonlabel FROM xtr_season WHERE seasonid = $seasonid";
		$this->openConnection();
		$result = mysql_query($query, $this->db) or trigger_error("SQL ERROR : UPDATE FAILED (course) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();

		$seasonLabel = mysql_fetch_array($result, MYSQL_NUM);
		$seasonLabel = $seasonLabel[0];

		return $seasonLabel;
	}
}

?>