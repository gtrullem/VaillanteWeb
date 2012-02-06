<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");

	if(empty($_GET['placeid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=place&referrer=place_listing.php");
		exit;
	}
	$placeid = $_GET['placeid'];

	require_once("./CLASS/dbplace.class.php");
	$database = new DBPlace();
	$place = $database->getPlace($placeid);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Nouvelle discipline :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<noscript>
		<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>
	</noscript>
</head>

<body>
<div id="body">

<?php
	require_once("./header.php");
?>
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
			<h2><a>Détails d'un Lieu</a></h2>
			<br />
			<table align="center">
				<tr>
					<td>
						<form class="formulaire" name="formulaire">
						<fieldset>
							<legend>Informations sur le lieu</legend>
							<p>
								<label>Nom</label>
								<?php echo $place->getName(); ?>
							</p>
							<p>
								<label>Adresse</label>
								<?php echo $place->getAddress(); ?>
							<p>
								<label>&nbsp;</label>
								<? echo $place->getPostal()." ".$place->getCity(); ?>
							</p>
							<p>
								<label>&nbsp;</label>
								<? echo $place->getCountry(); ?>
							</p>
							<p>
								<label>Nombre de Km</label>
								<?php echo $place->getNbKm()."Km"; ?>
							</p>
							<p>
								<label>Salle du club ?</label>
								<?php
									if($place->isLocal())	echo "Oui";
									else echo "Non"
								?>
							</p>
							<?php
								if($_SESSION['status_out'] >= 3) {
									echo "<p align=\"right\"><a href=\"place_update.php?placeid=".$place->getID()."\"><img src=\"./design/images/icons/16_Edit.png\" /></a></p>";
								}
							?>
							</fieldset>
						</form>
					</td>
				</tr>
			</table>
			<!-- /* Voir tous les évent associés*/ -->
			</div>		
		</div>
	</div>
</div>
	
<?php
	require_once("./footer.php");
?>
</div>
</body>
</html>