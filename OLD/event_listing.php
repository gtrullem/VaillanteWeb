<?php

	session_start();
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	/* rajouter des info-bulle pour le LIEU et le CONTACT */
	$query = "SELECT xtr_event.eventid, xtr_event.title AS stitle, xtr_event.event_type, xtr_event.dbegin, name, CONCAT(lastname, ', ', firstname) AS fullname FROM xtr_event, xtr_person, xtr_place WHERE xtr_event.personid = xtr_person.personid AND xtr_event.placeid = xtr_place.placeid ORDER BY xtr_event.dbegin";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (event) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Liste des évènements :.</title>
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
				<div id="post">
					<!--========================= BEGIN CODE ===========================-->
					<h2><a>Liste des évènements</a></h2>
					<br />
					<table id="table0" align="center" border="0" cellspacing="0" cellpadding="0" class="sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow">
						<thead>
							<tr>
								<th width="225" class="sortable">Titre</th>
								<th width="70" class="sortable">Type</th>
								<th width="200" class="sortable">Lieu</th>
								<th width="55" class="sortable">Date</th>
								<th width="135" class="sortable">Contact</th>
								<th width="35"></th>
							</tr>
						</thead>
						<tbody class="sort">
						<?php
							
							while ($line = mysql_fetch_array($result)) {
								echo "<tr valign=\"middle\"><td class=\"sort\"><a href=\"event_detail.php?id=".$line['eventid']."\">".$line['stitle']."</a></td><td class=\"sort\">".$line['event_type']."</td><td class=\"sort\">".$line['name']."</td><td class=\"sort\" align=\"center\">".substr($line['dbegin'], 8, 2)."-".substr($line['dbegin'], 5, 2)."-".substr($line['dbegin'], 0, 4)."</td><td class=\"sort\">".$line['fullname']."</td><td class=\"sort noprint\"><a href=\"event_update.php?id=".$line['eventid']."\" title=\"Modifier le cours\"><img src=\"./design/images/icons/16_Edit.png\" height=\"10\" width=\"10\" /></a>&nbsp;&nbsp;&nbsp;<a href=\"event_del.php?id=".$line['eventid']."\" title=\"Supprimer le cours\"><img src=\"./design/images/icons/16_delete.png\" height=\"10\" width=\"10\" /></a></td></tr>";
							}
						?>
						</tbody>
					</table>
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