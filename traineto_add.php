<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < 2) && ($_SESSION['status_out'] < 3)) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(empty($_GET['id'])) {
		
	}
	
	$id = $_GET['id'];
	
	if(isset($_POST['submit'])) {
		$trainer = $_POST['trainer'];

		$query = "SELECT eventid FROM xtr_traineto WHERE eventid = $id AND userid = '$trainer';";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (trainer) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		if(!mysql_fetch_array($result)) {			
			$query = "INSERT INTO xtr_traineto (eventid, userid) VALUES ('$id', '$trainer')";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (trainer) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	
			header("Location: ./event_detail.php?id=$id");
			exit;
		} else {
			$err = "L'entrainer choisi donne déjà ce stage !";
		}
	}
	
	$query = "SELECT title FROM xtr_event WHERE eventid=$id;";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (traineto) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Ajout d'un entraineur :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<script language="javascript">	
		function checkForm(formulaire)
		{
			
			if(document.formulaire.trainer.value == "") {
				alert('Veuillez choisir l\'entraineur.');
				return false;
			}

			return true;
		}
	</script>
	<noscript>
		<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>
	</noscript>
</HEAD>
	
<body>
<div id="body">

<?php	require_once("./header.php");	?>
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame">
			<div id="content">
			<!-- ========================= BEGIN FORM ====================== -->
			<h2><a>Ajout d'un entraineur</a></h2>
			<table align="center">
				<tr>
					<td>
						<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$id; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
						<fieldset>
							<legend>Informations</legend>
							<p>
								<label>Stage</label>
								<?php echo $line['title']; ?>
							</p>
							<p>
								<label>Entraineur</label>
								<select name="trainer">
									<option value=""></option>
									<?php
										$query = "SELECT xtr_users.userid, CONCAT(lastname, ', ', firstname) AS name FROM xtr_users, xtr_person WHERE xtr_users.personid = xtr_person.personid AND xtr_users.status_in != 0 ORDER BY name"; 
										$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (user, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
										
										while($line = mysql_fetch_array($result)) {
											echo "<option value=\"".$line['userid']."\">".$line['name']."</option>";
										}
									?>
								</select>
							</p>
							<p align="center"><input type="submit" name="submit" value="Ajouter" /></p>
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