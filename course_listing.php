<?php

	require_once("./CONFIG/config.php");
	require_once("./CONFIG/var_config.php");

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$function = "courselisting";
	
	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(date("n") >= "8")	$season = date("Y")."-".(date("Y") + 1);
	else	$season = (date("Y") - 1)."-".date("Y");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Liste des Cours :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" src="./library/tablesort.js"></script>
	<script language="javascript">
		var season = "1";
		
		function showCourse(season)
		{

			if (window.XMLHttpRequest) {		// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} else {							// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
		
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var result = document.getElementById("courselisting"); /* document.getElementById("result"); */
					result.innerHTML=xmlhttp.responseText;
					fdTableSort.init("tableCourse");
					/*
					var x = result.getElementsByTagName("table");
					for(var i = 0; i < x.length ; i++)
						eval(x[i].text);
					*/
				} 
			}

			var season = document.formulaire.season.value;
			xmlhttp.open("GET","getcourselisting.php?season="+season,true);
			xmlhttp.send();
		}
	</script>
	<noscript>
		<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>
	</noscript>
</head>

<body onLoad="javascript:showCourse();">
<div id="body">

<?php
	require_once("./header.php");
?>
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
				<h2><a>Liste des Cours</a></h2>
				<table align="center">
					<tr>
						<td>
							<form name="formulaire" class="formulaire">
								<fieldset>
									<legend>Saison des cours</legend>	
									<p>
										<label>Saison</label>
										<select name="season" onChange="javascript:showCourse()">
										<?php
											$query = "SELECT * FROM xtr_season";
											$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (season) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
											
											while($line = mysql_fetch_array($result)) {
												echo "<option value='".$line['seasonid']."'";
												if($line['seasonlabel'] === $season)
													echo " selected";
												echo ">".$line['seasonlabel']."</option>";
											}
										?>
										</select>
									</p>
							</form>
						</td>
					</tr>
				</table>
				<div id="courselisting"></div>
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