<?php
	
	require_once("./CONFIG/config.php");

	// $query = "SELECT DISTINCT(xtr_course.courseid), hallid, day, daynumber, h_begin, h_end, nbhour, seasonid FROM xtr_course, xtr_isaffiliate WHERE xtr_course.courseid = xtr_isaffiliate.courseid ORDER BY courseid";
	// $result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);

	// while($line=mysql_fetch_array($result)) {
	// 	if($line['hallid'] == 0)
	// 		$hallid = 1;
	// 	else
	// 		$hallid = $line['hallid'];

	// 	$query = "INSERT INTO xtr_linkCourseSeason (courseid, seasonid, hallid, h_begin, h_end, nbhour, day, daynumber) VALUES ('".$line['courseid']."', '".$line['seasonid']."', '".$hallid."', '".$line['h_begin']."', '".$line['h_end']."', '".$line['nbhour']."', '".$line['day']."', '".$line['daynumber']."')";
	// 	$res = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);

	// }

	// $query = "SELECT isaffiliateid, seasonid, courseid FROM xtr_isaffiliate";
	// $result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (isaffiliate) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);

	// while($line = mysql_fetch_array($result)) {
	// 	$query = "SELECT lcsid FROM vw_course WHERE seasonid = '".$line['seasonid']."' AND courseid = '".$line['courseid']."'";
	// 	// echo $query."<br />";
	// 	$res = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	// 	$lcsid = mysql_fetch_array($res, MYSQL_NUM);
	// 	$lcsid = $lcsid[0];

	// 	$query = "UPDATE xtr_isaffiliate SET lcsid = ".$lcsid." WHERE isaffiliateid = ".$line['isaffiliateid'];
	// 	echo $query."<br />";
	// 	$res = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (isaffiliate) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	// }

	$query = "SELECT istrainerid, seasonid, courseid FROM xtr_istrainer WHERE seasonid IS NOT NULL ORDER BY istrainerid";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (istrainer) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);

	while($line = mysql_fetch_array($result)) {
		$query = "SELECT lcsid FROM vw_course WHERE seasonid = '".$line['seasonid']."' AND courseid = '".$line['courseid']."'";
		$res = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		if($lcsid = mysql_fetch_array($res, MYSQL_NUM)) {
		
			$lcsid = $lcsid[0];

			$query = "UPDATE xtr_istrainer SET lcsid = ".$lcsid." WHERE istrainerid = ".$line['istrainerid'];
			echo $query."<br />";
			$res = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (isaffiliate) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		}
	}
?>