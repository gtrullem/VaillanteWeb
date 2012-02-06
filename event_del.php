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
	
	if(empty($_GET['eventid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=evenement&referrer=event_listing.php");
		exit;
	}
	
	$eventid = $_GET['eventid'];
	
	if(isset($_POST['no'])) {
		header("Location: ./event_listing.php");
		exit;
	} elseif(isset($_POST['yes'])) {
////		mysql_query("START TRANSACTION", $connect);

		$query = "DELETE FROM xtr_eventisfordisc WHERE eventid = '$eventid'";
////		echo $query."<br />";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : DELETE FAILED (isaffiliate) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
//		$query = "DELETE FROM xtr_istrainer WHERE courseid = '$courseid'";
////		echo $query."<br />";
//		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : DELETE FAILED (istrainer) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$query = "DELETE FROM xtr_event WHERE eventid = '$eventid'";
////		echo $query."<br />";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : DELETE FAILED (event) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
        
////        echo "res1 : ".$result1."<br />";
////        echo "res2 : ".$result2."<br />";
////         if (!($result1 && $result2)) { 
////            mysql_query("ROLLBACK", $connect); 
////         } else { 
////            mysql_query("COMMIT", $connect);
////            echo "<p align=\"center\" class=\"goodalert\">Cours supprimé.</p>";
			header("Location: ./event_listing.php");
			exit;
////         }
	}
	
	$query = " SELECT * FROM xtr_event WHERE eventid='$eventid'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (event) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);
	
	$query = "SELECT COUNT(eventid) FROM xtr_participateto WHERE eventid = '$eventid'";
//	echo $query."<br />";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (participateto) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	
	$begindate = explode("-", $line['dbegin']);
	if(mysql_fetch_array($result) && (date("Y") > $begindate[0] || date("n") > $begindate[1] || date("j") > $begindate[2])) {
		header("Refresh: 5; url=event_listing.php");
		$err = "Vous ne pouvez pas supprimer cet évènement : la date de l'évènement est déjà passée et des gymnastes y ont été inscrits.<br /> Vous serez redirigé(e) dans 5 secondes.";
	}
	$begindate = $begindate[2]."/".$begindate[1]."/".$begindate[0];
	
	$enddate = explode("-", $line['dend']);
	$enddate = $enddate[2]."/".$enddate[1]."/".$enddate[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Suppression d'un cours :.</title>
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
		<div id="frame">
			<div id="content">
				<h2><a>Suppression d'un évènement</a></h2>
				<br />
				<?php
					if(!empty($err))
						echo "<p align=\"center\" class=\"important\">$err</p>";
				?>
				<table align="center">
					<tr>
						<td>
							<form name="formulaire" class="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?eventid=".$eventid; ?>" enctype="multipart/form-data">
								<fieldset>
								<legend>Détail de l'évènement</legend>
								<p>
									<label>Titre</label>
									<?php echo $line['title']; ?>
								</p>
								<p>
									<label>Type</label>
									<?php echo $line['event_type']; ?>
								</p>
								<p>
									<table>
										<tr>
											<td><label>Lieu</label></td>
											<td>
												<?php
													$query = "SELECT * FROM xtr_place WHERE placeid=".$line['placeid'].";";
													$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (place) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
													$lineplace = mysql_fetch_array($result);
													
													echo $lineplace['name']."</td></tr><tr><td>&nbsp;</td><td>".$lineplace['address']."</td></tr><tr><td>&nbsp;</td><td>".$lineplace['postal']." ".$lineplace['city'];
												?>
											</td>
										</tr>
									</table>
								</p>
								<p>
									<label>Informations</label>
									<?php echo $line['information']; ?>
								</p>
								<p>
									<table>
										<tr>
											<td><label>Discipline(s)</label></td>
											<?php
												$query ="SELECT title FROM xtr_eventisfordisc, xtr_discipline WHERE xtr_eventisfordisc.eventid = $eventid AND xtr_eventisfordisc.disciplineid = xtr_discipline.disciplineid;";
												$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
												if($line2 = mysql_fetch_array($result)) {
													echo "<td>".$line2['title']."</td>";
												} else {
													echo "<td>(Aucune)</td>";
												}
												
												while($line2 = mysql_fetch_array($result)) {
													echo "<tr><td>&nbsp;</td><td>".$line2['title']."</td></tr>";
												}
											?>
									</table>
								</p>
								<p>
									<label>Date début</label>
									<?php echo $begindate; ?>
								</p>
								<p>
									<label>Date fin</label>
									<?php echo $enddate; ?>
								</p>
								<p>
									<label>Personne de contact</label>
									<?php
										$query = "SELECT CONCAT(lastname, ', ', firstname) AS name FROM xtr_person WHERE personid='".$line['personid']."'";
										$result2 = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />".$result2."<br />".mysql_error(), E_USER_ERROR);
//											echo "-".$line['personid']."-<br />";
//											echo $query."<br />";
										$line2 = mysql_fetch_array($result2);
										echo $line2['name'];
									?>
								</p>
								<?php
								
									if($line['event_type'] == "Stage") {
								
								?>
								<hr>
								<p>
									<table width="100%">
										<tr>
											<td>
												<label>Entraineur(s)</label>
											</td>
											<td>
											<?php
												$query = "SELECT CONCAT(lastname, ', ', firstname) AS name FROM xtr_traineto, xtr_users, xtr_person WHERE xtr_traineto.userid = xtr_users.userid AND xtr_users.personid = xtr_person.personid AND xtr_traineto.eventid = $eventid";
												$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />".$result2."<br />".mysql_error(), E_USER_ERROR);
												$line2 = mysql_fetch_array($result);
												echo $line2['name']."</td></tr>";
												
												while($line2 = mysql_fetch_array($result)) {
													echo "<tr><td>&nbsp;</td><td>".$line2['name']."</td></tr>";
												}
												echo "<tr><td>&nbsp;</td><td><a href=\"participateto_listing.php?id=$eventid\">Liste des participants</a></td></tr>";
												echo "</table></p><br />";
												
									}	// End trainer's listing
								?>
							</fieldset>
							<?php
								if(empty($err)) {
							?>
							<p align="center"><span class="important">Etes-vous sûr de vouloir supprimer cet évènement ? <input type="submit" name="yes" value="Oui" />&nbsp;&nbsp;&nbsp;<input type="submit" name="no" value="Non" /></span></p>
							<?php
								}
							?>
						</form>
					</td>
				</tr>
			</table>
		</div>

		<div id="sidebar" class="sidebar">
			<div>
				<div class="widget widget_categories">
					<h2 class="title">Informations</h2>
					<p align="justify">En effacant cet évènement, vous effacerez également toutes participations des gymnastes à cet évènement. Il en va de même pour les entraineurs/personnes de contacts.</p>
					<!--
					<ul>
						<li class="cat-item"><a href="./index.php?disc=*">Toutes</a></li>
					</ul>
					-->
				</div>
			</div>
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