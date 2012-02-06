<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "subdiscipline_update";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(empty($_GET['subdisciplineid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=sous-discipline&referrer=subdiscipline_listing.php");
		exit;
	}
	
	$id = $_GET['subdisciplineid'];
	$subdisciplineid = $_GET['subdisciplineid'];

	require_once("./CLASS/dbsubdiscipline.class.php");
	$database = new DBSubDiscipline();
	
	if(isset($_POST['submit'])) {
		$title = mysql_real_escape_string(stripslashes(trim($_POST['name'])));
		$acronym = $_POST['acronym'];
		if(isset($_POST['enable']))	$enable = "Y";
		else	$enable = "N";

		require_once("./CLASS/objectsubdiscipline.class.php");

		$subdiscipline = new SubDiscipline($subdisciplineid, mysql_real_escape_string(stripslashes(trim($_POST['name']))), $_POST['acronym'], $enable, $_POST['disciplineid']);

		$database->updateSubDiscipline($subdiscipline);
		
		// if($_POST['acronym_old'] != $acronym) {
		// 	// Checking if acronym is not alerady used
		// 	$query = " SELECT COUNT(subdisciplineid) FROM xtr_subdiscipline WHERE acronym = '$acronym'";
		// 	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : COUNT FAILED (subdiscipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		// 	$count = mysql_fetch_array($result);
		
			// if($count[0] > 0)	$err = "Cet acronyme est déjà utilisé, veuillez en choisir un autre.";
		// }

		// if(!isset($err)) {
		// 	$query = " UPDATE xtr_subdiscipline SET title = '$title', acronym = '$acronym', enable = '$enable' WHERE subdisciplineid = '$id'";
		// 	// echo $query."<br />";
		// 	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (subdiscipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
			header("Location: ./subdiscipline_listing.php");
			exit;
		// }
	}

	$subdiscipline = $database->getSubDiscipline($subdisciplineid);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Mise à jour :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<script language="javascript">
		function checkForm(formulaire)
		{
			if(document.formulaire.disciplineid.value == "default") {
				alert('Veuillez la discipline parente.');	
				return false;
			}
			
			if(document.formulaire.title.value.length < 3) {
				alert('Veuillez indiquer l\'intitulé correcte de la section.');	
				return false;
			}
			
			if(document.formulaire.acronym.value.length < 3) {
				alert('Veuillez indiquer un acronyme correcte pour la section.');	
				return false;
			}
			
			// Preparing data
			document.formulaire.acronym.value = document.formulaire.acronym.value.toUpperCase();
			
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
				<h2><a>Modification d'une sous-discipline</a></h2>
				<br />
				<table align="center">
					<tr>
						<td>
							<form name="formulaire" class="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?subdisciplineid=".$subdisciplineid; ?>" onSubmit="return checkForm(this.form)">
							<?php
								if(!empty($err))	echo "<p align=\"center\" class=\"important\">$err</p>";
							?>
							<fieldset>
								<legend>Information sur la discipline</legend>
								<p>
								<label>Discipline *</label>
								<select name="disciplineid">
									<option value="default"></option>
									<?php
										require_once("./CLASS/dbdiscipline.class.php");
										$database = new DBDiscipline();

										foreach($database->getDisciplines() as $discipline) {
											echo "<option value=\"".$discipline->getID()."\"";
											if($subdiscipline->getDisciplineID() == $discipline->getID())
												echo " selected";
											echo ">".$discipline->getTitle()."</option>";
										}
									?>
								</select>
							</p>
								<p>
									<label>Nom *</label>
									<input type="text" id="name" name="name" value="<?php echo $subdiscipline->getTitle(); ?>" maxlength="35" size="38" />
								</p>
								<p>
									<label>Acronyme *</label>
									<input type="text" id="acronym" name="acronym" value="<?php echo $subdiscipline->getAcronym(); ?>" maxlength="5" size="5" />
									<input type="hidden" id="acronym_old" name="acronym_old" value="<?php echo $subdiscipline->getAcronym(); ?>" />
								</p>
								<p>
									<label>Active</label>
									<input type="checkbox" id="enable" name="enable" <?php if($subdiscipline->isActive() == "Y")	echo " checked"; ?> />
								</p>
								<p align="center"><input type="submit" name="submit" value="Modifier"></p>
							</fieldset>
						</form>
					</td>
				</tr>
			</table>
			</div>	
			<div id="sidebar" class="sidebar">
				<div>
					<div class="widget widget_categories">
						<h2 class="title">Informations</h2>
						<p align="justify">Les champs signalés d'une étoile (*) sont obligatoires.<br /><br />L'intitulé de la discipline doit être composé d'au moins 3 carctères.<br /><br />L'acronyme doit etre composé de minimum 3 caractères et maximum 5 caractères.</p>
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