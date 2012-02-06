<?php
	require_once("./CONFIG/config.php");
	$test = FALSE;

	if(!empty($_GET['seasonid']) && (!empty($_GET['type'])) && (!empty($_GET['value']))) {
		$test = TRUE;
		$seasonid = $_GET['seasonid'];
		$type = $_GET['type'];
		$value = $_GET['value'];
		
		$query = "SELECT DISTINCT p.personid, p.lastname, p.firstname, p.phone, p.gsm, p.email FROM xtr_course, xtr_linkCourseSeason, xtr_subdiscipline, xtr_isaffiliate, xtr_person AS p WHERE xtr_linkCourseSeason.courseid = xtr_course.courseid AND xtr_course.subdisciplineid = xtr_subdiscipline.subdisciplineid AND xtr_isaffiliate.lcsid = xtr_linkCourseSeason.lcsid AND xtr_isaffiliate.personid = p.personid AND xtr_linkCourseSeason.seasonid = $seasonid";
		
//		if($value != "all") {
			if($type == "discipline") {
				$query .= " AND xtr_subdiscipline.disciplineid = $value";
			} elseif($type == "subdiscipline") {
				$query .= " AND sbd.subdisciplineid  = $value";
			} else {
				$query .= " AND c.courseid =  $value";
			}
//		}
		$query .= " ORDER BY p.lastname, p.firstname";

		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person, affiliate, course, subdiscipline, discipline, link) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		
		if($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			echo "<table align='center' border='0' id='table1' cellspacing='0' cellpadding='0' class='sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow'><thead><tr><th class='sortable' width='175'>&nbsp;Nom</th><th class='sortable' width='150'>Prénom</th><th class='sortable' width='100'>Téléphone</th><th class='sortable' width='100'>GSM</th><th width='50'>Email</th></tr></thead><tbody class='sort'>";

			$test = "/^02[0-9]{7}$/";
			do {
				echo "<tr><td class='sort'><a href='person_detail.php?personid=".$line['personid']."' title='détails de la personne'>".$line['lastname']."</a></td><td class='sort'>".$line['firstname']."</td><td class='sort'>";
				
				if($line['phone'] != "")
					if(preg_match($test, $line['phone']))
						echo substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
					else
						echo substr($line['phone'], 0, 3)."/".substr($line['phone'], 3, 2).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
				
				echo "</td><td class='sort'>";
				
				if($line['gsm'] != "")
					echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2)."</td>";
				
				echo "</td><td class='sort' align='center'>";
				
				if($line['email'] != "")
					echo "<a href='mailto:".$line['email']."' title='envoyer un email'><img src='./design/images/icons/16_send_mail.png' alt='envoyer un email' height='10' width='10' /></a>";
				
				echo "</td></tr>";
			} while ($line = mysql_fetch_array($result));
			echo "</table>";
		} else
			echo "<p align='center'>Désolé, aucun gymnaste ne correspond à vos critères de recherche.</p>";
	}
?>