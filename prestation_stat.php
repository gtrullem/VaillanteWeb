<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}

	$pagename = "prestation_stat";
	require_once("./CONFIG/config.php");
		
	if(($_SESSION['status_in'] < 1) && ($_SESSION['status_out'] < 2)) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

	require_once("./CONFIG/var_config.php");
	
	if(isset($_POST['paid'])) {
		$mois = $_POST['mois'];
		$annee = $_POST['annee'];
		$time = $annee."-".$mois;
		$user = $_POST['tabcheck'];

		for($i = 0; $i<sizeof($user); ++$i) {
			$query = "UPDATE xtr_prestation SET paid = 'Y' WHERE date REGEXP('^$time') AND userid = '$user[$i]'";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (prestation) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		}
	}
	
	$where = "N";
	if(isset($_POST['submit'])) {
		$mois = $_POST['mois'];
		$annee = $_POST['annee'];
		$time = $annee."-".$mois;
		if($_POST['action'] == "paid")
			$where = "Y";
	} else {
		if(date('j') < 10)
			$mois = date('m') - 1;
		else
			$mois = date('m');

		$annee = date('Y');
		$time = $annee."-".$mois;
	}

	$query = "SELECT value FROM xtr_config WHERE `key` = 'maxMonthReward'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (config) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result, MYSQL_NUM);
	$maxMonthReward = $line[0];

	$query = "SELECT xtr_prestation.userid, SUM(nbhour), SUM(LENGTH(description)) AS attention, reward, account, CONCAT(lastname, ' ', firstname) AS name, lastfinished FROM xtr_prestation, xtr_users, xtr_person WHERE DATE REGEXP ('^$time') AND xtr_prestation.userid = xtr_users.userid AND xtr_users.personid = xtr_person.personid AND paid ='".$where."' GROUP BY xtr_person.personid ORDER BY xtr_person.lastname, xtr_person.firstname";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (prestation, user, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>.: La Vaillante - Résumé des Prestations :.</TITLE>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
</head>

<body>
	<div id="body">
	
	<?php	require_once("./header.php");	?>
		
	<div id="page" class=" sidebar_right">
		<div class="container">
			<div id="frame2">
				<div id="content">
					<h2><a>Résumé des prestations</a></h2>
					<br />
					<table align="center" class="noprint" >
						<tr>
							<td>
								<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
									<fieldset>
										<legend>Affichage des prestations</legend>
										<p>
											<label>Prestations
											<select name="action">
												<option value="topa">à payer</option>
												<option value="paid" <?php if(!empty($where) && $where == "Y") echo "selected"; ?>>payées</option>
											</select>
											</label>&nbsp;de&nbsp;
											<select name="mois">
												<option value="01" <? if($mois=="01") { echo " selected"; } ?>> janvier </option>
												<option value="02" <? if($mois=="02") { echo " selected"; } ?>> février </option>
												<option value="03" <? if($mois=="03") { echo " selected"; } ?>> mars </option>
												<option value="04" <? if($mois=="04") { echo " selected"; } ?>> avril </option>
												<option value="05" <? if($mois=="05") { echo " selected"; } ?>> mai </option>
												<option value="06" <? if($mois=="06") { echo " selected"; } ?>> juin </option>
												<option value="07" <? if($mois=="07") { echo " selected"; } ?>> juillet </option>
												<option value="08" <? if($mois=="08") { echo " selected"; } ?>> août </option>
												<option value="09" <? if($mois=="09") { echo " selected"; } ?>> septembre </option>
												<option value="10" <? if($mois=="10") { echo " selected"; } ?>> octobre </option>
												<option value="11" <? if($mois=="11") { echo " selected"; } ?>> novembre </option>
												<option value="12" <? if($mois=="12") { echo " selected"; } ?>> décembre </option>
											</select>
											<select name="annee">
												<?php
													for($i = 2010; $i <= date('Y'); $i++) {
														echo "<option value='$i'";
														if($annee == $i) echo " selected";
														echo "> $i </option>";
													}
												?>
											</select>&nbsp;&nbsp;<input type="submit" name="submit" value="Lister">
										</p>
									</fieldset>
								</form>
							</td>
						</tr>
					</table>
					<?php
						if($line = mysql_fetch_array($result)) {
					?>
						<form name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
						<input type="hidden" name="mois" value="<?php echo $mois; ?>">
						<input type="hidden" name="annee" value="<?php echo $annee; ?>">
						<table align="center" border="0" cellpadding="0" cellspacing="0" width="775">
							<tr>
								<?php
									if($where == "N")
										echo "<th colspan='2'>Complète ?</th><th colspan='3'>&nbsp;</th><th colspan='5'>&nbsp;</th>";
									else
										echo "<th class='noprint' colspan='2'>&nbsp;</th><th class='noprint' >&nbsp;</th><th class='noprint'><th class='noprint' >&nbsp;</th><th colspan='2'>&nbsp;</th><th width='75'>&nbsp;Payées&nbsp;?</th>";
								?>
							</tr>
							<?php	
								$i = 0;
								do {
									$i++;
									if(($i%2) == 0)
										echo "<tr bgcolor='#E7F1F7'><td width='60' align='center' class='noprint'>";
									else
										echo "<tr><td width='60' align='center' class='noprint'>";

									
									if($where == "N")
										if((substr($line['lastfinished'], 0, 4) > $annee) || (substr($line['lastfinished'], 5, 2) > $mois))
											echo "Oui</td><td align='center'>&nbsp;";
										else
											echo "Non</td><td align='center'>&nbsp;";

									if(strlen($line['attention']) > 0)
										echo "<a href='prestation_listing.php?personid=".$line['userid']."&time=$time' title='Afficher les prestations' class='noprint'><img src='./design/images/icon_new/16x16/application_error.png' alt='Prestations spécifiques présentes' class='noprint' /></a>&nbsp;";
									
									$monthReward = $line['SUM(nbhour)']*$line['reward'];
									echo "</td><td>&nbsp;<a href='user_detail.php?uid=".$line['userid']."' title='Détails de ".$line['name']."'>".$line['name']."</a></td><td width='50' align='right'>&nbsp;".$line['SUM(nbhour)']."h</td><td class='noprint'>&nbsp;prestées&nbsp;:&nbsp;</td><td align='right'>&nbsp;".$monthReward."€&nbsp;";
									
									if($monthReward > $maxMonthReward) {
										$x2 = (((int)(($monthReward - $maxMonthReward) / 50))*50) + 50;
	   									$x1 = $monthReward - $x2;
										echo "<font size='1'>($x1 € + $x2 €)</font>";
									}
									
									echo "</td><td class='noprint'>&nbsp;";
									
									if($where == "N")
										echo "(N° de compte :</td><td>&nbsp;".$line['account']."</td><td class='noprint'>)</td><td align='center'><input type='checkbox' name='tabcheck[]' value='".$line['userid']."'></td>";
									else
										echo "versés (N° de compte :</td><td>".$line['account']."</td><td class='noprint'>)</td><td align='center'>Oui</td>";
									
									echo "</tr>\n";
								} while($line = mysql_fetch_array($result));
							?>
						</table>
						<?php
							if($where == "N")
								echo "<p align='center'><input type='submit' name='paid' value='Payé !'></p>";
						?>
						</form>
					<?
						} else {
							echo "<p align='center'>Aucun résultats ne correspond à votre requête.</p>";
						}
					?>
				</div>
			</div>	
		</div>
	</div>
</div>
	
<?php	require_once("./footer.php");	?>
</div>
</body>
</html>