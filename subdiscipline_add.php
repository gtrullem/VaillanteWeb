<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "subdiscipline_add";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(isset($_POST['submit'])) {
		require_once("./CLASS/objectsubdiscipline.class.php");
		require_once("./CLASS/dbsubdiscipline.class.php");

		if(isset($_POST['enable']))	$enable = "Y";
		else	$enable = "N";

		$subdiscipline = new SubDiscipline(null, mysql_real_escape_string(stripslashes(trim($_POST['title']))), mysql_real_escape_string(stripslashes(trim($_POST['acronym']))), $enable, $_POST['disciplineid']);

		// echo "<br /><br />";
		// var_dump($subdiscipline);

		$database = new DBSubDiscipline();
		$database->insertSubDiscipline($subdiscipline);
			
		header("Location: ./subdiscipline_listing.php");
		exit;

		// Checking if acronym is not alerady used
		// $discipline = $_POST['discipline'];
		// $acronym = $_POST['acronym'];
		// $query = "SELECT COUNT(subdisciplineid) FROM xtr_subdiscipline WHERE acronym = '$acronym'";
		// $result = mysql_query($query,$connect) or trigger_error("SQL ERROR : COUNT FAILED (subdiscipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		// $count = mysql_fetch_array($result);
		
		// if($count[0] > 0) {
		// 	$err = "Cet acronyme est déjà utilisé, veuillez en choisir un autre.";
		// } else {
		// 	$title = mysql_real_escape_string(stripslashes($_POST['name']));
		// 	if(isset($_POST['enable'])) {
		// 		$enable = "Y";
		// 	} else {
		// 		$enable = "N";
		// 	}
		// 	$query = "INSERT INTO xtr_subdiscipline (disciplineid, title, acronym, enable) VALUES ('$discipline', '$title', '$acronym', '$enable')";
		// 	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (subdiscipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

		// 	header("Location: ./subdiscipline_listing.php");
		// 	exit;
		// }
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Ajout d'une sous-discipline :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<SCRIPT language="javascript">
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
			<!-- ========================= BEGIN FORM ====================== -->
			<h2><a>Ajout d'une Sous-Discipline</a></h2>
			<br />
			<?php
				if(isset($err)) {
					echo "<p align=\"center\" class=\"important\">$err</p>";
				}
			?>
			<table align="center">
				<tr>
					<td>
						<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
						<fieldset>
							<legend>Information sur la Sous-Discipline</legend>
							<p>
								<label>Discipline *</label>
								<select name="disciplineid">
									<option value="default"></option>
									<?php
										require_once("./CLASS/dbdiscipline.class.php");
										$database = new DBDiscipline();

										foreach($database->getDisciplines() as $discipline) {
											echo "<option value=\"".$discipline->getID()."\"";
											if(!empty($_GET['disciplineid']) && ($_GET['disciplineid'] == $discipline->getID())) {
												echo "selected";
											}
											echo ">".$discipline->getTitle()."</option>";
										}
									?>
								</select>
							</p>
							<p>
								<label>Intitulé *</label>
								<input type="text" id="name" name="title" maxlength="40" size="38" />
							</p>
							<p>
								<label>Acronyme *</label>
								<input type="text" id="acronym" name="acronym" size="5" maxlength="5" />
							</p>
							<p>
								<label>Active</label>
								<input type="checkbox" id="enable" name="enable" checked />
							</p>
							<p align="center"><input type="submit" name="submit" value="Ajouter"></p>
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
						<p align="justify">Les champs signalés d'une étoile (*) sont obligatoires.<br /><br />L'intitulé de la sous-discipline doit être composé d'au moins 3 carctères.<br /><br />L'acronyme doit etre composé de minimum 3 caractères et maximum 5 caractères.</p>
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