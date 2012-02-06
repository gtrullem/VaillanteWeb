<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$function = "coursedelete";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(empty($_GET['courseid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=cours&referrer=course_listing.php");
		exit;
	}
	

	require_once("./CLASS/dbcourse.class.php");
	$courseid = $_GET['courseid'];
	$database = new DBCourse();

	if(isset($_POST['no'])) {
		header("Location: ./course_listing.php");
		exit;
	} elseif(isset($_POST['yes'])) {
		$database->deleteCourse($courseid);
		header("Location: ./course_listing.php");
		exit;
	}

	$course = $database->getCourse($courseid);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Suppression d'un cours :.</title>
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
		<div id="frame">
			<div id="content">
				<h2><a>Suppression d'un cours</a></h2>
				<br />
				<?php
					if(!empty($err))	echo "<p align=\"center\" class=\"important\">$err</p>";
				?>
				<table align="center">
					<tr>
						<td>
							<form name="formulaire" class="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?courseid=".$courseid; ?>">
								<fieldset>
									<legend>Informations</legend>
									<p>
										<label>Discipline</label>
										<?php echo $course->getSubDiscipline()->getDiscipline()->getTitle(); ?>
									</p>
									<p>
										<label>Sous-Discipline</label>
										<?php echo $course->getSubDiscipline()->getTitle(); ?>
									</p>
									<p>
										<label>Salle</label>
										<?php echo $course->getHall()->getName();	?>
									</p>
									<p>
										<label>Jour</label>
										<?php echo $course->getDay(); ?>
									</p>
									<p>
										<label>Heure</label>
										De <?php echo substr($course->getBeginHour(), 0, 5)." à ".substr($course->getEndHour(), 0, 5); ?>
									</p>
									<hr />
									<p align="center" class="important">
										Etes-vous sûr de vouloir supprimer ce cours ?
									</p>
									<p align="center"><input type="submit" name="yes" value="Oui" />&nbsp;&nbsp;&nbsp;<input type="submit" name="no" value="Non" />
									</p>
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
						En effacant ce cours, il sera effacé de tous les gymnaste le suivant. De même pour les moniteurs.
						<!--
						<ul>
							<li class="cat-item"><a href="./index.php?disc=*">Toutes</a></li>
						</ul>
						-->
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