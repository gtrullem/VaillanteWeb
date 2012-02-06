<?php
	
	session_start();
	
	if(!isset($_GET['id_stage'])) {
		header("Location: ./index.php");
		exit();
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	$id = $_GET['id_stage'];
	$query = "SELECT title, textbody FROM xtr_stage WHERE id=$id";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (stage) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$ligne = mysql_fetch_array($result);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Fred's birthday - Accueil :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
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
			<p><b><u> Inscription :</u></b></p>
			</div>
			<h2><?php echo $ligne['title']; ?></h2>
			<?php echo $ligne['textbody']; ?><br/>
			<br/>
			Je soussigné  <input type="text" name="name" value="" size="50" maxlenght="70"> inscris ma fille/mon fils au stage <i><u><?php echo $ligne['title']; ?></u></i> le(s)/la<br/>
			<TABLE align="center">
				<TR>
					<TD>
						<input type="checkbox" name="option1" value="Lundi"> Lundi<br />
						<input type="checkbox" name="option2" value="Mardi"> Mardi<br />
						<input type="checkbox" name="option3" value="Mercredi"> Mercredi<br />
						<input type="checkbox" name="option4" value="Jeudi"> Jeundi<br />
						<input type="checkbox" name="option5" value="Vendredi"> Vendredi<br />
						( Sélectionnez les jours)<br/>
					</TD>
					<TD>
						ou
					</TD>
					<TD> 						<input type="checkbox" name="option0" value="scomplete"> Semaine compète<br/>
					</TD>
				</TR>
			</TABLE>			NOM et PRENOM de l’enfant : <input type="text" name="namechild" value="" maxlenght="70"><br />			Téléphone : <input type="text" name="phone" value="0" maxlenght="10"><br/>
			GSM : 	<input type="text" name="phone" value="04" maxlenght="12"><br/>
			<br/>
			<p align="center"><input type="submit" name="inscrire" value="Inscrire"></p>	
		</div>
	</div>
</div>
	
<?php
	require_once("./footer.php");
?>
</div>
</body>
</html>