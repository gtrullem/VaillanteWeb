<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "isaffiliate_add";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < 1) && ($_SESSION['status_out'] < 2)) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(empty($_GET['linkid']) && empty($_GET['personid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=affilié%20ou%20cours&referrer=affiliate_listing.php");
		exit;
	}

	// var_dump($_POST);

	if(isset($_POST['submit'])) {
		$time = date("Y-m-d h:i:s");
		if(!empty($_GET['personid'])) {
			$personid = $_GET['personid'];
			$linkid = $_POST['linkid'];
			
			$query = "INSERT INTO xtr_isaffiliate (dateaffiliation, lcsid, personid, paid) VALUES ('$time', '$linkid', '$personid', 'N')";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT1 FAILED (affiliate) !<br />$query</br />$result<br />".mysql_error(), E_USER_ERROR);
		
			header("Location: ./person_detail.php?personid=".$personid);
			exit;
		} else {
			$personid = $_POST['gymnast'];
			$linkid = $_GET['linkid'];

			$query = "INSERT INTO xtr_isaffiliate (dateaffiliation, lcsid, personid, paid) VALUES ('$time', '$linkid', '$personid', 'N')";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT2 FAILED (affiliate) !<br />$query</br />$result<br />".mysql_error(), E_USER_ERROR);
		
			header("Location: ./course_detail.php?linkid=".$linkid);
			exit;
		}
	}
	
	if(date("n") >= "8")	$season = date("Y")."-".(date("Y") + 1);
	else	$season = (date("Y") - 1)."-".date("Y");
	$query = "SELECT seasonid FROM xtr_season WHERE seasonlabel = '$season'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT1 FAILED (affiliate) !<br />$query</br />$result<br />".mysql_error(), E_USER_ERROR);
	$seasonid = mysql_fetch_array($result, MYSQL_NUM);
	$seasonid = $seasonid[0];

	if(!empty($_GET['personid'])) {
		$personid = $_GET['personid'];
		$actionTarget = $_SERVER['PHP_SELF']."?personid=".$personid;

		$query = " SELECT resp1id, resp2id FROM xtr_person WHERE personid = $personid";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		$line = mysql_fetch_array($result);
		if((($line['resp1id'] == 0) || ($line['resp1id'] == '')) && (($line['resp2id'] == 0) || ($line['resp2id'] == ''))) {
			header("Refresh: 4; url=../person_listing.php");
			echo "<p align='center' class='important'>Au moins un responsable doit être renseigné pour que le gymnaste puisse suivre un cours.<br /> Vous allez être redirigé(e) dans 4 secondes...</p>";
			exit;
		}

		$query = " SELECT CONCAT(lastname, ', ', firstname) AS name FROM xtr_person WHERE personid = $personid";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (affiliate/person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		$gymnast = mysql_fetch_array($result, MYSQL_ASSOC);

		// Select all courses not already followed and not in the same time
		$query = "SELECT DISTINCT xtr_linkCourseSeason.*, xtr_subdiscipline.acronym FROM xtr_course, xtr_linkCourseSeason, xtr_subdiscipline WHERE xtr_linkCourseSeason.courseid = xtr_course.courseid AND xtr_course.subdisciplineid = xtr_subdiscipline.subdisciplineid AND xtr_linkCourseSeason.lcsid NOT IN ( SELECT DISTINCT xtr_linkCourseSeason.lcsid FROM xtr_linkCourseSeason, ( SELECT h_begin AS newStart, h_end AS newEnd, daynumber AS newDay FROM xtr_linkCourseSeason, xtr_isaffiliate WHERE xtr_linkCourseSeason.lcsid = xtr_isaffiliate.lcsid AND xtr_isaffiliate.personid = $personid AND xtr_linkCourseSeason.seasonid = $seasonid ) AS Subscribed WHERE Subscribed.newStart < xtr_linkCourseSeason.h_end AND Subscribed.newEnd > xtr_linkCourseSeason.h_begin AND Subscribed.newDay = xtr_linkCourseSeason.daynumber )  AND seasonid = $seasonid ORDER BY daynumber, xtr_subdiscipline.acronym, h_begin";

		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	} else {
		$linkid = $_GET['linkid'];
		$actionTarget = $_SERVER['PHP_SELF']."?linkid=".$linkid;

		$query = "SELECT lcs.*, acronym FROM xtr_course, xtr_linkCourseSeason AS lcs, xtr_subdiscipline WHERE lcs.courseid = xtr_course.courseid AND xtr_course.subdisciplineid = xtr_subdiscipline.subdisciplineid AND lcs.lcsid = $linkid";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		$line = mysql_fetch_array($result);

		$query = "SELECT personid, CONCAT(lastname, ', ', firstname) AS name FROM xtr_person WHERE resp1id IS NOT NULL OR resp2id IS NOT NULL ORDER BY lastname, firstname";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Affiliation :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<script language="javascript">
		function checkForm(formulaire)
		{
			if(document.formulaire.course.value.length == 0) {
				alert('Veuillez choisir le cours suivit par l\'inscrit.');	
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
				<h2><a>Affiliation à un cours</a></h2>
				<br />
				<table align="center">
					<tr>
						<td>
							<form class="formulaire" name="formulaire" method="post" action="<?php echo $actionTarget; ?>" enctype="multipart/form-data"> <!-- onSubmit="return checkForm(this.form)" -->
								<fieldset>
									<legend>Affiliation : Gymnaste <-> Cours</legend>
									<?php
										if(!empty($err))
											echo "<p align='center' class='important'>$err</p>";
									?>
									<p>
										<label>Cours</label>
										<?php
											if(!empty($linkid))
												echo $line['acronym']." - ".$line['day']." de ".substr($line['h_begin'], 0, 5)." à ".substr($line['h_end'], 0, 5);
											else {
												echo "<select id='linkid' name='linkid'><option value=''></option>";
												while ($line = mysql_fetch_array($result))
													echo "<option value='".$line['lcsid']."'>".$line['day']." - ".$line['acronym']." (".substr($line['h_begin'], 0, 5)." - ".substr($line['h_end'], 0, 5).")</option>";
												echo "</select>";
											}
										?>
									</p>
									<p>
										<label>Nom, Prénom</label>
										<?php 
											if(!empty($personid))
												echo $gymnast['name'];
											else {
												echo "<select id='gymnast' name='gymnast'><option value='default'></option>";
												while ($person = mysql_fetch_array($result))
													echo "<option value='".$person['personid']."'>".$person['name']."</option>";
												echo "</select>";
											}
										?>
									</p>
									<p align="center"><input type="submit" name="submit" value="Ajouter"></p>
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
						<ul>
							<li class="cat-item">Information à venir...<!-- Seuls les cours qui ne provoquent pas de conflit avec un cours déjà suivit par l'affilé sont affichés. --></li>
						</ul>
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