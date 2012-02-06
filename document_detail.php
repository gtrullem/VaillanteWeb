<?php
	
	session_start();

	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	if(empty($_GET['id'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=document&referrer=document_listing.php");
		exit;
	}
	
	$id = $_GET['id'];
	require_once("./CONFIG/config.php");
	
	$query = "SELECT D.title, D.summary, D.keyword, D.available, D.author, D.publisher, CONCAT( P.lastname,  ', ', P.firstname ) AS name FROM xtr_document AS D, xtr_users AS U, xtr_person AS P WHERE D.userid = U.userid AND U.personid = P.personid AND D.documentid = '$id'";
	
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Détails d'un document :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" language="javascript">
    function checkForm(formulaire)
    {
      if(document.formulaire.title.value.length < 12) {
        alert('Veuillez choisir un titre d\'au moins 12 caractères.');
        document.formulaire.title.focus();
        return false;
      }
      
      if(document.formulaire.textbody.length < 160) {
        alert('Veuillez composer un message d\'au moins 160 caractères.');
        document.formulaire.textbody.focus();
        return false;
      }
      
      return true;
    }
  </script>
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
				<div class="post">
					<h2><a>Détails d'un document</a></h2>
					<div class="entry">
						<table align="center">
							<tr>
								<td>
								<form class="formulaire">
									<fieldset>
										<legend>Détail du document</legend>
										<p>
											<label>Titre</label>
											<?php echo $line['title']; ?>
										</p>
										<p>
											<label>Auteur(s)</label>
											<?php echo $line['author']; ?>
										</p>
										<p>
											<label>Edition</label>
											<?php echo $line['publisher']; ?>
										</p>
										<p>
											<label>Résumé</label>
											<textarea  rows="10" cols="56" readonly><?php echo $line['summary']; ?></textarea>
										</p>
										<p>
										  <table>
											<tr>
												<td><label>Discipline(s)</label></td>
												<?php
													$query ="SELECT title FROM xtr_docisfordisc, xtr_discipline WHERE xtr_docisfordisc.documentid = '$id' AND xtr_docisfordisc.disciplineid = xtr_discipline.disciplineid;";
													$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
													if($line2 = mysql_fetch_array($result)) {
														echo "<td>".$line2['title']."</td>";
													} else {
														echo "<td>(Aucune)</td>";
													}
													
													while($line2 = mysql_fetch_array($result)) {
														echo "<tr><td>&nbsp;</td><td>".$line2['title']."</td></tr>";
													}
												?>
										</table>
										</p>
										<!--
										<p>
										  <label>Mots Clé</label>
										  <TEXTAREA name="textbody" rows="10" cols="60"></TEXTAREA>
										</p>
										-->
										<p>
											<label>Propriétaire</label>
											<?php echo $line['name']; ?>
										</p>
										<p>
											<label>Disponible</label>
											<input type="checkbox" id="available" name="available" <?php if($line['available'] == "Y") { echo "checked"; } ?> disable />
										</p>
										<?php
											if($_SESSION['status_out'] >= 3) {
												echo "<p align=\"right\"><a href=\"document_update.php?id=$id\"><img src=\"./design/images/icons/16_Edit.png\" /></a></p>";
											}
										?>
										</fieldset>
									</form>
								</td>
							</tr>
						</table>
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