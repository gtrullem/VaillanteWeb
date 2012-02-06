<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$userid = $_SESSION['uid'];
	if(!empty($_GET['uid']))	$userid = $_GET['uid'];
	
	$function = "selfuserupdate";
	require_once("./CONFIG/config.php");
	require_once("./CLASS/dbusers.class.php");
	
	if(($_SESSION['status_out'] < $line['rightout']) && ($_SESSION['status_in'] < $line['rightin'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	$function = "userotherupdate";
	$query = "SELECT rightin, rightout FROM xtr_functionright WHERE function LIKE '$function'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (functionright) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);
	if(($_SESSION['uid'] != $userid) && ($_SESSION['status_out'] < $line['rightout']) && ($_SESSION['status_in'] < $line['rightin'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

	$database = new DBUsers();
	
	if(isset($_POST['update'])) {
		$user = new User($_POST['pid'], mysql_real_escape_string(stripslashes(trim($_POST['lastname']))), mysql_real_escape_string(stripslashes(trim($_POST['firstname']))), $_POST['phone'], $_POST['gsm'], $_POST['email'], $_POST['birth'], mysql_real_escape_string(stripslashes(trim($_POST['birthplace']))), $_POST['sexe'], $_POST['niss'], mysql_real_escape_string(stripslashes(trim($_POST['address']))), $_POST['box'], $_POST['postal'], mysql_real_escape_string(stripslashes(trim($_POST['city']))), mysql_real_escape_string(stripslashes(trim($_POST['profession']))), $_POST['ffgid'], "Y", $userid, $_POST['login'], null, $_POST['account'], $_POST['trainerlevel'], $_POST['judgelevel'], $_POST['status_in'], $_POST['status_out'], null);

		$database->updateUser($user);
		
		header("Location: user_detail.php?uid=$userid");
		exit;
	}

	$user = $database->getUser($userid);
?>
<!DOCTYPE html PUBLIC "-//W3C//Dtd XHTML 1.0 Strict//EN" "http://www.w3.org/tr/xhtml1/Dtd/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Modification d'un Utilisateur :.</title>

	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />

	<script src="./library/library.js" type="text/javascript"></script>

	<script src="./library/jquery-1.4.4.min.js" type="text/javascript"></script>

	<script src="./library/jquery.maskedinput-1.3.min.js" type="text/javascript"></script>

	<link href="./library/redmond/jquery-ui-1.8.9.custom.css" rel="Stylesheet" type="text/css" />

	<script src="./library/jquery-ui-1.8.9.custom.min.js" type="text/javascript"></script>

	<script type="text/javascript" language="javascript">
		jQuery(function($){
			$("#account").mask("aa99 9999 9999 9999");
		});

		// $.datepicker.regional['fr'] = {
		// 	clearText: 'Effacer', clearStatus: '',
		// 	closeText: 'Fermer', closeStatus: 'Fermer sans modifier',
		// 	prevText: '<Préc', prevStatus: 'Voir le mois précédent',
		// 	nextText: 'Suiv>', nextStatus: 'Voir le mois suivant',
		// 	currentText: 'Courant', currentStatus: 'Voir le mois courant',
		// 	monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
		// 	monthNamesShort: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
		// 	monthStatus: 'Voir un autre mois', yearStatus: 'Voir un autre année',
		// 	weekHeader: 'Sm', weekStatus: '',
		// 	dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
		// 	dayNamesShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
		// 	dayNamesMin: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
		// 	dayStatus: 'Utiliser DD comme premier jour de la semaine', dateStatus: 'Choisir le DD, MM d',
		// 	dateFormat: 'dd/mm/yy', firstDay: 1,
		// 	// dateFormat: 'yy-mm-dd', firstDay: 0,
		// 	initStatus: 'Choisir la date', isRTL: false
		// };
		// $.datepicker.setDefaults($.datepicker.regional['fr']);

		// $(document).ready(function () {
		// 	$('#birthDate').datepicker({
		// 		// format: 'yy-m-d',
		// 		format: 'm/d/yy',
		// 		date: $('#datepicker').val(),
		// 		// current: $('#from #to').val(),
		// 		starts: 1,
		// 		position: 'r',
		// 		changeMonth: true,
		// 		changeYear: true,
		// 		yearRange: "-16:-60",
		// 		defaultDate: '-18y',
		// 		onBeforeShow: function () {
		// 			$('#from, #to').datepickerSetDate($('#from, #to').val(), true);
		// 		}
		// 	});
		// });

		function checkForm(formulaire)
		{

			if(document.formulaire.lastname.value.length < 3) {
				alert('Veuillez introduire un Nom de famille.');
				document.formulaire.lastname.focus();
				return false;
			}

			if(document.formulaire.firstname.value.length < 2){
				alert('Veuillez introduire un prénom.');
				document.formulaire.firstname.focus();
				return false;
			}

			if(!checkDate(document.formulaire.birthDate.value)) {
				alert('Veuillez introduire une date de naissance.');
				document.formulaire.birthDate.focus();
				return false;
			}

			if(!checkNISS(document.formulaire.niss.value, document.formulaire.sexe.value)) {
				document.formulaire.niss.focus();
				return false;
			}

			if(document.formulaire.address.value.length < 4) {
				alert('Veuillez indiquer votre adresse.');
				document.formulaire.address.focus();
				return false;
			}

			if(!checkPostal(document.formulaire.postal.value)) {
				document.formulaire.postal.focus();
				return false;
			}

			if(document.formulaire.city.value.length < 2) {
				alert('Veuillez indiquer la ville de résidence.');
				document.formulaire.city.focus();
				return false;
			}
			
			if(!checkPhone(document.formulaire.phone.value)) {
				document.formulaire.phone.focus();
				return false;
			}
			
			if(!checkGsm(document.formulaire.gsm.value)) {
				document.formulaire.gsm.focus();
				return false;
			}

			document.formulaire.email.value = document.formulaire.email.value.toLowerCase();
			document.formulaire.email.value = strtr(document.formulaire.email.value, "àäâéèêëïîôöùûüç","aaaeeeeiioouuuc");
			if(document.formulaire.email.value.length < 2) {
				alert('Veuillez introduire votre email.');
				document.formulaire.email.focus();
				return false;
			}

			if((document.formulaire.account.value.length > 0) && (!checkIBAN(document.formulaire.account.value))) {
				document.formulaire.account.focus();
				return false;
			}

			///////////////////////////////////////////////////////////////////////
			// Post-processing
			///////////////////////////////////////////////////////////////////////			
			var temp = strtr(document.formulaire.lastname.value, "àäâéèêëïîôöùûüç' -","aaaeeeeiioouuuc");
			document.formulaire.login.value = document.formulaire.firstname.value.substr(0,1) + temp.substr(0,7);
			document.formulaire.login.value = document.formulaire.login.value.toLowerCase();
			
			temp = document.formulaire.birthDate.value.split("/");
			document.formulaire.birth.value = temp[2]+"-"+temp[1]+"-"+temp[0];
			
			// End of function
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
				<div id="post">
					<h2><a>Modification d'un Utilisateur</a></h2>
					<br />
					<table align="center">
						<tr>
							<td>
								<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?uid=".$userid; ?>" onSubmit="return checkForm(this.form)">
								<fieldset>
									<legend>Informations de <i><?php  echo $user->getLastName().", ".$user->getFirstName(); ?></i></legend>
									<p>
										<label>Nom *</label>
										<input type="text" name="lastname" value="<?php echo $user->getLastName(); ?>" size="<?php echo (strlen($user->getLastName())+2); ?>" />
										<input type="hidden" name="pid" value="<?php echo $user->getID(); ?>" />
									</p>
									<p>
										<label>Prénom *</label>
										<input type="text" name="firstname" value="<?php echo $user->getFirstName(); ?>" size="<?php echo (strlen($user->getFirstName())+2); ?>" />
									</p>
									<p>
										<label>Login *</label>
										<?php	echo "&nbsp;".$user->getUsername();	?>
										<input type="hidden" name="login">
									</p>
									<p>
										<label>Sexe *</label>
										<select name="sexe">
											<option value="">Choisissez...</option>
											<option value="M" <?php if($user->getGender() == "M")	echo "selected";	?>>Homme</option>
											<option value="F" <?php if($user->getGender() == "F")	echo "selected";	?>>Femme</option>
										</select>
									</p>
									<p class="important">
										<label>NISS *</label>
										<input type="text" name="niss" value="<?php	echo $user->getNiss(); ?>" size="13" maxlength="11" />
									</p>
									<p>
										<label>Date de naissance *</label>
										<input type="text" name="birthDate" id="birthDate" maxlength="10" value="<?php echo $user->displayBirthDate(); ?>" size="12" />
										<input type="hidden" name="birth" id="birth" />
<!-- 										<input type="text" name="birthday" id="birthday" size="1" maxlength="2" value="<?php	echo substr($user->getBirthDate(), 8, 2); ?>" /> / <input type="text" name="birthmonth" id="birthmonth" size="1" maxlength="2" value="<?php echo substr($user->getBirthDate(), 5, 2); ?>" /> /<input type="text" name="birthyear" id="birthyear" size="3" maxlength="4" value="<?php echo substr($user->getBirthDate(), 0, 4); ?>" /><input type="hidden" name="birth" id="birth" />
 -->
 									</p>
									<p>
										<label>Lieu de naissance </label>
										<input type="text" name="birthplace" value="<?php	echo $user->getBirthPlace(); ?>" size="<?php echo (strlen($user->getBirthPlace())); ?>" />
									</p>
									<p>
										<label>Adresse *</label>
										<input type="text" name="address" value="<?php	echo $user->getAddress();	?>" size="<?php echo (strlen($user->getAddress())+2); ?>" />
									</p>
									<p>
										<label>Boite </label>
										<input type="text" name="box" value="<?php echo $user->getBox(); ?>" size="4" maxlength="4" />
									</p>
									<p>
										<label>Code postal *</label>
										<input type="text" name="postal" value="<?php echo $user->getPostal(); ?>" size="4" maxlength="4" />
									</p>
									<p>
										<label>Ville *</label>
										<input type="text" name="city" value="<?php echo $user->getCity(); ?>" size="<?php echo (strlen($user->getCity())+2); ?>" />
									</p>
									<p>
										<label>Téléphone</label>
										<input type="text" name="phone" value="<?php echo $user->getPhone(); ?>" size="12" maxlength="9" />
									</p>
									<p>
										<label>GSM *</label>
										<input type="text" name="gsm" value="<?php echo $user->getGsm(); ?>" size="14" maxlength="10" />
									</p>
									<p>
										<label>Profession</label>
										<input type="text" name="profession" value="<?php echo $user->getProfession(); ?>" size="<?php (strlen( $user->getProfession())+2); ?>" maxlength="50" />
									</p>
									<p>
										<label>Adresse email *</label>
										<input type="text" name="email" value="<?php echo $user->getEmail(); ?>" size="<?php echo (strlen($user->getEmail())+5); ?>" />
									</p>
									<p>
										<label>N° de licence FFG</label>
										<input type="text" name="ffgid" value="<?php echo $user->getFfgID(); ?>" size="6" maxlength="6" />
									</p>
									<p>
										<label>Carnet d'adresse ?</label>
										<?php 
											if($user->isBookmarked() == 'Y')	echo "&nbsp;Oui";
											else	echo "&nbsp;Non";
										?>
									</p>
									<HR>
									<p class="important">
										<label>N° de Compte (<b>IBAN</b>)</label>
										<input type="text" name="account" id="account" value="<?php echo $user->getAccount(); ?>" size="23" maxlength="19" />&nbsp;<font size="1">(BExx xxxx xxxx xxxx)</font>
									</p>
									<!-- <p>
										<label>Salaire (€/h)</label>
										<input type="text" name="reward" value="<?php echo $user->getReward(); ?>" size="4" maxlength="4" />
									</p> -->
									<?php
										if($_SESSION['status_out'] >= 4) {
									?>
									<p>
										<label>Niveau Entraineur *</label>
										<select name="trainerlevel">
											<?php
												for($i=-1 ; $i<5; $i++) {
													echo "<option value='$i'";
													if($user->getTrainerLevel() == $i) echo " selected";
													echo ">$i</option>";
												}
											?>
										</select>&nbsp;<font size="1">(<?php echo $user->getReward(); ?>€/h)</font>
									</p>
									<p>
										<label>Niveau Juge *</label>
										<select name="judgelevel">
										<?php
											for($i=0 ; $i<5; $i++) {
												echo "<option value='$i'";
												if($user->getJudgeLevel() == $i) echo " selected";
												echo ">$i</option>";
											}
										?>
										</select>
									</p>
									<!--
									<p>
										<label>Statut Pédagogique *</label>
										<select name="status_in">
											<option value=""></option>
											<?php
												// $query = "SELECT * FROM xtr_userright WHERE scopein = 1 AND value < 10 ORDER BY value";
												// $result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (userright) !<br />".$result_out."<br />".mysql_error(), E_USER_ERROR);
											
												// while($linein = mysql_fetch_array($result)) {
												// 	echo "<option value=\"".$linein['value']."\"";
												// 	if($line['status_in'] == $linein['value']) echo " selected";
												// 	echo ">".$linein['label']."</option>";
												// }
											?>
										</select>
									</p>
									<p>
										<label>Statut Gestionnaire *</label>
										<select name="status_out">
											<option value=""></option>
											<?php
												// $query = "SELECT * FROM xtr_userright WHERE scopeout = 1 AND value < 10 ORDER BY value";
												// $result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (userright) !<br />".$result_out."<br />".mysql_error(), E_USER_ERROR);

												// while($lineout = mysql_fetch_array($result)) {
												// 	echo "<option value=\"".$lineout['value']."\"";
												// 	if($line['status_out'] == $lineout['value']) echo " selected";
												// 	echo ">".$lineout['label']."</option>";
												// }
											?>
										</select>
									</p>
									-->
									<?php
										// } else {	
						            ?>
							            <input type="hidden" name="status_in" value="<?php echo $user->getStatusIn(); ?>" />
							            <input type="hidden" name="status_out" value="<?php echo $user->getStatusOut(); ?>" />
						            <?php
										}
						            ?>
						            <br />
						            <p align="center"><input type="submit" name="update" value="Mettre à jour"></p>
									</fieldset>
					            </form>
			           		</td>
			           	</tr>
					</table>
				</div>
			</div>
			<div id="sidebar" class="sidebar">
				<div>
					<div class="widget widget_categories">
						<h2 class="title">Informations</h2>
						<p align="justify">Dans cette page, vous pouvez mettre à jour vos données personnelles. Certains champs, signalés d'une étoile (*), sont obligatoires.<br /><br />Le login sera généré automatiquement à partir de votre Nom et Prénom.<br /><br /><span class="important">Le NISS est <b>OBLIGATOIRE</b>.</span><br /><br /><span class="important">Numéro <b>IBAN</b> est <b>OBLIGATOIRE</b>.</span></p>
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