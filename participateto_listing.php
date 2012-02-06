<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < 1) && ($_SESSION['status_out'] < 2)) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(empty($_GET['id'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=évènement&referrer=event_listing.php");
		exit;
	}
	
	$id = $_GET['id'];
	
//	$query = " SELECT * FROM xtr_event WHERE eventid='$id'";
//	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (event) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
//	$line = mysql_fetch_array($result);
//	
//	$begindate = explode("-", $line['dbegin']);
//	$begindate = $begindate[2]."/".$begindate[1]."/".$begindate[0];
//	
//	$enddate = explode("-", $line['dend']);
//	$enddate = $enddate[2]."/".$enddate[1]."/".$enddate[0];
	
	$query ="SELECT lastname, firstname, gsm, phone, email FROM xtr_participateto, xtr_person WHERE xtr_participateto.eventid = $id AND xtr_participateto.personid = xtr_person.personid";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (event) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Listing des Gymnastes :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" src="./library/tablesort.js"></script>
	<script language="javascript">
		function checkForm(formulaire)
		{

			if(document.formulaire.year.value == "default") {
				alert('Veuillez choisir l\'année.');
				return false;
			}
			
			if(document.formulaire.category.value == "default") {
				alert('Veuillez choisir la section.');
				return false;
			}

			return true;
		}
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
				<h2><a>Gymnastes participant</a></h2>
				<br />
				<table align="center" border="0" id="table1" cellspacing="0" cellpadding="0" class="sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow">
						<thead>
							<tr>
								<th width="150" class="sortable">&nbsp;Nom</th>
								<th width="100" class="sortable">Prénom</th>
								<th width="100" class="sortable">Téléphone</th>
								<th width="115" class="sortable">GSM</th>
								<th width="50" align="center" class="noprint">Email</th>
							</tr>
						</thead>
						<tbody class="sort">
						<?php
							$test = "/^02[0-9]{7}$/";
							while($line = mysql_fetch_array($result)){
								echo "<tr><td class='sort'><a href='affiliate_detail.php?personid=".$line['personid']."'>".$line['lastname']."</a></td>";
								echo "<td class='sort'>".$line['firstname']."</td><td class='sort'>";
								
								if($line['phone'] != "") {
									if(preg_match($test, $line['phone'])) {
										echo substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3);
									} else {
										echo substr($line['phone'], 0, 3)."/".substr($line['phone'], 3, 2);
									}
									echo ".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
								}
								echo "</td><td class='sort'>";
								
								if($line['gsm'] != "") {
									echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2);
								}
								
								echo "</td><td class='sort noprint' align='center'>";
								
								if($line['email'] != "") {
									echo "<a href='mailto:".$line['email']."' title='envoyer un email'><img src='./design/images/icons/16_send_mail.png' alt='envoyer un email' /></a>";
								}
								
								echo "</td></tr>";
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