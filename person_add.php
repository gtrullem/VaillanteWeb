<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] < 2) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

	if(isset($_POST['submit'])) {
		$birth = $_POST['birth'];
		$lastname = mysql_real_escape_string(stripslashes($_POST['lastname']));
		$firstname = mysql_real_escape_string(stripslashes($_POST['firstname']));
		
		$query = " SELECT personid FROM xtr_person WHERE lastname = '$lastname' AND firstname = '$firstname' AND birth = '$birth';";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

		if(mysql_fetch_array($result)) {
			$err = "Cette personne est déjà dans notre base de données.";
		} else {
			///////////////////////////////////////////////////////////////////////
			// Person's data treatment
			///////////////////////////////////////////////////////////////////////
			$birthplace = mysql_real_escape_string(stripslashes($_POST['birthplace']));
			$profession = mysql_real_escape_string(stripslashes($_POST['profession']));
			$address = mysql_real_escape_string(stripslashes($_POST['address']));
			$city = mysql_real_escape_string(stripslashes($_POST['city']));
						
			$postal = $_POST['postal'];
			$phone = $_POST['phone'];
			$email = $_POST['email'];
			$sexe = $_POST['sexe'];
			$niss = $_POST['niss'];
			$box = $_POST['box'];
			$gsm = $_POST['gsm'];
			
			if(!empty($_POST['addressbook'])) {
				$addressbook = "Y";
			} else {
				$addressbook = "N";
			}

			$query = " INSERT INTO xtr_person (lastname, firstname, birth, birthplace, sexe, niss, address, box, postal, city, phone, gsm, email, profession, addressbook) VALUES ('$lastname', '$firstname', '$birth', '$birthplace', '$sexe', '$niss', '$address', '$box', '$postal', '$city', '$phone', '$gsm', '$email', '$profession', '$addressbook')";
			//echo $query."<br/>";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
						
			if(!empty($_POST['addressbook'])) {
				header("Location: ./addressbook.php");;
			} else {
				header("Location: ./person_listing.php");
			}
			exit;
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Ajout d'une personne :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<script type="text/javascript" language="javascript" src="./library/library.js"></script>
	<script type="text/javascript" language="javascript">
		function checkForm(formulaire)
		{
			///////////////////////////////////////////////////////////////////////
			// Pre-processing
			///////////////////////////////////////////////////////////////////////
			document.formulaire.birth.value = document.formulaire.birthday.value+'/'+document.formulaire.birthmonth.value+'/'+document.formulaire.birthyear.value;
			document.formulaire.email.value = document.formulaire.email.value.toLowerCase();
			document.formulaire.email.value = strtr(document.formulaire.email.value, "àäâéèêëïîôöùûüç","aaaeeeeiioouuuc");
			
			if(document.formulaire.lastname.value.length < 2) {
				alert('Veuillez indiquer le nom de famille.');
				document.formulaire.lastname.focus();
				return false;
			}

			if(document.formulaire.firstname.value < 2) {
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

			if((!document.formulaire.sexe[0].checked) && (!document.formulaire.sexe[1].checked)) {
				alert('Veuillez indiquer le sexe.');
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

			reg = new RegExp("^04[0-9]{8}$");
			if(!reg.test(document.formulaire.gsm.value)) {
				alert('Veuillez indiquer un numéro de GSM correct.');
				document.formulaire.gsm.focus();
				return false;
			}

			if(document.formulaire.email.value.length < 6) {
				alert('Veuillez indiquer une adresse email.');
				document.formulaire.email.focus();
				return false;
			}

			document.formulaire.address.value = document.formulaire.address.value+", "+document.formulaire.number.value;
			if(document.formulaire.address.value.length < (document.formulaire.number.length + 2)) {
				document.formulaire.address.value = document.formulaire.address.value.substr(0, (document.formulaire.address.value.length - (document.formulaire.number.lenght + 2)));
				alert('Veuillez indiquer l\'adresse.');
				document.formulaire.address.focus();
				return false;
			}

			///////////////////////////////////////////////////////////////////////
			// Post-processing
			///////////////////////////////////////////////////////////////////////
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
				<!-- ========================= BEGIN FORM ====================== -->
				<H2><a>Ajout d'une personne</a></H2>
				<table align="center">
					<tr>
						<td>
							<FORM class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onSubmit="return checkForm(this.form);">
								<fieldset>
									<legend>Informations de la personne</legend>
									<?php
										if(isset($err)) {
											echo "<p align=\"center\" class=\"important\">".$err."</p>";
										}
									?>
									<p>
										<label>Nom *</label>
										<input type="text" name="lastname" id="lastname" size="25" maxlength="50" />
									</p>
									<p>
										<label>Prénom *</label>
										<input type="text" name="firstname" id="firstname" size="15" maxlength="50" />
									</p>
									<p>
										<label>Date de naissance</label>
										<input type="text" name="birthday" id="birthday" size="1" maxlength="2" onKeyUp="next(this, 'birthmonth', 2);">/
										<input type="text" name="birthmonth" id="birthmonth" size="1" maxlength="2" onKeyUp="next(this, 'birthyear', 2);">/
										<input type="text" name="birthyear" id="birthyear" size="3" maxlength="4" onKeyUp="next(this, 'birthplace', 4);">
										<input type="hidden" name="birth" id="birth">
									</p>
									<p>
										<label>Lieu de naissance</label>
										<input type="text" name="birthplace" id="birthplace" size="15" maxlength="50" />
									</p>
									<p>
										<label>Sexe *</label>
										<input type="radio" name="sexe" value="M">Homme &nbsp;&nbsp;<input type="radio" name="sexe" value="F">Femme
									</p>
									<p>
										<label>NISS</label>
										<input type="text" name="niss" id="niss" size="12" maxlength="11" onKeyUp="next(this, 'address', 11);" />
									</p>
									<p>
										<label>Adresse (principale) *</label>
										<input type="text" id="address" name="address" size="25" maxlength="100" />&nbsp;n° <input type="text" id="number" name="number" size="3" maxlength="3" />
									</p>
									<p>
										<label>Boite</label>
										<input type="text" name="box" id="box" size="4" maxlength="4" />
									</p>
									<p>
										<label>Code postal *</label>
										<input type="text" name="postal" id="postal" size="6" maxlength="4" onKeyUp="next(this, 'city', 4);" />
									</p>
									<p>
										<label>Ville *</label>
										<input type="text" name="city" id="city" size="20" maxlength="50" />
									</p>
									<p>
										<label>Téléphone (principal)</label>
										<input type="text" name="phone" id="phone" size="9" maxlength="9" onKeyUp="next(this, 'gsm', 9);" />
									</p>
									<p>
										<label>GSM *</label>
										<input type="text" name="gsm" id="gsm" size="11" maxlength="10" onKeyUp="next(this, 'email', 10);" />
									</p>
									<p>
										<label>email *</label>
										<input type="text" name="email" id="email" />
									</p>
									<p>
										<label>Profession</label>
										<input type="text" name="profession" id="profession" size="25" maxlength="30" />
									</p>
									<p>
										<label>Carnet d'adresse</label>
										<input type="checkbox" name="addressbook" id="addressbook" />
									</p>
									<p align="center"><input type="submit" name="submit" value="Ajouter"></p>
								</fieldset>
							</FORM>
						</td>
					</tr>
				</table>
				<!-- ========================== END FORM ======================= -->				
			</div>
			<div id="sidebar" class="sidebar">
				<div>
					<div class="widget widget_categories">
						<h2 class="title">Informations</h2>
						<p align="justify">Les champs signalés d'une étoile (*) sont obligatoires.<br /><br />Mettre une personne dans le <i>Carnet d'adresse</i> permet, entre autre, de pouvoir l'utiliser comme <i>personne de contact</i> d'un évènement.</p>
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