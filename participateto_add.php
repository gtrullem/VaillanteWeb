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
		$gymnast = $_POST['gymnast'];

		for($i = 0; $i<10; $i++) { // REFAIRE LE FOR/IF PLUS INTELLIGENT
			if(!empty($gymnast[$i])) {
				$query = "INSERT INTO xtr_participateto (eventid, personid) VALUES ('$id', '$gymnast[$i]')";
				$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (trainer) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			}
		}
	
			header("Location: ./participateto_listing.php?id=$id");
			exit;
//		} else {
//			$err = "... !";
//		}
	}
	
	$query = "SELECT event_type, title FROM xtr_event WHERE eventid=$id;";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (traineto) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);
	
	if(date("n") >= "8") {
		$season = date("Y")."-".(date("Y") + 1);
	} else {
		$season = (date("Y") - 1)."-".date("Y");
	}
	
	$query = "SELECT personid, CONCAT(lastname, ', ', firstname) AS name FROM xtr_person WHERE personid IN (SELECT DISTINCT (personid) FROM xtr_isaffiliate, xtr_course WHERE xtr_isaffiliate.courseid = xtr_course.courseid AND xtr_course.season =  '$season') ORDER BY name"; 
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (isaffiliate, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Inscription :.</title>
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
</head>
	
<body>
<div id="body">

<?php	require_once("./header.php");	?>
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
				<h2><a>
					Participants
					<?php
						if($line['event_type'] == "Stage") {
							echo " au Stage";
						} else {
							echo " à la Compétition";
						}
					?>
				</a></h2>
				<br />
				<table align="center">
					<tr>
						<td>
							<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$id; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
							<fieldset>
								<legend>Choix des participants</legend>
								<p>
									<label><?php echo $line['event_type']; ?></label>
									<?php echo $line['title']; ?>
								</p>
								<p>
									<label>Gymnastes</label>
									<select name="gymnast[]">
										<option value=""></option>
										<?php
											while($line = mysql_fetch_array($result)) {
												echo "<option value=\"".$line['personid']."\">".$line['name']."</option>";
											}
										?>
									</select>
								</p>
								<p>
									<label>&nbsp</label>
									<select name="gymnast[]">
										<option value=""></option>
										<?php
											
											$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (isaffiliate, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
											
											while($line = mysql_fetch_array($result)) {
												echo "<option value=\"".$line['personid']."\">".$line['name']."</option>";
											}
										?>
									</select>
								</p>
								<p>
									<label>&nbsp</label>
									<select name="gymnast[]">
										<option value=""></option>
										<?php
											
											$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (isaffiliate, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
											
											while($line = mysql_fetch_array($result)) {
												echo "<option value=\"".$line['personid']."\">".$line['name']."</option>";
											}
										?>
									</select>
								</p>
								<p>
									<label>&nbsp</label>
									<select name="gymnast[]">
										<option value=""></option>
										<?php
											
											$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (isaffiliate, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
											
											while($line = mysql_fetch_array($result)) {
												echo "<option value=\"".$line['personid']."\">".$line['name']."</option>";
											}
										?>
									</select>
								</p>
								<p>
									<label>&nbsp</label>
									<select name="gymnast[]">
										<option value=""></option>
										<?php
											
											$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (isaffiliate, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
											
											while($line = mysql_fetch_array($result)) {
												echo "<option value=\"".$line['personid']."\">".$line['name']."</option>";
											}
										?>
									</select>
								</p>
								<p>
									<label>&nbsp</label>
									<select name="gymnast[]">
										<option value=""></option>
										<?php
											
											$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (isaffiliate, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
											
											while($line = mysql_fetch_array($result)) {
												echo "<option value=\"".$line['personid']."\">".$line['name']."</option>";
											}
										?>
									</select>
								</p>
								<p>
									<label>&nbsp</label>
									<select name="gymnast[]">
										<option value=""></option>
										<?php
											
											$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (isaffiliate, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
											
											while($line = mysql_fetch_array($result)) {
												echo "<option value=\"".$line['personid']."\">".$line['name']."</option>";
											}
										?>
									</select>
								</p>
								<p>
									<label>&nbsp</label>
									<select name="gymnast[]">
										<option value=""></option>
										<?php
											
											$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (isaffiliate, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
											
											while($line = mysql_fetch_array($result)) {
												echo "<option value=\"".$line['personid']."\">".$line['name']."</option>";
											}
										?>
									</select>
								</p>
								<p>
									<label>&nbsp</label>
									<select name="gymnast[]">
										<option value=""></option>
										<?php
											
											$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (isaffiliate, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
											
											while($line = mysql_fetch_array($result)) {
												echo "<option value=\"".$line['personid']."\">".$line['name']."</option>";
											}
										?>
									</select>
								</p>
								<p>
									<label>&nbsp</label>
									<select name="gymnast[]">
										<option value=""></option>
										<?php
											
											$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (isaffiliate, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
											
											while($line = mysql_fetch_array($result)) {
												echo "<option value=\"".$line['personid']."\">".$line['name']."</option>";
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