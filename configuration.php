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
	
	if(!empty($_POST['update'])) {
		$keyvalue = $_POST['keyvalue'];
		$key = $_POST['key'];
		for($i = 0; $i < sizeof($keyvalue) ; $i++) {
			$query = "UPDATE xtr_config SET value = '".$_POST['keyvalue'][$i]."' WHERE `key` = '".$_POST['key'][$i]."'";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (config) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		}
		
		$priceid = $_POST['priceid'];
		for($i = 0; $i < sizeof($priceid); $i++) {
			$query = "UPDATE xtr_price SET price = '$price[$i]' WHERE priceid = $priceid[$i]";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (config) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Configuration Extranet :.</title>
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
					<h2><a>Configuration des variables de l'Extranet</a></h2>
					<br />
					<table align="center">
						<tr>
							<td>
								<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
									<fieldset>
										<legend>Variables Globales</legend>
										<?php
											$query = "SELECT * FROM xtr_config";
											$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (config) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	
											while($line = mysql_fetch_array($result))
												echo "<p><label>".$line['keylabel']."</label><input type='text' name='keyvalue[]' size='40' value='".$line['value']."' /><input type='hidden' name='key[]' value='".$line['key']."' /></p>";
										?>
									</fieldset>
									<p align="center"><input type="submit" name="update" value="Mettre à jour"></p>
									<br />
									<fieldset>
										<legend>Cotisations</legend>
										<?php
											$query = "SELECT * FROM xtr_price ORDER BY discipline, nbhour";
											$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (price) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
											$test = false;
											while($line = mysql_fetch_array($result)) {
												if(($line['discipline'] == "TRA") && !$test) {
													echo "<p><hr /></p>";
													$test = true;
												}
												echo "<p><label>".$line['discipline']." - ".$line['nbhour']."h</label><input type='text' name='price[]' size='5' value='".$line['price']."' />€<input type='hidden' name='priceid[]' value='".$line['priceid']."' /></p>";
											}
										?>
									</fieldset>
									<p align="center"><input type="submit" name="update" value="Mettre à jour"></p>
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