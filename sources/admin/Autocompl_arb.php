<?php
include_once('../commun/MyBdd.php');
include_once('../commun/MyTools.php');
	
session_start();

$myBdd = new MyBdd();

// Langue
$langue = parse_ini_file("../commun/MyLang.ini", true);
if (utyGetSession('lang') == 'en') {
	$lang = $langue['en'];
} else {
	$lang = $langue['fr'];
}

// Chargement
$j = utyGetSession('sessionJournee','');
$j = (int) utyGetGet('journee', $j);
$m = utyGetSession('sessionMatch','');
$m = (int) utyGetGet('sessionMatch',$m);
$q = trim(utyGetGet('q'));
$q = preg_replace('`^[0]*`','',$q);
$resultGlobal = '';

if ($j == '' && $m == '') {
	$resultGlobal = "Selectionnez une journee / une phase !|XXX||||\n";
} elseif(strlen($q) < 2) {
	$resultGlobal = "2 caractères minimum !|XXX||||\n";
} else {
	// Equipes
	$resultGlobal .= "---------- " . $lang['Equipes'] . " ----------|XXX\n";
	$arrayQuery = [];
	$sql = "SELECT a.Id, a.Libelle, a.Poule, a.Tirage, a.Code_compet 
		FROM gickp_Competitions_Equipes a, gickp_Journees b 
		WHERE a.Code_compet = b.Code_competition 
		AND a.Code_saison = b.Code_saison ";
	if ($j != '') {
		$sql .= "AND b.Id = ? ";
		$arrayQuery = array($j);
	}
	$sql .= "AND UPPER(a.Libelle) LIKE UPPER(?) 
		GROUP BY a.Libelle 
		ORDER BY a.Poule, a.Tirage, a.Libelle ";	 
	$result = $myBdd->pdo->prepare($sql);
	$result->execute(array_merge($arrayQuery, ['%'.$q.'%']));
	while ($row = $result->fetch()) {
		$libelle = $row['Libelle'];
		$matric = '';
		$resultGlobal .= "$libelle|$matric|$libelle|||\n";
	}
	// Joueurs
	$resultGlobal .= "|XXX\n";
	$resultGlobal .= "|XXX\n";
	$resultGlobal .= "|XXX\n";
	$resultGlobal .= "---------- " . $lang['Joueurs'] . " ----------|XXX\n";
	$arrayQuery = [];
	$sql = "SELECT DISTINCT a.Matric, a.Nom, a.Prenom, b.Libelle, c.Arb, c.niveau, 
		(c.Arb IS NULL) AS sortCol 
		FROM gickp_Competitions_Equipes b, gickp_Journees d, gickp_Matchs e, 
		gickp_Competitions_Equipes_Joueurs a 
		LEFT OUTER JOIN gickp_Arbitre c ON a.Matric = c.Matric 
		WHERE a.Id_equipe = b.Id 
		AND b.Code_compet = d.Code_competition 
		AND b.Code_saison = d.Code_saison 
		AND d.Id = e.Id_journee 
		AND a.Capitaine <> 'X' ";
	if ($j != '') {
		$sql .= "AND d.Id = ? ";
		$arrayQuery = array($j);
	} elseif ($m != '') {
		$sql .= "AND e.Id = ? ";
		$arrayQuery = array($m);
	}
	$sql .= "AND (a.Matric LIKE ? 
			OR UPPER(CONCAT_WS(' ', a.Nom, a.Prenom)) LIKE UPPER(?) 
			OR UPPER(CONCAT_WS(' ', a.Prenom, a.Nom)) LIKE UPPER(?) 
			OR UPPER(b.Libelle) LIKE UPPER(?) ) 
		ORDER BY b.Libelle, sortCol, c.Arb, a.Nom, a.Prenom ";
	$result = $myBdd->pdo->prepare($sql);
	$result->execute(array_merge($arrayQuery, ['%'.$q.'%'], ['%'.$q.'%'], ['%'.$q.'%'], ['%'.$q.'%']));
	while ($row = $result->fetch()) {
		$libelle = $row['Libelle'];
		$arb = strtoupper($row['Arb']);
		if ($row['niveau'] != '') {
			$arb .= '-' . $row['niveau'];
		}
		$matric = $row['Matric'];
		$nom = mb_convert_case(strtolower($row['Nom']), MB_CASE_TITLE, "UTF-8");
		$prenom = mb_convert_case(strtolower($row['Prenom']), MB_CASE_TITLE, "UTF-8");
		$resultGlobal .= "($libelle) $nom $prenom $arb|$matric|$nom|$prenom|$libelle|$arb\n";
	}
