<?php

	session_start();

	$pagename = "annual_calendar"; // add & multiadd : same feature
	require_once("./CONFIG/config.php");
	require_once("./CONFIG/var_config.php");
	
	// Validity Checking
//	$query = " SELECT lastfinished FROM xtr_users WHERE userid = $userid";
//	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (users) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
//	$line = mysql_fetch_array($result, MYSQL_ASSOC);

	$mois = 8;	
	if(isset($_POST['display']))	$annee = $_POST['annee'];
	else	$annee = date('Y');
	
	$timefrom = $annee."-08-01";
	$timeto = ($annee+1)."-07-01";
	
	/************************************************************/
	/* SELECTING HOLIDAY 										*/
	/************************************************************/
	$query = "SELECT * FROM xtr_holiday WHERE (holidate_begin > '$timefrom' AND holidate_begin < '$timeto' ) OR holidate_end REGEXP('^$timefrom') ORDER BY holidate_end";
	$result_holiday = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (holiday) !<br />".$result_holiday."<br />".mysql_error(), E_USER_ERROR);
	$line_holiday = mysql_fetch_array($result_holiday, MYSQL_ASSOC);
	
	/************************************************************/
	/* SELECTION DES EVENTS 									*/
	/************************************************************/
	$query = "SELECT eventid, title, information, dbegin, dend FROM xtr_event WHERE (dbegin > '$timefrom' AND dbegin < '$timeto' ) ORDER BY dbegin";
	$result_event = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (holiday) !<br />".$result_event."<br />".mysql_error(), E_USER_ERROR);
	$line_event = mysql_fetch_array($result_event, MYSQL_ASSOC);
	
	if(date("n") >= "8")	$season = date("Y")."-".(date("Y") + 1);
	else	$season = (date("Y") - 1)."-".date("Y");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>.: La Vaillante - Calendrier Annuel :.</TITLE>
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
					<H2><a>Calendrier Annuel</a></H2>
					<div class="entry">
						<table align="center">
							<tr>
								<td>
									<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
										<fieldset>
											<legend>Calendrier</legend>
											<p>
												<table width="100%">
													<tr>
														<td  width="30%" align="right"><label>Calendrier de</label></td>
														<td>
														<select name="season">
															<?php
																$query = "SELECT * FROM xtr_season";
																$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (holiday) !<br />".$result_event."<br />".mysql_error(), E_USER_ERROR);

																while($line = mysql_fetch_array($result)) {
																	echo "<option value='".$line['seasonid']."";
																	if($line['seasonlabel'] === $season) echo " selected";
																	echo "'>".$line['seasonlabel']."</option>";
																}
															?>
														</select>
														</td>
														<td align="right">
														<input type="submit" name="display" value="Afficher" />
														</td>
													</tr>
												</table>
											</p>
										</fieldset>
									</form>
								</td>
							</tr>
						</table>
						<table id="calendrier" align="center" class="sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow">
							<tr>
								<th align="center" >Lundi</th>
								<th align="center">Mardi</th>
								<th align="center">Mercredi</th>
								<th align="center">Jeudi</th>
								<th align="center">Vendredi</th>
								<th align="center">Samedi</th>
								<th align="center">Dimanche</th>
							</tr>
							<tr>
								<?php
									$beginday = date("N", mktime(0, 0, 0, $mois , 1, $annee));
									if($beginday != 1) {
										echo "<td colspan=\"".($beginday-1)."\">&nbsp;</td>";
									}
									$jour = 0;
							
									for($i=$beginday; $i<=7; $i++) {
										$jour++;
										$time = date("d/m/Y", mktime(0, 0, 0, $mois , $jour, $annee));
										$temps = date("Y-m-d", mktime(0, 0, 0, $mois , $jour, $annee));
										$displaytime = ucwords(strftime("%e %B %Y", strtotime(date("j F Y", mktime(0, 0, 0, $mois , $jour, $annee))))); /** utf8_encode( **/
										
										if($temps > $line_holiday['holidate_end']) {	// while
											$line_holiday = mysql_fetch_array($result_holiday, MYSQL_ASSOC);
										}
										
										if($temps > $line_event['dend']) {
											$line_event = mysql_fetch_array($result_event, MYSQL_ASSOC);
										}
										
										echo "<td><div class=\"";
										if (($i == 7) || (($line_holiday['holidate_begin'] <= $temps) && ($line_holiday['holidate_end'] >= $temps))) {
											echo "calenderholiday";
										} elseif($i == 6) {
											echo "calendersaturday";
										} else {
											echo "calender";
										}
										echo "\"><div class=\"caldate\"><a href=\"event_add.php?date=$time\" class=\"lien\" title=\"Ajouter un évènement\">$displaytime</a></div><div class=\"calcontent\">";
										
										if(($temps >= $line_event['dbegin']) && ($temps <= $line_event['dend'])) {
											echo "&nbsp;&nbsp;<a href=\"event_detail.php?id=".$line['eventid']."\">".$line_event['title']."</a>";
										}
										
										echo "</div></div></td>";
										
									}
									
									echo "</tr>";
									$jour++;
									
									for($j=0; $j<47; $j++) {
										echo "<tr>";
										for($i=0; $i<7; $i++) {
											$time = date("d/m/Y", mktime(0, 0, 0, $mois , $jour, $annee));
											$temps = date("Y-m-d", mktime(0, 0, 0, $mois , $jour, $annee));
											$displaytime = ucwords(strftime("%e %B %Y", strtotime(date("j F Y", mktime(0, 0, 0, $mois , $jour, $annee))))); /** utf8_encode( **/
											
											if($line_holiday && $temps > $line_holiday['holidate_end']) { // while
												$line_holiday = mysql_fetch_array($result_holiday, MYSQL_ASSOC);
											}
											
											if($temps > $line_event['dend']) {
												$line_event = mysql_fetch_array($result_event, MYSQL_ASSOC);
											}
	
											echo "<td><div class=\"";
											
											if(($temps >= $line_event['dbegin']) && ($temps <= $line_event['dend'])) {
												echo "calender\" style=\"background-color: #424242"; //<!-- FFCDC5 -->
											} elseif (($i == 6)|| ($mois == 1 && $jour == 1) || ($mois == 5 && (($jour == 1) || ($jour == 8))) || ($mois == 7 && $jour == 21) || ($mois == 8 && $jour == 15) || ($mois == 9 && $jour == 27) || ($mois == 11 && (($jour == 1) || ($jour == 11))) || ($mois == 12 && $jour == 25)  || (($line_holiday['holidate_begin'] <= $temps) && ($line_holiday['holidate_end'] >= $temps))) {
												echo "calenderholiday";
												if(date("m", mktime(0, 0, 0, $mois , $jour, $annee)) %2 == 1) {
													echo "\" style=\"background-color: #B8D6D8";
												}
											} elseif($i == 5) {
												echo "calendersaturday";
												if(date("m", mktime(0, 0, 0, $mois , $jour, $annee)) %2 == 1) {
													echo "\" style=\"background-color: #C4E1D9";
												}
											} else {
												echo "calender";
												if(date("m", mktime(0, 0, 0, $mois , $jour, $annee)) %2 == 1) {
													echo "\" style=\"background-color: #EEEEEE";
												}
											}
											
											echo "\"><div class=\"caldate\"><a href=\"event_add.php?date=$time\" class=\"lien\" title=\"Ajouter un évènement\">$displaytime</a></div><div class=\"calcontent\">";
											if(($temps >= $line_event['dbegin']) && ($temps <= $line_event['dend'])) {
												echo "&nbsp;&nbsp;<a href=\"event_detail.php?id=".$line_event['eventid']."\">".$line_event['title']."</a>";
											}
											echo "</div></div></td>";
											
											$jour++;
											if($jour > date("t", mktime(0, 0, 0, $mois , 1, $annee))) {
												$mois++;
												$mois %= 12;
												if($mois == 1) $annee++;
												$jour = 1;
											}
										}
										echo "</tr>\n";
									}
								?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php	require_once("./footer.php");	?>
</body>
</html>