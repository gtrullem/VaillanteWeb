<?php
	require_once("./CONFIG/config.php");
	$personid = $_GET['personid'];
	$seasonid = $_GET['seasonid'];
	
	$query = "SELECT DISTINCT LCS.lcsid, LCS.h_begin, LCS.h_end, LCS.day, SB.title, SB.acronym FROM xtr_isaffiliate, xtr_linkCourseSeason AS LCS, xtr_course, xtr_subdiscipline AS SB WHERE xtr_isaffiliate.lcsid = LCS.lcsid AND LCS.courseid = xtr_course.courseid AND xtr_course.subdisciplineid = SB.subdisciplineid AND xtr_isaffiliate.personid = $personid AND LCS.seasonid = $seasonid ORDER BY LCS.daynumber ";
	// echo $query;
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (affiliate, link, cours, subdiscipline) !<br />query<br />$result<br />".mysql_error(), E_USER_ERROR);
	
	$i=0;
	$OK = false;
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php
	
	while ($row = mysql_fetch_array($result)) {
		$i++;
		if(($i%2) == 0)
			echo "<tr bgcolor='#E7F1F7'>";
		else
			echo "<tr>";
		echo "<td width='150' align='left'>&nbsp;<a href='course_detail.php?linkid=".$row['lcsid']."'>".$row['acronym']." - ".$row['day']."</a></td><td  width='135' align='left'><font size='1'>(".substr($row['h_begin'], 0, 5)."-".substr($row['h_end'], 0, 5).")</font></td><td>";

		if($row['paid'] == "N") {
		  $OK = true;
			echo "<a href='isaffiliate_del.php?id=".$row['isaffiliateid']."' title='Supprimer le cours'><img src='./design/images/icons/16_delete.png' /></a>&nbsp;&nbsp;<input type='checkbox' name='paid[]' value='".$row[0]."' />";
		} else
			echo "&nbsp;";

		echo "</td></tr>";
	}
?>
</table>