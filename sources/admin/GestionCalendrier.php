<?php

include_once('../commun/MyPage.php');
include_once('../commun/MyBdd.php');
include_once('../commun/MyTools.php');

// Gestion du Calendrier

class GestionCalendrier extends MyPageSecure	 
{	
	function Load()
	{
		$myBdd = new MyBdd();
		
        // Langue
        $langue = parse_ini_file("../commun/MyLang.ini", true);
        if (utyGetSession('lang') == 'en') {
            $lang = $langue['en'];
        } else {
            $lang = $langue['fr'];
        }
        
        //Filtre mois
		if (isset($_POST['filtreMois'])) {
            $_SESSION['filtreMois'] = $_POST['filtreMois'];
        } else {
            $_SESSION['filtreMois'] = '';
        }
        $filtreMois = $_SESSION['filtreMois'];
		$this->m_tpl->assign('filtreMois', $_SESSION['filtreMois']);

		// Chargement des Evenements ...
		$idEvenement = utyGetSession('idEvenement', -1);
		$idEvenement = utyGetPost('evenement', $idEvenement);

		//Filtre affichage type compet
		$AfficheCompet = utyGetSession('AfficheCompet','');
		$AfficheCompet = utyGetPost('AfficheCompet', $AfficheCompet);
        $_SESSION['AfficheCompet'] = $AfficheCompet;
		$this->m_tpl->assign('AfficheCompet', $AfficheCompet);

        $_SESSION['idEvenement'] = $idEvenement;
		$this->m_tpl->assign('idEvenement', $idEvenement);
		
		$sql  = "Select Id, Libelle, Date_debut ";
		$sql .= "From gickp_Evenement ";
		//$sql .= "Where Publication = 'O' ";
		$sql .= "Order By Date_debut DESC, Libelle ";	 
		
		$result = $myBdd->Query($sql);

		$arrayEvenement = array();
		if (-1 == $idEvenement) {
            $selected1 = 'SELECTED';
        } else {
            $selected1 = '';
        }
        if (utyGetSession('lang') == 'en') {
            array_push($arrayEvenement, array('Id' => -1, 'Libelle' => '* - All events', 'Selection' => $selected1));
        } else {
            array_push($arrayEvenement, array('Id' => -1, 'Libelle' => '* - Tous les événements', 'Selection' => ''));
        }

		while($row = $myBdd->FetchArray($result)) {
            $PublicEvt = '';

            if ($row["Id"] == $idEvenement) {
                array_push($arrayEvenement, array('Id' => $row['Id'], 'Libelle' => $row['Id'] . ' - ' . $row['Libelle'] . $PublicEvt, 'Selection' => 'SELECTED'));
            } else {
                array_push($arrayEvenement, array('Id' => $row['Id'], 'Libelle' => $row['Id'] . ' - ' . $row['Libelle'] . $PublicEvt, 'Selection' => ''));
            }
        }
		$this->m_tpl->assign('arrayEvenement', $arrayEvenement);

		// Mode Evenement 
		$modeEvenement = utyGetSession('modeEvenement', '1');
		$modeEvenement = utyGetPost('choixModeEvenement', $modeEvenement);
		$_SESSION['modeEvenement'] = $modeEvenement;
		$this->m_tpl->assign('modeEvenement', $modeEvenement);
				
		// Chargement des Compétitions ...
		$codeCompet = utyGetSession('codeCompet', '*');
		// si changement de compétition, RAZ journée sélectionnée
		if (isset($_POST['codeCompet'])) {	// @COSANDCO_WAMPSER
			if ($codeCompet != utyGetPost('codeCompet')) {
                $_SESSION['idSelJournee'] = '*';
            }
        }
		$codeCompet = utyGetPost('competition', $codeCompet);
		$_SESSION['codeCompet'] = $codeCompet;
		
		if ( ($modeEvenement == 1) && ($idEvenement != -1) ) {
			// Mode Filtrage => La Combo Competition est chargée avec uniquement les compétitions de l'Evenement ...
			$sql  = "Select Distinct a.Code_niveau, a.Code, a.Code_ref, a.Code_tour, a.Libelle, a.Soustitre, "
                    . "a.Soustitre2, a.Titre_actif, g.id, g.section, g.ordre "
                    . "From gickp_Competitions a, gickp_Journees b, "
                    . "gickp_Evenement_Journees c, gickp_Competitions_Groupes g "
                    . "Where a.Code = b.Code_competition "
                    . "And a.Code_saison = b.Code_saison "
                    . "And b.Id = c.Id_journee "
                    . "And c.Id_evenement  = $idEvenement "
                    . utyGetFiltreCompetition('a.')
                    . " And a.Code_niveau Like '".utyGetSession('AfficheNiveau')."%' ";
            if ($AfficheCompet == 'N') {
                $sql .= " And a.Code Like 'N%' ";
            } elseif ($AfficheCompet == 'CF') {
                $sql .= " And a.Code Like 'CF%' ";
            } elseif ($AfficheCompet == 'M') {
                $sql .= " And a.Code_ref = 'M' ";
            } elseif($AfficheCompet > 0) {
                $sql .= " And g.section = '" . $AfficheCompet . "' ";
            }
            $sql .= " And a.Code_ref = g.Groupe "
                    . " Order By a.Code_saison, g.section, g.ordre, COALESCE(a.Code_ref, 'z'), a.Code_tour, a.GroupOrder, a.Code";	 
		} else {
			// Mode Association => La Combo Competition est complete ...
			$sql  = "Select c.Code_niveau, c.Code_ref, c.Code_tour, c.Code, c.Libelle, c.Soustitre, "
                    . "c.Soustitre2, c.Titre_actif, g.id, g.section, g.ordre "
                    . "From gickp_Competitions c, gickp_Competitions_Groupes g "
                    . "Where c.Code_saison = '"
                    . utyGetSaison()
                    . "' "
                    . utyGetFiltreCompetition('c.')
                    . " And c.Code_niveau Like '".utyGetSession('AfficheNiveau')."%' ";
			if ($AfficheCompet == 'N') {
                $sql .= " And c.Code Like 'N%' ";
            } elseif ($AfficheCompet == 'CF') {
                $sql .= " And c.Code Like 'CF%' ";
            } elseif ($AfficheCompet == 'M') {
                $sql .= " And c.Code_ref = 'M' ";
            } elseif($AfficheCompet > 0) {
                $sql .= " And g.section = '" . $AfficheCompet . "' ";
            }
            $sql .= " And c.Code_ref = g.Groupe "
                    . " Order By c.Code_saison, g.section, g.ordre, COALESCE(c.Code_ref, 'z'), c.Code_tour, c.GroupOrder, c.Code";	 
		}
		
		$result = $myBdd->Query($sql);
	
		$arrayCompetition = array();
        
		if (-1 != $idEvenement) {
            if('*' == $codeCompet) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            if (utyGetSession('lang') == 'en') {
                $arrayCompetition[0]['label'] = "All competitions";
                $arrayCompetition[0]['options'][] = array('Code' => '*', 'Libelle' => 'All competitions', 'selected' => $selected );
            } else {
                $arrayCompetition[0]['label'] = "Toutes les compétitions";
                $arrayCompetition[0]['options'][] = array('Code' => '*', 'Libelle' => 'Toutes les compétitions', 'selected' => $selected );
            }
        
            $i = 0;
        } else {
            $i = -1;
        }
        
        $listeCompet = "('";
        $j = '';
        $label = $myBdd->getSections();
		while ($row = $myBdd->FetchArray($result)) {
			// Titre
			if($row["Titre_actif"] != 'O' && $row["Soustitre"] != '')
				$Libelle = $row["Soustitre"];
			else
				$Libelle = $row["Libelle"];
			if($row["Soustitre2"] != '')
				$Libelle .= ' - '.$row["Soustitre2"];

			$listeCompet .= $row["Code"]."','";
			
            if($j != $row['section']) {
                $i ++;
                $arrayCompetition[$i]['label'] = $label[$row['section']];
            }
            if($row["Code"] == $codeCompet) {
                $row['selected'] = 'selected';
            } else {
                $row['selected'] = '';
            }
            $j = $row['section'];
            $arrayCompetition[$i]['options'][] = $row;
		}
		$this->m_tpl->assign('arrayCompetition', $arrayCompetition);
		
		$listeCompet .= "')";

		// Les différents tris de compétition ...
		$orderCompet = utyGetSession('orderCompet', 'Date_debut, Niveau, Phase, Lieu, Libelle, Id');
		$orderCompet = utyGetPost('competitionOrder', $orderCompet);
		$_SESSION['orderCompet'] = $orderCompet;
			
		$arrayCompetitionOrder = array();
		if ("Date_debut, Niveau, Phase, Lieu, Libelle" == $orderCompet)
			array_push($arrayCompetitionOrder, array('Code' => 'Date_debut, Niveau, Phase, Lieu, Libelle, Id', 'Libelle' => $lang['Par_date_croissante'], 'Selection' => 'SELECTED' ) );
		else
			array_push($arrayCompetitionOrder, array('Code' => 'Date_debut, Niveau, Phase, Lieu, Libelle, Id', 'Libelle' => $lang['Par_date_croissante'], 'Selection' => '' ) );
			
		if ("Date_debut Desc, Niveau, Phase, Lieu, Libelle" == $orderCompet)
			array_push($arrayCompetitionOrder, array('Code' => 'Date_debut Desc, Niveau, Phase, Lieu, Libelle', 'Libelle' => $lang['Par_date_decroissante'], 'Selection' => 'SELECTED' ) );
		else
			array_push($arrayCompetitionOrder, array('Code' => 'Date_debut Desc, Niveau, Phase, Lieu, Libelle', 'Libelle' => $lang['Par_date_decroissante'], 'Selection' => '' ) );
			
		if ("Libelle, Niveau, Phase" == $orderCompet)
			array_push($arrayCompetitionOrder, array('Code' => 'Libelle, Niveau, Phase', 'Libelle' => $lang['Par_Nom'], 'Selection' => 'SELECTED' ) );
		else
			array_push($arrayCompetitionOrder, array('Code' => 'Libelle, Niveau, Phase', 'Libelle' => $lang['Par_Nom'], 'Selection' => '' ) );
			
		if ("Id, Niveau, Phase" == $orderCompet)
			array_push($arrayCompetitionOrder, array('Code' => 'Id, Niveau, Phase', 'Libelle' => $lang['Par_Numero'], 'Selection' => 'SELECTED' ) );
		else
			array_push($arrayCompetitionOrder, array('Code' => 'Id, Niveau, Phase', 'Libelle' => $lang['Par_Numero'], 'Selection' => '' ) );
			
		if ("Niveau, Phase, Date_debut" == $orderCompet)
			array_push($arrayCompetitionOrder, array('Code' => 'Niveau, Phase, Date_debut', 'Libelle' => $lang['Par_Niveau'], 'Selection' => 'SELECTED' ) );
		else
			array_push($arrayCompetitionOrder, array('Code' => 'Niveau, Phase, Date_debut', 'Libelle' => $lang['Par_Niveau'], 'Selection' => '' ) );

		$this->m_tpl->assign('arrayCompetitionOrder', $arrayCompetitionOrder);
		
	
		$arrayEvenementJournees = array();
		if ( ($modeEvenement == '2') && ($idEvenement != -1) )
		{
			// Mode Association ... => Chargement des Journées de l'Evenement ...
			$sql = "Select Id_journee from gickp_Evenement_Journees Where Id_evenement = $idEvenement "; 
		
			$result = $myBdd->Query($sql);
			$num_results = $myBdd->NumRows($result);
			
            while($row = $myBdd->FetchArray($result)) {
				array_push($arrayEvenementJournees, $row['Id_journee']);
			}
		}
	
		// Chargement des Journees ...
		$arrayJournees = array();
		
		$sql  = "Select Id, Code_competition, Type, Phase, Niveau, Etape, Nbequipes, Date_debut, Date_fin, Nom, Libelle, Lieu, "
                . "Plan_eau, Departement, Responsable_insc, Responsable_R1, Organisateur, Delegue, ChefArbitre, Publication "
                . "From gickp_Journees "
                . "Where Code_competition Is Not Null ";
		// Contrôle compétitions autorisées
		$sql .= "And Code_Competition In ".$listeCompet." ";
		
		if ($codeCompet != "*")
		{
			$sql .= "And Code_competition = '";
			$sql .= $codeCompet;
			$sql .= "' ";
            $competition = $myBdd->GetCompetition($codeCompet, utyGetSaison());
            $this->m_tpl->assign('competition', $competition);
		}
		
		$sql .= "And Code_saison = '";
		$sql .= utyGetSaison();
		$sql .= "' ";
		if($filtreMois > 0)
			$sql .= "And (MONTH(Date_debut) = $filtreMois OR MONTH(Date_fin) = $filtreMois) ";
		if ( ($idEvenement != -1) && ($modeEvenement == '1') )
		{
			$sql .= "And Id In (Select Id_Journee From gickp_Evenement_Journees Where Id_evenement = $idEvenement) ";
		}
		// Limite l'affichage
		if ($idEvenement == -1 && $codeCompet == '*')
		{
			$sql .= "And Code_competition != 'POUBELLE' ";
			$sql .= "And (Date_fin - Date_debut) < 20 ";
			
		}
		if (strlen($orderCompet) > 0) {
			$sql .= "Order By $orderCompet";
		}

		$result = $myBdd->Query($sql);
		while($row = $myBdd->FetchArray($result)) {
			
			$Checked = '';
			if ($modeEvenement == '2')
			{
				// Mode Association ...
				for ($j=0;$j<Count($arrayEvenementJournees);$j++)
				{
					if ($row['Id'] == $arrayEvenementJournees[$j])
					{
						$Checked = 'checked';
						break;
					}
				}
			}
			$bAutorisation = utyIsAutorisationJournee($row['Id']);
			array_push($arrayJournees, array( 'Id' => $row['Id'], 
                                'Autorisation' => $bAutorisation,	
                                'Code_competition' => $row['Code_competition'],
                                'Phase' => $row['Phase'],
                                'Niveau' => $row['Niveau'],
                                'Etape' => $row['Etape'],
                                'Nbequipes' => $row['Nbequipes'],
                                'Date_debut' => utyDateUsToFr($row['Date_debut']), 
                                'Date_fin' => utyDateUsToFr($row['Date_fin']), 
                                'Nom' => $row['Nom'], 
                                'Libelle' => $row['Libelle'], 
                                'Type' => $row['Type'], 
                                'Lieu' => $row['Lieu'], 
                                'Plan_eau' => $row['Plan_eau'], 
                                'Departement' => $row['Departement'], 
                                'Responsable_insc' => $row['Responsable_insc'], 
                                'Responsable_R1' => $row['Responsable_R1'], 
                                'Delegue' => $row['Delegue'], 
                                'ChefArbitre' => $row['ChefArbitre'], 
                                'Organisateur' => $row['Organisateur'],
                                'Publication' => $row['Publication'],
                                'Checked' => $Checked ) );
		}
				
		$this->m_tpl->assign('arrayJournees', $arrayJournees);
	}
	
