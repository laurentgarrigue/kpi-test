<?php

include_once('commun/MyPage.php');
include_once('commun/MyBdd.php');
include_once('commun/MyTools.php');

// Details
class Details extends MyPage	 
{	
	function Load()
	{
		$myBdd = new MyBdd();

		$codeCompetGroup = utyGetSession('codeCompetGroup', 'N1H');
		$codeCompetGroup = utyGetPost('Group', $codeCompetGroup);
		$codeCompetGroup = utyGetGet('Group', $codeCompetGroup);
//		$this->m_tpl->assign('codeCompetGroup', $codeCompetGroup);
		$this->m_tpl->assign('group', $codeCompetGroup);
        if ((!isset($_SESSION['codeCompetGroup']) or $codeCompetGroup != $_SESSION['codeCompetGroup']) 
                and utyGetGet('Compet', '*') == '*') {
            $_GET['J'] = '*';
            $_GET['Compet'] = '*';
        }
		$_SESSION['codeCompetGroup'] = $codeCompetGroup;
		
		$codeCompet = utyGetSession('codeCompet', 'N1H');
		$codeCompet = utyGetPost('codeCompet', $codeCompet);
		$codeCompet = utyGetGet('Compet', $codeCompet);
		$_SESSION['codeCompet'] = $codeCompet;
		$this->m_tpl->assign('codeCompet', $codeCompet);
			
		$codeSaison = utyGetSaison();
		$codeSaison = utyGetPost('saisonTravail', $codeSaison);
		$codeSaison = utyGetGet('Saison', $codeSaison);
		$_SESSION['Saison'] = $codeSaison;
		$this->m_tpl->assign('Saison', $codeSaison);
        
		$idSelJournee = utyGetSession('idSelJournee', '*');
		$idSelJournee = utyGetPost('J', $idSelJournee);
		$idSelJournee = utyGetGet('J', $idSelJournee);
		$_SESSION['idSelJournee'] = $idSelJournee;
		$this->m_tpl->assign('idSelJournee', $idSelJournee);

        $Round = utyGetGet('Round', '*');
		$this->m_tpl->assign('Round', $Round);

		$filtreJour = utyGetGet('filtreJour', '');
		$_SESSION['filtreJour'] = $filtreJour;
		$this->m_tpl->assign('filtreJour', $filtreJour);

        $next = utyGetSession('next', '');
		$next = utyGetPost('next', $next);
		$next = utyGetGet('next', $next);
		$_SESSION['next'] = $next;
		$this->m_tpl->assign('next', $next);
        
        $private = utyGetSession('private', false);
		$private = utyGetGet('private', $private);
		$_SESSION['private'] = $private;

        $event = utyGetSession('event', 0);
		$event = utyGetPost('event', $event);
        $event = utyGetGet('event', $event);
		$this->m_tpl->assign('event', $event);
        if ($event != $_SESSION['event']) {
            $codeCompet = '*';
            $_SESSION['idSelCompet'] = $codeCompet;
            $this->m_tpl->assign('codeCompet', $codeCompet);
            $idSelJournee = '*';
            $_SESSION['idSelJournee'] = $idSelJournee;
            $this->m_tpl->assign('idSelJournee', $idSelJournee);
        }
		$_SESSION['event'] = $event;
        
        if (utyGetGet('navGroup', false)) {
            $arrayNavGroup = $myBdd->GetOtherCompetitions($codeCompet, $codeSaison, true, $event);
            $this->m_tpl->assign('arrayNavGroup', $arrayNavGroup);
            $this->m_tpl->assign('navGroup', 1);
        }

        if($codeCompet == '*' || count($arrayNavGroup) == 1) {
            $codeCompet = $arrayNavGroup[0]['Code'];
            $_SESSION['idSelCompet'] = $codeCompet;
            $this->m_tpl->assign('codeCompet', $codeCompet);
        }
        $codeCompet2 = $codeCompet;
		$this->m_tpl->assign('codeCompet2', $codeCompet2);
        
        $recordCompetition = $myBdd->GetCompetition($codeCompet, $codeSaison);
		$this->m_tpl->assign('Code_ref', $recordCompetition['Code_ref']);
		$this->m_tpl->assign('recordCompetition', $recordCompetition);
        
        $this->m_tpl->assign('Css', utyGetGet('Css', ''));

		// Chargement des Evénements
        $arrayEvents = $myBdd->GetEvents(true, false);
		$this->m_tpl->assign('arrayEvents', $arrayEvents);
        if ($event > 0) {
            foreach ($arrayEvents as $key => $value) {
                if ($value['Id'] == $event) {
                    $eventTitle = $value['Libelle'];
                    $this->m_tpl->assign('eventTitle', $eventTitle);
                }
            }
        }
           
        $type = $recordCompetition['Code_typeclt'];
		$this->m_tpl->assign('type', $type);
        
        
        if ($event > 0) {
            // Chargement des Compétitions de l'événement
            $sql  = "SELECT j.Id Id_journee, j.Libelle Libelle_journee, j.*, c.Libelle Libelle_compet, c.*, 0 Selected "
                    . "FROM gickp_Journees j, gickp_Competitions c, gickp_Evenement_Journees ej "
                    . "WHERE 1 "
                    . "AND j.Code_saison = $codeSaison "
                    . "AND j.Code_competition = c.Code "
                    . "AND j.Id = ej.Id_journee "
                    . "AND j.Code_saison = c.Code_saison "
                    . "AND j.Publication = 'O' "
                    . "AND c.Publication = 'O' "
                    . "AND ej.Id_evenement = $event "
                    . "GROUP BY c.Code "
                    . "ORDER BY c.GroupOrder ";	 
            $arrayListJournees = array();
            $result = $myBdd->Query($sql);
            while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){
                if ($row['Code_competition'] == $codeCompet) {
                    $row['Selected'] == true;
                    $journee[] = $row;
                } else {
                    $row['Selected'] == false;
                }
                array_push($arrayListJournees, $row);            
            }
            $this->m_tpl->assign('journee', $journee);
            $this->m_tpl->assign('arrayListJournees', $arrayListJournees);
            
            // Chargement des Equipes ...
            $arrayEquipe = array();
            $arrayPoule = array();
            $poule = '';
            if (strlen($codeCompet) > 0 && $codeCompet != '*') { 
                $sql  = "Select ce.Id, ce.Libelle, ce.Code_club, ce.Numero, ce.Poule, ce.Tirage, c.Code_comite_dep  ";
                $sql .= "From gickp_Competitions_Equipes ce, gickp_Club c ";
                $sql .= "Where ce.Code_compet = '";
                $sql .= $codeCompet;
                $sql .= "' And ce.Code_saison = '";
                $sql .= $codeSaison;
                $sql .= "' And ce.Code_club = c.Code ";	 
                $sql .= " Order By ce.Poule, ce.Tirage, ce.Libelle, ce.Id ";
                
                $result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load => ".$sql);
                while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){
                    //Logos
                    $logo = '';
                    $club = $row['Code_club'];
                    if(is_file('img/KIP/logo/'.$club.'-logo.png')){
                        $logo = 'img/KIP/logo/'.$club.'-logo.png';
                    }elseif(is_file('img/Nations/'.substr($club, 0, 3).'.png')){
                        $club = substr($club, 0, 3);
                        $logo = 'img/Nations/'.$club.'.png';
                    }

                    if (strlen($row['Code_comite_dep']) > 3) {
                        $row['Code_comite_dep'] = 'FRA';
                    }
                    if ($row['Tirage'] != 0 or $row['Poule'] != '') {
                        $this->m_tpl->assign('Tirage', 'ok');
                    }
                    if($row['Poule'] == '') {
                        $row['Poule'] = '-';
                    }
                    if ($row['Poule'] != $poule) {
                        $arrayPoule[] = $row['Poule'];
                    }
                    $poule = $row['Poule'];
                    
                    $arrayEquipe[$poule][] = array('Id' => $row['Id'], 
                                                        'Libelle' => $row['Libelle'], 
                                                        'Code_club' => $row['Code_club'],
                                                        'Code_comite_dep' => $row['Code_comite_dep'],
                                                        'logo' => $logo,
                                                        'club' => $club,
                                                        'Numero' => $row['Numero'], 
                                                        'Poule' => $row['Poule'], 
                                                        'Tirage' => $row['Tirage'], 
                                                        'Code_comite_dep' => $row['Code_comite_dep'] );
                }
            }	
            $this->m_tpl->assign('arrayEquipe', $arrayEquipe);
            $this->m_tpl->assign('arrayPoule', $arrayPoule);
            if (count($arrayPoule) % 2 == 0) {
                $poule = -1;
            }
            $this->m_tpl->assign('lastpoule', $poule);
            if(is_file('img/schemas/schema_' . $codeSaison . '_' . $codeCompet . '.png')) {
                $this->m_tpl->assign('schema', 'img/schemas/schema_' . $codeSaison . '_' . $codeCompet . '.png');
            }
            
        } elseif ($type == 'CHPT') {
            // Chargement des journées
            $sql  = "SELECT j.Id Id_journee, j.Libelle Libelle_journee, j.*, c.Libelle Libelle_compet, c.* "
                    . "FROM gickp_Journees j, gickp_Competitions c "
                    . "WHERE j.Code_competition = '$codeCompet' "
                    . "AND j.Code_saison = $codeSaison "
                    . "AND j.Code_competition = c.Code "
                    . "AND j.Code_saison = c.Code_saison "
                    . "AND j.Publication = 'O' "
                    . "AND c.Publication = 'O' "
                    . "ORDER BY j.Code_competition, j.Date_debut, j.Lieu ";
            $arrayListJournees = array();
            $journee = array();
            $result = $myBdd->Query($sql);
            while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){
                if($row['Id_journee'] == $idSelJournee || $idSelJournee == '*'){
                    $row['Selected'] = true;
                    $journee[] = $row;
                }else{
                    $row['Selected'] = false;
                }
                array_push($arrayListJournees, $row);            
            }
            $this->m_tpl->assign('journee', $journee);
            $this->m_tpl->assign('arrayListJournees', $arrayListJournees);
            
            $sql  = "SELECT DISTINCT ce.Libelle, ce.Code_club, Numero, c.Code_comite_dep             
                    FROM gickp_Competitions_Equipes ce, gickp_Matchs m, gickp_Club c ";
            if($idSelJournee != '*') {
                $sql .= "WHERE m.Id_journee = $idSelJournee ";
            } else {
                $sql .= "WHERE ce.Code_compet = '$codeCompet' "
                        . "AND ce.Code_saison = $codeSaison ";
            }
            $sql .= "AND ce.Code_club = c.Code
                    AND (ce.Id = m.Id_equipeA OR ce.Id = m.Id_equipeB)";
            $arrayEquipe = array();
            $result = $myBdd->Query($sql);
            while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){
                //Logos
                $row['logo'] = '';
                $row['club'] = $row['Code_club'];
                if(is_file('img/KIP/logo/'.$row['club'].'-logo.png')){
                    $row['logo'] = 'img/KIP/logo/'.$row['club'].'-logo.png';
                }elseif(is_file('img/Nations/'.substr($row['club'], 0, 3).'.png')){
                    $row['club'] = substr($row['club'], 0, 3);
                    $row['logo'] = 'img/Nations/'.$row['club'].'.png';
                }

                if (strlen($row['Code_comite_dep']) > 3) {
                    $row['Code_comite_dep'] = 'FRA';
                }
                array_push($arrayEquipe, $row);
            }
            $this->m_tpl->assign('arrayEquipe', $arrayEquipe);
                

        } else {
            // Chargement des Compétitions ...
            $sql  = "SELECT j.Id Id_journee, j.Libelle Libelle_journee, j.*, c.Libelle Libelle_compet, c.* "
                    . "FROM gickp_Journees j, gickp_Competitions c "
                    . "WHERE 1 "
                    . "AND j.Code_saison = $codeSaison "
                    . "AND j.Code_competition = c.Code "
                    . "AND j.Code_saison = c.Code_saison "
                    . "AND j.Publication = 'O' "
                    . "AND c.Publication = 'O' "
                    . "AND c.Code_ref = '$codeCompetGroup' "
                    . "GROUP BY c.Code "
                    . "ORDER BY c.Code_niveau, COALESCE(c.Code_ref, 'z'), c.GroupOrder, c.Code_tour, c.Code ";
            $arrayListJournees = array();
            $result = $myBdd->Query($sql);
            while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){
                if($row['Code_competition'] == $codeCompet){
                    $row['Selected'] == true;
                    $journee[] = $row;
                }else{
                    $row['Selected'] == false;
                }
                array_push($arrayListJournees, $row);            
            }
            $this->m_tpl->assign('journee', $journee);
            $this->m_tpl->assign('arrayListJournees', $arrayListJournees);
            
            // Chargement des Equipes ...
            $arrayEquipe = array();
            $arrayPoule = array();
            $poule = '';
            if (strlen($codeCompet) > 0 && $codeCompet != '*')
            { 
                $sql  = "Select ce.Id, ce.Libelle, ce.Code_club, ce.Numero, ce.Poule, ce.Tirage, c.Code_comite_dep  ";
                $sql .= "From gickp_Competitions_Equipes ce, gickp_Club c ";
                $sql .= "Where ce.Code_compet = '";
                $sql .= $codeCompet;
                $sql .= "' And ce.Code_saison = '";
                $sql .= $codeSaison;
                $sql .= "' And ce.Code_club = c.Code ";	 
                $sql .= " Order By ce.Poule, ce.Tirage, ce.Libelle, ce.Id ";
                
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){
                    //Logos
                    $logo = '';
                    $club = $row['Code_club'];
                    if(is_file('img/KIP/logo/'.$club.'-logo.png')){
                        $logo = 'img/KIP/logo/'.$club.'-logo.png';
                    }elseif(is_file('img/Nations/'.substr($club, 0, 3).'.png')){
                        $club = substr($club, 0, 3);
                        $logo = 'img/Nations/'.$club.'.png';
                    }

                    if (strlen($row['Code_comite_dep']) > 3) {
                        $row['Code_comite_dep'] = 'FRA';
                    }
                    if ($row['Tirage'] != 0 or $row['Poule'] != '') {
                        $this->m_tpl->assign('Tirage', 'ok');
                    }
                    if($row['Poule'] == '') {
                        $row['Poule'] = '-';
                    }
                    if ($row['Poule'] != $poule) {
                        $arrayPoule[] = $row['Poule'];
                    }
                    $poule = $row['Poule'];
                    
                    $arrayEquipe[$poule][] = array('Id' => $row['Id'], 
                                                        'Libelle' => $row['Libelle'], 
                                                        'Code_club' => $row['Code_club'],
                                                        'Code_comite_dep' => $row['Code_comite_dep'],
                                                        'logo' => $logo,
                                                        'club' => $club,
                                                        'Numero' => $row['Numero'], 
                                                        'Poule' => $row['Poule'], 
                                                        'Tirage' => $row['Tirage'], 
                                                        'Code_comite_dep' => $row['Code_comite_dep'] );
                }
            }	
            $this->m_tpl->assign('arrayEquipe', $arrayEquipe);
            $this->m_tpl->assign('arrayPoule', $arrayPoule);
            if (count($arrayPoule) % 2 == 0) {
                $poule = -1;
            }
            $this->m_tpl->assign('lastpoule', $poule);
            if(is_file('img/schemas/schema_' . $codeSaison . '_' . $codeCompet . '.png')) {
                $this->m_tpl->assign('schema', 'img/schemas/schema_' . $codeSaison . '_' . $codeCompet . '.png');
            }

        }
        
        //Logos
        $this->m_tpl->assign('visuels', utyGetVisuels($recordCompetition));
        $this->m_tpl->assign('page', 'Infos');
	}
		

	function Details()
	{			
        MyPage::MyPage();
		
		$alertMessage = '';
		
		$Cmd = '';
		if (isset($_POST['Cmd']))
			$Cmd = $_POST['Cmd'];

		if (strlen($Cmd) > 0)
		{
			if ($alertMessage == '')
			{
				header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);	
				exit;
			}
		}

		$this->SetTemplate("Details", "Matchs", true);
		$this->Load();
        
		// COSANDCO : Gestion Param Voie ...
		if (isset($_GET['voie']))
		{
			$voie = (int) $_GET['voie'];
			if ($voie > 0)
			{
                $this->m_tpl->assign('voie', $voie);
			}
		}        

		$this->DisplayTemplateFrame('frame_details');
	}
}		  	

$page = new Details();