//            die($resultGlobal);
	// Pool
	$resultGlobal .= "|XXX\n";
	$resultGlobal .= "|XXX\n";
	$resultGlobal .= "|XXX\n";
	$resultGlobal .= "---------- " . $lang['Pool_Arbitres'] . " ----------|XXX\n";
	$sql = "SELECT a.Matric, a.Nom, a.Prenom, b.Libelle, c.Arb, c.niveau 
		FROM gickp_Competitions_Equipes b, gickp_Competitions_Equipes_Joueurs a 
		LEFT OUTER JOIN gickp_Arbitre c ON a.Matric = c.Matric  
		WHERE a.Id_equipe = b.Id 
		AND b.Code_compet = 'POOL' 
		AND (a.Matric LIKE :query 
			OR UPPER(CONCAT_WS(' ', a.Nom, a.Prenom)) LIKE UPPER(:query) 
			OR UPPER(CONCAT_WS(' ', a.Prenom, a.Nom)) LIKE UPPER(:query) 
			OR UPPER(b.Libelle) LIKE UPPER(:query) ) 
		ORDER BY a.Nom, a.Prenom ";
	$result = $myBdd->pdo->prepare($sql);
	$result->execute(array(':query' => '%'.$q.'%'));
	while ($row = $result->fetch()) {
		//$libelle = 'Pool Arbitres 1';
		$libelle = substr($row['Libelle'],0,3);
		$arb = strtoupper($row['Arb']);
		if($row['niveau'] != '')
			$arb .= '-'.$row['niveau'];
		$matric = $row['Matric'];
		$nom = mb_convert_case(strtolower($row['Nom']), MB_CASE_TITLE, "UTF-8");
		$prenom = mb_convert_case(strtolower($row['Prenom']), MB_CASE_TITLE, "UTF-8");
		if(isset($row['Naissance'])) {
			$naissance = $row['Naissance'];
		}
		if(isset($row['Sexe'])) {
			$sexe = $row['Sexe'];
		}
		$resultGlobal .= "$nom $prenom ($libelle) $arb|$matric|$nom|$prenom|$libelle|$arb\n";
	}
	// Autres arbitres
	$resultGlobal .= "|XXX\n";
	$resultGlobal .= "|XXX\n";
	$resultGlobal .= "|XXX\n";
	$resultGlobal .= "---------- " . $lang['Autres_Arbitres'] . " ----------|XXX\n";
	$sql = "SELECT lc.*, c.Libelle, b.Arb, b.niveau 
		FROM gickp_Liste_Coureur lc, gickp_Arbitre b, gickp_Club c 
		WHERE lc.Matric = b.Matric 
		AND (lc.Matric LIKE :query 
			OR UPPER(CONCAT_WS(' ', lc.Nom, lc.Prenom)) LIKE UPPER(:query) 
			OR UPPER(CONCAT_WS(' ', lc.Prenom, lc.Nom)) LIKE UPPER(:query) ) 
		AND lc.Numero_club = c.Code 
		ORDER BY lc.Nom, lc.Prenom ";
	$result = $myBdd->pdo->prepare($sql);
	$result->execute(array(':query' => '%'.$q.'%'));
	while ($row = $result->fetch()) {
		$club = $row['Numero_club'];
		$libelle = mb_convert_case(strtolower($row['Libelle']), MB_CASE_TITLE, "UTF-8");
		$arb = strtoupper($row['Arb']);
		if ($row['niveau'] != '') {
			$arb .= '-' . $row['niveau'];
		}
		$matric = $row['Matric'];
		$nom = mb_convert_case(strtolower($row['Nom']), MB_CASE_TITLE, "UTF-8");
		$prenom = mb_convert_case(strtolower($row['Prenom']), MB_CASE_TITLE, "UTF-8");
		$naissance = $row['Naissance'];
		$sexe = $row['Sexe'];
		$resultGlobal .= "$nom $prenom ($libelle) $arb|$matric|$nom|$prenom|$libelle|$arb\n";
	}
	//Résultat
}

echo $resultGlobal;