	function Remove()
	{
		$ParamCmd = utyGetPost('ParamCmd');
			
		$arrayParam = explode(',', $ParamCmd);		
		if (count($arrayParam) == 0)
			return; // Rien à Detruire ...
			
		$myBdd = new MyBdd();

		//Contrôle suppression possible
		$sql = "Select Id From gickp_Matchs Where Id_journee In (";
		for ($i=0;$i<count($arrayParam);$i++)
		{
			if ($i > 0)
				$sql .= ",";
			$sql .= $arrayParam[$i];
			//if ($arrayParam[$i] > 20080000)
			//	die ("Il s'agit d'une journée fédérale ! Déplacez-là plutôt dans la competition 'POUBELLE'. Suppression impossible (<a href='javascript:history.back()'>Retour</a>)");
		}
		$sql .= ") ";
		$result = $myBdd->Query($sql);
		if ($myBdd->NumRows($result) != 0) {
			die ("Il reste des matchs dans cette journée ! Suppression impossible (<a href='javascript:history.back()'>Retour</a>)");
		}

		// Suppression	
		$sql = "Delete From gickp_Journees Where Id In (";
		for ($i=0;$i<count($arrayParam);$i++)
		{
			if ($i > 0)
				$sql .= ",";
			
			$sql .= $arrayParam[$i];
		
			$myBdd->utyJournal('Suppression journee', '', '', 'NULL', 'NULL', $arrayParam[$i]);
		}
		$sql .= ")";
	
		$myBdd->Query($sql);
	}

