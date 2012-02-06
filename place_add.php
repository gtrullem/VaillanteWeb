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
	
	if(isset($_POST['submit'])) {
		require_once("./CLASS/objectplace.class.php");
		require_once("./CLASS/dbplace.class.php");

		if(isset($_POST['isLocal']))	$isLocal = "1";
		else	$isLocal = "0";

		$place = new Place(null, mysql_real_escape_string(stripslashes(trim($_POST['name']))), mysql_real_escape_string(stripslashes(trim($_POST['address']))), $_POST['postal'], mysql_real_escape_string(stripslashes(trim($_POST['city']))), mysql_real_escape_string(stripslashes(trim($_POST['country']))),$_POST['nbkm'], $isLocal);

		$database = new DBPlace();
		$database->insertPlace($place);

		header("Location: ./place_listing.php");
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Ajout d'un lieu :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script language="javascript">
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
			
			document.formulaire.address.value = document.formulaire.address.value+", "+document.formulaire.number.value;
			if(document.formulaire.address.value.length < (document.formulaire.number.length + 2)) {
				document.formulaire.address.value = document.formulaire.address.value.substr(0, (document.formulaire.address.value.length - (document.formulaire.number.lenght + 2)));
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
			<!-- ========================= BEGIN FORM ====================== -->
			<h2><a>Ajout d'un lieu</a></h2>
			<table align="center">
				<tr>
					<td>
						<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
							<fieldset>
								<legend>Information sur le lieu</legend>
								<?php
									if(isset($err)) {
										echo "<p align=\"center\" class=\"important\">$err</p>";
									}
								?>
								<p>
									<label>Nom *</label>
									<input type="text" id="name" name="name" maxlength="35" size="38" />
								</p>
								<p>
									<label>Adresse *</label>
									<input type="text" id="address" name="address" size="25" maxlength="100" />&nbsp;n° <input type="text" id="number" name="number" size="3" maxlength="3" />
								</p>
								<p>
									<label>Code postal *</label>
									<input type="text" id="postal" name="postal" size="6" maxlength="6" />
								</p>
								<p>
									<label>Ville *</label>
									<input type="text" id="city" name="city" size="20" maxlength="50" />
								</p>
								<p>
									<label>Pays *</label>
									<input type="text" id="country" name="country" size="20" maxlength="50" />
								</p>
								<p>
									<label>Km *</label>
									<input type="text" id="nbkm" name="nbkm" size="5" maxlength="4" />&nbsp;<font size="1">(depuis la salle de Tubize)</font>
								</p>
								<p>
									<label>Salle du club ?</label>
									<input type="checkbox" id="islocal" name="isLocal" />
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