<?php

	session_start();
	
	$pagename = "event_listing";
	
	require './template/h2o/h2o.php';

	$h2o = new h2o('./template/event_listing.html');

	$page = array(
		'title' => 'Liste des Evènements'
	);

	require_once("./CLASS/dbevent.class.php");
	$database = new DBEvent();

	$events = $database->getEvents();

	# render your awesome page
	echo $h2o->render(compact('page', 'events'));
?>