	function GetNextIdJournee()
	{
		$myBdd = new MyBdd();

		$sql  = "Select max(Id) maxId From gickp_Journees Where Id < 19000001 ";
		$result = $myBdd->Query($sql);
	
		if ($myBdd->NumRows($result) == 1)
		{
			$row = $myBdd->FetchArray($result);	  
			return ((int) $row['maxId'])+1;
		}
		else
		{
			return 1;
		}
	}		

	function Duplicate()
	{
		$idJournee = utyGetPost('ParamCmd');
		if ($idJournee != 0)
		{
			$nextIdJournee = $this->GetNextIdJournee();
			
			$myBdd = new MyBdd();
	
			$sql  = "Insert Into gickp_Journees (Id, Code_competition, code_saison, Phase, Niveau, Etape, Nbequipes, Date_debut, Date_fin, Nom, Libelle, Type, Lieu, Plan_eau, ";
			$sql .= "Departement, Responsable_insc, Responsable_R1, Organisateur, Delegue, ChefArbitre) ";
			$sql .= "Select $nextIdJournee, Code_competition, code_saison, Phase, Niveau, Etape, Nbequipes, Date_debut, Date_fin, Nom, Libelle, Type, Lieu, Plan_eau, ";
			$sql .= "Departement, Responsable_insc, Responsable_R1, Organisateur, Delegue, ChefArbitre ";
			$sql .= "From gickp_Journees Where Id = $idJournee ";

			$myBdd->Query($sql);
		}			
		
		if (isset($_SESSION['ParentUrl']))
		{
			$target = $_SESSION['ParentUrl'];
			header("Location: http://".$_SERVER['HTTP_HOST'].$target);	
			exit;	
		}

		$myBdd->utyJournal('Dupplication journee', utyGetSaison(), '', '', $nextIdJournee); // A compléter (saison, compétition, options)
	}
	
