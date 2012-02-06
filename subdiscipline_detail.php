<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "subdiscipline_detail";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

	if(empty($_GET['subdisciplineid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=sous-discipline&referrer=index.php");
		exit;
	}
	
	$subdisciplineid = $_GET['subdisciplineid'];

	require_once("./CLASS/dbsubdiscipline.class.php");
	$database = new DBSubDiscipline();

	$subdiscipline = $database->getSubDiscipline($subdisciplineid);
	
	$query = "SELECT LCS.courseid, LCS.day, LCS.h_begin, LCS.h_end, CONCAT( B.lastname,  ', ', B.firstname ) AS name FROM xtr_course AS C, xtr_linkCourseSeason AS LCS, xtr_istrainer AS it, xtr_users, xtr_person AS B WHERE LCS.courseid = it.LCSid AND it.userid = xtr_users.userid AND xtr_users.personid = B.personid AND C.subdisciplineid = $subdisciplineid AND LCS.seasonid = 2";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (course, link, trainer, user, person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	
	////////////////////////////////////////////////////////////////
	// GET SEASON INFORMATIONS
	if(date("n") >= "8")	$season = date("Y")."-".(date("Y") + 1);
	else	$season = (date("Y") - 1)."-".date("Y");

	$query = "SELECT * FROM xtr_season ORDER BY seasonlabel";
	$seasonlist = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (season) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Détails d'une sous discipline :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />	
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<script type="text/javascript" language="javascript">
		function showCourse()
		{
			if (window.XMLHttpRequest) {		// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} else {							// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
		
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
					document.getElementById("result").innerHTML=xmlhttp.responseText;
			}
			
			var seasonid = document.getElementById("seasonid").value; //document.formulaire.seasonid.value;
			var subdisciplineid = document.getElementById("subdisciplineid").value;//document.formulaire.subdisciplineid.value;

			xmlhttp.open("GET","subdisciplinegetcourse.php?seasonid="+seasonid+"&subdisciplineid="+subdisciplineid,true);
			xmlhttp.send();
		}
	</script>
	<noscript>
		<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>
	</noscript>
</head>

<body onLoad="javascript:showCourse()">
<div id="body">

<?php
	require_once("./header.php");
?>
	
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
			<h2><a>Détail d'une Sous-discipline</a></h2>
			<table align="center">
				<tr>
					<td>
						<!-- <form name="formulaire" class="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"> -->
						<form class="formulaire">
							<fieldset>
								<legend>Informations de la Sous-discipline</legend>
								<?php
									if(!empty($err))	echo "<p align='center' class='important'>$err</p>";
								?>
								<p>
									<label>Intitulé</label>
									<?php echo $subdiscipline->getTitle(); ?>
									<input type="hidden" name="subdisciplineid" id="subdisciplineid"  value="<?php echo $subdisciplineid; ?>" />
								</p>
								<p>
									<label>Acronyme</label>
									<?php echo $subdiscipline->getAcronym(); ?>
								</p>
								<p>
									<label>Discipline parente</label>
									<a href="./discipline_detail.php?disciplineid=<?php	echo $subdiscipline->getDiscipline()->getID();	?>"><?php	echo $subdiscipline->getDiscipline()->getTitle();	?></a>&nbsp;(<a href="./user_detail.php?uid=<?php echo $subdiscipline->getDiscipline()->getResponsableID() ?>"><?php echo $subdiscipline->getDiscipline()->getResponsable()->getLastName().", ".$subdiscipline->getDiscipline()->getResponsable()->getFirstName(); ?></a>)
								</p>
								<p>
									<label>Active</label>
									<?php
										if($subdiscipline->isActive() == "Y")	echo " Oui";
										else	echo "Non";
									?>
								</p>
								<p align="right">
									<label>&nbsp;</label>
									<a href="subdiscipline_update.php?subdisciplineid=<?php echo $subdiscipline->getID(); ?>" title="Modifier la Sous-discipline"><img src="./design/images/icons/16_Edit.png" alt="Modifier la Sous-discipline" /></a>
								</p>
							</fieldset>
						</form>
					</td>
				</tr>
				<tr>
					<td>
						<form class="formulaire" name="formulaire" id="formulaire">
							<fieldset>
								<legend>
									Liste des Cours de <select name="seasonid" id="seasonid" onChange="javascript:showCourse()">
									<?php
										while($line = mysql_fetch_array($seasonlist)) {
											echo "<option value='".$line['seasonid']."'";
											if($line['seasonlabel'] === $season) echo " selected";
											echo ">".$line['seasonlabel']."</option>";
										}
									?>
									</select>
								</legend>
								<div id="result"></div>
								<p align="right"><a href="course_add.php?subdisciplineid=<?php echo $subdiscipline->getID(); ?>"><img src="./design/images/icons/16_add.png" title="Ajouter un cours" /></a>&nbsp;</p>
							<fieldset>
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