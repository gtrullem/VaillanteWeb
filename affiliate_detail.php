<?php
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_detail";
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
  
	if(!empty($_POST['submit'])) {
		if(!empty($_POST['paid']))
			foreach($_POST['paid'] as $affiliation) {
				$query = "UPDATE xtr_isaffiliate SET paid = 'Y' WHERE isaffiliateid = $affiliation";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (AFFILIATES/PERSONS) <br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			}
		else
			$err = "Veuillez sélectionner les cours qui ont été payés.";
	}
	
	$query = "SELECT * FROM xtr_person WHERE personid = $personid";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (AFFILIATES/PERSONS !<br />query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result, MYSQL_ASSOC);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Détail d'un Gymnaste :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<script>
		function showList()
		{
			if (window.XMLHttpRequest) {		// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} else {							// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
		
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
					document.getElementById("result").innerHTML=xmlhttp.responseText;
			}

			var personid = document.formulaire.personid.value;
			var seasonid = document.formulaire.seasonid.value;

			xmlhttp.open("GET","getaffiliatecourse.php?personid="+personid+"&seasonid="+seasonid,true);
			xmlhttp.send();
		}
	</script>
	<noscript>
		<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>
	</noscript>
</head>

<body onLoad="javascript:showList();">
<div id="body">

<?php
	require_once("./header.php");