	function ParamJournee()
	{
		$_SESSION['ParentUrl'] = $_SERVER['PHP_SELF'];

		$idJournee = (int) utyGetPost('ParamCmd', 0);
		$_SESSION['idJournee'] = $idJournee;
		
		header("Location: GestionParamJournee.php");	
		exit;	
	}	
	
	function AddEvenementJournee()
	{
		$idJournee = (int) utyGetPost('ParamCmd', 0);
		
		$idEvenement = utyGetPost('idEvenement', -1);
		if ($idEvenement == -1)
			return;
		$idEvenement = (int) $idEvenement;
		
		$sql = "Replace Into gickp_Evenement_Journees (Id_Evenement, Id_Journee) Values ($idEvenement, $idJournee)";
		$myBdd = new MyBdd();
	
		$myBdd->Query($sql);
		
		$myBdd->utyJournal('Evenement +journee', '', '', 'NULL', $idEvenement, $idJournee);
	}
	
	function RemoveEvenementJournee()
	{
		$idJournee = (int) utyGetPost('ParamCmd', 0);
		
		$idEvenement = utyGetSession('idEvenement', -1);
		if ($idEvenement == -1)
			return;
		$idEvenement = (int) $idEvenement;
		
		$sql = "Delete From gickp_Evenement_Journees Where Id_Evenement = $idEvenement And Id_Journee = $idJournee ";
		$myBdd = new MyBdd();
		$myBdd->Query($sql);
		
		$myBdd->utyJournal('Evenement -journee', '', '', 'NULL', $idEvenement, $idJournee);
	}
	
