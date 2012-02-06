<?php
	require_once("./CONFIG/config.php");
	$test = FALSE;
	
	if(!empty($_GET['field']) && (!empty($_GET['letter']))) {
		$test = TRUE;
		$field = $_GET['field'];
		$letter = $_GET['letter'];
		
		$query = " SELECT * FROM xtr_person WHERE ".$field." LIKE '$letter%' ORDER BY lastname, firstname";
	}
	
	if(!empty($_GET['season']) && (!empty($_GET['type'])) && (!empty($_GET['value']))) {
		$test = TRUE;
		$season = $_GET['season'];
		$type = $_GET['type'];
		$value = $_GET['value'];
		
		$query = "SELECT DISTINCT p.personid, p.lastname, p.firstname, p.birth, p.phone, p.gsm, p.email FROM xtr_isaffiliate AS ia, xtr_person AS p, xtr_course AS c, xtr_subdiscipline AS sbd, xtr_discipline AS d WHERE ia.personid = p.personid AND ia.courseid = c.courseid AND sbd.subdisciplineid = c.subdisciplineid AND d.disciplineid = sbd.disciplineid AND ia.season = '$season'";
		
//		$query = "SELECT * FROM vw_gymnast WHERE season = '$season'" 
		
//		if($value != "all") {
			if($type == "discipline") {
				$query .= " AND d.disciplineid  = '$value'";
			} elseif($type == "subdiscipline") {
				$query .= " AND sbd.subdisciplineid  = '$value'";
			} else {
				$query .= " AND c.courseid =  '$value'";
			}
//		}
		$query .= " ORDER BY p.lastname, p.firstname";
	}
	
//	echo $query."<br />";
	
	if($test) {
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		echo "<table align='center' border='0' id='table1' cellspacing='0' cellpadding='0' class='sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow'><thead><tr><th class='sortable' width='175'>&nbsp;Nom</th><th class='sortable' width='150'>Prénom</th><th class='sortable' width='100'>Téléphone</th><th class='sortable' width='100'>GSM</th><th width='50'>Email</th></tr></thead><tbody class='sort'>";

		$test = "/^02[0-9]{7}$/";
		while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			echo "<tr><td class='sort'><a href='person_detail.php?personid=".$line['personid']."' title='détails de la personne'>".$line['lastname']."</a></td><td class='sort'>".$line['firstname']."</td><td class='sort'>";
			
			if($line['phone'] != "") {
				if(preg_match($test, $line['phone'])) {
					echo substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
				} else {
					echo substr($line['phone'], 0, 3)."/".substr($line['phone'], 3, 2).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
				}
			}
			
			echo "</td><td class='sort'>";
			
			if($line['gsm'] != "") {
				echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2)."</td>";
			}
			
			echo "</td><td class='sort' align='center'>";
			
			if($line['email'] != "") {
				echo "<a href='mailto:".$line['email']."' title='envoyer un email'><img src='./design/images/icons/16_send_mail.png' alt='envoyer un email' height='10' width='10' /></a>";
			}
			
			echo "</td></tr>";
		}
		echo "</table>";
	}
?>