?>
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
			<h2><a>Détails de <?php echo $line['lastname'].", ".$line['firstname']?></a></h2>
			<table align="center">
				<tr>
					<td>
					<form name="formulaire" id="formulaire" class="formulaire">
						<fieldset>
							<legend><i><?php echo $line['lastname'].", ".$line['firstname']; ?></i></legend>
							<?php 
								if(!empty($err))
									echo "<p align='center' class='important'>".$err."</p>";
							?>
							<input type="hidden" name="personid" id="personid" value="<?php echo $personid ?>" />
							<p>
								<label>Nom :</label>
								<?php echo $line['lastname']; ?>
							</p>
							<p>
								<label>Prénom :</label>
								<?php echo $line['firstname']; ?>
							</p>
							<p>
								<label>Sexe :</label>
								<?php echo $line['sexe']; ?>
							</p>
							<p>
								<label>Date de naissance :</label>
								<?php echo substr($line['birth'], 8, 2)."/".substr($line['birth'], 5, 2)."/".substr($line['birth'], 0, 4); ?>
							</p>
							<p>
								<label>Lieux de naissance :</label>
								<?php echo $line['birthplace']; ?>
							</p>
							<p>
								<label>NISS :</label>
								<?php echo $line['niss']; ?>
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
								<a href="<?php echo $line['email']; ?>"><?php echo $line['email']; ?></a>
							</p>
							<?php
								}
							?>
							<p>
								<label>FFGID :</label>
								<?php echo $line['ffgid']; ?>
							</p>
							<p align="right">
								<a href="person_update.php?personid=<?php echo $personid; ?>" title="Modifier le cours"><img src="./design/images/icons/16_Edit.png" height="10" width="10" /></a>
							</p>
							<hr />
							<p>
								<label><u>Cours suivis :</u></label>
								<select name="seasonid" onChange="javascript:showList()">
									<?php
										$query = "SELECT DISTINCT xtr_season.seasonid, xtr_season.seasonlabel FROM xtr_season, xtr_linkCourseSeason, xtr_isaffiliate WHERE xtr_isaffiliate.lcsid = xtr_linkCourseSeason.lcsid AND xtr_linkCourseSeason.seasonid = xtr_season.seasonid AND xtr_isaffiliate.personid = $personid";
										$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (affiliate, link, cours, subdiscipline) !<br />query<br />$result<br />".mysql_error(), E_USER_ERROR);

										while($row = mysql_fetch_array($result))
											echo "<option value='".$row['seasonid']."'>".$row['seasonlabel']."</option>"
									?>
								</select>
							</p>
							<p>
								<div id="result"></div>
							</p>
							<p align="right">
								<label>&nbsp;</label>
								<a href="isaffiliate_add.php?id=<?php echo $personid; ?>"><img src="./design/images/icons/16_add.png" alt="Ajouter une discipline" /></a>
								<?php
								   if($OK)
									 echo "<input type='submit' name='submit' value='Cours payés !' />";
								?></td>
							</p>
							<!-- A ENLEVER PLUS TARD -->
							<!--
							<hr />
							<?php
								// $query = "SELECT personid, CONCAT(lastname, ', ', firstname) AS name, birth FROM xtr_preins WHERE lastname LIKE '".$line['lastname']."'";	//" AND firstname LIKE '".$line['firstname']."' AND birth = ".$line['birth'];
								// $result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (preins) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

								// if($row = mysql_fetch_array($result)) {
							?>
									<p>
										<label>Mettre à jour :</label>
										<select name="person">
											<option value="default"></option>
											<?php
												do {
													echo "<option value=\"".$row['personid']."\">".$row['name']." (".$row['birth'].")</option>";
												} while($row = mysql_fetch_array($result))
											?>
										</select>
									</p>
									<p align="center">
										<input type="submit" name="update" value="Mettre à jour" />
									</p>
							<?php
								// }
							?>
							<!-- -- -->
						</fieldset>
					</form>
				</td>
			</tr>
		</table>
	
		<table align="center">
			<tr>
				<?php
					if($line['resp1id'] != '') {
						$query1 = "SELECT * FROM xtr_person WHERE personid = ".$line['resp1id'];
						$result1 = mysql_query($query1,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person1) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
						$line1 = mysql_fetch_array($result1);
				?>
				<td valign="top">
					<form class="formulaire" name="formulaire1">
						<fieldset>
							<legend><i><?php echo $line1['lastname'].", ".$line1['firstname']; ?></i></legend>
							<p>
								<label>Nom :</label>
								<?php echo $line1['lastname']; ?>
							</p>
							<p>
								<label>Prénom :</label>
								<?php echo $line1['firstname']; ?>
							</p>
							<p>
								<label>Adresse :</label>
								<?php echo $line1['address']; ?>
							</p>
							<p>
								<label>&nbsp;</label>
								<?php echo $line1['postal']." ".$line1['city']; ?>
							</p>
							<?php
								if($line1['phone'] != "") {
							?>
							<p>
								<label>Téléphone :</label>
								<?php
									$test = "/^02[0-9]{7}$/";
									if(preg_match($test, $line1['phone']))
										echo substr($line1['phone'], 0, 2)."/".substr($line1['phone'], 2, 3).".".substr($line1['phone'], 5, 2).".".substr($line1['phone'], 7, 2);
									else
										echo substr($line1['phone'], 0, 3)."/".substr($line1['phone'], 3, 2).".".substr($line1['phone'], 5, 2).".".substr($line1['phone'], 7, 2);
								?>
							</p>
							<?php
								}
																	
								if($line1['gsm'] != "") {
							?>
							<p>
								<label>GSM :</label>
								<?php
									echo substr($line1['gsm'], 0, 4)."/".substr($line1['gsm'], 4, 2).".".substr($line1['gsm'], 6, 2).".".substr($line1['gsm'], 8, 2);
								?>
							</p>
							<?php
								}
								
								if($line1['email'] != "") {
							?>
							<p>
								<label>Email :</label>
								<a href="<?php echo $line1['email']; ?>"><?php echo $line1['email']; ?></a>
							</p>
							<?php
								}
								
								if($line1['profession'] != "") {
							?>
							<p>
								<label>Profession :</label>
								<?php echo $line1['profession']; ?>
							</p>
							<?php
								}
							?>
							<p align="right">
								<a href="person_update.php?personid=<?php echo $line1['personid']; ?>" title="Modifier le cours"><img src="./design/images/icons/16_Edit.png" height="10" width="10" /></a>
							</p>
							</fildset>
						</form>
					</td>
					<?php
						}

						if($line['resp2id'] != '') {
							$query2 = "SELECT * FROM xtr_person WHERE personid = ".$line['resp2id'];
							$result2 = mysql_query($query2,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person2) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
							$line2 = mysql_fetch_array($result2);
					?>
					<td valign="top">
						<form class="formulaire" name="formulaire2">
							<fieldset>
								<legend><i><?php echo $line2['lastname'].", ".$line2['firstname']; ?></i></legend>
								<p>
									<label>Nom :</label>
									<?php echo $line2['lastname']; ?>
								</p>
								<p>
									<label>Prénom :</label>
									<?php echo $line2['firstname']; ?>
								</p>
								<p>
									<label>Adresse :</label>
									<?php echo $line2['address']; ?>
								</p>
								<p>
									<label>&nbsp;</label>
									<?php echo $line2['postal']." ".$line2['city']; ?>
								</p>
								<?php
									if($line2['phone'] != "") {
								?>
								<p>
									<label>Téléphone :</label>
									<?php
										$test = "/^02[0-9]{7}$/";
										if(preg_match($test, $line2['phone'])) {
											echo substr($line2['phone'], 0, 2)."/".substr($line2['phone'], 2, 3).".".substr($line2['phone'], 5, 2).".".substr($line2['phone'], 7, 2);
										} else {
											echo substr($line2['phone'], 0, 3)."/".substr($line2['phone'], 3, 2).".".substr($line2['phone'], 5, 2).".".substr($line2['phone'], 7, 2);
										}
									?>
								</p>
								<?php
									}
																		
									if($line2['gsm'] != "") {
								?>
								<p>
									<label>GSM :</label>
									<?php
											echo substr($line2['gsm'], 0, 4)."/".substr($line2['gsm'], 4, 2).".".substr($line2['gsm'], 6, 2).".".substr($line2['gsm'], 8, 2);
									?>
								</p>
								<?php
									}
									
									if($line2['email'] != "") {
								?>
								<p>
									<label>Email :</label>
									<a href="<?php echo $line2['email']; ?>"><?php echo $line2['email']; ?></a>
								</p>
								<?php
								}
								
								if($line2['profession'] != "") {
							?>
							<p>
								<label>Profession :</label>
								<?php echo $line2['profession']; ?>
							</p>
							<?php
								}
							?>
							<p align="right">
								<a href="person_update.php?personid=<?php echo $line2['personid']; ?>" title="Modifier le cours"><img src="./design/images/icons/16_Edit.png" height="10" width="10" /></a>
							</p>
							</fildset>
						</form>
					</td>
					<?php
						}
					?>
				</tr>
			</table>
			<!-- <p>Si vous souhaitez inscrire <u>plusieurs enfants</u> dont <u><i><b>vous</b></i> être responsables</u>, <a href="affiliate_add.php?id=<?php echo $personid; ?>">cliquez ici</a></p> -->
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