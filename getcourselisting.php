<?php
	
	require_once("./CONFIG/var_config.php");
	
	if(!empty($_GET['season']))
		$season = $_GET['season'];
	else
		$season = 1;

	require_once("./CLASS/dbcourse.class.php");

	$database = new DBCourse();
?>

<table id="tableCourse" align="center" width="700" border="0" cellpadding="0" cellspacing="0" class="sort no-arrow">
	<thead>
		<tr>
			<th class="noprint"></th>
			<th class="sortable">Discipline</th>
			<th class="sortable">Sous Discipline</th>
			<th class="sortable">Jour</th>
			<th class="sortable">De</th>
			<th class="sortable">A</th>
			<!-- <th class="noprint"></th> -->
		</tr>
	</thead>
	<tbody class="sort">
		<?php
			foreach($database->getSeasonCourses($season) as $course)
				echo "<tr><td class='sort noprint'><a href='course_detail.php?linkid=".$course->getLinkID()."' title='Détails du cours'><img src='./design/images/icons/16_view.png' height='10' width='10' /></a></td><td align='left' class='sort'><a href='discipline_detail.php?disciplineid=".$course->getSubDiscipline()->getDiscipline()->getID()."'>".$course->getSubDiscipline()->getDiscipline()->getTitle()."</a></td><td align='left' class='sort'><a href='subdiscipline_detail.php?subdisciplineid=".$course->getSubDiscipline()->getID()."'>".$course->getSubDiscipline()->getTitle()."</a></td><td class='sort'>".$course->getDay()."</td><td align='center' class='sort'>".substr($course->getBeginHour(), 0, 5)."</td><td align='center' class='sort'>".substr($course->getEndHour(), 0, 5)."</td></tr>";
		?>
	</tbody>
</table>