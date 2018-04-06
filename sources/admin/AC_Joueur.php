<?php
// prevent direct access *****************************************************
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
  $user_error = 'Access denied - not an AJAX request...';
  trigger_error($user_error, E_USER_ERROR);
}
// ***************************************************************************
	
//include_once('../commun/MyPage.php');
include_once('../commun/MyBdd.php');
include_once('../commun/MyTools.php');
	
	$myBdd = new MyBdd();
	// Chargement
	$term = $myBdd->RealEscapeString(trim(utyGetGet('term')));
	// replace multiple spaces with one
	$term = preg_replace('/\s+/', ' ', $term);
	// supprime les 0 devant les numéros de licence
	$term = preg_replace('`^[0]*`','',$term);
 
	$a_json = array();
	$jRow = array();
/*	// SECURITY HOLE ***************************************************************
	$a_json_invalid = array(array("id" => "#", "value" => $term, "label" => "Only letters and digits are permitted..."));
	$json_invalid = json_encode($a_json_invalid);
	// allow space, any unicode letter and digit, underscore and dash
	if(preg_match("/[^\040\pL\pN_-]/u", $term)) {
	  print $json_invalid;
	  exit;
	}
	// *****************************************************************************
 */
	$sql  = "SELECT lc.*, c.Libelle "
            . "FROM gickp_Liste_Coureur lc, gickp_Club c "
            . "WHERE (lc.Matric Like '%".ltrim($term, '0')."%' "
            . "OR UPPER(CONCAT_WS(' ', lc.Nom, lc.Prenom)) LIKE UPPER('%".$term."%') "
            . "OR UPPER(CONCAT_WS(' ', lc.Prenom, lc.Nom)) LIKE UPPER('%".$term."%') "
            . ") AND lc.Numero_club = c.Code "
            . "ORDER BY lc.Nom, lc.Prenom "
            . "LIMIT 0, 40 ";
	$result = $myBdd->Query($sql);
	while($row = $myBdd->FetchAssoc($result)) {
		$jRow["club"] = $row['Numero_club'];
		$jRow["libelle"] = $row['Libelle'];
		$jRow["matric"] = $row['Matric'];
		$jRow["nom"] = $row['Nom'];
		$jRow["prenom"] = $row['Prenom'];
		$jRow["nom2"] = mb_convert_case(strtolower($row['Nom']), MB_CASE_TITLE, "UTF-8");
		$jRow["prenom2"] = mb_convert_case(strtolower($row['Prenom']), MB_CASE_TITLE, "UTF-8");
		$jRow["naissance"] = $row['Naissance'];
		$jRow["sexe"] = $row['Sexe'];
		$jRow["label"] = $jRow["matric"].' - '.$jRow["nom2"].' '.$jRow["prenom2"].' ('.$jRow["club"].'-'.$jRow["libelle"].')';
		$jRow["value"] = $jRow["nom2"].' '.$jRow["prenom2"].' ('.$jRow["matric"].')';
		$jRow["category"] = $row['Libelle'];
		array_push($a_json, $jRow);
	}
	$json = json_encode($a_json);
	print $json;