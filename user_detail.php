<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$userid = $_SESSION['uid'];
	if(!empty($_GET['uid']))	$userid = $_GET['uid'];
	
	$function = "userdetail";
	require_once("./CONFIG/config.php");
	require_once("./CLASS/dbusers.class.php");
	require_once("./library/vcf.php");
		
	if(($_SESSION['status_in'] < $line['rightin']) && ($_SESSION['status_out'] < $line['rightout'])) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

	$database = new DBUsers();
	$user = $database->getUser($userid);
	
	if(isset($_POST['export'])) {
		$data = array(
            'firstname' => $user->getFirstName(),
			'surname' => $user->getLastName(),
			'nickname' => '',
			'birthday' => $user->getBirthDate(),
			'company' => 'La Vaillante Tubize',
			'jobtitle' => $user->getProfession(),
			'workbuilding' => '',
			'workstreet' => '',
			'worktown' => '',
			'workcounty' => '',
			'workpostcode' => '',
			'workcountry' => '',
			'worktelephone' => '',
			'workemail' => '',
			'workurl' => '',
			'homebuilding' => '',
			'homestreet' => $user->getAddress(),
			'hometown' => $user->getCity(),
			'homecounty' => $user->getCity(),
			'homepostcode' => $user->getPostal(),
			'homecountry' => 'Belgique',
			'hometelephone' => $user->getPhone(),
            'homeemail' => $user->email(),
			'homeurl' => '',
			'mobile' => $user->gsm(),
			'notes' => ''
		);
		
		if(!empty($line['box'])) {
			$data[5] .= " boite : ".$user->getBox();
		}
				
		$vCard = new VCF($data);
		$vCard->show();
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>.: <?php	echo $user->getLastName().", ".$user->getFirstName();	?> :.</TITLE>
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
				<div id="post">
					<h2><a><?php	echo $user->getLastName().", ".$user->getFirstName();	?></a></h2>
					<table align="center">
						<tr>
							<td>
								<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']."?uid=".$userid; ?>" enctype="multipart/form-data">
								<fieldset>
									<legend>Informations</legend>
									<p>
										<label>Nom</label>
										<?php	echo $user->getLastName().", ".$user->getFirstName();	?>
									</p>
									<p>
										<label>Sexe </label>
										<?php	echo $user->displayGender();	?>
									</p>
									<p>
										<label>NISS </label>
										<?php	echo $user->getNiss();	?>
									</p>
									<p>
										<label>Date de naissance</label>
										<?php 
											echo $user->displayBirthDate();
											$age = floor((time() - strtotime($user->getBirthDate()))/(60*60*24*365.2425));
											echo "&nbsp;&nbsp;($age ans)";
										?>
									</p>
									<?php
										if($user->getBirthPlace() == "") {
									?>
									<p>
										<label>Lieu de naissance </label>
										<?php	echo $user->getBirthPlace();	?>
									</p>
									<?php
										}
									?>
									<p>
										<label>Adresse </label>
										<?php
											$target = $user->getAddress()." ".$user->getPostal()." ".$user->getCity();
											echo "<a href='http://maps.google.be/maps?q=".$target."&hl=fr&ie=UTF8' target='_blank'>".$user->getAddress()."</a>";		
											if($user->getBox() != "") {	echo "</p><p><label>&nbsp;</label>&nbsp;<a href='http://maps.google.be/maps?q=".$target."&hl=fr&ie=UTF8 target='_blank'>Boite : ".$user->getBox()."</a>";	}
											echo "<br /><label>&nbsp;</label>&nbsp;<a href='http://maps.google.be/maps?q=".$target."&hl=fr&ie=UTF8' target='_blank'>".$user->getPostal()." ".$user->getCity()."</a>";
										?>
									</p>
									
									<?php
										if($user->getPhone() != "") {
									?>
									<p>
										<label>Téléphone</label>
										<?php	echo $user->displayPhone();	?>
									</p>
									<?php
										}
										
										if($user->getGsm() != "") {
									?>
									</p>
									<p>
										<label>GSM</label>
										<?php 	echo $user->displayGsm();	?>
									</p>
									<?php
										}
										
										if(!empty($line['profession'])) {
									?>
									<p>
										<label>Profession</label>
										<?php	echo $user->getProfession();	?>
									</p>
									<?php
										}
									?>
									<p>
										<label>Adresse email</label>
										<a href="mailto:<?php	echo $user->getEmail();	?>"><?php	echo $user->getEmail();	?></a>
									</p>
									<p>
										<label>N° de licence FFG</label>
										<?php	echo $user->getFfgID();	?>
									</p>
									<p>
										<label>Carnet d'adresse ?</label>
										<?php 
											if($user->IsBookmarked() == 'Y')	echo "Oui";
											else	echo "Non";
										?>
									</p>
									<p>
										<label>Dernière connexion</label>
										<?php	echo $user->getLastCnx();	?>
									</p>
									<p>
										<label>Dernière Validation</label>
										<?php	echo $user->getLastFinished();	?>
									</p>
									<hr />
									<p>
										<label>N° de Compte</label>
										<?php
											echo $user->getAccount();
											if(strlen($user->getAccount()) == 19)
												echo "&nbsp;&nbsp;&nbsp;(<font size='1'>".$user->displayAccount().")</font>";
												
										?>
									</p>
									<p>
										<label>Entraineur Niveau</label>
										<?php	echo $user->getTrainerlevel();	?>&nbsp;<font size="1">(<?php	echo $user->getReward();	?>€/h)</font>
									</p>
									<p>
										<label>Juge Niveau</label>
										<?php	echo $user->getJudgelevel();	?>
									</p>
									<p>
										<label>Statut pédagogique</label>
										<?php	echo $user->getLabelIn();	?>
									</p>
									<?php
										if($_SESSION['status_out'] >= 4) {
									?>
									<p>
										<label>Statut gestionnaire</label>
										<?php	echo $user->getLabelOut();	?>
									</p>
									<?php
										}
									?>
									<table width="100%" class="noprint">
										<tr>
											<td width="50%" align="left">
												<input type="submit" name="export" value="exporter" />
											</td>
											<td width="50%" align="right">
												<?php
													if($_SESSION['status_out'] >= 4 || $_SESSION['uid'] == $_GET['uid'])
														echo "<a href='user_update.php?uid=$userid'><img src='./design/images/icons/16_Edit.png' /></a>";
													else
														echo "&nbsp;";
												?>
											</td>
										</tr>
									</table>
									</fieldset>
					            </form>
			           		</td>
			           	</tr>
					</table>
					<?php
						// if(date("n") >= "8")	$season = date("Y")."-".(date("Y") + 1);
						// else	$season = (date("Y") - 1)."-".date("Y");

						// $query = "SELECT DISTINCT(season) FROM xtr_istrainer WHERE userid = $userid";
						// $result_season = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (istrainer) !<br />".$result_season."<br />".mysql_error(), E_USER_ERROR);

						// $query = "SELECT C.courseid, C.day, C.h_begin, C.h_end, SD.acronym AS sdacronym, D.acronym AS dacronym FROM xtr_istrainer AS IT, xtr_course AS C, xtr_subdiscipline AS SD, xtr_discipline AS D WHERE IT.userid = $userid AND IT.courseid = C.courseid AND C.subdisciplineid = SD.subdisciplineid AND SD.disciplineid = D.disciplineid  AND IT.season = '$season' ORDER BY daynumber";
						// $result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (user, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
						
						// if(mysql_fetch_array($result)) {
						// 	mysql_data_seek($result, 0);
						
					?>
<!-- 					<br />
					<table align="center">
						<tr>
							<td>
								<form class="formulaire" name="formulaire">
								<fieldset>
									<legend>
										Cours donnés en <select name="season">
															<option value="default"></option>
															<?php
																while($line = mysql_fetch_array($result_season)) {
																	echo "<option value=\"".$line['season']."\"";
																	if($line['season'] == $season)	echo " selected";
																	echo ">".$line['season']."</option>";
																}
															?>
														</select>
									</legend>
									<?php
										while($line = mysql_fetch_array($result)) {
									?>
									<p>
										<label><a href="course_detail.php?courseid=<?php echo $line['courseid'] ?>"><?php echo $line['dacronym']." - ".$line['sdacronym']; ?></a></label>
										<?php	echo $line['day']." (de ".substr($line['h_begin'], 0, 5)." à ".substr($line['h_end'], 0, 5).")";	?>
									</p>
									<?php
										}
									?>
									</fieldset>
					            </form>
			           		</td>
			           	</tr>
					</table> -->
					<?php
						// }
					?>
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