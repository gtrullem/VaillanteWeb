<?php
	/****************************************************/
	/* 						SECURITE					*/
	/*													*/
	/* You need to create a new User in MySQL. There 	*/
	/* are informations :								*/
	/****************************************************/
	// All priliveges for this DB but nothing for other DB.
	$servername='localhost';					// <- Server	// localhost	//	mysql5-6.start
	$dbusername='root';						// <- Username	// root	//	lavailla_01
	$dbpassword='root';						// <- password	// root	//	lavailla01
	$dbname='laailla_01';							// <- Database	// vaillante	//	lavailla_01
	
	// Database connection & selection
	$connect = mysql_connect($servername,$dbusername,$dbpassword) or die("Impossible de se connecter : " . mysql_error());
	$selected_db = mysql_select_db($dbname, $connect) or die('Could not select database.');
	
	// Local Configuration
	mysql_query("SET NAMES 'utf8'");
	setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8', 'fra');
	
	// Mail Configuration
	require_once("./CONFIG/configmail.php");
	
	// Right Connection
	if(!empty($function)) {
		$query = "SELECT rightin, rightout FROM xtr_functionright WHERE function LIKE '$function'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (functionright) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		$line = mysql_fetch_array($result);
	}
	
?>