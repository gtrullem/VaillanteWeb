<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$function = "productlisting";
	require_once("./CONFIG/config.php");

	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Listing du Matériel :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" src="./library/tablesort.js"></script>
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
				<h2><a>Listing du Matériel</a></h2>
				<br />
				<table align="center" border="0" id="table1" cellspacing="0" cellpadding="0" class="sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow">
					<thead>
						<tr>
							<th class=\"sortable\" width=\"225\">&nbsp;Nom</th>
							<th class=\"sortable\" width=\"50\">Quantité</th>
							<th class=\"sortable\" width=\"350\">Détails</th>
						</tr>
					</thead>
					<tbody>
						<?php
							require_once("./CLASS/dbproduct.class.php");
							$database = new DBProduct();
							
							foreach($database->getProducts() as $product)
								echo "<tr><td class=\"sort\"><a href=\"product_del.php?productid=".$product->getID()."\"><img src=\"./design/images/icons/16_delete.png\" height=\"10\" width=\"10\" title=\"Supprimer ce matériel\" /></a>&nbsp;&nbsp;<a href=\"product_update.php?productid=".$product->getId()."\">".$product->getName()."</a></td><td class=\"sort\">".$product->getQuantity()."</td><td class=\"sort\">".$product->getDetail()."</td></tr>";
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