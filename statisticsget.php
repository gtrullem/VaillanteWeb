<?php
	
	///////////////////////////////////////////////////////////////////////////
	// Specific Statistics
	///////////////////////////////////////////////////////////////////////////
	require_once("./CONFIG/config.php");
	require_once("./CONFIG/var_config.php");
	$seasonid = $_GET['seasonid'];

	// label de la saison
	$query = "SELECT seasonlabel FROM xtr_season WHERE seasonid = $seasonid";
	$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (course) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$seasonlabel = mysql_fetch_array($result, MYSQL_NUM);
	$seasonlabel = $seasonlabel[0];

	// nombre de cours pour la saison
	$query = " SELECT COUNT(lcsid) FROM xtr_linkCourseSeason WHERE seasonid = $seasonid";
	$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (course) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$ncourse = mysql_fetch_array($result, MYSQL_NUM);
	$ncourse = $ncourse[0];
	
	$query = "SELECT COUNT(DISTINCT(disciplineid)) FROM xtr_subdiscipline, xtr_course, xtr_linkCourseSeason WHERE xtr_subdiscipline.subdisciplineid = xtr_course.subdisciplineid AND xtr_course.courseid = xtr_linkCourseSeason.lcsid AND xtr_linkCourseSeason.seasonid = $seasonid";
	$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (cubdiscipline, course, link) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$nDisciplineUsed = mysql_fetch_array($result, MYSQL_NUM);
	$nDisciplineUsed = $nDisciplineUsed[0];

	$query = "SELECT COUNT(DISTINCT(subdisciplineid)) FROM xtr_course, xtr_linkCourseSeason WHERE xtr_course.courseid = xtr_linkCourseSeason.lcsid AND xtr_linkCourseSeason.seasonid = $seasonid";
	$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (course, link) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$nSubDisciplineUsed = mysql_fetch_array($result, MYSQL_NUM);
	$nSubDisciplineUsed = $nSubDisciplineUsed[0];
	
	// Nombre d'affiliés distincts
	$query = "SELECT COUNT(DISTINCT personid) FROM xtr_isaffiliate, xtr_linkCourseSeason WHERE xtr_isaffiliate.lcsid = xtr_linkCourseSeason.lcsid AND xtr_linkCourseSeason.seasonid = $seasonid";
	$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (affiliate, link) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$naffiliate = mysql_fetch_array($result, MYSQL_NUM);
	$naffiliate = $naffiliate[0];

	// Nombre d'affilités total
	$query = "SELECT COUNT(personid) FROM xtr_isaffiliate, xtr_linkCourseSeason WHERE xtr_isaffiliate.lcsid = xtr_linkCourseSeason.lcsid AND xtr_linkCourseSeason.seasonid = $seasonid";
	$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (affiliate, link) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$naffiliatetot = mysql_fetch_array($result, MYSQL_NUM);
	$naffiliatetot = $naffiliatetot[0];

	// nombre de prestation par saison
	$query = "SELECT COUNT(prestationid) FROM xtr_prestation WHERE `date` BETWEEN '".substr($seasonlabel, 0, 4)."-08-01' AND '".substr($seasonlabel, 5, 4)."-07-31'";
	$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (prestation) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$nprestationseason = mysql_fetch_array($result, MYSQL_NUM);
	$nprestationseason = $nprestationseason[0];

	// nombre d'event de la saison
	$query = "SELECT COUNT(xtr_event.eventid) FROM xtr_event WHERE dbegin <= '".substr($seasonlabel, 5, 4)."-07-31' AND dend >= '".substr($seasonlabel, 0, 4)."-08-01' ORDER BY xtr_event.dbegin";
	$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (prestation) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$neventseason = mysql_fetch_array($result, MYSQL_NUM);
	$neventseason = $neventseason[0];

	///////////////////////////////////////////////////////////////////////////
	// General Statistics
	///////////////////////////////////////////////////////////////////////////
	$query = " SELECT COUNT(disciplineid) FROM xtr_discipline";
	$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (discipline) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$ndiscipline = mysql_fetch_array($result, MYSQL_NUM);
	$ndiscipline = $ndiscipline[0];
	
	$query = " SELECT COUNT(disciplineid) FROM xtr_discipline WHERE active = 'N'";
	$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (discipline) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$ndisableddiscipline = mysql_fetch_array($result, MYSQL_NUM);
	$ndisableddiscipline = $ndisableddiscipline[0];
	
	$query = " SELECT COUNT(subdisciplineid) FROM xtr_subdiscipline";
	$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (subdiscipline) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$nsubdiscipline = mysql_fetch_array($result, MYSQL_NUM);
	$nsubdiscipline = $nsubdiscipline[0];
	
	$query = " SELECT COUNT(subdisciplineid) FROM xtr_subdiscipline WHERE active = 'N'";
	$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (subdiscipline) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$ndisabledsubdiscipline = mysql_fetch_array($result, MYSQL_NUM);
	$ndisabledsubdiscipline = $ndisabledsubdiscipline[0];
	
	$query = " SELECT COUNT(personid) FROM xtr_person";
	$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$nperson = mysql_fetch_array($result, MYSQL_NUM);
	$nperson = $nperson[0];
	
	$query = " SELECT COUNT(userid) FROM xtr_users";
	$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (user) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$nuser = mysql_fetch_array($result, MYSQL_NUM);
	$nuser = $nuser[0];
	
	$query = " SELECT COUNT(userid) FROM xtr_users WHERE status_in = 0 AND status_out = 0";
	$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (user) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$ndisableduser = mysql_fetch_array($result, MYSQL_NUM);
	$ndisableduser = $ndisableduser[0];

	$query = " SELECT COUNT(prestationid) FROM xtr_prestation ";
	$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (user) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$nprestation = mysql_fetch_array($result, MYSQL_NUM);
	$nprestation = $nprestation[0];
