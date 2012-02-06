<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "discipline_add";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(isset($_POST['submit'])) {
		require_once("./CLASS/objectdiscipline.class.php");
		require_once("./CLASS/dbdiscipline.class.php");

		if(isset($_POST['enable']))	$enable = "Y";
		else	$enable = "N";

		$discipline = new Discipline(null, mysql_real_escape_string(stripslashes(trim($_POST['title']))), mysql_real_escape_string(stripslashes(trim($_POST['acronym']))), $enable, $_POST['responsableid']);

		var_dump($discipline);

		$database = new DBDiscipline();
		$database->insertDiscipline($discipline);

		// Checking if acronym is not alerady used
// 		$query = " SELECT COUNT(disciplineid) FROM xtr_discipline WHERE acronym = '$acronym'";
// 		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : COUNT FAILED (discipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
// 		$count = mysql_fetch_array($result);
		
// 		if($count[0] > 0) {
// 			$err = "Cet acronyme est déjà utilisé, veuillez en choisir un autre.";
// 		} else {
//			//some code
//		}			
		header("Location: ./discipline_listing.php");
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Ajout d'une section :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script language="javascript">
		function checkForm(formulaire)
		{
			if(document.formulaire.name.value.length < 3) {
				alert('Veuillez indiquer l\intitulé correcte de la section.');	
				return false;
			}
			
			if(document.formulaire.acronym.value.length < 3) {
				alert('Veuillez indiquer un acronyme correcte pour la section.');	
				return false;
			}
			
			if(document.formulaire.responsable.value == "") {
				alert('Veuillez choisir un responsable de section.');
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
			<h2><a>Ajout d'une Discipline</a></h2>
			<br />
			<table align="center">
				<tr>
					<td>
						<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
							<fieldset>
								<legend>Information de la Section</legend>
								<?php
									if(isset($err))	echo "<p align=\"center\" class=\"important\">$err</p>";
								?>
								<p>
									<label>Intitulé *</label>
									<input type="text" id="name" name="title" maxlength="35" size="38" />
								</p>
								<p>
									<label>Acronyme *</label>
									<input type="text" id="acronym" name="acronym" size="5" maxlength="5" />
								</p>
								<p>
									<label>Responsable de section *</label>
									<select name="responsableid">
										<option value=""></option>
									<?php
									
										$query = "SELECT xtr_users.userid, CONCAT(lastname,  ', ', firstname) AS name FROM xtr_users, xtr_person WHERE status_in > 0 AND xtr_users.personid = xtr_person.personid ORDER BY name";
															
										$result_user = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (USER, PERSON)!<br />".$result."<br />".mysql_error(), e_user_error);
										while($line = mysql_fetch_array($result_user, MYSQL_ASSOC)) {
											echo "<option value=\"".$line['userid']."\">".$line['name']."</option>";
										}
									?>
									</select>
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
</HTML>