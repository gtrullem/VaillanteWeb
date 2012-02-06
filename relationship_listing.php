<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] < 1) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

	// LEFT JOIN CHECKED
	$query = " SELECT type, P.personid AS aid, CONCAT(P.lastname,', ',P.firstname) AS namechild, B.personid AS bid, CONCAT(B.lastname,', ',B.firstname) AS namep FROM xtr_relationship LEFT JOIN xtr_person AS P ON xtr_relationship.personid = P.personid LEFT JOIN xtr_person AS B ON xtr_relationship.personid1 = B.personid WHERE xtr_relationship.responsable = 'Y' ORDER BY namechild"; 
//	echo $query;
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (relationship, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>.: Listing des Relations :.</TITLE>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
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
				<H2><a>Liste des Liens</a></H2>
				<br/>
				<TABLE align="center" border="0" width="85%" id="table1" cellspacing="0" cellpadding="0">
					<TR bgcolor="#9DD4FB">
						<TD>&nbsp;Affilié(e)</TD>
						<TD>Responsable 1</TD>
						<TD>Responsable 2</TD>
					</TR>
				<?php
					$i = 0;
					$save = "";
					while($ligne = mysql_fetch_array($result)){
						if($save != $ligne['aid']) {
							if(!empty($save)) {
								echo "<td></td></tr>";
							}
							$save = $ligne['aid'];
							
							$i++;
							if(($i%2) == 0) {
								echo "<TR bgcolor=\"#E7F1F7\">";
							} else {
								echo "<TR>";
							}
											
							echo "<TD><a href=\"person_update.php?id=".$ligne['aid']."\">".$ligne['namechild']."</A></TD>";
							echo "<TD><a href=\"person_update.php?id=".$ligne['bid']."\">".$ligne['namep']."</A>";
							if(($ligne['type'] != NULL) && ($ligne['type'] != "")) {
								echo "&nbsp;<font size=\"2\">(".$ligne['type'].")</font>";
							}
							echo "</TD>";
						} else {
							echo "<td><a href=\"person_update.php?id=".$ligne['bid']."\">".$ligne['namep']."</A>";
							if(($ligne['type'] != NULL) && ($ligne['type'] != "")) {
								echo "&nbsp;<font size=\"2\">(".$ligne['type'].")</font>";
							}
							echo "</TD></TR>";
						}
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