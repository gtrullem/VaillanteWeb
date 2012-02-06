<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] < 2){
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

	if(isset($_POST['submit'])) {
		$childid = $_POST['child'];
		$parentid1 = $_POST['parent1'];
		$type = $_POST['type'];
		if($_POST['responsable'] == "on") {
			$responsable = "Y";
		} else {
			$responsable = "N";
		}
		
		// Vérification de la relation
		$query = "SELECT id FROM xtr_relationship WHERE personid = '$childid' AND personid1 = '$parentid1'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (relationship) !<br>".$result."<br>".mysql_error(), E_USER_ERROR);
		
		if(mysql_fetch_array($result)) {
			$err = "Une relation entre des deux personnes existe déjà.";
		} else {
			// not more than 2 responsable
			if($responsable == "Y") {
				$query = " SELECT COUNT(relationshipid) FROM xtr_relationship WHERE personid = '$childid' AND responsable = 'Y'";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (relationship) !<br>".$result."<br>".mysql_error(), E_USER_ERROR);
				$count = mysql_fetch_array($result);
				
				if($count[0] >= 2) {
					$err = "Le nombre maximum de responsable pour cette personne est atteint.";
				} else {
					// Création de la relation entre les personnes
					$query = "INSERT INTO xtr_relationship (personid, personid1, type, responsable) VALUES ('$childid', '$parentid1', '$type', '$responsable')";
					$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (relationship) !<br>".$result."<br>".mysql_error(), E_USER_ERROR);
					
//					header("Location: ./relationship_listing.php");
//					exit;
					$ok = "Relation Ajoutée.";
				}
			}
		}
	}
	
	$query = " SELECT personid, CONCAT(lastname, ', ', firstname) AS name FROM xtr_person ORDER BY lastname, firstname";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Ajout de relation :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<SCRIPT language="javascript">
		function checkForm(formulaire)
		{

			if(document.formulaire.child.value == "default") {
				alert('Veuillez choisir un enfant.');
				return false;
			}
			
			if(document.formulaire.parent1.value == "default") {
				alert('Veuillez choisir au moins un parent.');
				return false;
			}
			
			if(document.formulaire.type.value == "default") {
				alert('Veuillez choisir le type de relation.');
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
			<!-- ========================= BEGIN FORM ====================== -->
			<H2><a>Ajout d'une relation</a></H2>
			<table align="center">
				<tr>
					<td>
						<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onSubmit="return checkForm(this.form)">
							<fieldset>
								<legend>Information sur la relation</legend>
								<p>Ajouter une relation associant un gymnaste à une <b>personne de contact</b>.</p>
								<?php 
									if(isset($err)) {
										echo "<p align=\"center\" class=\"important\">$err</p>";
									} elseif (isset($ok)) {
										echo "<p align=\"center\" class=\"goodalert\">$ok</p>";
									}
								?>
								<p>
									<label>Parent/Responsables :</label>
									<select name="parent1">
										<option value="default"></option>
										<?php
											$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br>"/$result."<br>".mysql_error(), E_USER_ERROR);
		
											while($line = mysql_fetch_array($result)) {
												echo "<option value=\"".$line['personid']."\">".$line['name']."</option>";
											}
										?>
									</SELECT>
								</p>
								<p>
									<label>Lien de parenté :</label>
										<select name="type">
											<option value="default"></option>
											<option value="père"> Père </option>
											<option value="mère"> Mère </option>
											<option value="frère"> Frère </option>
											<option value="soeur"> Soeur </option>
											<option value="conjoint"> Conjoint(e) </option>
											<option value="oncle"> Oncle </option>
											<option value="tante"> Tante </option>
											<option value="grand-père"> Grand-père </option>
											<option value="grand-mère"> Grand-mère </option>
											<option value="tuteur/tutrice"> Tuteur/Tutrice </option>
										</select>
								</p>
								<p>
									<label>Responsable ?</label>
									<input type="checkbox" name="responsable" />
								</>
								<p>
									<label>Inscrit :</label>
										<select name="child">
											<option value="default"></option>
											<?php
												$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT (person) !<br>"/$result."<br>".mysql_error(), E_USER_ERROR);
												
												while($line = mysql_fetch_array($result)) {
													echo "<option value=\"".$line['personid']."\">".$line['name']."</option>";
												}
											?>
										</SELECT>
								</p>
								<p align="center"><input type="submit" name="submit" value="Ajouter"></p>
							</fieldset>
						</form>
					</td>
				</tr>
			</table>
			<!-- ========================= END FORM ====================== -->
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