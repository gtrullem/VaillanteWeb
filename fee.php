<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$function = "feecomputing";
	require_once("./CONFIG/config.php");

	if(($_SESSION['status_in'] < $line['rightin']) && ($_SESSION['status_out'] < $line['rightout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(!empty($_POST['search'])) {
		$season = $_POST['season'];
		if($_POST['discipline'] != "all") {
			$section = $_POST['discipline'];
			$endquery = " AND xtr_discipline.disciplineid = $section"; /***** A FINIR *****/
		}
	} else
		if(date("n") >= "8")
			$season = date("Y")."-".(date("Y") + 1);
		else
			$season = (date("Y") - 1)."-".date("Y");
	
	$price_gym = array();
	$price_tra = array();
	$query = "SELECT * FROM xtr_price";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (price) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	while($line = mysql_fetch_array($result, MYSQL_ASSOC))
		if($line['discipline'] == "GYM")
			$price_gym[$line['nbhour']] = $line['price'];
		else
			$price_tra[$line['nbhour']] = $line['price'];
	
	// $query = "SELECT xtr_person.personid, CONCAT(lastname, ', ', firstname) AS name, SUM(nbhour) AS nbhour, tarification FROM xtr_person, xtr_isaffiliate, xtr_linkCourseSeason, xtr_course WHERE xtr_isaffiliate.personid = xtr_person.personid AND xtr_isaffiliate.lcsid = xtr_linkCourseSeason.lcsid AND xtr_linkCourseSeason.courseid = xtr_course.courseid AND paid = 'N' AND GROUP BY name, tarification";
	$query = "SELECT xtr_person.personid, CONCAT( lastname,  ', ', firstname ) AS name, SUM( nbhour ) AS nbhour, tarification FROM xtr_person, xtr_isaffiliate, xtr_linkCourseSeason, xtr_course WHERE xtr_isaffiliate.personid = xtr_person.personid AND xtr_isaffiliate.lcsid = xtr_linkCourseSeason.lcsid AND xtr_linkCourseSeason.courseid = xtr_course.courseid AND xtr_isaffiliate.paid = 'N' AND xtr_linkCourseSeason.seasonid = 2 GROUP BY name, xtr_course.tarification";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (AFFILIATES/PERSONS) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>.: La Vaillante - Calcul des Cotisations :.</TITLE>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" src="./library/tablesort.js"></script>
	<SCRIPT language="javascript">
		function checkForm(formulaire)
		{

			if(document.formulaire.year.value == "default") {
				alert('Veuillez choisir l\'année.');
				return false;
			}
			
			if(document.formulaire.category.value == "default") {
				alert('Veuillez choisir la section.');
				return false;
			}

			return true;
		}
		

		function computeFee(id)
		{
			if (window.XMLHttpRequest) {		// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} else {							// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("price"+id).style.visibility="hidden";
					document.getElementById("submit"+id).style.visibility="hidden";
					document.getElementById("result"+id).innerHTML=xmlhttp.responseText;
				}
			}
			
			xmlhttp.open("GET","setFee.php?personid="+id+"&seasonid="+document.getElementById("seasonid"+id)+"&fee="+document.getElementById("price"+id).value,true);
			xmlhttp.send();
		}
	</script>
	<noscript>
		<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>
	</noscript>
</HEAD>

<body>
<div id="body">

<?php
	require_once("./header.php");
?>
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
				<h2><a>Calcul des Cotisations</a></h2>
				<br />
				<!--
				<table align="center">
					<tr>
						<td>
							<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
								<fieldset>
									<legend>Informations de la recherche</legend>
									<p>
										<label>Tous les affiliés de</label>
										<select name="season">
											<option value="default"></option>
											<?php
												$query = "SELECT * FROM xtr_season";
												$resultyear = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (season) !<br />$query<br />$resultyear<br />".mysql_error(), e_user_error);
											
												while($line = mysql_fetch_array($resultyear, MYSQL_ASSOC)) {
													echo "<option value='".$line['seasonid']."'";
													if($line['seasonlabel'] === $season)
														echo " selected";
													echo ">".$line['seasonlabel']."</option>";
												}
											?>
										</select>
									</p>
									<!--
									<p>
										<label>de la section</label>
										<?php
											$query = "SELECT * FROM xtr_discipline ORDER BY TITLE";
											$resultyear = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$resultyear."<br />".mysql_error(), E_USER_ERROR);
										?>
										<select name="discipline">
											<option value="default"></option>
											<option value="all" <?php if(empty($endquery)) echo "selected"; ?>> Toutes </option>
											<?php
												while($line = mysql_fetch_array($resultyear, MYSQL_ASSOC)) {
													echo "<option value='".$line['disciplineid']."'";
													if($line['disciplineid'] == $section)
														echo " selected";
													echo ">".$line['title']."</option>";
												}
											?>
										</select>
									</p>
									
									<p align="center"><input type="submit" name="search" value="Rechercher"></p>
								</fieldset>
							</form>
						</td>
					</tr>
				</table>
				-->
				<table align="center" border="0" width="800" id="table1" cellspacing="0" cellpadding="0" class="sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow">
						<thead>
							<tr>
								<th width="150" class="sortable">&nbsp;Nom</th>
								<th width="85" class="sortable">Type</th>
								<th width="85" class="sortable"># Heures</th>
								<th width="75" class="sort">Prix</th>
							</tr>
						</thead>
						<tbody class="sort">
						<?php
							$total = 0;
							while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
								echo "<tr><td class='sort'><a href='person_detail.php?personid=".$line['personid']."'>".$line['name']."</a></td><td class='sort' align='center'>".$line['tarification']."</td><td align='right' class='sort'>".$line['nbhour']."</td><td align='right' class='sort'><div id='result".$line['personid']."'><input type='text' name='price".$line['personid']."' id='price".$line['personid']."' size='3' value='";
								if($line['tarification'] == "GYM") {
									$total += $price_gym[$line['nbhour']];
									echo $price_gym[$line['nbhour']];
								} else {
									$total += $price_tra[$line['nbhour']];
									echo $price_tra[$line['nbhour']];
								}
								echo "' /><input type='submit' name='submit".$line['personid']."' id='submit".$line['personid']."' value='OK' onClick='computeFee(".$line['personid'].")'/></div></td></tr>";
							}
						?>
						</tbody>
				</table>
				<table align="center" border="0" id="table2" cellspacing="0" cellpadding="0" class="sort">
					<tr><th width="390" align="right" class="sort">Total :</th><th width="54" class="sort" align="right"><?php echo $total; ?>€</th></tr>
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