<?php
	require_once("./CONFIG/config.php");
	
	if(isset($_POST['Submit'])) {
		$pwd = crypt($_POST['pwd'], '$2a$!ç$#é@');
		$login = $_POST['login'];

		$query = " SELECT userid, xtr_person.personid, pwd, status_in, status_out, CONCAT(lastname, ', ', firstname) AS name FROM xtr_users, xtr_person WHERE xtr_person.personid = xtr_users.personid AND username = '$login' AND pwd = '$pwd' AND (status_in != 0 OR status_out != 0)";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (user) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		
		if ($data = mysql_fetch_array($result)) {
				// Configure le délai d'expiration à 10 minutes
				///////////////////////////////////////////////////////////////////////
				session_cache_expire(10);
				session_start();
				
				// Informations PERSON
				///////////////////////////////////////////////////////////////////////
				$_SESSION['pid'] = $data['personid'];
				$_SESSION['name'] = $data['name'];
				
				// Informations USERS
				///////////////////////////////////////////////////////////////////////
				$_SESSION['uid'] = $data['userid'];
				$_SESSION['status_in'] = $data['status_in'];
				$_SESSION['status_out'] = $data['status_out'];
				
				// Update DatabBase
				///////////////////////////////////////////////////////////////////////
				mysql_query("UPDATE xtr_users SET lastcnx = '" . date("Y-m-d H:i:s", $_SERVER['REQUEST_TIME']) . "' WHERE userid = " . $data['userid'], $connect);
				
				// Redirection
				///////////////////////////////////////////////////////////////////////
				if(!empty($_POST['url']))
					$url = $_POST['url'];
				else
					$url = "./index.php";
				
				header("Location: $url");
				exit;
		} else
			// Pas d'utilisateur avec ce login/droit d'accès
			///////////////////////////////////////////////////////////////////////
			$err = "Mauvais login et/ou password. Veuillez recommencer...";
	}
	
	if(!empty($_GET['url']))
		$url = $_GET['url'];
	else
		$url = "./index.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Connexion :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<script language="javascript">
		function checkForm(formulaire)
		{
			if(document.formulaire.login.value.length < 3) {
				alert('Veuillez introduire votre login.');
				document.formulaire.login.focus();
				return false;
			}
			
			if(document.formulaire.pwd.value.length < 8) {
				alert('Veuillez introduire mot de passe.');
				document.formulaire.pwd.focus();
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

<div id="header"> 
	<div class="container">
		<div align="right">
			&nbsp;
		</div>
		<div id="header_image"></div>
	</div>
</div>
<div id="page" class=" sidebar_right">
	<div class="container">	
		<div id="frame2">
			<div id="content">
				<div class="post">
					<div class="entry">
						<table align="center">
							<tr>
								<td>
									<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onSubmit="return checkForm(this.form)">
										<fieldset>
											<legend>Connexion</legend>
											<?php
												if(!empty($err))
													echo "<p align='center' class='important'>$err</p>";
											?>
											<p>
												<label>Login :</label>
												<input type="text" name="login" size="10" maxlength="8" />
											</p>
											<p>
												<label>Password :</label>
												<input type="password" name="pwd" size="15" maxlength="12" />
											</p>
											<input type="hidden" name="url" value="<?php echo $url; ?>">
											<p align="center"><input type="submit" name="Submit" value="Connexion"></p>
										</fieldset>
									</form>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php
	require_once("./footer.php");
?>
</body>
</html>