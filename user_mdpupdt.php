<?php	
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$function = "usermdpupdate";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['rightin']) && ($_SESSION['status_out'] < $line['rightout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if (isset($_POST['Submit'])) {
		$userid = $_SESSION['uid'];
		$query = "SELECT * FROM xtr_users WHERE userid='$userid' AND pwd='".crypt($_POST['oldpwd'], '$2a$!ç$#é@')."'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (user) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

		if ($data = mysql_fetch_array($result)) {
				$newpwd = crypt($_POST['newpwd1'], '$2a$!ç$#é@');
				$query = "UPDATE xtr_users SET pwd='$newpwd' WHERE userid='$userid'";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (user) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
				
				header("Location: ./user_listing.php");
				exit;
		} else {
			$msg = "Ancien mot de passe incorrect...";
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Changement de mot de passe :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<SCRIPT language="javascript">
		function checkForm(formulaire)
		{
			if(document.formulaire.oldpwd.value == "") {
				alert('Veuillez entrer votre password.');
				document.formulaire.oldpwd.focus();
				return false;
			}
			
			if((document.formulaire.newpwd1.value == "") || (document.formulaire.newpwd2.value == "")) {
				alert('Veuillez introduire deux fois votre nouveau password.');
				return false;
			}
			
			if(document.formulaire.newpwd1.value != document.formulaire.newpwd2.value) {
				alert('Vos nouveaux mots de passe ne correspondent pas.');
				return false;
			}
			
			if(document.formulaire.newpwd1.value.length < 8) {
				alert('La longueur de votre mot de passe est trop courte.\n Veuillez mettre un mot de passe d\'au moins 8 caractères.');
				return false;
			}
			
			var reg = new RegExp("[0-9]+");
			if(!reg.test(document.formulaire.newpwd1.value)) {
				alert('Votre mot de passe doit contenir au moins un chiffre.');
				return false;
			}
			
			reg = new RegExp("[a-z]+");
			if(!reg.test(document.formulaire.newpwd1.value)) {
				alert('Votre mot de passe doit contenir au moins une lettre minuscule.');
				return false;
			}
			
			/*
			reg = new RegExp("[A-Z]+");
			if(!reg.test(document.formulaire.newpwd1.value)) {
				alert('Votre mot de passe doit contenir au moins une lettre MAJUSCULE.');
				return false;
			}
			*/
			/* \|\(\[\{\}\]\)\*\?\-\+\\\$ */
			/*
			var chaine ="àâäéèêëïöôûüùç&@\"#\'§!_=:;,\./<>€£µ%";
			var i=0;
			var isOK = false;
			
			while(chaine[i] && !isOK){
				var regSpe=new RegExp(chaine[i]+"+");
				if(regSpe.test(document.formulaire.newpwd1.value)) {
					isOK = true;
				}
				alert(regSpe);
				i++
			}
			
			if(!isOK) {
				alert('Votre mot de passe doit contenir au moins un caractère spécial : \n'+chaine);
				return false;
			}
			*/
			
			return true;
		}
	</script>
	<noscript>
		<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>
	</noscript>
</head>

<body>
<div id="body">
	<?php	require_once("./header.php");	?>
	<div id="page" class=" sidebar_right">
		<div class="container">
			<div id="frame">
				<div id="content">
					<div class="post">
						<h2><a>Modification du mot de passe</a></h2>
						<br />
						<div class="entry">
						<table align="center">
							<tr>
								<td>
									<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onSubmit="return checkForm(this.form)">
										<fieldset>
											<legend>Mots de passe</legend>
											<?php
												if(!empty($msg)) {
													echo "<p align=\"center\" class=\"important\">$msg</p>";
												}
											?>
											<p>
												<label>Ancien mot de passe</label>
												<input type="password" name="oldpwd" size="14" MAXLENGTH="12">
											</p>
											<p>
												<label>Nouveau mot de passe</label>
												<input type="password" name="newpwd1" size="14" MAXLENGTH="12">
											</p>
											<p>
												<label>Nouveau mot de passe</label>
												<input type="password" name="newpwd2" size="14" MAXLENGTH="12"> (retapez)
											</p>
											<p align="center"><input type="submit" name="Submit" value="Changer"></p>
										</fieldset>
									</form>
								<td>
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
						<p align="justify">Votre nouveau mot de passe doit fait être composé de <i>8 à 12 caractères</i> et doit contenir au moins
						<ul>
							<li>&nbsp;- une minuscules et</li>
							<li>&nbsp;- un chiffre.</li>
						</ul></p>
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>
<?php	require_once("./footer.php");	?>
</div>
</body>
</html>