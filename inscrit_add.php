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

	if(isset($_POST['submit'])) {		
		$birth = $_POST['birth'];
		$lastname = mysql_real_escape_string(stripslashes($_POST['lastname']));
		$firstname = mysql_real_escape_string(stripslashes($_POST['firstname']));
		
		$query = " SELECT personid FROM xtr_person WHERE lastname = '$lastname' AND firstname = '$firstname' AND birth = '$birth'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (users) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

		if(mysql_fetch_array($result)) {
			$err = "Vous êtes déjà inscrit. Veuillez contacter une personne responsable pour votre réinscription.";
		} else {
			$birthplace = mysql_real_escape_string(stripslashes($_POST['birthplace']));
			$address = mysql_real_escape_string(stripslashes($_POST['address']));
			$city = mysql_real_escape_string(stripslashes($_POST['city']));
			
			$postal = $_POST['postal'];
			$phone = $_POST['phone'];
			$email = $_POST['email'];
			$sexe = $_POST['sexe'];
			$niss = $_POST['niss'];
			$gsm = $_POST['gsm'];
			$box = $_POST['box'];
			
			// Insertion de l'enfant
			$query = " INSERT INTO xtr_person (lastname, firstname, birth, birthplace, sexe, niss, address, box, postal, city, phone, gsm, email) VALUES ('$lastname', '$firstname', '$birth', '$birthplace', '$sexe', '$niss', '$address', '$box', '$postal', '$city', '$phone', '$gsm', '$email') ";
//			echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			$query = " SELECT LAST_INSERT_ID()";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GET LAST ID !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			$id_affiliate = mysql_fetch_array($result);
			
			
			
			///////////////////////////////////////////////////////////////////////
			// Responsable 1 data treatment
			///////////////////////////////////////////////////////////////////////
			if(!empty($_POST['lastname1'])) {
				$city = mysql_real_escape_string(stripslashes($_POST['city1']));
				$address = mysql_real_escape_string(stripslashes($_POST['address1']));
				$lastname = mysql_real_escape_string(stripslashes($_POST['lastname1']));
				$firstname = mysql_real_escape_string(stripslashes($_POST['firstname1']));
				$profession = mysql_real_escape_string(stripslashes($_POST['profession1']));
				$gsm = $_POST['gsm1'];
				$box = $_POST['box1'];
				$sexe = $_POST['sexe1'];
				$type = $_POST['type1'];
				$email = $_POST['email1'];
				$phone = $_POST['phone1'];
				$postal = $_POST['postal1'];
				
				$query = " INSERT INTO xtr_person (lastname, firstname, sexe, address, box, postal, city, phone, gsm, email, profession) VALUES ('$lastname', '$firstname', '$sexe', '$address', '$box', '$postal', '$city', '$phone', '$gsm', '$email', '$profession') ";
//				echo $query."<br />";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (person1) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
				$query = " SELECT LAST_INSERT_ID()";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GET LAST ID !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
				$id_parent = mysql_fetch_array($result);
				
				$query = " INSERT INTO xtr_relationship (personid, personid1, type, responsable) VALUES ('$id_affiliate[0]', '$id_parent[0]', '$type', 'Y')";
//				echo $query."<br />";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (relation) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			}


			
			///////////////////////////////////////////////////////////////////////
			// Responsable 2 data treatment
			///////////////////////////////////////////////////////////////////////
			if(!empty($_POST['lastname2'])) {
				$city = mysql_real_escape_string(stripslashes($_POST['city2']));
				$address = mysql_real_escape_string(stripslashes($_POST['address2']));
				$lastname = mysql_real_escape_string(stripslashes($_POST['lastname2']));
				$firstname = mysql_real_escape_string(stripslashes($_POST['firstname2']));
				$profession = mysql_real_escape_string(stripslashes($_POST['profession2']));
				$gsm = $_POST['gsm2'];
				$box = $_POST['box2'];
				$sexe = $_POST['sexe2'];
				$type = $_POST['type2'];
				$phone = $_POST['phone2'];
				$email = $_POST['email2'];
				$postal = $_POST['postal2'];
				
				$query = " INSERT INTO xtr_person (lastname, firstname, sexe, address, box, postal, city, phone, gsm, email, profession) VALUES ('$lastname', '$firstname', '$sexe', '$address', '$box', '$postal', '$city', '$phone', '$gsm', '$email', '$profession') ";
//				echo $query."<br />";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (person2) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
				$query = " SELECT LAST_INSERT_ID()";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GETTING LAST ID !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
				$id_parent = mysql_fetch_array($result);
				
				$query = " INSERT INTO xtr_relationship (personid, personid1, type, responsable) VALUES ('$id_affiliate[0]', '$id_parent[0]', '$type', 'Y')";
//				echo $query."<br />";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (relation) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			}



			///////////////////////////////////////////////////////////////////////
			// Affiliation treatment
			///////////////////////////////////////////////////////////////////////
			$course = $_POST['courseid'];
//			var_dump($_POST['courseid']);
			
			for($i = 0; $i<sizeof($course); $i++) {
				$query = " INSERT INTO xtr_isaffiliate (courseid, pid) VALUES ('$course[$i]', '$id_affiliate[0]');";
				$result = mysql_query($query,$connect) or trigger_error("SQL Error : INSERT FAILED (course) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			}
			
			header("Location: ./affiliate_details.php?id=".$id_affiliate[0]);
			exit;
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Inscription :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<script type="text/javascript" language="javascript" src="./library/library.js"></script>
	<script type="text/javascript" language="javascript">
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
		
		function affiliateIsCorrect()
		{
			if(document.formulaire.lastname.value.length < 2) {
				alert('Veuillez indiquer le nom de l\'inscrit.');
				document.formulaire.lastname.focus();
				return false;
			}

			if(document.formulaire.firstname.value.length < 2) {
				alert('Veuillez indiquer le prénom de l\'inscrit.');
				document.formulaire.firstname.focus();
				return false;
			}
			
			if((!document.formulaire.sexe[0].checked) && (!document.formulaire.sexe[1].checked)) {
				alert('Veuillez indiquer le sexe de l\'inscrit.');
				return false;
			}
			
			document.formulaire.birth.value = document.formulaire.birthday.value+'/'+document.formulaire.birthmonth.value+'/'+document.formulaire.birthyear.value;
			if(!dateIsCorrect(document.formulaire.birth.value)) {
				alert('Date de naissance incorrecte.');
				return false;
			}
			
			if(!checkNISS(document.formulaire.niss.value)) {
				alert('Numéro NISS/SIS incorrecte.');
				document.formulaire.niss.focus();
				return false;
			}

			if(document.formulaire.number.value.length < 1) {
				alert('Veuillez indiquer le numéro de l\'inscrit.');
				document.formulaire.number.focus();
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

			document.formulaire.address.value = document.formulaire.address.value+", "+document.formulaire.number.value;
			if(document.formulaire.address.value.length < (document.formulaire.number.length + 2)) {
				document.formulaire.address.value = document.formulaire.address.value.substr(0, (document.formulaire.address.value.length - (document.formulaire.number.lenght + 2)));
				alert('Veuillez indiquer l\'adresse.');
				document.formulaire.address.focus();
				return false;
			}
			
			return true;
		}
		
		/* Ajouter le téléphone */
		function parentIsCorrect(firstname_length, relationtype, address_length, postal, city_length, gsm)
		{
			if(firstname_length < 2)	return false;
			if(address_length < 3)	return false;
			if(city_length < 2)	return false;
			/*	if(sex == "")	{	return false;	}	*/
			if(relationtype == "default")	return false;
			
			var reg = new RegExp("^[0-9]{4}$");
			if(!reg.test(postal))
				return false;
			
			reg = new RegExp("^04[0-9]{8}$");
			if(!reg.test(gsm)) {
				alert('Numéro de GSM du responsable incorrect.');
				return false;
			}
			
			return true;
		}
		
		function checkForm(formulaire)
		{
			///////////////////////////////////////////////////////////////////
			// Pre-processing
			///////////////////////////////////////////////////////////////////
			document.formulaire.birth.value = document.formulaire.birthday.value+'/'+document.formulaire.birthmonth.value+'/'+document.formulaire.birthyear.value;
			document.formulaire.email.value = document.formulaire.email.value.toLowerCase();
			document.formulaire.email.value = strtr(document.formulaire.email.value, "àäâéèêëïîôöùûüç","aaaeeeeiioouuuc");
			document.formulaire.email1.value = document.formulaire.email1.value.toLowerCase();
			document.formulaire.email1.value = strtr(document.formulaire.email1.value, "àäâéèêëïîôöùûüç","aaaeeeeiioouuuc");
			document.formulaire.email2.value = document.formulaire.email2.value.toLowerCase();
			document.formulaire.email2.value = strtr(document.formulaire.email2.value, "àäâéèêëïîôöùûüç","aaaeeeeiioouuuc");
			
			alert('2');
			if(!affiliateIsCorrect()) return false;
			
			if((document.formulaire.lastname1.value.length < 2) && (document.formulaire.lastname2.value.length < 2)) {
				alert('Veuillez remplir tous les champs obligatoires (*) pour au moins un des deux responsables.')
			}

			if((document.formulaire.lastname1.value.length > 2) && !parentIsCorrect(document.formulaire.firstname1.value.length, document.formulaire.type1.value, document.formulaire.address1.value.length, document.formulaire.postal1.value, document.formulaire.city1.value.length, document.formulaire.gsm1.value)) {
				alert('Veuillez remplir tous les champs obligatoires (*) le responsable 1.')
				return false;
			}

			if((document.formulaire.lastname2.value.length > 2) && !parentIsCorrect(document.formulaire.firstname2.value.length, document.formulaire.type2.value, document.formulaire.address2.value.length, document.formulaire.postal2.value, document.formulaire.city2.value.length, document.formulaire.gsm2.value)) {
				alert('Veuillez remplir tous les champs obligatoires (*) le responsable 2.')
				return false;
			}

			///////////////////////////////////////////////////////////////////
			// Post-processing
			///////////////////////////////////////////////////////////////////
			document.formulaire.birth.value = document.formulaire.birthyear.value+'-'+document.formulaire.birthmonth.value+'-'+document.formulaire.birthday.value;
			document.formulaire.address.value = document.formulaire.address.value+", "+document.formulaire.number.value;
			document.formulaire.address1.value = document.formulaire.address1.value+", "+document.formulaire.number1.value;
			document.formulaire.address2.value = document.formulaire.address2.value+", "+document.formulaire.number2.value;

			return true;
		}
		
		function showCourse()
		{
			
			if (window.XMLHttpRequest) {		// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} else {							// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
		
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
					document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
			}
			
			var day = "";
			var disc = "";
			
			for(var i=0; i < document.formulaire.daynumber.length; i++)
				if(document.formulaire.daynumber[i].checked)
					day += ", "+document.formulaire.daynumber[i].value;
			
			for(var i=0; i < document.formulaire.discipline.length; i++)
				if(document.formulaire.discipline[i].checked)
					disc += ", "+document.formulaire.discipline[i].value;		
					
			day = day.substring(2, day.length);
			disc = disc.substring(2, disc.length); 

			xmlhttp.open("GET","getcourse.php?discipline="+disc+"&daynumber="+day,true);
			xmlhttp.send();
		}
	</script>
	<noscript>
		<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>
	</noscript>
</head>

<body onload="createListObjects()">
<div id="body">

<?php	require_once("./header.php");	?>
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
				<!-- ========================= BEGIN FORM ====================== -->
				<H2><a>Inscription d'un gymnaste (<u>/!\</u> EN TEST <u>/!\</u>)</a></H2>
				<table width="100%">
					<tr>
						<td>
							<form class="formulaire" name="formulaire" id="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
								<table align="center">
									<tr>
										<td colspan="2" align="center">
											<fieldset align="left">
												<legend>Informations de l'inscrit</legend>
												<?php 
													if(!empty($err)) {
														echo "<p class=\"important\">".$err."</p>";
													}
												?>
												<p>
													<label>Nom *</label>
													<INPUT type="text" id="lastname" name="lastname" size="30" maxlength="50" />
												</p>
												<p>
													<label>Prénom *</label>
													<input type="text" name="firstname" size="25" maxlength="50" />
												</p>
												<p>
													<label>Sexe *</label>
													<input type="radio" name="sexe" value="M" />Garçon &nbsp;&nbsp;
													<input type="radio" name="sexe" value="F" />Fille
												</p>
												<p>
													<label>Date de naissance*</label>
													<input type="text" name="birthday" id="birthday" size="1" maxlength="2" onKeyUp="next(this, 'birthmonth', 2);">/
													<input type="text" name="birthmonth" id="birthmonth" size="1" maxlength="2" onKeyUp="next(this, 'birthyear', 2);">/
													<input type="text" name="birthyear" id="birthyear" size="3" maxlength="4" onKeyUp="next(this, 'birthplace', 4);">
													<input type="hidden" name="birth" id="birth">
												</p>
												<p>
													<label>Lieu de naissance *</label>
													<input type="text" name="birthplace" id="birthplace" size="15" maxlength="50" />
												</p>
												<p>
													<label>NISS/SIS *</label>
													<input type="text" name="niss" size="13" maxlength="11" onKeyUp="next(this, 'address', 11);" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Adresse (principale) *</label>
													<input type="text" name="address" id="address" size="30" maxlength="100" />&nbsp;n° <input type="text" id="number" name="number" size="3" maxlength="3" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Boite</label>
													<input type="text" name="box" id="box" size="4" maxlength="4" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Code postal *</label>
													<input type="text" name="postal" size="6" maxlength="4" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label for="city">Ville *</label>
													<input type="text" name="city" size="20" maxlength="50" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label for="phone">Téléphone (principal)</label>
													<input type="text" name="phone" size="10" maxlength="9" />
												</p>
												<p>
													<label>GSM</label>
													<input type="text" name="gsm" size="11" maxlength="10" />
												</p>
												<p>
													<label>email</label>
													<input type="text" name="email" size="25" />
												</p>
												<hr />
												<p>
													<label>Cours *</label>
												</p>
													<?php
														$query = "SELECT disciplineid, acronym FROM xtr_discipline WHERE enable = 'Y' ORDER BY acronym";
														$result_discipline = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$result_discipline."<br />".mysql_error(), E_USER_ERROR);
														
														if(date("n") >= "8") {
															$season = date("Y")."-".(date("Y") + 1);
														} else {
															$season = (date("Y") - 1)."-".date("Y");
														}
														
														$query = "SELECT DISTINCT(day), daynumber FROM xtr_course WHERE enable = 'Y' AND season = '$season' ORDER BY daynumber";
														$result_course = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />".$result_course."<br />".mysql_error(), E_USER_ERROR);

													?>
												<table width="100%">
													<?php
														while(true) {
															$line_discipline = mysql_fetch_array($result_discipline);
															$line_course = mysql_fetch_array($result_course);
															
															if(!$line_course || !$line_discipline) break;

															echo "<tr><td width=\"25\"><input type=\"checkbox\" name=\"discipline\" value=\"".$line_discipline['disciplineid']."\" onClick=\"showCourse()\" /></td><td>".$line_discipline['acronym']."</td><td width=\"25\"><input type=\"checkbox\" name=\"daynumber\" value=\"".$line_course['daynumber']."\" onClick=\"showCourse()\" /></td><td>".$line_course['day']."</td></tr>";
														}
														
														if(!$line_course) {
															while(true) {
																echo "<tr><td><input type=\"checkbox\" name=\"discipline\" value=\"".$line_discipline['disciplineid']."\" onClick=\"showCourse()\" /></td><td>".$line_discipline['acronym']."</td><td colspan=\"2\"></td></tr>";
																$line_discipline = mysql_fetch_array($result_discipline);
																
																if(!$line_discipline) break;
															}
														} else {
															while(true) {
																echo "<tr><td colspan=\"2\"></td><td><input type=\"checkbox\" name=\"daynumber\" value=\"".$line_course['daynumber']."\" onClick=\"showCourse()\" /></td><td>".$line_course['day']."</td></tr>";
																$line_course = mysql_fetch_array($result_course);
																
																if(!$line_course) break;
															}
														}	
													?>
												</table>
												<div id="txtHint">
													&nbsp;
												</div>
											</fieldset>
											L'adresse des responsables est identique à celle de l'inscrit <input type="checkbox" name="adr" onClick="copyInfo();">
										</td>
									</tr>
									<tr>
										<td>
											<fieldset>
												<legend>Responsable 1</legend>
												<p>
													<label>Nom *</label>
													<input type="text" id="lastname1" name="lastname1" size="30" maxlength="50" />
												</p>
												<p>
													<label>Prénom *</label>
													<input type="text" name="firstname1" size="25" maxlength="50" />
												</p>
												<p>
													<label>Sexe *</label>
													<input type="radio" name="sexe1" value="M" />Homme &nbsp;&nbsp;
													<input type="radio" name="sexe1" value="F" />Femme
												</p>
												<p>
													<label>Lien de parenté *</label>
													<select name="type1">
														<option value="default"></option>
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
													<label>Adresse (principale) *</label>
													<input type="text" name="address1" size="30" maxlength="100" />&nbsp;n° <input type="text" id="number1" name="number1" size="3" maxlength="3" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Boite</label>
													<input type="text" name="box1" id="box1" size="4" maxlength="4" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Code postal *</label>
													<input type="text" name="postal1" size="6" maxlength="4" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Ville *</label>
													<input type="text" name="city1" size="20" maxlength="50" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Téléphone (principal)</label>
													<input type="text" name="phone1" size="10" maxlength="9" />
												</p>
												<p>
													<label>GSM *</label>
													<input type="text" name="gsm1" size="11" maxlength="10" />
												</p>
												<p>
													<label>Profession</label>
													<input type="text" name="profession1" size="10" maxlength="9" />
												</p>
												<p>
													<label>email *</label>
													<input type="text" name="email1" size="25" />
												</p>
											</fieldset>
										</td>
										<td>
											<fieldset>
												<legend>Responsable 2</legend>
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
													<input type="radio" name="sexe2" value="M" />Homme &nbsp;&nbsp;
													<input type="radio" name="sexe2" value="F" />Femme
												</p>
												<p>
													<label>Lien de parenté</label>
													<select name="type2">
														<option value="default"></option>
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
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Boite</label>
													<input type="text" name="box2" id="box2" size="4" maxlength="4" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Code postal</label>
													<input type="text" name="postal2" size="6" maxlength="4" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Ville	</label>
													<input type="text" name="city2" size="20" maxlength="50" />
												</p>
												<p style="background-color: #E7F1F7 ;">
													<label>Téléphone (principal)</label>
													<input type="text" name="phone2" size="10" maxlength="9" />
												</p>
												<p>
													<label>GSM</label>
													<input type="text" name="gsm2" size="11" maxlength="10" />
												</p>
												<p>
													<label>Profession</label>
													<input type="text" name="profession2" size="10" maxlength="9" />
												</p>
												<p>
													<label>email</label>
													<input type="text" name="email2" size="25" />
												</p>
											</fieldset>
										</td>
									</tr>
								</table>
								<p align="center"><input type="submit" name="submit" value="Ajouter"></p>
							</form>
						</td>
					</tr>
				</table>
				<!-- ========================= END FORM ====================== -->	
			</div>	
		</div>
	</div>
</div>
	
<?php	require_once("./footer.php");	?>
</div>
</body>
</html>