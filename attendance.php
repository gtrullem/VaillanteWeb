<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "attendance";
	require_once("./CONFIG/config.php");
	
//	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
//		header("Refresh: 0; url=./redirection.php?err=1");
//		exit;
//	}
	
	if(empty($_POST['linkid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=lien&referrer=course_listing.php");
		exit;
	}

	$linkeid = $_POST['linkid'];

	$query = "SELECT c.courseid, lcs.day, lcs.daynumber, lcs.h_begin, lcs.h_end, lcs.seasonid, c.active, d.title AS dtitle, d.acronym AS dacronym, sd.title AS sdtitle, sd.acronym AS sdacronym, xtr_season.seasonlabel FROM xtr_course AS c, xtr_discipline AS d, xtr_subdiscipline AS sd, xtr_linkCourseSeason AS lcs, xtr_season WHERE c.subdisciplineid = sd.subdisciplineid AND sd.disciplineid = d.disciplineid AND lcs.courseid = c.courseid AND lcs.seasonid = xtr_season.seasonid AND lcs.lcsid = $linkid";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (personne, cours, discipline, subdiscipline, link, saison) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$course = mysql_fetch_array($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Liste de présences :.</title>
</head>
	
<body>
<div id="body">
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
				<font size="5"><b><u>La Vaillante Tubize - <?php echo $course['dtitle']." (".$course['seasonlabel'].")"; ?></b></u></font>
				<table width="100%">
					<tr>
						<td>
							<b><?php echo $course['sdtitle']." - ".$course['day']." de ".substr($course['h_begin'], 0, 5)." à ".substr($course['h_end'], 0, 5)."&nbsp;&nbsp;&nbsp;"; ?></b>
						</td>
						<td align="right"><b>
						<?php
							$query = "SELECT xtr_users.userid, CONCAT(firstname , ' ', lastname) AS name FROM xtr_istrainer, xtr_users, xtr_person WHERE  xtr_istrainer.userid = xtr_users.userid AND xtr_users.personid = xtr_person.personid AND xtr_istrainer.lcsid = $linkid ORDER BY name";
							$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (trainer, user, person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
							$line = mysql_fetch_array($result);
							echo $line['name'];
							while($line = mysql_fetch_array($result))
								echo ",&nbsp;".$line['name'];

						?></b>
						</td>
					</tr>

				</table>
				<br />
				<?php
					
					$query = "SELECT xtr_isaffiliate.personid, CONCAT(lastname, ', ', firstname) AS name, phone, gsm FROM xtr_isaffiliate, xtr_person WHERE xtr_isaffiliate.personid = xtr_person.personid AND xtr_isaffiliate.lcsid = $linkid ORDER BY name";
					$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (trainer, user, person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);

					$day = abs(intval(date('N'))-intval($course['daynumber']));
				?>
				<table border="1" cellspacing="0" cellpadding="0">
					<tr>
						<td>&nbsp;</td><!-- &nbsp;Nom, Prénom -->
					<?php
						
						
						for($i = 0; $i<12; $i++) {
							$nbday = "+".($day+(7*$i))." day";
							
							echo "<td>&nbsp;&nbsp;&nbsp;".strftime("%e%b", strtotime(date('d M', strtotime($nbday))))."&nbsp;&nbsp;&nbsp;</td>";
						}
						
						while($line = mysql_fetch_array($result)) {
							echo "<tr><td><table width='100%'><tr><td>&nbsp;".$line['name']."&nbsp;</td><td align='right'>&nbsp;<font size='1'>";
							if($line['gsm'] != "") {
								echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2)."&nbsp;</td></tr></table></td>";
							} else {
								if($line['phone'] != "") {
									if($line['phone'][1] == 2)
										echo substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3);
									else
										echo substr($line['phone'], 0, 3)."/".substr($line['phone'], 4, 2);

									echo ".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
								}
								echo "&nbsp;</td></tr></table></td>";
							}
							
							for($i = 0; $i<12; $i++)
								echo "<td>&nbsp;</td>";
							
							echo "</tr>";
							
						}
					?>
					<!-- </tr>	-->
				</table>
			</div>		
		</div>
	</div>
</div>
</div>
</body>
</html>