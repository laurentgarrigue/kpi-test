<?php
// prevent direct access *****************************************************
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
    $user_error = 'Access denied !';
    die($user_error);
    //trigger_error($user_error, E_USER_ERROR);
}
// ***************************************************************************

include_once('commun/MyBdd.php');
include_once('commun/MyTools.php');

$myBdd = new MyBdd();

// Chargement des Clubs ayant une équipe inscrite dans une compétition de polo ...
$a_json = array();
$term = trim(utyGetGet('term'));
// replace multiple spaces with one
$term = preg_replace('/\s+/', ' ', $term);
$term2 = preg_replace('/\s/', '-', $term);
$sql  = "SELECT DISTINCT c.Code, c.Libelle, c.Coord, c.Postal, c.Coord2, c.www, c.email "
        ."FROM gickp_Club c, gickp_Equipe e "
        ."WHERE c.Code = e.Code_club "
        ."AND (c.Code LIKE '".$term."%' "
        ."OR c.Libelle LIKE '%".$term."%' "
        ."OR e.Libelle LIKE '%".$term."%' "
        ."OR c.Libelle LIKE '%".$term2."%' "
        ."OR e.Libelle LIKE '%".$term2."%') "
        ."ORDER BY c.Officiel DESC, c.Code, c.Libelle ";
$result = $myBdd->Query($sql);
while($row = $myBdd->FetchAssoc($result)) {
    $jRow["value"] = $row['Libelle'];
    $jRow["idClub"] = $row['Code'];
    $jRow["label"] = $row['Code'].' - '.$row['Libelle'];
    $jRow["www"] = $row['www'];
    $jRow["email"] = $row['email'];
    $jRow["postal"] = $row['Postal'];
    $jRow["coord"] = $row['Coord'];
    array_push($a_json, $jRow);
}
$json = json_encode($a_json);
print $json;