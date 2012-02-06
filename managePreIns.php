<html>
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

</body>
<?php

	$servername='mysql5-6.start';
	$dbusername='lavailla_01';
	$dbpassword='lavailla01';
	$dbname='lavailla_01';

	$connect = mysql_connect($servername,$dbusername,$dbpassword) or die("Impossible de se connecter : " . mysql_error());
	$selected_db = mysql_select_db($dbname, $connect) or die('Could not select database.');

	mysql_query("SET NAMES 'utf8'");
	setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8', 'fra');

	// TO TITLE CASE

	$query = "SELECT personid, lastname, firstname FROM xtr_preins";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (preins) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

	while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$query = "UPDATE xtr_preins SET lastname='".mysql_real_escape_string(ucwords(strtolower($line['lastname'])))."', firstname='".mysql_real_escape_string(ucwords(strtolower($line['firstname'])))."' WHERE personid = ".$line['personid'];
		$result2 = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (preins) !<br />".$result2."<br />".mysql_error(), E_USER_ERROR);
	}

	// WRONG PHONE LISTING
	// $query = "SELECT personid, phone FROM xtr_preins WHERE phone IS NOT NULL AND phone != ''";
	// $result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (preins) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	// while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	// 	if(strlen($line['phone']) != 9) {
	// 		echo $line['personid'].", ";
	// 		// echo $line['personid']." - ".$line['phone']."<br />";
	// 	}
	// }

	// WRONG PHONE LISTING
	// $query = "SELECT personid, phone FROM xtr_preins WHERE phone IS NOT NULL AND phone != ''";
	// $result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (preins) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	// while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	// 	if(substr($line['phone'], 0, 1) != 0) {
	// 		// echo $line['personid'].", ";
	// 		echo $line['personid']." - ".$line['phone']."<br />";
	// 	}
	// }

	// $query = "SELECT relationshipid, personid, personid1 FROM xtr_relationship WHERE type = 'père' AND responsable = 'Y'";
	// $result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (preins) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

	// while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	// 	$query = "UPDATE xtr_person SET resp1id='".$line['personid1']."', type1='père' WHERE personid = ".$line['personid'];
	// 	$result2 = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (preins) !<br />".$result2."<br />".mysql_error(), E_USER_ERROR);

	// 	$query = "DELETE FROM xtr_relationship WHERE relationshipid = ".$line['relationshipid'];
	// 	echo "Relationship ".$line['relationshipid']." deleted...<br />";
	// 	$result2 = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (preins) !<br />".$result2."<br />".mysql_error(), E_USER_ERROR);
	// }

	// $query = "SELECT relationshipid, personid, personid1 FROM xtr_relationship WHERE type = 'mère' AND responsable = 'Y'";
	// $result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (preins) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

	// while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	// 	$query = "SELECT resp1id FROM xtr_person WHERE personid = ".$line['personid'];
	// 	$result2 = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (preins) !<br />".$result2."<br />".mysql_error(), E_USER_ERROR);
	// 	$test = mysql_fetch_array($result);

	// 	if($test != '')
	// 		$query = "UPDATE xtr_person SET resp1id='".$line['personid1']."', type1='mère' WHERE personid = ".$line['personid'];
	// 	else 
	// 		$query = "UPDATE xtr_person SET resp2id='".$line['personid1']."', type2='mère' WHERE personid = ".$line['personid'];
	// 	// echo $query."<br />";
	// 	$result2 = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (preins) !<br />".$result2."<br />".mysql_error(), E_USER_ERROR);

	// 	$query = "DELETE FROM xtr_relationship WHERE relationshipid = ".$line['relationshipid'];
	// 	echo $query.";<br />";
	// 	// echo "Relationship ".$line['relationshipid']." deleted...<br />";
	// 	$result2 = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (preins) !<br />".$result2."<br />".mysql_error(), E_USER_ERROR);
	// }
?>
</body>
</html>