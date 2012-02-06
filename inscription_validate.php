<?php
	
	if(empty($_GET['id']) || empty($_GET['idg']) || empty($_GET['ln'])) {
		$err = "Paramètres manquants.";
	} else {
		$servername='mysql5-6.start';
		$dbusername='lavailla_01';
		$dbpassword='lavailla01';
		$dbname='lavailla_01';
		
		// Database connection & selection
		$connect = mysql_connect($servername,$dbusername,$dbpassword) or die("Impossible de se connecter : " . mysql_error());
		$selected_db = mysql_select_db($dbname, $connect) or die('Could not select database.');
		
		// Local Configuration
		mysql_query("SET NAMES 'utf8'");
		setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8', 'fra');
		
		$id = $_GET['id'];
		$gymid = $_GET['idg'];
		$lastname = $_GET['ln'];
		
		$query = "UPDATE xtr_preins SET validate = 'Y' WHERE personid = $id AND lastname LIKE '$ln'";
//		echo "<br /><br />".$query;
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE INSCRIPTION RESPONSABLE FAILED !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$query = "UPDATE xtr_preins SET validate = 'Y' WHERE personid = $gymid";
//		echo "<br /><br />".$query;
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE INSCRIPTION GYMNASTIC FAILED !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$msg = "Validation enregistrée. Merci.";
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Validation :.</title>
	<link rel="stylesheet" href="./Extranet/design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./Extranet/design/AdminBar.css" type="text/css" media="screen" />
	<script type="text/javascript" language="javascript" src="./Extranet/library/library.js"></script> <!-- /Extranet -->
</head>

<div id="body">
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
				<!-- ========================= BEGIN FORM ====================== -->
				<h2><a>Validation d'inscription</a></h2>
				<?php
					if(!empty($msg)) {
						echo "<p align=\"center\" class=\"goodalert\">$msg</p>";
					} elseif(!empty($err)) {
						echo "<p align=\"center\" class=\"important\">$err</p>";
					}
				?>
				
			</div>	
		</div>
	</div>
</div>
</div>
</body>
</html>