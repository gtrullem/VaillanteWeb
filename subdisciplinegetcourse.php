<?php
	$saisonid = $_GET['seasonid'];
	$subdsiciplineid = $_GET['subdisciplineid'];
?>

<table width="100%" cellspacing="0">
	<tr>
		<th width="75">Jour</th>
		<th width="90">Horaire</th>
		<th colspan="2">Moniteur</th>
	</tr>
	<?php
		require_once("./CLASS/dbcourse.class.php");

		$database = new DBCourse();

		foreach($database->getCourseFromSubDisciplineID($subdisciplineid, $seasonid) as $course)
			echo "<tr><td>&nbsp;<a href='course_detail.php?linkid=".$course->getLinkId()."'>".$course->getDay()."</a></td><td>De ".substr($course->getBeginHour(), 0, 5)." à ".substr($course->getEndHour(), 0, 5)."</td><td width='250'>&nbsp;</td><td><a href='trainer_del.php?linkid=".$course->getLinkId()."&userid=".$line2['userid']."'><img src='./design/images/icons/16_delete.png' title='Supprimer le moniteur !' /></a></td></tr>";
			
	?>
</table>