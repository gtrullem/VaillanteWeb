<?php

	session_start();
	
	if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>.: La Vaillante - Documents à télécharger :.</TITLE>
	<link rel="stylesheet" href="./design/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/AdminBar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="./design/print.css" type="text/css" media="print" />
</head>

<body>
<?php
	require_once("./header.php");
?>
<div id="page" class=" sidebar_right">
	<div class="container">
		<div id="frame2">
			<div id="content">
				<div class="post">
					<h2><a>Documents à télécharger</a></h2>
					<div class="entry">
						<table align="center">
							<tr>
								<td>
									<form class="formulaire">
										<fieldset>
										<legend>Examen ADEPS - Niveau 2</legend>
										<p class="important"><b>ATTENTION :</b> Toutes les réponses des examens ne sont pas correctes, vérifiez-les avec les syllabus !!!</p>
										<p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/1993%20-%201sess.pdf">1993 - 1ère Session</a></p>
										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/1997%20-%202sess.pdf">1997 - 2ème Session</a></p>
										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/1999%20-%201sess.pdf">1999 - 1ère Session</a></p>
										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/1999%20-%202sess.pdf">1999 - 2ème Session</a></p>
										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/2002%20-%201sess.pdf">2002 - 1ère Session</a></p>
										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/2002%20-%202sess.pdf">2002 - 2ème Session</a></p>
										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/2003%20-%201sess.pdf">2003 - 1ère Session - 1</a></p>
										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/2003%20-%201sess1.pdf">2003 - 1ère Session - 2</a></p>
										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/2004%20-%201sess.pdf">2004 - 1ère Session</a></p>
										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/2007%20-%201sess.pdf">2007 - 1ère Session</a></p>
										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/2008%20-%201sess.pdf">2008 - 1ère Session</a></p>
										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/2010%20-%201sess.pdf">2010 - 1ère Session</a></p>
										  <hr />
										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/syllabus_Anatomie.pdf">Syllabus d'Anatomie</a></p>
										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/syllabus_Biometrie.pdf">Syllabus de Biométrie</a></p>
										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/syllabus_Methodologie.pdf">Syllabus de Méthodologie</a></p>
<!--										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/syllabus_Musculation.pdf">Syllabus de Musculation</a></p> -->
										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/syllabus_Physiologie.pdf">Syllabus de Physiologie</a></p>
										  <p><a href="http://lavaillantetubize.be/Extranet/documents/ADEPS_N2/syllabus_ThEntrainement.pdf">Syllabus de Théorie de l'entraînement</a></p>
										
									</fieldset>
								</form>
							</td>
							<td>
								<form class="formulaire">
									<fieldset>
										<legend>FFG Théorie commune - Niveau 1</legend>
										<p><a href="http://lavaillantetubize.be/Extranet/documents/FFG_N1/Theorie%20Commune%20Syllabus.pdf">Syllabus officiel 2010 FFG Niveau 1</a> (PDF - 1.9Mo)</p>
										<p><a href="http://lavaillantetubize.be/Extranet/documents/FFG_N1/FFG-TR-Niveau1.pdf">Syllabus 2010 FFG Niveau 1 (revu et corrigé)</a> (PDF - 16Mo)</p>
										<p>&nbsp;</p>
									</fieldset>
								</form>
							<p>&nbsp;</p>
								<form class="formulaire">
									<fieldset>
										<legend>FFG Théorie commune - Niveau 2</legend>
										<p><a href="http://lavaillantetubize.be/Extranet/documents/FFG_N2/Biomécanique%20(79p).doc">Syllabus Biomécanique (Léon Delisse)</a> (DOC - 2.3Mo)</p>
										<p><a href="http://lavaillantetubize.be/Extranet/documents/FFG_N2/Planification%20de%20l'entrainement%20(96%20p).docx">Planification de l'entraînement (Léon Delisse)</a> (DOCX - 5.8Mo)</p>
										<p><a href="http://lavaillantetubize.be/Extranet/documents/FFG_N2/Préparation%20physique%20en%20gymnastique%20(141%20pages).doc">Préparation Physique (Léon Delisse)</a> (DOC - 5.9Mo)</p>
									</fieldset>
								</form>
								<!--
								<form class="formulaire">
										<fieldset>
										<legend>Examen ADEPS - Niveau 3</legend>
										<p>(A VENIR)</p>
									</fieldset>
								</form>
								-->
							</td>
						</tr>
						<!--
						<tr>
							<td>
								<form class="formulaire">
									<fieldset>
										<legend>FFG Théorie commune - Niveau 1</legend>
										<p><a href="http://lavaillantetubize.be/Extranet/documents/FFG_N1/Theorie%20Commune%20Syllabus.pdf">Syllabus officiel 2010 FFG Niveau 1</a> (PDF - 1.9Mo)</p>
										<p><a href="http://lavaillantetubize.be/Extranet/documents/FFG_N1/FFG-TR-Niveau1.pdf">Syllabus 2010 FFG Niveau 1 (revu et corrigé)</a> (PDF - 16Mo)</p>
										<p>&nbsp;</p>
									</fieldset>
								</form>
							</td>
							<td>
								<form class="formulaire">
									<fieldset>
										<legend>FFG Théorie commune - Niveau 2</legend>
										<p><a href="http://lavaillantetubize.be/Extranet/documents/FFG_N2/Biomécanique%20(79p).doc">Syllabus Biomécanique (Léon Delisse)</a> (DOC - 2.3Mo)</p>
										<p><a href="http://lavaillantetubize.be/Extranet/documents/FFG_N2/Planification%20de%20l'entrainement%20(96%20p).docx">Planification de l'entraînement (Léon Delisse)</a> (DOCX - 5.8Mo)</p>
										<p><a href="http://lavaillantetubize.be/Extranet/documents/FFG_N2/Préparation%20physique%20en%20gymnastique%20(141%20pages).doc">Préparation Physique (Léon Delisse)</a> (DOC - 5.9Mo)</p>
									</fieldset>
								</form>
							</td>
						</tr>
						-->
					</table>
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