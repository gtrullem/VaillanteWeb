<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] <= 4) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

	if(isset($_POST['submit'])) {
		// echo "<br /><br /><br />";
		require_once("./CLASS/objectholiday.class.php");
		require_once("./CLASS/dbholiday.class.php");

		$holiday = new Holiday(null, $_POST['begindate'], $_POST['enddate'], mysql_real_escape_string(stripslashes(trim($_POST['information']))));
		$holiday->setHallID($_POST['hallid']);

		// var_dump($holiday);

		$database = new DBHoliday();
		$database->insertHoliday($holiday);

		header("Location: ./holiday_listing.php");
		exit;
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Ajout d'une Indisponibilité :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" language="javascript" src="./library/library.js"></script>
	<SCRIPT language="javascript">

		function transmit()
		{
			if(document.formulaire.hall_all.checked)
				for(var i=0; i < document.formulaire.hallid.length; i++)
					document.formulaire.hallid[i].checked = true;
			else
				for(var i=0; i < document.formulaire.hallid.length; i++)
					document.formulaire.hallid[i].checked = false;
		}
		
		function checkAll()
		{
			for(var i=0; i < document.formulaire.hallid.length; i++)
				if(document.formulaire.hallid[i].checked == false)
					document.formulaire.hall_all.checked = false;
		}

		function checkForm(formulaire)
		{

			document.formulaire.begindate.value = document.formulaire.beginday.value+'/'+document.formulaire.beginmonth.value+'/'+document.formulaire.beginyear.value;
			if(!checkDate(document.formulaire.begindate.value)) {
				alert('La date du début est incorrecte.');
				document.formulaire.begindate.focus();
				return false;
			}
			
			document.formulaire.enddate.value = document.formulaire.endday.value+'/'+document.formulaire.endmonth.value+'/'+document.formulaire.endyear.value;
			if(!checkDate(document.formulaire.enddate.value)) {
				alert('La date de fin est incorrecte.');
				document.formulaire.enddate.focus();
				return false;
			}
			
			if(compareDate(document.formulaire.begindate.value, document.formulaire.enddate.value) > 0) {
				alert('La fin du début doit précéder la date de fin.')
				document.formulaire.enddate.focus();
				return false;
			}

			// if(document.formulaire.hallid.value.length == 0) {
			// 	alert('Veuillez choisir une salle.');
			// 	document.formulaire.hallid.focus();
			// 	return false;
			// }

			if(!document.formulaire.hall_all.checked) {
				var test = false;
				for(var i = 0; i < document.formulaire.hallid.length && !test; i++)
					if(document.formulaire.hallid[i].checked)
						test = true;

				if(!test) {
					alert('Veuillez sélectionner au moins une salle.');
					return false;
				}
			}

			if(document.formulaire.information.value.length < 12) {
				alert('L\'information doit être composée d\'au moins 12 caractères.')
				document.formulaire.information.focus();
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
<?php	require_once("./header.php");	?>
		
	<div id="page" class=" sidebar_right">
		<div class="container">
			<div id="frame">
				<div id="content">
					<h2><a>Ajout d'une Indisponibilité</a></h2>
					<br />
					<table align="center">
						<tr>
							<td>
								<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
								<fieldset>
									<legend>Détails</legend>
									<p>
										<label>Début *</label>
										<input type="text" name="beginday" id="beginday" size="1" maxlength="2" onKeyUp="next(this, 'beginmonth', 2);">/
										<input type="text" name="beginmonth" id="beginmonth" size="1" maxlength="2" onKeyUp="next(this, 'beginyear', 2);">/
										<input type="text" name="beginyear" id="beginyear" size="3" maxlength="4" onKeyUp="next(this, 'endday', 4);">
										<input type="hidden" name="begindate" id="begindate">
									</p>
									<p>
										<label>Fin *</label>
										<input type="text" name="endday" id="endday" size="1" maxlength="2" onKeyUp="next(this, 'endmonth', 2);">/
										<input type="text" name="endmonth" id="endmonth" size="1" maxlength="2" onKeyUp="next(this, 'endyear', 2);">/
										<input type="text" name="endyear" id="endyear" size="3" maxlength="4" onKeyUp="next(this, 'information', 4);">
										<input type="hidden" name="enddate" id="enddate">
									</p>
									<p>
										<table>
											<tr>
												<td><label>Salle de sport *</label></td>
												<td>
													<input type="checkbox" name="hall_all" onClick="transmit()" /> Toutes
												</td>
												<?php
													require_once("./CLASS/dbplace.class.php");

													$database = new DBPlace();

													foreach($database->getHalls() as $hall)
														echo "<tr><td>&nbsp;</td><td><input type='checkbox' name='hallid[]' id='hallid' value='".$hall->getID()."' onClick='checkAll()' /> ".$hall->getName()."</td></tr>";
												?>
										</table>
									</p>
									<p>
										<label>Informations *</label>
										<textarea name="information" id="information" rows="5" cols="56"></textarea>
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
						<p align="justify">Les champs signalés d'une étoile (*) sont obligatoires.<br />
						<br />Les dates doivent respecter le format jj/mm/aaaa.<br />
						<br />L'information doit contenir au moins 12 caractères.</p>
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