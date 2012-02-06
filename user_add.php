<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$function = "useradd";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['rightin']) && ($_SESSION['status_out'] < $line['rightout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(isset($_POST['add'])) {
		// Preparing Data
		$id = $_POST['name'];
		$reward = $_POST['reward'];
		$password = "$2DUgOhjjpSp2";
		$trainerlevel = $_POST['trainerlevel'];
		$judgelevel = $_POST['judgelevel'];
		$statusin = $_POST['status_in'];
		$statusout = $_POST['status_out'];
		
		// Retrieving person's data
		$query = "SELECT lastname, firstname, email FROM xtr_person WHERE personid='$id'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : select FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		$line = mysql_fetch_array($result);
		$login = substr($line['firstname'], 0, 1).substr(strtr(strtr($line['lastname'], "àäâéèêëïîôöùûüç","aaaeeeeiioouuuc"), array(" " => "", "'" => "", "-" => "")), 0, 7);
		$login = strtolower($login);
		
		//Updating person's data
		$query = "UPDATE xtr_person SET addressbook = 'Y' WHERE personid = $id";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

		// Insertion into USERS
		$query = " INSERT INTO xtr_users (personid, username, pwd, reward, trainerlevel, judgelevel, status_in, status_out) VALUES ('$id', '$login', '$password', '$reward', '$trainerlevel', '$judgelevel', '$statusin', '$statusout')";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (user) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$headers ="From: \"La Vaillante - Extranet\"<extranet@lavaillantetubize.be>\n"; 
		$headers .="Reply-To: extranet@lavaillantetubize.be"."\n"; 
		$headers .="Content-type: text/html; charset=iso-8859-1"."\n";
		$headers .="Content-Transfer-Encoding: 8bit";
		$subject = "Extranet : Nouvel Utilisateur.";
		
		$message = "Cher(e) ".$line['lastname']." ".$line['firstname'].",<br /><br />Vous êtes maintenant autorisé(e) à vous rendre sur le site Extranet de La Vaillante Tubize. Pour cela, veuillez vous rendre à l'adresse suivante : <a href=\"http://lavaillantetubize.be/Extranet/cnx.php\">http://lavaillantetubize.be/Extranet/cnx.php</a>, et vous connecter avec les informations suivantes<br />Login : ".$login."<br />Mot de passe : pass1234<br />Pensez à <b>changer votre mot de passe des que possible</b> ainsi qu'à complèter vos données personnelles !<br /><br />Si vous rencontrez le moindre problème, n'hésitez pas à contacter <a mailto=\"extranet@lavaillantetubize.be\">l'équipe Extranet</a>.<br /><br />Bonne fin de journée.";
		mail($line['email'], $subject, utf8_decode($message), $headers);
		
		header('Location: ./user_listing.php');
		exit;
	}
			
	$query = "SELECT xtr_person.personid, CONCAT(lastname, ', ', firstname) AS name FROM xtr_person LEFT JOIN xtr_users ON xtr_person.personid = xtr_users.personid WHERE xtr_users.personid IS NULL AND xtr_person.email IS NOT NULL ORDER BY lastname, firstname";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (user, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>.: La Vaillante - Ajout d'un Utilisateur :.</TITLE>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<SCRIPT language="javascript">
		function checkForm(formulaire)
		{

			if(document.formulaire.name.value == "default") {
				alert('Veuillez choisir la personne.');
				return false;
			}
			
			if(document.formulaire.status_in.value == "default") {
				alert('Veuillez attribuer le Statut pédagogique de cette personne.');
				return false;
			} else {
				if(document.formulaire.status_in.value != "0") {
					var reg = new RegExp("^[0-9]{1,2}\.[05]{1}$");
					if(!reg.test(document.formulaire.reward.value)) {
						alert('Veuillez indiquer un salaire correct.')
						document.formulaire.reward.focus();
						return false;
					}
				}
			}
			
			if(document.formulaire.status_out.value == "") {
				alert('Veuillez attribuer les Statut gestionnaires de cette personne.');
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
				<!-- ========================= BEGIN FORM ====================== -->
				<h2><a>Ajout d'un Utilisateur</a></h2>
				<br />
				<table align="center">
					<tr>
						<td>
							<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
								<fieldset>
									<legend>Informations Utilisateur</legend>
									<?php
										if(isset($err)) {
											echo "<p align=\"center\" class=\"important\">$err</p>";
										} else {
									?>
								<p>
									<label>Nom, Prénom *</label>
									<select name="name">
										<option value="default"></option>
										<?php
											while($line = mysql_fetch_array($result)) {
												echo "<option value=\"".$line['personid']."\">".$line['name']."</option>";
											}
										?>
									</select>
								</p>
								<p>
									<label>Entraineur Niveau</label>
									<select name="trainerlevel">
										<?php
											for($i=-1 ; $i<5; $i++)
												echo "<option value=\"$i\">$i</option>";
										?>
									</select>
								</p>
								<p>
									<label>Juge Niveau</label>
									<select name="judgelevel">
									<?php
										for($i=0 ; $i<6; $i++)
											echo "<option value=\"$i\">$i</option>";
									?>
									</select>
									</p>
								<p>
									<label>Statut pédagogique *</label>
										<select name="status_in">
											<option value="default"></option>
											<?php
												$query = "SELECT value, label FROM xtr_userright WHERE scopein = 1 AND value < 10 ORDER BY value";
												$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (userright) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
												
												while($line = mysql_fetch_array($result)) {
													echo "<option value=\"".$line['value']."\">".$line['label']."</option>";
												}
											?>
										</select>
								</p>
								<p>
									<label>Statut gestionnaire *</label>
										<select name="status_out">
											<option value="default"></option>
											<?php
												$query = "SELECT value, label FROM xtr_userright WHERE scopeout = 1 AND value < 10 ORDER BY value";
												$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (userright) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
												
												while($line = mysql_fetch_array($result)) {
													echo "<option value=\"".$line['value']."\">".$line['label']."</option>";
												}
											?>
										</select>
								</p>
								<p>
									<label>Salaire (€/h)</label>
									<input type="text" name="reward" size="6" maxlength="4">
								</p>
								<p align="center"><input type="submit" name="add" value="Ajouter"></p>
								</fieldset>
							</form>
							<?php
								}
							?>
						</td>
					</tr>
				</table>
				<!-- ========================= END FORM ====================== -->
			</div>
			<div id="sidebar" class="sidebar">
				<div>
					<div class="widget widget_categories">
						<h2 class="title">Informations</h2>
						<p align="justify">Les champs signalés d'une étoile (*) sont obligatoires.<br /><br />Le choix d'un nouvel utilisateur ne peut se faire que dans la liste des personnes <i>déjà inscrites</i> et possédant une <i>adresse email</i>.<br /><br />Le salaire doit être écrit avec un <u>point</u> et non une <u>virgule</u>.</p>
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