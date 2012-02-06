<?php
	
	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	require_once("./CONFIG/config.php");
	require_once("./CLASS/dbnews.class.php");

	$database = new DBNews();
	
	if(!empty($_GET['limit']) && ($_GET['limit'] > 5))	$limit = " LIMIT ".($_GET['limit'] - 5).", ".$_GET['limit'];
	else	$limit = " LIMIT 0, 5";
	
	if($_SESSION['status_out'] == 9)	$where = "";
	else	$where = "AND visible = 'Y'";
	
	if(!empty($_GET['disc']) && ($_GET['disc'] != "*" )) {
		$disciplineid = $_GET['disc'];
		$query = "SELECT N.newsid AS nid, N.userid, N.title AS ntitle, N.textbody, N.visible, N.date, CONCAT(lastname,  ', ', firstname) AS name, D.title AS dtitle, D.acronym FROM xtr_news AS N, xtr_newsisfordisc, xtr_discipline AS D, xtr_users, xtr_person WHERE xtr_newsisfordisc.newsid = N.newsid AND xtr_newsisfordisc.disciplineid = D.disciplineid AND N.userid = xtr_users.userid AND xtr_users.personid = xtr_person.personid AND D.disciplineid = $disciplineid";
	} else
		$query = "SELECT N.newsid AS nid, N.userid, N.title AS ntitle, N.textbody, N.visible, N.date, CONCAT(lastname,  ', ', firstname) AS name FROM xtr_news AS N, xtr_users, xtr_person WHERE N.userid = xtr_users.userid AND xtr_users.personid = xtr_person.personid";

	$query .= " $where ORDER BY date DESC $limit";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (news, user, person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$ok = false;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>.: LA VAILLANTE TUBIZE :.</title>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<script type="text/javascript" language="javascript">
		var myDate = new Date();
		var month = 1 + (myDate.getMonth());
		var year = myDate.getFullYear();
		
		function showCal(theMonth)
		{
			month = theMonth;
			if(month > 12) {
				year += 1;
				month -= 12;
			} else if (month < 1) {
				month = 12;
				year -= 1;
			}
			
			if (window.XMLHttpRequest) {		// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} else {							// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
		
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
					document.getElementById("monthcal").innerHTML=xmlhttp.responseText;
			}

			xmlhttp.open("GET","index_cal.php?month="+month+"&year="+year,true);
			xmlhttp.send();
		}
	</script>
	<noscript>
		<p class="important">Javascript est désactivé. Vous devez l'activer afin de pouvoir utiliser le site dans des conditions optimales.</p>
	</noscript>
</head>

<body onLoad="javascript:showCal(month);">
<div id="body">

<?php
	require_once("./header.php");
?>
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame">
			<div id="content">
				<?php
					$i = 0;
					while($line = mysql_fetch_array($result)) {
						$i++;
						echo "<div class='post'>\n<h2><img src='./design/images/icon_new/32x32/comments.png' />&nbsp;<a href='news_upd.php?newsid=".$line['nid']."'>".$line['ntitle']."</a>";
						if((($_SESSION['status_out'] >= 4) || ($_SESSION['status_in'] >= 2)) && ($line['visible'] == "N"))
							echo " (invisible)";

						echo "</h2><font size='1'>Le <a>".ucwords(strftime("%e %B %Y", strtotime(date("j F Y", mktime(0, 0, 0, substr($line['date'], 5, 2), substr($line['date'], 8, 2), substr($line['date'], 0, 4))))))."</a> par <a href='user_detail.php?userid=".$line['userid']."'>".$line['name']."</a>.<br />";
						
						$query = "SELECT * FROM xtr_newsisfordisc, xtr_discipline WHERE xtr_newsisfordisc.newsid=".$line['nid']." AND xtr_newsisfordisc.disciplineid = xtr_discipline.disciplineid";
						$result2 = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (discipline, for_discipline) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
						
						$discipline = mysql_fetch_array($result2);
						echo "<a href='index.php?disc=".$discipline['disciplineid']."'>".$discipline['title']."</a>";
						
						while($discipline = mysql_fetch_array($result2))
							echo ", <a href='index.php?disc=".$discipline['disciplineid']."'>".$discipline['title']."</a>";
						
						echo ".</font>\n<div class='entry'>".$line['textbody']."</div>\n</div>";
					}
					
					if($i == 5) $ok = true;
				?>
				<br />
				<table align="center" width="100%" border="0" cellspadding="0" cellspacing="0">
					<tr>
						<td align="left">
							<font size="1">
							<?php
								if(isset($_GET['limit']) && ($_GET['limit'] > 5))
									echo "<a href='index.php?limit=".($_GET['limit'] - 5)."'><< News suivantes</a>";
							?>
							</font>
						</td>
						<td align="right">
							<font size="1">
							<?php
								if($ok) {
									echo "<a href='index.php?limit=";
									if(isset($_GET['limit']))	echo ($_GET['limit'] + 5)."'>";
									else	echo "10'>";
								}
							?>
							News précédentes >></a>
							</font>
						</td>
					</tr>
				</table>
			</div>

			<div id="sidebar" class="sidebar">
				<div>
					<div class="widget widget_categories">
						<div id="monthcal" style="height:206px"></div>
						<table width="100%" cellspacing="0" cellpadding=0"><tr><td width="50%"><input type="submit" name="xx" value="<<" onClick="showCal((month-1))"><td width="50%" align="right"><input type="submit" name="xx" value=">>" onClick="showCal((month+1))"></td></tr></table>
						<br />
						<h2 class="title">Prochains Evènements</h2>
						<ul>
							<?php
								$date = date("Y-m-d",mktime(0,0,0,date("m"),date("j"),date("Y")));
								$query = "SELECT eventid, dbegin, title FROM xtr_event WHERE dbegin >= '$date' ORDER BY dbegin LIMIT 0, 10;";
								$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (event) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
								
								$month = Array("Jan", "Fév", "Mar", "Avr", "Mai", "Juin", "Jui", "Aout", "Sept", "Oct", "Nov", "Dec");
								while ($line = mysql_fetch_array($result)) {
									echo "<li class=\"cat-item\"><a href=\"./event_detail.php?id=".$line['eventid']."\">".substr($line['dbegin'], 8, 2)." ".$month[substr($line['dbegin'], 5, 2) - 1]." : ";
									
									if(strlen($line['title']) <= 25)	echo $line['title'];
									else	echo substr($line['title'], 0, 21)."...";
									
									echo "</a></li>";
								}
							?>
							<li><a href="annual_calendar.php" target="_blank">Calendrier Annuel</a></li>
						</ul>
						<br />
						<h2 class="title">Disciplines</h2>
						<ul>
							<li class="cat-item"><a href="./index.php?disc=*">Toutes</a></li>
							<?php
								require_once("./CLASS/dbdiscipline.class.php");
								$database = new DBDiscipline();

								foreach($database->getDisciplines() as $discipline) /* WHERE active = 'Y' */
									echo "<li class=\"cat-item\"><a href=\"./index.php?disc=".$discipline->getID()."\">".$discipline->getTitle()."</a></li>";
							?>
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