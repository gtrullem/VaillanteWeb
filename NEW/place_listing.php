<?php

	// session_start();
	
	// if(!isset($_SESSION['uid'])) {
	// 	header("Refresh: 0; url=./redirection.php");
	// 	exit;
	// }
	
	// $pagename = "affiliate_upd";
	// require_once("./CONFIG/config.php");
	
	// if(($_SESSION['status_in'] < 1) && ($_SESSION['status_out'] < 1)) {
	// 	header("Refresh: 0; url=./redirection.php?err=1");
	// 	exit;
	// }

	require './template/h2o/h2o.php';

	$h2o = new h2o('./template/place_listing.html');

	$page = array(
		'title' => 'Liste des Disciplines'
	);

	require_once("./CLASS/dbplace.class.php");
	$database = new DBPlace();

	$places = $database->getPlaces();

	# render your awesome page
	echo $h2o->render(compact('page', 'places'));
?>