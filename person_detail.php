<?php
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "person_detail";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(empty($_GET['personid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=affilié&referrer=affiliate_listing.php");
		exit;
	}
	
	$personid = $_GET['personid'];
	
	// sinon, on continue...
	require_once("./library/vcf.php");

	$query = "SELECT * FROM xtr_person WHERE personid='$personid'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	
	if(isset($_POST['export'])) {
		$data = array(
            'firstname' => utf8_decode($line['firstname']),
			'surname' => utf8_decode($line['lastname']),
			'nickname' => '',
			'birthday' => $line['birth'],
			'company' => 'La Vaillante Tubize',
			'jobtitle' => utf8_decode($line['profession']),
			'workbuilding' => '',
			'workstreet' => '',
			'worktown' => '',
			'workcounty' => '',
			'workpostcode' => '',
			'workcountry' => '',
			'worktelephone' => '',
			'workemail' => '',
			'workurl' => '',
			'homebuilding' => '',
			'homestreet' => utf8_decode($line['address']),
			'hometown' => utf8_decode($line['city']),
			'homecounty' => utf8_decode($line['city']),
			'homepostcode' => $line['postal'],
			'homecountry' => 'Belgique',
			'hometelephone' => $line['phone'],
            'homeemail' => $line['email'],
			'homeurl' => '',
			'mobile' => $line['gsm'],
			'notes' => ''
		);
		
		if(!empty($line['box'])) {
			$data[5] .= " boite : ".$line['box'];
		}
				
		$vCard = new VCF($data);
		$vCard->show();
	}
	
	if($line['resp1id'] == '' && $line['resp2id'] == '')
		$msg = "Cette personne n'a pas de responsable. Vous ne pouvez donc pas lui associer un cours.";
	else {
		if($line['resp1id'] != '') {
			$query = "SELECT personid, CONCAT(lastname, ', ', firstname) as name, gsm, phone FROM xtr_person WHERE personid = ".$line['resp1id'];
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			$resp1 = mysql_fetch_array($result, MYSQL_ASSOC);
		}

		if($line['resp2id'] != '') {
			$query = "SELECT personid, CONCAT(lastname, ', ', firstname) as name, gsm, phone FROM xtr_person WHERE personid = ".$line['resp2id'];
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			$resp2 = mysql_fetch_array($result, MYSQL_ASSOC);
		}
	}

	$testPhone = "/^02[0-9]{7}$/";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Détails d'une personne :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
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
			<h2><a>Détails de <?php echo $line['lastname'].", ".$line['firstname']?></a></h2>
			<br />
			<table align="center" border="0">
				<tr>
					<td>
						<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?personid=".$personid; ?>" enctype="multipart/form-data">
							<fieldset>
								<legend>Informations</i></legend>
								<p>
									<label>Nom</label>
									<?php echo $line['lastname']; ?>
								</p>
								<p>
									<label>Prénom</label>
									<?php echo $line['firstname']; ?>
								</p>
								<?php 
									if($line['birth'] != "") {
								?>
								<p>
									<label>Date de naissance</label>
									<?php 
										echo substr($line['birth'], 8, 2)."/".substr($line['birth'], 5, 2)."/".substr($line['birth'], 0, 4);
									?>
								</p>
								<?php
									}

									if($line['birthplace'] != "") {
								?>
								<p>
									<label>Lieu de naissance</label>
									<?php echo $line['birthplace']; ?>
								</p>
								<?php
									}
								?>
								<p>
									<label>Sexe</label>
									<?php
										if($line['sexe'] == "M")	echo "Masculin";
										else	echo "Féminin";
									?>
								</p>
								<?php
									if($line['niss'] != "") {
								?>
								<p>
									<label>NISS</label>
									<?php echo $line['niss']; ?>
								</p>
								<?php
									}
								?>
								<p>
									<label>Adresse</label>
									<label>
										<?php
											echo $line['address'];
											if($line['box'] != "")
												echo " (Boite ".$line['box'].")";
											echo "<br />".$line['postal']." ".stripslashes($line['city']);
										?>
									</label>
								</p>
								<?php
									if($line['profession'] != "") {
								?>
								<p>
									<label>Profession</label>
									<?php echo $line['profession']; ?>
								</p>
								<?php
									}
									
									if($line['phone'] != "") {
								?>
								<p>
									<label>Téléphone</label>
									<?php
										if(preg_match($testPhone, $line['phone']))
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
									<label>GSM</label>
									<?php
										echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2);
									?>
								</p>
								<?php
									}

									if($line['email'] != "") {
								?>
								<p>
									<label>email</label>
									<?php echo $line['email']; ?>
								</p>
								<?php
									}

									if($line['ffgid'] != "") {
								?>
								<p>
									<label>N° de licence</label>
									<?php echo $line['ffgid']; ?>
								</p>
								<?php
									}
								?>
								<p>
									<label>Carnet d'adresse ?</label>
									<?php 
										if($line['addressbook'] == 'Y')
											echo "Oui";
										else
											echo "Non";
									?>
								</p>
								<?php
									if($line['resp1id'] != '' || $line['resp2id'] != '') {
								?>
								<p><hr /></p>
								<p><b><u>Relations :</u></b></p>
								<table width="100%" border="0">
								<?php
									if($line['resp1id'] != '') {
										echo "<tr><td>&nbsp;<a href='person_detail.php?personid=".$line['resp1id']."'>".$resp1['name']."</a>&nbsp;<font size='1'>(".$line['type1'].")</font></td><td width='95'>";
										if($resp1['phone'] != "")
											if(preg_match($testPhone, $resp1['phone']))
												echo substr($resp1['phone'], 0, 2)."/".substr($resp1['phone'], 2, 3).".".substr($resp1['phone'], 5, 2).".".substr($resp1['phone'], 7, 2);
											else
												echo substr($resp1['phone'], 0, 3)."/".substr($resp1['phone'], 3, 2).".".substr($resp1['phone'], 5, 2).".".substr($resp1['phone'], 7, 2);
										echo "</td><td width='100' align='right'>".substr($resp1['gsm'], 0, 4)."/".substr($resp1['gsm'], 4, 2).".".substr($resp1['gsm'], 6, 2).".".substr($resp1['gsm'], 8, 2)."</td></tr>";
									}

									if($line['resp2id'] != '') {
										echo "<tr><td>&nbsp;<a href='person_detail.php?personid=".$line['resp2id']."'>".$resp2['name']."</a>&nbsp;<font size='1'>(".$line['type2'].")</font></td><td width='95'>";
										if($resp2['phone'])
											if(preg_match($testPhone, $resp2['phone']))
												echo substr($resp2['phone'], 0, 2)."/".substr($resp2['phone'], 2, 3).".".substr($resp2['phone'], 5, 2).".".substr($resp2['phone'], 7, 2);
											else
												echo substr($resp2['phone'], 0, 3)."/".substr($resp2['phone'], 3, 2).".".substr($resp2['phone'], 5, 2).".".substr($resp2['phone'], 7, 2);
										echo "</td><td width='100' align='right'>".substr($resp2['gsm'], 0, 4)."/".substr($resp2['gsm'], 4, 2).".".substr($resp2['gsm'], 6, 2).".".substr($resp2['gsm'], 8, 2)."</td></tr>";
									}
								?>
								</table>
								<p><hr /><p/>
								<?php
									}
								?>
								<table width="100%" class="noprint">
									<tr>
										<td width="50%" align="left"><input type="submit" name="export" value="exporter" /></td>
										<td width="50%" align="right">
											<?php
												if($_SESSION['status_out'] >= 3)
													echo "<a href='person_update.php?id=$personid'><img src='./design/images/icons/16_Edit.png' /></a>";
											?>
										</td>
									</tr>
								</table>
							</fieldset>
						</form>
						<br />
						<form class="formulaire">
								<fieldset>									
									<?php
										// Person is gymnast ?
										if(date("n") >= "8")	$season = date("Y")."-".(date("Y") + 1);
										else	$season = (date("Y") - 1)."-".date("Y");

										$query = "SELECT DISTINCT xtr_season.seasonid, xtr_season.seasonlabel FROM xtr_isaffiliate, xtr_linkCourseSeason, xtr_season WHERE xtr_isaffiliate.personid = $personid AND xtr_isaffiliate.lcsid = xtr_linkCourseSeason.lcsid AND xtr_linkCourseSeason.seasonid = xtr_season.seasonid";
										$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (Affiliate, Link, Season) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
										if($line = mysql_fetch_array($result)) {
									?>
							
									<legend>Cours suivis en <select name="seasonid">
										<?php
											do
												echo "<option value='".$line['seasonid']."'>".$line['seasonlabel']."</option>";
											while ($line = mysql_fetch_array($result));
										?>
										</select>
									</legend>

									<p>
										Les cours seront affichés ici (A VENIR !!!).
									</p>
								<?php
									} else
										echo "<legend>Cours suivis</legend>";

									if(!empty($msg))
										echo "<p align='center' class='important'>".$msg."</p>";
									else
										echo "<a href='./isaffiliate_add.php?personid=".$personid."'>Affilier à un cours</a>";
								?>
							</fieldset>
						</form>
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