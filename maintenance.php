<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] < 8) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(!empty($_POST['personopt'])) {
		$query = "UPDATE xtr_person SET birth = NULL WHERE birth = '0000-00-00'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$query = "UPDATE xtr_person SET birthplace = NULL WHERE birthplace = ''";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$query = "UPDATE xtr_person SET niss = NULL WHERE niss = ''";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$query = "UPDATE xtr_person SET address = NULL WHERE address = ''";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$query = "UPDATE xtr_person SET `box = NULL WHERE box = ''";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$query = "UPDATE xtr_person SET postal = NULL WHERE postal = ''";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$query = "UPDATE xtr_person SET city = NULL WHERE city = ''";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$query = "UPDATE xtr_person SET profession = NULL WHERE profession = ''";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$query = "UPDATE xtr_person SET phone = NULL WHERE phone = ''";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$query = "UPDATE xtr_person SET gsm = NULL WHERE gsm = '' OR gsm = ' '";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$query = "UPDATE xtr_person SET email = NULL WHERE email = ''";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$query = "UPDATE xtr_person SET ffgid = NULL WHERE ffgid = '' OR ffgid = '0'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

		$query = "UPDATE xtr_person SET resp1id = NULL WHERE resp1id = '' OR resp1id = '0'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

		$query = "UPDATE xtr_person SET type1 = NULL WHERE type1 = '' OR type1 = '0'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

		$query = "UPDATE xtr_person SET resp2id = NULL WHERE resp2id = '' OR resp2id = '0'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);

		$query = "UPDATE xtr_person SET type2 = NULL WHERE type2 = '' OR type2 = '0'";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
				
		$query = "OPTIMIZE TABLE xtr_person";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : OPTIMIZE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$person = "Optimisation effectuée...";
	} elseif(!empty($_POST['useropt'])) {
		$query = "UPDATE xtr_users SET account = NULL WHERE account = ''";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		$user = "Optimisation effectuée...";
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Database Maintenance :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
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
				<div class="post">
					<h2><a>Gestion de la base de données</a></h2>
					<table align="center">
						<tr>
							<td>
								<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" v>
									<fieldset>
										<legend>Optimisation de la table <i>Personnes</i></legend>
										<?php
											if(!empty($person)) {
												echo "<p align=\"center\" class=\"goodalert\">$person</p>";
											}
										?>
										<p align="center"><input type="submit" name="personopt" value="Optimiser !"></p>
									</fieldset>
									<br />
									<fieldset>
										<legend>Optimisation de la table <i>Utilisateurs</i></legend>
										<?php
											if(!empty($user)) {
												echo "<p align=\"center\" class=\"goodalert\">$user</p>";
											}
										?>
										<p align="center"><input type="submit" name="useropt" value="Optimiser !"></p>
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
	
<?php
	require_once("./footer.php");
?>
</div>
</body>
</html>