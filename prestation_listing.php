<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$function = "prestationListing";
	require_once("./CONFIG/config.php");
	require_once("./library/right.php");
	
	if(($_SESSION['status_in'] < $line['rightin']) && ($_SESSION['status_out'] < $line['rightout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	require_once("./CONFIG/var_config.php");
	$userid = $_SESSION['uid'];
	$mois = "";
	$annee = "";
	$time = "";
	
	if(!empty($_POST['complete'])) {
		$mois = $_POST['mois'];
		$annee = $_POST['annee'];
		$date = $annee."-".(++$mois)."-01";
		
		$query = "UPDATE xtr_users SET lastfinished = '$date' WHERE userid = ".$_SESSION['uid'];
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (user) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);

		// email sending
		$headers ="From: \"La Vaillante - Extranet\"<extranet@lavaillantetubize.be>\n"; 
		$headers .="Reply-To: extranet@lavaillantetubize.be"."\n"; 
		$headers .="Content-type: text/html; charset=iso-8859-1"."\n";
		$headers .="Content-Transfer-Encoding: 8bit";
		$subject = "Extranet : Prestations validées.";
		
		$message = "Bonjour,<br /><br />Les prestations de <b>".$_SESSION['name']."</b> pour le mois de <b>".date("F", mktime(0, 0, 0, $mois, 0, 0))." ".$annee."</b> ont été validées.<br /><br />Bonne fin de journée,<br /><br /> l'équipe Extranet";
		mail("tresorier@lavaillantetubize.be, aide-tresorier@lavaillantetubize.be, gtrullem@gmail.com", $subject, utf8_decode($message), $headers);

		$msg = "Prestations de ".date("F", mktime(0, 0, 0, $mois, 0, 0))." $annee validées...";
	}
  
	if(isset($_POST['submit'])) {
		$mois = $_POST['mois'];
		$annee = $_POST['annee'];
		$time = $annee."-".$mois;
		
		if(isset($_POST['user']))
			$userid = $_POST['user'];
	} else {
		if(!empty($_GET['personid']))
			$userid = $_GET['personid'];

		if(!empty($_GET['time'])) {
			$time = $_GET['time'];
			$tmp = explode("-", $time);
			$annee = $tmp[0];
			$mois = $tmp[1];
		} else {
			$mois = date('m');
			$annee = date('Y');
			$time = $annee."-".$mois;
		}
	}
	
	// Retrieving results
	$query = "SELECT COUNT(prestationid) FROM xtr_prestation WHERE userid='$userid' AND date REGEXP('^$time') GROUP BY date ORDER BY COUNT(prestationid) DESC LIMIT 0, 1";
//	echo $query."<br />";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (prestation count) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$max_prest = mysql_fetch_array($result);
	$max_prest = $max_prest[0];
	
	$query = "SELECT * FROM xtr_prestation WHERE userid = '$userid' AND date REGEXP('^$time') ORDER BY date, h_from";
//	echo $query."<br />";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (prestation) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	
	$nbj=date("t",mktime(0,0,0,$mois,1,$annee));
	$table = array();
	
	for($i=0; $i<$nbj; ++$i)					// the day
		for($j=0; $j<6; ++$j)					// the information
			for($k=0; $k< $max_prest; ++$k)		// the value
				$table[$i][$j][$k] = "";
	
	$save ="";
	while($line = mysql_fetch_array($result)) {			// recup des info
		$day = intval(substr($line['date'], 8, 2));
		
		if($save != $day) {
			$save = $day;
			$k = 0;
		}
				
		$table[$day][0][$k] = substr($line['h_from'], 0, 5);
		$table[$day][1][$k] = substr($line['h_to'], 0, 5);
		$table[$day][2][$k] = $line['nbhour'];
		$table[$day][3][$k] = $line['paid'];
		$table[$day][4][$k] = $line['prestationid'];
		$table[$day][5][$k] = $line['description'];
		++$k;
	}
	
	$query = "SELECT SUM(nbhour) FROM xtr_prestation WHERE userid = $userid AND date REGEXP('^$time') ORDER BY date, h_from";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (prestation) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$sum = $line[0];
	
	$query = "SELECT reward, DATE(lastfinished) FROM xtr_users WHERE userid = $userid";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (user) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$reward = $line[0];
	$lastfinished = $line[1];
	
	// SELECTION DES JOURS FERIER
	$query = "SELECT * FROM xtr_holiday WHERE holidate_begin REGEXP('^$time') OR holidate_end REGEXP('^$time') ORDER BY holidate_end";
//	echo "<br /><br /><br />".$query;
	$result_holiday = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (holiday) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$line_holiday = mysql_fetch_array($result_holiday, MYSQL_ASSOC);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Listing des Prestations :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />

	<link href="./design/tinyTips.css" rel="stylesheet" type="text/css" media="screen" />

	<script type="text/javascript" src="./library/jquery.min.js"></script>
	<script type="text/javascript" src="./library/jquery.tinyTips.js"></script>

	<script language="javascript">
		function checkForm(formulaire)
		{
			if(document.formulaire.user.value == "default") {
				alert('Veuillez choisir un moniteur.');
				return false;
			}
			return true;
		}

		$(document).ready(function() {
			$('a.tTip').tinyTips('title');
		});

	</script>
	<noscript>
		<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>
	</noscript>
</head>

<body>
	<div id="body">
	
	<?php
		require_once("./header.php");
	?>
		
	<div id="page" class=" sidebar_right">
		<div class="container">
			<div id="frame2">
				<div id="content">
					<h2><a>Liste des prestations</a></h2>
					<br />
					<table align="center">
						<tr>
							<td>
								<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form);">
									<fieldset>
										<legend>Lister les prestations</legend>
										<p>
											<label>Prestations de</label>
											<select name="mois">
												<option value="01" <?php if($mois=="01") { echo "selected"; } ?>> Janvier </option>
												<option value="02" <?php if($mois=="02") { echo "selected"; } ?>> Février </option>
												<option value="03" <?php if($mois=="03") { echo "selected"; } ?>> Mars </option>
												<option value="04" <?php if($mois=="04") { echo "selected"; } ?>> Avril </option>
												<option value="05" <?php if($mois=="05") { echo "selected"; } ?>> Mai </option>
												<option value="06" <?php if($mois=="06") { echo "selected"; } ?>> Juin </option>
												<option value="07" <?php if($mois=="07") { echo "selected"; } ?>> Juillet </option>
												<option value="08" <?php if($mois=="08") { echo "selected"; } ?>> Août </option>
												<option value="09" <?php if($mois=="09") { echo "selected"; } ?>> Septembre </option>
												<option value="10" <?php if($mois=="10") { echo "selected"; } ?>> Octobre </option>
												<option value="11" <?php if($mois=="11") { echo "selected"; } ?>> Novembre </option>
												<option value="12" <?php if($mois=="12") { echo "selected"; } ?>> Décembre </option>
											</select>
											<select name="annee">
												<?php
													for($i = 2010; $i <= date('Y'); $i++) {
														echo "<option value='$i'";
														if($annee == $i)
															echo " selected";
														echo "> $i </option>";
													}
												?>
											</select>
											&nbsp;
											<input type="submit" name="submit" value="Afficher" class="noprint" />
										</p>
									<?php
										$function = "prestationOtherListing";
										if(checkRight($function)) {
											$query = "SELECT xtr_users.userid, CONCAT(lastname, ', ', firstname) AS name FROM xtr_users, xtr_person WHERE status_in > 0 AND xtr_users.personid = xtr_person.personid ORDER BY name";
											$result_user = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (user, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
									?>
									<p>
										<label>Pour</label>
										<select name="user">
											<option value="default"></option>
										<?php
											while($line = mysql_fetch_array($result_user)){
												echo "<option value='".$line['userid']."'";
												if($userid == $line['userid'])
													echo " selected";
												echo ">".$line['name']."</option>";
											}
										?>
										</select>
										<?php
											}
										?>
									</p>
								</form>
							</td>
						</tr>
					</table>
					<!--  width="600" -->
					<table id="table2" align="center" cellspacing="0" cellpadding="0" class="sort">
						<tr>
							<th width="70" align="center">Date</th>
							<th width="57" align="center">De</th>
							<th width="57" align="center">A</th>
							<th width="64" align="center">Nb Heures</th>
							<th width="60">Description</th>
						</tr>
					<?php
						$count = 0;
						$show = FALSE;
	
						for($jour=01; $jour<=$nbj; ++$jour) {
							$temps = date("Y-m-d", mktime(0, 0, 0, $mois , $jour, $annee));
							if($temps > $line_holiday['holidate_end'])	// while
								$line_holiday = mysql_fetch_array($result_holiday, MYSQL_ASSOC);

							if ((date("D", mktime(0, 0, 0, date($mois) , date($jour), date($annee)))=="Sun") || ($mois == 1 && $jour == 1) || ($mois == 5 && (($jour == 1) || ($jour == 8))) || ($mois == 7 && $jour == 21) || ($mois == 8 && $jour == 15) || ($mois == 9 && $jour == 27) || ($mois == 11 && (($jour == 1) || ($jour == 11))) || ($mois == 12 && $jour == 25)  || (($line_holiday['holidate_begin'] <= $temps) && ($line_holiday['holidate_end'] >= $temps)))
								echo "<tr bgcolor='#C9E7E9' class='sort'>";
							elseif(date("D", mktime(0, 0, 0, date($mois) , date($jour), date($annee)))=="Sat")
								echo "<tr bgcolor='#D5F2F0' class='sort'>";
							else
								echo "<tr bgcolor='#FFFFFF' class='sort'>";

							if($table[$jour][0][0] != "")
								$show=TRUE;
	
							echo "<td align='center' class='sort'>"; /*	height='28' */
							$temps = date("d/m/Y", mktime(0, 0, 0, $mois , $jour, $annee));
							
							if($show) {
								// for($k = 0; $k < $max_prest; ++$k)
								// 	if($table[$jour][5][$k] != "")
								// 		echo "<a href='prestation_detail.php?id=".$line['id']."' class='lien' title='xxxxxx\">";

								// if($line['paid'] == "N")
								// 	echo "<a href='prestation_upd.php?id=".$line['id']."' class='lien' title='Modifier la prestation\">";
							} elseif((intval($mois) >= intval(substr($lastfinished, 5, 2)) && intval($annee) == intval(substr($lastfinished, 0, 4))) || (intval($annee) > intval(substr($lastfinished, 0, 4))))
								echo "<a href='prestation_add.php?date=$temps' class='lien' title='Ajouter une prestation'>";
							
							echo $temps."</a></td>";

							// From hour
							if ($show) {
								for($j = 0; $j < 3; ++$j) {
									echo "<td align='center' class='sort'>";
									for($k=0; $k < $max_prest; ++$k)
										echo $table[$jour][$j][$k]."<br />";
									echo "</td>";
								}
								
								echo "<td align='center' class='sort'>";
								for($k = 0; $k < $max_prest; ++$k) {
									if($table[$jour][3][$k] == "N")
										echo "<a href='prestation_del.php?id=".$table[$jour][4][$k]."' title='Supprimer la prestation'><img src='./design/images/icons/16_delete.png' height='10' width='10' /></a>";
									
									if(strlen($table[$jour][5][$k]) > 0) {
										// echo "<span onmouseover=\"ShowText('$count'); return false;\">&nbsp;<img src='./design/images/icons/16_attention.png' onmouseover=\"ShowText('$count'); return false;\" height='10' width='10' /></span><span id='$count' class='box' onMouseOut=\"HideText('$count'); return false;\" style='display:none'>".$table[$jour][5][$k]."</span>";
										// echo "<a class='tTip' href='#' title='".$table[$jour][5][$k]."'><img src='./design/images/icons/16_attention.png' height='10' width='10' />";
										echo "&nbsp;<a class='tTip' href='#' title='".$table[$jour][5][$k]."'><img src='./design/images/icon_new/16x16/application_error.png' /></a>";
										++$count;
									}
									echo "<br />";
								}
								echo "</td>";
								
								$show = false;
								
							} else
								echo "<td align='center' class='sort'>&nbsp;</td><td align='center' class='sort'>&nbsp;</td><td align='center' class='sort'>&nbsp;</td><td align='center' class='sort'>&nbsp;</td></tr>";
						}
						
						echo "<tr class='sort'><td colspan='4' align='right' class='sort'>Total&nbsp;</td><td align='center' class='sort'>".$sum."h</td></tr>";
						echo "<tr class='sort'><td colspan='4' align='right' class='sort'>Vous devriez recevoir&nbsp;</td><td align='center' class='sort'>".($sum*$reward)."€</td></tr>\n</table>";
					?>
					<br />
					<?php
						$query = "SELECT COUNT(userid)  FROM xtr_prestation WHERE  `date`  REGEXP ('^$time') AND userid = $userid";
						$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (user) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
						$test = mysql_fetch_array($result, MYSQL_NUM);
						$test = $test[0];

						if(((intval($mois) >= intval(substr($lastfinished, 5, 2)) && intval($annee) == intval(substr($lastfinished, 0, 4))) || (intval($annee) > intval(substr($lastfinished, 0, 4)))) && ($test != 0)) {
					?>
					<table align="center"  class="noprint">
						<tr>
							<td>
								<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form);">
									<fieldset>
										<legend>Validation des prestations</legend>
										<p class="noprint">
											<label>Prestations complètes ?</label><input type="hidden" name="mois" value="<?php echo $mois; ?>" /><input type="hidden" name="annee" value="<?php echo $annee; ?>" />
											<input type="submit" name="complete" value="Signaler !" />
										</p>
										<?php
											if(!empty($msg))
												echo "<p align='center' class='goodalert'>$msg</p>";
										?>
									</fieldset>
								</form>
							</td>
						</tr>
					</table>
					<?php

						}

					?>
				</div>
			</div>	
		</div>
	</div>
</div>
	
<?php
	require_once("./footer.php");
?>
</div>
</body>
</html>