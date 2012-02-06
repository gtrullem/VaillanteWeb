<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < 1) && ($_SESSION['status_out'] < 2)){
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	if(empty($_GET['id'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=prestation&referrer=prestation_listing.php");
		exit;
	}
	
	$id = $_GET['id'];
	
	if(isset($_POST['update'])){
		$datep = $_POST['datep'];
		$h_from = $_POST['h_from'];
		$h_to = $_POST['h_to'];
		$nbhour = $_POST['nbhour'];
		$id = $_POST['id'];
		
		$query = "UPDATE xtr_prestation SET datep='$datep', h_from='$h_from', h_to='$h_to', nbhour='$nbhour' WHERE id='$id';";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (prestation) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		
		header("Location: ./prestation_listing.php");
		exit();
	}
	
	// LEFT JOIN TO CHECK
	$query = "SELECT xtr_prestation.prestationid, personid, h_from, h_to, nbhour, datep, paid, CONCAT(lastname, ', ', firstname) AS name FROM xtr_prestation LEFT JOIN xtr_person ON xtr_prestation.personid = xtr_person.personid WHERE xtr_prestation.prestationid='$id' AND paid='N'";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (prestation, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>.: Modification d'une Prestation :.</TITLE>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<SCRIPT language="javascript">
		function checkForm(formulaire)
		{

			if(document.formulaire.datep.value == "") {
				alert('Veuillez entrer une date.');
				return false;
			}
			
			if(document.formulaire.h_from.value == "") {
				alert('Veuillez indiquer l\'heure de début.');
				return false;
			}
			
			if(document.formulaire.h_to.value == "") { 
				alert('Veuillez indiquer l\'heure de fin.');
				document.formulaire.reward.focus();
				return false;
			}

			if(document.formulaire.nbhour.value <= "0") {
				alert('Veuillez vérifier les heures que vous avez entré.');
				return false;
			}

			// Preparing data
			if(document.formulaire.nbhour.value.length > 2) {
				if((document.formulaire.nbhour.value % 100) > 0) {
					var temp = document.formulaire.nbhour.value % 100;
					document.formulaire.nbhour.value = parseInt((document.formulaire.nbhour.value - tmp) / 100) + parseInt(0.5);
				} else {
					document.formulaire.nbhour.value = document.formulaire.nbhour.value / 100;
				}
			}
			var date = document.formulaire.datep.value.split("/");
			document.formulaire.datep.value = date[2]+'-'+date[1]+'-'+date[0];
			
			return true;
		}
		
		function calculate() {
			if((document.formulaire.h_to.value != "") && (document.formulaire.h_from.value != "")){
				document.formulaire.nbhour.value = parseInt(document.formulaire.h_to.value) - parseInt(document.formulaire.h_from.value);
			}
		}
		
	</SCRIPT>
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
					<H2><a>Modification d'une prestation</a></H2>
					<br />
					<FORM name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?id=".$id; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
					<TABLE align="center">
						<TR>
							<TD>Moniteur :</TD>
							<TD><?php echo $line['name']; ?><input type="hidden" name="id" value="<?php echo $line['personid']; ?>"></TD>
						</TR>
						<TR>
							<TD>Date :</TD>
							<TD><input type="text" name="datep" value="<?php echo substr($line['datep'], 8, 2).'/'.substr($line['datep'], 5, 2).'/'.substr($line['datep'], 0, 4); ?>" size="10" maxlength="10"></TD>
						</TR>
						<TR>
							<TD>De :</TD>
							<TD><input type="text" name="h_from" value="<?php echo $line['h_from']; ?>" size="4" maxlength="4" onchange="calculate()"></TD>
						</TR>
						<TR>
							<TD>A :</TD>
							<TD><input type="text" name="h_to" value="<?php echo $line['h_to']; ?>" size="4" maxlength="4" onchange="calculate()"></TD>
						</TR>
						<TR>
							<TD><font size="3">Nombre d'heures :</font></TD>
							<TD><input type="text" name="nbhour" size="4" maxlength="4" value="<?php echo $line['nbhour']; ?>" READONLY><font size="1">(Calculé automatiquement)<font size="1"></TD>
						</TR>
						<!--
						<TR>
							<TD>Commentaires :</TD>
							<TD>&nbsp;</TD>
						</TR>
						-->
					</TABLE>
					<p align="center"><input type="submit" name="update" value="Mettre à jour"></p>
					</FORM>
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