<?php
	
	session_start();

	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "news_add";
	require_once("./CONFIG/config.php");
	
	if(isset($_POST['submit'])) {
		$title = mysql_real_escape_string(stripslashes(trim($_POST['title'])));
		$textbody = mysql_real_escape_string(stripslashes(trim($_POST['textbody'])));
		$userid = $_SESSION['uid'];
		$disciplineid = $_POST['disciplineid'];
	
		if(!empty($_POST['visible']))
			$visible = "Y";
		else
			$visible = "N";
		
		$query = "INSERT INTO xtr_news (title, textbody, userid, visible) VALUES ('$title', '$textbody', '$userid', '$visible')";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (news) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$result = mysql_query("SELECT LAST_INSERT_ID()",$connect) or trigger_error("SQL ERROR : GET LAST ID !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		$newsid = mysql_fetch_array($result, MYSQL_NUM);
		$newsid = $newsid[0];
		
		$discipline = "";
		$subquery = "";
		for($i=0; $i<sizeof($disciplineid); $i++) {
			$subquery .= "('$newsid', '$disciplineid[$i]'), ";
			$discipline .= $disciplineid[$i].", ";
		}
		$subquery = substr($subquery, 0, -2);
		$query = "INSERT INTO xtr_newsisfordisc (newsid, disciplineid) VALUES $subquery";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (newsisfordisc) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		
		if($visible == "Y") {
			$query = "SELECT email FROM xtr_person WHERE personid =".$_SESSION['pid'];
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (get author email) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			$replyto = mysql_fetch_array($result, MYSQL_NUM);
			$replyto = $replyto[0];

			$discipline = substr($discipline, 0, -2);
			$query = "SELECT CONCAT( lastname,  ', ', firstname ) AS name, email FROM xtr_person, xtr_users WHERE xtr_users.personid = xtr_person.personid AND ((xtr_users.userid IN (SELECT DISTINCT (userid) FROM xtr_istrainer, xtr_course, xtr_discipline, xtr_subdiscipline WHERE xtr_istrainer.courseid = xtr_course.courseid AND xtr_course.subdisciplineid = xtr_subdiscipline.subdisciplineid AND xtr_subdiscipline.disciplineid = xtr_discipline.disciplineid AND xtr_discipline.disciplineid IN ( $discipline ))) OR (xtr_users.status_out >= 2))";
			// $query = "SELECT CONCAT(lastname, ', ', firstname) as name, email FROM xtr_person, xtr_users WHERE xtr_users.personid = xtr_person.personid AND xtr_users.userid IN (SELECT DISTINCT (userid) FROM xtr_istrainer, xtr_course, xtr_discipline, xtr_subdiscipline WHERE xtr_istrainer.courseid = xtr_course.courseid AND xtr_course.subdisciplineid = xtr_subdiscipline.subdisciplineid AND xtr_subdiscipline.disciplineid = xtr_discipline.disciplineid AND xtr_discipline.disciplineid IN ($discipline))";
			// echo $query;
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (get email for news) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
			
			$headers ="From: \"La Vaillante - Extranet\"<extranet@lavaillantetubize.be>\n"; 
			$headers .="Reply-To: ".$replyto."\n"; 
			$headers .="Content-type: text/html; charset=iso-8859-1"."\n";
			$headers .="Content-Transfer-Encoding: 8bit"; 
			$subject = "Extranet News : ".utf8_decode(stripslashes($_POST['title']));
			
			while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$message = "Cher(e) ".$line['name']."<br /><br />Une nouvelle news a été postée par ".$_SESSION['name']." sur l'extranet, cette dernière pourrait vous intéresser.<br /><br />".nl2br(stripslashes($_POST['textbody']))."<br /><br />Bonne fin de journée.";
				mail($line['email'], $subject, utf8_decode($message), $headers);
			}

			$query = "SELECT CONCAT(lastname, ', ', firstname) as name, email FROM xtr_person, xtr_users WHERE xtr_users.personid = xtr_person.personid AND xtr_users.";
//			echo $query;
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (get email for news) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		}
		
		header("Location: ./index.php");
		exit;
	}
  
	require_once("./CLASS/dbdiscipline.class.php");
	$database = new DBDiscipline();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: La Vaillante - Nouvelle News :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	
	<script type="text/javascript" src="./library/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" language="javascript">
	function transmit()
	{
		if(document.formulaire.discipline_all.checked)
			for(var i=0; i < document.formulaire.disciplineid.length; i++)
				document.formulaire.disciplineid[i].checked = true;
		else
			for(var i=0; i < document.formulaire.disciplineid.length; i++)
				document.formulaire.disciplineid[i].checked = false;
	}
	
	function checkAll()
	{
		for(var i=0; i < document.formulaire.disciplineid.length; i++)
			if(document.formulaire.disciplineid[i].checked == false)
				document.formulaire.discipline_all.checked = false;
	}
	
	function checkForm(formulaire)
	{
		if(document.formulaire.title.value.length < 12) {
			alert('Veuillez choisir un titre d\'au moins 12 caractères.');
			document.formulaire.title.focus();
			return false;
		}

		if(!document.formulaire.discipline_all.checked) {
			var test = false;
			for(var i = 0; i < document.formulaire.disciplineid.length && !test; i++)
				if(document.formulaire.disciplineid[i].checked)
					test = true;

			if(!test) {
				alert('Veuillez sélectionner au moins une discipline');
				return false;
			}
		}

		var textbody = tinyMCE.activeEditor.getContent();
		if(textbody.length < 160) {
			alert('Veuillez composer un message d\'au moins 160 caractères.');
			document.formulaire.textbody.focus();
			return false;
		}

		return true;
	}
	
	tinyMCE.init({
		// General options
		mode : "exact",
		elements : "textbody",
		theme : "advanced",
		skin : "o2k7",
		skin_variant : "black",
		
		// REVOIR LES PLUG-INS !!!!!!
		plugins : "lists,pagebreak,style,layer,advimage,advlink,emotions,iespell,preview,media,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave", 

		// Theme options
		theme_advanced_buttons1 : "styleselect,formatselect,fontsizeselect, bold,italic,underline,strikethrough,|,forecolor,backcolor,removeformat",
		theme_advanced_buttons2 : "justifyleft,justifycenter,justifyright,justifyfull,|,numlist,bullist,outdent,indent,blockquote,|,sub,sup,|,hr,|,undo,redo,|,emotions,iespell,|,link,unlink,image,code,|,preview,|,print",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
  </script>
  <noscript>
	<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>
  </noscript>
</head>

<body class="yui-skin-sam">
<div id="body">

<?php
	require_once("./header.php");
?>
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame">
			<div id="content">
				<div class="post">
					<h2><a>Ajout d'une News</a></h2>
					<div class="entry">
						<table align="center">
							<tr>
								<td>
									<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
										<fieldset style="width:542px;">
											<legend>News</legend>
											<p>
												<label>Titre</label>
												<input type="text" name="title" size="46" maxlength="70">
											</p>
											<p>
												<table>
													<tr>
														<td><label>Section(s) concernée(s)</label></td>
														<td>
															<input type="checkbox" name="discipline_all" onClick="transmit()" /> Toutes
														</td>
														<?php
															foreach($database->getDisciplines() as $discipline)
																echo "<tr><td>&nbsp;</td><td><input type='checkbox' name='disciplineid[]' id='disciplineid' value='".$discipline->getID()."' onClick='checkAll()' /> ".$discipline->getTitle()."</td></tr>";
														?>
												</table>
											</p>
											<p>
												<textarea name="textbody" id="textbody" cols="50" rows="20"></textarea>
											</p>
											<p align="right">
												<font size="1">le <i><?php echo strftime("%e %B %Y", strtotime(date("Y-m-d"))); ?></i> par <i><?php echo $_SESSION['name']; ?></i>.</font><input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">
											</p>
											<p>
												<label>Visible</label>
												<input type="checkbox" id="visible" name="visible" checked/>
											</p>
											<p align="center"><input type="submit" name="submit" id="submit" value="Ajouter"></p>
										</fieldset>
									</form>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div id="sidebar" class="sidebar">
				<div>
					<div class="widget widget_categories">
						<h2 class="title">Informations</h2>
						<ul>
							<li>Le titre doit contenir au moins <i>12 caractères</i>.</li>
							<li>Le corps de la news doit contenir au moins <i>160 caractères</i></li>
							<br />
							<li>Un email sera envoyé a toute personne liée à une discipline sélectionnée.</li>
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