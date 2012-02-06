<?php	
	require_once("./CONFIG/config.php");
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$id = $_SESSION['personid'];

	if (isset($_POST['Submit'])) {
			
		$newpwd = crypt($_POST['newpwd1'], '$2a$!ç$#é@');
	
		$query = "SELECT * FROM xtr_users WHERE id='$id'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (user) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

		if ($data = mysql_fetch_array($result)) {
			if ($data['pwd'] == crypt($_POST['oldpwd'], '$2a$!ç$#é@')) {
				$query = "UPDATE xtr_users SET pwd='$newpwd' WHERE id='$id'";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (user) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		    } else {
				$msg = "Ancien mot de passe incorrect...";
		    }
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
	<script src="./jquery/jquery.min.js" type="text/javascript"></script>
	<script src="./jquery/jquery.validate.pack.js" type="text/javascript"></script>

	<script type="text/javascript">
	$.validator.setDefaults({
		submitHandler: function() { alert("submitted!"); }
	});
	
	$().ready(function() {
		// validate signup form on keyup and submit
		$("#formulaire").validate({
			rules: {
				oldpwd: {
					required: true,
					minlength: 8
				},
				newpwd1: {
					required: true,
					minlength: 8
				},
				newpwd2: {
					required: true,
					minlength: 8,
					equalTo: "#newpwd1"
				}
			},
			messages: {
				newpwd2: {
					required: "Introduisez un mot de passe",
					minlength: "Minimum 8 caractères",
					equalTo: "Veuillez entrez le même mot de passe que le champs précédent"
				},
				newpwd1: {
					required: "Introduisez un mot de passe",
					minlength: "Minimum 8 caractères"
				},
				oldpwd: {
					required: "Introduisez votre mot de passe",
					minlength: "Minimum 8 caractères"
				}
			}
		});
	});
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
						<h2><a>Modification de votre mot de passe</a></h2>
						<div class="entry">
						<?php
							if(isset($msg)) {
								echo "<p align=\"center\" class=\"important\">".$msg."</p>";
							}
						?>
						Votre nouveau mot de passe doit fait être composé de <i>8 à 10 caractères</i> et doit contenir au moins :
						<ul>
							<li>une minuscule,</li>
							<li>une majuscule et</li>
							<li>un chiffre.</li>
						</ul>
						<FORM id="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<table align="center">
							<tr>
								<td><font size="2">Ancien mot de passe&nbsp;</font></td>
								<td><input type="password" id="oldpwd" name="oldpwd" size="12" MAXLENGTH="10" /></td>
							</tr>
							<tr>
								<td><font size="2">Nouveau mot de passe&nbsp;</font></td>
								<td><input type="password" id="newpwd1" name="newpwd1" size="12" MAXLENGTH="10" /></td>
							</tr>
							<tr>
								<td><font size="2">Répéter le nouveau mot de passe&nbsp;</font></td>
								<td><input type="password" id="newpwd2" name="newpwd2" size="12" MAXLENGTH="10" /></td>
							</tr>
						</table>
						<p align="center"><input type="submit" name="Submit" value="Changer" /></p>
						</FORM>
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