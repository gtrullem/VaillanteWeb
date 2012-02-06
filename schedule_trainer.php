<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < 1) && ($_SESSION['status_out'] < 1)) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(!empty($_GET['id']))
		$id = $_GET['id'];
	
	require_once("./CONFIG/var_config.php");
	
	if(isset($_POST['display'])) {
		$seasonid = $_POST['seasonid'];
	} else {
		if(date("n") >= "8")
			$season = date("Y")."-".(date("Y") + 1);
		else
			$season = (date("Y") - 1)."-".date("Y");

		$query = "SELECT seasonid FROM xtr_season WHERE seasonlabel = '$season'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (trainer, cours) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		$seasonid = mysql_fetch_array($result, MYSQL_NUM);
		$seasonid = $seasonid[0];
	}

	// For color
	$query = "SELECT COUNT(DISTINCT userid) FROM xtr_istrainer, xtr_course, xtr_linkCourseSeason WHERE xtr_istrainer.lcsid=xtr_linkCourseSeason.lcsid AND xtr_linkCourseSeason.courseid = xtr_course.courseid AND xtr_linkCourseSeason.seasonid=$seasonid";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (trainer, cours) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$nb_trainer = mysql_fetch_array($result);
	$nb_trainer = $nb_trainer[0];

	// AJOUTER un WHERE enable = 'Y'
	$query = "SELECT * FROM xtr_course, xtr_linkCourseSeason, xtr_istrainer WHERE xtr_istrainer.lcsid = xtr_linkCourseSeason.lcsid AND xtr_linkCourseSeason.courseid = xtr_course.courseid AND xtr_linkCourseSeason.seasonid = $seasonid ORDER BY daynumber, userid, h_begin"; // LEFT
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (trainer, cours) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	
	$table = array();
	for($i=0; $i<30; $i++)
		for($j = 0; $j < (6*$nb_trainer); $j++)
			$table[$i][$j] = 0;
	
	$vect_trainer = array();
	for($j = 0; $j < ($nb_trainer); $j++)
		$vect_trainer[$j] = 0;

	while($line = mysql_fetch_array($result))
		for($i = 0; $i < 30; $i++)
			if(($line['h_begin'] <= date("H:i", mktime(8, (30*$i), 0, 0, 0, 0))) && ($line['h_end']) >= date("H:i", mktime(8, (30*$i), 0, 0, 0, 0)))
				$table[$i][(($line['daynumber']-1)*$nb_trainer)+($line['userid']-1)] = $line['userid']; // 1

?>
<!DOCTYPE html PUBLIC "-//W3C//Dtd XHTML 1.0 Strict//EN" "http://www.w3.org/tr/xhtml1/Dtd/xhtml1-strict.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Horaires des Entraineurs :.</title>
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
				<!-- ========================= BEGIN FORM ====================== -->
				<h2><a>Horaires des Entraineurs</a> (en cours de construction)</h2>
				<br />
				<table align="center">
					<tr>
						<td>
							<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
								<fieldset>
									<legend>Statistiques de saison</legend>
									<p>
										<label>Saison</label>
										<select name="seasonid">
											<?php
												$query = "SELECT * FROM xtr_season ORDER BY seasonlabel";
												$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (season) !<br />$query<br />$resultyear<br />".mysql_error(), E_USER_ERROR);
											
												while($line = mysql_fetch_array($result)) {
													echo "<option value='".$line['seasonid']."'";
													if($line['seasonid'] == $seasonid)
														echo " selected";
													echo ">".$line['seasonlabel']."</option>";
												}
											?>
										</select>
										&nbsp;&nbsp;&nbsp;<input type="submit" name="display" value="Afficher" />
									</p>
								</fieldset>
							</form>
						</td>
					</tr>
				</table>
				<table align="center">
					<tr>
						<?php
							$query = "SELECT DISTINCT (CONCAT( lastname,  ', ', firstname )) AS name FROM xtr_istrainer, xtr_linkCourseSeason, xtr_users, xtr_person WHERE xtr_istrainer.lcsid = xtr_linkCourseSeason.lcsid AND xtr_istrainer.userid = xtr_users.userid AND xtr_users.personid = xtr_person.personid AND xtr_linkCourseSeason.seasonid = $seasonid ORDER BY name"; /* ORDER BY xtr_users.userid */
							$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (cours, user, person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
							
							$i = 0;
							while($line = mysql_fetch_array($result)) {
								$i++;
								echo "<td width='10' bgcolor='".$color[$i]."'></td><td><font size='1'>".$line['name']."</font></td><td width='20'>&nbsp;</td>";
								if(($i % 10) == 0)
									echo "</tr><tr>";
							}
						?>
					</tr>
				</table>
				<BR />
				<table align="center" width="900" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<th></th>
						<th colspan="<?php echo $nb_trainer; ?>">Lundi</th>
						<th colspan="<?php echo $nb_trainer; ?>">Mardi</th>
						<th colspan="<?php echo $nb_trainer; ?>">Mercredi</th>
						<th colspan="<?php echo $nb_trainer; ?>">Jeudi</th>
						<th colspan="<?php echo $nb_trainer; ?>">Vendredi</th>
						<th colspan="<?php echo $nb_trainer; ?>">Samedi</th>
						<th></th>
					</tr>
					<?php
						$hour = 8;
						
						// Pour les heures
						for($i=1; $i<30; $i++) {
							if($hour<10)
								$h_display = "0".$hour;
							else
								$h_display = $hour;
							
							if(($i%2) == 0) {
								$hour++;
								$h_display .= ":30";
								echo "<tr bgcolor='#E7F1F7'>";
							} else
								$h_display .= ":00";
							
							echo "<td valign='top' align='left'><font size='1'><sup>$h_display</sup></font></td>";
							
							// Pour les disciplines
							for($j = 0; $j < (6*$nb_trainer); $j++) {
								echo "<td width='10' bgcolor='".$color[$table[$i][$j]]."'>";
//								if((($j+1) % $nb_disc) == 0) {
//									"<div style=\"border: solid 2px #DDDDDD; border-left-width:2px; padding-left:0.5ex\">&nbsp;</DIV></td>";
//								} else {
									echo "&nbsp;</td>";
//								}
							}
							echo "<td align='right'><font size='1'><sup>$h_display</sup></font></td>";
							echo "<tr>";
						}
					?>
				</table>
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
</HTML>