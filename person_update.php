<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < 1) && ($_SESSION['status_out'] < 2)) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(empty($_GET['id']) && empty($_GET['personid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=personne&referrer=person_listing.php");
		exit;
	}
	
	if(!empty($_GET['id']))	$personid = $_GET['id'];
	else	$personid = $_GET['personid'];


	if(isset($_POST['submit'])) {
		$city = mysql_real_escape_string(stripslashes(trim($_POST['city'])));
		$address = mysql_real_escape_string(stripslashes(trim($_POST['address'])));
		$lastname = mysql_real_escape_string(stripslashes(trim($_POST['lastname'])));
		$firstname = mysql_real_escape_string(stripslashes(trim($_POST['firstname'])));
		$birthplace = mysql_real_escape_string(stripslashes(trim($_POST['birthplace'])));
		$profession = mysql_real_escape_string(stripslashes(trim($_POST['profession'])));
		
		$postal = $_POST['postal'];
		
		if(strlen($_POST['birth']) >= 8)	$birth = $_POST['birth']; 
		else	$birth = 'NULL';

		$phone = $_POST['phone'];
		$email = $_POST['email'];
		
		if(strlen($_POST['ffgid']) > 3)	$ffgid = $_POST['ffgid'];
		else	$ffgid = 'NULL';

		$sexe = $_POST['sexe'];
		$niss = $_POST['niss'];
		$box = $_POST['box'];
		$gsm = $_POST['gsm'];
		if(!empty($_POST['addressbook']))	$addressbook = "Y";
		else	$addressbook = "N";
		
		$query = " UPDATE xtr_person SET lastname='$lastname', firstname='$firstname', birth='$birth', birthplace='$birthplace', sexe='$sexe', niss='$niss', address='$address', box='$box', postal='$postal', city='$city', phone='$phone', gsm='$gsm', email='$email', profession='$profession', ffgid='$ffgid', addressbook='$addressbook' WHERE personid='$personid'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
					
		header("Location: ./person_detail.php?id=".$personid);
		exit;
	}


	$query = "SELECT userid FROM xtr_users WHERE personid = $personid";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (user) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	if($line = mysql_fetch_array($result))
		$err = "Cette personne est un Utilisateur de l'extranet. Veuillez modifier son profil via la section <a href='./user_listing.php'>Utilisateurs</a>. Merci.";
	
	$query = "SELECT * FROM xtr_person WHERE personid = $personid";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);
	
	
	if($line['resp1id'] == '' && $line['resp2id'] = '')
		$msg = "Cette personne n'a pas de responsable. Vous ne pouvez donc pas lui associer un cours.";
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Personne :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<script type="text/javascript" language="javascript" src="./library/library.js"></script>
	<script type="text/javascript" language="javascript">
		function checkForm(formulaire)
		{
			///////////////////////////////////////////////////////////////////
			// Pre-processing
			///////////////////////////////////////////////////////////////////
			document.formulaire.birth.value = document.formulaire.birthday.value+'/'+document.formulaire.birthmonth.value+'/'+document.formulaire.birthyear.value;
			document.formulaire.email.value = document.formulaire.email.value.toLowerCase();
			
			if(document.formulaire.lastname.value.length < 3) {
				alert('Veuillez indiquer le nom de famille.');
				document.formulaire.lastname.focus();
				return false;
			}
			
			if(document.formulaire.firstname.value.length < 3) {
				alert('Veuillez indiquer le prénom.');
				document.formulaire.firstname.focus();
				return false;
			}
			
			/*
			if(!dateIsCorrect(document.formulaire.birth.value)) {
				alert('Date de naissance incorrecte.');
				return false;
			}
			*/
			
			if(document.formulaire.address.value.length < 3) {
				alert('Veuillez indiquer l\'adresse.');
				document.formulaire.address.focus();
				return false;
			}
			
			if(!checkPostal(document.formulaire.postal.value)) {
				alert('Veuillez indiquer le code postal principal de l\'inscrit.');
				document.formulaire.postal.focus();
				return false;
			}
			
			if(document.formulaire.city.value.length < 2) {
				alert('Veuillez indiquer la ville de résidence.');
				document.formulaire.city.focus();
				return false;
			}
			
			///////////////////////////////////////////////////////////////////
			// Post-processing
			///////////////////////////////////////////////////////////////////
			document.formulaire.birth.value = document.formulaire.birthyear.value+'-'+document.formulaire.birthmonth.value+'-'+document.formulaire.birthday.value;
			
			// End function
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
			<h2><a>Mise à jour de <?php echo $line['lastname'].", ".$line['firstname']?></a></h2>
			<br />
			<table align="center">
				<tr>
					<td>
						<?php 
							if(!empty($err))
								echo "<p align=\"center\" class=\"important\">".$err."</p>";
							else {
						?>
						<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?personid=".$personid; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form);">
						<fieldset>
							<legend>Informations</i></legend>
							<p>
								<label>Nom *</label>
								<input type="text" name="lastname" size="25" maxlength="50" value="<?php echo $line['lastname']; ?>" />
							</p>
							<p>
								<label>Prénom *</label>
								<input type="text" name="firstname" size="15" maxlength="50" value="<?php echo $line['firstname']; ?>" />
							</p>
							<p>
								<label>Date de naissance*</label>
								<input type="text" name="birthday" id="birthday" size="1" maxlength="2" value="<?php echo substr($line['birth'], 8, 2); ?>" />/
								<input type="text" name="birthmonth" id="birthmonth" size="1" maxlength="2" value="<?php echo substr($line['birth'], 5, 2); ?>" />/
								<input type="text" name="birthyear" id="birthyear" size="3" maxlength="4" value="<?php echo substr($line['birth'], 0, 4); ?>" />
								<input type="hidden" name="birth" id="birth">
							</p>
							<p>
								<label>Lieu de naissance</label>
								<input type="text" name="birthplace" size="15" maxlength="50" value="<?php echo stripslashes($line['birthplace']); ?>" />
							</p>
							<p>
								<label>Sexe*</label>
								<input type="radio" name="sexe" value="M" <?php if($line['sexe'] == "M") { echo "checked"; } ?>>Masculin &nbsp;&nbsp;
								<input type="radio" name="sexe" value="F" <?php if($line['sexe'] == "F") { echo "checked"; } ?>>Féminin
							</p>
							<p>
								<label>NISS*</label>
								<input type="text" name="niss" maxlength="11" value="<?php echo $line['niss']; ?>" />
							</p>
							<p>
								<label>Adresse *</label>
								<input type="text" name="address" size="30" maxlength="100" value="<?php echo $line['address']; ?>" />
							</p>
							<p>
								<label>Boite</label>
								<input type="text" name="box" size="4" maxlength="4" value="<?php echo $line['box']; ?>" />
							</p>
							<p>
								<label>Code postal *</label>
								<input type="text" name="postal" size="6" maxlength="4" value="<?php echo $line['postal']; ?>" />
							</p>
							<p>
								<label>Ville *</label>
								<input type="text" name="city" size="20" maxlength="50" value="<?php echo $line['city']; ?>" />
							</p>
							<p>
								<label>Profession</label>
								<input type="text" name="profession" size="20" maxlength="50" value="<?php echo $line['profession']; ?>" />
							</p>
							<p>
								<label>Téléphone (principal)</label>
								<input type="text" name="phone" size="11" value="<?php echo $line['phone']; ?>" maxlength="9" />
							</p>
							<p>
								<label>GSM</label>
								<input type="text" name="gsm" size="12" value="<?php echo $line['gsm']; ?>" maxlength="10" />
							</p>
							<p>
								<label>email</label>
								<input type="text" name="email" value="<?php echo $line['email']; ?>" size="35" />
							</p>
							<p>
								<label>N° de licence</label>
								<input type="text" name="ffgid" size="10" value="<?php echo $line['ffgid']; ?>" maxlength="11" />
							</p>
							<p>
								<label>Carnet d'adresse ?</label>
								<input type="checkbox" name="addressbook" <?php if($line['addressbook'] == 'Y') { echo "checked"; } ?> />
							</p>
							<?php
								if(!empty($msg))
									echo "<p align='center' class='important'>".$msg."</p>";
								//else {
							?>
							<!--
							<p>
								<label><a href="./isaffiliate_add.php?id=<?php echo $line['personid']; ?>">Affilier à un cours</a></label>
							</p>
							-->
							<?php
							//	}
							?>
							<p align="center"><input type="submit" name="submit" value="Mettre à jour"></p>
							</fieldset>
						</form>
						<?php
							}
						?>
					</td>
				</tr>
			</table>
			</div>
			<div id="sidebar" class="sidebar">
				<div>
					<div class="widget widget_categories">
						<h2 class="title">Informations</h2>
						<p align="justify">Les champs signalés d'une étoile (*) sont obligatoires.</p>
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