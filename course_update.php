<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$function = "courseupdate";
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
	
	if(!empty($_POST['submit'])) {
		require_once("./CLASS/objectcourse.class.php");
		if(isset($_POST['enable']))	$enable = "Y";
		else	$enable = "N";

		$course = new Course($courseid, $_POST['subdiscipline'], $_POST['hallid'], $_POST['day'], $_POST['daynumber'], $_POST['h_begin'], $_POST['h_end'], $_POST['nbhour'], $enable, $_POST['tarification']);
		
		$database->updateCourse($course);
		
		header("Location: ./course_detail.php?courseid=".$courseid);
		exit;
	}

	$course = $database->getCourse($courseid);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Modification d'un Cours :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<script language="javascript">
		function checkForm(formulaire)
		{
			
			if(document.formulaire.h_begin.value > document.formulaire.h_end.value) {
				alert('L\'heure de fin doit être plus grande que l\'heure de début.');	
				return false;
			}
			
			// Preaparing data
			// document.formulaire.h_b.value = document.formulaire.h_begin.value+":"+document.formulaire.m_begin.value+":00";
			// document.formulaire.h_e.value = document.formulaire.h_end.value+":"+document.formulaire.m_end.value+":00";
			if(document.formulaire.day.value == "Lundi") document.formulaire.daynumber.value = 1;
			if(document.formulaire.day.value == "Mardi") document.formulaire.daynumber.value = 2;
			if(document.formulaire.day.value == "Mercredi") document.formulaire.daynumber.value = 3;
			if(document.formulaire.day.value == "Jeudi") document.formulaire.daynumber.value = 4;
			if(document.formulaire.day.value == "Vendredi") document.formulaire.daynumber.value = 5;
			if(document.formulaire.day.value == "Samedi") document.formulaire.daynumber.value = 6;
			
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
			<h2><a>Modification d'un Cours</a></h2>
			<br />
			<table align="center">
				<tr>
					<td>
						<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?courseid=".$courseid; ?>" onSubmit="return checkForm(this.form)">
							<fieldset>
								<legend>Informations du cours</legend>
								<p>
									<label>Sous-Discipline *</label>
									<select name="subdiscipline">
										<?php		
											
											require_once("./CLASS/dbsubdiscipline.class.php");
											$database = new DBSubDiscipline();

											foreach($database->getSubDisciplines() as $subdiscipline)	{
												echo "<option value=\"".$subdiscipline->getID()."\"";
												if($subdiscipline->getID() == $course->getSubDisciplineID())	echo " selected";
												echo ">".$subdiscipline->getAcronym()."</option>";
											}
										?>
									</select>
								</p>
								<p>
									<label>Lieu *</label>
									<select name="hallid">
										<?php
											require_once("./CLASS/dbplace.class.php");
											$database = new DBPlace();

											foreach($database->getHalls() as $hall)
												echo "<option value=\"".$hall->getID()."\"";
												if($hall->getID() == $course->getHallID())	echo " selected";
												echo ">".$hall->getName()."</option>";
										?>
									</select>
								</p>
								<p>
									<label>Jour *</label>
									<select name="day">
										<option value="Lundi" <?php if($course->getDayNumber() == 1) { echo "selected"; } ?>>Lundi</option>
										<option value="Mardi" <?php if($course->getDayNumber() == 2) { echo "selected"; } ?>>Mardi</option>
										<option value="Mercredi" <?php if($course->getDayNumber() == 3) { echo "selected"; } ?>>Mercredi</option>
										<option value="Jeudi" <?php if($course->getDayNumber() == 4) { echo "selected"; } ?>>Jeudi</option>
										<option value="Vendredi" <?php if($course->getDayNumber() == 5) { echo "selected"; } ?>>Vendredi</option>
										<option value="Samedi" <?php if($course->getDayNumber() == 6) { echo "selected"; } ?>>Samedi</option>
									</select>
									<input type="hidden" name="daynumber" />
								</p>
								<p>
									<label>Heure début *</label>
									<select name="h_begin">
						              <option value=""> </option>
						              <?php
						                for($i=0; $i<29; ++$i) {
						                  echo "<option value=\"".date("H:i:s", mktime(8, (30*$i), 0, 0, 0, 0))."\"";
						                  if(substr($course->getBeginHour(), 0, 5) == date("H:i", mktime(8, (30*$i), 0, 0, 0, 0))) {
						                    echo " selected";
						                  }
						                  echo ">".date("H:i", mktime(8, (30*$i), 0, 0, 0, 0))."</option>";
						                }
						              ?>
						            </select>
									<input type="hidden" name="h_beg" />
								</p>
								<p>
									<label>Heure fin *</label>
									<select name="h_end">
						              <option value=""> </option>
						              <?php
						                for($i=0; $i<29; ++$i) {
						                  echo "<option value=\"".date("H:i:s", mktime(8, (30*$i), 0, 0, 0, 0))."\"";
						                  if(substr($course->getEndHour(), 0, 5) == date("H:i", mktime(8, (30*$i), 0, 0, 0, 0))) {
						                    echo " selected";
						                  }
						                  echo ">".date("H:i", mktime(8, (30*$i), 0, 0, 0, 0))."</option>";
						                }
						              ?>
						            </select>
									<input type="hidden" name="h_e" />
								</p>
								<p>
									<label>Tarification *</label>
									<select name="tarification">
										<option value="GYM" <?php if($course->getTarification() == "GYM")	echo "selected"; ?>>Gym</option>
										<option value="TRA" <?php if($course->getTarification() == "TRA")	echo "selected"; ?>>Trampo</option>
									</select>
								</p>
								<p>
									<label>Actif</label>
									<input type="checkbox" id="enable" name="enable" <?php if($course->isActive() == "Y") echo " checked";	?> />
								</p>
								<p align="center"><input type="submit" name="submit" value="Modifier"></p>
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
							<li>Tous les champs sont obligatoires.</li>
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