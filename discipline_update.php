<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "discipline_update";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(empty($_GET['disciplineid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=discipline&referrer=index.php");
		exit;
	}
	
	require_once("./CLASS/dbdiscipline.class.php");

	$disciplineid = $_GET['disciplineid'];
	$database = new DBDiscipline();
	
	if(isset($_POST['submit'])) {
		if(isset($_POST['enable']))	$enable = "Y";
		else	$enable = "N";

		$discipline = new Discipline($disciplineid, mysql_real_escape_string(stripslashes(trim($_POST['name']))), $_POST['acronym'], $enable, $_POST['responsable']);

		$database->updateDiscipline($discipline);
		// $title = mysql_real_escape_string(stripslashes(trim($_POST['name'])));
		// $responsableid = $_POST['responsable'];
		// $acronym = $_POST['acronym'];
		
		// if($_POST['acronym_old'] != $discipline->getAcronym()) {
		// 	// Checking if acronym is not alerady used
		// 	$query = " SELECT COUNT(disciplineid) FROM xtr_discipline WHERE acronym = '$acronym'";
		// 	$result = mysql_query($query,$connect) or mail($webmaster, "Extranet Error", "SQL ERROR : DISCIPLIEN UPDATE - COUNT FAILED (discipline) !<br />$query<br />$result<br />".mysql_error(), $headers_basic);
		// 	$count = mysql_fetch_array($result);
		
		// 	if($count[0] > 0)	$err = "Cet acronyme est déjà utilisé, veuillez en choisir un autre.";
		// }

		// if(empty($err)) {
			header("Location: ./discipline_detail.php?disciplineid=".$disciplineid);
			exit;
		// }
	}

	$discipline = $database->getDiscipline($disciplineid);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Section : Mise à jour :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script language="javascript">
		function checkForm(formulaire)
		{
			if(document.formulaire.name.value.length < 2) {
				alert('Veuillez indiquer l\intitulé correcte de la section.');
				document.formulaire.name.focus();
				return false;
			}
			
			if(document.formulaire.acronym.value.length < 3) {
				alert('Veuillez indiquer un acronyme correcte pour la section.');
				document.formulaire.acronym.focus();
				return false;
			}
			
			if(document.formulaire.responsable.value == "") {
				alert('Veuillez indiquer un responsable de section.');
				document.formulaire.responsable.focus();
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
	
<BODY>
	<div id="body">

<?php
	require_once("./header.php");
?>
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
				<h2><a>Modification d'une section</a></h2>
				<?php
					if(!empty($err))	echo "<p align=\"center\" class=\"important\">$err</p>";
				?>
				<table align="center">
					<tr>
						<td>
							<form name="formulaire" class="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?disciplineid=".$disciplineid; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
							<fieldset>
								<legend>Informations de la Section</legend>
								<?php
									if(!empty($err))	echo "<p align=\"center\" class=\"important\">$err</p>";
								?>
								<p>
									<label>Intitulé *</label>
									<input type="text" id="name" name="name" value="<?php echo $discipline->getTitle(); ?>" maxlength="35" size="38" />
								</p>
								<p>
									<label>Acronyme *</label>
									<input type="text" id="acronym" name="acronym" value="<?php echo $discipline->getAcronym(); ?>" maxlength="5" size="5" />
									<input type="hidden" id="acronym_old" name="acronym_old" value="<?php echo $discipline->getAcronym(); ?>" />
								</p>
								<p>
									<label>Responsable de section *</label>
									<select name="responsable">
										<option value=""></option>
										<?php

											require_once("./CLASS/dbusers.class.php");
											$database = new DBUsers();

											foreach($database->getUsers() as $user) {
												echo "<option value=\"".$user->getUserID()."\"";
												if($user->getUserID() == $discipline->getResponsableID())	echo " selected";
												echo ">".$user->getLastName().", ".$user->getFirstName()."</option>";
											}
										?>
									</select>
								</p>
								<p>
									<label>Active</label>
									<input type="checkbox" id="enable" name="enable" <?php if($discipline->isActive() == "Y") echo " checked";	?> />
								</p>
								<p align="center"><input type="submit" name="submit" value="Modifier"></p>
							</fieldset>
							</form>
						</td>
					</tr>
				</table>
			</div>	
		</div>
	</div>
</div>
	
<?php
	require_once("./footer.php");
?>
</div>
</BODY>
</HTML>