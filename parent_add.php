<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] < 4) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

	if(empty($_GET['id'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=affilié&referrer=person_listing.php");
		exit;
	}
	
	$id = $_GET['id'];
	
	$query = " SELECT * FROM xtr_person WHERE personid='$id';";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);

	if(isset($_POST['submit'])) {
			
			/***** Insertion du parent 1 *****/
			if((isset($_POST['lastname1'])) && ($_POST['lastname1'] != "")) {
				$lastname = mysql_real_escape_string(stripslashes($_POST['lastname1']));
				$firstname = mysql_real_escape_string(stripslashes($_POST['firstname1']));
				if(!isset($_POST['adr'])) {
					$address = mysql_real_escape_string(stripslashes($_POST['address1']));
					$postal = $_POST['postal1'];
					$city = mysql_real_escape_string(stripslashes($_POST['city1']));
					$phone = $_POST['phone1'];
				} else {
					$address = addslashes($line['address']); // addslashes obligatoire car vient de la DB et pas du POST
					$postal = $line['postal'];
					$city = $line['city'];
					$phone = addslashes($line['phone']); // addslashes obligatoire car vient de la DB et pas du POST
					$box = $line['box'];
				}
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
				if(!isset($_POST['adr'])) {
					$address = mysql_real_escape_string(stripslashes($_POST['address2']));
					$postal = $_POST['postal2'];
					$city = mysql_real_escape_string(stripslashes($_POST['city2']));
					$phone = $_POST['phone2'];
				} else {
					$address = addslashes($line['address']); // addslashes obligatoire car vient de la DB et pas du POST
					$postal = $line['postal'];
					$city = addslashes($line['city']); // addslashes obligatoire car vient de la DB et pas du POST
					$phone = $line['phone'];
					$box = $line['box'];
				}
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
			exit();
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Ajout de parents :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<SCRIPT language="javascript">
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
			<H2><a>Ajout de parents</a></H2>
			<br />
			<?php 
				if(isset($err)) {
					echo "<p class=\"important\">".$err."</p>";
				}
			?>
			<form name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?id=".$id; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form);">
			<table align="center" border="0" width="70%" id="table1" cellspacing="0" cellpadding="0">
				<TR bgcolor="#9DD4FB">
					<TD colspan="2" align="center"><u>Informations de l'Inscrit</u></td>
				</tr>
				<tr>
					<td>Nom</td>
					<td><?php echo $line['lastname']; ?></td>
				</tr>
				<tr>
					<td>Prénom</td>
					<td><?php echo $line['firstname']; ?></td>
				</tr>
				<tr>
					<td>Date de naissance</td>
					<td><?php echo substr($line['birth'], 8, 2)."/".substr($line['birth'], 5, 2)."/".substr($line['birth'], 0, 4); ?></td>
				</tr>
				<tr>
					<td>Lieu de naissance</td>
					<td>
					<?php
						echo $line['birthplace']; 
					?>
					</td>
				</tr>
				<tr>
					<td>Sexe</td>
					<td><?php echo $line['sexe']; ?></td>
				</tr>
				<tr>
					<td>Numéro NISS</td>
					<td><?php echo $line['niss']; ?></td>
				</tr>
				<TR bgcolor="#E7F1F7">
					<td>Adresse</td>
					<td>
					<?php 
						echo $line['address'];
						if(($line['box'] != NULL) && ($line['box'] != "")) {
							echo " ,boite ".$line['box'];
						}
					?>
					</td>
				</tr>
				<TR bgcolor="#E7F1F7">
					<td>&nbsp;</td>
					<td><?php echo $line['postal']." ".$line['city']; ?></td>
				</tr>
				<TR bgcolor="#E7F1F7">
					<td>Téléphone</td>
					<?php
						if($line['phone'] != "") {
							$test = "/^02[0-9]{7}$/";
							if(preg_match($test, $line['phone'])) {
								echo "<td>".substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2)."</td>";
							} else {
								echo "<td>".substr($line['phone'], 0, 3)."/".substr($line['phone'], 3, 2).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2)."</td>";
							}
						} else {
							echo "<td>&nbsp;</td>";
						}
					?>
				</tr>
				<tr>
					<td>GSM</td>
					<td>
						<?php
							if($line['gsm'] != "") {
								echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2);
							}
						?>
					</td>
				</tr>
				<tr>
					<td>email</td>
					<td><?php echo $line['email']; ?></td>
				</tr>
				</TABLE>
				<table align="center" border="0" width="70%" id="table1" cellspacing="0" cellpadding="0">
				<tr><TD colspan="2">&nbsp;</td></tr>
				<TR bgcolor="#9DD4FB">
					<td>&nbsp;<u>Parents</u></td>
					<TD colspan="3" align="right">Adresse des parents identique à celle de l'enfant <input type="checkbox" name="adr"></td>
				</tr>
				<tr>
					<td>Nom *</td>
					<td><input type="text" name="lastname1" maxlength="50" value=""></td>
					<td>Nom *</td>
					<td><input type="text" name="lastname2" maxlength="50" value=""></td>
				</tr>
				<tr>
					<td>Prénom *</td>
					<td><input type="text" name="firstname1" maxlength="50" value=""></td>
					<td>Prénom *</td>
					<td><input type="text" name="firstname2" maxlength="50" value=""></td>
				</tr>
				<tr>
					<td>Sexe</td>
					<td><input type="radio" name="sexe1" value="M">Homme &nbsp;&nbsp;<input type="radio" name="sexe1" value="F">Femme</td>
					<td>Sexe</td>
					<td><input type="radio" name="sexe2" value="M">Homme &nbsp;&nbsp;<input type="radio" name="sexe2" value="F">Femme</td>
				</tr>
				<tr>
					<td>Lien de parenté :</td>
					<td>
						<SELECT name="type1">
							<option value="default"></option>
							<option value="père"> Père </option>
							<option value="mère"> Mère </option>
							<option value="frère"> Frère </option>
							<option value="soeur"> Soeur </option>
							<option value="oncle"> Oncle </option>
							<option value="tante"> Tante </option>
							<option value="grand-père"> Grand-père </option>
							<option value="grand-mère"> Grand-mère </option>
							<option value="Tuteur/Tutrice"> Tuteur/Tutrice </option>
							<!-- <option value="mariés"> Marié </option> -->
						</SELECT>
					</td>
					<td>Lien de parenté :</td>
					<td>
						<SELECT name="type2">
							<option value="default"></option>
							<option value="père"> Père </option>
							<option value="mère"> Mère </option>
							<option value="frère"> Frère </option>
							<option value="soeur"> Soeur </option>
							<option value="oncle"> Oncle </option>
							<option value="tante"> Tante </option>
							<option value="grand-père"> Grand-père </option>
							<option value="grand-mère"> Grand-mère </option>
							<option value="Tuteur/Tutrice"> Tuteur/Tutrice </option>
							<!-- <option value="mariés"> Marié </option> -->
						</SELECT>
					</td>
				</tr>
				<TR bgcolor="#E7F1F7">
					<td>Adresse *</td>
					<td><input type="text" name="address1" size="30" maxlength="100"></td>
					<td>Adresse *</td>
					<td><input type="text" name="address2" size="30" maxlength="100"></td>
				</tr>
				<TR bgcolor="#E7F1F7">
					<td>Boite</td>
					<td><input type="text" name="box1" size="5" maxlength="5"></td>
					<td>Boite</td>
					<td><input type="text" name="box2" size="5" maxlength="5"></td>
				</tr>
				<TR bgcolor="#E7F1F7">
					<td>Code postal *</td>
					<td><input type="text" name="postal1" size="4" maxlength="4"></td>
					<td>Code postal *</td>
					<td><input type="text" name="postal2" size="4" maxlength="4"></td>
				</tr>
				<TR bgcolor="#E7F1F7">
					<td>Ville *</td>
					<td><input type="text" name="city1" maxlength="50"></td>
					<td>Ville *</td>
					<td><input type="text" name="city2" maxlength="50"></td>
				</tr>
				<TR bgcolor="#E7F1F7">
					<td>Téléphone (principal)</td>
					<td><input type="text" name="phone1" size="10" value="" maxlength="9"></td>
					<td>Téléphone (principal)</td>
					<td><input type="text" name="phone2" size="10" value="" maxlength="9"></td>
				</tr>
				<tr>
					<td>Profession </td>
					<td><input type="text" name="profession1" maxlength="30" value=""></td>
					<td>Profession</td>
					<td><input type="text" name="profession2" maxlength="30" value=""></td>
				</tr>
				<tr>
					<td>GSM *</td>
					<td><input type="text" name="gsm1" size="11" value="" maxlength="10"></td>
					<td>GSM *</td>
					<td><input type="text" name="gsm2" size="11" value="" maxlength="10"></td>
				</tr>
				<tr>
					<td>email</td>
					<td><input type="text" name="email1" size="40"></td>
					<td>email</td>
					<td><input type="text" name="email2" size="40"></td>
				</tr>
				<tr>
					<TD colspan="4">
						<p class="important" align="center"><b>Au moins un parent doit être complété.</b></p>
					</td>
				</tr>
			</table>
			<p align="center"><input type="submit" name="submit" value="Ajouter"></p>
			</form>
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