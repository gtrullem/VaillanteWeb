<?php
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "preins_detail";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(empty($_GET['id'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=affilié&referrer=preins_listing.php");
		exit;
	}
	
	$personid = $_GET['id'];

	if(!empty($_POST['add'])) {
		// echo "<br /><br />";
		$query = "SELECT * FROM xtr_preins WHERE personid = $personid";
		// echo $query."<br />";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (AFFILIATES/PERSONS) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		$line = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$resp1id = null;
		$resp2id = null;

		if(($line['resp1id'] != '') && ($line['resp1id'] != 0 )) {
			$query = "SELECT * FROM xtr_preins WHERE personid = ".$line['resp1id'];
			// echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (AFFILIATES/PERSONS - RESP1) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			$row = mysql_fetch_array($result, MYSQL_ASSOC);

			// var_dump($row);

			$query = "INSERT INTO xtr_person (lastname, firstname, sexe, address, box, postal, city, profession, phone, gsm, email) VALUES ('".mysql_real_escape_string($row['lastname'])."', '".mysql_real_escape_string($row['firstname'])."', '".$row['sexe']."', '".mysql_real_escape_string($row['address'])."', '".$row['box']."', '".$row['postal']."', '".mysql_real_escape_string($row['city'])."', '".mysql_real_escape_string($row['profession'])."', '".$row['phone']."', '".$row['gsm']."', '".$row['email']."')";
			// echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (AFFILIATES/PERSONS - RESP1) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);

			$query = "SELECT LAST_INSERT_ID()";
			// echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GET LAST ID !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			$resp1id = mysql_fetch_array($result, MYSQL_NUM);
			$resp1id = $resp1id[0];

			$query = "UPDATE xtr_preins SET transfered = '1' WHERE personid = ".$line['resp1id'];
			// echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (xtr_preins) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		}

		if(($line['resp2id'] != '') && ($line['resp2id'] != 0 )) {
			$query = "SELECT * FROM xtr_preins WHERE personid = ".$line['resp2id'];
			// echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (AFFILIATES/PERSONS - RESP2) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			$row = mysql_fetch_array($result);

			$query = "INSERT INTO xtr_person (lastname, firstname, sexe, address, box, postal, city, profession, phone, gsm, email) VALUES ('".mysql_real_escape_string($row['lastname'])."', '".mysql_real_escape_string($row['firstname'])."', '".$row['sexe']."', '".mysql_real_escape_string($row['address'])."', '".$row['box']."', '".$row['postal']."', '".mysql_real_escape_string($row['city'])."', '".mysql_real_escape_string($row['profession'])."', '".$row['phone']."', '".$row['gsm']."', '".$row['email']."')";
			// echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (AFFILIATES/PERSONS - RESP2) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);

			$query = "SELECT LAST_INSERT_ID()";
			// echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GET LAST ID !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			$resp2id = mysql_fetch_array($result, MYSQL_NUM);
			$resp2id = $resp2id[0];

			$query = "UPDATE xtr_preins SET transfered = '1' WHERE personid = ".$line['resp2id'];
			// echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (xtr_preins) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		}

		$query = "INSERT INTO xtr_person (lastname, firstname, birth, birthplace, sexe, niss, address, box, postal, city, phone, gsm, email, resp1id, type1, resp2id, type2) VALUES ( '".mysql_real_escape_string($line['lastname'])."', '".mysql_real_escape_string($line['firstname'])."', '".$line['birth']."', '".mysql_real_escape_string($line['birthplace'])."', '".$line['sexe']."', '".$line['niss']."', '".mysql_real_escape_string($line['address'])."', '".$line['box']."', '".$line['postal']."', '".mysql_real_escape_string($line['city'])."', '".$line['phone']."', '".$line['gsm']."', '".$line['email']."', '".$resp1id."', '".$line['type1']."', '";
		if($resp2id != '' && $resp2id != 0)
			$query .= $resp2id;
		else 
			$query .= "null";

		$query .= "', '".$line['type2']."')";
		// echo $query."<br />";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (AFFILIATES/PERSONS - GYM) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);

		$query = "SELECT LAST_INSERT_ID()";
		// echo $query."<br />";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GET LAST ID !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		$id = mysql_fetch_array($result, MYSQL_NUM);
		$id = $id[0];

		$query = "UPDATE xtr_preins SET transfered = '1' WHERE personid = $personid";
		// echo $query."<br />";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);

		header("Location: ./person_detail.php?personid=".$id);
		exit;

	}
	
	$query = "SELECT * FROM xtr_preins WHERE personid = $personid";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (AFFILIATES/PERSONS - PREINS) !<br />$query<br />$result<br /><br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	$gymid = $personid;
	
	$headers ="From: \"La Vaillante - Extranet\"<extranet@lavaillantetubize.be>\n"; 
	$headers .="Reply-To: extranet@lavaillantetubize.be"."\n"; 
	$headers .="Content-type: text/html; charset=iso-8859-1"."\n";
	$headers .="Content-Transfer-Encoding: 8bit"; 
	$subject = "Extranet : Confirmation d'inscription";

	if(!empty($_POST['conf1'])) {
		$respid = $line['resp1id'];
		
		$query = "SELECT lastname, email FROM xtr_preins WHERE personid = $respid";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (AFFILIATES/PERSONS) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		$savelastname = mysql_fetch_array($result, MYSQL_NUM);
		$email = $savelastname[1];
		$savelastname = $savelastname[0];
		
		$message = utf8_decode(nl2br(stripslashes("Bonjour,<br /><br />Ceci est un mail de confirmation d'inscription d'un gymnaste au club La Vaillante de Tubize. Veuillez cliquer sur le lien qui suit afin de confirmer l'inscription de l'élève : <a href=\"http://www.lavaillantetubize.be/inscription_validate.php?id=$respid&idg=$gymid&ln=$savelastname\">confirmation</a>.<br /><br />Si le lien ne fonctionne pas, veuillez envoyer un mail à l'adresse suivante : <a href=\"mailto:extranet@lavaillantetubize.be\">extranet@lavaillantetubize.be</a><br />Si vous recevez cet email par erreur, veuillez l'ignorer.<br /><br />Bonne journée,<br />Bien à vous.<br /><br />La Vaillante Tubize - Extranet")));
		mail($email, $subject, $message, $headers);
		$msg1 = "<br />Email de confirmation envoyé au premier responsable.";
		
	} elseif (!empty($_POST['conf2'])) {
		$respid = $line['resp2id'];
		
		$query = "SELECT lastname, email FROM xtr_preins WHERE personid = $respid";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (AFFILIATES/PERSONS) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		$savelastname = mysql_fetch_array($result, MYSQL_NUM);
		$email = $savelastname[1];
		$savelastname = $savelastname[0];
		
		$message = utf8_decode(nl2br(stripslashes("Bonjour,<br /><br />Ceci est un mail de confirmation d'inscription d'un gymnaste au club La Vaillante de Tubize. Veuillez cliquer sur le lien qui suit afin de confirmer l'inscription de l'élève : <a href=\"http://www.lavaillantetubize.be/inscription_validate.php?id=$respid&idg=$gymid&ln=$savelastname\">confirmation</a>.<br /><br />Si le lien ne fonctionne pas, veuillez envoyer un mail à l'adresse suivante : <a href=\"mailto:extranet@lavaillantetubize.be\">extranet@lavaillantetubize.be</a><br />Si vous recevez cet email par erreur, veuillez l'ignorer.<br /><br />Bonne journée,<br />Bien à vous.<br /><br />La Vaillante Tubize - Extranet")));
		mail($email, $subject, $message, $headers);
		$msg2 = "<br />Email de confirmation envoyé au second responsable.";
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>.: Détail d'une Pré-Inscription :.</TITLE>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
</head>

<body>
<div id="body">

<?php
	require_once("./header.php");
?>
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
			<H2><a>Informations d'une Pré-Inscription</a></H2>
			<table align="center">
				<tr>
					<td>
					<form class="formulaire" name="formulaire">
						<fieldset>
							<legend><i><?php echo $line['lastname'].", ".$line['firstname']; ?></i></legend>
							<?php 
								if(!empty($err))
									echo "<p align='center' class='important'>".$err."</p>";
							?>
							<p>
								<label>Nom :</label>
								<?php 
									echo $line['lastname'];
									$savelastname = mysql_real_escape_string(trim($line['lastname']));
								?>
							</p>
							<p>
								<label>Prénom :</label>
								<?php 
									echo $line['firstname'];
									$savefirstname = mysql_real_escape_string(trim($line['firstname']));
								?>
							</p>
							<p>
								<label>Sexe :</label>
								<?php
									echo $line['sexe'];
								?>
							</p>
							<p>
								<label>Lieu de naissance :</label>
								<?php
									echo $line['birthplace'];
								?>
							</p>
							<p>
								<label>Date de naissance :</label>
								<?php
									echo substr($line['birth'], 8, 2)."/".substr($line['birth'], 5, 2)."/".substr($line['birth'], 0, 4);
									$savebirth = $line['birth'];
								?>
							</p>
							<p>
								<label>NISS :</label>
								<?php
									echo $line['niss'];
								?>
							</p>
							<p>
								<label>Adresse :</label>
								<?php echo $line['address']; ?>
							</p>
							<p>
								<label>&nbsp;</label>
								<?php echo $line['postal']." ".$line['city']; ?>
							</p>
							<?php
								if($line['phone'] != "") {
							?>
							<p>
								<label>Téléphone :</label>
								<?php
									$test = "/^02[0-9]{7}$/";
									if(preg_match($test, $line['phone']))
										echo substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
									else
										echo substr($line['phone'], 0, 3)."/".substr($line['phone'], 3, 2).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
								?>
							</p>
							<?php
								}
																	
								if($line['gsm'] != "") {
							?>
							<p>
								<label>GSM :</label>
								<?php
										echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2);
								?>
							</p>
							<?php
								}
								
								if($line['email'] != "") {
							?>
							<p>
								<label>Email :</label>
								<a href="mailto:<?php echo $line['email']; ?>"><?php echo $line['email']; ?></a>
							</p>
							<?php
								}
							?>
							<hr />
							<p>
								<label><u>Discipline(s) désirée(s) :</u></label>
								<?php 
									echo $line['disclist'];
									$validate = $line['validate'];
								?>
							</p>
						</fieldset>
					</form>
				</td>
			</tr>
		</table>
		
		<?php
			
			$query = "SELECT * FROM xtr_preins WHERE personid = ".$line['resp1id'];
			$result1 = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (AFFILIATES/PERSONS !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			
			$query = "SELECT * FROM xtr_preins WHERE personid = ".$line['resp2id'];
			$result2 = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (AFFILIATES/PERSONS !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			
			$line = mysql_fetch_array($result1);
		?>
		<table align="center">
			<tr>
				<td valign="top">
					<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$personid; ?>" enctype="multipart/form-data" >

						<fieldset>
							<legend><i><?php echo $line['lastname'].", ".$line['firstname']; ?></i></legend>
							<p>
								<label>Nom :</label>
								<?php 
									echo $line['lastname'];
									$resp1lastname = $line['lastname'];
								?>
							</p>
							<p>
								<label>Prénom :</label>
								<?php
									echo $line['firstname'];
									$resp1firstname = $line['firstname'];
								?>
							</p>
							<p>
								<label>Adresse :</label>
								<?php echo $line['address']; ?>
							</p>
							<p>
								<label>&nbsp;</label>
								<?php echo $line['postal']." ".$line['city']; ?>
							</p>
							<?php
								if($line['phone'] != "") {
							?>
							<p>
								<label>Téléphone :</label>
								<?php
									$test = "/^02[0-9]{7}$/";
									if(preg_match($test, $line['phone']))
										echo substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
									else
										echo substr($line['phone'], 0, 3)."/".substr($line['phone'], 3, 2).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
								?>
							</p>
							<?php
								}
																	
								if($line['gsm'] != "") {
							?>
							<p>
								<label>GSM :</label>
								<?php
										echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2);
								?>
							</p>
							<?php
								}
								
								if($line['email'] != "") {
							?>
							<p>
								<label>Email :</label>
								<a href="mailto:<?php echo $line['email']; ?>"><?php echo $line['email']; ?></a>&nbsp;&nbsp;&nbsp;<input type="submit" name="conf1" value="email confirm" />
								<?php
									if(!empty($msg1))
										echo $msg1;
								?>
							</p>
							<?php
								}
								
								if($line['profession'] != "") {
							?>
							<p>
								<label>Profession :</label>
								<?php echo $line['profession']; ?>
							</p>
							<?php
								}
							?>
							</fildset>
						</form>
					</td>
					<?php
						if($line = mysql_fetch_array($result2)) {
					?>
					<td valign="top">
						<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$personid; ?>" enctype="multipart/form-data" >

							<fieldset>
								<legend><i><?php echo $line['lastname'].", ".$line['firstname']; ?></i></legend>
								<p>
									<label>Nom :</label>
									<?php
										echo $line['lastname'];
										$resp2lastname = $line['lastname'];
									?>
								</p>
								<p>
									<label>Prénom :</label>
									<?php
										echo $line['firstname'];
										$resp2firstname = $line['firstname'];
									?>
								</p>
								<p>
									<label>Adresse :</label>
									<?php echo $line['address']; ?>
								</p>
								<p>
									<label>&nbsp;</label>
									<?php echo $line['postal']." ".$line['city']; ?>
								</p>
								<?php
									if($line['phone'] != "") {
								?>
								<p>
									<label>Téléphone :</label>
									<?php
										$test = "/^02[0-9]{7}$/";
										if(preg_match($test, $line['phone']))
											echo substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
										else
											echo substr($line['phone'], 0, 3)."/".substr($line['phone'], 3, 2).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
									?>
								</p>
								<?php
									}
																		
									if($line['gsm'] != "") {
								?>
								<p>
									<label>GSM :</label>
									<?php
											echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2);
									?>
								</p>
								<?php
									}
									
									if($line['email'] != "") {
								?>
								<p>
									<label>Email :</label>
									<a href="mailto:<?php echo $line['email']; ?>"><?php echo $line['email']; ?></a>&nbsp;&nbsp;&nbsp;<input type="submit" name="conf2" value="email confirm" />
									<?php
										if(!empty($msg2))
											echo $msg2;
									?>
								</p>
								<?php
								}
								
								if($line['profession'] != "") {
							?>
							<p>
								<label>Profession :</label>
								<?php echo $line['profession']; ?>
							</p>
							<?php
								}
							?>
							</fildset>
						</form>
					</td>
					<?php
						}
					?>
				</tr>
				<tr>
					<td colspan="2" align="center">

						<?php
							$query = "SELECT COUNT(personid) FROM xtr_person WHERE lastname LIKE '$savelastname' AND firstname LIKE '$savefirstname' AND birth = '".$savebirth."'";
							// echo $query;
							$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (Person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
							$count = mysql_fetch_array($result, MYSQL_NUM);

							if($count[0] == 0) {
						?>
							<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$personid; ?>" enctype="multipart/form-data" >
								<fieldset>
									<legend>Nouveau Gymnaste ?</legend>
									<p align="justify">Nous pensons que c'est un nouveau gymnaste. Cliquez sur Ajouter si c'est bien un <b><u>NOUVEAU GYMNASTE</u></b> que vous souhaitez l'ajouter.</p>
									<?php
										if($validate == 'N')
											echo "<p class='important' align='center'><b><u>ATTENTION :</u></b> cette inscription n'a pas encore été <b>validée</b> !!!</p>";
									?>
									<p align="center"><input type="submit" name="add" value="Ajouter" /></p>
								</fieldset>
							</form>

						<?php
							}
						?>
						</td>
					</tr>
				</table>
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