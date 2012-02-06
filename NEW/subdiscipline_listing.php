<?php

	// session_start();
	
	// if(!isset($_SESSION['uid'])) {
	// 	header("Refresh: 0; url=./redirection.php");
	// 	exit;
	// }
	
	// $pagename = "discipline_listing";
	// require_once("./CONFIG/config.php");
	
	// if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
	// 	header("Refresh: 0; url=./redirection.php?err=1");
	// 	exit;
	// }

	require './template/h2o/h2o.php';

	$h2o = new h2o('./template/subdiscipline_listing.html');

	$page = array(
	  'title' => 'Liste des Sous-Disciplines'
	);

	require_once("./CLASS/dbsubdiscipline.class.php");
	$database = new DBSubDiscipline();

	$subdisciplines = $database->getSubDisciplines();

	# render your awesome page
	echo $h2o->render(compact('page', 'subdisciplines'));

?>
