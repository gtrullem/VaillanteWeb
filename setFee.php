<?php
	require_once("./CONFIG/config.php");
	$test = FALSE;
	
	if(!empty($_GET['fee']) && !empty($_GET['personid']) && !empty($_GET['seasonid'])) {
		$personid = $_GET['personid'];
		$seasonid = $_GET['seasonid'];
		$amount = $_GET['fee'];
		
		if(date("n") >= "8")	$season = date("Y")."-".(date("Y") + 1);
		else	$season = (date("Y") - 1)."-".date("Y");
		
		$query = "INSERT INTO xtr_feetopay (personid, seasonid, amount, datepay) VALUE ('$personid', '$seasonid', '$amount', '".date("Y-m-d H:i:s")."')";
		echo $query;
		//$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (feetopay) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		echo $_GET['fee']."€&nbsp;<img src='./design/images/Accept.png' width='16' height='16' />";
	}
?>