<?php

	$servername='mysql5-6.start';				// <- Server	// localhost	//	mysql5-6.start
	$dbusername='lavailla_01';						// <- Username	// root	//	lavailla_01
	$dbpassword='lavailla01';						// <- password	// root	//	lavailla01
	$dbname='lavailla_01';						// <- Database	// vaillante	//	lavailla_01
	
	// Database connection & selection
	$connect = mysql_connect($servername,$dbusername,$dbpassword) or die("Impossible de se connecter : " . mysql_error());
	$selected_db = mysql_select_db($dbname, $connect) or die('Could not select database.');
	
	// Local Configuration
	mysql_query("SET NAMES 'utf8'");
	setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8', 'fra');

	if(isset($_POST['submit'])) {		
		$niss = $_POST['niss'];
		$query = "SELECT personid FROM xtr_preins WHERE niss = '$niss'";
		// echo "<br /><br /><br />".$query."<br />";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (preins) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);

		if(mysql_fetch_array($result)) {
			$err = "Le gymnaste est déjà inscrit";
		} else {
			///////////////////////////////////////////////////////////////////////
			// Responsable 1 data treatment
			///////////////////////////////////////////////////////////////////////
			$city = mysql_real_escape_string(stripslashes($_POST['city1']));
			$address = mysql_real_escape_string(stripslashes($_POST['fulladdress1']));
			$lastname = mysql_real_escape_string(stripslashes($_POST['lastname1']));
			$firstname = mysql_real_escape_string(stripslashes($_POST['firstname1']));
			$profession = mysql_real_escape_string(stripslashes($_POST['profession1']));
			$gsm = $_POST['gsm1'];
			$box = $_POST['box1'];
			$sexe = $_POST['sexe1'];
			$type = $_POST['type1'];
			$email1 = $_POST['email1'];
			$phone = $_POST['phone1'];
			$postal = $_POST['postal1'];
			$savelastname1 = $lastname;
			
			$query = "INSERT INTO xtr_preins (lastname, firstname, sexe, address, box, postal, city, phone, gsm, email, profession) VALUES ('$lastname', '$firstname', '$sexe', '$address', '$box', '$postal', '$city', '$phone', '$gsm', '$email1', '$profession') ";
			// echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (preins1) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			
			$query = "SELECT LAST_INSERT_ID()";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GET LAST ID !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			$resp1id = mysql_fetch_array($result, MYSQL_NUM);
			$resp1id = $resp1id[0];
			
			///////////////////////////////////////////////////////////////////////
			// Responsable 2 data treatment
			///////////////////////////////////////////////////////////////////////
			if(!empty($_POST['lastname2'])) {
				$city = mysql_real_escape_string(stripslashes($_POST['city2']));
				$address = mysql_real_escape_string(stripslashes($_POST['fulladdress2']));
				$lastname = mysql_real_escape_string(stripslashes($_POST['lastname2']));
				$firstname = mysql_real_escape_string(stripslashes($_POST['firstname2']));
				$profession = mysql_real_escape_string(stripslashes($_POST['profession2']));
				$gsm = $_POST['gsm2'];
				$box = $_POST['box2'];
				$sexe = $_POST['sexe2'];
				$type = $_POST['type2'];
				$phone = $_POST['phone2'];
				$email2 = $_POST['email2'];
				$postal = $_POST['postal2'];
				$savelastname2 = $lastname;
				
				$query = " INSERT INTO xtr_preins (lastname, firstname, sexe, address, box, postal, city, phone, gsm, email, profession) VALUES ('$lastname', '$firstname', '$sexe', '$address', '$box', '$postal', '$city', '$phone', '$gsm', '$email2', '$profession') ";
				// echo $query."<br />";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (preins2) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
				
				$query = "SELECT LAST_INSERT_ID()";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GET LAST ID !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
				$resp2id = mysql_fetch_array($result, MYSQL_NUM);
				$resp2id = $resp2id[0];
			}
			
			$lastname = mysql_real_escape_string(stripslashes($_POST['lastname']));
			$firstname = mysql_real_escape_string(stripslashes($_POST['firstname']));
			$birthplace = mysql_real_escape_string(stripslashes($_POST['birthplace']));
			$address = mysql_real_escape_string(stripslashes($_POST['fulladdress']));
			$city = mysql_real_escape_string(stripslashes($_POST['city']));
			$postal = $_POST['postal'];
			$phone = $_POST['phone'];
			$email = $_POST['email'];
			$sexe = $_POST['sexe'];
			$gsm = $_POST['gsm'];
			$box = $_POST['box'];
			$birth = $_POST['birth'];
			$type1 = $_POST['type1'];
			$type2 = $_POST['type2'];
			$disclist = substr($_POST['disclist'], 2, strlen($_POST['disclist']));
			
			// GYMNAST'S INSERTION
			$query = " INSERT INTO xtr_preins (lastname, firstname, birth, birthplace, sexe, niss, address, box, postal, city, phone, gsm, email, resp1id, type1, resp2id, type2, disclist) VALUES ('$lastname', '$firstname', '$birth', '$birthplace', '$sexe', '$niss', '$address', '$box', '$postal', '$city', '$phone', '$gsm', '$email', '$resp1id', '$type1', '$resp2id', '$type2', '$disclist') ";
			// echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (preins) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			
			$msg = "Enregistrement effectué...";
			
			$query = "SELECT LAST_INSERT_ID()";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GET LAST ID !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			$gymid = mysql_fetch_array($result, MYSQL_NUM);
			$gymid = $gymid[0];
			
			$headers ="From: \"La Vaillante - Extranet\"<extranet@lavaillantetubize.be>\n"; 
			$headers .="Reply-To: extranet@lavaillantetubize.be"."\n"; 
			$headers .="Content-type: text/html; charset=iso-8859-1"."\n";
			$headers .="Content-Transfer-Encoding: 8bit"; 
			$subject = "Inscription : Confirmation";
			
			if(!empty($email1)) {
				$respid = $resp1id;
				$savelastname = $savelastname1;
				$message = utf8_decode(nl2br(stripslashes("Bonjour,<br /><br />Ceci est un mail de confirmation d'inscription, sous réserve de places disponibles, d'un gymnaste au club La Vaillante de Tubize. Veuillez cliquer sur le lien qui suit afin de confirmer l'inscription de l'élève : <a href=\"http://www.lavaillantetubize.be/inscription_validate.php?id=$respid&idg=$gymid&ln=$savelastname\">confirmation</a>.<br /><br />Si le lien ne fonctionne pas, veuillez envoyer un mail à l'adresse suivante : <a href=\"mailto:extranet@lavaillantetubize.be\">extranet@lavaillantetubize.be</a><br />Si vous recevez cet email par erreur, veuillez l'ignorer.<br /><br />Bonne journée,<br />Bien à vous.<br /><br />La Vaillante Tubize - Extranet")));
				mail($email1, $subject, $message, $headers);
				$msg .= "<br />Email de confirmation envoyé au premier responsable.";
			}
			
			if(!empty($email2))	{
				$respid = $resp2id;
				$savelastname = $savelastname2;
				$message = utf8_decode(nl2br(stripslashes("Bonjour,<br /><br />Ceci est un mail de confirmation d'inscription, sous réserve de places disponibles, d'un gymnaste au club La Vaillante de Tubize. Veuillez cliquer sur le lien qui suit afin de confirmer l'inscription de l'élève : <a href=\"http://www.lavaillantetubize.be/inscription_validate.php?id=$respid&idg=$gymid&ln=$savelastname\">confirmation</a>.<br /><br />Si le lien ne fonctionne pas, veuillez envoyer un mail à l'adresse suivante : <a href=\"mailto:extranet@lavaillantetubize.be\">extranet@lavaillantetubize.be</a><br />Si vous recevez cet email par erreur, veuillez l'ignorer.<br /><br />Bonne journée,<br />Bien à vous.<br /><br />La Vaillante Tubize - Extranet")));
				mail($email2, $subject, $message, $headers);
				$msg .= "<br />Email de confirmation envoyé au second responsable.";
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Pré-Inscription :.</title>

	<link href="./Extranet/design/style.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="./Extranet/library/redmond/jquery-ui-1.8.9.custom.css" rel="Stylesheet" type="text/css" />

	<script src="./Extranet/library/jquery-1.4.4.min.js" type="text/javascript"></script>
	<script src="./Extranet/library/jquery-ui-1.8.9.custom.min.js" type="text/javascript"></script>
	<script src="./Extranet/library/library.js" type="text/javascript"></script>
	
	
	<script type="text/javascript">

		function copyInfo()
		{
			if(document.formulaire.adr.checked) {
				document.formulaire.address1.value = document.formulaire.address.value;
				document.formulaire.address2.value = document.formulaire.address.value;
				document.formulaire.number1.value = document.formulaire.number.value;
				document.formulaire.number2.value = document.formulaire.number.value;
				document.formulaire.box1.value = document.formulaire.box.value;
				document.formulaire.box2.value = document.formulaire.box.value;
				document.formulaire.postal1.value = document.formulaire.postal.value;
				document.formulaire.postal2.value = document.formulaire.postal.value;
				document.formulaire.city1.value = document.formulaire.city.value;
				document.formulaire.city2.value = document.formulaire.city.value;
				document.formulaire.phone1.value = document.formulaire.phone.value;
				document.formulaire.phone2.value = document.formulaire.phone.value;
			} else {
				document.formulaire.address1.value = "";
				document.formulaire.address2.value = "";
				document.formulaire.number1.value = "";
				document.formulaire.number2.value = "";
				document.formulaire.box1.value = "";
				document.formulaire.box2.value = "";
				document.formulaire.postal1.value = "";
				document.formulaire.postal2.value = "";
				document.formulaire.city1.value = "";
				document.formulaire.city2.value = "";
				document.formulaire.phone1.value = "";
				document.formulaire.phone2.value = "";
			}
		}

		function checkForm(formulaire)
		{
			/********************************************************************************/
			/*	Vérification du gymnaste													*/
			/********************************************************************************/
			if(document.formulaire.lastname.value.length < 3) {
				alert('Veuillez indiquer le nom de l\'inscrit.');
				document.formulaire.lastname.focus();
				return false;
			}

			if(document.formulaire.firstname.value.length < 3) {
				alert('Veuillez indiquer le prénom de l\'inscrit.');
				document.formulaire.firstname.focus();
				return false;
			}
			
			if(document.formulaire.sexe.value.length == 0) {
				alert('Veuillez indiquer le sexe de l\'inscrit.');
				return false;
			}
			
			document.formulaire.birth.value = document.formulaire.birthday.value+'/'+document.formulaire.birthmonth.value+'/'+document.formulaire.birthyear.value;
			if(!checkDate(document.formulaire.birth.value)) {
				alert('Date de naissance incorrecte.');
				return false;
			}
			
			if(!checkNISS(document.formulaire.niss.value, document.formulaire.sexe.value)) {
				alert('Numéro NISS de l\'inscrit incorrecte.');
				document.formulaire.niss.focus();
				return false;
			}

			// if(document.formulaire.numbertype.value == "niss") {
			// 	var reg = new RegExp("^[0-9]{11}$");
			// 	if(!reg.test(document.formulaire.number.value)) {
			// 		alert('Veuillez indiquer un numéro d\'identification national correct.');
			// 		document.formulaire.numbertype.focus();
			// 		return false;
			// 	}

			// 	if((97 - (Math.round(document.formulaire.number.value.substring(0,9))%97)) != (document.formulaire.number.value%100)) {
			// 		if((97 - (2000000000+(Math.round(document.formulaire.number.value.substring(0,9)))%97)) != (document.formulaire.number.value%100)) {
			// 			alert('Veuillez indiquer un numéro d\'identification national correct.');
			// 			document.formulaire.number.focus();
			// 			return false;
			// 		}
			// 	}

			// 	if((((Math.floor(document.formulaire.number.value.substring(8,9)) % 2 ) == 0) && (document.formulaire.gender.value == "M")) || (((Math.floor(document.formulaire.number.value.substring(8,9)) % 2 ) == 1) && (document.formulaire.gender.value == "F"))) {
			// 		alert('Le Numéro d\'Identification National ne correspond pas avec le genre choisi.');
			// 		document.formulaire.number.focus();
			// 		return false;
			// 	}

			// 	// if(((Math.floor(document.formulaire.number.value / 100)/2) == Math.round(Math.floor(document.formulaire.number.value / 100)/2)) && (document.formulaire.gender.value == "F")) {
			// 	// 	alert('Le Numéro d\'Identification National ne correspond pas avec le genre choisi.');
			// 	// }
			// } else {
			// 	if(document.formulaire.number.value.length < 6) {
			// 		alert('Veuillez indiquer un numéro de passport (ex : AB1234).');
			// 		document.formulaire.number.focus();
			// 		return false;
			// 	}
			// }
			
			if(document.formulaire.number.value.length < 1) {
				alert('Veuillez indiquer le numéro de l\'inscrit.');
				document.formulaire.number.focus();
				return false;
			}
			
			document.formulaire.fulladdress.value = document.formulaire.address.value+", "+document.formulaire.number.value;
			if(document.formulaire.fulladdress.value.length <= (document.formulaire.number.value.length + 2)) {
//				document.formulaire.address.value = document.formulaire.address.value.substr(0, (document.formulaire.address.value.length - (document.formulaire.number.lenght + 2)));
				alert('Veuillez indiquer l\'adresse.');
				document.formulaire.address.focus();
				return false;
			}

			if(!checkPostal(document.formulaire.postal.value)) {
				alert('Veuillez indiquer le code postal principal de l\'inscrit.');
				document.formulaire.postal.focus();
				return false;
			}

			if(document.formulaire.city.value < 2) {
				alert('Veuillez indiquer la ville de résidence principale de l\'inscrit.');
				document.formulaire.city.focus();
				return false;
			}
			
//			if((document.formulaire.email.value.length != 0)){// && !checkEmail(document.formulaire.email.value)) {
//				alert('Veuillez indiquer une adresse email correcte.');
//				document.formulaire.email.focus();
//				return false;
//			}

			var test = false;
			document.formulaire.disclist.value = "";
			for(var i=0; i < document.formulaire.discipline.length; i++)
				if(document.formulaire.discipline[i].checked) {
					test = true;
					document.formulaire.disclist.value += ", "+document.formulaire.discipline[i].value;
				}
					
			if(!test) {
				alert('Veuillez choisir au moins une discipline');
				return false;
			}

			/********************************************************************************/
			/*	Vérification du responsable 1												*/
			/********************************************************************************/
			if(document.formulaire.lastname1.value.length < 2) {
				alert('Veuillez indiquer le nom du premier responsable.');
				document.formulaire.lastname1.focus();
				return false;
			}

			if(document.formulaire.firstname1.value.length < 2) {
				alert('Veuillez indiquer le prénom du premier responsable.');
				document.formulaire.firstname1.focus();
				return false;
			}
			
			if(document.formulaire.sexe1.value.length == 0){
				alert('Veuillez indiquer le sexe du premier responsable.');
				return false;
			}

			if(!checkNISS(document.formulaire.niss1.value, document.formulaire.sexe1.value)) {
				alert('Numéro NISS du premier responsable incorrecte.');
				document.formulaire.niss1.focus();
				return false;
			}
			
			if(document.formulaire.type1.value.length == 0) {
				alert('Veuillez choisir le lien de parenté du premier responsable.');
				document.formulaire.type1.focus();
				return false;
			}
	
			if(document.formulaire.number1.value.length < 1) {
				alert('Veuillez indiquer le numéro du premier responsable.');
				document.formulaire.number1.focus();
				return false;
			}
			
			document.formulaire.fulladdress1.value = document.formulaire.address1.value+", "+document.formulaire.number1.value;
			if(document.formulaire.fulladdress1.value.length <= (document.formulaire.number1.value.length + 2)) {
				alert('Veuillez indiquer l\'adresse du premier responsable.');
				document.formulaire.address1.focus();
				return false;
			}

			if(!checkPostal(document.formulaire.postal1.value)) {
				alert('Code postal du premier responsable incorrect.');
				document.formulaire.postal1.focus();
				return false;
			}

			if(document.formulaire.city1.value < 2) {
				alert('Ville de résidence du premier responsable incorrecte.');
				document.formulaire.city1.focus();
				return false;
			}
			
			if(!checkGsm(document.formulaire.gsm1.value) && !checkPhone(document.formulaire.phone1.value)) {
				alert('Numéro de GSM et/ou de Téléphone du premier responsable incorrect.');
				document.formulaire.phone1.focus();
				return false;
			}
			
//			if((document.formulaire.email1.value.length != 0)){// && !checkEmail(document.formulaire.email1.value)) {
//				alert('Veuillez indiquer une adresse email correcte pour le premier responsable.');
//				document.formulaire.email1.focus();
//				return false;
//			}
			
			/********************************************************************************/
			/*	Vérification du responsable 2												*/
			/********************************************************************************/
			if(document.formulaire.lastname2.value.length > 0) {
				if(document.formulaire.lastname2.value.length < 2) {
					alert('Veuillez indiquer le nom du second responsable.');
					document.formulaire.lastname2.focus();
					return false;
				}
	
				if(document.formulaire.firstname2.value.length < 2) {
					alert('Veuillez indiquer le prénom du second responsable.');
					document.formulaire.firstname2.focus();
					return false;
				}
				
				if(document.formulaire.sexe2.value.length == 0){
					alert('Veuillez indiquer le sexe du second responsable.');
					return false;
				}

				if(!checkNISS(document.formulaire.niss2.value, document.formulaire.sexe2.value)) {
					alert('Numéro NISS du second responsable incorrecte.');
					document.formulaire.niss2.focus();
					return false;
				}
				
				if(document.formulaire.type2.value.length == 0) {
					alert('Veuillez choisir le lien de parenté du second responsable.');
					document.formulaire.type2.focus();
					return false;
				}
		
				if(document.formulaire.number2.value.length < 1) {
					alert('Veuillez indiquer le numéro du second responsable.');
					document.formulaire.number2.focus();
					return false;
				}
				
				document.formulaire.fulladdress2.value = document.formulaire.address2.value+", "+document.formulaire.number2.value;
				if(document.formulaire.fulladdress2.value.length <= (document.formulaire.number2.value.length + 2)) {
					alert('Veuillez indiquer l\'adresse du second responsable.');
					document.formulaire.address2.focus();
					return false;
				}
	
				if(!checkPostal(document.formulaire.postal2.value)) {
					alert('Code postal du second responsable incorrect.');
					document.formulaire.postal2.focus();
					return false;
				}
	
				if(document.formulaire.city2.value < 2) {
					alert('Ville de résidence du second responsable incorrecte.');
					document.formulaire.city2.focus();
					return false;
				}
				
				if(!checkGsm(document.formulaire.gsm2.value) && !checkPhone(document.formulaire.phone2.value)) { /**  A CORRIGER **/
					alert('Numéro de GSM et/ou de Téléphone du second responsable incorrect.');
					document.formulaire.phone2.focus();
					return false;
				}
				
//				if((document.formulaire.email2.value.length != 0)){// && !checkEmail(document.formulaire.email2.value)) {
//					alert('Veuillez indiquer une adresse email correcte pour le second responsable.');
//					document.formulaire.email2.focus();
//					return false;
//				}
			}
			
			/********************************************************************************/
			/*	Post-Processing																*/
			/********************************************************************************/
			document.formulaire.lastname.value = toTitleCase(document.formulaire.lastname.value);
			document.formulaire.lastname1.value = toTitleCase(document.formulaire.lastname1.value);
			document.formulaire.lastname2.value = toTitleCase(document.formulaire.lastname2.value);

			document.formulaire.firstname.value = toTitleCase(document.formulaire.firstname.value);
			document.formulaire.firstname1.value = toTitleCase(document.formulaire.firstname1.value);
			document.formulaire.firstname2.value = toTitleCase(document.formulaire.firstname2.value);

			document.formulaire.birth.value = document.formulaire.birthyear.value+"-"+document.formulaire.birthmonth.value+"-"+document.formulaire.birthday.value;
			document.formulaire.email.value = document.formulaire.email.value.toLowerCase();
			document.formulaire.email.value = strtr(document.formulaire.email.value, "àäâéèêëïîôöùûüç","aaaeeeeiioouuuc");
			document.formulaire.email1.value = document.formulaire.email1.value.toLowerCase();
			document.formulaire.email1.value = strtr(document.formulaire.email1.value, "àäâéèêëïîôöùûüç","aaaeeeeiioouuuc");
			document.formulaire.email2.value = document.formulaire.email2.value.toLowerCase();
			document.formulaire.email2.value = strtr(document.formulaire.email2.value, "àäâéèêëïîôöùûüç","aaaeeeeiioouuuc");

			return true;
		}

		function toTitleCase(str)
		{
			return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
		}

		String.prototype.toTitleCase = function () {
			return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
		};
	</script>


	<link rel="stylesheet" type="text/css" media="screen" href="./Extranet/design/tinyTips.css" />

	<script type="text/javascript" src="./Extranet/library/jquery.min.js"></script>
	<script type="text/javascript" src="./Extranet/library/jquery.tinyTips.js"></script>
 	
	<script type="text/javascript">
		$(document).ready(function() {
			$('a.tTip').tinyTips('title');
		});
	</script>
	
	<noscript>
		<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>
	</noscript>
</head>

<div id="body">
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
				<!-- ========================= BEGIN FORM ====================== -->
				<h2><a>Pré-Inscription d'un gymnaste</a></h2>
				<?php
					if(!empty($msg))
						echo "<p align='center' class='goodalert'>$msg</p>";
					elseif(!empty($err))
						echo "<p align='center' class='important'>$err</p>";
					else {
				?>
				<p align="justify">
				Ce formulaire est optimisé pour les navigateurs suivants : <a href="http://windows.microsoft.com/fr-BE/internet-explorer/downloads/ie" target="_blank">Internet Explorer 9</a>, <a href="http://www.mozilla-europe.org/fr/" target="_blank">Firefox 6 (et ultérieur)</a>, <a href="http://www.chromium.org/Home" target="_blank">Chromium</a> (ou <a href="http://www.google.com/chrome?hl=fr" target="_blank">Chrome</a>), <a href="http://www.apple.com/fr/safari/download/" target="_blank">Safari</a> et <a href="http://www.opera.com/" target="_blank">Opéra</a> et nécessite l'activation de JavaScript.<br /><br />
				Afin de pré-inscrire un enfant dont vous êtes responsable, veuillez remplir le formulaire ci-dessous. Tous les champs suivis d'une étoile (*) sont obligatoires.<br />Toutes les informations qui vous sont demandées le sont à but d'<u>identification univoque</u> et de <u>facilité de communication d'informations</u> (en cas d'accident, de cours supprimés, de compétition, d'évènement, …). Ces informations seront conservées par La Vaillante Tubize, <u>aucune transmission à une tierce partie quelconque ne sera jamais faite</u>, <u>aucune utilisation autre que celle décrite ci-dessus ou dans les info-bulles ne sera fait de ces informations</u>.<br /><br />
				De plus, nous devons pouvoir contacter téléphoniquement (téléphone ou GSM) un "responsable" en cas d'accident. A cette fin, <b>au moins</b> un responsable doit être renseigné. Conscient que certaines des informations demandées peuvent paraître incongrues, nous avons placé des explications à plusieurs endroits : passer votre souris sur le label devant un champs et une informations concernant ce champs apparaitra.<br /><br />
				Une fois le formulaire enregistré, vous recevrez une demande de confirmation par email. Veillez donc à bien entrer au moins une adresse email valide. Si vous ne recevez pas d'email de confirmation, ou si vous rencontrez le moindre problème lors du remplissage ou de l'enregistrement de ce formulaire, veuillez nous contacter via l'addresse email suivante : <a href="mailto:extranet@lavaillantetubize.be">extranet@lavaillantetubize.be</a></p>
				<table width="100%">
					<tr>
						<td>
							<form class="formulaire" name="formulaire" id="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
								<table align="center">
									<tr>
										<td colspan="2" align="center">
											<fieldset align="left">
												<legend>Informations du gymnaste</legend>
												<p>
													<label>Nom *</label>
													<input type="text" id="lastname" name="lastname" size="30" maxlength="50" value="Trullemans" />
												</p>
												<p>
													<label>Prénom *</label>
													<input type="text" name="firstname" size="25" maxlength="50" value="Gregory" />
												</p>
												<p>
													<label>Sexe *</label>
													<select name="sexe">
														<option value="">Choisissez...</option>
														<option value="M" selected>Garçon</option>
														<option value="F">Fille</option>
													</select>
												</p>
												<p>
													<label>Date de naissance*</label>
													<!-- <input type="text" name="birthDate" id="birthDate" style="width:75px" maxlength="10" /> -->
													<!-- &nbsp;&nbsp;<font size="1">(format : jj-mm-aaaa)</font> -->
													
													<input type="text" name="birthday" id="birthday" size="1" maxlength="2" onKeyUp="next(this, 'birthmonth', 2);" value="05" />/
													<input type="text" name="birthmonth" id="birthmonth" size="1" maxlength="2" onKeyUp="next(this, 'birthyear', 2);" value="02" />/
													<input type="text" name="birthyear" id="birthyear" size="3" maxlength="4" onKeyUp="next(this, 'birthplace', 4);" value="1982" />
													<input type="hidden" name="birth" id="birth">
												</p>
												<p>
													<label>Lieu de naissance</label>
													<input type="text" name="birthplace" id="birthplace" size="15" maxlength="50" value="Anderlecht" />
												</p>
												<p>
													<label><a class='tTip' href='#' title="Ce numéro permettra des ré-inscriptions (pour les saisons prochaines) plus facile et plus rapide.<br />Vous pouvez trouver ce numéro au dos de la carte d'identité de l'enfant ou en hauf à gauche de la carte SIS.">NISS (Numéro National) *</a></label>
													<input type="text" name="niss" size="13" maxlength="11" onKeyUp="next(this, 'address', 11);" value="82020539131" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Adresse (principale) *</label>
													<input type="text" name="address" id="address" size="30" maxlength="100" value="Rue des Cendres" />&nbsp;n° <input type="text" id="number" name="number" size="3" maxlength="3" value="24" />
													<input type="hidden" name="fulladdress" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Boite</label>
													<input type="text" name="box" id="box" size="4" maxlength="4" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Code postal *</label>
													<input type="text" name="postal" size="6" maxlength="4" value="1430" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label for="city">Ville *</label>
													<input type="text" name="city" size="20" maxlength="50" value="Rebecq" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label for="phone">Téléphone (principal)</label>
													<input type="text" name="phone" size="10" maxlength="9" value="067638700" />&nbsp;&nbsp;&nbsp;<font size="1">(ex: 025556789)</font>
												</p>
												<p>
													<label>GSM (du gymnaste)</label>
													<input type="text" name="gsm" size="11" maxlength="10" value="0494923851" />&nbsp;&nbsp;<font size="1">(ex: 0478123456)</font>
												</p>
												<p>
													<label>Adresse email</label>
													<input type="text" name="email" size="25" value="gtrullem@gmail.com" />
												</p>
												<hr />
												<p>
													<label><u>Section(s) Souhaitée(s)</u> *</label>
												</p>
													<?php
														$query = "SELECT acronym, title FROM xtr_discipline WHERE active = 'Y' ORDER BY acronym";
														$result_discipline = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$result_discipline."<br />".mysql_error(), E_USER_ERROR);
													?>
												<table width="100%">
													<?php
														while($line_discipline = mysql_fetch_array($result_discipline))
															echo "<tr><td width='25'><input type='checkbox' name='discipline' value='".$line_discipline['acronym']."' /></td><td>".$line_discipline['title']." (".$line_discipline['acronym'].")</td></tr>";
													?>
												</table>
												<input type="hidden" name="disclist" />
											</fieldset>
											L'adresse des responsables est identique à celle de l'inscrit : <input type="checkbox" name="adr" onClick="copyInfo();">
										</td>
									</tr>
									<tr>
										<td>
											<fieldset>
												<legend>Premier Responsable</legend>
												<p>
													<label>Nom *</label>
													<input type="text" id="lastname1" name="lastname1" size="30" maxlength="50" value="Ponsaerts" />
												</p>
												<p>
													<label>Prénom *</label>
													<input type="text" name="firstname1" size="25" maxlength="50" value="Arlette" />
												</p>
												<p>
													<label>Sexe *</label>
													<select name="sexe1">
														<option value="">Choisissez...</option>
														<option value="M" selected>Garçon</option>
														<option value="F">Fille</option>
													</select>
												</p>
												<p>
													<label><a class='tTip' href='#' title="Ce numéro permettra des ré-inscriptions (pour les saisons prochaines) plus faciles et plus rapides. Cela permettra également, dans un avenir que nous espérons proche, une (ré-)inscription facilité pour les familles ayant plus d'un enfant (plus besoin de réencoder les informations des responsables).<br /> Vous pouvez trouver ce numéro au dos de votre carte d'identité ou en hauf à gauche de la carte SIS.">NISS (Numéro National) *</a></label>
													<input type="text" name="niss1" size="13" maxlength="11" onKeyUp="next(this, 'type1', 11);" value="82020539131" />
												</p>
												<p>
													<label>Lien de parenté *</label>
													<select name="type1">
														<option value="">Choisissez...</option>
														<option value="père"> Père </option>
														<option value="mère" selected> Mère </option>
														<option value="frère"> Frère </option>
														<option value="soeur"> Soeur </option>
														<option value="oncle"> Oncle </option>
														<option value="tante"> Tante </option>
														<option value="grand-père"> Grand-père </option>
														<option value="grand-mère"> Grand-mère </option>
														<option value="tuteur"> Tuteur </option>
														<option value="tutrice"> Tutrice </option>
														<option value="conjoint"> Conjoint </option>
													</select>
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Adresse (principale) *</label>
													<input type="text" name="address1" size="30" maxlength="100" />&nbsp;n° <input type="text" id="number1" name="number1" size="3" maxlength="3" />
													<input type="hidden" name="fulladdress1" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Boite</label>
													<input type="text" name="box1" id="box1" size="4" maxlength="4" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Code postal *</label>
													<input type="text" name="postal1" size="6" maxlength="4" onKeyUp="next(this, 'city1', 4);" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Ville *</label>
													<input type="text" name="city1" id="city1" size="20" maxlength="50" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Téléphone (principal)</label>
													<input type="text" name="phone1" size="10" maxlength="9" onKeyUp="next(this, 'gsm1', 9);" />&nbsp;&nbsp;&nbsp;<font size="1">(ex: 025556789)</font>
												</p>
												<p>
													<label>GSM *</label>
													<input type="text" name="gsm1" id="gsm1" size="11" maxlength="10" onKeyUp="next(this, 'profession1', 10);" value="0486321266" />&nbsp;&nbsp;<font size="1">(ex: 0478123456)</font>
												</p>
												<p>
													<label><a class='tTip' href='#' title="Notre seul but est de privilégié avant tout un parent d'un de nos inscrits (et donc proche du club), en cas de nécessité, plutôt qu’une tierce personne sans rapport avec nous.">Profession</a></label>
													<input type="text" name="profession1" id="profession1" size="35" maxlength="35" value="Institutrice" />
												</p>
												<p>
													<label>Adresse email *</label>
													<input type="text" name="email1" size="25" value="gtrullem@gmail.com" />
												</p>
											</fieldset>
										</td>
										<td>
											<fieldset>
												<legend>Second Responsable</legend>
												<p>
													<label>Nom</label>
													<input type="text" id="lastname2" name="lastname2" size="30" maxlength="50" />
												</p>
												<p>
													<label>Prénom</label>
													<input type="text" name="firstname2" size="25" maxlength="50" />
												</p>
												<p>
													<label>Sexe</label>
													<select name="sexe2">
														<option value="">Choisissez...</option>
														<option value="M">Garçon</option>
														<option value="F">Fille</option>
													</select>
												</p>
												<p>
													<label><a class='tTip' href='#' title="Ce numéro permettra des ré-inscriptions (pour les saisons prochaines) plus faciles et plus rapides. Cela permettra également, dans un avenir que nous espérons proche, une (ré-)inscription facilité pour les familles ayant plus d'un enfant (plus besoin de réencoder les informations des responsables).<br /> Vous pouvez trouver ce numéro au dos de votre carte d'identité ou en hauf à gauche de la carte SIS.">NISS (Numéro National)</a></label>
													<input type="text" name="niss2" size="13" maxlength="11" onKeyUp="next(this, 'type2', 11);" />
												</p>
												<p>
													<label>Lien de parenté</label>
													<select name="type2">
														<option value="">Choisissez...</option>
														<option value="père"> Père </option>
														<option value="mère"> Mère </option>
														<option value="frère"> Frère </option>
														<option value="soeur"> Soeur </option>
														<option value="oncle"> Oncle </option>
														<option value="tante"> Tante </option>
														<option value="grand-père"> Grand-père </option>
														<option value="grand-mère"> Grand-mère </option>
														<option value="tuteur"> Tuteur </option>
														<option value="tutrice"> Tutrice </option>
														<option value="conjoint"> Conjoint </option>
													</select>
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Adresse (principale)</label>
													<input type="text" name="address2" size="30" maxlength="100" />&nbsp;n° <input type="text" id="number2" name="number2" size="3" maxlength="3" />
													<input type="hidden" name="fulladdress2" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Boite</label>
													<input type="text" name="box2" id="box2" size="4" maxlength="4" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Code postal</label>
													<input type="text" name="postal2" size="6" maxlength="4" onKeyUp="next(this, 'city2', 4);" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Ville</label>
													<input type="text" name="city2" id="city2" size="20" maxlength="50" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Téléphone (principal)</label>
													<input type="text" name="phone2" id="phone2" size="10" maxlength="9" onKeyUp="next(this, 'gsm2', 9);" />&nbsp;&nbsp;&nbsp;<font size="1">(ex: 025556789)</font>
												</p>
												<p>
													<label>GSM</label>
													<input type="text" name="gsm2" id="gsm2" size="11" maxlength="10" onKeyUp="next(this, 'profession2', 10);" />&nbsp;&nbsp;<font size="1">(ex: 0478123456)</font>
												</p>
												<p>
													<label><a class='tTip' href='#' title="Notre seul but est de privilégié avant tout un parent d'un de nos inscrits (et donc proche du club), en cas de nécessité, plutôt qu’une tierce personne sans rapport avec nous.">Profession</a></label>
													<input type="text" name="profession2" id="profession2" size="35" maxlength="35" />
												</p>
												<p>
													<label>Adresse email</label>
													<input type="text" name="email2" size="25" value="" />
												</p>
											</fieldset>
										</td>
									</tr>
								</table>
								<p align="center"><input type="submit" name="submit" value="Enregistrer"></p>
							</form>
						</td>
					</tr>
				</table>
				<?php
					}
				?>
			</div>	
		</div>
	</div>
</div>
</div>
</body>
</html>