<?php
//include_once('../commun/MyPage.php');
include_once('../commun/MyBdd.php');
include_once('../commun/MyTools.php');
	
session_start();

$myBdd = new MyBdd();

// Chargement
$q = $myBdd->RealEscapeString(trim(utyGetGet('q')));
$q = preg_replace('`^[0]*`','',$q);
$resultGlobal = '';

	// Referentiel Journees
	$sql  = "SELECT * "
			. "FROM gickp_Ref_Journee "
			. "WHERE UPPER(nom) LIKE UPPER('%".$q."%') "
			. "ORDER BY nom ";	 
	$result = $myBdd->Query($sql);
	while ($row = $myBdd->FetchAssoc($result))
	{
		//$libelle = __encode($row['Libelle']);
		$resultGlobal .= $row['nom']."|".$row['nom']."\n";
	}

echo $resultGlobal;
