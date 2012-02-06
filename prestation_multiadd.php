<?php

	// Script à revoir COMPLETEMENT !!!!!!

	session_start();

	$pagename = "prestation_add"; // add & multiadd : same feature
	require_once("./CONFIG/config.php");
	require_once("./CONFIG/var_config.php");

	$userid = $_SESSION['uid'];

	// GET SEASONID
	if(date("n") >= 8)	$season = date("Y")."-".(date("Y") + 1); // 9
	else	$season = (date("Y") - 1)."-".date("Y");
	$query = " SELECT seasonid FROM xtr_season WHERE seasonlabel = '$season'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (season) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$seasonid = mysql_fetch_array($result, MYSQL_NUM);
	$seasonid = $seasonid[0];

	if(isset($_POST['display'])) {
		$mois = $_POST['mois'];
		$annee = $_POST['annee'];
		//** AJOUTER LE CHECK DE LA SAISON PAR RAPPORT AU MOIS CHOISI **//
	} else {
		$mois = date('m');
		$annee = date('Y');
	}

	$time = $annee."-".$mois;
	
	// SELECTION DES JOURS FERIERS
	$query = "SELECT * FROM xtr_holiday WHERE holidate_begin REGEXP('^$time') OR holidate_end REGEXP('^$time') ORDER BY holidate_end";
	$result_holiday = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (holiday) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$line_holiday = mysql_fetch_array($result_holiday, MYSQL_ASSOC);

	$nbj=date("t",mktime(0,0,0,$mois,1,$annee));
	$beginday = date("N", mktime(0, 0, 0, $mois , 1, $annee));
	
	if(isset($_POST['submit'])) {
		$course = $_POST['course'];
		$fullQuery = "INSERT INTO xtr_prestation (date, h_from, h_to, nbhour, userid) VALUES ";

		for($i = 0; $i < sizeof($course); $i++) {
			// var_dump($course[$i]);
			$course_info = explode("&", $course[$i]);
			$query = "SELECT h_begin, h_end FROM xtr_linkCourseSeason WHERE lcsid = ".$course_info[1];
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (link)!<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			$line = mysql_fetch_array($result, MYSQL_ASSOC);
			
			$nbh = intval(substr($line['h_end'], 0, 2)-substr($line['h_begin'], 0, 2));
			if(intval(substr($line['h_end'], 3, 2)-substr($line['h_begin'], 3, 2)) < 0)
				$nbh -= 0.5;
			elseif (intval(substr($line['h_end'], 3, 2)-substr($line['h_begin'], 3, 2)) > 0)
				$nbh += 0.5;
			
			$fullQuery .= "('".$course_info[0]."', '".$line['h_begin']."', '".$line['h_end']."', '".$nbh."' , '$userid'), ";
		}
		$query = substr($fullQuery, 0, -2);
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (prestation) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		
		header("Location: ./prestation_listing.php");
		exit;
	}
	
	// VALIDITY CHECKING
	$query = "SELECT DATE(lastfinished) FROM xtr_users WHERE userid = $userid";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (users) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$lastfinished = $line[0];

	// on propose les prestations si : 
	//	- on est dans une annee supérieure à la dernière sauvée OU
	//	- on est dans la même année ET (mais) dans une mois supérieur ou égal au dernier enregistré.
	$print = ($annee > substr($lastfinished, 0, 4) || ($mois >= substr($lastfinished, 5, 2)) && ($annee == substr($lastfinished, 0, 4)));

	if($print) {
		$query = "SELECT DISTINCT lcs.lcsid, lcs.daynumber, lcs.h_begin, lcs.h_end FROM xtr_course AS C, xtr_linkCourseSeason AS lcs, xtr_istrainer AS IT, xtr_isaffiliate AS IA WHERE C.active = 'Y' AND IT.userid = $userid AND IT.lcsid = lcs.lcsid AND IA.lcsid = lcs.lcsid AND lcs.seasonid = $seasonid ORDER BY lcs.daynumber";

		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (cours, trainer, affiliate, link) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$i = 0;
		$save = 0;
		$prevision = array();

		while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if($save != $line['daynumber'])
				$i = 0;
			$prevision[$line['daynumber']][$i][0] = $line['lcsid'];
			$prevision[$line['daynumber']][$i][1] = substr($line['h_begin'], 0, 5)."-".substr($line['h_end'], 0, 5);
			$save = $line['daynumber'];
			$i++;
		}
		
		$query = " SELECT h_from, h_to, date FROM xtr_prestation WHERE userid = $userid AND date REGEXP('^$time') GROUP BY date ORDER BY COUNT(prestationid) DESC ";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (prestation) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		$line = mysql_fetch_array($result);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>.: La Vaillante - Prestations Multiples :.</TITLE>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/cal.css" type="text/css" media="screen" />
