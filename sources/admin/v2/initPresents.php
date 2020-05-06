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
$idMatch = (int) utyGetPost('idMatch');
$codeEquipe = trim( utyGetPost('codeEquipe'));
$idEquipe = (int) utyGetPost('idEquipe');
// Contrôle autorisation journée
$myBdd->AutorisationMatch($idMatch);

$myBdd->InitTitulaireEquipe($codeEquipe, $idMatch, $idEquipe, true);

echo 'OK'; 

