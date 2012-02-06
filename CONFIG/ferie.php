<?php

function isHoliday($timestamp)
{
	// dates fériées fixes
	if($jour == 1 && $mois == 1)	return 1;		// 1er janvier
	if($jour == 1 && $mois == 5)	return 1;		// 1er mai
	if($jour == 8 && $mois == 5)	return 1;		// 8 mai
	if($jour == 21 && $mois == 7)	return 1;		// 14 juillet
	if($jour == 15 && $mois == 8)	return 1;		// 15 aout
	if($jour == 1 && $mois == 11)	return 1;		// 1 novembre
	if($jour == 11 && $mois == 11)	return 1;		// 11 novembre
	if($jour == 25 && $mois == 12)	return 1;		// 25 décembre

	$jour = date("d", $timestamp);
	$mois = date("m", $timestamp);
	$annee = date("Y", $timestamp);
	
	// fetes religieuses mobiles
	$pak = easter_date($annee);
	$jp = date("d", $pak);
	$mp = date("m", $pak);
	if($jp == $jour && $mp == $mois)	return 1;	// Pâques
	
	$lpk = mktime(date("H", $pak), date("i", $pak), date("s", $pak), date("m", $pak), date("d", $pak) +1, date("Y", $pak) );
	$jp = date("d", $lpk);
	$mp = date("m", $lpk);
	if($jp == $jour && $mp == $mois)	return 1;	// Lundi de Pâques
	
	$asc = mktime(date("H", $pak), date("i", $pak), date("s", $pak), date("m", $pak), date("d", $pak) + 39, date("Y", $pak) );
	$jp = date("d", $asc);
	$mp = date("m", $asc);
	if($jp == $jour && $mp == $mois)	return 1;	//ascension
	
	$pe = mktime(date("H", $pak), date("i", $pak), date("s", $pak), date("m", $pak), date("d", $pak) + 49, date("Y", $pak) );
	$jp = date("d", $pe);
	$mp = date("m", $pe);
	if($jp == $jour && $mp == $mois)	return 1;	// Pentecôte
	
	$lp = mktime(date("H", $asc), date("i", $pak), date("s", $pak), date("m", $pak), date("d", $pak) + 50, date("Y", $pak) );
	$jp = date("d", $lp);
	$mp = date("m", $lp);
	if($jp == $jour && $mp == $mois)	return 1;	// lundi Pentecôte

	return 0;
}

?>