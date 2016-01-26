<?php

include_once('../commun/MyPage.php');
include_once('../commun/MyBdd.php');
include_once('../commun/MyTools.php');

// Deconnexion

class UnLogin extends MyPage 
{	
	function UnLogin()
	{
		session_start();
			
		if (isset($_SESSION['User']))
		{
			unset ($_SESSION['User']);
  		$_SESSION = array();
		}

		header("Location: http://".$_SERVER['HTTP_HOST'].MAIN_DIRECTORY."/index.php");	
	}		
}		  	

$page = new UnLogin();

?>
