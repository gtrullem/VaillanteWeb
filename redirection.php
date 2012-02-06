<?php
	
	if(!empty($_GET['err'])) {
		if($_GET['err'] == 1) {
			$err = "<p align=\"center\"><span class=\"important\">Vous n'avez pas les droits nécessaires pour accéder à cette zone.</span><br/><br/><span class=\"important\">Vous serez redirigé(e) dans 5 secondes.</span></p>";
			// need to get referer's referer
			header("Refresh: 5; url=./index.php");
		} else {
			$err = "<p align=\"center\"><span class=\"important\">Aucun(e) ".$_GET['item']." sélectionné(e)</span><br/><br/><span class=\"important\">Vous serez redirigé(e) dans 5 secondes.</span></p>";
			if(!empty($_GET['referrer'])) {
				header("Refresh: 5; url=./".$_GET['referrer']);
			} else {
				header("Refresh: 5; url=./index.php");
			}
		}
	} else {
		$err = "<p align=\"center\"><span class=\"important\">Vous n'êtes pas autorisé(e) à acceder à cette zone sans être connecté(e).</span><br/><br/><span class=\"important\">Vous serez redirigé(e) vers la page de connexion dans 5 secondes.</span></p>";
		$ref = $_SERVER['HTTP_REFERER'];
		header("Refresh: 5; url=cnx.php?url=$ref");
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>.: Redirection :.</TITLE>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
</head>

<body>
<div id="body">

<div id="header"> 
	<div class="container">
		<div align="right">
			<?php
				if(!empty($_SESSION['name'])) {
					echo "Connecté(e) : ".$_SESSION['name']; //." as ".$_SESSION['right'];
				} else {
					echo "Non connecté(e)";
				}
			?>
		</div>
		<div id="header_image"></div>
	</div>
</div>
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
				<H2><a>Redirection</a></H2>
				<?php
					if(!empty($err)) {
						echo $err;
					}
				?>
		</div>
	</div>
</div>
	
<?php
	require_once("./footer.php");
?>
</div>
</body>
</html>