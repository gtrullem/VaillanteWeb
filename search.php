<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	require_once("./CONFIG/config.php");
	
	if(isset($_POST['search'])) {
		$keyword = mysql_real_escape_string(stripslashes($_POST['keyword']));
		
		if(!empty($_POST['relation'])) {
			// p1.personid, p1.firstname, p1.lastname, p1.address, p1.city, p1.phone, p1.gsm
//			$query = "SELECT p1.personid, p1.firstname, p1.lastname, p1.address, p1.city, p1.phone, p1.gsm FROM xtr_person p1 LEFT JOIN xtr_relationship r ON p1.personid = r.personid LEFT JOIN xtr_person p2 ON r.personid1 = p2.personid WHERE p1.lastname LIKE '%$keyword%' OR p1.firstname LIKE '%$keyword%' OR p1.address LIKE '%$keyword%' OR p1.city LIKE '%$keyword%' OR p1.gsm LIKE '%$keyword%' OR p1.phone LIKE '%$keyword%' OR p2.lastname LIKE '%$keyword%' OR p2.firstname LIKE '%$keyword%' OR p2.address LIKE '%$keyword%' OR p2.city LIKE '%$keyword%' OR p2.gsm LIKE '%$keyword%' OR p2.phone LIKE '%$keyword%' GROUP BY p1.personid;";
			$query = "SELECT p1.personid, p1.firstname, p1.lastname, p1.address, p1.city, p1.phone, p1.gsm FROM xtr_person p1 LEFT JOIN xtr_relationship r ON p1.personid = r.personid LEFT JOIN xtr_person p2 ON r.personid1 = p2.personid WHERE p1.lastname LIKE '%$keyword%' OR p1.firstname LIKE '%$keyword%' OR p1.address LIKE '%$keyword%' OR p1.city LIKE '%$keyword%' OR p1.gsm LIKE '%$keyword%' OR p1.phone LIKE '%$keyword%' OR p2.lastname LIKE '%$keyword%' OR p2.firstname LIKE '%$keyword%' OR p2.address LIKE '%$keyword%' OR p2.city LIKE '%$keyword%' OR p2.gsm LIKE '%$keyword%' OR p2.phone LIKE '%$keyword%' GROUP BY p1.personid UNION SELECT p1.personid, p1.firstname, p1.lastname, p1.address, p1.city, p1.phone, p1.gsm FROM xtr_person p1 LEFT JOIN xtr_relationship r ON p1.personid = r.personid1 LEFT JOIN xtr_person p2 ON r.personid = p2.personid WHERE p1.lastname LIKE '%$keyword%'OR p1.firstname LIKE '%$keyword%' OR p1.address LIKE '%$keyword%' OR p1.city LIKE '%$keyword%' OR p1.gsm LIKE '%$keyword%' OR p1.phone LIKE '%$keyword%' OR p2.lastname LIKE '%$keyword%' OR p2.firstname LIKE '%$keyword%' OR p2.address LIKE '%$keyword%' OR p2.city LIKE '%$keyword%' OR p2.gsm LIKE '%$keyword%' OR p2.phone LIKE '%$keyword%' GROUP BY p2.personid";
		} else {
			$query = "SELECT * FROM xtr_person WHERE lastname LIKE '%$keyword%' OR firstname LIKE '%$keyword%' OR address LIKE '%$keyword%' OR city LIKE '%$keyword%' OR gsm LIKE '%$keyword%' OR phone LIKE '%$keyword%'";
		}
//		echo $query;
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person, relation) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Recherche :.</title>
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
				<H2><A>Recherche</A></h2>
				<table align="center">
					<tr>
						<td>
							<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
								<fieldset>
									<legend>Informations de la recherche</legend>
									<p>
										<label>Mot clé</label>
										<input type="text" name="keyword" value="">
									</p>
									<p>
										<label>Recherche par relation</label>
										<input type="checkbox" name="relation">
									</p>
									<p align="center"><input type="submit" name="search" value="Rechercher"></p>
								</fieldset>
							</form>
						</td>
					</tr>
				</table>
				<br />
				<?php
					if(isset($result)) {
				?>
					<table cellpadding="0" cellspacing="0" border="0" align="center" width="725" class="sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow">
						<thead>
							<tr>
								<th class="sortable">nom</th>
								<th class="sortable">prénom</th>
								<th class="sortable">addresse</th>
								<th class="sortable">ville</th>
								<th width="110" class="sortable">gsm</th>
								<th width="100" class="sortable">téléphone</th>
							</tr>
						</thead>
						<tbody class="sort">
						<?php
							$test = "/^02[0-9]{7}$/";
							while($line = mysql_fetch_array($result)) {
								echo "<tr><td class='sort'><a href='./person_detail.php?personid=".$line['personid']."'>".$line['lastname']."</a>&nbsp;</td>";
								echo "<td class='sort'><a href='./person_detail.php?personid=".$line['personid']."'>".$line['firstname']."</a>&nbsp;</td>";
								echo "<td class='sort'>".$line['address']."&nbsp;</td>";
								echo "<td class='sort'>".$line['city']."&nbsp;</td>";
								
								echo "<td class='sort'>";
								if($line['gsm'] != "")
									echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2);
								
								echo "</td><td class='sort'>";
								if($line['phone'] != "")
									if(preg_match($test, $line['phone']))
										echo substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
									else
										echo substr($line['phone'], 0, 3)."/".substr($line['phone'], 3, 2).".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2);
								echo "</td></tr>";
							}
						?>
					</TABLE>
				<?php
					}
				?>
				<!-- ========================= END FORM ====================== -->
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