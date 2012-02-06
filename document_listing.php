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
	
	$query = "SELECT D.documentid, D.title, D.summary, D.author, D.publisher, D.keyword, CONCAT( P.lastname,  ', ', P.firstname ) AS name FROM xtr_document AS D, xtr_users AS U, xtr_person AS P WHERE D.userid = U.userid AND U.personid = P.personid";
	
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (document, user, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>.: Listing des Documents :.</TITLE>
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
					<h2><a>Listing des Documents</a></h2>
					<br />
					<table align="center" border="0" id="table1" cellspacing="0" cellpadding="0" class="sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow">
						<thead>
							<tr>
								<th width="275" class="sortable"><b>Titre</b></Th>
								<th width="200" class="sortable">Auteur(s)</Th>
								<th width="200" class="sortable">Edition</Th>
								<th width="125" class="sortable">Possesseur</Th>
							</tr>
						</thead>
						<tbody class="sort">
						<?php	
							while($line = mysql_fetch_array($result)) {	
								echo "<tr><td class=\"sort\"><a href=\"document_detail.php?id=".$line['documentid']."\" title=\"".substr($line['summary'], 0, 97)."...\">".$line['title']."</a></td><td class=\"sort\">";
								
								if(strlen($line['author']) >= 50) {
									echo substr($line['author'], 0, 47)."...";
								} else {
									echo $line['author'];
								}
								
								echo "</td><td class=\"sort\">".$line['publisher']."</td>";
								echo "<td class=\"sort\">".$line['name']."</td></tr>";
							}
						?>
						</tbody>
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