</head>

<body>
<div id="body">

<?php	require_once("./header.php");	?>
<div id="page" class=" sidebar_right">
	<div class="container">	
		<div id="frame2">
			<div id="content">
				<div class="post">
					<h2><a>Ajout de prestations multiples</a></h2>
					<br />
					<div class="entry">
						<table align="center">
							<tr>
								<td>
									<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
										<fieldset>
											<legend>Listing des prestations</legend>
											<p>
												<label>Prestations de</label>
												<select name="mois">
													<option value="01" <?php if($mois=="01") { echo "selected"; } ?>> Janvier </option>
													<option value="02" <?php if($mois=="02") { echo "selected"; } ?>> Février </option>
													<option value="03" <?php if($mois=="03") { echo "selected"; } ?>> Mars </option>
													<option value="04" <?php if($mois=="04") { echo "selected"; } ?>> Avril </option>
													<option value="05" <?php if($mois=="05") { echo "selected"; } ?>> Mai </option>
													<option value="06" <?php if($mois=="06") { echo "selected"; } ?>> Juin </option>
													<option value="07" <?php if($mois=="07") { echo "selected"; } ?>> Juillet </option>
													<option value="08" <?php if($mois=="08") { echo "selected"; } ?>> Août </option>
													<option value="09" <?php if($mois=="09") { echo "selected"; } ?>> Septembre </option>
													<option value="10" <?php if($mois=="10") { echo "selected"; } ?>> Octobre </option>
													<option value="11" <?php if($mois=="11") { echo "selected"; } ?>> Novembre </option>
													<option value="12" <?php if($mois=="12") { echo "selected"; } ?>> Décembre </option>
												</select>
												<select name="annee">
													<?php
														for($i = 2010; $i <= date('Y'); $i++) {
															echo "<option value='$i'";
															if($annee == $i) echo " selected";
															echo "> $i </option>";
														}
													?>
												</select>
												<input type="submit" name="display" value="Afficher" />
											</p>
										</fieldset>
									</form>
								</td>
							</tr>
						</table>
						<form name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
							<table id="calendrier" align="center" class="sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow">
								<tr>
									<th align="center">Lundi</th>
									<th align="center">Mardi</th>
									<th align="center">Mercredi</th>
									<th align="center">Jeudi</th>
									<th align="center">Vendredi</th>
									<th align="center">Samedi</th>
									<th align="center">Dimanche</th>
								</tr>
								<tr>
									<?php
										if($beginday != 1)
											echo "<td colspan='".($beginday-1)."'>&nbsp;</td>";

										$jour = 0;
								
										for($i = $beginday; $i <= 7; $i++) {
											$jour++;
											$show = (TRUE && $print);
											$time = date("d/m/Y", mktime(0, 0, 0, $mois , $jour, $annee));
											$daynumber = date("N", mktime(0, 0, 0, $mois , $jour, $annee));
											$temps = date("Y-m-d", mktime(0, 0, 0, $mois , $jour, $annee));
											$displaytime = strftime("%e %B %Y", strtotime(date("j F Y", mktime(0, 0, 0, $mois , $jour, $annee))));
											
											if($temps > $line_holiday['holidate_end'])	// while
												$line_holiday = mysql_fetch_array($result_holiday, MYSQL_ASSOC);
											
											echo "<td><div class='";
											if (($i == 7) || ($mois == 1 && $jour == 1) || ($mois == 5 && (($jour == 1) || ($jour == 8))) || ($mois == 7 && $jour == 21) || ($mois == 8 && $jour == 15) || ($mois == 9 && $jour == 27) || ($mois == 11 && (($jour == 1) || ($jour == 11))) || ($mois == 12 && $jour == 25)  || (($line_holiday['holidate_begin'] <= $temps) && ($line_holiday['holidate_end'] >= $temps))) {
												$show = FALSE;
												echo "calenderholiday";
											} elseif($i == 6)
												echo "calendersaturday";
											else
												echo "calender";
											
											echo "'><div class='caldate'>";
											
											if((substr($lastfinished, 0, 4) <= $annee) && (substr($lastfinished, 5, 2) <= $mois ))
												echo "<a href='prestation_add.php?date=$time' class='lien' title='Ajouter une prestation'>";
												
											echo $displaytime."</a></div><div class='calcontent'>";
											
//											if((date("N", mktime(0, 0, 0, $mois , substr($line['date'], 8, 2), $annee)) != $daynumber) || ($line['h_from'] != substr($line['h_begin'], 0, 5)) || ($line['h_from'] != substr($line['h_end'], 0, 5))) {
//												$show = FALSE;
//												$line = mysql_fetch_array($result);
//											}
											
											if($show && (!empty($prevision[$daynumber])))
												for($k=0; !empty($prevision[$daynumber][$k]); ++$k)
												  echo "<input type='checkbox' name='course[]' value='$temps&".$prevision[$daynumber][$k][0]."' \>".$prevision[$daynumber][$k][1]."<br />";
												
											echo "</div></div></td>";
											
										}
									?>
	
								</tr>
								<?php
									$endday = date("N", mktime(0, 0, 0, $mois , $nbj, $annee));
									$jour++;
	
									for($j=0; $j<5 && $jour<=$nbj; $j++) {
										echo "<tr>";
										for($i=0; $i<7 && $jour <= $nbj; $i++) {
											$show = (TRUE && $print);
											$time = date("d/m/Y", mktime(0, 0, 0, $mois , $jour, $annee));
											$daynumber = date("N", mktime(0, 0, 0, $mois , $jour, $annee));
											$temps = date("Y-m-d", mktime(0, 0, 0, $mois , $jour, $annee));
											$displaytime = strftime("%e %B %Y", strtotime(date("j F Y", mktime(0, 0, 0, $mois , $jour, $annee))));
											
											if($temps > $line_holiday['holidate_end'])	// while
												$line_holiday = mysql_fetch_array($result_holiday, MYSQL_ASSOC);
											
//											echo $temps." -- ".$line_holiday['holidate_begin']." -- ".$line_holiday['holidate_end']." -- ".($line_holiday['holidate_begin'] <= $temps)." -- ".($line_holiday['holidate_end'] >= $temps)."<br />";
	
											echo "<td><div class='";
											if (($i == 6) || ($mois == 1 && $jour == 1) || ($mois == 5 && (($jour == 1) || ($jour == 8))) || ($mois == 7 && $jour == 21) || ($mois == 8 && $jour == 15) || ($mois == 9 && $jour == 27) || ($mois == 11 && (($jour == 1) || ($jour == 11))) || ($mois == 12 && $jour == 25)  || (($line_holiday['holidate_begin'] <= $temps) && ($line_holiday['holidate_end'] >= $temps))) {
												$show = FALSE;
												echo "calenderholiday";
											} elseif($i == 5)
												echo "calendersaturday";
											else
												echo "calender";

											echo "'><div class='caldate'><a href='prestation_add.php?date=$time' class='lien' title='Ajouter une prestation'>$displaytime</a></div><div class='calcontent'>";
											if($show && (!empty($prevision[$daynumber])))
												for($k=0; !empty($prevision[$daynumber][$k]); ++$k)
												  echo "<input type='checkbox' name='course[]' value='$temps&".$prevision[$daynumber][$k][0]."' \>".$prevision[$daynumber][$k][1]."<br />";
												
											echo "</div></div></td>";
											
											$jour++;
										}
										
										if($jour != ($nbj+1))
											echo "</tr>\n";
										elseif($endday != 7)
											echo "<td colspan='".(7-$endday)."'>&nbsp;</td>";

										echo "</tr>\n";
									}
								?>
							</table>
							<p align="center"><input type="submit" name="submit" value="Enregistrer" /></p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php	require_once("./footer.php");	?>
</body>
</html>