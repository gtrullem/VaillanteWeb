<?php

	session_start();
	
	$pagename = "event_listing";
	require_once("./CONFIG/config.php");

	if(!empty($_POST['seasonlabel']))
		$season = $_POST['seasonlabel'];
	else
		if(date("n") >= "8")
			$season = date("Y")."-".(date("Y") + 1);
		else
			$season = (date("Y") - 1)."-".date("Y");
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Liste des évènements :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<link href="./design/tinyTips.css" rel="stylesheet" type="text/css" media="screen" />

	<script type="text/javascript" src="./library/jquery.min.js"></script>
	<script type="text/javascript" src="./library/jquery.tinyTips.js"></script>
	<script type="text/javascript" src="./library/tablesort.js"></script>
	<script language="javascript">

		$(document).ready(function() {
			$('a.tTip').tinyTips('title');
		});

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
		<div id="frame2">
			<div id="content">
				<div id="post">
					<!--========================= BEGIN CODE ===========================-->
					<h2><a>Liste des évènements</a></h2>
					<br />
					<table align="center">
						<tr>
							<td>
								<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
									<fieldset>
										<legend>Evènements de saison</legend>
										<p>
											<label>Saison</label>
											<select name="seasonlabel">
												<?php
													$query = "SELECT * FROM xtr_season ORDER BY seasonlabel";
													$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (season) !<br />$query<br />$resultyear<br />".mysql_error(), E_USER_ERROR);
												
													while($line = mysql_fetch_array($result)) {
														echo "<option value='".$line['seasonlabel']."'";
														if($line['seasonlabel'] === $season)
															echo " selected";
														echo ">".$line['seasonlabel']."</option>";
													}
												?>
											</select>
											&nbsp;&nbsp;&nbsp;<input type="submit" name="display" value="Afficher" />
										</p>
									</fieldset>
								</form>
							</td>
						</tr>
					</table>
					<br />
					<table id="table0" align="center" border="0" cellspacing="0" cellpadding="0" class="sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow">
						<thead>
							<tr>
								<th width="225" class="sortable">Titre</th>
								<th width="70" class="sortable">Type</th>
								<th width="200" class="sortable">Lieu</th>
								<th width="55" class="sortable">Date</th>
								<th width="135" class="sortable">Contact</th>
								<th width="35"></th>
							</tr>
						</thead>
						<tbody class="sort">
						<?php
							require_once("./CLASS/dbevent.class.php");
							$database = new DBevent();

							foreach ($database->getEventsFromSeason($season) as $event)
								echo "<tr valign='middle'><td class='sort'><a class='tTip' a href='event_detail.php?eventid=".$event->getID()."' title='".$event->getInformation()."'>".$event->getTitle()."</a></td><td class='sort'>".$event->getEventType()."</td><td class='sort'>".$event->getPlace()->getName()."</td><td class='sort' align='center'>".$event->displayBeginDate()."</td><td class='sort'><a href='./person_detail.php?personid=".$event->getContact()->getID()."'>".$event->getContact()->getLastName().", ".$event->getContact()->getFirstName()."</td><td class='sort noprint'><a href='event_update.php?eventid=".$event->getID()."' title=\"Modifier l'évènement\"><img src='./design/images/icons/16_Edit.png' height='10' width='10' /></a>&nbsp;&nbsp;&nbsp;<a href='event_del.php?eventid=".$event->getID()."' title=\"Supprimer l'évènement\"><img src='./design/images/icons/16_delete.png' height='10' width='10' /></a></td></tr>";
						?>
						</tbody>
					</table>
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