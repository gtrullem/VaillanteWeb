<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "event_details";
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] < 2){
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(empty($_GET['eventid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=évènement&referrer=event_listing.php");
		exit;
	}
	
	$eventid = $_GET['eventid'];
	
	$query = " SELECT * FROM xtr_event WHERE eventid=$eventid";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (event) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);
	
	$begindate = explode("-", $line['dbegin']);
	$begindate = $begindate[2]."/".$begindate[1]."/".$begindate[0];
	
	$enddate = explode("-", $line['dend']);
	$enddate = $enddate[2]."/".$enddate[1]."/".$enddate[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Détails d'un évènement :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
</HEAD>

<body>
<div id="body">

<?php
	require_once("./header.php");
	$today = date("d/m/Y");
?>
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
				<!-- ========================= BEGIN FORM ====================== -->
				<h2><a>Détail d'un évènement</a></h2>
				<br />
				<table align="center">
					<tr>
						<td>
							<form class="formulaire">
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
										<label><u>Informations :</u></label>
										<textarea name="textbody" rows="10" cols="56"><?php echo $line['information']; ?></textarea>
									</p>
									<p>
										<table>
											<tr>
												<td><label>Discipline(s)</label></td>
												<?php
													$query ="SELECT title FROM xtr_eventisfordisc, xtr_discipline WHERE xtr_eventisfordisc.eventid = $eventid AND xtr_eventisfordisc.disciplineid = xtr_discipline.disciplineid;";
													$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
													if($line2 = mysql_fetch_array($result))
														echo "<td>".$line2['title']."</td>";
													else
														echo "<td>(Aucune)</td>";
													
													while($line2 = mysql_fetch_array($result))
														echo "<tr><td>&nbsp;</td><td>".$line2['title']."</td></tr>";
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
											$query = "SELECT CONCAT(lastname, ', ', firstname) AS name FROM xtr_person WHERE personid = ".$line['personid'];
											$result2 = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />$query<br />$result2<br />".mysql_error(), E_USER_ERROR);
//											echo "-".$line['personid']."-<br />";
//											echo $query."<br />";
											$line2 = mysql_fetch_array($result2);
											echo "<a href=\"person_detail.php?personid=".$line['personid']."\">".$line2['name']."</a>";
										?>
									</p>
									<?php
									
										if(($line['event_type'] == "Stage") || ($line['event_type'] == "Competition")) {
									
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
													$query = "SELECT xtr_users.userid, CONCAT(lastname, ', ', firstname) AS name FROM xtr_traineto, xtr_users, xtr_person WHERE xtr_traineto.userid = xtr_users.userid AND xtr_users.personid = xtr_person.personid AND xtr_traineto.eventid = $eventid";
													$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />$query<br />$result2<br />".mysql_error(), E_USER_ERROR);
													$line2 = mysql_fetch_array($result, MYSQL_ASSOC);
													echo "<a href=\"user_detail.php?id=".$line2['userid']."\">".$line2['name']."</td></tr>";
													
													while($line2 = mysql_fetch_array($result, MYSQL_ASSOC)) {
														echo "<tr><td>&nbsp;</td><td><a href=\"user_detail.php?id=".$line2['userid']."\">".$line2['name']."</a></td></tr>";
													}
													echo "<tr><td>&nbsp;</td><td><a href=\"participateto_listing.php?id=$eventid\">Liste des participants</a></td></tr>";
													echo "</table></p><br />";
													
										}
													
													
										echo "<p><table width=\"100%\"><tr><td align=\"left\" width=\"50%\">";
										if(($line['event_type'] == "Competition") || ($line['event_type'] == "Stage")) {
											echo "<a href=\"traineto_add.php?id=$id\" title=\"Ajouter un Entraineur\"><img src=\"./design/images/icons/16_user_add.png\" /></a>";
											echo "&nbsp;&nbsp;<a href=\"participateto_add.php?id=$id\" title=\"Ajouter un Gymnaste\"><img src=\"./design/images/icons/16_gymnast_add.png\" /></a></td>";
										}
										echo "</td><td align=\"right\">";
										
										if($_SESSION['status_out'] >= 3) {
											echo "<p align=\"right\" class=\"no print\"><a href=\"event_update.php?id=$id\"><img src=\"./design/images/icons/16_Edit.png\" /></a>";
										}
										echo "</td></tr></table>";
									?>
								</fieldset>
							</FORM>
						</tr>
					</td>
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