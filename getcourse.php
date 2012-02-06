<?php
	require_once("./CONFIG/config.php");
	
	if(date("n") >= "8") {
		$season = date("Y")."-".(date("Y") + 1);
	} else {
		$season = (date("Y") - 1)."-".date("Y");
	}
	
	$day = $_GET['daynumber'];
	$discipline = $_GET['discipline'];
	
	$query = "SELECT xtr_course.courseid, day, h_begin, h_end, xtr_discipline.acronym FROM xtr_course, xtr_discipline, xtr_subdiscipline WHERE season = '$season' AND xtr_course.enable =  'Y' AND xtr_course.subdisciplineid = xtr_subdiscipline.subdisciplineid AND xtr_subdiscipline.disciplineid = xtr_discipline.disciplineid";
	
	if(!empty($day)) $query .= " AND daynumber IN ($day)";
	
	if(!empty($discipline)) $query .= " AND xtr_discipline.disciplineid IN ($discipline)";
	
	$query .= " ORDER BY xtr_discipline.acronym, daynumber, h_begin, h_end";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course, discipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	
	echo "<hr /><table align=\"center\" width=\"100%\">";
	$i=0;
	while($line = mysql_fetch_array($result)) {
		$i++;
		if(($i%2) == 0) {
			echo "<TR bgcolor=\"#E7F1F7\">";
		} else {
			echo "<TR>";
		}
		
		echo "<td><input type=\"checkbox\" name=\"courseid[]\" value=\"".$line['courseid']."\" /></td><td>".$line['acronym']." : ".$line['day']." <font size=\"1\">(".substr($line['h_begin'], 0, 5)."-".substr($line['h_end'], 0, 5).")</font></td></tr>";
	}
	echo "</table>";

?>