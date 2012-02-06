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

	$h2o = new h2o('./template/discipline_listing.html');

	$page = array(
		'title' => 'Liste des Disciplines'
	);

	require_once("./CLASS/dbdiscipline.class.php");
	$database = new DBDiscipline();

	$disciplines = $database->getDisciplines();

	# render your awesome page
	echo $h2o->render(compact('page', 'disciplines'));

?>
