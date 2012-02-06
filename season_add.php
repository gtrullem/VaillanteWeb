<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "configuration";
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] < $line['statusout']) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(!empty($_POST['add'])) {
		$query = "INSERT INTO xtr_season SET ('seasonlabel') VALUES ('".$_POST['season']."')";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (season) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	}

	if(date("n") >= "8")	$season = date("Y")."-".(date("Y") + 1);
	else	$season = (date("Y") - 1)."-".date("Y");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Configuration :.</title>
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
				<div class="post">
					<h2><a>Ajout d'une saison</a></h2>
					<br />
					<table align="center">
						<tr>
							<td>
								<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?season=".$season; ?>">
									<fieldset>
										<legend>Ajout d'une saison</legend>
										<p align="center">
											Voulez-vous ajouter la saison <?php echo $season; ?> ?&nbsp;<input type="submit" name="add" value="Oui !">
										</p>
									</fieldset>
								</form>
							</td>
						</tr>
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