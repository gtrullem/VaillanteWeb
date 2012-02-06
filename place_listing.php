<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < 1) && ($_SESSION['status_out'] < 1)) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	require_once("./CLASS/dbplace.class.php");
	$database = new DBPlace();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Liste des Lieux :.</title>
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
			<!-- ========================= BEGIN FORM ====================== -->
			<H2><a>Liste des lieux</a></H2>
				<table align="center" border="0" width="50%" id="table1" cellspacing="0" cellpadding="0" class="sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow">
					<thead>
							<tr>
								<th width="200" class="sortable">Nom</th>
								<th width="300" class="sortable">Adresse</th>
								<th width="100" class="sortable">Pays</th>
								<th align="right" class="sortable"># Km</th>
							</tr>
						</thead>
						<tbody class="sort">
						<?php
							foreach($database->getPlaces() as $place)	{
								echo "<tr><td class='sort'>&nbsp;";	
								echo "<a href='place_detail.php?placeid=".$place->getID()."'>";
								echo $place->getName()."</a></td>";
								echo "<td class='sort'>".$place->getAddress()." - ".$place->getPostal()." ".$place->getCity()."</td>";
								echo "<td class='sort'>".$place->getCountry()."</td>";
								echo "<td align='right' class='sort'>".$place->getNbKm()."</td></tr>";
							}
						?>
					</tbody>
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
</HTML>