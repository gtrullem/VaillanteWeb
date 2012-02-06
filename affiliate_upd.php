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
		header("Refresh: 0; url=./redirection.php?err=2&item=affilié&referrer=affiliate_listing.php");
		exit;
	}
	
	$personid = $_GET['id'];
	
	$query = "SELECT * FROM xtr_person WHERE personid='$personid'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>.: Détail d'un Gymnaste :.</TITLE>
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
		<div id="frame">
			<div id="content">
			<H2><a>Informations d'un Gymnaste</a></H2>
			<br/>
			<TABLE width="70%" align="center">
				<tr>
					<td>
			<table align="center" border="0" width="60%" id="table1" cellspacing="0" cellpadding="0">
				<tr>
					<td><b>Nom :</b></td>
					<TD colspan="2"><?php echo $line['lastname']; ?></td>
				</tr>
				<tr>
					<td><b>Prénom :</b></td>
					<TD colspan="2"><?php echo $line['firstname']; ?></td>
				</tr>
				<tr>
					<td>Date de naissance :</td>
					<TD colspan="2"><?php echo substr($line['birth'], 8, 2)."/".substr($line['birth'], 5, 2)."/".substr($line['birth'], 0, 4); ?></td>
				</tr>
				<tr>
					<td>Adresse :</td>
					<TD colspan="2"><?php echo $line['address']; ?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<TD colspan="2"><?php echo $line['postal']." ".$line['city']; ?></td>
				</tr>
				<tr>
					<td>Téléphone :</td>
					<TD colspan="2">
					<?php
						if($line['phone'] != "") {
							$test = "/^02[0-9]{7}$/";
							if(preg_match($test, $line['phone'])) {
								echo substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
							} else {
								echo substr($line['phone'], 0, 3)."/".substr($line['phone'], 3, 2).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
							}
						} else {
							echo "<td>&nbsp;</td>";
						}
					?>
					</td>
				</tr>
				<tr>
					<td>GSM :</td>
					<TD colspan="2">
						<?php
							if($line['gsm'] != "") {
								echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2);
							}
						?>
					</td>
				</tr>
				<tr>
					<td>Email :</td>
					<TD colspan="2"><a href="<?php echo $line['email']; ?>"><?php echo $line['email']; ?></a></td>
				</tr>
				<tr><TD colspan="3"><HR></td></tr>
				<tr>
					<TD colspan="3"><b>Cours suivis :</b></td>
				</tr>
					<?php
						$query = "SELECT * FROM xtr_isaffiliate, xtr_course, xtr_subdiscipline WHERE xtr_isaffiliate.personid = '$personid' AND xtr_isaffiliate.courseid = xtr_course.courseid AND xtr_course.subdisciplineid = xtr_subdiscipline.subdisciplineid ORDER BY season, daynumber";
						$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (affiliate, subdiscipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
												$i=0;
						while ($line = mysql_fetch_array($result)) {							$i++;							if(($i%2) == 0) {								echo "<TR bgcolor=\"#E7F1F7\">";							} else {								echo "<tr>";							}
							echo "<td>".$line['season']." : </td>";
							echo "<td>".$line['acronym']." - ".$line['day']." (".substr($line['h_begin'], 0, 5)."-".substr($line['h_end'], 0, 5).")</td>";							if($line['paid'] == "N") {								echo "<td><a href=\"isaffiliate_del.php?id=".$line[0]."\" title=\"Supprimer le cours\"><img src=\"./design/images/icons/16_delete.png\" /></a></td>";							} else {								echo "<td>&nbsp;</td>";							}							echo "</tr>";
						}
					?>
				<tr>
					<TD colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<TD colspan="3" align="right"><a href="isaffiliate_add.php?id=<?php echo $personid; ?>"><img src="./design/images/icons/16_add.png" alt="Ajouter une discipline" /></a>&nbsp;&nbsp;</td>
				</tr>
				</TABLE>
				<HR>
				<?php
					$query = " SELECT personid1 FROM xtr_relationship WHERE personid='$personid'";
					$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (relationship) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
					
					$line = mysql_fetch_array($result);
					$query1 = " SELECT * FROM xtr_person WHERE personid='".$line['personid1']."'";
					
					$line = mysql_fetch_array($result);
					$query2 = " SELECT * FROM xtr_person WHERE personid='".$line['personid1']."'";
					
					$result1 = mysql_query($query1,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person1) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
					$result2 = mysql_query($query2,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person2) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
					
					$line1 = mysql_fetch_array($result1);
					$line2 = mysql_fetch_array($result2);
				?>
				<TABLE align="center" width="100%">
					<tr>
					<TD width="20%"><b>Nom :</b></td>
					<TD width="30%"><?php echo $line1['lastname']; ?></td>
					<TD width="20%"><b>Nom :</b></td>
					<TD width="30%"><?php echo $line2['lastname']; ?></td>
				</tr>
				<tr>
					<td><b>Prénom :</b></td>
					<td><?php echo $line1['firstname']; ?></td>
					<td><b>Prénom :</b></td>
					<td><?php echo $line2['firstname']; ?></td>
				</tr>
				<tr>
					<?php
						if(($line1['profession'] != "NULL") && ($line1['profession'] != "")) {
							echo "<td>Profession :</td><td>".$line1['profession']."</td>";
						}
						
						if(($line2['profession'] != "NULL") && ($line2['profession'] != "")) {
							echo "<td>Profession :</td><td>".$line1['profession']."</td>";
						}
					?>
				</tr>
				<tr>
					<td>Adresse :</td>
					<td><?php echo $line1['address']; ?></td>
					<td>Adresse :</td>
					<td><?php echo $line2['address']; ?></td>
				</tr>
				<tr>
					<td></td>
					<td><?php echo $line1['postal']." ".$line1['city']; ?></td>
					<td></td>
					<td><?php echo $line2['postal']." ".$line2['city']; ?></td>
				</tr>
				<tr>
					<?php
						$test = "/^02[0-9]{7}$/";
						
						if(($line1['phone'] != "NULL") && ($line1['phone'] != "")) {
							echo"<td>Téléphone :</td><td>";
							
							if(preg_match($test, $line1['phone'])) {
								echo substr($line1['phone'], 0, 2)."/".substr($line1['phone'], 2, 3).".".substr($line1['phone'], 5, 2).".".substr($line1['phone'], 7, 2);
							} else {
								echo substr($line1['phone'], 0, 3)."/".substr($line1['phone'], 3, 2).".".substr($line1['phone'], 5, 2).".".substr($line1['phone'], 7, 2);
							}
							echo "</td>";
						}

						if($line2['phone'] != "") {
							echo"<td>Téléphone :</td><td>";
							
							if(preg_match($test, $line2['phone'])) {
								echo substr($line2['phone'], 0, 2)."/".substr($line2['phone'], 2, 3).".".substr($line2['phone'], 5, 2).".".substr($line2['phone'], 7, 2);
							} else {
								echo substr($line2['phone'], 0, 3)."/".substr($line2['phone'], 3, 2).".".substr($line2['phone'], 5, 2).".".substr($line2['phone'], 7, 2);
							}
						} else {
							echo "</td>";
						}
					?>
					</td>
				</tr>
				<tr>
					<td>GSM :</td>
					<td>
						<?php
							if($line1['gsm'] != "") {
								echo substr($line1['gsm'], 0, 4)."/".substr($line1['gsm'], 4, 2).".".substr($line1['gsm'], 6, 2).".".substr($line1['gsm'], 8, 2);
							}
						?>
					</td>
					<td>GSM :</td>
					<td><?php
							if($line1['gsm'] != "") {
								echo substr($line2['gsm'], 0, 4)."/".substr($line2['gsm'], 4, 2).".".substr($line2['gsm'], 6, 2).".".substr($line2['gsm'], 8, 2);
							}
						?>
					</td>
				</tr>
				<tr>
					<td>email :</td>
					<td><a href="<?php echo $line1['email']; ?>"><?php echo $line1['email']; ?></a></td>
					<td>email :</td>
					<td><a href="<?php echo $line2['email']; ?>"><?php echo $line2['email']; ?></a></td>
				</tr>
				<tr>
					<td>Profession :</td>
					<td><?php echo $line1['profession']; ?></td>
					<td>Profession :</td>
					<td><?php echo $line2['profession']; ?></td>
				</tr>
				<!-- <hr width="0.5px" size="70px"> -->
				</TABLE>
						</td>
					</tr>
				</TABLE>
			<!-- <p>Si vous souhaitez inscrire votre enfant à <u>plusieurs cours</u>, <a href="course_add.php?id=<?php echo $id; ?>">cliquez ici</a>.<br /> -->
			<p>Si vous souhaitez inscrire <u>plusieurs enfants</u> dont <u><i><b>vous</b></i> être responsables</u>, <a href="affiliate_add.php?id=<?php echo $personid; ?>">cliquez ici</a></p>
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