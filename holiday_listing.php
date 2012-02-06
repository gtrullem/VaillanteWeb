<?php

	session_start();
	
	$pagename = "holiday_listing";
	require_once("./CONFIG/config.php");

	require_once("./CLASS/dbholiday.class.php");
	$database = new DBHoliday();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Liste des indisponibilités :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" src="./library/tablesort.js"></script>

	<link href="./design/tinyTips.css" rel="stylesheet" type="text/css" media="screen" />

	<script type="text/javascript" src="./library/jquery.min.js"></script>
	<script type="text/javascript" src="./library/jquery.tinyTips.js"></script>

	<script language="javascript">
		function ShowText(id)
		{ 
			document.getElementById(id).style.display = 'block'; 
		}
	
		function HideText(id) { 
			document.getElementById(id).style.display = 'none'; 
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
				<div id="post">
					<h2><a>Liste des indisponibilités</a></h2>
					<br />
					<table id="table0" align="center" border="0" cellspacing="0" cellpadding="0" class="sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow">
						<thead>
							<tr>
								<th width="75" class="sortable">Début</th>
								<th width="75" class="sortable">Fin</th>
								<th width="200" class="sortable">Information</th>
							</tr>
						</thead>
						<tbody class="sort">
						<?php
							foreach($database->getHolidays() as $holiday)	{
								echo "<tr>\n<td class='sort' align='center'>&nbsp;".$holiday->getBeginDate()."</a></td>\n";
								echo "<td class='sort' align='center'>".$holiday->getEndDate()."</td>";
								echo "<td class='sort'><a class='tTip' href='#' title='".$holiday->DisplayPlaces()."'>".$holiday->getInformation()."</a></td></tr>";
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