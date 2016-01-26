<?php

include_once('commun/MyPage.php');
include_once('commun/MyBdd.php');
include_once('commun/MyTools.php');

// Gestion des Classements
	
class Classements extends MyPage	 
{	
	function Load()
	{
		$myBdd = new MyBdd();
		
		$codeCompetGroup = utyGetSession('codeCompetGroup', 'N1H');
		$codeCompetGroup = utyGetPost('codeCompetGroup', $codeCompetGroup);
		$codeCompetGroup = utyGetGet('Group', $codeCompetGroup);
		$this->m_tpl->assign('codeCompetGroup', $codeCompetGroup);
		$_SESSION['codeCompetGroup'] = $codeCompetGroup;

		$codeCompet = utyGetSession('codeCompet', 'N1H');
		$codeCompet = utyGetPost('comboCompet', $codeCompet);
		$codeCompet = utyGetGet('Compet', $codeCompet);
		$_SESSION['codeCompet'] = $codeCompet;

		$activeSaison = $myBdd->GetActiveSaison();
		$codeSaison = utyGetSaison();
		$codeSaison = utyGetPost('saisonTravail', $codeSaison);
		$codeSaison = utyGetGet('Saison', $codeSaison);
		$_SESSION['Saison'] = $codeSaison;
		$this->m_tpl->assign('Saison', $codeSaison);

		// Chargement des Saisons ...
		$sql  = "Select Code, Etat, Nat_debut, Nat_fin, Inter_debut, Inter_fin ";
		$sql .= "From gickp_Saison ";
		$sql .= "Order By Code DESC ";	 
		
		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load");
		$num_results = mysql_num_rows($result);
	
		$arraySaison = array();
		for ($i=0;$i<$num_results;$i++)
		{
			$row = mysql_fetch_array($result);	
			array_push($arraySaison, array('Code' => $row['Code'], 'Etat' => $row['Etat'], 
											'Nat_debut' => utyDateUsToFr($row['Nat_debut']), 'Nat_fin' => utyDateUsToFr($row['Nat_fin']), 
											'Inter_debut' => utyDateUsToFr($row['Inter_debut']), 'Inter_fin' => utyDateUsToFr($row['Inter_fin']) ));
		}
		
		$this->m_tpl->assign('arraySaison', $arraySaison);
		$this->m_tpl->assign('sessionSaison', $codeSaison);
		
		// Chargement des Groupes
			//Compétitions internationales
		$arrayCompetitionGroupe = array();
		$sql  = "Select * From gickp_Competitions_Groupes Where id < 7 And id > 0 Order by id";
		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 6a");
		$num_results = mysql_num_rows($result);
		array_push($arrayCompetitionGroupe, array('', 'CI', '=== COMPETITIONS INTERNATIONALES ===', '' ) );
		for ($i=0;$i<$num_results;$i++)
		{
			$row = mysql_fetch_array($result);	
			if ($row["Groupe"] == $codeCompetGroup)
				array_push($arrayCompetitionGroupe, array($row["id"], $row["Groupe"], $row["Libelle"], "SELECTED" ) );
			else
				array_push($arrayCompetitionGroupe, array($row["id"], $row["Groupe"], $row["Libelle"], "" ) );
		}
			//Compétitions nationales
		$sql  = "Select * From gickp_Competitions_Groupes Where id >= 7 And id < 25 Order by id";
		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 6a");
		$num_results = mysql_num_rows($result);
		array_push($arrayCompetitionGroupe, array('', 'CN', '=== COMPETITIONS NATIONALES ===', '' ) );
		for ($i=0;$i<$num_results;$i++)
		{
			$row = mysql_fetch_array($result);	
			if ($row["Groupe"] == $codeCompetGroup)
				array_push($arrayCompetitionGroupe, array($row["id"], $row["Groupe"], $row["Libelle"], "SELECTED" ) );
			else
				array_push($arrayCompetitionGroupe, array($row["id"], $row["Groupe"], $row["Libelle"], "" ) );
		}
			//Compétitions régionales
		$sql  = "Select * From gickp_Competitions_Groupes Where id >= 25 And id < 40 Order by id";
		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 6a");
		$num_results = mysql_num_rows($result);
		array_push($arrayCompetitionGroupe, array('', 'CR', '=== COMPETITIONS REGIONALES ===', '' ) );
		for ($i=0;$i<$num_results;$i++)
		{
			$row = mysql_fetch_array($result);	
			if ($row["Groupe"] == $codeCompetGroup)
				array_push($arrayCompetitionGroupe, array($row["id"], $row["Groupe"], $row["Libelle"], "SELECTED" ) );
			else
				array_push($arrayCompetitionGroupe, array($row["id"], $row["Groupe"], $row["Libelle"], "" ) );
		}
			//Tournois
		$sql  = "Select * From gickp_Competitions_Groupes Where id > 40 And id <> 60 Order by id";
		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 6a");
		$num_results = mysql_num_rows($result);
		array_push($arrayCompetitionGroupe, array('', 'T', '=== TOURNOIS ===', '' ) );
		for ($i=0;$i<$num_results;$i++)
		{
			$row = mysql_fetch_array($result);	
			if ($row["Groupe"] == $codeCompetGroup)
				array_push($arrayCompetitionGroupe, array($row["id"], $row["Groupe"], $row["Libelle"], "SELECTED" ) );
			else
				array_push($arrayCompetitionGroupe, array($row["id"], $row["Groupe"], $row["Libelle"], "" ) );
		}

		// Chargement des Compétitions ...
		$arrayCompetition = array();
		$recordCompetition = array();
		$arrayEquipe_publi = array();
		$existMatch = 0;
		$typeClt = array();
		$sql  = "Select * ";
		$sql .= "From gickp_Competitions ";
		$sql .= "Where Code_saison = '";
		$sql .= $codeSaison;
		$sql .= "' ";
		$sql .= utyGetFiltreCompetition('');
		$sql .= " And Publication='O' ";
		$sql .= " And Code_ref = '$codeCompetGroup' ";
		$sql .= " Order By Code_niveau, COALESCE(Code_ref, 'z'), Code_tour DESC, GroupOrder, Code";	 
		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 6a =>  ".$sql);
		$num_results = mysql_num_rows($result);
		for ($i=0;$i<$num_results;$i++)
		{
			$row = mysql_fetch_array($result);	
			array_push($arrayCompetition, array($row["Code"], $row["Libelle"], "SELECTED" ) );
			$codeCompet1 = $row['Code'];
			$codeSaison = $row['Code_saison'];
			$typeClt = $row['Code_typeclt'];
			$Statut = $row['Statut'];
			//if ($row['ToutGroup'] == 'O' && ($row['Web'] != '' || $row['LogoLink'] != ''))
			if ($row['Web'] != '' || $row['LogoLink'] != '')
			{
				$recordCompetition[] = array( 'ToutGroup' => $row['ToutGroup'], 'Web' => $row['Web'], 'LogoLink' => $row['LogoLink'] );
			}
			//Existence de matchs
			$sql2  = "Select m.Id ";
			$sql2 .= "From gickp_Matchs m, gickp_Journees j ";
			$sql2 .= "Where m.Id_journee = j.Id ";
			$sql2 .= "And m.Publication = 'O' ";
			$sql2 .= "And j.Publication = 'O' ";
			$sql2 .= "And j.Code_competition = '$codeCompet1' ";
			$sql2 .= "And j.Code_saison = '$codeSaison' ";
			$result2 = mysql_query($sql2, $myBdd->m_link) or die ("Erreur Load 5b => ".$sql2);
			if (mysql_num_rows($result2) > 0)
				$existMatch = 1;
		
			// Classement public				
			$sql2  = "Select ce.Id, ce.Numero, ce.Libelle, ce.Code_club, ce.Clt_publi, ce.Pts_publi, ce.J_publi, ce.G_publi, ce.N_publi, ce.P_publi, ce.F_publi, ce.Plus_publi, ce.Moins_publi, ce.Diff_publi, ce.PtsNiveau_publi, ce.CltNiveau_publi, c.Code_comite_dep ";
			$sql2 .= "From gickp_Competitions_Equipes ce, gickp_Club c ";
			$sql2 .= "Where ce.Code_compet = '";
			$sql2 .= $codeCompet1;
			$sql2 .= "' And ce.Code_saison = '";
			$sql2 .= $codeSaison;
			$sql2 .= "' And ce.Code_club = c.Code ";	 
			
			if ($typeClt == 'CP')
				$sql2 .= "Order By CltNiveau_publi Asc, Diff_publi Desc ";	 
			else
				$sql2 .= "Order By Clt_publi Asc, Diff_publi Desc ";	 
	
			$result2 = mysql_query($sql2, $myBdd->m_link) or die ("Erreur Load 5b => ".$sql2);
			$num_results2 = mysql_num_rows($result2);
		
			for ($j=0;$j<$num_results2;$j++)
			{
				$row2 = mysql_fetch_array($result2);	  
				if (strlen($row2['Code_comite_dep']) > 3)
					$row2['Code_comite_dep'] = 'FRA';
				if ($row2['Clt_publi'] != 0 || $row2['CltNiveau_publi'])
				{
					$arrayEquipe_publi[] = array( 	'CodeCompet' => $row['Code'], 'CodeSaison' => $row['Code_saison'], 'LibelleCompet' => $row['Libelle'], 'Code_typeclt' => $row['Code_typeclt'],
												'Code_tour' => $row['Code_tour'], 'Qualifies' => $row['Qualifies'], 'Elimines' => $row['Elimines'], 'Nb_equipes' => $row['Nb_equipes'],
												'Statut' => $Statut, 'Code_niveau' => $row['Code_niveau'], 'Soustitre' => $row['Soustitre'], 'Soustitre2' => $row['Soustitre2'], 'Web' => $row['Web'], 'LogoLink' => $row['LogoLink'], 'ToutGroup' => $row['ToutGroup'],
												'Id' => $row2['Id'], 'Numero' => $row2['Numero'], 'Libelle' => $row2['Libelle'], 'Code_club' => $row2['Code_club'], 'Code_comite_dep' => $row2['Code_comite_dep'],
												'Clt' => $row2['Clt_publi'], 'Pts' => $row2['Pts_publi'], 'existMatch' => $existMatch,
												'J' => $row2['J_publi'], 'G' => $row2['G_publi'], 'N' => $row2['N_publi'], 
												'P' => $row2['P_publi'], 'F' => $row2['F_publi'], 'Plus' => $row2['Plus_publi'], 
												'Moins' => $row2['Moins_publi'], 'Diff' => $row2['Diff_publi'],
												'PtsNiveau' => $row2['PtsNiveau_publi'], 'CltNiveau' => $row2['CltNiveau_publi'] );
				}
			}
		
		}
		$this->m_tpl->assign('arrayCompetition', $arrayCompetition);
		$this->m_tpl->assign('arrayCompetitionGroupe', $arrayCompetitionGroupe);
		$this->m_tpl->assign('arrayEquipe_publi', $arrayEquipe_publi);
		$this->m_tpl->assign('recordCompetition', $recordCompetition);
		$this->m_tpl->assign('typeClt', $typeClt);
//		print_r($recordCompetition);
//		print_r($arrayEquipe_publi);
//		print_r($arrayCompetition);

	}
	
