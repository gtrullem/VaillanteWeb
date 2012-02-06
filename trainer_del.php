<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] < 4) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(empty($_GET['itid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=association&referrer=subdiscipline_listing.php");
		exit;
	}
	
	if (empty($_GET['cid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=cours&referrer=subdiscipline_listing.php");
		exit;
	}
	
	$courseid = $_GET['cid'];
	$istrainerid = $_GET['itid'];
	
	if(!empty($_POST['yes']) || !empty($_POST['no'])) {
		if(isset($_POST['yes'])) {
			$query = "DELETE FROM xtr_istrainer WHERE istrainerid = '$istrainerid'";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : DELETE FAILED (trainer) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		}
		header("Location: ./course_detail.php?courseid=$courseid");
		exit;
	}
	
	$query = "SELECT COUNT(istrainerid) FROM xtr_istrainer WHERE courseid = '$courseid'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$count = mysql_fetch_array($result);
	
	if($count[0] <= 1) {
		$err = "Ce cours doit être donné par, au moins, un entraineur. Vous ne pouvez pas l'effacer.";
	} else {
		// CHECKING LEFT JOIN NEEDED !!!
		$query = "SELECT C.courseid, C.day, C.h_begin, C.h_end, CONCAT(P.lastname, ', ', P.firstname) as name, it.istrainerid AS itid FROM xtr_course AS C LEFT JOIN xtr_istrainer AS it ON C.courseid = it.courseid LEFT JOIN xtr_users ON it.userid = xtr_users.userid LEFT JOIN xtr_person AS P ON xtr_users.personid = P.personid WHERE it.istrainerid = '$istrainerid'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		$line = mysql_fetch_array($result);
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Suppression d'un entraineur :.</title>
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
				<h2><a>Suppression d'un entraineur</a></h2>
				<?php
					if(!empty($err)) {
						echo "<br /><p align=\"center\" class=\"important\">$err</p><br />";
					} else {
				?>
				<table align="center">
					<tr>
						<td>
							<form name="formulaire" class="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?cid=".$courseid."&itid=".$istrainerid; ?>" enctype="multipart/form-data">
								<fieldset>
									<legend>Informations</legend>
									<p>
										<label>Nom</label>
										<?php echo $line['name']; ?>
									</p>
									<p>
										<label>Jour</label>
										<?php echo $line['day']; ?>
									</p>
									<p>
										<label>&nbsp;</label>
										De <?php echo $line['h_begin']." à ".$line['h_end']; ?>
									</p>
									<p align="center" class="important">Etes-vous sûr de vouloir supprimer cet entraineur pour ce cours ?<br /><input type="submit" name="yes" value="Oui" />&nbsp;&nbsp;&nbsp;<input type="submit" name="no" value="Non" /></p>
								</fieldset>
							</form>
						</td>
					</tr>
				</table>
				<?php
					}
				?>
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