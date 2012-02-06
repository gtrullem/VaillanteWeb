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
	
	if(isset($_POST['submit'])) {
		$id = $_POST['id'];
		$title = mysql_real_escape_string(stripslashes(trim($_POST['title'])));
		$author = mysql_real_escape_string(stripslashes(trim($_POST['author'])));
		$publisher = mysql_real_escape_string(stripslashes(trim($_POST['publisher'])));
		$summary = mysql_real_escape_string(stripslashes(trim($_POST['summary'])));
		$disciplineid = $_POST['disciplineid'];
    
		if(!empty($_POST['available'])) {
			$available = "Y";
		} else {
			$available = "N";
		}
		
		// Update Document's information
		$query = "UPDATE xtr_document SET title='$title', author='$author', publisher='$publisher', summary='$summary', available='$available' WHERE documentid='$id'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (document) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		echo "<br /><br /><br /> UPDATE SUCCESS";
		
		// Updating Document's section
		$querydel = "";
		var_dump($disciplineid);
		for($i=0; $i<sizeof($disciplineid); $i++) {
			// Existence of line ? A RETRAVAILLER !!!!! se baser sur la primary key !!!!!!
			$query = "SELECT documentid FROM xtr_docisfordisc WHERE documentid='$id' AND disciplineid = '$disciplineid[$i]'";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (DIFD) !<br>".$result."<br>".mysql_error(), E_USER_ERROR);
			echo "<br /><br /><br /> SELECT SUCCESS";
			if(!mysql_fetch_array($result)) {
				// line doesn't exist
				$query = "INSERT INTO xtr_docisfordisc (documentid, disciplineid) VALUES ('$id', '$disciplineid[$i]')";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (DIFD) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
				echo "<br /><br /><br />INSERTION SUCCESS";
			}
			$querydel .= $disciplineid[$i].", ";
		}
		
		// Delete of old line
		$query = "DELETE FROM xtr_docisfordisc WHERE documentid='$id' AND disciplineid NOT IN (".substr($querydel, 0, -2).")";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : DELETE FAILED (DIFD) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		echo "<br /><br /><br /> DELETE SUCCESS";
		
		header("Location: ./document_listing.php");
		exit;
	}

	$query = "SELECT D.title, D.summary, D.keyword, D.available, D.author, D.publisher, CONCAT( P.lastname,  ', ', P.firstname ) AS name FROM xtr_document AS D, xtr_users AS U, xtr_person AS P WHERE D.userid = U.userid AND U.personid = P.personid AND D.documentid = '$id'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Document Modification :.</title>
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
//		var test = true;
		for(var i=0; i <= document.formulaire.disciplineid.length; i++) {
//			test = test && document.formulaire.disciplineid[i].checked;
			if(document.formulaire.disciplineid[i].checked == false)
				document.formulaire.discipline_all.checked = false;
		}
		
//		if(test) document.formulaire.discipline_all.checked = true; 
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
					<h2><a>Modification d'un document</a></h2>
					<div class="entry">
						<table align="center">
							<tr>
								<td>
						<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$id; ?>"  enctype="multipart/form-data" onSubmit="return checkForm(this.form)"><!-- double transmission de l'ID : GET & POST !!!! -->
							<fieldset>
								<legend>Détails du document</legend>
								<p>
									<label>Titre *</label>
									<input type="text" name="title" size="46" maxlength="160" value="<?php echo $line['title']; ?>" />
									<input type="hidden" name="id" value="<?php echo $id; ?>" />
								</p>
								<p>
									<label>Auteur(s) *</label>
									<input type="text" name="author" size="46" maxlength="100" value="<?php echo $line['author']; ?>" />
								</p>
								<p>
									<label>Edition *</label>
									<input type="text" name="publisher" size="46" maxlength="50" value="<?php echo $line['publisher']; ?>" />
								</p>
								<p>
									<label>Résumé *</label>
									<textarea name="summary" rows="10" cols="56"><?php echo $line['summary']; ?></textarea>
								</p>
								<p>
									<table>
										<tr>
											<td><label>Section(s) concernée(s)</label></td>
											<td><input type="checkbox" name="discipline_all" onClick="transmit()" /> Toutes</td>
										</tr>
										<?php
										
											$querydoc = "SELECT disciplineid FROM xtr_docisfordisc WHERE documentid='$id' ORDER BY disciplineid;";
									    	$resultdoc = mysql_query($querydoc,$connect) or trigger_error("SQL ERROR : SELECT FAILED (DIFD) !<br />".$resultdoc."<br />".mysql_error(), E_USER_ERROR);
									    	$linedoc = mysql_fetch_array($resultdoc);
										
											$query ="SELECT disciplineid, title FROM xtr_discipline WHERE enable = 'Y'";
											$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
											
											while($line2 = mysql_fetch_array($result)) {
												echo "<tr><td>&nbsp;</td><td><input type=\"checkbox\" id=\"disciplineid\" name=\"disciplineid[]\" value=\"".$line2['disciplineid']."\"";
												if($linedoc['disciplineid'] == $line2['disciplineid']) {
													echo " checked";
													$linedoc = mysql_fetch_array($resultdoc);
												}
												echo " onClick=\"checkAll()\" />".$line2['title']."</td></tr>";
											}
										?>
									</table>
								</p>
								<!--
								<p>
								  <label>Mots Clé</label>
								  <TEXTAREA name="textbody" rows="10" COLS="60"></TEXTAREA>
								</p>
								-->
								<p>
									<label>Propriétaire *</label>
									<?php echo $line['name']; ?>
								</p>
								<br />
								<p align="center"><input type="submit" name="submit" value="Modifier"></p>
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