<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "discipline_listing";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Liste des sections :.</title>
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
						<h2><a>Liste des Disciplines</a></h2>
						<table id="table0" align="center" border="0" cellspacing="0" cellpadding="0" class="sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow">
							<thead>
								<tr>
									<th width="200" class="sortable">&nbsp;Nom</th>
									<th width="150" class="sortable">Responsable</th>
									<th colspan="2" class="sortable" align="center">Acronyme</th>
								</tr>
							</thead>
							<tbody class="sort">
							<?php

								require_once("./CLASS/dbdiscipline.class.php");
								$database = new DBDiscipline();

								foreach ($database->getDisciplines() as $discipline)
									echo "<tr><td class=\"sort\"><a href=\"discipline_detail.php?disciplineid=".$discipline->getID()."\">".$discipline->getTitle()."</td><td class=\"sort\"><a href=\"user_detail.php?uid=".$discipline->getResponsable()->getUserID()."\">".$discipline->getResponsable()->getLastName().", ".$discipline->getResponsable()->getFirstName()."</a></td><td class=\"sort\">".$discipline->getAcronym()."</td></tr>";
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
</html>