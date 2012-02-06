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
	
	if(!empty($_GET['linkid']))
		$linkid = $_GET['linkid'];
	
	if(isset($_POST['submit'])) {
		// var_dump($_POST);
		$trainer = $_POST['trainer'];
		$linkid = $_POST['linkid'];

		if(date("n") >= "8")	$season = date("Y")."-".(date("Y") + 1);
		else	$season = (date("Y") - 1)."-".date("Y");

		$query = "SELECT * FROM xtr_istrainer WHERE userid = $trainer AND lcsid = $linkid";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (trainer) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		
		if(!mysql_fetch_array($result)) {
			$query = "INSERT INTO xtr_istrainer (userid, lcsid) VALUES ('$trainer', '$linkid')";
			// echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (trainer) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			
			$query = " SELECT email FROM xtr_person, xtr_users WHERE xtr_person.personid = xtr_users.personid AND xtr_users.userid = '$trainer' ";
			// echo $query."<br />";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person, user) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			$to = mysql_fetch_array($result, MYSQL_NUM);
			$to = $to[0];
			
			$headers = "From: \"La Vaillante - Extranet\"<extranet@lavaillantetubize.be>\n"; 
			$headers .="Reply-To: extranet@lavaillantetubize.be\n"; 
			$headers .="Content-type: text/html; charset=iso-8859-1\n";
			$headers .="Content-Transfer-Encoding: 8bit"; 
			$subject = utf8_decode("Extranet : vous avez ete ajoute(e) comme entraineur d'un cours");
			
			$message = utf8_decode("Vous êtes maintenant responsable d'un nouveau cours. Vous pouvez consulter la liste de vos cours <a href=\"http://lavaillantetubize.be/Extranet/user_detail.php?uid=".$trainer."\">I C I</a>.");
			mail($to, $subject, $message, $headers);
	
			header("Location: ./course_detail.php?linkid=$link");
			exit;
		} else {
			$err = "L'entrainer choisi donne déjà ce cours !";
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Ajout d'un entraineur :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<script language="javascript">	
		function checkForm(formulaire)
		{
		
			if(document.formulaire.course.value.length == 0) {
				alert('Veuillez choisir le cours.');
				return false;
			}
			
			if(document.formulaire.trainer.value.length == 0) {
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

<?php
	require_once("./header.php");
?>
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame">
			<div id="content">
			<h2><a>Ajout d'un entraineur</a></h2>
			<br />
			<table align="center">
				<tr>
					<td>
						<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
						<fieldset>
							<legend>Informations</legend>
							<p>
								<label>Cours *</label>
								<?php
									if(!empty($linkid)) {
										$query = "SELECT lcs.day, lcs.h_begin, lcs.h_end, d.title AS dtitle, d.acronym AS dacronym, sd.title AS sdtitle, sd.acronym AS sdacronym FROM xtr_course AS c, xtr_discipline AS d, xtr_subdiscipline AS sd, xtr_linkCourseSeason AS lcs WHERE lcs.courseid = c.courseid AND c.subdisciplineid = sd.subdisciplineid AND sd.disciplineid = d.disciplineid AND lcs.lcsid = $linkid";
			
										$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course, lien, discipline) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
										$line = mysql_fetch_array($result, MYSQL_ASSOC);

										echo $line['sdacronym']." (".$line['day']." : ".substr($line['h_begin'], 0, 5)."-".substr($line['h_end'], 0, 5).")";
										echo "<input type='hidden' name='linkid' value='$linkid' />";
									} else {
										if(date("n") >= "07")
											$season = date("Y")."-".(date("Y") + 1);
										else
											$season = (date("Y") - 1)."-".date("Y");

										$query = "SELECT * FROM xtr_season WHERE seasonlabel = '$season'";
										$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course (discipline, subdiscipline)) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
										$season = mysql_fetch_array($result, MYSQL_ASSOC);
			
										$query = "SELECT lcs.*, d.title AS dtitle, d.acronym AS dacronym, sd.title AS sdtitle, sd.acronym AS sdacronym FROM xtr_course AS c, xtr_discipline AS d, xtr_subdiscipline AS sd, xtr_linkCourseSeason AS lcs WHERE lcs.courseid = c.courseid AND c.subdisciplineid = sd.subdisciplineid AND sd.disciplineid = d.disciplineid AND c.active = 'Y' AND lcs.seasonid = ".$season['seasonid']." ORDER BY dtitle, sdtitle, h_begin";
			
										$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course (discipline, subdiscipline)) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);

										echo "<select name='linkid'><option value=''></option>";
			
										while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
											echo "<option value='".$line['lcsid']."'";
											if((!empty($linkid)) && ($line['lcsid'] == $linkid))	echo " selected";
											echo ">".$line['sdacronym']." (".$line['day']." : ".substr($line['h_begin'], 0, 5)."-".substr($line['h_end'], 0, 5).")</option>";
										}
										echo "</select>";
									}
								?>
							</p>
							<p>
								<label>Entraineur *</label>
								<select name="trainer">
									<option value=""></option>
									<?php
										$query = "SELECT xtr_users.userid, CONCAT(lastname, ', ', firstname) AS name FROM xtr_users, xtr_person WHERE xtr_users.personid = xtr_person.personid AND xtr_users.status_in != 0 ORDER BY name"; // xtr_istrainer, 
										$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (user, person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
										
										while($line = mysql_fetch_array($result, MYSQL_ASSOC))
											echo "<option value='".$line['userid']."'>".$line['name']."</option>";
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
			<div id="sidebar" class="sidebar">
				<div>
					<div class="widget widget_categories">
						<h2 class="title">Informations</h2>
						<p align="justify">Les champs signalés d'une étoile (*) sont obligatoires.</p>
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