	function GetTypeClt($codeCompet,  $codeSaison)
	{
		if (strlen($codeCompet) == 0)
			return 'CHPT';
			
		$myBdd = new MyBdd();
		
		$recordCompetition = $myBdd->GetCompetition($codeCompet, $codeSaison);
		$typeClt = $recordCompetition['Code_typeclt'];
		if ($typeClt != 'CP')
			$typeClt = 'CHPT';
		
		return $typeClt;
	}
	
	
	// ExistCompetitionEquipeNiveau
	function ExistCompetitionEquipeNiveau($idEquipe, $niveau)
	{
		$myBdd = new MyBdd();
	
		$sql  = "Select Count(*) Nb From gickp_Competitions_Equipes_Niveau Where Id = $idEquipe And Niveau = $niveau ";
		
		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Select 3");
		if (mysql_num_rows($result) == 1)
		{
			$row = mysql_fetch_array($result);	 
			if ($row['Nb'] == 1)
				return; // Le record existe ...
		}

		$sql  = "Insert Into gickp_Competitions_Equipes_Niveau (Id, Niveau, Pts, Clt, J, G, N, P, F, Plus, Moins, Diff, PtsNiveau, CltNiveau) ";
		$sql .= "Values ($idEquipe, $niveau, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0) ";
		mysql_query($sql, $myBdd->m_link) or die ("Erreur Insert 1");
	}

	// ExistCompetitionEquipeJournee
	function ExistCompetitionEquipeJournee($idEquipe, $idJournee)
	{
		$myBdd = new MyBdd();
		
		$sql  = "Select count(*) Nb From gickp_Competitions_Equipes_Journee Where Id = $idEquipe And Id_journee = $idJournee";
		
		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Select 4");
		if (mysql_num_rows($result) == 1)
		{
			$row = mysql_fetch_array($result);	 
			if ($row['Nb'] == 1)
				return; // Le record existe ...
		}

		$sql  = "Insert Into gickp_Competitions_Equipes_Journee (Id, Id_journee, Pts, Clt, J, G, N, P, F, Plus, Moins, Diff, PtsNiveau, CltNiveau) ";
		$sql .= "Values ($idEquipe, $idJournee, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0) ";
		
		mysql_query($sql, $myBdd->m_link) or die ("Erreur Insert 2");
	}
	

	// Classements		
	function Classements()
	{			
		MyPage::MyPage();
		
		$this->SetTemplate("Classements", "Classements", true);
		$this->Load();
		//$this->m_tpl->assign('AlertMessage', $alertMessage);
		$this->DisplayTemplate('Classements');
	}
}		  	

$page = new Classements();

?>
