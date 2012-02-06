<?php
	
	session_start();

	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	require_once ("./CONFIG/config.php");
	
	if(isset($_POST['submit'])) {
		$title = mysql_real_escape_string(stripslashes(trim($_POST['title'])));
		$author = mysql_real_escape_string(stripslashes(trim($_POST['author'])));
		$publisher = mysql_real_escape_string(stripslashes(trim($_POST['publisher'])));
		$summary = mysql_real_escape_string(stripslashes(trim($_POST['summary'])));
		$userid = $_SESSION['uid'];
		$disciplineid = $_POST['disciplineid'];
    
		if(!empty($_POST['available'])) {
			$available = "Y";
		} else {
			$avaible = "N";
		}
		
		$query = "INSERT INTO xtr_document (title, author, publisher, summary, userid, available) VALUES ('$title', '$author', '$publisher', '$summary', '$userid', '$available')";

		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (document) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$query = "SELECT LAST_INSERT_ID();";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GET LAST ID !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		$documentid = mysql_fetch_array($result);
		$documentid = $documentid[0];

		for($i=0; $i<sizeof($disciplineid); $i++) {
			$query = "INSERT INTO xtr_docisfordisc (documentid, disciplineid) VALUES ('$documentid', '$disciplineid[$i]');";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : GET LAST ID !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		}
		
		header("Location: ./document_listing.php");
		exit;
	}
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Ajout d'un Document :.</title>
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
      
      if(document.formulaire.author.value.length < 5) {
        alert('Veuillez choisir un titre d\'au moins 12 caractères.');
        document.formulaire.title.focus();
        return false;
      }
      
      if(document.formulaire.publisher.value.length < 8) {
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
    
    function transmit()
	{
		if(document.formulaire.discipline_all.checked)
			for(var i=0; i <= document.formulaire.disciplineid.length; i++)
				document.formulaire.disciplineid[i].checked = true;
		else
			for(var i=0; i <= document.formulaire.disciplineid.length; i++)
				document.formulaire.disciplineid[i].checked = false;
	}
	
	function checkAll()
	{
		for(var i=0; i <= document.formulaire.disciplineid.length; i++) {
			if(document.formulaire.disciplineid[i].checked == false)
				document.formulaire.discipline_all.checked = false;
		}
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
		<div id="frame">
			<div id="content">
				<div class="post">
					<h2><a>Ajout d'un document</a></h2>
					<div class="entry">
						<table align="center">
							<tr>
								<td>
									<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
										<fieldset>
											<legend>Information sur le document</legend>
											<p>
												<label>Titre *</label>
												<input type="text" name="title" size="46" maxlength="160">
											</p>
											<p>
												<label>Auteur(s) *</label>
												<input type="text" name="author" size="46" maxlength="100">
											</p>
											<p>
												<label>Edition *</label>
												<input type="text" name="publisher" size="46" maxlength="50">
											</p>
											<p>
												<label>Résumé *</label>
												<textarea name="summary" rows="10" cols="56"></textarea>
											</p>
											<p>
												<table>
													<tr>
														<td><label>Section(s) concernée(s)</label></td>
														<td><input type="checkbox" name="discipline_all" onClick="transmit()" /> Toutes</td>
													</tr>
													<?php
													
														$query ="SELECT disciplineid, title FROM xtr_discipline WHERE enable = 'Y'";
														$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
														
														while($line = mysql_fetch_array($result)) {
															echo "<tr><td>&nbsp;</td><td><input type=\"checkbox\" name=\"disciplineid\" value=\"".$line['disciplineid']."\" onClick=\"checkAll()\" />".$line['title']."</td></tr>";
														}
													?>
												</table>
											</p>
											<!--
											<p>
											  <label>Mots Clé</label>
											  <textarea name="textbody" rows="10" cols="60"></textarea>
											</p>
											-->
											<p>
												<label>Propriétaire *</label>
												<?php echo $_SESSION['name']; ?>
											</p>
											<p>
												<label>Disponible</label>
												<input type="checkbox" id="available" name="available" checked/>
											</p>
											<br />
											<p align="center"><input type="submit" name="submit" value="Ajouter"></p>
										</fieldset>
									</form>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div id="sidebar" class="sidebar">
				<div>
					<div class="widget widget_categories">
						<h2 class="title">Informations</h2>
						<p align="justify">Les champs signalés d'une étoile (*) sont obligatoires.<br /><br />Le titre doit être composé de 12 caractères minimum.<br /><br />L'auteur doit être composé de 5 caractères minimum.<br /><br />L'édition doit avoir 8 caractère minimum.<br /><br />Le résumé doit être composé de 160 caractères minimum.</p>
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