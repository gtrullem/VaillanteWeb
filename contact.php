<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "contact";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(!empty($_POST['submit'])) {
		$person_list = array();
		$i = 0;
		
		// Query Pre-processing
		if(date("n") >= "8") {
			$season = date("Y")."-".(date("Y") + 1);
		} else {
			$season = (date("Y") - 1)."-".date("Y");
		}
		
		$endquery = " AND sbd.subdisciplineid = c.subdisciplineid AND d.disciplineid = sbd.disciplineid AND c.season =  '$season'";
		if(!empty($_POST['course'])) {
			$endquery .= " AND c.courseid = '".$_POST['course']."'";
		} elseif (!empty($_POST['day'])) {
			$endquery .= " AND c.daynumber  = '".$_POST['day']."'";
		} elseif (!empty($_POST['subdiscipline'])) {
			$endquery .= " AND sbd.subdisciplineid  = '".$_POST['subdiscipline']."'";
		} else {
			if($_POST['discipline'] != "all") {
				$endquery .= " AND d.disciplineid  = '".$_POST['discipline']."'";
			} else {
				$subquery = "SELECT disciplineid FROM xtr_discipline WHERE enable='Y'";
				$result = mysql_query($subquery,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
				
				$line = mysql_fetch_array($result);
				$endquery .= " AND (d.disciplineid  = '".$line['disciplineid']."' ";

				while($line=mysql_fetch_array($result)) {
					$endquery .= "OR d.disciplineid = '".$line['disciplineid']."' ";
				}
				
				$endquery .= ")";
			}
		}
		
//		echo "<br /><br />";
		
		// Mail Pre-Processing
		$headers ="From: \"La Vaillante Tubize\"<".$_POST['emailcontact'].">\n"; 
		$headers .="Reply-To: ".$_POST['emailcontact']."\n"; 
		$headers .="Content-type: text/html; charset=iso-8859-1"."\n";
		$headers .="Content-Transfer-Encoding: 8bit"; 
		
//		echo "<br /><br /><br /><br />".$headers;
		
		$subject = "Informations : ".utf8_decode($_POST['mailobject']);
		$message = utf8_decode(nl2br(stripslashes($_POST['mailbody'])));

		
			
		$msg = "Mail(s) Envoyé(s) aux Gymnastes...";
				
		if(empty($_POST['responsable'])) {
			// on ne contacte QUE les gymnastes
			$query = "SELECT DISTINCT p.email, CONCAT(p.lastname, ', ', p.firstname) AS name FROM xtr_isaffiliate AS ia, xtr_person AS p, xtr_course AS c, xtr_subdiscipline AS sbd, xtr_discipline AS d WHERE p.email IS NOT NULL AND ia.personid = p.personid AND ia.courseid = c.courseid $endquery";
		} else {
			// Si on veut contacter les responsables des gymnastes
			$subquery = "SELECT DISTINCT p.personid FROM xtr_isaffiliate AS ia, xtr_person AS p, xtr_course AS c, xtr_subdiscipline AS sbd, xtr_discipline AS d WHERE ia.personid = p.personid AND ia.courseid = c.courseid $endquery ";
			
			// $query = "SELECT DISTINCT person.email, CONCAT(person.lastname, ', ', person.firstname) AS name FROM xtr_person AS person, xtr_relationship AS relation WHERE ( person.personid = relation.personid OR person.personid = relation.personid1 ) AND ( relation.personid IN ( $subquery ) OR relation.personid1 IN ( $subquery ) ) AND person.email IS NOT NULL";

			$query = "SELECT DICTINCT person.email, CONCAT(person.lastname, ', ', person.firstname) AS name FROM xtr_person WHERE person.personid IN ($subquery) AND person.email IS NOT NULL";
			
			$msg .= "<br />Mail(s) Envoyé(s) aux Responsables...";
		}
		
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		while($line = mysql_fetch_array($result)) {
			if(!empty($line['email'])) {
//				echo $line['email'].", ";
				if(mail($line['email'], $subject, $message, $headers)) {
					$person_list[$i] = $line['name'];
					$i++;
				}	
			}
		}
		
		if(!empty($_POST['trainer'])) {
			// Si on veut contacter les entraineurs
			$query = "SELECT DISTINCT p.email, CONCAT(p.lastname, ', ', p.firstname) AS name FROM xtr_istrainer AS istrainer, xtr_users AS user, xtr_person AS p, xtr_course AS c, xtr_subdiscipline AS sbd, xtr_discipline AS d WHERE p.email IS NOT NULL AND istrainer.userid = user.userid AND user.userid = p.personid AND istrainer.courseid = c.courseid $endquery";
			
//			echo "<br /><br /><br /><br />".$query;
		
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
			
			while($line = mysql_fetch_array($result)) {
				if(!empty($line['email'])) {
//					echo $line['email'].", ";
					if(mail($line['email'], $subject, $message, $headers)) {
						$person_list[$i] = $line['name'];
						$i++;
					}
				}
			}
			
			$msg .= "<br />Mail(s) Envoyé(s) aux Entraineurs...";
		}
		
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Contact :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<script language="javascript">
		function checkForm(formulaire)
		{
			if(document.formulaire.emailcontact.value == "") {
				alert('Veuillez choisir la personne de contact.');	
				return false;
			}
			
			if((document.formulaire.course.value == "") && (document.formulaire.subdiscipline.value == "") && (document.formulaire.discipline.value == "") && (document.formulaire.day.value == "")) {
				alert('Veuillez choisir un cours\nOU un jour\nOU une sous-discipline\nOU une discipline.');	
				return false;
			}
			
			if(document.formulaire.mailobject.value.length < 6) {
				alert('Veuillez introduire un titre plus long (min 6 caractères).');	
				return false;
			}
			
			if(document.formulaire.mailbody.value.length < 40) {
				alert('Veuillez introduire un corps plus long (min 40 caractères).');	
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
			<!-- ========================= BEGIN FORM ====================== -->
				<h2><a>Contact</a></h2>
				<table align="center">
					<tr>
						<td>
							<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
								<fieldset>
									<legend>Formulaire de Contact</legend>
									<p>
										<label>Personne de contact *</label>
										<select name="emailcontact">
											<option value=""></option>
											<?php
												$query = "SELECT xtr_person.email, CONCAT( xtr_person.lastname,  ', ', xtr_person.firstname ) AS name  FROM xtr_person, xtr_users WHERE xtr_users.personid = xtr_person.personid AND (status_out !=0 OR status_in !=0) ORDER BY lastname, firstname";
												$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	
												while($line=mysql_fetch_array($result)) {
													echo "<option value=\"".$line['email']."\">".$line['name']."</option>";
												}
											?>
										</select>
									</p>
									<p>
										<label>Cours</label>
										<select name="course">
											<option value=""></option>
											<?php
												$query = "SELECT acronym, courseid, day, h_begin, h_end FROM xtr_course, xtr_subdiscipline WHERE xtr_course.subdisciplineid = xtr_subdiscipline.subdisciplineid AND xtr_course.enable='Y' ORDER BY acronym, daynumber";
												$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	
												while($line=mysql_fetch_array($result)) {
													echo "<option value=\"".$line['courseid']."\">".$line['acronym']."&nbsp;&nbsp;&nbsp;".$line['day']." - (".substr($line['h_begin'], 0, -3)."-".substr($line['h_end'], 0, -3).")</option>";
												}
											?>
										</select>
									</p>
									<p>
										<label>Jour</label>
										<select name="day">
											<option value=""></option>
											<?php
												$query = "SELECT DISTINCT day, daynumber FROM xtr_course WHERE enable='Y' ORDER BY daynumber";
												$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	
												while($line=mysql_fetch_array($result)) {
													echo "<option value=\"".$line['daynumber']."\">".$line['day']."</option>";
												}
											?>
										</select>
									</p>
									<p>
										<label>Sous-Discipline</label>
										<select name="subdiscipline">
											<option value=""></option>
											<?php
												$query = "SELECT subdisciplineid, title FROM xtr_subdiscipline WHERE enable='Y'";
												$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (subdiscipline) !<br />".$result2."<br />".mysql_error(), E_USER_ERROR);
	
												while($line=mysql_fetch_array($result)) {
													echo "<option value=\"".$line['subdisciplineid']."\">".$line['title']."</option>";
												}
											?>
										</select>
									</p>
									<p>
										<label>Discipline</label>
										<select name="discipline">
											<option value=""></option>
											<option value="all">Toutes</option>
											<?php
												$query = "SELECT disciplineid, title FROM xtr_discipline WHERE enable='Y'";
												$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	
												while($line=mysql_fetch_array($result)) {
													echo "<option value=\"".$line['disciplineid']."\">".$line['title']."</option>";
												}
											?>
										</select>
									</p>
									<p>
										<label>Responsables</label>
										<input type="checkbox" name="responsable" />
									</p>
									<p>
										<label>Entraineurs</label>
										<input type="checkbox" name="trainer" />
									</p>
									<hr />
									<p>
										<label>Objet du mail *</label>
										<input type="text" name="mailobject" size="46" />
									</p>
									<p>
										<label>Corps du mail *</label>
										<textarea name="mailbody" rows="10" cols="56"></textarea>
									</p>
									<p align="center"><input type="submit" name="submit" value="Envoyer"></p>
									<?php
										if(!empty($msg)) {
											echo "<p align=\"center\" class=\"goodalert\">$msg</p>";
										}
										
										if(!empty($person_list)) {
									?>
									<p>
										<table>
											<tr>
												<td>Liste des gens contactés</td>
												<td>&nbsp;</td>
											</tr>
											<?php
												for($i=0; $i < sizeof($person_list); $i++) {
													echo "<tr><td>&nbsp;</td><td>".$person_list[$i]."</td></tr>";
												}
												
											?>
										</table>
									</p>
									<?php
										}
									?>
								</fieldset>
							</form>
						</td>
					</tr>
				</table>
				<!-- ========================= END FORM ====================== -->
			</div>
			<div id="sidebar" class="sidebar">
				<div>
					<div class="widget widget_categories">
						<h2 class="title">Informations</h2>
						<p align="justify">Les champs signalés d'une étoile (*) sont obligatoires.<br /><br />Le titre du mail doit être compsé d'au moins 6 caractères.<br /><br />Le corps du mail doit être composé d'au moins 40 caractères.<br /><br />Vous devez choisir :<ul><li> - un Cours <b>ou</b></li><li> - un Jour <b>ou</b></li><li> - une Sous-Discipline <b>ou</b></li><li> - une Discipline.</li></ul><br /><p align="justify">Choisissez si vous souhaitez que les responsables soient contactés également.</p>
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