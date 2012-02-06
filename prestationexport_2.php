<?
session_start();

if(!isset($_SESSION['uid'])) {
		header("Refresh: 0; url=./redirection.php");
		exit;
	}
	
	if(($_SESSION['status_in'] < 3) && ($_SESSION['status_out'] < 4)) {
		header("Refresh: 0; url=./redirection.php?err=1");
		exit;
	}

require_once("config.php");
require_once("Spread~1/writer.php");

/*************************************************************** CONVERTION DES JOURS ******************************************************************************/
function jour($d,$m,$y)
{
	$date = date("D", mktime(0, 0, 0, date($m) , date($d), date($y)));
	$days = array('`Mon`','`Tue`','`Wed`','`Thu`','`Fri`','`Sat`','`Sun`');
	$jours = array('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche');
	$date= preg_replace($days,$jours,$date);
	return $date;
}

if (isset($Submit)) {
	$OK = TRUE;

	if ($datedebut=="") {
		$errormsg1="<tr><td colspan=\"3\" align=\"center\"><span class=\"erreur\">Date de début manquante</span></td></tr>";
		$OK = FALSE;
	} else {
		$annee = substr($datedebut, 6, 2);
		$mois = substr($datedebut, 3, 2);
		$jour = substr($datedebut, 0, 2);
		if( ($mois<1) || ($mois>12) || ($jour<1) || ($jour>31) ) {
			$errormsg1 = "<tr><td colspan=\"3\" align=\"center\"><span class=\"erreur\">Date de début incorrecte</span></td></tr>";
			$OK = FALSE;
		}
	}
	
	if ($datefin=="") {
		$errormsg2="<tr><td colspan=\"3\" align=\"center\"><span class=\"erreur\">Date de fin manquante</span></td></tr>";
		$OK = FALSE;
	} else {
		$annee = substr($datedebut, 6, 2);
		$mois = substr($datedebut, 3, 2);
		$jour = substr($datedebut, 0, 2);
//		echo (gettype($annee)!="string").gettype($mois).gettype($jour);
		if( ($mois<1) || ($mois>12) || ($jour<1) || ($jour>31) ) {
			$errormsg2 = "<tr><td colspan=\"3\" align=\"center\"><span class=\"erreur\">Date de fin incorrecte</span></td></tr>";
			$OK = FALSE;
		}
	}

	$datebegin = substr($datedebut, 6, 2).substr($datedebut, 3, 2).substr($datedebut, 0, 2);
	$dateend = substr($datefin, 6, 2).substr($datefin, 3, 2).substr($datefin, 0, 2);

	if($OK) {
		$request ="SELECT * FROM horaire WHERE temps>=\"$datebegin\" AND temps<=\"$dateend\" ORDER BY trav, temps"; // AND temps ASC
		$result = mysql_db_query($dbname,$request,$connect) or die('Erreur SQL : GET_PRESTATION !<br>'.$result.'<br>'.mysql_error());
		$exportxls = $result;

		// Création d'un manuel de travail
		$workbook = new Spreadsheet_Excel_Writer();

		// Envoi des en-têtes HTTP
		$workbook->send('Prestations.xls');

		// Les données actuelles
		$format_info =& $workbook->addFormat();

		$format_titre =& $workbook->addFormat(array('Align' => 'center', 'Color' => 'white', 'Pattern' => 1, 'FgColor' => 'black'));
		$format_titre->setBold();
		$format_total =& $workbook->addFormat();
		$format_total->setBold();
		$format_we =& $workbook->addFormat(array('FgColor' => 'grey'));

		$savetrav = 0;
		$nbsheet = 0;

		while($rowxls = mysql_fetch_array($exportxls)){

			if($savetrav != $rowxls[trav]) {

				$savetrav = $rowxls[trav];
				$numligne = 0;

				$requete2 ="SELECT * FROM employe WHERE num=$rowxls[trav]";
				$resultat2 = mysql_db_query($dbname,$requete2,$connect) or die('Erreur SQL : GET_NAME_TRAV !<br>'.$resultat2.'<br>'.mysql_error());
				$ligne2 = mysql_fetch_array($resultat2);
				$nomtrav= substr($ligne2[prenom], 0, 1);
				$nomtrav.= substr($ligne2[nom], 0, 1);

				// Création d'une feuille de travail
				$worksheet =& $workbook->addWorksheet($nomtrav);

				$worksheet->setColumn(0,0,7.5);		// Date
				$worksheet->setColumn(1,1,9.5);		// N°Do
				$worksheet->setColumn(2,2,15);		// Nom Dossier
				$worksheet->setColumn(3,3,15);		// Client
				$worksheet->setColumn(4,4,104);		// Description
				$worksheet->setColumn(5,5,5.5);		// Colla
				$worksheet->setColumn(6,6,4.5);		// Aide
				$worksheet->setColumn(7,7,3.5);		// HT
				$worksheet->setColumn(8,8,3.5);		// HB
				$worksheet->setColumn(9,9,3.5);		// km
				$worksheet->setColumn(10,10,5);		// Frais

				$worksheet->write(0, 0, "Date", $format_titre);
				$worksheet->write(0, 1, "N° Dossier", $format_titre);
				$worksheet->write(0, 2, "Nom Dossier", $format_titre);
				$worksheet->write(0, 3, "Client", $format_titre);
				$worksheet->write(0, 4, "Description", $format_titre);
				$worksheet->write(0, 5, "Colla", $format_titre);
				$worksheet->write(0, 6, "Aide", $format_titre);
				$worksheet->write(0, 7, "H.T.", $format_titre);
				$worksheet->write(0, 8, "H.B.", $format_titre);
				$worksheet->write(0, 9, "Km", $format_titre);
				$worksheet->write(0, 10, "Frais", $format_titre);

				$numligne++;
			}

			$date = substr($rowxls[temps], 4, 2)."/".substr($rowxls[temps], 2, 2)."/".substr($rowxls[temps], 0, 2);
			$worksheet->write($numligne, 0, $date, $format_info);

			$requetedossier = "SELECT nomchantier, client1 FROM dossier WHERE numdossier=\"$rowxls[nodossier]\"";
			$resultatdossier = mysql_db_query($dbname,$requetedossier,$connect) or die('Erreur SQL : GET_MONTH !<br>'.$resultatdossier.'<br>'.mysql_error());
			$lignedossier = mysql_fetch_array($resultatdossier);
			$nomchantier = stripslashes($lignedossier[nomchantier]);
			$client1 = stripslashes($lignedossier[client1]);

//			$worksheet->write($numligne, 2, $nomchantier, $format_info);
//			$worksheet->write($numligne, 3, $client1, $format_info);

//			$worksheet->write($numligne, 4, $rowxls[description], $format_info);

//			$worksheet->write($numligne, 5, $nomtrav, $format_info);

			if($rowxls[aide]!="") {
				$requete2 ="SELECT * FROM employe WHERE num=$rowxls[aide]";
				$resultat2 = mysql_db_query($dbname,$requete2,$connect) or die('Erreur SQL : GET_NAME_AIDE !<br>'.$resultat2.'<br>'.mysql_error());
				$ligne2 = mysql_fetch_array($resultat2);
				$nomaide= substr($ligne2[prenom], 0, 1);
				$nomaide.= substr($ligne2[nom], 0, 1);
			}

//			$worksheet->write($numligne, 6, $nomaide, $format_info);
//			$worksheet->write($numligne, 7, $rowxls[nbht], $format_info);
//			$worksheet->write($numligne, 8, $rowxls[nbhb], $format_info);
//			$worksheet->write($numligne, 9, $rowxls[nbkm], $format_info);
//			$worksheet->write($numligne, 10, $rowxls[frais], $format_info);

			$numligne++;

//			$numligne++;
//			$worksheet->write($numligne, 1, "Total :", $format_total);
//			$worksheet->writeFormula($numligne, 7, "=SUM(G2:G$nbrow)",$format_total);
//			$worksheet->writeFormula($numligne, 8, "=SUM(H2:H$nbrow)",$format_total);
//			$worksheet->writeFormula($numligne, 9, "=SUM(I2:I$nbrow)",$format_total);
//			$worksheet->writeFormula($numligne, 10, "=SUM(J2:J$nbrow)",$format_total);

		}

	/**************************************************************** DOSSIER ****************************************************************/

		$request ="SELECT * FROM horaire WHERE temps>=\"$datebegin\" AND temps<=\"$dateend\" ORDER BY nodossier, temps"; // AND temps ASC
		$result = mysql_db_query($dbname,$request,$connect) or die('Erreur SQL : GET_PRESTATION !<br>'.$result.'<br>'.mysql_error());
		$exportxls = $result;

		// Création d'une feuille de travail
		$worksheet =& $workbook->addWorksheet('Dossiers');

		$format_client1 =& $workbook->addFormat(array('Align' => 'left', 'top' => '1', 'right' => '1', 'left' => '1'));
		$format_client2 =& $workbook->addFormat(array('Align' => 'left', 'right' => '1', 'left' => '1'));
		$format_client3 =& $workbook->addFormat(array('Align' => 'left', 'bottom' => '1', 'right' => '1', 'left' => '1'));

		$format_titre =& $workbook->addFormat(array('Align' => 'center'));
		$format_titre_description =& $workbook->addFormat(array('Align' => 'left'));
		$format_titre_description2 =& $workbook->addFormat(array('Align' => 'left'));
		$format_titre_description2->setBold();

		$format_info =& $workbook->addFormat(array('Align' => 'center', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1'));
		$format_info2 =& $workbook->addFormat(array('Color' => 'black', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1'));
		$format_doublon =& $workbook->addFormat(array('Color' => 'grey', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1'));
		$format_doublon_titre =& $workbook->addFormat(array('Align' => 'center', 'Color' => 'grey', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1'));

		$format_titre_totaux =& $workbook->addFormat(array('Align' => 'right'));
		$format_subtotal =& $workbook->addFormat(array('Align' => 'right', 'bottom' => '2'));
		$format_total =& $workbook->addFormat(array('Align' => 'right', 'bottom' => '5'));

		$worksheet->setColumn(0,0,4.33);		// Espace
		$worksheet->setColumn(1,1,9.67);		// Date
		$worksheet->setColumn(2,2,79.67);		// Description
		$worksheet->setColumn(3,3,10.33);		// Aide
		$worksheet->setColumn(4,4,7);			// Geom
		$worksheet->setColumn(5,5,7);			// HT
		$worksheet->setColumn(6,6,7.83);		// HB
		$worksheet->setColumn(7,7,5);			// km
		$worksheet->setColumn(8,8,5.83);		// Frais

		$numligne = 0;
		$nodossier = 0;
/*
		$worksheet->write($numligne, 1, "Date", $format_titre);
		$worksheet->write($numligne, 2, "Description", $format_titre_description);
		$worksheet->write($numligne, 3, "Aide", $format_titre);
		$worksheet->write($numligne, 4, "Géom", $format_titre);
		$worksheet->write($numligne, 5, "H.éq.", $format_titre);
		$worksheet->write($numligne, 6, "H.géo.", $format_titre);
		$worksheet->write($numligne, 7, "Kms", $format_titre);
		$worksheet->write($numligne, 8, "Frais", $format_titre);
*/
		while($rowxls = mysql_fetch_array($exportxls)){

			if($nodossier != $rowxls[nodossier]) {
				$nodossier = $rowxls[nodossier];
				$numligne+=4;

				$requetedossier = "SELECT nomchantier, client1 FROM dossier WHERE numdossier=\"$rowxls[nodossier]\"";
				$resultatdossier = mysql_db_query($dbname,$requetedossier,$connect) or die('Erreur SQL : GET_MONTH !<br>'.$resultatdossier.'<br>'.mysql_error());
				$lignedossier = mysql_fetch_array($resultatdossier);
				$nomchantier = stripslashes($lignedossier[nomchantier]);
				$client1 = stripslashes($lignedossier[client1]);

				$ecrire = "Dossier n° ".$rowxls[nodossier]." : ".$nomchantier." pour ".$client1;
				$worksheet->write($numligne, 2, $ecrire, $format_titre_description2);

				$numligne++;
				$numligne++;

				$worksheet->write($numligne, 1, "Date", $format_titre);
				$worksheet->write($numligne, 2, "Description", $format_titre_description);
				$worksheet->write($numligne, 3, "Aide", $format_titre);
				$worksheet->write($numligne, 4, "Géom", $format_titre);
				$worksheet->write($numligne, 5, "H.éq.", $format_titre);
				$worksheet->write($numligne, 6, "H.géo.", $format_titre);
				$worksheet->write($numligne, 7, "Kms", $format_titre);
				$worksheet->write($numligne, 8, "Frais", $format_titre);

				$numligne++;
			}

			$date = substr($rowxls[temps], 4, 2)."/".substr($rowxls[temps], 2, 2)."/".substr($rowxls[temps], 0, 2);
			$worksheet->write($numligne, 1, $date, $format_info);

			$worksheet->write($numligne, 2, $rowxls[description], $format_info2);

			if($rowxls[aide]!="") {
				$requete2 ="SELECT * FROM employe WHERE num=$rowxls[aide]";
				$resultat2 = mysql_db_query($dbname,$requete2,$connect) or die('Erreur SQL : GET_NAME_AIDE !<br>'.$resultat2.'<br>'.mysql_error());
				$ligne2 = mysql_fetch_array($resultat2);
				$nomaide= substr($ligne2[prenom], 0, 1);
				$nomaide.= substr($ligne2[nom], 0, 1);
			}
			$worksheet->write($numligne, 3, $nomaide, $format_info);

			$requete2 ="SELECT * FROM employe WHERE num=$rowxls[trav]";
			$resultat2 = mysql_db_query($dbname,$requete2,$connect) or die('Erreur SQL : GET_NAME_TRAV !<br>'.$resultat2.'<br>'.mysql_error());
			$ligne2 = mysql_fetch_array($resultat2);
			$nomtrav= substr($ligne2[prenom], 0, 1);
			$nomtrav.= substr($ligne2[nom], 0, 1);
			$worksheet->write($numligne, 4, $nomtrav, $format_info);

			$worksheet->write($numligne, 5, $rowxls[nbht], $format_info);
			$worksheet->write($numligne, 6, $rowxls[nbhb], $format_info);
			$worksheet->write($numligne, 7, $rowxls[nbkm], $format_info);
			$worksheet->write($numligne, 8, $rowxls[frais], $format_info);

			$numligne++;

		}
		$workbook->close();
	}
}

/*************************************************************** CREATION DU TABLEAU DIRECTEUR ******************************************************************************/
$position = array();
$position[0] = "Exportation des Prestations";

?>
<HTML>

<HEAD>
<TITLE>.: Exportation des Prestations :.</TITLE>
<LINK href="css.css" type="text/css" rel="stylesheet">

</HEAD>

<BODY>
<div align="center">

<TABLE cellSpacing="0" cellPadding="0" width="750" border="0" height="214" background="images\khb.jpg">
<TBODY>
  <?	require_once("header_a.php");		?>
  <TR>
    <TD vAlign="top" height="15"></TD>
  </TR>
  <TR>
    <TD vAlign="top" width="750">
      <div align="center">
		<br>
		<FORM method="post" action="" enctype="multipart/form-data">
      	<table border="0" width="650" id="table1" cellspacing="0" cellpadding="0">
			<tr>
				<td width="50%" align="right" height="21">
					<span class="infofixe">Du :&nbsp;&nbsp;</span>
				</td>
				<td width="50%" align="left" height="21">
					<input type="text" name="datedebut" value="" size="8"><span class="infofixe2">&nbsp;&nbsp;(JJ/MM/AA)</span>
				</td>
			</tr>
			<?
				echo $errormsg1;
			?>
			<tr>
				<td width="50%" align="right" height="21">
					<span class="infofixe">Au :&nbsp;&nbsp;</span>
				</td>
				<td width="50%" align="left" height="21">
					<input type="text" name="datefin" value="" size="8"><span class="infofixe2">&nbsp;&nbsp;(JJ/MM/AA)</span>
				</td>
			</tr>
			<?
				echo $errormsg2;
			?>
			<tr>
				<td colspan="2" align="center" height="21">&nbsp;</td>
			</tr>
		</table>
		<br>
		<input type="submit" value="Exporter" name="Submit">
		</FORM>
		</div>
	</TD>
   </TR>
   <TR>
   	<td>
	     <? require_once("footer.php"); ?>
     </td>
   </TR>
  </TBODY>
</table>
</BODY>
</HTML>