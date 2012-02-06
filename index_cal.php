<?php
	require_once ("./CONFIG/config.php");
	
	if(!empty($_GET['month']))
		$mois = $_GET['month'];
	else
		$mois = date("m");
	
	if(!empty($_GET['year']))
		$annee = $_GET['year'];
	else
		$annee = date("Y");

	$today = date("j");
	$tomonth = date("m");
	$toyear = date("Y");
?>
<h2 class="title"><?php echo ucwords(strftime("%B %Y", strtotime(date("F Y", mktime(0, 0, 0, $mois , 1, $annee)))));	?></h2>
<table cellspacing="1">
<tr>
	<th align="center"><font color="555555">Lu</font></th>
	<th align="center"><font color="555555">Ma</font></th>
	<th align="center"><font color="555555">Me</font></th>
	<th align="center"><font color="555555">Je</font></th>
	<th align="center"><font color="555555">Ve</font></th>
	<th align="center"><font color="555555">Sa</font></th>
	<th align="center"><font color="555555">Di</font></th>
</tr>
<tr>
<?php
	
	$nbj=date("t",mktime(0,0,0,$mois,1,$annee));
	
	$month = array();
	$event = array();
	
	for($i=0; $i<($nbj+1); $i++) {
		$month[$i] = 0;
		$event[$i] = 0;
	}
	
	$time = $annee."-".$mois;
	
	// SELECTING HOLIDAY
	$query = "SELECT * FROM xtr_holiday WHERE holidate_begin REGEXP('^$time') OR holidate_end REGEXP('^$time') ORDER BY holidate_end";
	$result_holiday = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (holiday) !<br />".$result_holiday."<br />".mysql_error(), E_USER_ERROR);
	$line_holiday = mysql_fetch_array($result_holiday, MYSQL_ASSOC); 
	
	// SELECTING EVENT
//	$query = "SELECT eventid, dbegin, dend FROM xtr_event WHERE dbegin REGEXP('^$time') ORDER BY dbegin";
//	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (event) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
//	
//	while($line = mysql_fetch_array($result)) {
//		for($i = intval(substr($line['dbegin'], 8, 2)); $i <= intval(substr($line['dend'], 8, 2)); ++$i) {
//			$month[$i] = 1;
//			$event[$i] = $line['eventid'];
//		}
//	}

	$beginday = date("N", mktime(0, 0, 0, $mois , 1, $annee));
	if($beginday != 1)
		echo "<td colspan='".($beginday-1)."'>&nbsp;</td>";

	$jour = 0;
	
	for($i=$beginday; $i<=7; $i++) {
		// echo "Jour : $jour - i : $i<br />";
		$jour++;
		$temps = date("Y-m-d", mktime(0, 0, 0, $mois , $jour, $annee));		
		
		if($temps > $line_holiday['holidate_end']) {	// while
			$line_holiday = mysql_fetch_array($result_holiday, MYSQL_ASSOC);
		}
		
//		if($temps > $line_event['dend']) {
//			$line_event = mysql_fetch_array($result_event, MYSQL_ASSOC);
//		}
		
//		if($month[$jour] == 1) {									
//			echo "<td onMouseOver=\"this.bgColor='#78757A'\" onMouseOut=\"this.bgColor='#565358'\" bgcolor=\"565358\" align=\"center\" width=\"30\" onClick=\"window.location.href='event_detail.php?id=$line[0]'\"><font size=\"1\" color=\"EEEEEE\">$jour</font></td>";
//		} else
//		
		echo "<td bgcolor='";
		if($jour == $today  && $mois == $tomonth && $annee == $toyear)
			echo "#3D3D3D";
		elseif(($i == 7) || ($mois == 1 && $jour == 1) || ($mois == 5 && (($jour == 1) || ($jour == 8))) || ($mois == 7 && $jour == 21) || ($mois == 8 && $jour == 15) || ($mois == 9 && $jour == 27) || ($mois == 11 && (($jour == 1) || ($jour == 11))) || ($mois == 12 && $jour == 25)  || (($line_holiday['holidate_begin'] <= $temps) && ($line_holiday['holidate_end'] >= $temps)))
			echo "#DDDDDD";
		elseif($i == 6)
			echo "#EEEEEE";
		else
			echo "#FFFFFF";
		
		echo "' align='center' width='30'><font size='1' color='777777'>$jour</font></td>";
	}
	
	echo "</tr>\n";

	$endday = date("N", mktime(0, 0, 0, $mois , $nbj, $annee));
	$jour++;

	for($j=0; $j<5 && $jour<=$nbj; $j++) {
		echo "<tr>";
		for($i=0; $i<7 && $jour <= $nbj; $i++) {
			// echo "Jour : $jour - i : $i<br />";
			$temps = date("Y-m-d", mktime(0, 0, 0, $mois , $jour, $annee));
			
			if($temps > $line_holiday['holidate_end']) { // while
				$line_holiday = mysql_fetch_array($result_holiday, MYSQL_ASSOC);
			}
			
//			if($temps > $line_event['dend']) {
//				$line_event = mysql_fetch_array($result_event, MYSQL_ASSOC);
//			}
//			
//			if($month[$jour] == 1) {							
//				echo "<td onMouseOver=\"this.bgColor='#78757A'\" onMouseOut=\"this.bgColor='#565358'\" bgcolor=\"565358\" align=\"center\" width=\"30\" onClick=\"window.location.href='event_detail.php?id=$event[$jour]'\"><font size=\"1\" color=\"EEEEEE\">$jour</font></td>";
//			} else
			
			echo "<td bgcolor='";
			if($jour == $today  && $mois == $tomonth && $annee == $toyear)
				echo "#3D3D3D";
			elseif(($i == 6) || ($mois == 1 && $jour == 1) || ($mois == 5 && (($jour == 1) || ($jour == 8))) || ($mois == 7 && $jour == 21) || ($mois == 8 && $jour == 15) || ($mois == 9 && $jour == 27) || ($mois == 11 && (($jour == 1) || ($jour == 11))) || ($mois == 12 && $jour == 25)  || (($line_holiday['holidate_begin'] <= $temps) && ($line_holiday['holidate_end'] >= $temps)))
				echo "#DDDDDD";
			elseif($i == 5)
				echo "#EEEEEE";
			else
				echo "#FFFFFF";
			
			echo "' align='center' width='30'><font size='1' color='777777'>$jour</font></td>";
			$jour++;
		}
		
		if($jour != ($nbj+1))
			echo "</tr>";
		elseif($endday != 7)
			echo "<td colspan=\"".(7-$endday)."\">&nbsp;</td>";

		echo "</tr>";
	}
	echo "</table>";
?>