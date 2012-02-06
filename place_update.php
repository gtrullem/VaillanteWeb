<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if($_SESSION['status_out'] < 4) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

	if(empty($_GET['placeid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=place&referrer=place_listing.php");
		exit;
	}
	
	require_once("./CLASS/dbplace.class.php");
	$placeid = $_GET['placeid'];
	$database = new DBPlace();

	
	if(isset($_POST['submit'])) {
		require_once("./CLASS/objectplace.class.php");

		if(isset($_POST['isLocal']))	$isLocal = "1";
		else	$isLocal = "0";

		$place = new Place($placeid, mysql_real_escape_string(stripslashes(trim($_POST['name']))), mysql_real_escape_string(stripslashes(trim($_POST['address']))), $_POST['postal'], mysql_real_escape_string(stripslashes(trim($_POST['city']))), mysql_real_escape_string(stripslashes(trim($_POST['country']))),$_POST['nbkm'], $isLocal);
		
		$database->updatePlace($place);
		
		header("Location: ./place_listing.php");
		exit;
	}

	$place = $database->getPlace($placeid);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Nouveau lieu :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<SCRIPT language="javascript">
		function checkForm(formulaire)
		{	
			if(document.formulaire.name.value.length < 3) {
				alert('Veuillez indiquer le nom du lieu.');
				document.formulaire.name.focus();
				return false;
			}

			var reg = new RegExp("^[0-9]{4,6}$");
			if(!reg.test(document.formulaire.postal.value)) {
				alert('Veuillez indiquer un code postal correct.');
				document.formulaire.postal.focus();
				return false;
			}
			
			if(document.formulaire.city.value.length < 2) {
				alert('Veuillez indiquer la ville.');
				document.formulaire.city.focus();
				return false;
			}
			
			if(document.formulaire.country.value.length < 2) {
				alert('Veuillez indiquer le pays.');
				document.formulaire.country.focus();
				return false;
			}
			
			if(document.formulaire.nbkm.value.length < 1) {
				alert('Veuillez indiquer le nombre de kilomètre depuis Tubize.');
				document.formulaire.nbkm.focus();
				return false;
			}
			
			if(document.formulaire.address.value.length < 3) {
				alert('Veuillez indiquer l\'adresse.');
				document.formulaire.address.focus();
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
			<h2><a>Modification d'un lieu</a></h2>
			<br />
			<table align="center">
				<tr>
					<td>
						<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?placeid='.$placeid; ?>" onSubmit="return checkForm(this.form)">
						<fieldset>
							<legend>Informations du lieu</legend>
							<p>
								<label>Nom *</label>
								<input type="text" id="name" name="name" maxlength="35" size="38" value="<?php echo $place->getName(); ?>" />
								<!-- <input type="hidden" id="id" name="id" value="<?php echo $placeid; ?>" /> -->
							</p>
							<p>
								<label>Adresse *</label>
								<input type="text" id="address" name="address" size="25" maxlength="100" value="<?php echo $place->getAddress(); ?>" />
							</p>
							<p>
								<label>Code postal *</label>
								<input type="text" id="postal" name="postal" size="6" maxlength="6" value="<?php echo $place->getPostal(); ?>" />
							</p>
							<p>
								<label>Ville *</label>
								<input type="text" id="city" name="city" size="20" maxlength="50" value="<?php echo $place->getCity(); ?>" />
							</p>
							<p>
								<label>Pays *</label>
								<input type="text" id="country" name="country" size="20" maxlength="50" value="<?php echo $place->getCountry(); ?>" />
							</p>
							<p>
								<label>Nb Km *</label>
								<input type="text" id="nbkm" name="nbkm" size="5" maxlength="4" value="<?php echo $place->getNbKm(); ?>" />&nbsp;<font size="1">(depuis la salle de Tubize)</font>
							</p>
							<p>
								<label>Salle du club ?</label>
								<input type="checkbox" id="islocal" name="isLocal" <?php if($place->isLocal())	echo "checked" ?>/>
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
						<p align="justify">Les champs signalés d'une étoile (*) sont obligatoires.</p>
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
</HTML>