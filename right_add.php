<?php
	
	session_start();

	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < 3) && ($_SESSION['status_out'] < 4)) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(isset($_POST['submit'])) {
		$title = mysql_real_escape_string(stripslashes(trim($_POST['title'])));
		$group = $_POST['group'];
		
		$_POST['scopein'] == 'on' ? $scopein = 1 : $scopein = 0;
		$_POST['scopeout'] == 'on' ? $scopeout = 1 : $scopeout = 0;
		
		$query = "INSERT INTO xtr_userright (value, label, scopein, scopeout) VALUES ('$group', '$title', '$scopein', '$scopeout')";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		header("Location: ./right_listing.php");
		exit;
	}
  
	$query ="SELECT disciplineid, title FROM xtr_discipline WHERE enable = 'Y';";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Ajout d'un sDroit :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" language="javascript">
	function checkForm(formulaire)
	{
		if(document.formulaire.title.value.length < 6) {
			alert('Veuillez choisir un titre d\'au moins 6 caractères.');
			document.formulaire.title.focus();
			return false;
		}
		
		if(document.formulaire.group.value == "default") {
			alert('Veuillez choisir un groupe.');
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
		<div id="frame">
			<div id="content">
				<div class="post">
					<h2><a>Ajout d'un Droit</a></h2>
					<div class="entry">
						<table align="center">
							<tr>
								<td>
									<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
										<fieldset>
											<legend>Droit</legend>
											<p>
												<label>Titre</label>
												<input type="text" name="title" size="46" maxlength="70">
											</p>
											<p>
												<label>Groupe</label>
												<select name="group">
													<option value="default"></option>
													<?php
														for($i=0; $i<10; $i++) {
															echo "<option value=\"$i\">$i</option>";
														}
													?>
												</select>
											</p>
											<p>
												<label>Status pédagogique ?</label>
												<input type="checkbox" name="scopeout" />
											</p>
											<p>
												<label>Status gestionnaire ?</label>
												<input type="checkbox" name="scopein" />
											</p>
											<p align="center"><input type="submit" name="submit" value="Ajouter"></p>
										</fieldset>
									</form>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<!--------------------------------------------------------------------------------->
			<div id="sidebar" class="sidebar">
				<div>
					<div class="widget widget_categories">
						<h2 class="title">Informations</h2>
						<ul>
							<li>Le titre doit contenir au moins <i>6 caractères</i>.</li>
							<li>Les droits sont gérés par groupes <u>hiérarchiques</u> : le groupe 3 a moins de pouvoir que le groupe 4.</li>
						</ul>
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