<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_add";
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] < $line['statusout']){
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

	if(empty($_GET['id'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=affilié&referrer=affiliate_listing.php");
		exit;
	}
	
	$id = $_GET['id'];

	if(isset($_POST['submit'])) {
		/***** Ajout d'une personne *****/
		/* Vérifier que l'adresse est identique à celle de l'un des deux parents !!!! */
		$birth = $_POST['birth'];
		$lastname = mysql_real_escape_string(stripslashes($_POST['lastname']));
		$firstname = mysql_real_escape_string(stripslashes($_POST['firstname']));
		/*
		$query = " SELECT personid FROM xtr_person WHERE lastname = '$lastname' AND firstname = '$firstname' AND birth = '$birth'";
		//echo $query."<br />";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GET LAST ID !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		if(mysql_fetch_array($result)) {
			$err = " Vous êtes déjà inscrit. Veuillez contacter une personne responsable pour votre inscription.";
		} else {
			$city = mysql_real_escape_string(stripslashes($_POST['city']));
			$address = mysql_real_escape_string(stripslashes($_POST['address']));
			$postal = $_POST['postal'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$gsm = $_POST['gsm'];
			
			$query = " INSERT INTO xtr_person (lastname, firstname, birth, address, postal, city, phone, gsm, email) VALUES ('$lastname', '$firstname', '$birth', '$address', '$postal', '$city', '$phone', '$gsm', '$email') ";
			//echo $query."<br/>";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			$query = " SELECT LAST_INSERT_ID()";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GET LAST ID !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			$id_affiliate = mysql_fetch_array($result);
			
			$idp1 = $_POST['responsable1'];
			$type = $_POST['type1'];
			$query = "INSERT INTO xtr_relationship (personid, personid1, type, responsable) VALUES ('$id_affiliate[0]', '$idp1', '$type', 'Y')";
			// echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (relation) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

			$idp2 = $_POST['responsable2'];
			$type = $_POST['type2'];
			$query = "INSERT INTO xtr_relationship (personid, personid1, type, responsable) VALUES ('$id_affiliate[0]', '$idp2', '$type', 'Y')";
			// echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (relation) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			
			// brother/sister relation
			// $query = " SELECT personid1 FROM xtr_relationship WHERE personid='$id' AND responsable = 'Y';";
			// $result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			
			///////////////////////////////////////////////////////////////////////
			// Affiliation treatment
			///////////////////////////////////////////////////////////////////////
			$course = $_POST['course'];
			
			for($i = 0; $i<sizeof($course); $i++) {
				$query = " INSERT INTO xtr_isaffiliate (courseid, pid) VALUES ('$course[$i]', '$id_affiliate[0]');";
				$result = mysql_query($query,$connect) or trigger_error("SQL Error : INSERT FAILED (course) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			}
			
			header("Location: ./affiliate_details.php?id=".$id_affiliate[0]);
			exit;
		}
     * */
	}
	
	/* RECUPERER LES INFO DE L'ADRESSE DE L'AUTRE ENFANT */
	$query = " SELECT lastname, address, postal, city, phone, resp1id, resp2id FROM xtr_person WHERE personid='$id'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (affiliate/person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);

	// $query = " SELECT resp1 FROM xtr_person WHERE personid='$id' AND responsable = 'Y';";
	// echo $query."<br />";
	//  $result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (affiliate/person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	
	// $line = mysql_fetch_array($result);
	// $query1 = " SELECT * FROM xtr_person WHERE personid='".$line['resp1id']."'";
	// echo $query."<br />";
	// $line = mysql_fetch_array($result);
	// $query2 = " SELECT * FROM xtr_person WHERE personid='".$line['resp2id']."'";
	// echo $query."<br />";
	
	// $result1 = mysql_query($query1,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person1) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	// $result2 = mysql_query($query2,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person2) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	
	// $line1 = mysql_fetch_array($result1);
	// $line2 = mysql_fetch_array($result2);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Nouvel affilié :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<script type="text/javascript" language="javascript" src="./library/library.js"></script>
	<script type="text/javascript" language="javascript">
	 var m1;
	 var m2;
	 
	 function createListObjects()
	 {
	   m1 = document.formulaire.courseavailable;
     m2 = document.formulaire.coursechoosed;
	 }
	 
	 function one2two()
	 {
      m1len = m1.length ;
      for ( i=0; i<m1len ; i++){
          if (m1.options[i].selected == true ) {
              m2len = m2.length;
              m2.options[m2len]= new Option(m1.options[i].text);
          }
      }
      
      for ( i = (m1len -1); i>=0; i--){
          if (m1.options[i].selected == true ) {
              m1.options[i] = null;
          }
      }
    }

  function two2one()
  {
    m2len = m2.length ;
        for ( i=0; i<m2len ; i++){
            if (m2.options[i].selected == true ) {
                m1len = m1.length;
                m1.options[m1len]= new Option(m2.options[i].text);
            }
        }
        for ( i=(m2len-1); i>=0; i--) {
            if (m2.options[i].selected == true ) {
                m2.options[i] = null;
            }
        }
    }

		function checkForm(formulaire)
		{
		
			if(!affiliateIsCorrect())	return false;
			
			//////////////////////////////////////////////////////////////////////////
			// Preparing Data
			//////////////////////////////////////////////////////////////////////////
			if(document.formulaire.gsm.value.length <= 2)	document.formulaire.gsm.value = "";
			if(document.formulaire.phone.value.length <= 1)	document.formulaire.phone.value = "";
			document.formulaire.birth.value = document.formulaire.birthyear.value+'-'+document.formulaire.birthmonth.value+'-'+document.formulaire.birthday.value;

			var myselect=document.getElementById("coursechoosed")
			for (var i=0; i<myselect.options.length; i++) {
				myselect.options[i].selected = true;
			}
	
			return true;
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
			
			document.formulaire.birth.value = document.formulaire.birthday.value+'/'+document.formulaire.birthmonth.value+'/'+document.formulaire.birthyear.value;
			if(!dateIsCorrect(document.formulaire.birth.value)) {
				alert('Date de naissance incorrecte.');
				return false;
			}
			
			if(document.formulaire.address.value.length < 4) {
				alert('Veuillez indiquer l\'adresse principale de l\'inscrit.');
				document.formulaire.address.focus();
				return false;
			}
			
			var reg = new RegExp("^[0-9]{4}$");
			if(!reg.test(document.formulaire.postal.value)) {
				alert('Veuillez indiquer le code postal principal de l\'inscrit.');
				document.formulaire.postal.focus();
				return false;
			}
			
			if(document.formulaire.city.value.length < 2) {
				alert('Veuillez indiquer la ville de résidence principale de l\'inscrit.');
				document.formulaire.city.focus();
				return false;
			}
			
			reg = new RegExp("^0[0-9]{8}$");
			if((document.formulaire.phone.value.length > 1) && !reg.test(document.formulaire.phone.value)) {
				alert('Numéro de téléphone de l\'inscrit incorrect.');
				document.formulaire.phone.focus();
				return false;
			}
			
			reg = new RegExp("^04[0-9]{8}$");
			if((document.formulaire.gsm.value.length > 2) && !reg.test(document.formulaire.gsm.value)) {
				alert('Numéro de GSM de l\'inscrit incorrect.');
				document.formulaire.gsm.focus();
				return false;
			}
			
			return true;
		}
	</script>
	<noscript>
		<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>
	</noscript>
</head>

<body onload="createListObjects()">
<div id="body">

<?php
	require_once("./header.php");
?>
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame">
			<div id="content">
				<!-- ========================= BEGIN FORM ====================== -->
				<H2><A>Ajout d'un affilié</A></H2>
				<?php 
					if(isset($err)) {
						echo "<p align=\"center\" class=\"important\">$err</p>";
					}
				?>
				<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?id=".$id; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
					<fieldset>
						<legend>Information de l'inscrit</legend>
						<p>
							<label>Nom *</label>
							<input type="text" name="lastname" id="lastname" size="30" maxlength="50" value="<?php echo $line['lastname']; ?>" />
						</p>
						<p>
							<label>Prénom *</label>
							<input type="text" name="firstname" id="firstname" size="25" maxlength="50" />
						</p>
						<p>
							<label>Date de naissance *</label>
							<input type="text" name="birthday" id="birthday" size="1" maxlength="2" onKeyUp="next(this, 'birthmonth', 2);" />/
							<input type="text" name="birthmonth" id="birthmonth" size="1" maxlength="2" onKeyUp="next(this, 'birthyear', 2);" />/
							<input type="text" name="birthyear" id="birthyear" size="3" maxlength="4" onKeyUp="next(this, 'birthplace', 4);" />
							<input type="hidden" name="birth" id="birth" />
						</p>
						<p>
							<label>Adresse (principale) *</label>
							<input type="text" name="address" id="address" size="30" maxlength="100" value="<?php echo $line['address']; ?>" />
						</p>
						<p>
							<label>Code postal *</label>
							<input type="text" name="postal" id="postal" size="6" maxlength="6" value="<?php echo $line['postal']; ?>" onKeyUp="next(this, 'city', 4);" />
						</p>
						<p>
							<label>Ville *</label>
							<input type="text" name="city" id="city" size="20" maxlength="50" value="<?php echo $line['city']; ?>" />
						</p>
						<p>
							<td>Téléphone (principal)</td>
							<input type="text" name="phone" id="phone" size="10" value="<?php echo $line['phone']; ?>" maxlength="9" onKeyUp="next(this, 'gsm', 9);" />
						</p>
						<p>
							<label>GSM</label>
							<input type="text" name="gsm" id="gsm" size="11" value="04" maxlength="10" onKeyUp="next(this, 'email', 10);" />
						</p>
						<p>
							<label>email</label>
							<input type="text" name="email" id="email" size="30" />
						</p>
					</fieldset>
				<table align="center" border="0" width="650" id="table2" cellspacing="0" cellpadding="0">
				  <tr>
					  <td width="150">Cours</td>
					  <td width="160" align="center"> 
							<select id="courseavailable" name="courseavailable[]" size="10" disable>
								<?php
									if(date("n") >= "8") {
										$season = date("y")."-".(date("y") + 1);
									} else {
										$season = (date("y") - 1)."-".date("y");
									}
	
									$query = "SELECT xtr_course.courseid, day, h_begin, h_end, acronym FROM xtr_course, xtr_subdiscipline, xtr_isaffiliate WHERE xtr_course.subdisciplineid = xtr_subdiscipline.subdisciplineid AND xtr_course.courseid = xtr_isaffiliate.courseid AND xtr_isaffiliate.season = '$season' AND xtr_subdiscipline.enable = 'Y' order by xtr_course.daynumber";
									$result = mysql_query($query,$connect) or trigger_error("sql error : select failed (course) !<br />".$result."<br />".mysql_error(), e_user_error);
									
									while ($line = mysql_fetch_array($result)) {
										echo "<option value=\"".$line['courseid']."\">".$line['day']." - ".$line['acronym']." (".substr($line['h_begin'], 0, 5)." - ".substr($line['h_end'], 0, 5).")</option>";
									}
								?>
							</select>
						</td>
						<td width="150" align="center">
							<input type="button" onClick="one2two()" value=">>" /><br />
							<br />
							<input type="button" onClick="two2one()" value="<<" />
						</td>
						<td width="160" align="center">
							<select id="coursechoosed" name="coursechoosed[]" size="10" disable>
							</select>
						</td>
					</tr>
				</TABLE>
					<table align="center" border="0" width="650" id="table3" cellspacing="0" cellpadding="0">
						<tr><TD colspan="2">&nbsp;</td></tr>
						<TR bgcolor="#9DD4FB">
							<TD colspan="4">&nbsp;<u>Informations sur les Responsables</u></td>
					</tr>
					<tr>
						<TD width="175">Nom</td>
						<TD width="225">
							<?php echo $line1['lastname']; ?>
							<input type="hidden" name="responsable1" value="<?php echo $line1['id']; ?>">
						</td>
						<TD width="175">Nom</td>
						<TD width="225">
							<?php echo $line2['lastname']; ?>
							<input type="hidden" name="responsable2" value="<?php echo $line2['id']; ?>">
						</td>
					</tr>
					<tr>
						<td>Prénom</td>
						<td><?php echo $line1['firstname']; ?></td>
						<td>Prénom</td>
						<td><?php echo $line2['firstname']; ?></td>
					</tr>
					<tr>
						<td>Lien de parenté *</td>
						<td>
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
								<OPTION value="Tuteur"> Tuteur </OPTION>
								<OPTION value="Tutrice"> Tutrice </OPTION>
								<!-- <OPTION value="mariés"> Marié </OPTION> -->
							</SELECT>
						</td>
						<td>Lien de parenté</td>
						<td>
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
								<OPTION value="Tuteur"> Tuteur </OPTION>
								<OPTION value="Tutrice"> Tutrice </OPTION>
								<!-- <OPTION value="mariés"> Marié </OPTION> -->
							</SELECT>
						</td>
					</tr>
					<tr>
						<td>Adresse</td>
						<td><?php echo $line1['address']; ?></td>
						<td>Adresse</td>
						<td><?php echo $line2['address']; ?></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo $line1['postal']." ".$line1['city']; ?></td>
						<td></td>
						<td><?php echo $line2['postal']." ".$line2['city']; ?></td>
					</tr>
					<tr>
						<td>Téléphone</td>
						<?php
							if($line1['phone'] != "") {
								$test = "/^02[0-9]{7}$/";
								// if(ereg($test, $line1['phone'])) {
								if(preg_match($test, $line1['phone'])) {
									echo "<td>".substr($line1['phone'], 0, 2)."/".substr($line1['phone'], 2, 3).".".substr($line1['phone'], 5, 2).".".substr($line1['phone'], 7, 2)."</td>";
								} else {
									echo "<td>".substr($line1['phone'], 0, 3)."/".substr($line1['phone'], 3, 2).".".substr($line1['phone'], 5, 2).".".substr($line1['phone'], 7, 2)."</td>";
								}
							} else {
								echo "<td>&nbsp;</td>";
							}
						?>
						<td>Téléphone</td>
						<?php
							if($line2['phone'] != "") {
								$test = "/^02[0-9]{7}$/";
								if(preg_match($test, $line2['phone'])) {
									echo "<td>".substr($line2['phone'], 0, 2)."/".substr($line2['phone'], 2, 3).".".substr($line2['phone'], 5, 2).".".substr($line2['phone'], 7, 2)."</td>";
								} else {
									echo "<td>".substr($line2['phone'], 0, 3)."/".substr($line2['phone'], 3, 2).".".substr($line2['phone'], 5, 2).".".substr($line2['phone'], 7, 2)."</td>";
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
								if($line1['gsm'] != "") {
									echo substr($line1['gsm'], 0, 4)."/".substr($line1['gsm'], 4, 2).".".substr($line1['gsm'], 6, 2).".".substr($line1['gsm'], 8, 2);
								}
							?>
						</td>
						<td>GSM</td>
						<td><?php echo substr($line2['gsm'], 0, 4)."/".substr($line2['gsm'], 4, 2).".".substr($line2['gsm'], 6, 2).".".substr($line2['gsm'], 8, 2); ?></td>
					</tr>
					<tr>
						<td>email</td>
						<td><?php echo $line1['email']; ?></td>
						<td>email</td>
						<td><?php echo $line2['email']; ?></td>
					</tr>
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