?>

<table align="center">
	<tr>
		<td>
			<form class="formulaire">
				<fieldset>
					<legend>Statistiques de la saison <?php echo $seasonlabel; ?></legend>
					<p>
						<label>Nombre de cours</label>
						<?php echo $ncourse; ?> (dont <?php echo $ndisabledcourse; ?> désactivés)
					</p>
					<p>
						<label>Nombre de gymnastes</label>
						<?php echo $naffiliate." uniques&nbsp;&nbsp;&nbsp;(".$naffiliatetot." total)"; ?>
					</p>
					<p>
						<label>Moyenne gym/cours</label>
						<?php echo substr(($naffiliate/($ncourse-$ndisabledcourse)), 0, 5); ?>
					</p>
					<p>
						<label>Moyenne gym/sous-sec.</label>
						<?php echo substr(($naffiliate/($nsubdiscipline-$ndisabledsubdiscipline)), 0, 5); ?>
					</p>
					<p>
						<label>Moyenne gym/section</label>
						<?php echo substr(($naffiliate/($ndiscipline-$ndisableddiscipline)), 0, 5); ?>
					</p>
					<p>
						<label>Nombre de Prestations</label>
						<?php echo $nprestationseason; ?>
					</p>
					<p>
						<label>Nombre d'évènements</label>
						<?php echo $neventseason; ?>
					</p>
				</fieldset>
			</form>
		</td>
	</tr>
</table>

