<?php

	session_start();
	
	// Détruit toutes les variables de session
	$_SESSION = array();
	
	// Effacement du cookie de session.
	if (isset($_COOKIE[session_name()]))
		setcookie(session_name(), '', time()-42000, '/');
	
	// Destruction de la session.
	session_unset();
	session_destroy();
	
	header("Refresh: 2; url=./cnx.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Déconnexion :.</title>
	<link href="./design/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="body">

<div id="header"> 
	<div class="container">
		<div align="right">
			&nbsp;
		</div>
		<div id="header_image"></div>
	</div>
</div>
<div id="page" class=" sidebar_right">
	<div class="container">	
		<div id="frame2">
			<div id="content">
				<div class="post">
					<h2><a>Déconnexion</a></h2>
					<br />
					<div class="entry">
		            	<p align="center"><span class="important">Vous êtes déconnecté(e)...</span>
		            	<br />
		            	<br />
		            	<span class="important">Vous serez redirigé(e) dans 2 secondes.</span></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	require_once("./footer.php");
?>
</body>
</html>