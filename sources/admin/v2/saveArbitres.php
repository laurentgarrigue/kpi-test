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
	$id = $myBdd->RealEscapeString(trim($_POST['id']));
	$value = explode('|',$_POST['value']);
	$value[0] = $myBdd->RealEscapeString(trim($value[0]));
	if(isset($value[1])) {
        $value[1] = (int)$myBdd->RealEscapeString(trim($value[1]));
    } else {
        $value[1] = 0;
    }
    
/*	// SECURITY HOLE ***************************************************************
	$a_json_invalid = array(array("id" => "#", "value" => $term, "label" => "Only letters and digits are permitted..."));
	$json_invalid = json_encode($a_json_invalid);
	// allow space, any unicode letter and digit, underscore and dash
	if(preg_match("/[^\040\pL\pN_-]/u", $value[0])) {
	  print $json_invalid;
	  exit;
	}
	// *****************************************************************************
*/
	// Contrôle autorisation journée
	$sql  = "SELECT Id_journee, Validation "
            . "FROM gickp_Matchs "
            . "WHERE Id = ".$idMatch;
	$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Select<br />".$sql);
	$row = mysql_fetch_array($result);
	if (!utyIsAutorisationJournee($row['Id_journee']))
		die ("Vous n'avez pas l'autorisation de modifier les matchs de cette journée !");
	if ($row['Validation']=='O')
		die ("Ce match est verrouillé !");
	
	$sql  = "UPDATE gickp_Matchs "
            . "SET ".$id." = '".$value[0]."' ";
	if($id == 'Arbitre_principal')
		$sql .= ", Matric_arbitre_principal = ".$value[1]." ";
	if($id == 'Arbitre_secondaire')
		$sql .= ", Matric_arbitre_secondaire = ".$value[1]." ";
	$sql .= "WHERE Id = ".$idMatch;
	$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur UPDATE<br />".$sql);
	if($value[0] != '') {
        echo $value[0];
    } else {
        echo $value[1];
    }
//echo '<br>'.$sql.'<br>'.$value[1];