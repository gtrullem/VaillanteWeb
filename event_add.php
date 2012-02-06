<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "event_add";
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] < 2) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

	if(isset($_POST['submit'])) {
		$eventtype = $_POST['eventType'];
		$title = mysql_real_escape_string(stripslashes(trim($_POST['title'])));
		$information = mysql_real_escape_string(stripslashes(trim($_POST['information'])));
		$placeid = $_POST['placeid'];
		$begindate = $_POST['beginDate'];
		$begintime = $_POST['beginTime'];
		$enddate = $_POST['endDate'];
		$endtime = $_POST['endTime'];
		$contactid = $_POST['contactid'];
		
		$query = "INSERT INTO xtr_event (event_type, title, placeid, information, dbegin, eventStartTime, dend, eventEndTime, personid) VALUES ('$eventtype', '$title', '$placeid', '$information', '$begindate', '$startTime', '$enddate', '$endTime', '$contactid')";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (event) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$query = "SELECT LAST_INSERT_ID();";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GET LAST EVENT ID !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		$eventid = mysql_fetch_array($result, MYSQL_NUM);
		$eventid = $eventid[0];

		if(!empty($_POST['internet'])) {
			$query = "SELECT CONCAT(name, ' (', city, ')') AS name FROM xtr_place WHERE placeid = $placeid";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (place) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			$place = mysql_fetch_array($result, MYSQL_NUM);
			$place = $place[0];

			$query = "INSERT INTO wp5_eventscalendar_main (eventTitle, eventDescription, eventLocation, eventStartDate, eventStartTime, eventEndDate, eventEndTime, accessLevel) VALUES ('$title', '$information', '$place', '$begindate', '$startTime', '$enddate', '$endTime', 'public')";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT WP5 FAILED !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		}
		
		$discipline = "";
		$disciplineid = $_POST['disciplineid'];
		$query = "INSERT INTO xtr_eventisfordisc (eventid, disciplineid) VALUES ";
		for($i = 0; $i < sizeof($disciplineid); $i++) {
			$query .= "('$eventid', '$disciplineid[$i]'), ";
			$discipline .= ", ".$disciplineid[$i];
		}
		$query = substr($query, 0, -2);
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (EIFD) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		
		/****************************************/
		/* Email Sending						*/
		/****************************************/
		$discipline = substr($discipline, 2);
		$query = "SELECT CONCAT(lastname, ', ', firstname) as name, email FROM xtr_person, xtr_users WHERE xtr_users.personid = xtr_person.personid AND xtr_users.userid IN (SELECT DISTINCT userid FROM xtr_istrainer, xtr_course, xtr_discipline, xtr_subdiscipline, xtr_linkCourseSeason WHERE xtr_istrainer.lcsid = xtr_linkCourseSeason.lcsid AND xtr_linkCourseSeason.courseid = xtr_course.courseid AND xtr_course.subdisciplineid = xtr_subdiscipline.subdisciplineid AND xtr_subdiscipline.disciplineid = xtr_discipline.disciplineid AND xtr_discipline.disciplineid IN ($discipline))";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (get email for news) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$headers ="From: \"La Vaillante - Extranet\"<extranet@lavaillantetubize.be>\n"; 
		$headers .="Reply-To: extranet@lavaillantetubize.be"."\n"; 
		$headers .="Content-type: text/html; charset=iso-8859-1"."\n";
		$headers .="Content-Transfer-Encoding: 8bit"; 
		$subject = "Extranet News : ".utf8_decode(stripslashes($_POST['title']));
		
		while($line = mysql_fetch_array($result)) {
			$message = "Cher(e) ".$line['name']."<br /><br />Un nouvel évènement a été ajouté au calendrier de La Vaillante Tubize sur l'extranet. Cet évènement concerne une section dans laquelle vous officiez : <a href='http://www.lavaillantetubize.be/Extranet/event_detail.php?id=$eventid'>cliquez ici</a>.<br /><br />".nl2br(stripslashes($_POST['textbody']))."<br /><br />Bien à vous,<br />Bonne fin de journée.<br /><br /><b>L'Equipe Extranet</b>";
			mail($line['email'], $subject, utf8_decode($message), $headers);
		}

		header("Location: event_listing.php");
		exit;
	}
	
	if($_GET['date'])
		$dbegin = explode("/", $_GET['date']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Ajout d'un évènement :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" language="javascript" src="./library/library.js"></script>

		<link href="./library/redmond/jquery-ui-1.8.9.custom.css" rel="Stylesheet" type="text/css" />

	<script src="./library/jquery-1.4.4.min.js" type="text/javascript"></script>
	<script src="./library/jquery-ui-1.8.9.custom.min.js" type="text/javascript"></script>
  
	<script type="text/javascript">
		$.datepicker.regional['fr'] = {
			clearText: 'Effacer', clearStatus: '',
			closeText: 'Fermer', closeStatus: 'Fermer sans modifier',
			prevText: '<Préc', prevStatus: 'Voir le mois précédent',
			nextText: 'Suiv>', nextStatus: 'Voir le mois suivant',
			currentText: 'Courant', currentStatus: 'Voir le mois courant',
			monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
			monthNamesShort: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
			monthStatus: 'Voir un autre mois', yearStatus: 'Voir un autre année',
			weekHeader: 'Sm', weekStatus: '',
			dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
			dayNamesShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
			dayNamesMin: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
			dayStatus: 'Utiliser DD comme premier jour de la semaine', dateStatus: 'Choisir le DD, MM d',
			// dateFormat: 'dd/mm/yy', firstDay: 1,
			dateFormat: 'yy-mm-dd', firstDay: 1,
			initStatus: 'Choisir la date', isRTL: false
		};
		$.datepicker.setDefaults($.datepicker.regional['fr']);

		$(document).ready(function () {
			$('#beginDate').datepicker({
				format: 'm/d/yy',
				date: $('#datepicker').val(),
				starts: 1,
				position: 'r',
				changeMonth: true,
				changeYear: true,
				yearRange: "-1:+3",
				onBeforeShow: function () {
					$('#from, #to').datepickerSetDate($('#from, #to').val(), true);
				}
			});
		});

		$(document).ready(function () {
			$('#endDate').datepicker({
				format: 'm/d/yy',
				date: $('#datepicker').val(),
				starts: 1,
				position: 'r',
				changeMonth: true,
				changeYear: true,
				yearRange: "-1:+3",
				onBeforeShow: function () {
					$('#from, #to').datepickerSetDate($('#from, #to').val(), true);
				}
			});
		});

		function checkForm(formulaire)
		{
			if(document.formulaire.title.value.length < 10) {
				alert('Veuillez mettre un intitulé correcte.');
				document.formulaire.title.focus();
				return false;
			}

			if(document.formulaire.eventType.value.length == 0) {
				alert('Veuillez choisir le type d\'évènement.');
				document.formulaire.eventType.focus();
				return false;
			}
			
			if(document.formulaire.placeid.value.length == 0) {
				alert('Veuillez choisir le lieu de l\'évènement.');
				document.formulaire.placeid.focus();
				return false;
			}

			if(document.formulaire.information.value.length < 50) {
				alert('Veuillez mettre des informations.');
				document.formulaire.information.focus();
				return false;
			}

			if(!document.formulaire.discipline_all.checked) {
				var test = false;
				for(var i = 0; i < document.formulaire.disciplineid.length && !test; i++)
					if(document.formulaire.disciplineid[i].checked)
						test = true;

				if(!test) {
					alert('Veuillez sélectionner au moins une discipline');
					return false;
				}
			}
			
			if(!checkDateUs(document.formulaire.beginDate.value)) {
				alert('La date du début stage est incorrecte.');
				document.formulaire.beginDate.focus();
				return false;
			}
			
			if(!checkDateUs(document.formulaire.endDate.value)) {
				alert('La date de fin de stage est incorrecte.');
				document.formulaire.endDate.focus();
				return false;
			}

			if(document.formulaire.startTime.value.length == 0) {
				alert('Veuillez choisir l\'heure de début.');
				document.formulaire.startTime.focus();
				return false;
			}

			if(document.formulaire.endTime.value.length == 0) {
				alert('Veuillez choisir l\'heure de fin.');
				document.formulaire.endTime.focus();
				return false;
			}

			if(document.formulaire.contactid.value.length == 0) {
				alert('Veuillez définir la personne de contact.');
				document.formulaire.contactid.focus();
				return false;
			}
			
			if(compareDate(document.formulaire.beginDate.value, document.formulaire.endDate.value) > 0) {
				alert('La fin du début doit précéder la date de fin.')
				document.formulaire.enddate.focus();
				return false;
			}
			
			///////////////////////////////////////////////////////////////////////
			// Post-processing
			///////////////////////////////////////////////////////////////////////
			document.formulaire.begindate.value = document.formulaire.beginyear.value+'-'+document.formulaire.beginmonth.value+'-'+document.formulaire.beginday.value;
			document.formulaire.enddate.value = document.formulaire.endyear.value+'-'+document.formulaire.endmonth.value+'-'+document.formulaire.endday.value;
			
			// alert('Tout est OK !');
			return true;
		}
		
		function transmit()
		{
			if(document.formulaire.discipline_all.checked)
				for(var i=0; i <= document.formulaire.disciplineid.length; i++)
					document.formulaire.disciplineid[i].checked = true;
			else
				for(var i=0; i <= document.formulaire.disciplineid.length; i++)
					document.formulaire.disciplineid[i].checked = false;
		}
		
		function checkAll()
		{
			for(var i=0; i <= document.formulaire.disciplineid.length; i++)
				if(document.formulaire.disciplineid[i].checked == false)
					document.formulaire.discipline_all.checked = false;
		}
	</script>
	<noscript>
		<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>
	</noscript>
</head>

<body>
<div id="body">
<?php	require_once("./header.php");	?>
		
	<div id="page" class=" sidebar_right">
		<div class="container">
			<div id="frame">
				<div id="content">
					<h2><a>Ajout d'un évènement</a></h2>
					<table align="center">
						<tr>
							<td>
								<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
								<fieldset>
									<legend>Détails</legend>
									<p>
										<label>Intitulé *</label>
										<input type="text" name="title" size="46" />
									</p>
									<p>
										<label>Type d'évènement *</label>
										<select name="eventType">
											<option value=""></option>
											<option value="Competition">Compétition</option>
											<option value="Festivité">Festivité</option>
											<option value="Formation">Formation</option>
											<option value="Stage">Stage</option>
										</select>
									</p>
									<p>
										<label>Lieu *</label>
										<select name="placeid">
											<option value=""></option>
											<?php
												require_once("./CLASS/dbplace.class.php");

												$database = new DBPlace();

												foreach($database->getPlaces() as $place)
													echo "<option value='".$place->getID()."'>".$place->getName()."</option>";
											?>
										</select>
									</p>
									<p>
										<label>Informations *</label>
										<textarea name="information" rows="10" cols="56"></textarea>
									</p>
									<p>
										<table>
											<tr>
												<td><label>Discipline(s) *</label></td>
												<td><input type="checkbox" name="discipline_all" onClick="transmit()" /> Toutes</td>
											</tr>
											<?php
												require_once("./CLASS/dbdiscipline.class.php");
												$database = new DBDiscipline();

												foreach($database->getDisciplines() as $discipline)
													echo "<tr><td>&nbsp;</td><td><input type='checkbox' name='disciplineid[]' id='disciplineid' value='".$discipline->getID()."' onClick='checkAll()' /> ".$discipline->getTitle()."</td></tr>";
											?>
										</table>
									</p>
									<p>
										<label>Date & Heure de début *</label>
										<input type="text" name="beginDate" id="beginDate" size="15" maxlength="10" />
										&nbsp;
										<select name="startTime">
											<option value=""> </option>
											<?php
												for($i=0; $i<29; ++$i)
													echo "<option value='".date("H:i:s", mktime(8, (30*$i), 0, 0, 0, 0))."'>".date("H:i", mktime(8, (30*$i), 0, 0, 0, 0))."</option>";
											?>
										</select>
									</p>
									<p>
										<label>Date & Heure de fin *</label>
										<input type="text" name="endDate" id="endDate" size="15" maxlength="10" />
										&nbsp;
										<select name="endTime">
											<option value=""> </option>
											<?php
												for($i=0; $i<29; ++$i)
													echo "<option value='".date("H:i:s", mktime(8, (30*$i), 0, 0, 0, 0))."'>".date("H:i", mktime(8, (30*$i), 0, 0, 0, 0))."</option>";
											?>
										</select>
									</p>
									<p>
										<label>Personne de contact *</label>
										<select name="contactid">
											<option value=""></option>
											<?php
												$query = "SELECT personid, CONCAT(lastname, ', ', firstname ) AS name FROM vw_contact ORDER BY lastname, firstname";
												$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (user) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
												
												while($line = mysql_fetch_array($result))
													echo "<option value='".$line['personid']."'>".$line['name']."</option>";
											?>
										</select>
									</p>
									<p>
										<label>Afficher sur internet ?</label>
										<input type="checkbox" id="internet" name="internet" checked/>
									</p>
									<p align="center"><input type="submit" name="submit" value="Ajouter"></p>
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