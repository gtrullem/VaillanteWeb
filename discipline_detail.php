<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "discipline_detail";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

	if(empty($_GET['disciplineid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=section&referrer=discipline_listing.php");
		exit;
	}
	
	$disciplineid = $_GET['disciplineid'];

	require_once("./CLASS/dbdiscipline.class.php");

	$database = new DBDiscipline();
	$discipline = $database->getDiscipline($disciplineid);
	
	if(date("n") >= "8")	$season = date("Y")."-".(date("Y") + 1);
	else	$season = (date("Y") - 1)."-".date("Y");
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Détail d'une Discipline :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
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
		<div id="frame2">
			<div id="content">
			<h2><a>Détail d'une Discipline</a></h2>
			<?php
				if(!empty($err))	echo "<p align='center' class='important'>$err</p>";
			?>
			<table align="center">
				<tr>
					<td>
						<form name="formulaire" class="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<fieldset>
								<legend>Informations de la Discipline</legend>
								<?php
									if(!empty($err))
										echo "<p align='center' class='important'>$err</p>";
								?>
								<p>
									<label>Intitulé</label>
									<?php echo $discipline->getTitle(); ?>
								</p>
								<p>
									<label>Acronyme</label>
									<?php echo $discipline->getAcronym(); ?>
								</p>
								<p>
									<label>Responsable de section</label>
									<?php
										echo "<a href=./user_detail?uid=".$discipline->getResponsableID().">".$discipline->getResponsable()->getLastName().", ".$discipline->getResponsable()->getFirstName()."</a>";
									?>
								</p>
								<p>
									<label>Active</label>
									<?php
										if($discipline->isActive() == "Y")	echo " Oui";
										else	"Non";
									?>
								</p>
								<p align="right">
									<label>&nbsp;</label>
									<a href="discipline_update.php?disciplineid=<?php echo $discipline->getID(); ?>" title="Modifier la discipline"><img src="./design/images/icons/16_Edit.png" alt="Modifier la discipline" /></a>
								</p>
							</fieldset>
						</form>
					</td>
				</tr>
				<tr>
					<td>
						<form name="formulaire" class="formulaire">
							<fieldset>
								<legend>Liste des Sous-Disciplines associées</legend>
								<table width="100%" cellspacing="0">
									<tr>
										<th>Nom</th>
										<th>Acronym</th>
										<th>Active</th>
									</tr>
									<?php
										require_once("./CLASS/dbsubdiscipline.class.php");

										$database = new DBSubDiscipline();
										$disciplineid = $discipline->getID();

										foreach($database->getSubDisciplines("disciplineid = $disciplineid") as $subdiscipline) {
											echo "<tr><td><a href='./subdiscipline_detail.php?subdisciplineid=".$subdiscipline->getID()."'>".$subdiscipline->getTitle()."</a></td><td>".$subdiscipline->getAcronym()."</td><td align='center'>";
											if($subdiscipline->isActive() == "Y")	echo " Oui";
											else	echo "Non";
											echo "</td></tr>";
										}
									?>
								</table>
								<p align="right">
									<label>&nbsp;</label>
									<a href="subdiscipline_add.php?disciplineid=<?php echo $discipline->getID(); ?>" title="Ajouter une sous-discipline"><img src="./design/images/icons/16_add.png" alt="Ajouter une sous-discipline" /></a>	<!-- //	?id=<?php echo $line['disciplineid']; ?>	-->
								</p>
							</fieldset>
						</form>
					</td>
				</tr>
			</table>
			<br />
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