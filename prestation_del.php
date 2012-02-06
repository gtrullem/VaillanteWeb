<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < 1) && ($_SESSION['status_out'] < 1)) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(empty($_GET['id'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=prestation&referrer=prestation_listing.php");
		exit;
	}
	
	$prestationid = $_GET['id'];
	
	if(isset($_POST['no'])) {
		header("Location: ./prestation_listing.php");
		exit;
	} elseif(isset($_POST['yes'])) {
		$query = "DELETE FROM xtr_prestation WHERE prestationid = $prestationid";
		// echo $query."<br />";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : DELETE FAILED (prestation) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);

		// echo "<p align=\"center\" class=\"goodalert\">Cours supprimé.</p>";
		header("Location: prestation_listing.php");
		exit;
	}
	
	$query = "SELECT pr.* , CONCAT(pe.lastname, ', ', pe.firstname) AS name FROM xtr_prestation AS pr, xtr_users AS u, xtr_person AS pe WHERE pr.prestationid = $prestationid AND pr.userid = u.userid AND u.personid = pe.personid";
//	echo $query."<br />";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (prestation) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	
	$line = mysql_fetch_array($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Suppression d'une prestation :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
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
				<!-- ========================= BEGIN FORM ====================== -->
				<h2><a>Suppression d'une prestation</a></h2>
				<br />
				<table align="center">
					<tr>
						<td>
							<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?id=".$prestationid; ?>" enctype="multipart/form-data">
							<fieldset>
								<legend>Informations</legend>
								<p>
									<label>Entraineur :</label>
									<b><?php echo $line['name']; ?></b>
								</p>
								<p>
									<label>Prestation :</label>
									<?php
										echo substr($line['date'], 8, 2)."/".substr($line['date'], 5, 2)."/".substr($line['date'], 0, 4).", de ".substr($line['h_from'], 0, 5)." à ".substr($line['h_to'], 0, 5);
									?>
								</p>
								<?php
									if($line['description'] != "") {
								?>
								<p>
									<label>Description :</label>
									<textarea name="description" rows="5" cols="34"><?php	echo $line['description'];	?></textarea>
								</p>
								<?php
									}
								?>
								<br />
								<p align="center" class="important">Etes-vous sûr de vouloir supprimer cette prestation ? <br /><input type="submit" name="yes" value="Oui" />&nbsp;&nbsp;&nbsp;<input type="submit" name="no" value="Non" /></p>
							</form>
						</td>
					</tr>
				</table>
				<!-- ========================= END FORM ====================== -->
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