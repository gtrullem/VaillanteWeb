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
	
	if(empty($_GET['id'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=gymnaste&referrer=person_listing.php");
		exit;
	}
	
	$id = $_GET['id'];
	
	$query = " SELECT COUNT(relationshipid) FROM xtr_relationship WHERE personid = '$id' AND responsable = 'Y'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (relationship) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$count = mysql_fetch_array($result);
	if($count[0] >= 2) {
		header("Refresh: 4; url=./person_listing.php");
		echo "<p align=\"center\" class=\"important\">Vous ne pouvez pas rajouter de responsable pour ce gymnaste : maximum atteint.<br /> Vous allez être redirigé(e) dans 4 secondes...</p>";
		exit;
	}

	if(isset($_POST['submit'])) {
			
			// Responsable 1 Insertion
			if((isset($_POST['lastname1'])) && ($_POST['lastname1'] != "")) {
				$lastname = mysql_real_escape_string(stripslashes($_POST['lastname1']));
				$firstname = mysql_real_escape_string(stripslashes($_POST['firstname1']));
//				if(!isset($_POST['adr'])) {
					$address = mysql_real_escape_string(stripslashes($_POST['address1']));
					$postal = $_POST['postal1'];
					$city = mysql_real_escape_string(stripslashes($_POST['city1']));
					$phone = $_POST['phone1'];
//				} else {
//					$address = mysql_real_escape_string(stripslashes(($line['address'])); // addslashes obligatoire car vient de la DB et pas du POST
//					$postal = $line['postal'];
//					$city = $line['city'];
//					$phone = mysql_real_escape_string(stripslashes(($line['phone'])); // addslashes obligatoire car vient de la DB et pas du POST
//					$box = $line['box'];
//				}
				$gsm = $_POST['gsm1'];
				$email = $_POST['email1'];
				$profession = mysql_real_escape_string(stripslashes($_POST['profession1']));
				$sexe = $_POST['sexe1'];
				$type1 = $_POST['type1'];
				
				$query = " INSERT INTO xtr_person (lastname, firstname, address, box, postal, city, phone, gsm, email, profession, sexe) VALUES ('$lastname', '$firstname', '$address', '$box', '$postal', '$city', '$phone', '$gsm', '$email', '$profession', '$sexe') ";
//				echo $query."<br/>";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
				$query = " SELECT LAST_INSERT_ID()";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GET LAST ID !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
				$id_parent1 = mysql_fetch_array($result);
			}
			
			/***** Insertion du parent 2 *****/
			if((isset($_POST['lastname2'])) && ($_POST['lastname2'] != "")) {
				$lastname = $_POST['lastname2'];
				$firstname = $_POST['firstname2'];
//				if(!isset($_POST['adr'])) {
					$address = mysql_real_escape_string(stripslashes($_POST['address2']));
					$postal = $_POST['postal2'];
					$city = mysql_real_escape_string(stripslashes($_POST['city2']));
					$phone = $_POST['phone2'];
//				} else {
//					$address = mysql_real_escape_string(stripslashes($line['address'])); // addslashes obligatoire car vient de la DB et pas du POST
//					$postal = $line['postal'];
//					$city = mysql_real_escape_string(stripslashes($line['city'])); // addslashes obligatoire car vient de la DB et pas du POST
//					$phone = $line['phone'];
//					$box = $line['box'];
//				}
				$gsm = $_POST['gsm2'];
				$email = $_POST['email2'];
				$profession = mysql_real_escape_string(stripslashes($_POST['profession2']));
				$sexe = $_POST['sexe2'];
				$type2 = $_POST['type2'];
				
				$query = " INSERT INTO xtr_person (lastname, firstname, address, box, postal, city, phone, gsm, email, profession, sexe) VALUES ('$lastname', '$firstname', '$address', '$box', '$postal', '$city', '$phone', '$gsm', '$email', '$profession', '$sexe') ";
//				echo $query."<br/>";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
				$query = " SELECT LAST_INSERT_ID()";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GET LAST ID !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
				$id_parent2 = mysql_fetch_array($result);
			}
			
			/***** Création de la relation entre les personnes *****/
			$query = " INSERT INTO xtr_relationship (personid, personid1, type) VALUES ('$id', '$id_parent1[0]', '$type1')";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (relationship) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			
			$query = " INSERT INTO xtr_relationship (personid, personid1, type) VALUES ('$id', '$id_parent2[0]', '$type2')";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (relationship) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			
			header("Location: ./person_detail.php?id=$id");
			exit;
	}
	
	$query = " SELECT * FROM xtr_person WHERE personid='$id';";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT person by id !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Ajout de parents :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<SCRIPT language="javascript">
		function copyInfo()
		{
			if(document.formulaire.adr.checked) {
				// Address
				document.formulaire.address1.value = document.formulaire.address.value;
				document.formulaire.address2.value = document.formulaire.address.value;
				
				// Box
				document.formulaire.box1.value = document.formulaire.box.value;
				document.formulaire.box2.value = document.formulaire.box.value;
				
				// Postal
				document.formulaire.postal1.value = document.formulaire.postal.value;
				document.formulaire.postal2.value = document.formulaire.postal.value;
				
				// City
				document.formulaire.city1.value = document.formulaire.city.value;
				document.formulaire.city2.value = document.formulaire.city.value;
				
				// Phone
				document.formulaire.phone1.value = document.formulaire.phone.value;
				document.formulaire.phone2.value = document.formulaire.phone.value;
			} else {
				// Address
				document.formulaire.address1.value = "";
				document.formulaire.address2.value = "";
				
				// Box
				document.formulaire.box1.value = "";
				document.formulaire.box2.value = "";
				
				// Postal
				document.formulaire.postal1.value = "";
				document.formulaire.postal2.value = "";
				
				// City
				document.formulaire.city1.value = "";
				document.formulaire.city2.value = "";
				
				// Phone
				document.formulaire.phone1.value = "";
				document.formulaire.phone2.value = "";
			}
		}
		
		function checkForm(formulaire)
		{
			
			// parentIsIncorrect(lastname, firstname, address, postal, city, gsm, adr)
			if(parentIsIncorrect(document.formulaire.lastname1.value, document.formulaire.firstname1.value, document.formulaire.address1.value, document.formulaire.postal1.value, document.formulaire.city1.value, document.formulaire.gsm1.value, document.formulaire.adr.checked, document.formulaire.email1.value) && parentIsIncorrect(document.formulaire.lastname2.value, document.formulaire.firstname2.value, document.formulaire.address2.value, document.formulaire.postal2.value, document.formulaire.city2.value, document.formulaire.gsm2.value, document.formulaire.adr.checked, document.formulaire.email2.value)) {
				alert('Veuillez remplir tous les champs obligatoires (*) pour au moins un des deux parents.')
				return false;
			}
			
			return true;
		}
		
		function parentIsIncorrect(lastname, firstname, address, postal, city, gsm, adr, email)
		{
			if(lastname == "") { return true; }
			if(firstname == "") { return true; }
			
			if(!adr) {
				if(address == "") { return true; }
				if(postal == "") { return true; }
				if(city == "") { return true; }
			}
			
			if(gsm == "") { return true; }
			
			var reg = new RegExp("^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$");
			//var reg = new RegExp("^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$");
			if ((email != "") && (!reg.test(email))) {
				alert('Veuillez entrer une adresse email valide.');
				return true;
			}
			
			return false;
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
				<H2><a>Ajout de Responsables</a></H2>
				<br />
				<?php 
					if(isset($err)) {
						echo "<p class=\"important\">".$err."</p>";
					}
				?>
				<FORM name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?id=".$id; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form);">
				<table align="center" border="0" width="70%" id="table1" cellspacing="0" cellpadding="0">
					<TR bgcolor="#9DD4FB">
						<TD colspan="2" align="center"><u>Informations sur le gymnaste</u></TD>
					</TR>
					<TR>
						<TD>Nom</TD>
						<TD><?php echo $line['lastname']; ?></TD>
					</TR>
					<TR>
						<TD>Prénom</TD>
						<TD><?php echo $line['firstname']; ?></TD>
					</TR>
					<TR>
						<TD>Date de naissance</TD>
						<TD><?php echo substr($line['birth'], 8, 2)."/".substr($line['birth'], 5, 2)."/".substr($line['birth'], 0, 4); ?></TD>
					</TR>
					<TR>
						<TD>Lieu de naissance</TD>
						<TD>
						<?php
							echo $line['birthplace']; 
						?>
						</TD>
					</TR>
					<TR>
						<TD>Sexe</TD>
						<TD><?php echo $line['sexe']; ?></TD>
					</TR>
					<TR>
						<TD>Numéro NISS</TD>
						<TD><?php echo $line['niss']; ?></TD>
					</TR>
					<TR bgcolor="#E7F1F7">
						<TD>Adresse</TD>
						<TD>
						<?php 
							echo $line['address'];
							if(($line['box'] != NULL) && ($line['box'] != "")) {
								echo " ,boite ".$line['box'];
							}
						?>
						<INPUT type="hidden" name="address" value="<?php echo $line['address']; ?>" />
						<INPUT type="hidden" name="box" value="<?php echo $line['box']; ?>" />
						<INPUT type="hidden" name="postal" value="<?php echo $line['postal']; ?>" />
						<INPUT type="hidden" name="city" value="<?php echo $line['city']; ?>" />
						<INPUT type="hidden" name="phone" value="<?php echo $line['phone']; ?>" />
						</TD>
					</TR>
					<TR bgcolor="#E7F1F7">
						<TD>&nbsp;</TD>
						<TD><?php echo $line['postal']." ".$line['city']; ?></TD>
					</TR>
					<TR bgcolor="#E7F1F7">
						<TD>Téléphone</TD>
						<?php
							if($line['phone'] != "") {
								$test = "/^02[0-9]{7}$/";
								// if(ereg($test, $line['phone'])) {
								if(preg_match($test, $line['phone'])) {
									echo "<TD>".substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2)."</TD>";
								} else {
									echo "<TD>".substr($line['phone'], 0, 3)."/".substr($line['phone'], 3, 2).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2)."</TD>";
								}
							} else {
								echo "<TD>&nbsp;</TD>";
							}
						?>
					</TR>
					<TR>
						<TD>GSM</TD>
						<TD>
							<?php
								if($line['gsm'] != "") {
									echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2);
								}
							?>
						</TD>
					</TR>
					<TR>
						<TD>email</TD>
						<TD><?php echo $line['email']; ?></TD>
					</TR>
					</TABLE>
					<table align="center" border="0" width="70%" id="table1" cellspacing="0" cellpadding="0">
					<TR><TD colspan="2">&nbsp;</TD></TR>
					<TR bgcolor="#9DD4FB">
						<TD>&nbsp;<u>Responsable</u></TD>
						<TD colspan="3" align="right">Adresse des parents identique à celle du gymnaste <input type="checkbox" name="adr" onChange="copyInfo()"></TD>
					</TR>
					<TR>
						<TD>Nom *</TD>
						<TD><input type="text" name="lastname1" maxlength="50" value=""></TD>
						<TD>Nom *</TD>
						<TD><input type="text" name="lastname2" maxlength="50" value=""></TD>
					</TR>
					<TR>
						<TD>Prénom *</TD>
						<TD><input type="text" name="firstname1" maxlength="50" value=""></TD>
						<TD>Prénom *</TD>
						<TD><input type="text" name="firstname2" maxlength="50" value=""></TD>
					</TR>
					<TR>
						<TD>Sexe</TD>
						<TD><input type="radio" name="sexe1" value="M">Homme &nbsp;&nbsp;<input type="radio" name="sexe1" value="F">Femme</TD>
						<TD>Sexe</TD>
						<TD><input type="radio" name="sexe2" value="M">Homme &nbsp;&nbsp;<input type="radio" name="sexe2" value="F">Femme</TD>
					</TR>
					<TR>
						<TD>Lien de parenté :</TD>
						<TD>
							<SELECT name="type1">
								<OPTION value="default"></OPTION>
								<OPTION value="père"> Père </OPTION>
								<OPTION value="mère"> Mère </OPTION>
								<OPTION value="frère"> Frère </OPTION>
								<OPTION value="soeur"> Soeur </OPTION>
								<OPTION value="oncle"> Oncle </OPTION>
								<OPTION value="tante"> Tante </OPTION>
								<OPTION value="grand-père"> Grand-père </OPTION>
								<OPTION value="grand-mère"> Grand-mère </OPTION>
								<OPTION value="Tuteur/Tutrice"> Tuteur/Tutrice </OPTION>
								<!-- <OPTION value="mariés"> Marié </OPTION> -->
							</SELECT>
						</TD>
						<TD>Lien de parenté :</TD>
						<TD>
							<SELECT name="type2">
								<OPTION value="default"></OPTION>
								<OPTION value="père"> Père </OPTION>
								<OPTION value="mère"> Mère </OPTION>
								<OPTION value="frère"> Frère </OPTION>
								<OPTION value="soeur"> Soeur </OPTION>
								<OPTION value="oncle"> Oncle </OPTION>
								<OPTION value="tante"> Tante </OPTION>
								<OPTION value="grand-père"> Grand-père </OPTION>
								<OPTION value="grand-mère"> Grand-mère </OPTION>
								<OPTION value="Tuteur/Tutrice"> Tuteur/Tutrice </OPTION>
								<!-- <OPTION value="mariés"> Marié </OPTION> -->
							</SELECT>
						</TD>
					</TR>
					<TR bgcolor="#E7F1F7">
						<TD>Adresse *</TD>
						<TD><input type="text" name="address1" size="30" maxlength="100"></TD>
						<TD>Adresse *</TD>
						<TD><input type="text" name="address2" size="30" maxlength="100"></TD>
					</TR>
					<TR bgcolor="#E7F1F7">
						<TD>Boite</TD>
						<TD><input type="text" name="box1" size="5" maxlength="5"></TD>
						<TD>Boite</TD>
						<TD><input type="text" name="box2" size="5" maxlength="5"></TD>
					</TR>
					<TR bgcolor="#E7F1F7">
						<TD>Code postal *</TD>
						<TD><input type="text" name="postal1" size="4" maxlength="4"></TD>
						<TD>Code postal *</TD>
						<TD><input type="text" name="postal2" size="4" maxlength="4"></TD>
					</TR>
					<TR bgcolor="#E7F1F7">
						<TD>Ville *</TD>
						<TD><input type="text" name="city1" maxlength="50"></TD>
						<TD>Ville *</TD>
						<TD><input type="text" name="city2" maxlength="50"></TD>
					</TR>
					<TR bgcolor="#E7F1F7">
						<TD>Téléphone (principal)</TD>
						<TD><input type="text" name="phone1" size="10" value="" maxlength="9"></TD>
						<TD>Téléphone (principal)</TD>
						<TD><input type="text" name="phone2" size="10" value="" maxlength="9"></TD>
					</TR>
					<TR>
						<TD>Profession </TD>
						<TD><input type="text" name="profession1" maxlength="30" value=""></TD>
						<TD>Profession</TD>
						<TD><input type="text" name="profession2" maxlength="30" value=""></TD>
					</TR>
					<TR>
						<TD>GSM *</TD>
						<TD><input type="text" name="gsm1" size="11" value="" maxlength="10"></TD>
						<TD>GSM *</TD>
						<TD><input type="text" name="gsm2" size="11" value="" maxlength="10"></TD>
					</TR>
					<TR>
						<TD>email</TD>
						<TD><input type="text" name="email1" size="40"></TD>
						<TD>email</TD>
						<TD><input type="text" name="email2" size="40"></TD>
					</TR>
					<TR>
						<TD colspan="4">
							<p class="important" align="center"><b>Au moins un parent doit être complété.</b></p>
						</TD>
					</TR>
				</table>
				<p align="center"><input type="submit" name="submit" value="Ajouter"></p>
				</FORM>
				<!-- ========================== END FORM ======================= -->
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