<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$function = "coursedetail";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(!empty($_GET['linkid']))	$linkid = $_GET['linkid'];
	require_once("./CLASS/dbcourse.class.php");
	$database = new DBCourse();

	$course = $database->getCourseFromLinkID($linkid);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Détail d'un cours :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
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
			
			var lcsid = document.formulaire.lcsid.value;
			// var lcsid = 1;
			// var courseid = document.formulaire.courseid.value;
			// var season = document.formulaire.season.value;

			// xmlhttp.open("GET","getcoursedetail.php?courseid="+courseid+"&seasonid="+season,true);
			xmlhttp.open("GET","getcoursedetail.php?lcsid="+lcsid,true);
			xmlhttp.send();
		}
	</script>
	<noscript>
		<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>
	</noscript>
</head>
	
<body onLoad="javascript:showList();">
<div id="body">

<?php	require_once("./header.php");	?>
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
				<h2><a>Détails d'un cours</a></h2>
				<br />
				<table align="center">
					<tr>
						<td>
							<form class="formulaire" name="formulaire" method="post" action="attendance.php">
								<fieldset>
									<legend>Informations</legend>
									<p>
										<label>Discipline</label>
										<?php echo $course->getSubDiscipline()->getDiscipline()->getTitle()." <font size='1'>(".$course->getSubDiscipline()->getDiscipline()->getAcronym().")</font>"; ?>
									</p>
									<p>
										<label>Sous-Discipline</label>
										<?php echo $course->getSubDiscipline()->getTitle()." <font size='1'>(".$course->getSubDiscipline()->getAcronym().")</font>"; ?>
									</p>
									<p>
										<label>Saison</label>
										<?php echo $course->getSeasonLabel(); ?>
									</p>
									<p>
										<label>Jour</label>
										<?php echo $course->getDay().", de ".substr($course->getBeginHour(), 0, 5); ?> à <?php echo substr($course->getEndHour(), 0, 5); ?>
									</p>
									<p align="right">
										<a href="course_update.php?courseid=<?php echo $course->getID(); ?>" title="Détails du cours"><img src="./design/images/icons/16_Edit.png" height="10" width="10" /></a>
									</p>
									<p><hr></p>
									<?php
										$connect = mysql_connect('mysql5-6.start','lavailla_01','lavailla01') or die("Impossible de se connecter : " . mysql_error());
										$selected_db = mysql_select_db('lavailla_01', $connect) or die('Could not select database.');
										mysql_query("SET NAMES 'utf8'");
										setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8', 'fra');

										$query = "SELECT xtr_users.userid, CONCAT(lastname, ', ', firstname) AS name FROM xtr_istrainer, xtr_users, xtr_person WHERE  xtr_istrainer.userid = xtr_users.userid AND xtr_users.personid = xtr_person.personid AND xtr_istrainer.lcsid = $linkid ORDER BY name";
										// echo $query."<br />";
										$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (trainer, user, person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
										$line = mysql_fetch_array($result)
									?>
									
									<p>
										<table width="100%">
											<tr>
												<td height="15" width="37%">Entraineur(s)</td>
												<td><?php echo "<a href='trainer_del.php?userid=".$line['userid']."&lcsid=".$linkid."' title=\"Supprimer l'entraineur\" class='noprint'><img src='./design/images/icons/16_delete.png' height='10' width='10' /></a></td><td><a href='user_detail.php?uid=".$line['userid']."'>".$line['name']."</a></td></tr>"; ?></td>
											</tr>
											<?php
												while($line = mysql_fetch_array($result))
													echo "<tr><td>&nbsp;</td><td><a href='trainer_del.php?userid=".$line['userid']."&lcsid=".$linkid."' title=\"Supprimer l'entraineur\" class='noprint'><img src='./design/images/icons/16_delete.png' height='10' width='10' /></a></td><td><a href='user_detail.php?uid=".$line['userid']."'>".$line['name']."</a></td></tr>";
											?>
										</table>
									</p>
									<p align="right" class="noprint">
										<a href="trainer_add.php?linkid=<?php echo $linkid; ?>"><img src="./design/images/icons/16_user_add.png" alt="Ajouter un Entrainer" /></a>
									</p>
									<p><hr></p>
									<p>
										<?php
											$query = "SELECT xtr_isaffiliate.personid, CONCAT(lastname, ', ', firstname) AS name, phone, gsm FROM xtr_isaffiliate, xtr_person WHERE xtr_isaffiliate.personid = xtr_person.personid AND xtr_isaffiliate.lcsid = $linkid ORDER BY name";
											// echo $query."<br />";
											$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (trainer, user, person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
											$line = mysql_fetch_array($result)
										?>
										<table width="100%">
											<tr>
												<td height="15" width="37%">Elève(s)</td>
												<td width="20">
													<?php
														echo "<a href='isaffiliate_del.php?personid=".$line['personid']."&linkid=$linkid' title=\"Supprimer l'élève\" class='noprint'><img src='./design/images/icons/16_delete.png' height='10' width='10' /></a></td><td><a href='person_detail.php?personid=".$line['personid']."'>".$line['name']."</a></td><td>";
														if($line['gsm'] != "")
															echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2)."</td>";
														else
															if($line['phone'] != "") {
																if($line['phone'][1] == 2)
																	echo substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3);
																else
																	echo substr($line['phone'], 0, 3)."/".substr($line['phone'], 3, 2);
																echo ".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2)."</td>";
															} else
																echo "<td>&nbsp;</td>";
													?>
												</td>
											</tr>
											<?php
												while($line = mysql_fetch_array($result)) {
													echo "<tr><td>&nbsp;</td><td><a href='isaffiliate_del.php?personid=".$line['personid']."&linkid=$linkid' title=\"Supprimer l'élève\" class='noprint'><img src='./design/images/icons/16_delete.png' height='10' width='10' /></a></td><td><a href='person_detail.php?personid=".$line['personid']."'>".$line['name']."</a></td><td>";
														if($line['gsm'] != "")
															echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2)."</td>";
														elseif($line['phone'] != "") {
															if($line['phone'][1] == 2)
																echo substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3);
															else
																echo substr($line['phone'], 0, 3)."/".substr($line['phone'], 3, 2);
															echo ".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2)."</td></tr>";
														} else
															echo "<td>&nbsp;</td>";
												}
											?>
										</table>
									</p>
									<p align="right" class="noprint">
										<a href="isaffiliate_add.php?linkid=<?php echo $linkid; ?>"><img src="./design/images/icons/16_gymnast_add.png" alt="Ajouter un Gymnaste" /></a>
									</p>
									<!-- END AJOUT -->
									<p align="center">
										<input type="hidden" name="linkid" value="<?php echo $linkid; ?>" />
										<input type="submit" name="attendance" value="Liste de présences" />
										<!-- <a href="attendance.php?linkid=<?php echo $linkid; ?>">Liste de présence</a> -->
									</p>
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