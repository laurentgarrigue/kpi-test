<?php

include_once('../commun/MyPage.php');
include_once('../commun/MyBdd.php');
include_once('../commun/MyTools.php');

// Gestion des Evenements

class GestionStats extends MyPageSecure	 
{	
	function Load()
	{
		$myBdd = new MyBdd();

        $sql_total = '';
		
        //Saison
		$codeSaison = utyGetSession('codeSaison', utyGetSaison());
		if (isset($_POST['codeSaison'])) {
            $codeSaison = $_POST['codeSaison'];
        }
        $this->m_tpl->assign('codeSaison', $codeSaison);
		$_SESSION['codeSaison'] = $codeSaison;

		//Competition
		$codeCompet = utyGetSession('codeCompet', '');
		if (isset($_POST['codeCompet'])) {
            $codeCompet = $_POST['codeCompet'];
        }
        $this->m_tpl->assign('codeCompet', $codeCompet);
		$_SESSION['codeCompet'] = $codeCompet;
		
		//CompetitionS selectionnées
		$Compets = utyGetSession('Compets', array( $codeCompet ));
		if (isset($_POST['Compets'])) {
            $Compets = $_POST['Compets'];
        }
        $this->m_tpl->assign('Compets', $Compets);
		$_SESSION['Compets'] = $Compets;
		$CompetsList = '("'.@implode('","',$Compets).'")';

        //Filtre affichage type compet
		$AfficheCompet = utyGetSession('AfficheCompet','');
		$AfficheCompet = utyGetPost('AfficheCompet', $AfficheCompet);
        $_SESSION['AfficheCompet'] = $AfficheCompet;
		$this->m_tpl->assign('AfficheCompet', $AfficheCompet);

        //Groupe Competition
		$groupCompet = utyGetSession('groupCompet', '');
		if (isset($_POST['groupCompet'])) {
            $groupCompet = $_POST['groupCompet'];
        }
        $this->m_tpl->assign('groupCompet', $groupCompet);
		$_SESSION['groupCompet'] = $groupCompet;
		
		//Filtre affichage niveau
		$AfficheNiveau = utyGetSession('AfficheNiveau', '');
		if (isset($_POST['AfficheNiveau'])) {
            $AfficheNiveau = $_POST['AfficheNiveau'];
        }
        $this->m_tpl->assign('AfficheNiveau', $AfficheNiveau);
		$_SESSION['AfficheNiveau'] = $AfficheNiveau;

		//Filtre affichage type compet
		$AfficheCompet = utyGetSession('AfficheCompet', '');
		if (isset($_POST['AfficheCompet'])) {
            $AfficheCompet = $_POST['AfficheCompet'];
        }
        $this->m_tpl->assign('AfficheCompet', $AfficheCompet);
		$_SESSION['AfficheCompet'] = $AfficheCompet;

		//Filtre affichage journee
		$AfficheJournee = utyGetSession('AfficheJournee', '');
		if (isset($_POST['AfficheJournee'])) {
            $AfficheJournee = $_POST['AfficheJournee'];
        }
        $this->m_tpl->assign('AfficheJournee', $AfficheJournee);
		$_SESSION['AfficheJournee'] = $AfficheJournee;

		//Filtre stat
		$AfficheStat = utyGetSession('AfficheStat', 'Buteurs');
		if (isset($_POST['AfficheStat'])) {
            $AfficheStat = $_POST['AfficheStat'];
        }
        $this->m_tpl->assign('AfficheStat', $AfficheStat);
		$_SESSION['AfficheStat'] = $AfficheStat;

		//Nb lignes
		$nbLignes = utyGetSession('nbLignes', 30);
		if (isset($_POST['nbLignes'])) {
            $nbLignes = $_POST['nbLignes'];
        }
        $this->m_tpl->assign('nbLignes', $nbLignes);
		$_SESSION['nbLignes'] = $nbLignes;

		// Chargement des Saisons ...
		$sql  = "SELECT Code "
                        . "FROM gickp_Saison "
                        . "ORDER BY Code DESC ";	 
        $sql_total .= '<br><br>'.$sql;
		$result = $myBdd->Query($sql);
		$num_results = $myBdd->NumRows($result);
		$arraySaison = array();
		for ($i=0;$i<$num_results;$i++)	{
			$row = $myBdd->FetchArray($result);
			array_push($arraySaison, array('Code' => $row['Code']));
		}
		$this->m_tpl->assign('arraySaison', $arraySaison);
		$this->m_tpl->assign('sessionSaison', $codeSaison);

		// Chargement des Compétitions
		$sql  = "Select c.Code, c.Code_niveau, c.Libelle, c.Code_ref, c.Code_typeclt, "
                . "c.Age_min, c.Age_max, c.Sexe, c.Code_tour, c.Nb_equipes, c.Verrou, c.Qualifies, c.Elimines, c.Publication "
                . "From gickp_Competitions c, gickp_Competitions_Groupes g "
                . "Where c.Code_saison = '" . $codeSaison . "' ";
		$sql .= utyGetFiltreCompetition('');
		$sql .= " And c.Code_niveau Like '".utyGetSession('AfficheNiveau')."%' ";
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
                . "Order By Code_niveau, COALESCE(Code_ref, 'z'), Code_tour, Code ";	 
        $sql_total .= '<br><br>'.$sql;
		$result = $myBdd->Query($sql);
		$arrayCompet = array();
		while ($row = $myBdd->FetchArray($result)){ 
			$StdOrSelected = 'Std';
			if ($codeCompet == $row['Code']) {
                $StdOrSelected = 'Selected';
            }
            array_push($arrayCompet, array( 'Code' => $row["Code"], 'Libelle' => $row["Libelle"], 'StdOrSelected' => $StdOrSelected ));
		}
		$this->m_tpl->assign('arrayCompet', $arrayCompet);

		// Chargement des CompétitionS 
		$sql  = "Select c.*, g.id, g.section, g.ordre "
                . "From gickp_Competitions c, gickp_Competitions_Groupes g "
                . "Where c.Code_saison = '" . $codeSaison . "' "
                . utyGetFiltreCompetition('') . " ";
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
                . "Order By c.Code_saison, g.section, g.ordre, COALESCE(c.Code_ref, 'z'), c.Code_tour, c.GroupOrder, c.Code ";	 
        $sql_total .= '<br><br>'.$sql;
		$arrayCompetition = array();
		$result = $myBdd->Query($sql);
        
        $listeCompet = "('";
        $i = -1;
        $j = '';
        $label = $myBdd->getSections();
		while ($row = $myBdd->FetchArray($result)){ 
            if($j != $row['section']) {
                $i ++;
                $arrayCompetition[$i]['label'] = $label[$row['section']];
            }
            if(in_array($row["Code"], $Compets)) {
                $row['selected'] = 'selected';
            } else {
                $row['selected'] = '';
            }
            $j = $row['section'];
            $arrayCompetition[$i]['options'][] = $row;
//			$key = $row['Code']; 
//			$arrayCompets[$key] = $row["Code"].'-'.$row["Libelle"]; 
		}
		$this->m_tpl->assign('arrayCompetition', $arrayCompetition);

		// Chargement des Groupes Compétitions
		$sql  = "Select Distinct c.Code_ref, c.Code_niveau, c.Libelle, c.Code_typeclt, c.Code_tour ";
		$sql .= "From gickp_Competitions c, gickp_Competitions_Groupes g ";
		$sql .= "Where c.Code_saison = '";
		$sql .= $codeSaison;
		$sql .= "' ";
		$sql .= utyGetFiltreCompetition('');
		$sql .= " And c.Code_niveau Like '".utyGetSession('AfficheNiveau')."%' ";
        if ($AfficheCompet == 'N') {
            $sql .= " And c.Code Like 'N%' ";
        } elseif ($AfficheCompet == 'CF') {
            $sql .= " And c.Code Like 'CF%' ";
        } elseif ($AfficheCompet == 'M') {
            $sql .= " And c.Code_ref = 'M' ";
        } elseif($AfficheCompet > 0) {
            $sql .= " And g.section = '" . $AfficheCompet . "' ";
        }        
        $sql .= " And c.Code_ref = g.Groupe ";
		$sql .= " Order By c.Code_niveau, COALESCE(c.Code_ref, 'z'), c.Code_tour ";	 
        $sql_total .= '<br><br>'.$sql;
		$arrayGroupCompet = array();
		$result = $myBdd->Query($sql);
		while ($row = $myBdd->FetchArray($result)){ 
			$StdOrSelected = 'Std';
			if ($groupCompet == $row['Code_ref']){
				$StdOrSelected = 'Selected';
            }
			array_push($arrayGroupCompet, array( 'Code_ref' => $row["Code_ref"], 'Libelle' => $row["Libelle"], 'StdOrSelected' => $StdOrSelected ));
		}
		$this->m_tpl->assign('arrayGroupCompet', $arrayGroupCompet);

		// Chargement des journées
		if($codeCompet != '' && $codeCompet != '*')
		{
			$sql  = "Select Id, Code_competition, Phase, Niveau, Libelle, Lieu, Date_debut ";
			$sql .= "From gickp_Journees ";
			$sql .= "Where Code_competition = '$codeCompet' And Code_saison = '$codeSaison' ";
			$sql .= "Order by Code_competition, Date_debut, Id ";
            $sql_total .= '<br><br>'.$sql;
			$arrayJournees = array();
            $result = $myBdd->Query($sql);
            while ($row = $myBdd->FetchArray($result)){ 
				$StdOrSelected = 'Std';
				if ($AfficheJournee == $row['Id']){
					$StdOrSelected = 'Selected';
                }
				array_push($arrayJournees, array( 'Id' => $row['Id'], 'Code_competition' => $row['Code_competition'], 'StdOrSelected' => $StdOrSelected,
                                                                        'Phase' => $row['Phase'], 'Niveau' => $row['Niveau'], 
                                                                        'Libelle' => $row['Libelle'], 'Lieu' => $row['Lieu'], 
                                                                        'Date_debut' => utyDateUsToFr($row['Date_debut']) ));
			}
			$this->m_tpl->assign('arrayJournees', $arrayJournees);
		}
		
		// Type Stats
		switch($AfficheStat)
		{
			case 'Buteurs' :
			default :
				$sql  = "SELECT d.Code_competition Competition, a.Matric Licence, a.Nom, a.Prenom, a.Sexe, b.Numero, f.Libelle Equipe, COUNT(*) Buts "
                    . "FROM gickp_Liste_Coureur a, gickp_Matchs_Detail b, gickp_Matchs c, gickp_Journees d, gickp_Competitions_Equipes f "
                    . "WHERE a.Matric = b.Competiteur "
                    . "AND b.Id_match = c.Id "
                    . "AND c.Id_journee = d.Id "
                    . "AND d.Code_competition = f.Code_compet "
                    . "AND d.Code_saison = f.Code_saison "
                    . "AND f.Id = IF(b.Equipe_A_B='A',c.Id_equipeA, c.Id_equipeB) "
                    . "AND d.Code_competition IN $CompetsList "
                    . "AND d.Code_saison = '$codeSaison' "
                    . "AND b.Id_evt_match = 'B' "
                    . "GROUP BY a.Matric "
                    . "ORDER BY Buts DESC, a.Nom "
                    . "LIMIT 0,$nbLignes ";
                $sql_total .= '<br><br>'.$sql;
				$arrayButeurs = array();
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result)){ 
					array_push($arrayButeurs, array( 'Competition' => $row['Competition'], 
								'Licence' => $row['Licence'],  
								'Nom' => $row['Nom'],  
								'Prenom' => $row['Prenom'],  
								'Sexe' => $row['Sexe'],  
								'Numero' => $row['Numero'],  
								'Equipe' => $row['Equipe'],  
								'Buts' => $row['Buts']));
				}
				$this->m_tpl->assign('arrayButeurs', $arrayButeurs);
				break;
			case 'Attaque' :
				$sql  = "SELECT d.Code_competition Competition, f.Libelle Equipe, COUNT(*) Buts "
                    . "FROM gickp_Matchs_Detail b, gickp_Matchs c, gickp_Journees d, gickp_Competitions_Equipes f "
                    . "WHERE b.Id_match = c.Id "
                    . "AND c.Id_journee = d.Id "
                    . "AND d.Code_competition = f.Code_compet "
                    . "AND d.Code_saison = f.Code_saison "
                    . "AND f.Id = IF(b.Equipe_A_B='A',c.Id_equipeA, c.Id_equipeB) "
                    . "AND d.Code_competition IN $CompetsList "
                    . "AND d.Code_saison = '$codeSaison' "
                    . "AND b.Id_evt_match = 'B' "
                    . "GROUP BY Equipe "
                    . "ORDER BY Buts DESC, Equipe "
                    . "LIMIT 0,$nbLignes ";
                $sql_total .= '<br><br>'.$sql;
				$arrayAttaque = array();
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result)){ 
					array_push($arrayAttaque, array( 'Competition' => $row['Competition'], 
								'Equipe' => $row['Equipe'],  
								'Buts' => $row['Buts']));
				}
				$this->m_tpl->assign('arrayAttaque', $arrayAttaque);
				break;
			case 'Defense' :
				$sql  = "SELECT d.Code_competition Competition, f.Libelle Equipe, COUNT(*) Buts "
                    . "FROM gickp_Matchs_Detail b, gickp_Matchs c, gickp_Journees d, gickp_Competitions_Equipes f "
                    . "WHERE b.Id_match = c.Id "
                    . "AND c.Id_journee = d.Id "
                    . "AND d.Code_competition = f.Code_compet "
                    . "AND d.Code_saison = f.Code_saison "
                    . "AND f.Id = IF(b.Equipe_A_B='B',c.Id_equipeA, c.Id_equipeB) "
                    . "AND d.Code_competition IN $CompetsList "
                    . "AND d.Code_saison = '$codeSaison' "
                    . "AND b.Id_evt_match = 'B' "
                    . "GROUP BY Equipe "
                    . "ORDER BY Buts ASC, Equipe "
                    . "LIMIT 0,$nbLignes ";
                $sql_total .= '<br><br>'.$sql;
				$arrayDefense = array();
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result)){ 
					array_push($arrayDefense, array( 'Competition' => $row['Competition'], 
								'Equipe' => $row['Equipe'],  
								'Buts' => $row['Buts']));
				}
				$this->m_tpl->assign('arrayDefense', $arrayDefense);
				break;
			case 'Cartons' :
				$sql  = "SELECT d.Code_competition Competition, a.Matric Licence, a.Nom, a.Prenom, a.Sexe, b.Numero, f.Libelle Equipe, "
                    . "SUM(IF(b.Id_evt_match='V',1,0)) Vert, "
                    . "SUM(IF(b.Id_evt_match='J',1,0)) Jaune, "
                    . "SUM(IF(b.Id_evt_match='R',1,0)) Rouge "
                    . "FROM gickp_Liste_Coureur a, gickp_Matchs_Detail b, gickp_Matchs c, gickp_Journees d, gickp_Competitions_Equipes f "
                    . "WHERE a.Matric = b.Competiteur "
                    . "AND b.Id_match = c.Id "
                    . "AND c.Id_journee = d.Id "
                    . "AND d.Code_competition = f.Code_compet "
                    . "AND d.Code_saison = f.Code_saison "
                    . "AND f.Id = IF(b.Equipe_A_B='A',c.Id_equipeA, c.Id_equipeB) "
                    . "AND d.Code_competition IN $CompetsList "
                    . "AND d.Code_saison = '$codeSaison' "
                    . "AND b.Id_evt_match IN ('V','J','R') "
                    . "GROUP BY a.Matric "
                    . "ORDER BY Rouge Desc, Jaune Desc, Vert Desc, Equipe, a.Nom "
                    . "LIMIT 0,$nbLignes ";
                $sql_total .= '<br><br>'.$sql;
				$arrayCartons = array();
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result)){ 
					array_push($arrayCartons, array( 'Competition' => $row['Competition'], 
								'Licence' => $row['Licence'],  
								'Nom' => $row['Nom'],  
								'Prenom' => $row['Prenom'],  
								'Sexe' => $row['Sexe'],  
								'Numero' => $row['Numero'],  
								'Equipe' => $row['Equipe'],  
								'Num' => $row['Num'],  
								'Date' => $row['Date'],  
								'Heure' => $row['Heure'],  
								'Vert' => $row['Vert'],  
								'Jaune' => $row['Jaune'],  
								'Rouge' => $row['Rouge']));
				}
				$this->m_tpl->assign('arrayCartons', $arrayCartons);
				break;
			case 'CartonsEquipe' :
				$sql  = "SELECT d.Code_competition Competition, f.Libelle Equipe, "
                    . "SUM(IF(b.Id_evt_match='V',1,0)) Vert, "
                    . "SUM(IF(b.Id_evt_match='J',1,0)) Jaune, "
                    . "SUM(IF(b.Id_evt_match='R',1,0)) Rouge "
                    . "FROM gickp_Matchs_Detail b, gickp_Matchs c, gickp_Journees d, gickp_Competitions_Equipes f "
                    . "WHERE b.Id_match = c.Id "
                    . "AND c.Id_journee = d.Id "
                    . "AND d.Code_competition = f.Code_compet "
                    . "AND d.Code_saison = f.Code_saison "
                    . "AND d.Code_competition IN $CompetsList "
                    . "AND d.Code_saison = '$codeSaison' "
                    . "AND f.Id = IF(b.Equipe_A_B='A',c.Id_equipeA, c.Id_equipeB) "
                    . "AND b.Id_evt_match IN ('V','J','R') "
                    . "GROUP BY Equipe "
                    . "ORDER BY Rouge Desc, Jaune Desc, Vert Desc, Equipe "
                    . "LIMIT 0,$nbLignes ";
                $sql_total .= '<br><br>'.$sql;
				$arrayCartonsEquipe = array();
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result)){ 
					array_push($arrayCartonsEquipe, array( 'Competition' => $row['Competition'], 
								'Equipe' => $row['Equipe'],  
								'Num' => $row['Num'],  
								'Date' => $row['Date'],  
								'Heure' => $row['Heure'],  
								'Vert' => $row['Vert'],  
								'Jaune' => $row['Jaune'],  
								'Rouge' => $row['Rouge']));
				}
				$this->m_tpl->assign('arrayCartonsEquipe', $arrayCartonsEquipe);
				break;
			case 'CartonsCompetition' :
				$sql  = "SELECT d.Code_competition Competition, "
                    . "SUM(IF(b.Id_evt_match='B',1,0)) Buts, "
                    . "SUM(IF(b.Id_evt_match='V',1,0)) Vert, "
                    . "SUM(IF(b.Id_evt_match='J',1,0)) Jaune, "
                    . "SUM(IF(b.Id_evt_match='R',1,0)) Rouge "
                    . "FROM gickp_Matchs_Detail b, gickp_Matchs c, gickp_Journees d, gickp_Competitions_Equipes f "
                    . "WHERE b.Id_match = c.Id "
                    . "AND c.Id_journee = d.Id "
                    . "AND d.Code_competition = f.Code_compet "
                    . "AND d.Code_saison = f.Code_saison "
                    . "AND d.Code_competition IN $CompetsList "
                    . "AND d.Code_saison = '$codeSaison' "
                    . "AND f.Id = IF(b.Equipe_A_B='A', c.Id_equipeA, c.Id_equipeB) "
                    . "AND b.Id_evt_match IN ('B','V','J','R') "
                    . "GROUP BY Code_competition "
                    . "ORDER BY Rouge Desc, Jaune Desc, Vert Desc, Code_competition "
                    . "LIMIT 0,$nbLignes ";
                $sql_total .= '<br><br>'.$sql;
				$arrayCartonsCompetition = array();
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result)){ 
					array_push($arrayCartonsCompetition, array( 'Competition' => $row['Competition'], 
                                'Buts' => $row['Buts'],  
								'Vert' => $row['Vert'],  
								'Jaune' => $row['Jaune'],  
								'Rouge' => $row['Rouge']));
				}
				$this->m_tpl->assign('arrayCartonsCompetition', $arrayCartonsCompetition);
				break;
			case 'Fairplay' :
				$sql  = "SELECT d.Code_competition Competition, a.Matric Licence, a.Nom, a.Prenom, a.Sexe, b.Numero, f.Libelle Equipe, "
                    . "SUM(IF(b.Id_evt_match='V',1, IF(b.Id_evt_match='J',2, IF(b.Id_evt_match='R',4,0)))) Fairplay "
                    . "FROM gickp_Liste_Coureur a, gickp_Matchs_Detail b, gickp_Matchs c, gickp_Journees d, gickp_Competitions_Equipes f "
                    . "WHERE a.Matric = b.Competiteur "
                    . "AND b.Id_match = c.Id "
                    . "AND c.Id_journee = d.Id "
                    . "AND d.Code_competition = f.Code_compet "
                    . "AND d.Code_saison = f.Code_saison "
                    . "AND d.Code_competition IN $CompetsList "
                    . "AND d.Code_saison = '$codeSaison' "
                    . "AND f.Id = IF(b.Equipe_A_B='A',c.Id_equipeA, c.Id_equipeB) "
                    . "AND b.Id_evt_match IN ('V','J','R') "
                    . "GROUP BY a.Matric "
                    . "ORDER BY Fairplay Desc, a.Nom "
                    . "LIMIT 0,$nbLignes ";
                $sql_total .= '<br><br>'.$sql;
				$arrayFairplay = array();
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result)){ 
					array_push($arrayFairplay, array( 'Competition' => $row['Competition'], 
								'Licence' => $row['Licence'],  
								'Nom' => $row['Nom'],  
								'Prenom' => $row['Prenom'],  
								'Sexe' => $row['Sexe'],  
								'Numero' => $row['Numero'],  
								'Equipe' => $row['Equipe'],  
								'Fairplay' => $row['Fairplay']));
				}
				$this->m_tpl->assign('arrayFairplay', $arrayFairplay);
				break;
			case 'FairplayEquipe' :
				$sql  = "SELECT d.Code_competition Competition, f.Libelle Equipe, "
                    . "SUM(IF(b.Id_evt_match='V',1, IF(b.Id_evt_match='J',2, IF(b.Id_evt_match='R',4,0)))) Fairplay "
                    . "FROM gickp_Matchs_Detail b, gickp_Matchs c, gickp_Journees d, gickp_Competitions_Equipes f "
                    . "WHERE b.Id_match = c.Id "
                    . "AND c.Id_journee = d.Id "
                    . "AND d.Code_competition = f.Code_compet "
                    . "AND d.Code_saison = f.Code_saison "
                    . "AND d.Code_competition IN $CompetsList "
                    . "AND d.Code_saison = '$codeSaison' "
                    . "AND f.Id = IF(b.Equipe_A_B='A',c.Id_equipeA, c.Id_equipeB) "
                    . "AND b.Id_evt_match IN ('V','J','R') "
                    . "GROUP BY Equipe "
                    . "ORDER BY Fairplay Desc, Equipe "
                    . "LIMIT 0,$nbLignes ";
                $sql_total .= '<br><br>'.$sql;
				$arrayFairplayEquipe = array();
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result)){ 
					array_push($arrayFairplayEquipe, array( 'Competition' => $row['Competition'], 
								'Equipe' => $row['Equipe'],  
								'Fairplay' => $row['Fairplay']));
				}
				$this->m_tpl->assign('arrayFairplayEquipe', $arrayFairplayEquipe);
				break;
                
            case 'Arbitrage' :
				$sql  = "SELECT j.Code_competition Competition, a.Matric Licence, lc.Nom, lc.Prenom, lc.Sexe, "
                    . "c.Code Code_club, c.Libelle Club, a.Arb, a.niveau, a.saison, a.Livret, "
                    . "SUM(IF(m.Matric_arbitre_principal=a.Matric,1,0)) principal, "
                    . "SUM(IF(m.Matric_arbitre_secondaire=a.Matric,1,0)) secondaire, "
                    . "COUNT(*) Total "
                    . "FROM gickp_Liste_Coureur lc, gickp_Arbitre a, gickp_Club c, gickp_Matchs m, gickp_Journees j "
                    . "WHERE 1 "
                    . "AND a.Matric = lc.Matric "
                    . "AND c.Code = lc.Numero_club "
                    . "AND m.Id_journee = j.Id "
                    . "AND j.Code_saison = $codeSaison "
                    . "AND j.Code_competition IN $CompetsList "
                    . "AND (m.Matric_arbitre_principal = a.Matric OR m.Matric_arbitre_secondaire = a.Matric) "
                    . "GROUP BY Licence "
                    . "ORDER BY Total DESC, principal DESC, lc.Nom "
                    . "LIMIT 0,$nbLignes ";
                $sql_total .= '<br><br>'.$sql;
				$arrayArbitrage = array();
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result)){ 
					array_push($arrayArbitrage, array( 'Competition' => $row['Competition'], 
								'Licence' => $row['Licence'],  
								'Nom' => $row['Nom'],  
								'Prenom' => $row['Prenom'],  
								'Sexe' => $row['Sexe'],  
								'Principal' => $row['principal'],  
								'Secondaire' => $row['secondaire'],  
								'Total' => $row['Total']));
				}
				$this->m_tpl->assign('arrayArbitrage', $arrayArbitrage);
				break;
			case 'ArbitrageEquipe' :
				$sql  = "SELECT d.Code_competition Competition, f.Libelle Equipe, "
                    . "SUM(IF((c.Arbitre_principal=f.Libelle) OR (c.Arbitre_principal LIKE CONCAT('%',f.Libelle,')%')),1,0)) principal, "
                    . "SUM(IF((c.Arbitre_secondaire=f.Libelle) OR (c.Arbitre_secondaire LIKE CONCAT('%',f.Libelle,')%')),1,0)) secondaire "
                    . "FROM gickp_Matchs c, gickp_Journees d, gickp_Competitions_Equipes f "
                    . "WHERE c.Id_journee = d.Id "
                    . "AND d.Code_competition = f.Code_compet "
                    . "AND d.Code_saison = f.Code_saison "
                    . "AND d.Code_competition IN $CompetsList "
                    . "AND d.Code_saison = '$codeSaison' "
                    . "GROUP BY Equipe "
                    . "ORDER BY principal DESC, secondaire DESC, Equipe "
                    . "LIMIT 0,$nbLignes ";
                $sql_total .= '<br><br>'.$sql;
				$arrayArbitrageEquipe = array();
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result)){ 
					array_push($arrayArbitrageEquipe, array( 'Competition' => $row['Competition'], 
								'Equipe' => $row['Equipe'],  
								'Principal' => $row['principal'],  
								'Secondaire' => $row['secondaire'],  
								'Total' => $row['principal']+$row['secondaire']));
				}
				//array_multisort($arrayArbitrageEquipe[3], SORT_DESC, $arrayArbitrageEquipe);
				$this->m_tpl->assign('arrayArbitrageEquipe', $arrayArbitrageEquipe);
				break;
			case 'CJouees' : // Compétitions jouées dans la saison en cours (par clubs)
				$sql  = "SELECT lc.Matric, lc.Nom, lc.Prenom, lc.Numero_club, clubs.Libelle Nom_club, j.Code_competition Competition, "
                    . "COUNT(DISTINCT mj.Id_match) Nb_matchs "
                    . "FROM gickp_Matchs_Joueurs mj, gickp_Matchs m, gickp_Journees j, gickp_Liste_Coureur lc, gickp_Club clubs "
                    . "WHERE lc.Matric = mj.Matric "
                    . "AND mj.Capitaine NOT IN ('E','A','X') "
                    . "AND lc.Numero_club = clubs.Code "
                    . "AND mj.Id_match = m.Id "
                    . "AND m.Id_journee = j.Id "
                    . "AND j.Code_competition IN $CompetsList "
                    . "AND j.Code_saison = '$codeSaison' "
                    . "AND m.Date_match <= CURDATE() "
                    . "AND m.Validation = 'O' "
                    . "GROUP BY mj.Matric, j.Code_competition "
                    . "ORDER BY lc.Numero_club, lc.Nom, lc.Prenom, Competition DESC  "
                    . "LIMIT 0,$nbLignes ";
                $sql_total .= '<br><br>'.$sql;
				$arrayCJouees = array();
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result)){ 
					array_push($arrayCJouees, array( 'Competition' => $row['Competition'], 
								'Matric' => $row['Matric'],  
								'Nom' => $row['Nom'],  
								'Prenom' => $row['Prenom'],  
								'Numero_club' => $row['Numero_club'],  
								'Nom_club' => $row['Nom_club'],  
								'Nb_matchs' => $row['Nb_matchs']));
				}
				$this->m_tpl->assign('arrayCJouees', $arrayCJouees);
				break;
			case 'CJouees2' : // Compétitions jouées dans la saison en cours (par équipe)
				$sql  = "SELECT ce.Libelle nomEquipe, lc.Matric, lc.Nom, lc.Prenom, j.Code_competition Competition, COUNT(DISTINCT mj.Id_match) Nb_matchs "
                    . "FROM gickp_Matchs_Joueurs mj, gickp_Matchs m, gickp_Journees j, gickp_Liste_Coureur lc, gickp_Competitions_Equipes ce "
                    . "WHERE lc.Matric = mj.Matric "
                    . "AND mj.Capitaine NOT IN ('E','A','X') "
                    . "AND mj.Id_match = m.Id "
                    . "AND IF(mj.Equipe = 'A', m.Id_equipeA, m.Id_equipeB) = ce.Id "
                    . "AND m.Id_journee = j.Id "
                    . "AND j.Code_competition IN $CompetsList "
                    . "AND j.Code_saison = '$codeSaison' "
                    . "AND m.Date_match <= CURDATE() "
                    . "AND m.Validation = 'O' "
                    . "GROUP BY nomEquipe, mj.Matric, j.Code_competition "
                    . "ORDER BY lc.Nom, lc.Prenom, Competition "
                    . "LIMIT 0,$nbLignes ";
                $sql_total .= '<br><br>'.$sql;
				$arrayCJouees2 = array();
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result)){ 
					array_push($arrayCJouees2, array( 'Competition' => $row['Competition'], 
								'Matric' => $row['Matric'],  
								'Nom' => $row['Nom'],  
								'Prenom' => $row['Prenom'],  
								'nomEquipe' => $row['nomEquipe'],  
								'Nb_matchs' => $row['Nb_matchs']));
				}
				$this->m_tpl->assign('arrayCJouees2', $arrayCJouees2);
				break;
			case 'CJouees3' : // Irrégularités
				$sql  = "SELECT ce.Libelle nomEquipe, lc.Matric, lc.Nom, lc.Prenom, j.Code_competition Competition, COUNT(DISTINCT mj.Id_match) Nb_matchs, "
                    . "lc.Origine, lc.Pagaie_ECA, lc.Etat_certificat_CK, lc.Etat_certificat_APS "
                    . "FROM gickp_Matchs_Joueurs mj, gickp_Matchs m, gickp_Journees j, gickp_Liste_Coureur lc, gickp_Competitions_Equipes ce "
                    . "WHERE lc.Matric = mj.Matric "
                    . "AND mj.Capitaine NOT IN ('E','A','X') "
                    . "AND mj.Id_match = m.Id "
                    . "AND IF(mj.Equipe = 'A', m.Id_equipeA, m.Id_equipeB) = ce.Id "
                    . "AND m.Id_journee = j.Id "
                    . "AND j.Code_competition IN $CompetsList "
                    . "AND j.Code_saison = '$codeSaison' "
                    . "AND m.Date_match <= CURDATE() "
                    . "AND m.Validation = 'O' "
                    . "AND (lc.Origine <> '$codeSaison' OR lc.Pagaie_ECA = '' OR lc.Pagaie_ECA = 'PAGJ' OR lc.Pagaie_ECA = 'PAGB' "
                    . " OR lc.Etat_certificat_CK = 'NON') "
                    . "GROUP BY nomEquipe, mj.Matric, j.Code_competition "
                    . "ORDER BY lc.Nom, lc.Prenom, Competition "
                    . "LIMIT 0,$nbLignes ";
                $sql_total .= '<br><br>'.$sql;
				$arrayCJouees3 = array();
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result)){ 
					$row['Irreg'] = '';
					if ($row['Origine'] != $codeSaison) {
                        $row['Irreg'] = 'Licence ' . $row['Origine'];
                    }
                    if($row['Pagaie_ECA'] == '' or $row['Pagaie_ECA'] == 'PAGJ' or $row['Pagaie_ECA'] == 'PAGB')
					{
						if ($row['Irreg'] != '') {
                            $row['Irreg'] .= '<br>';
                        }
                        if ($row['Pagaie_ECA'] != '') {
                            $row['Irreg'] .= $row['Pagaie_ECA'];
                        } else {
                            $row['Irreg'] .= 'PAG ?';
                        }
                    }
					if($row['Etat_certificat_CK'] == 'NON')
					{
						if ($row['Irreg'] != '') {
                            $row['Irreg'] .= '<br>';
                        }
                        $row['Irreg'] .= 'Certif CK';
					}
					array_push($arrayCJouees3, $row);
				}
				$this->m_tpl->assign('arrayCJouees3', $arrayCJouees3);
				break;
			case 'OfficielsJournees' : // OfficielsJournees
				$sql  = "SELECT j.* "
                    ."FROM gickp_Journees j "
                    ."WHERE 1 "
                    ."AND j.Code_competition IN $CompetsList "
                    ."AND j.Code_saison = '$codeSaison' "
                    ."GROUP BY j.Code_competition, j.Date_debut, j.Lieu "
                    ."ORDER BY j.Code_competition, j.Date_debut, j.Lieu "
                    ."LIMIT 0,$nbLignes ";
                $sql_total .= '<br><br>'.$sql;
				$nbOfficiels = 0;
				$arrayOfficielsJournees = array();
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result)){ 
					array_push($arrayOfficielsJournees, $row);
					if($row['Delegue'] != '' or $row['ChefArbitre'] != '')
					{
						$nbOfficiels ++;
					}
				}
				$this->m_tpl->assign('nbOfficiels', $nbOfficiels);
				$this->m_tpl->assign('nbJournees', $num_results);
				$this->m_tpl->assign('arrayOfficielsJournees', $arrayOfficielsJournees);
				break;
			case 'OfficielsMatchs' : // OfficielsMatchs
				$sql  = "SELECT j.Code_competition, j.Lieu, j.Departement, m.Id, m.Numero_ordre, m.Date_match, m.Heure_match, "
                                    . "a.Libelle equipeA, b.Libelle equipeB, m.Arbitre_principal, "
                                    . "m.Arbitre_secondaire, m.Ligne1, m.Ligne2, m.Secretaire, m.Chronometre, m.Timeshoot "
                                    . "FROM gickp_Journees j, gickp_Matchs m, gickp_Competitions_Equipes a, gickp_Competitions_Equipes b "
                                    . "WHERE 1 "
                                    . "AND j.Code_competition IN $CompetsList "
                                    . "AND j.Code_saison = '$codeSaison' "
                                    . "AND j.Id = m.Id_journee "
                                    . "AND m.Id_equipeA = a.Id "
                                    . "AND m.Id_equipeB = b.Id "
                                    . "ORDER BY j.Code_competition, m.Date_match, m.Heure_match, m.Numero_ordre "
                                    . "LIMIT 0,$nbLignes ";
                $sql_total .= '<br><br>'.$sql;
				$arrayOfficielsMatchs = array();
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result)){ 
					array_push($arrayOfficielsMatchs, $row);
				}
				$this->m_tpl->assign('arrayOfficielsMatchs', $arrayOfficielsMatchs);
				break;
			case 'ListeArbitres' : // ListeArbitres
				$sql  = "SELECT lc.Matric, lc.Nom, lc.Prenom, lc.Sexe, c.Code Code_club, c.Libelle Club, a.Arb, a.niveau, a.saison, a.Livret  "
                                    . "FROM gickp_Arbitre a, gickp_Liste_Coureur lc, gickp_Club c "
                                    . "WHERE 1 "
                                    . "AND a.Matric = lc.Matric "
                                    . "AND c.Code = lc.Numero_club "
                                    . "AND a.Matric < 2000000 "
                                    . "AND a.Arb != '' "
                                    . "ORDER BY a.Arb, a.Niveau, a.Saison, lc.Nom, lc.Prenom "
                                    . "LIMIT 0,$nbLignes ";
                $sql_total .= '<br><br>'.$sql;
				$arrayListeArbitres = array();
                $result = $myBdd->Query($sql);
                while ($row = $myBdd->FetchArray($result)){ 
                    array_push($arrayListeArbitres, $row);
				}
				$this->m_tpl->assign('arrayListeArbitres', $arrayListeArbitres);
                break;
		}
        $_SESSION['sql_query'] = $sql;
        $this->m_tpl->assign('sql_csv', $AfficheStat);
	}
	
	function GestionStats()
	{			
	  MyPageSecure::MyPageSecure(10);
		
		$alertMessage = '';
		
		$Cmd = '';
		if (isset($_POST['Cmd']))
			$Cmd = $_POST['Cmd'];

		$ParamCmd = '';
		if (isset($_POST['ParamCmd']))
			$ParamCmd = $_POST['ParamCmd'];

		if (strlen($Cmd) > 0)
		{
			if ($Cmd == 'Add')
				($_SESSION['Profile'] <= 2) ? $this->Add() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($alertMessage == '')
			{
				header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);	
				exit;
			}
		}

		$this->SetTemplate("Statistiques", "Stats", false);
		$this->Load();
		$this->m_tpl->assign('AlertMessage', $alertMessage);
		$this->DisplayTemplate('GestionStats');
	}
}		  	

$page = new GestionStats();
