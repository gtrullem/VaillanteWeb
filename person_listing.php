<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < 1) && ($_SESSION['status_out'] < 2)) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Listing des personnes :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" src="./library/tablesort.js"></script>
	<script>
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
			
			var field = document.formulaire.field.value;
			var letter = document.formulaire.letter.value;

			xmlhttp.open("GET","getplist.php?field="+field+"&letter="+letter,true);
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
				<h2><a>Listing des personnes</a></h2>
				<table align="center">
					<tr>
						<td>
							<form class="formulaire" name="formulaire" id="formulaire">
								<fieldset>
									<legend>Lister</legend>
									<p>
										<label>Personnes dont le</label>
										<select name="field" onChange="showList()">
											<option value="lastname" selected>Nom</option>
											<option value="firstname">Prénom</option>
										</select>
										&nbsp; commence par
										<select name="letter" onChange="showList()">
										<?php 
											for ($i=65; $i<=90; $i++)
												echo "<option value\"".chr($i)."\">".chr($i)."</option>"; 
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