	function PubliJournee()
	{
		$idJournee = (int) utyGetPost('ParamCmd', 0);
		(utyGetPost('Pub', '') != 'O') ? $changePub = 'O' : $changePub = 'N';
		
		$sql = "Update gickp_Journees Set Publication = '$changePub' Where Id = $idJournee ";
		$myBdd = new MyBdd();
		$myBdd->Query($sql);
		
		$myBdd->utyJournal('Publication journee', '', '', 'NULL', 'NULL', $idJournee, $changePub);
	}

	function PubliMultiJournees()
	{
		$ParamCmd = '';
		if (isset($_POST['ParamCmd']))
			$ParamCmd = $_POST['ParamCmd'];
			
		$arrayParam = explode(',', $ParamCmd);		
		if (count($arrayParam) == 0)
			return; // Rien à changer ...

		$myBdd = new MyBdd();
		
		// Change Publication	
		for ($i=0;$i<count($arrayParam);$i++)
		{
			$sql = "Select Publication From gickp_Journees Where Id = ".$arrayParam[$i]." ";
			$result = $myBdd->Query($sql);
			if ($myBdd->NumRows($result) != 1)
				continue;
			$row = $myBdd->FetchArray($result);	
			($row['Publication']=='O') ? $changePub = 'N' : $changePub = 'O';
			$sql = "Update gickp_Journees Set Publication = '$changePub' Where Id = '".$arrayParam[$i]."' ";
			$myBdd->Query($sql);
			$myBdd->utyJournal('Publication journee', utyGetSaison(), '', 'NULL', 'NULL', $arrayParam[$i], $changePub);
		}
	}
	
