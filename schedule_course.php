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
	
	// function microtime_float()
// {
    // list($usec, $sec) = explode(" ", microtime());
    // return ((float)$usec + (float)$sec);
// }

	if(!empty($_GET['id']))
		$id = $_GET['id'];

	require_once("./CONFIG/var_config.php");
	
	if(isset($_POST['submit'])) {

	}
	
	if(date("n") >= "8")
		$season = date("Y")."-".(date("Y") + 1);
	else
		$season = (date("Y") - 1)."-".date("Y");
					
	// Counting discipline
	$query = "SELECT COUNT(disciplineid) FROM xtr_discipline WHERE active = 'Y'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$nb_disc = mysql_fetch_array($result);
	$nb_disc = $nb_disc[0];
	
	// Counting discipline
	$query = "SELECT COUNT(subdisciplineid) FROM xtr_subdiscipline WHERE active = 'Y'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (subdiscipline) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$nb_subdisc = mysql_fetch_array($result);
	$nb_subdisc = $nb_subdisc[0];

	$query = "SELECT daynumber, h_begin, h_end, sd.title AS sdtitle, sd.acronym AS sdacronym, d.disciplineid AS did, d.title AS dtitle, d.acronym AS dacronym FROM xtr_course, xtr_linkCourseSeason, xtr_subdiscipline AS sd, xtr_discipline AS d WHERE xtr_linkCourseSeason.courseid = xtr_course.courseid AND sd.disciplineid = d.disciplineid AND xtr_course.subdisciplineid = sd.subdisciplineid AND sd.active = 'Y' AND d.active = 'Y' AND xtr_linkCourseSeason.seasonid = 2 ORDER BY daynumber, xtr_course.subdisciplineid, h_begin"; /** AND season =  '$season'  **/
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course, discipline, subdiscipline) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);

	$table = array();
	for($i=0; $i<30; $i++)
		for($j=0; $j<(6*$nb_disc); $j++)
			$table[$i][$j] = 0;
	
	while($line = mysql_fetch_array($result)) {
		$i = (intval(substr($line['h_begin'], 0, 2))*2 + (intval(substr($line['h_begin'], 3, 2))/30)) - 15;
		$end = (intval(substr($line['h_end'], 0, 2))*2 + (intval(substr($line['h_end'], 3, 2))/30)) - 15;
		for($i; $i<$end; $i++)
			$table[$i][(($line['daynumber']-1)*$nb_disc)+($line['did']-1)] = $line['did']; // 1
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Horaires des Cours :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
</HEAD>

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
				<h2><a>Horaires des Cours</a> (en cours de construction)</h2>
				<br />
				<TABLE align="center">
					<TR>
						<?php
							$query = " SELECT disciplineid, acronym FROM xtr_discipline WHERE active = 'Y' ORDER BY acronym";
							$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
							
							while($line = mysql_fetch_array($result)) {
								echo "<TD width=\"10\" bgcolor=\"".$color[$line['disciplineid']]."\"></TD><TD>".$line['acronym']."</TD><TD width=\"20\">&nbsp;</TD>";
							}
						?>
					</TR>
				</TABLE>
				<BR />
				<TABLE align="center" width="600" border="0" cellpadding="0" cellspacing="0">
					<TR>
						<TH>&nbsp;</TH>
						<TH colspan="<?php echo $nb_disc; ?>">Lundi</TH>
						<TH colspan="<?php echo $nb_disc; ?>">Mardi</TH>
						<TH colspan="<?php echo $nb_disc; ?>">Mercredi</TH>
						<TH colspan="<?php echo $nb_disc; ?>">Jeudi</TH>
						<TH colspan="<?php echo $nb_disc; ?>">Vendredi</TH>
						<TH colspan="<?php echo $nb_disc; ?>">Samedi</TH>
						<TH>&nbsp;</TH>
					</TR>
					<?php
						$hour = 8;
						
						// Pour les heures
						for($i=1; $i<30; $i++) {
							if($hour<10) {
								$h_display = "0".$hour;
							} else {
								$h_display = $hour;
							}
							
							if(($i%2) == 0) {
								$hour++;
								$h_display .= ":30";
								echo "<TR height=\"25\" bgcolor=\"#E7F1F7\" valign=\"top\">";
							} else {
								$h_display .= ":00";
								echo "<TR height=\"25\" valign=\"top\">";
							}
							
							echo "<TD valign=\"top\" align=\"left\"><font size=\"1\"><sup>$h_display</sup></font></TD>";
							
							// Pour les disciplines
							for($j = 0; $j<(6*$nb_disc); $j++) {
								echo "<TD width=\"12\" bgcolor=\"".$color[$table[$i][$j]]."\">";
//								if((($j+1) % $nb_disc) == 0) {
//									"<div style=\"border: solid 2px #DDDDDD; border-left-width:2px; padding-left:0.5ex\">&nbsp;</DIV></TD>";
//								} else {
									echo "&nbsp;</TD>";
//								}
							}
							echo "<TD align=\"right\"><font size=\"1\"><sup>$h_display</sup></font></TD>";
							echo "</TR>\r\n";
						}
					?>
				</TABLE>
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