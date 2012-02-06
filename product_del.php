<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$function = "productupdate";
	require_once("./CONFIG/config.php");
	
	if(empty($_GET['productid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=Produit&referrer=index.php");
    	exit;
	}
	
	if(($_SESSION['status_in'] < $line['statusin']) && ($_SESSION['status_out'] < $line['statusout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

	$productid = $_GET['productid'];
	require_once("./CLASS/dbproduct.class.php");

	$database = new DBProduct();
	
	if(!empty($_POST['yes']) || !empty($_POST['no'])) {
		if(!empty($_POST['yes']))
			$database->deleteProduct($productid);

		header("Location: ./product_listing.php");
		exit;
	}

	$myProduct = $database->getProduct($productid);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: Suppression de Matériel :.</title>
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
			<h2><a>Suppression de Matériel</a></h2>
			<br />
			<table align="center">
				<tr>
					<td>
						<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?productid=".$productid; ?>" onSubmit="return checkForm(this.form)">
						<fieldset>
							<legend>Informations</legend>
							<p>
								<label>Nom/Objet</label>
								<?php echo $myProduct->getName(); ?>
							</p>
							<p>
								<label>Quantité</label>
								<?php echo $myProduct->getQuantity(); ?>
							</p>
							<p>
								<label>Détails</label>
								<textarea name="detail" cols="33" rows="4"><?php echo $myProduct->getDetail(); ?></textarea>
							</p>
							<hr />
							<p>Etes-vous sûr(e) de vouloir supprimer ce matériel ?</p>
							<p align="center"><input type="submit" name="yes" value="Oui" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="no" value="Non" /></p>
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
						<p align="justify">Vous vous apprétez à supprimer un matériel. En as de suppression, toutes les informations relatives à ce matériel seront définitivement effacées.</p>
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