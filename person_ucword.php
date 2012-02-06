<?php
	
	require_once("./CONFIG/config.php");

	$query = "SELECT personid, lastname, firstname, city, profession, birthplace FROM xtr_person ORDER BY personid";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);

	while($line=mysql_fetch_array($result)) {
		echo $line['personid']." : ".$line['lastname']." - ".$line['firstname']."<br />";
		$newlastname = ucwords(strtolower($line['lastname']));
		$newfirstname = ucwords(strtolower($line['firstname']));
		$newcity = ucwords(strtolower($line['city']));
		$newbirthplace = ucwords(strtolower($line['birthplace']));
		$newprofession = ucwords(strtolower($line['profession']));

		if(($newlastname !== $line['lastname']) || ($newfirstname !== $line['firstname']) || ($newcity !== $line['city']) || ($newbirthplace !== $line['birthplace']) || ($newprofession !== $line['profession'])) {
			$newlastname = mysql_real_escape_string($newlastname);
			$newfirstname = mysql_real_escape_string($newfirstname);
			$newcity = mysql_real_escape_string($newcity);
			$newprofession = mysql_real_escape_string($newprofession);
			$newbirthplace = mysql_real_escape_string($newbirthplace);

			$query = "UPDATE xtr_person SET lastname = '$newlastname', firstname = '$newfirstname', city = '$newcity', profession = '$newprofession', birthplace = '$newbirthplace' WHERE personid = ".$line['personid'];
			echo $query."<br />";
			$res = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		}

	}

?>