	function __construct()
	{			
	  	MyPageSecure::MyPageSecure(10);
		
		$alertMessage = '';
	  
		$Cmd = utyGetPost('Cmd');
		
		if (strlen($Cmd) > 0)
		{
			if ($Cmd == 'Duplicate')
				($_SESSION['Profile'] <= 4) ? $this->Duplicate() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'Remove')
				($_SESSION['Profile'] <= 4) ? $this->Remove() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'ParamJournee')
				($_SESSION['Profile'] <= 10) ? $this->ParamJournee() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'AddEvenementJournee')
				($_SESSION['Profile'] <= 3) ? $this->AddEvenementJournee() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'RemoveEvenementJournee')
				($_SESSION['Profile'] <= 3) ? $this->RemoveEvenementJournee() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'PubliJournee')
				($_SESSION['Profile'] <= 4) ? $this->PubliJournee() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'PubliMultiJournees')
				($_SESSION['Profile'] <= 4) ? $this->PubliMultiJournees() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($alertMessage == '')
			{
				header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);	
				exit;
			}
		}
		
		$this->SetTemplate("Gestion_journees_phases_poules", "Journees_phases", false);
		$this->Load();
		$this->m_tpl->assign('AlertMessage', $alertMessage);
		$this->DisplayTemplate('GestionCalendrier');
	}
}		  	

$page = new GestionCalendrier();
