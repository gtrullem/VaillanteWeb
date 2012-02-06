<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$function = "userlisting";
	require_once("./CONFIG/config.php");
	
	if(($_SESSION['status_in'] < $line['rightin']) && ($_SESSION['status_out'] < $line['rightout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}
	
	$where = "";
	if(isset($_POST['submit'])) {
		
		if($_POST['search'] != "*"){
			$search = explode("-", $_POST['search']);
			if($search[0] == 0)
				$where = " AND status_in = '0' AND status_out = '0'";
			elseif ($search[0] == 1)
				$where = " AND status_in = '$search[1]'";
			else
				$where = " AND status_out = '$search[1]'";
		}
	}
	$query = "SELECT u.userid, p.lastname, p.firstname, p.phone, p.gsm, p.email FROM xtr_users AS u, xtr_person AS p WHERE u.personid = p.personid".$where." ORDER BY p.lastname";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (user, person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Listing des Utilisateurs :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/tablesort.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	<script type="text/javascript" src="./library/tablesort.js"></script>
</head>

<body>
	<div id="body">
	
	<?php
		require_once('./header.php');
	?>
		
	<div id="page" class=" sidebar_right">
		<div class="container">
			<div id="frame2">
				<div id="content">
					<h2><a>Listing des Utilisateurs</a></h2>
					<?php
						if($_SESSION['status_out'] == 9) {
					?>
					<table align="center" class="no print">
						<tr>
							<td>
								<form class="formulaire noprint" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
								<fieldset>
									<legend>Information de la recherche</legend>
									<p>
										<label>Lister tous les</label>
										<select name="search">
											<option value="*" <?php if(!empty($_POST['search']) && ($_POST['search'] == "*")) { echo "selected"; } ?>> Tous </option>
											<option value="0-0" <?php if(!empty($_POST['search']) && ($_POST['search'] == "0-0")) { echo "selected"; } ?>> Désactivés </option>
											<option value="1-1" <?php if(!empty($_POST['search']) && ($_POST['search'] == "1-1")) { echo "selected"; } ?>> Entraineurs / Juges </option>
											<option value="1-2" <?php if(!empty($_POST['search']) && ($_POST['search'] == "1-2")) { echo "selected"; } ?>> Responsables Technique </option>
											<option value="2-1" <?php if(!empty($_POST['search']) && ($_POST['search'] == "2-1")) { echo "selected"; } ?>> Membres d'honneur </option>
											<option value="2-2" <?php if(!empty($_POST['search']) && ($_POST['search'] == "2-2")) { echo "selected"; } ?>> Comité </option>
											<option value="2-3" <?php if(!empty($_POST['search']) && ($_POST['search'] == "2-3")) { echo "selected"; } ?>> Comité d'inscription </option>
											<option value="2-4" <?php if(!empty($_POST['search']) && ($_POST['search'] == "2-4")) { echo "selected"; } ?>> Comité Restreint </option>
											<option value="2-9" <?php if(!empty($_POST['search']) && ($_POST['search'] == "2-9")) { echo "selected"; } ?>> Administrateurs </option>
										</select>
										&nbsp;
										<input type="submit" name="submit" value="Lister" />
									</p>
								</fieldset>
								</form>
							</td>
						</tr>
					</table>
					<?php
						}
					?>
					<br />
					<table id="table1" align="center" border="0" cellspacing="0" cellpadding="0" class="sort sortable-onload-5-6r rowstyle-alt colstyle-alt no-arrow">
						<thead>
							<tr>
								<th width="150" class="sortable"><b>&nbsp;Nom</b></th>
								<th width="100" class="sortable"><b>Prénom</b></th>
								<th width="100" class="sortable" align="center">Téléphone</th>
								<th width="110" class="sortable" align="center">GSM</th>
								<th width="32" class="noprint" align="center" >Email</th>
							</tr>
						</thead>
						<tbody class="sort">
						<?php

							require_once("./CLASS/dbusers.class.php");

							$database = new DBUsers();

							foreach($database->getUsers() as $user) {
								echo "<tr><td class='sort'><a href='user_detail.php?uid=".$user->getUserID()."' title='Modifier l'utilisateur'>".$user->getLastName()."</a></td><td class='sort'><a href='user_detail.php?uid=".$user->getUserID()."' title='Modifier l'utilisateur'>".$user->getFirstName()."</a></td><td class='sort'>".$user->displayPhone()."</td><td class='sort'>".$user->displayGsm()."</td><td align='center' class='sort noprint'><a href='mailto:".$user->getEmail()."' title='envoyer un email'><img src='./design/images/icons/16_send_mail.png' height='10' width='10' /></a></td></tr>";
							}
						?>
						</tbody>
					</table>
				<br />
			</div>
		</div>
	</div>
</div>
	
<?php
	require_once('./footer.php');
?>
</div>
</body>
</html>