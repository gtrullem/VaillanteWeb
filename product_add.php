<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$function = "productadd";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(isset($_POST['add'])) {
		require_once("./CLASS/dbproduct.class.php");
		require_once("./CLASS/objectproduct.class.php");

		$database = new DBProduct();
		
		$myProduct = new Product(NULL, mysql_real_escape_string(stripslashes(trim($_POST['name']))), mysql_real_escape_string(stripslashes(trim($_POST['quantity']))), mysql_real_escape_string(stripslashes(trim($_POST['detail']))));
		
		$database->insertProduct($myProduct);
		
		header("Location: ./product_listing.php");
		exit;
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Ajout de Matériel :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" language="javascript" src="./library/library.js"></script>
	<script type="text/javascript" language="javascript">	
		function checkForm(formulaire)
		{
			if(document.formulaire.name.value.length < 5) {
				alert('Veuillez préciser le nom.');	
				return false;
			}
			
			if(document.formulaire.quantity.value.length < 2) {
				alert('Veuillez préciser la quantité et l\'unité.');	
				return false;
			}
			
			if(document.formulaire.detail.value .length > 160) {
				alert('Veuillez diminuer la longueur des détails : 160 caractères maximum.');	
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
			<h2><a>Ajout de Matériel</a></h2>
			<br />
			<table align="center">
				<tr>
					<td>
						<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?productid=".$productid; ?>" onSubmit="return checkForm(this.form)">
						<fieldset>
							<legend>Informations</legend>
							<p>
								<label>Nom/Objet *</label>
								<input type="text" name="name" size="46" maxlength="50" />
							</p>
							<p>
								<label>Quantité *</label>
								<input type="text" name="quantity" size="15" maxlength="15" /> (Précisez l'unité !)
							</p>
							<p>
								<label>Détails</label>
								<textarea name="detail" cols="33" rows="4"></textarea>
							</p>
							<p align="center"><input type="submit" name="add" value="Ajouter" /></p>
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