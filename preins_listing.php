<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	require_once ("./CONFIG/config.php");

	$query = "SELECT COUNT(personid) FROM xtr_preins WHERE disclist IS NOT NULL AND transfered = 0 AND validate = 'Y'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (pré-inscription) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$validate = mysql_fetch_array($result, MYSQL_NUM);
	$validate = $validate[0];

	$query = "SELECT COUNT(personid) FROM xtr_preins WHERE disclist IS NOT NULL AND transfered = 0 AND validate = 'N'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (pré-inscription) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$notvalidate = mysql_fetch_array($result, MYSQL_NUM);
	$notvalidate = $notvalidate[0];

	$query = "SELECT * FROM xtr_preins WHERE disclist IS NOT NULL AND transfered = 0";	//resp1id IS NOT NULL OR resp2id IS NOT NULL ORDER BY lastname";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (pré-inscription) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Listing des Gymnastes :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" src="./library/tablesort.js"></script>
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
				<h2><a>Listing des Pré-inscriptions</a></h2>
				<br />
				<p align="center">Validées : <?php echo $validate; ?> | Non Validées : <?php echo $notvalidate; ?></p>
				<table align="center" border="0" width="800" id="table1" cellspacing="0" cellpadding="0" class="sort rowstyle-alt colstyle-alt no-arrow">
					<thead>
						<tr>
							<th width="25" align="center" class="sortable">Validé</th>
							<th width="150" class="sortable">&nbsp;Nom</th>
							<th width="150" class="sortable">Prénom</th>
							<th width="75" class="sortable-sortEnglishDateTime">Naissance</th>
							<th width="85" class="sortable">Téléphone</th>
							<th width="85" class="sortable">GSM</th>
							<th width="100" align="center" class="noprint">Discipline(s)</th>
							<!-- <th align="center" class="sortable">Nouveau</th> -->
						</tr>
					</thead>
					<tbody class="sort">
				<?php
					$test = "/^02[0-9]{7}$/";
					while($line = mysql_fetch_array($result)){
						echo "<tr><td class=\"sort\" align=\"center\">".$line['validate']."</td><td class=\"sort\"><a href=\"preins_details.php?id=".$line['personid']."\">".$line['lastname']."</a></td><td class=\"sort\">".$line['firstname']."</td><td class=\"sort\" align=\"center\">".substr($line['birth'], 8, 2)."-".substr($line['birth'], 5, 2)."-".substr($line['birth'], 0, 4)."</td><td class=\"sort\">";
						
						if($line['phone'] != "") {
							if(preg_match($test, $line['phone'])) {
								echo substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
							} else {
								echo substr($line['phone'], 0, 3)."/".substr($line['phone'], 3, 2).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
							}
						}
						echo "</td><td class=\"sort\">";
						
						if($line['gsm'] != "") {
							echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2);
						}
						
						echo "</td><td class=\"sort noprint\" align=\"center\">".$line['disclist']."</td>";
						// $query = "SELECT COUNT(personid) FROM xtr_person WHERE lastname LIKE '".$line['lastname']."' AND firstname LIKE '".$line['']."' "
						echo "</tr>";
					}
				?>
				</table>
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