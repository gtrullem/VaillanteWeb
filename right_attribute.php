<?php
	
	session_start();

	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] < 9) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(empty($_GET['id'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=prestation&referrer=prestation_listing.php");
		exit;
	}
	$id = $_GET['id'];
	
	if(!empty($_POST['update'])) {
		$statusin = $_POST['statusin'];
		$statusout = $_POST['statusout'];
		
		$query = " UPDATE xtr_functionright SET statusin = '$statusin', statusout = '$statusout' WHERE functionrightid = $id";
//		echo "<br /><br /><br />".$query;
		
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (functionright) !<br />".$result_out."<br />".mysql_error(), E_USER_ERROR);
		
		header("Location: ./right_summary.php");
		exit;
	}
	
	$query = " SELECT * FROM xtr_functionright WHERE functionrightid = $id";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (functionright) !<br />".$result_out."<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Attribution des Droits :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" language="javascript">
	function checkForm(formulaire)
	{
		if(document.formulaire.statusin.value.length == 0) {
			alert('Veuillez choisir le statut pédagogique.');
			document.formulaire.statusin.focus();
			return false;
		}
		
		if(document.formulaire.statusout.value.length == 0) {
			alert('Veuillez choisir le statut gestionnaire.');
			document.formulaire.statusout.focus();
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
				<div class="post">
					<h2><a>Attribution des droits</a></h2>
					<div class="entry">
						<table align="center">
							<tr>
								<td>
									<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?id=".$id; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
										<fieldset>
											<legend>Attribution de droit pour une fonction</legend>
											<p>
												<label>Fonctionalité</label>
												<b>&nbsp;<?php echo $line['feature']; ?></b>
											</p>
											<p>
												<label>Titre</label>
												<b>&nbsp;<?php echo $line['name']; ?></b>
											</p>
											<p>
												<label>Nom de la fonction</label>
												&nbsp;<?php echo $line['function']; ?>
											</p>
											<p>
												<label>Action</label>
												<b>&nbsp;<?php echo $line['action']; ?></b>
											</p>
											<p>
												<?php
													$query = "SELECT * FROM xtr_userright WHERE scopein = 1 AND value > 0 ORDER BY value";
													$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (userright) !<br />".$result_out."<br />".mysql_error(), E_USER_ERROR);
												?>
												<label>Statut Pédagogique</label>
												<select name="statusin">
													<option value=""></option>
													<?php
														while($linein = mysql_fetch_array($result)) {
															echo "<option value=\"".$linein['value']."\"";
															if($line['rightin'] == $linein['value']) echo " selected";
															echo ">".$linein['label']."</option>";
														}
													?>
												</select>
											</p>
											<p>
												<?php
													$query = "SELECT * FROM xtr_userright WHERE scopeout = 1 AND value > 0 ORDER BY value";
													$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (userright) !<br />".$result_out."<br />".mysql_error(), E_USER_ERROR);
												?>
												<label>Statut Gestionnaire</label>
												<select name="statusout">
													<option value=""></option>
													<?php
														while($lineout = mysql_fetch_array($result)) {
															echo "<option value=\"".$lineout['value']."\"";
															if($line['rightout'] == $lineout['value']) echo " selected";
															echo ">".$lineout['label']."</option>";
														}
													?>
												</select>
											</p>
										</fieldset>
									<p align="center"><input type="submit" name="update" value="Mettre à jour" />
									</form>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<!--------------------------------------------------------------------------------->
			<div id="sidebar" class="sidebar">
				<div>
					<div class="widget widget_categories">
						<h2 class="title">Informations</h2>
						<ul>
							<li>(A VENIR)</li>
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