<?php
	
	session_start();

	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] < 9) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	$query = "SELECT * FROM xtr_userright WHERE scopein = 1 ORDER BY value";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (userright) !<br />".$result_in."<br />".mysql_error(), E_USER_ERROR);
	
	$statusin = array();
	while($line = mysql_fetch_array($result)) {
		$statusin[$line['value']] = $line['label'];
	}
	
	$query = "SELECT * FROM xtr_userright WHERE scopeout = 1 ORDER BY value";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (userright) !<br />".$result_out."<br />".mysql_error(), E_USER_ERROR);
	
	$statusout = array();
	while($line = mysql_fetch_array($result)) {
		$statusout[$line['value']] = $line['label'];
	}
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Attribution des Droits :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
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
		<div id="frame">
			<div id="content">
				<div class="post">
					<h2><a>Résumés des droits</a></h2>
					<div class="entry">
						<table align="center" class="sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow">
							<tr>
								<th class="sortable">Page - Action</th>
								<th class="sortable">Statut Pédagogique</th>
								<th class="sortable">Statut Gestionnaire</th>
							</tr>
							<tr>
								<td colspan="3" class="sort"><font size="3"><br /><b><u>Gestions des Personnes</u></b></font></td>
							</tr>
							<?php
								$query ="SELECT * FROM xtr_functionright WHERE feature = 'Personnes' ORDER BY name, action";
								$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (functionright) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
								
								while($line = mysql_fetch_array($result)) {
									echo "<tr><td class=\"sort\">&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"right_attribute.php?id=".$line['functionrightid']."\" title=\"".$line['explanation']."\">".$line['name']." - ".$line['action']."<a></td>";
									echo "<td class=\"sort\">".$statusin[$line['rightin']]."</td>";
									echo "<td class=\"sort\">".$statusout[$line['rightout']]."</td></tr>";
								}
							?>
							<tr>
								<td colspan="3" class="sort"><font size="3"><br /><b><u>Gestion des Cours</u></b></font></td>
							</tr>
							<?php
								$query ="SELECT * FROM xtr_functionright WHERE feature = 'Cours' ORDER BY name, action";
								$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (functionright) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
								
								while($line = mysql_fetch_array($result)) {
									echo "<tr><td class=\"sort\">&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"right_attribute.php?id=".$line['functionrightid']."\">".$line['name']." - ".$line['action']."<a></td>";
									echo "<td class=\"sort\">".$statusin[$line['statusin']]."</td>";
									echo "<td class=\"sort\">".$statusout[$line['statusout']]."</td></tr>";
								}
							?>
							<tr>
								<td colspan="3" class="sort"><font size="3"><br /><b><u>Gestion Connaissances</u></b></font></td>
							</tr>
							<?php
								$query ="SELECT * FROM xtr_functionright WHERE feature = 'Knowledge' ORDER BY name, action";
								$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (functionright) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
								
								while($line = mysql_fetch_array($result)) {
									echo "<tr><td class=\"sort\">&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"right_attribute.php?id=".$line['functionrightid']."\">".$line['name']." - ".$line['action']."<a></td>";
									echo "<td class=\"sort\">".$statusin[$line['statusin']]."</td>";
									echo "<td class=\"sort\">".$statusout[$line['statusout']]."</td></tr>";
								}
							?>
							<tr>
								<td colspan="3" class="sort"><font size="3"><br /><b><u>Gestion de l'Extranet</u></b></font></td>
							</tr>
							<?php
								$query ="SELECT * FROM xtr_functionright WHERE feature = 'Gestion' ORDER BY name, action";
								$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (functionright) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
								
								while($line = mysql_fetch_array($result)) {
									echo "<tr><td class=\"sort\">&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"right_attribute.php?id=".$line['functionrightid']."\">".$line['name']." - ".$line['action']."<a></td>";
									echo "<td class=\"sort\">".$statusin[$line['statusin']]."</td>";
									echo "<td class=\"sort\">".$statusout[$line['statusout']]."</td></tr>";
								}
							?>
						</table>
						</form>
					</div>
				</div>
			</div>
			<!--------------------------------------------------------------------------------->
			<div id="sidebar" class="sidebar">
				<div>
					<div class="widget widget_categories">
						<h2 class="title">Informations</h2>
						<ul>
							<li>(A VENIR)</li>
						</ul>
					</div>
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