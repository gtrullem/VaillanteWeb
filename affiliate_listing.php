<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$function = "affiliatelisting";
	require_once("./CONFIG/config.php");

	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(date("n") >= "8")	$season = date("Y")."-".(date("Y") + 1);
	else	$season = (date("Y") - 1)."-".date("Y");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Listing des Gymnastes :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" src="./library/tablesort.js"></script>
	<script language="javascript">
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
		
		function showList()
		{
			if (window.XMLHttpRequest) {		// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} else {							// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
		
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
					document.getElementById("result").innerHTML=xmlhttp.responseText;
			}
			
			var seasonid = document.formulaire.seasonid.value;
			var value = document.formulaire.value.value;

			xmlhttp.open("GET","getglist.php?seasonid="+seasonid+"&type=discipline&value="+value,true);
			xmlhttp.send();
		}
	</script>
	<noscript>
		<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>
	</noscript>
</head>

<body onLoad="javascript:showList();">
<div id="body">

<?php
	require_once("./header.php");
?>
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
				<h2><a>Listing des Gymnastes</a></h2>
				<table align="center">
					<tr>
						<td>
							<form class="formulaire" name="formulaire" id="formulaire">
								<fieldset>
									<legend>Informations de la recherche</legend>
									<p>
										<label>Tous les affiliés de</label>
										<select name="seasonid" onChange="showList()">
											<?php
												$query = "SELECT * FROM xtr_season ORDER BY seasonlabel";
												$resultyear = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (affiliate) !<br />$query<br />$resultyear<br />".mysql_error(), e_user_error);
											
												while($line = mysql_fetch_array($resultyear)) {
													echo "<option value='".$line['seasonid']."'";
													if($season === $line['seasonlabel']) echo " selected";
													echo ">".$line['seasonlabel']."</option>";
												}
											?>
										</select>
									</p>
									<p>
										<label>de la section</label>
										<?php
											$query = "SELECT * FROM xtr_discipline ORDER BY TITLE";
											$resultyear = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$resultyear."<br />".mysql_error(), E_USER_ERROR);
										?>
										<select name="value" onChange="showList()">
											<!--	<option value="all"> Toutes </option>	-->
											<?php
												while($line = mysql_fetch_array($resultyear))
													echo "<option value=\"".$line['disciplineid']."\">".$line['title']."</option>";
											?>
										</select>
									</p>
								</fieldset>
							</form>
						</td>
					</tr>
				</table>
				<div id="result">
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