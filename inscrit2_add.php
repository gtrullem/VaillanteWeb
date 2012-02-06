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
	
	if(empty($_GET['id'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=affilié&referrer=index.php");
		exit;
	}
	
	$id = $_GET['id'];

	if(isset($_POST['submit'])) {
		///////////////////////////////////////////////////////////////////////
		// Affiliate's data update
		///////////////////////////////////////////////////////////////////////
		$city = mysql_real_escape_string(stripslashes($_POST['city']));
		$address = mysql_real_escape_string(stripslashes($_POST['address']));
		$lastname = mysql_real_escape_string(stripslashes($_POST['lastname']));
		$firstname = mysql_real_escape_string(stripslashes($_POST['firstname']));
		$birthplace = mysql_real_escape_string(stripslashes($_POST['birthplace']));
		
		$birthday = $_POST['birthday'];
		$postal = $_POST['postal'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$sizet = $_POST['sizet'];
		$ffgid = $_POST['ffgid'];
		$sexe = $_POST['sexe'];
		$niss = $_POST['niss'];
		$box = $_POST['box'];
		$gsm = $_POST['gsm'];
		
		$query = " UPDATE xtr_person SET lastname='$lastname', firstname='$firstname', birth='$birthday', birthplace='$birthplace', sexe='$sexe', niss='$niss', address='$address', box='$box', postal='$postal', city='$city', phone='$phone', gsm='$gsm', email='$email', size='$sizet', ffgid='$ffgid' WHERE id='$id'";
//		echo $query."<br />";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		
		
		///////////////////////////////////////////////////////////////////////
		// Responsable 1 data treatment
		///////////////////////////////////////////////////////////////////////
		$city = mysql_real_escape_string(stripslashes($_POST['city1']));
		$address = mysql_real_escape_string(stripslashes($_POST['address1']));
		$lastname = mysql_real_escape_string(stripslashes($_POST['lastname1']));
		$firstname = mysql_real_escape_string(stripslashes($_POST['firstname1']));
		$profession = mysql_real_escape_string(stripslashes($_POST['profession1']));
		
		$postal = $_POST['postal1'];
		$phone = $_POST['phone1'];
		$email = $_POST['email1'];
		$sexe = $_POST['sexe1'];
		$type = $_POST['type1'];
		$box = $_POST['box1'];
		$gsm = $_POST['gsm1'];
			
		// if(isset($_POST['id1']) && ($_POST['id1'] != "")) {
		if(!empty($_POST['id1'])) {
			// UPDATE
			$id1 = $_POST['id1'];
			$query = " UPDATE xtr_person SET lastname='$lastname', firstname='$firstname', sexe='$sexe', address='$address', box='$box', postal='$postal', city='$city', phone='$phone', gsm='$gsm', email='$email', profession='$profession' WHERE id='$id1'";
//			echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
			$query = " UPDATE xtr_relationship SET type = '$type' WHERE personid = '$id' AND personid1 = '$id1'";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (relation) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		} elseif ($lastname != "") {
			// INSERT + Relationship
			$query = " INSERT INTO xtr_person (lastname, firstname, sexe, address, box, postal, city, phone, gsm, email) VALUES ('$lastname', '$firstname', '$sexe', '$address', '$box', '$postal', '$city', '$phone', '$gsm', '$email') ";
//			echo $query."<br/>";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			$query = " SELECT LAST_INSERT_ID()";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GETTING LAST ID !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			$id_parent = mysql_fetch_array($result);
			
			$query = " INSERT INTO xtr_relationship (personid, personid1, type, responsable) VALUES ('$id', '$id_parent[0]', '$type', 'Y')";
			// echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (relation) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		}
		
		
		
		///////////////////////////////////////////////////////////////////////
		// Responsable 2 data treatment
		///////////////////////////////////////////////////////////////////////
		$city = mysql_real_escape_string(stripslashes($_POST['city2']));
		$address = mysql_real_escape_string(stripslashes($_POST['address2']));
		$lastname = mysql_real_escape_string(stripslashes($_POST['lastname2']));
		$firstname = mysql_real_escape_string(stripslashes($_POST['firstname2']));
		$profession = mysql_real_escape_string(stripslashes($_POST['profession2']));
		
		$postal = $_POST['postal2'];
		$phone = $_POST['phone2'];
		$email = $_POST['email2'];
		$sexe = $_POST['sexe2'];
		$type = $_POST['type2'];
		$box = $_POST['box2'];
		$gsm = $_POST['gsm2'];
			
		// if(isset($_POST['id2']) && ($_POST['id2'] != "")) {
		if(!empty($_POST['id2'])) {
			// UPDATE
			$id2 = $_POST['id2'];
			$query = " UPDATE xtr_person SET lastname='$lastname', firstname='$firstname', sexe='$sexe', address='$address', box='$box', postal='$postal', city='$city', phone='$phone', gsm='$gsm', email='$email', profession='$profession' WHERE id='$id2'";
//			echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
			$query = " UPDATE xtr_relationship SET type = '$type' WHERE personid = '$id' AND personid1 = '$id2'";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (relationship 2) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		} elseif ($lastname != "") {
			// INSERT + Relationship
			$query = " INSERT INTO xtr_person (lastname, firstname, sexe, address, box, postal, city, phone, gsm, email) VALUES ('$lastname', '$firstname', '$sexe', '$address', '$box', '$postal', '$city', '$phone', '$gsm', '$email') ";
//			echo $query."<br/>";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (person2) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			$query = " SELECT LAST_INSERT_ID()";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GETTING LAST ID !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			$id_parent = mysql_fetch_array($result);
			
			$query = " INSERT INTO xtr_relationship (personid, personid1, type, responsable) VALUES ('$id', '$id_parent[0]', '$type', 'Y')";
			// echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (relationship 2) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		}
		
		///////////////////////////////////////////////////////////////////////
		// Affiliation treatment
		///////////////////////////////////////////////////////////////////////
		$course = $_POST['course'];
		
		for($i = 0; $i<sizeof($course); $i++) {
			$query = " INSERT INTO xtr_isaffiliate (courseid, pid) VALUES ('$course[$i]', '$id');";
//			echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL Error : INSERT FAILED (course) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		}
			
		header("Location: ./affiliate_detail.php?personid=".$id);
		exit;
	}
	
	
	
	///////////////////////////////////////////////////////////////////////
	// Retrieving Responsables
	///////////////////////////////////////////////////////////////////////
	$query = " SELECT personid1 FROM xtr_relationship WHERE personid='$id' AND responsable = 'Y';";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (relationship) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	
	///////////////////////////////////////////////////////////////////////
	// Responsable 1's data retrieving
	///////////////////////////////////////////////////////////////////////
	$line = mysql_fetch_array($result);
	$query = " SELECT * FROM xtr_person WHERE personid='".$line['personid1']."'";
	$result1 = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (Person 1) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$line1 = mysql_fetch_array($result1);
	
	///////////////////////////////////////////////////////////////////////
	// Responsable 2's data retrieving
	///////////////////////////////////////////////////////////////////////
	$line = mysql_fetch_array($result);
	$query = " SELECT * FROM xtr_person WHERE personid='".$line['personid1']."'";
	$result2 = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (Person 2) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$line2 = mysql_fetch_array($result2);
	
	///////////////////////////////////////////////////////////////////////
	// Affiliate's data retrieving
	///////////////////////////////////////////////////////////////////////
	$query = "SELECT * FROM xtr_person WHERE personid='$id'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (Relation) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Nouveau Gymnaste :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<script type="text/javascript" language="javascript" src="./library/library.js"></script>
	<script type="text/javascript" language="javascript">
		function copyInfo()
		{
			if(document.formulaire.adr.checked) {
				document.formulaire.address1.value = document.formulaire.address.value;
				document.formulaire.address2.value = document.formulaire.address.value;
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
			
			if(!dateIsCorrect(document.formulaire.birth.value)) {
				alert('Date de naissance incorrecte.');
				return false;
			}
			
			/*
			if(document.formulaire.birthplace.value < 2) {
				alert('Veuillez indiquer le lieu de naissance de l\'inscrit.');
				document.formulaire.birthplace.focus();
				return false;
			}
			*/
			
			if((!document.formulaire.sexe[0].checked) && (!document.formulaire.sexe[1].checked)) {
				alert('Veuillez indiquer le sexe de l\'inscrit.');
				return false;
			}
			
			/*
			var reg = new RegExp("^[0-9]{11}$");
			if(!reg.test(document.formulaire.niss.value)) {
				alert('Veuillez indiquer le numéro NISS de l\'inscrit.');
				document.formulaire.niss.focus();
				return false;
			}
			*/
			
			if(document.formulaire.address.value.length < 3) {
				alert('Veuillez indiquer l\'adresse principale de l\'inscrit.');
				document.formulaire.address.focus();
				return false;
			}
			
			if(document.formulaire.number.value.length < 1) {
				alert('Veuillez indiquer le numéro de l\'inscrit.');
				document.formulaire.number.focus();
				return false;
			}
			
			var reg = new RegExp("^[0-9]{4}$");
			if(!reg.test(document.formulaire.postal.value)) {
				alert('Veuillez indiquer le code postal principal de l\'inscrit.');
				document.formulaire.postal.focus();
				return false;
			}
			
			if(document.formulaire.city.value < 2) {
				alert('Veuillez indiquer la ville de résidence principale de l\'inscrit.');
				document.formulaire.city.focus();
				return false;
			}
			
			/*
			reg = new RegExp("^0[0-9]{8}$");
			if(!reg.test(document.formulaire.phone.value)) {
				alert('Numéro de téléphone de l\'inscrit incorrect.');
				document.formulaire.phone.focus();
				return false;
			}
			
			reg = new RegExp("^04[0-9]{8}$");
			if(!reg.test(document.formulaire.gsm.value)) {
				alert('Numéro de GSM de l\'inscrit incorrect.');
				document.formulaire.gsm.focus();
				return false;
			}
			*/
			return true;
		}
		
		/* Ajouter le téléphone */
		function parentIsCorrect(firstname_length, relationtype, address_length, postal, city_length, gsm)
		{
			if(firstname_length < 2) { return false; }
			if(address_length < 3) { return false; }
			if(city_length < 2) { return false; }
			/*	if(sex == "")	{	return false;	}	*/
			if(relationtype == "")	{	return false;	}
			
			var reg = new RegExp("^[0-9]{4}$");
			if(!reg.test(postal)) { return false; }
			
			reg = new RegExp("^04[0-9]{8}$");
			if(!reg.test(gsm)) {
				alert('Numéro de GSM du responsable incorrect.');
				return false;
			}
			
			return true;
		}
		
		function checkForm(formulaire)
		{
			///////////////////////////////////////////////////////////////////////
			// Preparing Data
			///////////////////////////////////////////////////////////////////////
			document.formulaire.birth.value = document.formulaire.birthday.value+'/'+document.formulaire.birthmonth.value+'/'+document.formulaire.birthyear.value;
			document.formulaire.email.value = document.formulaire.email.value.toLowerCase();
			document.formulaire.email.value = strtr(document.formulaire.email.value, "àäâéèêëïîôöùûüç","aaaeeeeiioouuuc");
			document.formulaire.email1.value = document.formulaire.email1.value.toLowerCase();
			document.formulaire.email1.value = strtr(document.formulaire.email1.value, "àäâéèêëïîôöùûüç","aaaeeeeiioouuuc");
			document.formulaire.email2.value = document.formulaire.email2.value.toLowerCase();
			document.formulaire.email2.value = strtr(document.formulaire.email2.value, "àäâéèêëïîôöùûüç","aaaeeeeiioouuuc");
			
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
			<H2><a>Inscription d'un gymnaste</a></H2>
			<?php 
				if(isset($err)) {
					echo "<p class=\"important\">".$err."</p>";
				}
			?>
			<FORM name="formulaire" id="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?id=".$id; ?>" enctype="multipart/form-data"  onSubmit="return checkForm(this.form);">
			<TABLE align="center" border="0" width="700" id="table1" cellspacing="0" cellpadding="0">
				<TR bgcolor="#9DD4FB">
					<TH colspan="2">&nbsp;<u>Informations concernant le gymnaste</u></TH>
				</TR>
				<TR>
					<TD>Nom *</TD>
					<TD>
						<INPUT type="text" name="lastname" id="lastname" size="30" maxlength="50" value="<?php echo $line['lastname']; ?>">
					</TD>
				</TR>
				<TR>
					<TD>Prénom *</TD>
					<TD><input type="text" name="firstname" size="25" maxlength="50" value="<?php echo $line['firstname']; ?>" /></TD>
				</TR>
				<TR>
					<TD>Sexe *</TD>
					<TD>
						<input type="radio" name="sexe" value="M" <?php if($line['sexe'] == "M") { echo "checked"; } ?>>Garçon &nbsp;&nbsp;
						<input type="radio" name="sexe" value="F" <?php if($line['sexe'] == "F") { echo "checked"; } ?>>Fille
					</TD>
				</TR>
				<TR>
						<TD>Date de naissance *</TD>
						<TD>
							<input type="text" name="birthday" id="birthday" size="1" maxlength="2" value="<?php echo substr($line['birth'], 8, 2) ?>" onKeyUp="next(this, 'birthmonth', 2);">/
							<input type="text" name="birthmonth" id="birthmonth" size="1" maxlength="2" value="<?php echo substr($line['birth'], 5, 2) ?>" onKeyUp="next(this, 'birthyear', 2);">/
							<input type="text" name="birthyear" id="birthyear" size="3" maxlength="4" value="<?php echo substr($line['birth'], 0, 4) ?>" onKeyUp="next(this, 'birthplace', 4);">
							<input type="hidden" name="birth" id="birth">
						</TD>
					</TR>
				<TR>
					<TD>Lieu de naissance *</TD>
					<TD><input type="text" name="birthplace" size="15" maxlength="50" value="<?php echo $line['birthplace']; ?>" /></TD>
				</TR>
				<TR>
					<TD>NISS *</TD>
					<TD><input type="text" name="niss" size="11" maxlength="11" value="<?php echo $line['niss']; ?>" /></TD>
				</TR>
				<TR bgcolor="#E7F1F7">
					<TD>Adresse (principale) *</TD>
					<TD><input type="text" name="address" size="30" maxlength="100" value="<?php echo $line['address']; ?>" /></TD>
				</TR>
				<TR bgcolor="#E7F1F7">
					<TD>Boite</TD>
					<TD><input type="text" name="box" size="4" maxlength="4" value="<?php echo $line['box']; ?>" /></TD>
				</TR>
				<TR bgcolor="#E7F1F7">
					<TD>Code postal *</TD>
					<TD><input type="text" name="postal" size="6" maxlength="4" value="<?php echo $line['postal']; ?>" /></TD>
				</TR>
				<TR bgcolor="#E7F1F7">
					<TD>Ville *</TD>
					<TD><input type="text" name="city" size="20" maxlength="50" value="<?php echo $line['city']; ?>" /></TD>
				</TR>
				<TR bgcolor="#E7F1F7">
					<TD>Téléphone (principal)</TD>
					<TD><input type="text" name="phone" size="10" maxlength="9"  value="<?php echo $line['phone']; ?>" /></TD>
				</TR>
				<TR>
					<TD>GSM</TD>
					<TD><input type="text" name="gsm" size="11" maxlength="10" value="<?php echo $line['gsm']; ?>" /></TD>
				</TR>
				<TR>
					<TD>email</TD>
					<TD><input type="text" name="email" size="25" value="<?php echo $line['email']; ?>" /></TD>
				</TR>
				<TR>
					<TD colspan="2"><HR></TD>
				</TR>
				<TR>
					<TD>N° de licence</TD>
					<TD><input type="text" name="ffgid" size="10" maxlength="11" value="<?php echo $line['ffgid']; ?>" /></TD>
				</TR>
				<!--
				<TR>
					<TD>T-Shirt donné ?</TD>
					<TD><input type="checkbox" id="given" name="given" /></TD>
				</TR>
				-->
				<TR>
					<TD>Cours</TD>
					<TD> 
						<SELECT id="course" name="course[]" size="10" multiple>
							<?php
								if(date("n") >= "8") {
									$season = date("Y")."-".(date("Y") + 1);
								} else {
									$season = (date("Y") - 1)."-".date("Y");
								}

								$query = "SELECT xtr_course.courseid, day, h_begin, h_end, acronym FROM xtr_course, xtr_subdiscipline WHERE xtr_course.subdisciplineid = xtr_subdiscipline.subdisciplineid AND xtr_course.season = '$season' AND xtr_subdiscipline.enable = 'Y' ORDER BY xtr_course.daynumber, xtr_course.h_begin, acronym";
								$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
								
								while ($line = mysql_fetch_array($result)) {
									echo "<OPTION value=\"".$line['courseid']."\">".$line['day']." - ".$line['acronym']." (".substr($line['h_begin'], 0, 5)." - ".substr($line['h_end'], 0, 5).")</OPTION>";
								}
							?>
						</SELECT>
					</TD>
				</TR>
				</TABLE>
				<table align="center" border="0" width="700" id="table1" cellspacing="0" cellpadding="0">
					<TR><TD colspan="2">&nbsp;</TD></TR>
					<TR bgcolor="#9DD4FB">
						<TD colspan="2">&nbsp;<u>Informations sur les Responsables</u></TD>
						<TD colspan="2" align="right">Adresse des responsables identique à celle du gymnaste&nbsp;&nbsp;<input type="checkbox" name="adr" onChange="copyInfo()" /></TD>
				</TR>
				<TR>
					<TD>Nom *</TD>
					<TD>
						<input type="hidden" name="id1" value="<?php echo $line1['personid'] ?>">
						<input type="text" name="lastname1" size="30" maxlength="50" value="<?php echo $line1['lastname']; ?>" >
					</TD>
					<TD>Nom</TD>
					<TD>
						<input type="hidden" name="id2" value="<?php echo $line2['personid'] ?>">
						<input type="text" name="lastname2" size="30" maxlength="50" value="<?php echo $line2['lastname']; ?>" >
					</TD>
				</TR>
				<TR>
					<TD>Prénom *</TD>
					<TD><input type="text" name="firstname1" size="25" maxlength="50" value="<?php echo $line1['firstname']; ?>" ></TD>
					<TD>Prénom</TD>
					<TD><input type="text" name="firstname2" size="25" maxlength="50" value="<?php echo $line2['firstname']; ?>" ></TD>
				</TR>
				<TR>
					<TD>Sexe *</TD>
					<TD><input type="radio" name="sexe1" value="M" <?php if($line1['sexe'] == "M") { echo "checked"; } ?>>Homme &nbsp;&nbsp;<input type="radio" name="sexe1" value="F"<?php if($line1['sexe'] == "F") { echo "checked"; } ?>>Femme</TD>
					<TD>Sexe</TD>
					<TD><input type="radio" name="sexe2" value="M" <?php if($line2['sexe'] == "M") { echo "checked"; } ?>>Homme &nbsp;&nbsp;<input type="radio" name="sexe2" value="F"<?php if($line2['sexe'] == "F") { echo "checked"; } ?>>Femme</TD>
				</TR>
				<TR>
						<TD>Lien de parenté *</TD>
						<TD>
							<SELECT name="type1">
								<OPTION value="default"></OPTION>
								<OPTION value="père"> Père </OPTION>
								<OPTION value="mère"> Mère </OPTION>
								<OPTION value="frère"> Frère </OPTION>
								<OPTION value="soeur"> Soeur </OPTION>
								<option value="conjoint"> Conjoint(e) </option>
								<OPTION value="oncle"> Oncle </OPTION>
								<OPTION value="tante"> Tante </OPTION>
								<OPTION value="grand-père"> Grand-père </OPTION>
								<OPTION value="grand-mère"> Grand-mère </OPTION>
								<OPTION value="tuteur"> Tuteur </OPTION>
								<OPTION value="tutrice"> Tutrice </OPTION>
								<!-- <OPTION value="mariés"> Marié </OPTION> -->
							</SELECT>
						</TD>
						<TD>Lien de parenté</TD>
						<TD>
							<SELECT name="type2">
								<OPTION value="default"></OPTION>
								<OPTION value="père"> Père </OPTION>
								<OPTION value="mère"> Mère </OPTION>
								<OPTION value="frère"> Frère </OPTION>
								<OPTION value="soeur"> Soeur </OPTION>
								<option value="conjoint"> Conjoint(e) </option>
								<OPTION value="oncle"> Oncle </OPTION>
								<OPTION value="tante"> Tante </OPTION>
								<OPTION value="grand-père"> Grand-père </OPTION>
								<OPTION value="grand-mère"> Grand-mère </OPTION>
								<OPTION value="tuteur"> Tuteur </OPTION>
								<OPTION value="tutrice"> Tutrice </OPTION>
								<!-- <OPTION value="mariés"> Marié </OPTION> -->
							</SELECT>
						</TD>
					</TR>
				<TR bgcolor="#E7F1F7">
					<TD>Adresse *</TD>
					<TD><input type="text" name="address1" size="30" maxlength="50" value="<?php echo $line1['address']; ?>" ></TD>
					<TD>Adresse</TD>
					<TD><input type="text" name="address2" size="30" maxlength="50" value="<?php echo $line2['address']; ?>" ></TD>
				</TR>
				<TR bgcolor="#E7F1F7">
					<TD>Boite</TD>
					<TD><input type="text" name="box1" size="4" maxlength="4" value="<?php echo $line1['box']; ?>" /></TD>
					<TD>Boite</TD>
					<TD><input type="text" name="box2" size="4" maxlength="4" value="<?php echo $line2['box']; ?>" /></TD>
				</TR>
				<TR bgcolor="#E7F1F7">
					<TD>Code postal *</TD>
					<TD><input type="text" name="postal1" size="5" maxlength="4" value="<?php echo $line1['postal']; ?>" ></TD>
					<TD>Code postal</TD>
					<TD><input type="text" name="postal2" size="5" maxlength="4" value="<?php echo $line2['postal']; ?>" ></TD>
				</TR>
				<TR bgcolor="#E7F1F7">
					<TD>Ville *</TD>
					<TD><input type="text" name="city1" size="20" maxlength="50" value="<?php echo $line1['city']; ?>" ></TD>
					<TD>Ville</TD>
					<TD><input type="text" name="city2" size="20" maxlength="50" value="<?php echo $line2['city']; ?>" ></TD>
				</TR>
				<TR bgcolor="#E7F1F7">
					<TD>Téléphone</TD>
					<TD><input type="text" name="phone1" size="10" maxlength="9" value="<?php echo $line1['phone']; ?>" /></TD>
					<TD>Téléphone</TD>
					<TD><input type="text" name="phone2" size="10" maxlength="9" value="<?php echo $line2['phone']; ?>" /></TD>
				</TR>
				<TR>
					<TD>Profession</TD>
					<TD><input type="text" name="profession1" maxlength="30" value="<?php echo $line1['profession']; ?>" /></TD>
					<TD>Profession</TD>
					<TD><input type="text" name="profession2" maxlength="30" value="<?php echo $line2['profession']; ?>" /></TD>
				</TR>
				<TR>
					<TD>GSM *</TD>
					<TD><input type="text" name="gsm1" size="11" maxlength="10" value="<?php echo $line1['gsm']; ?>" /></TD>
					<TD>GSM</TD>
					<TD><input type="text" name="gsm2" size="11" maxlength="10" value="<?php echo $line2['gsm']; ?>" /></TD>
				</TR>
				<TR>
					<TD>email *</TD>
					<TD><input type="text" name="email1" size="25" value="<?php echo $line1['email']; ?>" /></TD>
					<TD>email</TD>
					<TD><input type="text" name="email2" size="25" value="<?php echo $line2['email']; ?>" /></TD>
				</TR>
				<TR>
					<TD colspan="4">
						<p class="important" align="center"><b>Au moins un responsable doit être complété.</b></p>
					</TD>
				</TR>
				</TABLE>
			<p align="center"><input type="submit" name="submit" value="Ajouter"></p>
			</FORM>
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