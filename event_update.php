<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "event_upd";
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] < 2){
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(empty($_GET['eventid'])) {
		header('Location: ./event_listing.php');
		exit;
	}
	
	$id = $_GET['eventid'];
	
	if(isset($_POST['submit'])) {
		$eventtype = $_POST['eventtype'];
		$title = mysql_real_escape_string(stripslashes($_POST['title']));
		$information = mysql_real_escape_string(stripslashes($_POST['information']));
		$placeid = $_POST['place'];
		$begindate = $_POST['begindate'];
		$enddate = $_POST['enddate'];
		$contactid = $_POST['contact'];
		$disciplineid = $_POST['disciplineid'];

		$query = "UPDATE xtr_event SET event_type='$eventtype', title='$title', placeid='$placeid', information='$information', dbegin='$begindate', dend='$enddate', personid='$contactid' WHERE eventid='$id';";
//		echo $query;
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (event) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		for($i=0; $i<sizeof($disciplineid); $i++) {
			// Existence of line ?
			$query = "SELECT eventid FROM xtr_eventisfordisc WHERE eventid='$id' AND disciplineid = '".$disciplineid[$i]."';";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (EIFD) !<br>".$result."<br>".mysql_error(), E_USER_ERROR);
			if(!mysql_fetch_array($result)) {
				// line doesn't exist
				$query = "INSERT INTO xtr_eventisfordisc (eventid, disciplineid) VALUES ('$id', '$disciplineid[$i]');";
//				echo $query."<br />";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (EIFD) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			}
		}
		
		// Delete of old line
		$query = "DELETE FROM xtr_eventisfordisc WHERE eventid='$id' AND disciplineid NOT IN (";
		foreach($disciplineid as $discipline) {
			$query .=$discipline.", ";
		}
		$query = substr($query, 0, -2).");";
//		echo $query."<br />";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : DELETE FAILED (EIFD) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		header("Location: event_listing.php");
		exit;
	}
	
	$query = " SELECT * FROM xtr_event WHERE eventid='$id'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT STAGE (stage) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);
	
	$query ="SELECT disciplineid, title FROM xtr_discipline ORDER BY disciplineid";
	$resultdisc = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Modification d'un évènement :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" language="javascript" src="./library/library.js"></script>
	<script type="text/javascript" language="javascript">
		function checkForm(formulaire)
		{	
			if(document.formulaire.title.value.length < 3) {
				alert('Veuillez mettre un intitulé correcte.');
				document.formulaire.title.focus();
				return false;
			}
			
			if(document.formulaire.information.value.length < 3) {
				alert('Veuillez mettre des informations.');
				document.formulaire.information.focus();
				return false;
			}
			
			if(document.formulaire.place.value == "default") {
				alert('Veuillez choisir le lieu de l\'évènement.');
				document.formulaire.place.focus();
				return false;
			}
			/*
			if(document.formulaire.discipline.value == "default") {
				alert('Veuillez choisir la discipline.');
				document.formulaire.discipline.focus();
				return false;
			}
			
			/*
			if(document.formulaire.contact.value == "default") {
				alert('Veuillez choisir une personne de contact.');
				document.formulaire.contact.focus();
				return false;
			}
			*/
			
			document.formulaire.begindate.value = document.formulaire.beginday.value+'/'+document.formulaire.beginmonth.value+'/'+document.formulaire.beginyear.value;
			if(!dateIsCorrect(document.formulaire.begindate.value)) {
				alert('La date du début stage est incorrecte.');
				document.formulaire.begindate.focus();
				return false;
			}
			
			document.formulaire.enddate.value = document.formulaire.endday.value+'/'+document.formulaire.endmonth.value+'/'+document.formulaire.endyear.value;
			if(!dateIsCorrect(document.formulaire.enddate.value)) {
				alert('La date de fin de stage est incorrecte.');
				document.formulaire.enddate.focus();
				return false;
			}
			
			if(compareDate(document.formulaire.begindate.value, document.formulaire.enddate.value) > 0) {
				alert('La date de début doit précéder la date de fin.')
				document.formulaire.enddate.focus();
				return false;
			}
			
			///////////////////////////////////////////////////////////////////////
			// Post-processing
			///////////////////////////////////////////////////////////////////////
			document.formulaire.begindate.value = document.formulaire.beginyear.value+'-'+document.formulaire.beginmonth.value+'-'+document.formulaire.beginday.value;
			document.formulaire.enddate.value = document.formulaire.endyear.value+'-'+document.formulaire.endmonth.value+'-'+document.formulaire.endday.value;
			
			return true;
		}
	</script>
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
		<div id="frame">
			<div id="content">
				<!-- ========================= BEGIN FORM ====================== -->
				<h2><a>Modification d'un évènement</a></h2>
				<br />
				<table align="center">
					<tr>
						<td>			
							<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?eventid='.$eventid; ?>" ENCTYPE="multipart/form-data" onSubmit="return checkForm(this.form)">
								<fieldset>
									<legend>Détails de l'évènement</legend>
									<p>
										<label>Intitulé *</label>
										<input type="text" name="title" value="<?php echo $line['title']; ?>" size="46" />
									</p>
									<p>
										<label>Type d'évènement *</label>
										<select name="eventtype">
											<option value=""></option>
											<option value="Competition" <?php if($line['event_type'] == "Competition") { echo "selected"; } ?>>Compétition</option>
											<option value="Festivité" <?php if($line['event_type'] == "Festivité") { echo "selected"; } ?>>Festivité</option>
											<option value="Formation" <?php if($line['event_type'] == "Formation") { echo "selected"; } ?>>Formation</option>
											<option value="Stage" <?php if($line['event_type'] == "Stage") { echo "selected"; } ?>>Stage</option>
										</select>
									</p>
									<p>
										<label>Lieu *</label>
										<select name="place">
											<?php
												$query = "SELECT placeid, name FROM xtr_place ORDER BY name";
												$resultplace = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (place) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
												
												while($lineplace = mysql_fetch_array($resultplace)) {
													echo "<option value=\"".$lineplace['placeid']."\"";
													if($line['placeid'] == $lineplace['placeid'])
														echo " selected";
													echo ">".$lineplace['name']."</option>";
												}
											?>
										</select>
									</p>
									<p>
										<label>Informations *</label>
										<textarea name="information" rows="10" cols="56"><?php echo $line['information']; ?></textarea>
									</p>
									<p>
										<table>
											<tr>
												<td><label>Discipline(s) *</label></td>
												<?php
													$queryevent = "SELECT disciplineid FROM xtr_eventisfordisc WHERE eventid='$id' ORDER BY disciplineid;";
											    	$resultevent = mysql_query($queryevent,$connect) or trigger_error("SQL ERROR : SELECT FAILED (NIFD) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
											    	$lineevent = mysql_fetch_array($resultevent);
											    	
													$linedisc = mysql_fetch_array($resultdisc);
													echo "<td><input type=\"checkbox\" name=\"disciplineid[]\" value=\"".$linedisc['disciplineid']."\"";
													if($linedisc['disciplineid'] == $lineevent['disciplineid']) {
														echo " checked";
														$lineevent = mysql_fetch_array($resultevent);
													}
													echo ">".$linedisc['title']."</td>";
													
													while($linedisc = mysql_fetch_array($resultdisc)) {
														echo "<tr><td>&nbsp;</td><td><input type=\"checkbox\" name=\"disciplineid[]\" value=\"".$linedisc['disciplineid']."\"";
														if($linedisc['disciplineid'] == $lineevent['disciplineid']) {
															echo " checked";
															$lineevent = mysql_fetch_array($resultevent);
														}
														echo ">".$linedisc['title']."</td></tr>";
													}
												?>
										</table>
									</p>
									<p>
										<label>Date début *</label>
										<input type="text" name="beginday" id="beginday" size="1" maxlength="2" value="<?php echo substr($line['dbegin'], 8, 2); ?>" />/
										<input type="text" name="beginmonth" id="beginmonth" size="1" maxlength="2" value="<?php echo substr($line['dbegin'], 5, 2); ?>" />/
										<input type="text" name="beginyear" id="beginyear" size="3" maxlength="4" value="<?php echo substr($line['dbegin'], 0, 4); ?>" />
										<input type="hidden" name="begindate" id="begindate">
									</p>
									<p>
										<label>Date fin *</label>
										<input type="text" name="endday" id="endday" size="1" maxlength="2" value="<?php echo substr($line['dend'], 8, 2); ?>" />/
										<input type="text" name="endmonth" id="endmonth" size="1" maxlength="2" value="<?php echo substr($line['dend'], 5, 2); ?>" />/
										<input type="text" name="endyear" id="endyear" size="3" maxlength="4"  value="<?php echo substr($line['dend'], 0, 4); ?>" />
										<input type="hidden" name="enddate" id="enddate">
									</p>
									<p>
										<p>
										<label>Personne de contact *</label>
										<select name="contact">
											<option value="default"></option>
											<?php
												$query = "SELECT personid, CONCAT(lastname, ', ', firstname) AS name FROM vw_contact ORDER BY name";
												$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
												
												while($line2 = mysql_fetch_array($result)) {
													echo "<option value=\"".$line2['personid']."\"";
													if($line2['personid'] == $line['personid']) {
														echo " selected";
													}
													echo ">".$line2['name']."</option>";
												}
											?>
										</select>
									</p>
									<p align="center"><input type="submit" name="submit" value="Modifier"></p>
								</fieldset>
							</form>
						</td>
					</tr>
				</table>
				
				<!-- ========================= END FORM ====================== -->
				
			</div>
			<div id="sidebar" class="sidebar">
				<div>
					<div class="widget widget_categories">
						<h2 class="title">Informations</h2>
						<p align="justify">
							Les champs signalés d'une étoile (*) sont obligatoires.<br />
							<br />L'intitulé doit être composé d'au moins 10 caractères.<br />
							<br />Les informations doivent être composées d'au moins 50 caractères.
						</p>
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