<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] <= 8) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
//	if(!empty($_GET['search'])) {
//		$letter = $_GET['search'];
//		$query = " SELECT * FROM xtr_person WHERE lastname LIKE '$letter%' ORDER BY lastname, phone, firstname";
//	} else {
//		$query = " SELECT * FROM xtr_person ORDER BY lastname, phone, firstname";
//	}

	$query = "SELECT * FROM xtr_userright ORDER BY value";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (userright) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>.: Listing des droits :.</TITLE>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" src="./library/tablesort.js"></script>	<noscript>		<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>	</noscript>
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
				<h2><a>Listing des Droits</a></h2>
				<br />
				<table align="center" border="0" id="table1" cellspacing="0" cellpadding="0" class="sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow">
					<thead>
						<tr>
							<th class="sortable" width="25">ID</th>
							<th class="sortable" width="50">Groupe</th>
							<th class="sortable" width="175">Titre</th>
							<th class="sortable" width="25">Out</th>
							<th class="sortable" width="25">In</th>
						</tr>
					</thead>
					<tbody class="sort">
				<?php
					while($line = mysql_fetch_array($result)){
						echo "<tr><td class=\"sort\">".$line['userrightid']."</td>";
						echo "<td class=\"sort\">".$line['value']."</td>";						
						echo "<td class=\"sort\">".$line['label']."</td>";
						echo "<td class=\"sort\" align=\"center\">";
						if($line['scopeout'] == 1) { echo "Oui"; } else { echo "Non"; }
						echo "</td><td class=\"sort\" align=\"center\">";
						if($line['scopein'] == 1) { echo "Oui"; } else { echo "Non"; }
						echo "</td></tr>";
					}
				?>
				</table>
				<br />
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