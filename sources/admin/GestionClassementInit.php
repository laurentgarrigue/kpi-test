<?php

include_once('../commun/MyPage.php');
include_once('../commun/MyBdd.php');
include_once('../commun/MyTools.php');

// Gestion des Classements Initiaux

class GestionClassementInit extends MyPageSecure	 
{	
	function Load()
	{
		$codeCompet = utyGetSession('codeCompet');
		$this->m_tpl->assign('codeCompet', $codeCompet);
		
		$_SESSION['updatecell_tableName'] = 'gickp_Competitions_Equipes_Init';
		$_SESSION['updatecell_where'] = 'Where Id = ';
		$_SESSION['updatecell_document'] = 'formClassementInit';
		
		$myBdd = new MyBdd();
		
		$sql  = "Select Id From gickp_Competitions_Equipes Where Code_compet = '$codeCompet' And Code_saison = '";
		$sql .= utyGetSaison();
		$sql .= "'";
		
		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load");
		$num_results = mysql_num_rows($result);
		
		for ($i=0;$i<$num_results;$i++)
		{
			$row = mysql_fetch_array($result);	  
			
			$sql  = "Select Id From gickp_Competitions_Equipes_Init Where Id = ";
			$sql .= $row['Id'];
			
			$result2 = mysql_query($sql, $myBdd->m_link) or die ("Erreur Select");
			if (mysql_num_rows($result2) == 0)
			{
					// Insertion
					$sql = "Insert Into gickp_Competitions_Equipes_Init (Id,Clt,Pts,J,G,N,P,F,Plus,Moins,Diff) Values (";
					$sql .= $row['Id'];
					$sql .= ",0,0,0,0,0,0,0,0,0,0)";
					mysql_query($sql, $myBdd->m_link) or die ("Erreur Insert"); 
			}
	  }
	  
	 	// Chargement des Equipes avec leurs valeurs initiales ...
		$sql  = "SELECT a.Id, a.Libelle, a.Code_club, b.Clt, b.Pts, b.J, b.G, b.N, b.P, b.F, b.Plus, b.Moins, b.Diff ";
		$sql .= "FROM gickp_Competitions_Equipes a, gickp_Competitions_Equipes_Init b ";
		$sql .= "WHERE a.Id = b.Id AND a.Code_compet = '$codeCompet' AND a.Code_saison = '";
		$sql .= utyGetSaison();
		$sql .= "' ";
		$sql .= "ORDER BY b.Clt, b.Pts, b.Diff DESC ";
	
		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 1");
		$num_results = mysql_num_rows($result);
		
		// Chargement des Equipes ...
		$arrayEquipe = array();
		
		for ($i=0;$i<$num_results;$i++)
		{
			$row = mysql_fetch_array($result);	  
						
			array_push($arrayEquipe, array( 'Id' => $row['Id'], 'Libelle' => $row['Libelle'], 'Code_club' => $row['Code_club'],
															        'Clt' => $row['Clt'], 'Pts' => $row['Pts'], 
															        'J' => $row['J'], 'G' => $row['G'], 'N' => $row['N'], 
															        'P' => $row['P'], 'F' => $row['F'], 'Plus' => $row['Plus'], 
															        'Moins' => $row['Moins'], 'Diff' => $row['Diff'] ));
		}	
		$this->m_tpl->assign('arrayEquipe', $arrayEquipe);
	}
		
	function GestionClassementInit()
	{			
		MyPageSecure::MyPageSecure(10);
		
		$this->SetTemplate("Initialisation des Classements", "Classements", false);
		$this->Load();
		$this->DisplayTemplate('GestionClassementInit');
	}
}		  	

$page = new GestionClassementInit();

?>