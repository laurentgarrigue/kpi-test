<?php
//include_once('../commun/MyPage.php');
include_once('../commun/MyBdd.php');
include_once('../commun/MyTools.php');

$myBdd = new MyBdd();	

$codeSaison = $myBdd->GetActiveSaison();
// Chargement
$q = trim(utyGetGet('q'));
$q = preg_replace('`^[0]*`','',$q);

if (strlen($q) < 2) {
	echo 'Trop court...';
	return;
}

$matric = (int) $q;
if ($matric > 0) {
	$sql = "SELECT lc.*, c.Libelle, s.Date date_surclassement 
	FROM gickp_Club c, gickp_Liste_Coureur lc 
	LEFT OUTER JOIN gickp_Surclassements s 
		ON (lc.Matric = s.Matric AND s.Saison = ?) 
	WHERE (lc.Matric = ? 
		OR lc.Reserve = ? ) 
		AND lc.Numero_club = c.Code 
		ORDER BY lc.Nom, lc.Prenom ";
	$arrayQuery = array($codeSaison, $matric, $matric);
} else {
	$sql = "SELECT lc.*, c.Libelle, s.Date date_surclassement 
		FROM gickp_Club c, gickp_Liste_Coureur lc 
		LEFT OUTER JOIN gickp_Surclassements s 
			ON (lc.Matric = s.Matric AND s.Saison = ?) 
		WHERE (UPPER(CONCAT_WS(' ', lc.Nom, lc.Prenom)) LIKE UPPER(?) 
			OR UPPER(CONCAT_WS(' ', lc.Prenom, lc.Nom)) LIKE UPPER(?) ) 
		AND lc.Numero_club = c.Code 
		ORDER BY lc.Nom, lc.Prenom ";
	$arrayQuery = array($codeSaison, $q.'%', $q.'%');
}
$result = $myBdd->pdo->prepare($sql);
$result->execute($arrayQuery);
while($row = $result->fetch()) {
	$club = $row['Numero_club'];
	$libelle = $row['Libelle'];
	$matric = $row['Matric'];
	$nom = mb_strtoupper($row['Nom']);
	$prenom = mb_convert_case($row['Prenom'], MB_CASE_TITLE, "UTF-8");
	$nom2 = mb_strtoupper($row['Nom']);
	$prenom2 = mb_convert_case($row['Prenom'], MB_CASE_TITLE, "UTF-8");
	$naissance = $row['Naissance'];
	$sexe = $row['Sexe'];
	$origine = $row['Origine'];
	$pagaie_ECA = $row['Pagaie_ECA'];
	$pagaie_EVI = $row['Pagaie_EVI'];
	$pagaie_MER = $row['Pagaie_MER'];
	$pagaies = array('', 'PAGB', 'PAGJ');
	if (in_array($pagaie_ECA, $pagaies)) { //si pas de pagaie verte ECA (ou plus)
		if (in_array($pagaie_EVI, $pagaies)) { // si pas de pagaie verte EVI (ou plus)
			if (!in_array($pagaie_MER, $pagaies)) { // si une pagaie verte MER (ou plus)
				$pagaie_ECA = 'PAGV'; // sinon ECA est au moins verte
			}
		} else {
			$pagaie_ECA = 'PAGV'; // sinon ECA est au moins verte
		}
	}

	$certificat_CK = $row['Etat_certificat_CK'];
	$certificat_APS = $row['Etat_certificat_APS'];
	$date_surclassement = utyDateUsToFr($row['date_surclassement']);
	$return = "$matric - $nom $prenom ($club - $libelle)|$matric|$nom|$prenom|$naissance|$sexe|$nom2|$prenom2|$origine|$pagaie_ECA|$certificat_CK|$certificat_APS|$libelle|$date_surclassement|$club\n";
	echo $return;
}

