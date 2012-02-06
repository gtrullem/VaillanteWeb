<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_del";
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] < 3) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(empty($_GET['personid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=personne&referrer=affiliate_listing.php");
		exit;
	}

	if(empty($_GET['linkid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=lien&referrer=course_listing.php");
		exit;
	}
	
	$personid = $_GET['personid'];
	$linkid = $_GET['linkid'];
	
	if(isset($_POST['no'])) {
		header("Location: ./affiliate_detail.php?personid=".$_POST['personid']);
		exit;
	} elseif(isset($_POST['yes'])) {
		$query = "DELETE FROM xtr_isaffiliate WHERE personid = $personid AND lcsid = $linkid";
		// echo "<br /><br /><br />".$query."<br />";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : DELETE FAILED (trainer) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);

		// $ref = $_SERVER['HTTP_REFERER'];
		// header("Refresh: 5; url=cnx.php?url=$ref");
		header("Location: person_detail.php?personid=".$_POST['personid']);
		exit;
	}
	
	$query = "SELECT h_begin, h_end, `day`, CONCAT( lastname,  ', ', firstname ) AS name, sd.title AS sdtitle, sd.acronym AS sdacronym, d.title AS dtitle, d.acronym AS dacronym, seasonlabel FROM xtr_linkCourseSeason, xtr_person, xtr_subdiscipline AS sd, xtr_course, xtr_discipline AS d, xtr_season WHERE xtr_linkCourseSeason.lcsid = $linkid AND xtr_person.personid = $personid AND sd.subdisciplineid = xtr_course.subdisciplineid AND xtr_course.courseid = xtr_linkCourseSeason.courseid AND sd.disciplineid = d.disciplineid AND xtr_season.seasonid = xtr_linkCourseSeason.seasonid";
//	echo $query."<br />";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (link, person, cours, discipline, subdiscipline, affiliation) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	
	$line = mysql_fetch_array($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Suppression d'un cours :.</title>
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
				<h2><a>Suppression d'une association</a></h2>
				<table align="center">
					<tr>
						<td>
							<form name="formulaire" class="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?personid=$personid&linkid=$linkid"; ?>" enctype="multipart/form-data">
								<fieldset>
									<legend>Informations</legend>
									<p>
										<label>Nom du gymnaste</label>
										<?php echo $line['name']; ?><input type="hidden" name="personid" value="<?php echo $line['pid']; ?>" />
									</p>
									<p>
										<label>Discipline</label>
										<?php echo $line['dtitle']." (".$line['dacronym'].")"; ?>
									</p>
									<p>
										<label>Sous-Discipline</label>
										<?php echo $line['sdtitle']." (".$line['sdacronym'].")"; ?>
									</p>
									<p>
										<label>Entrainement</label>
										<?php echo $line['day']." : de ".substr($line['h_begin'], 0, 5)." à ".substr($line['h_end'], 0, 5); ?>
									</p>
									<p>
										<label>Saison</label>
										<?php echo $line['seasonlabel']; ?>
									</p>
									<br />
									<p align="center" class="important">Etes-vous sûr de vouloir supprimer ce cours pour ce(tte) gymnaste ?<br /><input type="submit" name="yes" value="Oui" />&nbsp;&nbsp;&nbsp;<input type="submit" name="no" value="Non" /></p>
								</fieldset>
							</form>
						</td>
					</tr>
				</table>
			</div>		
		</div>
	</div>
</div>

	
<?php	require_once("./footer.php");	?>
</div>
</body>
</html>