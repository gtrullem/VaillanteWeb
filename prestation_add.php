<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "prestation_add";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < 1) && ($_SESSION['status_out'] < 2)) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

	$query = "SELECT DATE(lastfinished) FROM xtr_users WHERE userid = ".$_SESSION['uid'];
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (users) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$lastfinished = $line[0];
	
	if(isset($_POST['add'])) {
		$date = $_POST['pdate'];

		// if((intval($mois) >= intval(substr($lastfinished, 5, 2)) && intval($annee) == intval(substr($lastfinished, 0, 4))) || (intval($annee) > intval(substr($lastfinished, 0, 4))))
		if((intval(substr($date, 5, 2)) > intval(substr($lastfinished, 5, 2)) && (intval(substr($date, 0, 4)) >= intval(substr($lastfinished, 0, 4))))|| (intval(substr($date, 0, 4)) > intval(substr($lastfinished, 0, 4)))) {
			$description = mysql_real_escape_string(stripslashes(trim($_POST['description'])));
			$nbhour = $_POST['nbhour'];
			$h_from = $_POST['h_from'];
			$userid = $_SESSION['uid'];
			$h_to = $_POST['h_to'];
			
			$query = "INSERT INTO xtr_prestation (userid, `date`, h_from, h_to, nbhour, description) VALUES ('$userid', '$date', '$h_from', '$h_to', '$nbhour', '$description');";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (prestation) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			
			header("Location: ./prestation_listing.php");
			exit;
		} else 
			$err = "<p class='important' align='center'>Vous n'êtes plus autorisé(e) à ajouter ou modifier des prestations de la date sélectionnée. Veuillez contacter la trèsorière pour de plus amples informations</p>";
	}

	if(!empty($_GET['date'])) {
		$date = $_GET['date'];
		if(intval(substr($lastfinished, 0, 4)) > intval(substr($date, 6, 4)) || (intval(substr($lastfinished, 5, 2)) > intval(substr($date, 3, 2)) && intval(substr($lastfinished, 0, 4)) == intval(substr($date, 6, 4))) && empty($err)) {
			$redirection = "<p class='important' align='center'>Vous n'êtes plus autorisé(e) à ajouter ou modifier les prestations de la date sélectionnée. Veuillez contacter la trèsorière pour de plus amples informations</p>";
			header("Refresh: 4; url=./index.php");
		}
	} else
		$date = date('d/m/Y');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Ajout d'une Prestation Spécifique :.</title>
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
			dateFormat: 'dd/mm/yy', firstDay: 1,
			// dateFormat: 'yy-mm-dd', firstDay: 0,
			initStatus: 'Choisir la date', isRTL: false
		};
		$.datepicker.setDefaults($.datepicker.regional['fr']);

		$(document).ready(function () {
			$('#prestationDate').datepicker({
				// format: 'yy-m-d',
				format: 'm/d/yy',
				date: $('#datepicker').val(),
				// current: $('#from #to').val(),
				starts: 1,
				position: 'r',
				changeMonth: true,
				changeYear: true,
				yearRange: "-1:+1",
				// defaultDate: '-55y',
				onBeforeShow: function () {
					$('#from, #to').datepickerSetDate($('#from, #to').val(), true);
				}
			});
		});

		function checkForm(formulaire)
		{
			
			if(!checkDate(document.formulaire.prestationDate.value)) {
				alert('Veuillez entrer une date correcte.');
				document.formulaire.date.focus();
				return false;
			}

			if(document.formulaire.h_from.value.length == 0){
				alert('Veuillez indiquer l\'heure de début.');
				return false;
			}

			if(document.formulaire.h_to.value.length == 0) { 
				alert('Veuillez indiquer l\'heure de fin.');
				return false;
			}

			var tmp = document.formulaire.nbhour.value.split(":");
			if((tmp[0] <= 0) || (tmp[0] >= 10)) {
				alert('Veuillez vérifier les heures que vous avez entrées.');
				return false;
			}

			if(document.formulaire.description.value.length < 15) { 
				alert('Votre commentaire doit contenir au moins 15 caractères.');
				return false;
			}

			var tmp = document.formulaire.prestationDate.value.split("/");
			document.formulaire.pdate.value = tmp[2]+'-'+tmp[1]+'-'+tmp[0];

			tmp = document.formulaire.nbhour.value.split(":");
			if(tmp[1] == "30")
				document.formulaire.nbhour.value = tmp[0]+".5";
			else
				document.formulaire.nbhour.value = tmp[0]+".0";
			
			return true;
		}
		
		function calculate()
		{
			if(document.formulaire.h_to.value != "") {
				if(document.formulaire.h_from.value != "") {
					var t2 = new Date("September 1, 2010 "+document.formulaire.h_to.value);
					var t1 = new Date("September 1, 2010 "+document.formulaire.h_from.value);
					var diff = new Date();
					diff.setTime(t2-t1);
					
					document.formulaire.nbhour.value = (diff.getHours() -1)+":"+diff.getMinutes();
				}
			}
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
					<h2><a>Ajout d'une Prestation Spécifique</a></h2>
					<br />
					<?php

						if(!empty($redirection))
							echo $redirection;
						else {
					?>
					<table align="center">
						<tr>
							<td>
								<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
									<fieldset>
										<legend>Informations</legend>
										<?php
											if(!empty($err))
												echo $err;
										?>
										<p>
											<label>Moniteur *</label>
											<?php echo $_SESSION['name']; ?>
										</p>
										<p>
											<label>Date *</label>
											<input type="text" name="prestationDate" id="prestationDate" value="<?php echo $date; ?>" size="11" maxlength="10" />
											<input type="hidden" name="pdate" />
										</p>
										<p>
											<label>De *</label>
											<select name="h_from" onChange="calculate()">
												<option value=""> </option>
												<?php
													for($i=0; $i<29; ++$i)
														echo "<option value='".date("H:i:s", mktime(8, (30*$i), 0, 0, 0, 0))."'>".date("H:i", mktime(8, (30*$i), 0, 0, 0, 0))."</option>";
												?>
											</select>
										</p>
										<p>
											<label>A *</label>
											<select name="h_to" onChange="calculate()">
												<option value=""> </option>
												<?php
													for($i=0; $i<29; ++$i)
														echo "<option value='".date("H:i:s", mktime(8, (30*$i), 0, 0, 0, 0))."'>".date("H:i", mktime(8, (30*$i), 0, 0, 0, 0))."</option>";
												?>
											</select>
										</p>
										<p>
											<label>Nombre d'heures *</label>
											<input type="text" name="nbhour" size="4" maxlength="4" READONLY />
										</p>
										<p>
											<label>Commentaires *</label>
											<textarea name="description" rows="10" cols="56"></textarea>
										</p>
									</fieldset>
									<p align="center"><input type="submit" name="add" value="Ajouter" /></p>
								</form>
							</td>
						</tr>
					</table>
					<?php
						}
					?>
				</div>
				<div id="sidebar" class="sidebar">
					<div>
						<div class="widget widget_categories">
							<h2 class="title">Informations</h2>
							<ul>
								<li>La date doit être du format <i>jj/mm/aaaa</i>.</li>
								<br />
								<li>Votre commentaire doit contenir au moins <i>15 caractères</i>.</li>
								<br />
								<li>Le nombre d'heures est calculé automatiquement.</li>
							</ul>
						</div>
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