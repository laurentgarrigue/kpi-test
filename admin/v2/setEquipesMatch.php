<?php 
// prevent direct access *****************************************************
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
  $user_error = 'Access denied - not an AJAX request...';
  trigger_error($user_error, E_USER_ERROR);
}
// ***************************************************************************
	
include_once('../../commun/MyBdd.php');
include_once('../../commun/MyTools.php');

	session_start();

	$myBdd = new MyBdd();
	$idMatch = (int)$_POST['idMatch'];
	$idEquipe = (int)$_POST['idEquipe'];
	$Equipe = $myBdd->RealEscapeString(trim($_POST['equipe'])); // A / B
/*	// SECURITY HOLE ***************************************************************
	$a_json_invalid = array(array("id" => "#", "value" => $term, "label" => "Only letters and digits are permitted..."));
	$json_invalid = json_encode($a_json_invalid);
	// allow space, any unicode letter and digit, underscore and dash
	if(preg_match("/[^\040\pL\pN_-]/u", $value)) {
	  print $json_invalid;
	  exit;
	}
	// *****************************************************************************
*/
	$sql  = "UPDATE gickp_Matchs "
            . "SET Id_equipe".$Equipe." = ".$idEquipe." "
            . "WHERE Id = ".$idMatch;
	$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Update<br />".$sql);
	// Vidage compo
	$sql  = "DELETE FROM gickp_Matchs_Joueurs "
            . "WHERE Id_match = $idMatch "
            . "AND Equipe = '".$Equipe."' ";
	mysql_query($sql, $myBdd->m_link) or die ("Erreur Delete => ".$sql);
	
	echo "OK"; 