<table id="table0" cellspacing="5" cellpadding="0" align="center" width="80%"> 
	<tr>
		<td align="center" colspan="2">
			<div id="chartcontainer1">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</div>
			<?php
				$query = "SELECT COUNT(DISTINCT personid), xtr_discipline.disciplineid, xtr_discipline.acronym FROM xtr_isaffiliate, xtr_linkCourseSeason, xtr_course, xtr_subdiscipline, xtr_discipline WHERE xtr_isaffiliate.lcsid = xtr_linkCourseSeason.lcsid AND xtr_linkCourseSeason.courseid = xtr_course.courseid AND xtr_course.subdisciplineid = xtr_subdiscipline.subdisciplineid AND xtr_subdiscipline.disciplineid = xtr_discipline.disciplineid AND xtr_linkCourseSeason.seasonid = $seasonid GROUP BY xtr_discipline.acronym";
				$result = mysql_query($query,$connect) or trigger_error("SQL Error : SELECT FAILED (affiliate, link, course, subdiscipline, discipline) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
				
				// JSCHART VAR
				$string = "new Array(";
				$colorString = "new Array(";

				$i = 1;
				while($line = mysql_fetch_array($result)) {
					$string .= "['".$line['acronym']."',".$line[0]."], ";
					$colorString .= "'".$color[$i]."', ";
					++$i;
				}

				$colorString = substr($colorString, 0, -2).")";
				$string = substr($string, 0, -2).");";
				
				echo "<script type='text/javascript'>	var myData = ".$string;

				echo "var myChart = new JSChart('chartcontainer1', 'pie');	myChart.setTitle('Répartitions des Gymnastes par Discipline');	myChart.setDataArray(myData);	myChart.setPieValuesColor('#111111');	var myColors = ".$colorString.";	myChart.colorizePie(myColors);	myChart.setSize(500, 500);		myChart.draw();	</script>";
				?>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2">
			<div id="chartcontainer2">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</div>
				<?php
					$query = "SELECT COUNT(DISTINCT personid), xtr_subdiscipline.subdisciplineid, xtr_subdiscipline.acronym, xtr_discipline.disciplineid, xtr_discipline.acronym AS discAcro FROM xtr_isaffiliate, xtr_linkCourseSeason, xtr_course, xtr_subdiscipline, xtr_discipline WHERE xtr_isaffiliate.lcsid = xtr_linkCourseSeason.lcsid AND xtr_linkCourseSeason.courseid = xtr_course.courseid AND xtr_course.subdisciplineid = xtr_subdiscipline.subdisciplineid AND xtr_discipline.disciplineid = xtr_subdiscipline.disciplineid AND xtr_linkCourseSeason.seasonid = $seasonid GROUP BY xtr_subdiscipline.acronym ORDER BY xtr_discipline.disciplineid, xtr_subdiscipline.subdisciplineid, xtr_course.courseid, xtr_linkCourseSeason.lcsid";
					$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (affiliate, link, course, subdiscipline) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
					
					$string = "new Array(";
					$colorString = "";
					while($line = mysql_fetch_array($result)) {
						$string .= "['".$line['acronym']."',".$line[0]."], ";
						$colorString .= "'".$color[$line['disciplineid']]."', ";
					}
					$string = substr($string, 0, -2).");";
					$colorString = substr($colorString, 0, -2);

					echo "<script type='text/javascript'>	var myData = ".$string;

					echo "var myChart = new JSChart('chartcontainer2', 'bar');	myChart.setTitle('Répartitions des Gymnastes par Sous-Discipline');	myChart.setDataArray(myData);	myChart.setAxisNameX('Sous-Discipline');	myChart.setAxisNameY('Nombre d élève');	var myColors = new Array(".$colorString.");	myChart.colorizeBars(myColors);	myChart.setSize(920, 400);	myChart.draw();	</script>";
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<br />
			<table id="table2" cellspacing="0" cellpadding="0" align="center" width="50%">
				<tr bgcolor="#9DD4FB">
					<th colspan="2">Cours</th>
					<th># Gymnastes</th>
				</tr>
				<?php
					$query = "SELECT COUNT( personid ) , xtr_subdiscipline.acronym, xtr_linkCourseSeason.*, xtr_course.* FROM xtr_isaffiliate, xtr_linkCourseSeason, xtr_course, xtr_subdiscipline WHERE xtr_isaffiliate.lcsid = xtr_linkCourseSeason.lcsid AND xtr_linkCourseSeason.courseid = xtr_course.courseid AND xtr_course.subdisciplineid = xtr_subdiscipline.subdisciplineid AND xtr_linkCourseSeason.seasonid = $seasonid GROUP BY xtr_linkCourseSeason.courseid ORDER BY xtr_subdiscipline.acronym, xtr_linkCourseSeason.daynumber, xtr_linkCourseSeason.h_begin";
					$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (affiliate, link, course, subdiscipline) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
					
					while($line = mysql_fetch_array($result))
							echo "<tr><td><a href='affiliate_listing.php?action=course&id=".$line['courseid']."'>".$line['acronym']." - ".$line['day']." </a></td><td><font size='1'>(de ".substr($line['h_begin'], 0, 5)." à ".substr($line['h_end'], 0, 5).")</font></td><td align='right'>".$line[0]."</td></tr>";
				?>
			</table>
		</td>
	</tr>
</table>