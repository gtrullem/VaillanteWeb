<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$function = "courseadd";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(!empty($_GET['subdisciplineid']))	$subdisciplineid = $_GET['subdisciplineid'];
	
	if(!empty($_POST['submit'])) {
		require_once("./CLASS/dbcourse.class.php");
		require_once("./CLASS/objectcourse.class.php");

		// ($linkid, $courseid, $subdisciplineid, $hallid, $day, $daynumber, $beginhour, $endhour, $nbhour, $active, $tarification, $seasonid)
		$course = new Course(NULL, NULL, $_POST['subdiscipline'], $_POST['hallid'], $_POST['day'], $_POST['daynumber'], $_POST['h_begin'], $_POST['h_end'], $_POST['nbhour'], 'Y', $_POST['tarification'], $_POST['seasonid']);

		$database = new DBCourse();

		$course->setID($database->insertCourse($course));

		// $servername = 'mysql5-6.start';
		// $dbusername = 'lavailla_01';
		// $dbpassword = 'lavailla01';
		// $dbname = 'lavailla_01';
		// $connect = mysql_connect($servername,$dbusername,$dbpassword) or die("Could not connect to database : " . mysql_error());
		// mysql_select_db($dbname, $db)  or trigger_error("Could not select database : ".mysql_error(), E_USER_ERROR);
		// mysql_query("SET NAMES 'utf8'");
		
		// $trainer1 = $_POST['trainer1'];
		// $query = " INSERT INTO xtr_istrainer (courseid, userid) VALUES ('".$course->getLinkID()."', '$trainer1')";
		// $result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (trainer) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		// if(!empty($_POST['trainer2'])) {
		// 	$trainer2 = $_POST['trainer2'];
		// 	$query = " INSERT INTO xtr_istrainer (courseid, userid) VALUES ('".$course->getLinkID()."', '$trainer2')";
		// 	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (trainer) !<br />query<br />$result<br />".mysql_error(), E_USER_ERROR);
		// }
		
		header("Location: ./subdiscipline_detail.php?subdisciplineid=".$_POST['subdiscipline']);
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Ajout d'un Cours :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" language="javascript" src="./library/library.js"></script>
	<script type="text/javascript" language="javascript">
		function copyInfo()
		{
			if(document.formulaire.day.value == "Lundi") document.formulaire.daynumber.value = 1;
			if(document.formulaire.day.value == "Mardi") document.formulaire.daynumber.value = 2;
			if(document.formulaire.day.value == "Mercredi") document.formulaire.daynumber.value = 3;
			if(document.formulaire.day.value == "Jeudi") document.formulaire.daynumber.value = 4;
			if(document.formulaire.day.value == "Vendredi") document.formulaire.daynumber.value = 5;
			if(document.formulaire.day.value == "Samedi") document.formulaire.daynumber.value = 6;
			if(document.formulaire.day.value == "Dimanche") document.formulaire.daynumber.value = 7;
		}
		
		function checkForm(formulaire)
		{
			if(document.formulaire.hallid.value.length == 0) {
				alert('Veuillez choisir une salle.');
				document.formulaire.hallid.focus();
				return false;
			}

			if(document.formulaire.subdiscipline.value.length == 0) {
				alert('Veuillez choisir une sous-discipline.');
				document.formulaire.discipline.focus();
				return false;
			}
			
			if(document.formulaire.day.value.length == 0) {
				alert('Veuillez choisir un jour.');	
				document.formulaire.day.focus();
				return false;
			}
			
			if(document.formulaire.h_begin.value.length == 0) {
				alert('Veuillez indiquer une heure de début.');
				document.formulaire.h_begin.focus();
				return false;
			}
			
			if(document.formulaire.h_end.value.length == 0) {
				alert('Veuillez indiquer une heure de fin.');
				document.formulaire.h_end.focus();
				return false;
			}
			
			calculate();
			var tmp = document.formulaire.nbhour.value.split(":");
			if((tmp[0] <= 0) || (tmp[0] >= 10)) {
				alert('Veuillez vérifier les heures que vous avez entrées.');
				return false;
			}

			if(document.formulaire.season.value.length == 0) {
				alert('Veuillez choisir une saison.')
				document.formulaire.season.focus();
				return false;
			}

			if(document.formulaire.tarification.value.length == 0) {
				alert('Veuillez choisir une tarification pour le cours.')
				document.formulaire.tarification.focus();
				return false;
			}

			if(tmp[1] == "30")
				document.formulaire.nbhour.value = tmp[0]+".5";
			else
				document.formulaire.nbhour.value = tmp[0]+".0";
			
			return true;
		}
				
		function calculate()
		{
			if(document.formulaire.h_end.value != "")
				if(document.formulaire.h_begin.value != "") {
					var t2 = new Date("September 1, 2010 "+document.formulaire.h_end.value);
					var t1 = new Date("September 1, 2010 "+document.formulaire.h_begin.value);
					var diff = new Date();
					diff.setTime(t2-t1);
					document.formulaire.nbhour.value = (diff.getHours() -1)+":"+diff.getMinutes();
				}
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
			<h2><a>Ajout d'un Cours</a></h2>
			<br />
			<table align="center">
				<tr>
					<td>
						<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onSubmit="return checkForm(this.form)">
						<fieldset>
							<legend>Détails du cours</legend>
							<p>
								<label>Salle de sport *</label>
								<select name="hallid">
									<option value=""></option>
									<?php
										require_once("./CLASS/dbplace.class.php");

										$database = new DBPlace();

										foreach($database->getHalls() as $hall)
											echo "<option value='".$hall->getID()."'>".$hall->getName()."</option>";
									?>
								</select>
							</p>
							<p>
								<label>Sous-Discipline *</label>
								<select name="subdiscipline">
									<option value=""></option>
									<?php
										require_once("./CLASS/dbsubdiscipline.class.php");

										$database = new DBSubDiscipline();

										foreach($database->getSubDisciplines() as $subdiscipline)	{
											echo "<option value='".$subdiscipline->getID()."'";
											if($subdiscipline->getID() == $subdisciplineid)	echo " selected";
											echo ">".$subdiscipline->getAcronym()."</option>";
										}
									?>
								</select>
							</p>
							<p>
								<label>Saison *</label>
								<select name="seasonid">
									<option value=""></option>
									<?php
										if(date("n") >= "8")	$season = date("Y")."-".(date("Y") + 1);
										else	$season = (date("Y") - 1)."-".date("Y");

										$query = "SELECT seasonid FROM xtr_season WHERE seasonlabel = '$season'";
										$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (season) !<br />query<br />$result<br />".mysql_error(), E_USER_ERROR);
										$minid = mysql_fetch_array($result, MYSQL_NUM);
										$minid = $minid[0];

										$query = "SELECT * FROM xtr_season WHERE seasonid >= $minid";
										$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (season) !<br />query<br />$result<br />".mysql_error(), E_USER_ERROR);

										while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
											echo "<option value='".$line['seasonid']."'";
											if($line['seasonlabel'] === $season) echo " selected";
											echo ">".$line['seasonlabel']."</option>";
										}
									?>
								</select>
							</p>
							<p>
								<label>Jour *</label>
								<select name="day" onChange="copyInfo()">
									<option value=""></option>
									<option value="Lundi">Lundi</option>
									<option value="Mardi">Mardi</option>
									<option value="Mercredi">Mercredi</option>
									<option value="Jeudi">Jeudi</option>
									<option value="Vendredi">Vendredi</option>
									<option value="Samedi">Samedi</option>
									<option value="Dimanche">Dimanche</option>
								</select>
								<input type="hidden" name="daynumber" id="daynumber" />
							</p>
							<p>
								<label> Heure début *</label>
								<select name="h_begin">
									<option value=""> </option>
									<?php
										for($i=0; $i<29; ++$i)
											echo "<option value='".date("H:i:s", mktime(8, (30*$i), 0, 0, 0, 0))."'>".date("H:i", mktime(8, (30*$i), 0, 0, 0, 0))."</option>";
									?>
								</select>
								<input type="hidden" name="nbhour" id="nbhour" />
							</p>
							<p>
								<label> Heure fin *</label>
								<select name="h_end">
									<option value=""> </option>
									<?php
										for($i=0; $i<29; ++$i)
											echo "<option value=\"".date("H:i:s", mktime(8, (30*$i), 0, 0, 0, 0))."\">".date("H:i", mktime(8, (30*$i), 0, 0, 0, 0))."</option>";
									?>
								</select>
							</p>
							<p>
								<label>Tarification *</label>
								<select name="tarification">
									<option value=""></option>
									<option value="GYM">Gym</option>
									<option value="TRA">Trampo</option>
								</select>
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
						<p align="justify">Les champs signalés d'une étoile (*) sont obligatoires.<br /><br /></p>
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