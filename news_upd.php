<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	$pagename = "affiliate_upd";
	require_once("./CONFIG/config.php");

	if(($_SESSION['status_out'] != 9) && ($_SESSION['uid'] != $line['userid'])) {
		header("Location: ./index.php");
		exit;
	}
	
	if(empty($_GET['newsid'])) {
		header("Refresh: 0; url=./redirection.php?err=2&item=news&referrer=index.php");
		exit;
	}
	
	$newsid = $_GET['newsid'];
	
	if(isset($_POST['submit'])) {
		$title = mysql_real_escape_string(stripslashes(trim($_POST['title'])));
		$textbody = mysql_real_escape_string(stripslashes(trim($_POST['textbody'])));
		$disciplineid = $_POST['disciplineid'];
		if(!empty($_POST['visible']))
			$visible = "Y";
		else
			$visible = "N";
		
		$query = "UPDATE xtr_news SET title='$title', textbody='$textbody', visible='$visible' WHERE newsid = $newsid";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : UPDATE FAILED (news) !<br>$query<br />$result<br>".mysql_error(), E_USER_ERROR);
		
		$subquery = "";
		for($i=0; $i<sizeof($disciplineid); $i++) {
			$query = "SELECT newsid FROM xtr_newsisfordisc WHERE newsid = $newsid AND disciplineid = ".$disciplineid[$i];
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (NIFD) !<br>$query<br />$result<br>".mysql_error(), E_USER_ERROR);
			if(!mysql_fetch_array($result))	/* , MYSQL_NUM */
				$subquery .= "('$newsid', '$disciplineid[$i]'), ";
		}

		if(strlen($subquery) != 0) {
			$subquery = substr($subquery, 0, -2);
			$query = "INSERT INTO xtr_newsisfordisc (newsid, disciplineid) VALUES $subquery";
			$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : INSERT FAILED (NIFD) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		}
		
		// Delete of old line
		$query = "DELETE FROM xtr_newsisfordisc WHERE newsid = $newsid AND disciplineid NOT IN (";
		foreach($disciplineid as $discipline)
			$query .= $discipline.", ";
		$query = substr($query, 0, -2).")";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : DELETE FAILED (NIFD) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
		
		header("Location: ./index.php");
		exit;
	}

	$query = "SELECT xtr_news.newsid, xtr_news.userid, title, textbody, date, visible, CONCAT(lastname, ', ', firstname) AS name FROM xtr_news, xtr_users, xtr_person WHERE xtr_news.userid = xtr_users.userid AND xtr_users.personid = xtr_person.personid AND xtr_news.newsid = $newsid";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (news) !<br>$query<br />$result<br>".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result);
	
	$query ="SELECT disciplineid, title FROM xtr_discipline";
	$resultdisc = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: News - Mise à jour :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
	
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
	</script>	
	<script type="text/javascript" src="./library/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript">			
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

<body>
<div id="body">

<?php
	require_once("./header.php");
?>
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame">
			<div id="content">
				<div class="post">
					<h2><a>Mise à jour</a></h2>
					<div class="entry">
						<table align="center">
							<tr>
								<td>
								<form class="formulaire" name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?newsid='.$newsid; ?>" enctype="multipart/form-data" onSubmit="return checkForm(this.form)">
								<fieldset style="width:542px;">
									<legend>News</legend>
									<p>
										<label>Titre</label>
										<input type="text" name="title" value="<?php echo $line['title']; ?>" size="46" maxlength="70">
									</p>
									<p>
										<table>
											<tr>
												<td><label>Section(s) concernée(s)</label></td>
												<td>&nbsp;<input type="checkbox" name="discipline_all" onClick="transmit()" /> Toutes</td>
											</tr>
										
										<?php
											$querynews = "SELECT disciplineid FROM xtr_newsisfordisc WHERE newsid = $newsid ORDER BY disciplineid;";
											$resultnews = mysql_query($querynews,$connect) or trigger_error("SQL ERROR : SELECT FAILED (NIFD) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
											$linenews = mysql_fetch_array($resultnews);	          
											
											while($linedisc = mysql_fetch_array($resultdisc)) {
											  echo "<tr><td>&nbsp;</td><td>&nbsp;<input type='checkbox' name='disciplineid[]' id='disciplineid' value='".$linedisc['disciplineid']."'";

											  if($linenews['disciplineid'] == $linedisc['disciplineid']) {
												echo " checked";
												$linenews = mysql_fetch_array($resultnews);
											  }
											  echo " onClick='checkAll()' />".$linedisc['title']."</td></tr>";
											}
										?>
										</table>
									</p>
									<p>
										<textarea name="textbody" id="textbody" rows="10" cols="56"><?php echo $line['textbody']; ?></textarea>
									</p>
									<p align="right">
										<font size="1">le <i><?php echo strftime("%e %B %Y", strtotime(date("j F Y", mktime(0, 0, 0, substr($line['date'], 5, 2), substr($line['date'], 8, 2), substr($line['date'], 0, 4))))); ?></i> par <i><?php echo $line['name']; ?></i>.</font>
									</p>
									<p>
										<label>Visible</label>
										<input type="checkbox" id="visible" name="visible" <?php if($line['visible'] == "Y") { echo " checked"; } ?> />
									</p>
									<p align="center"><input type="submit" name="submit" value="Mettre à jour"></p>
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
							<li>Le corps de la news doit contenir au moins <i>160 caractères</i>.</li>
							<li>Vous devez sélectionner au moins une Discipline.</li>
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