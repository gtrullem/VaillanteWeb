<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$function = "addressbook";
	require_once("./CONFIG/config.php");
		
	if(($_SESSION['status_in'] < $line['rightin']) && ($_SESSION['status_out'] < $line['rightout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(!empty($_GET['search'])) {
		$letter = $_GET['search'];
		$query = " SELECT * FROM vw_contact WHERE lastname LIKE '$letter%' ORDER BY lastname, phone, firstname";
	} else {
		$query = " SELECT * FROM vw_contact ORDER BY lastname, phone, firstname";
	}
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Carnet d'adresse :.</title>
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
				<H2><a>Carnet d'adresse</a></H2>
				<br />
				<TABLE align="center" border="0" width="90%">
					<TR>
						<!--
						<td>
							<SELECT name="choice">
								<OPTION value="lastname">Nom</OPTION>
								<OPTION value="firstname">Prénom</OPTION>
							</SELECT>
						</td>
						-->
						<td width="4%"><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Tous</a></td>
						<?php 
							for ($i=65; $i<=90; $i++) {
								echo "<td width='3%' align='center'><a href='person_listing.php?search=".chr($i)."'>".chr($i)."</a></td>"; 
							} 
						?>
					</TR>
				</TABLE>
				<BR/>
				<TABLE align="center" border="0" id="table1" cellspacing="0" cellpadding="0" class="sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow">
					<thead>
						<tr>
							<th class="sortable" width="175">&nbsp;Nom</th>
							<th class="sortable" width="150">Prénom</th>
							<th class="sortable" width="100">Téléphone</th>
							<th class="sortable" width="100">GSM</th>
							<th width="50">Email</th>
						</TR>
					</thead>
					<tbody class="sort">
				<?php
					$test = "/^02[0-9]{7}$/";
					while($line = mysql_fetch_array($result)){
						echo "<tr><td class='sort'><a href='person_detail.php?personid=".$line['personid']."' title='détails de la personne'>".$line['lastname']."</a></td>";
						echo "<td class='sort'>".$line['firstname']."</td><td class='sort'>";
						
						if($line['phone'] != "")
							if(preg_match($test, $line['phone']))
								echo substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
							else
								echo substr($line['phone'], 0, 3)."/".substr($line['phone'], 3, 2).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
						
						echo "</td><td class='sort'>";
						
						if($line['gsm'] != "")
							echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2)."</td>";
						
						echo "</td><td class='sort' align='center'>";
						
						if($line['email'] != "")
							echo "<a href='mailto:".$line['email']."' title='envoyer un email'><img src='./design/images/icons/16_send_mail.png' alt='envoyer un email' height='10' width='10' /></a>";
						
						echo "</td></tr>";
					}
				?>
				</TABLE>
				<br/>
				<TABLE align="center" border="0" width="90%">
					<TR>
						<!--
						<td>
							<SELECT name="choice">
								<OPTION value="lastname">Nom</OPTION>
								<OPTION value="firstname">Prénom</OPTION>
							</SELECT>
						</td>
						-->
						<td width="4%"><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Tous</a></td>
						<?php 
							for ($i=65; $i<=90; $i++) {
								echo "<td width='3%' align='center'><a href='person_listing.php?search=".chr($i)."'>".chr($i)."</a></td>"; 
							} 
						?>
					</TR>
				</TABLE>
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