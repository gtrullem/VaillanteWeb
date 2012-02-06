<?php

function checkRight($function)
{
	if(!empty($function)) {
		require("./CONFIG/config.php");

		$query = "SELECT rightin, rightout FROM xtr_functionright WHERE function LIKE '$function'";
//		echo "<br /><br />".$query;
		$resultRight = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (functionright) !<br />".$resultRight."<br />".mysql_error(), E_USER_ERROR);
//		echo "<br />test...";
		$lineRight = mysql_fetch_array($resultRight, MYSQL_ASSOC);
//		echo "<br />encore...";
		if(($_SESSION['status_in'] < $lineRight['rightin']) && ($_SESSION['status_out'] < $lineRight['rightout']))	return false;
		return true;
	}
	return false;
}

function haveRight($function)
{
	if(!empty($function)) {
		require("./CONFIG/config.php");
		
		$query = "SELECT rightin, rightout FROM xtr_functionright WHERE function LIKE '$function'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (functionright) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		$line = mysql_fetch_array($result);
	}
	
	return($line);
}

?>