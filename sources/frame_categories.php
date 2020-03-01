<?php
include_once('commun/MyPage.php');
include_once('commun/MyBdd.php');
include_once('commun/MyTools.php');

// Gestion d'une Journee

class Matchs extends MyPage	 
{	
	function Load()
	{
		$myBdd = new MyBdd();
        
		$codeCompetGroup = utyGetGet('Group', 'N1H');
        $Compets = utyGetGet('Compets', '');
        $event = utyGetGet('event', 0);
		$this->m_tpl->assign('group', $codeCompetGroup);
		$this->m_tpl->assign('event', $event);
        
		$codeCompet = utyGetGet('Compet', $codeCompet);
		$_SESSION['idSelCompet'] = $codeCompet;
		$this->m_tpl->assign('codeCompet', $codeCompet);

        $codeSaison = utyGetSaison();
		$codeSaison = utyGetPost('saisonTravail', $codeSaison);
		$codeSaison = utyGetGet('Saison', $codeSaison);
		$_SESSION['Saison'] = $codeSaison;
		$this->m_tpl->assign('Saison', $codeSaison);
        
        $Round = utyGetGet('Round', '*');
		$this->m_tpl->assign('Round', $Round);
		
        if($event > 0) {
            $sql .= "SELECT DISTINCT(j.Code_competition), j.Code_saison "
                    . "FROM gickp_Journees j, gickp_Evenement_Journees ej "
                    . "WHERE j.Id = ej.Id_journee "
                    . "AND ej.Id_evenement = $event ";
            $result = $myBdd->Query($sql);
            while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){ 
                if($Compets != '') {
                    $Compets .= ',';
                }
                $Compets .= $row['Code_competition'];
            }
        }
        
		$this->m_tpl->assign('codeCompetGroup', $codeCompetGroup);
        if((!isset($_SESSION['codeCompetGroup']) or $codeCompetGroup != $_SESSION['codeCompetGroup'])
                && $event == 0) {
            $_GET['J'] = '*';
            $_GET['Compet'] = '*';
        }
		$_SESSION['codeCompetGroup'] = $codeCompetGroup;
		
        if($Compets != '') {
            $Compets = explode(',', $Compets);
            foreach ($Compets as $value) {
                $Compets1[] = "'" . $value . "'";
            }
            $Compets = implode(',', $Compets1);
        }
        
		$codeSaison = utyGetGet('Saison', utyGetSaison());
        if($codeSaison != $_SESSION['Saison']){
            $_GET['J'] = '*';
            $_GET['Compet'] = '*';
        }
		$this->m_tpl->assign('Saison', $codeSaison);
        
		$idSelJournee = utyGetGet('J', '*');
        if ($idSelJournee == '') {
            $idSelJournee = '*';
        }
		$this->m_tpl->assign('idSelJournee', $idSelJournee);
		
		$idSelCompet = utyGetGet('Compet', '*');
        if ($idSelCompet == '') {
            $idSelCompet = '*';
        }
		$this->m_tpl->assign('idSelCompet', $idSelCompet);
		
		$arbitres = utyGetGet('arbitres', 1);
		$this->m_tpl->assign('arbitres', $arbitres);

		$filtreJour = utyGetSession('filtreJour', '');
		$filtreJour = utyGetGet('filtreJour', $filtreJour);
        $_SESSION['filtreJour'] = $filtreJour;
		$this->m_tpl->assign('filtreJour', $filtreJour);
        
		$Pg = utyGetSession('Pg', '');
		$Pg = utyGetGet('Pg', $Pg);
        $_SESSION['Pg'] = $Pg;
		$this->m_tpl->assign('Pg', $Pg);
        
        $terrains = utyGetSession('terrains', '');
        $terrains = utyGetGet('terrains', $terrains);
        $_SESSION['terrains'] = $terrains;
        $this->m_tpl->assign('terrains', $terrains);
        
        if (utyGetGet('navGroup', false)) {
            $arrayNavGroup = $myBdd->GetOtherCompetitions($codeCompet, $codeSaison, true, $event);
            $this->m_tpl->assign('arrayNavGroup', $arrayNavGroup);
            $this->m_tpl->assign('navGroup', 1);
        }
        
        $this->m_tpl->assign('Css', utyGetGet('Css', ''));

        $arrayDates = [];
        $arrayHeures = [];
        
		// Chargement des Compétitions ...
		$arrayCompetition = array();
		$sql  = "SELECT * "
                ."FROM gickp_Competitions "
                ."WHERE Code_saison = $codeSaison "
                ."AND (Publication='O' OR Code_ref = 'M') ";
        if($Compets == '') {
            $sql .= "AND Code_ref = '$codeCompetGroup' ";
        } else {
            $sql .= "AND Code IN ($Compets) ";
        }
        $sql .= "ORDER BY Code_niveau, COALESCE(Code_ref, 'z'), GroupOrder, Code_tour, Code ";
        $result = $myBdd->Query($sql);
        $nbCompet = $myBdd->NumRows($result);
        $listCompet = '';

        while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){ 
            array_push($arrayCompetition, $row);
            if($idSelCompet == '*' || $idSelCompet == $row["Code"]) {
                if ($listCompet) {
                    $listCompet .= ',';
                }
                $listCompet .= "'".$row["Code"]."'";
            }
		}
        $this->m_tpl->assign('arrayCompetition', $arrayCompetition);
        $this->m_tpl->assign('nbCompet', $nbCompet);
        
        // Chargement des Compétitions du groupe
        $arrayCompetitionDuGroupe = array();

        if (!$listCompet) {
            $listCompet = "'0'";
        }

        // Chargement des journées
        if ($event > 0) {
            $sql  = "SELECT j.Id, j.Code_competition, j.Phase, j.Niveau, j.Libelle, j.Lieu, j.Date_debut "
                    ."FROM gickp_Journees j, gickp_Competitions c, gickp_Evenement_Journees ej "
                    ."WHERE ej.Id_journee = j.Id "
                    ."AND ej.Id_evenement = $event "
                    ."AND j.Code_competition In (".$listCompet.") "
                    ."AND j.Code_saison = '".$codeSaison."' "
                    ."AND j.Code_competition = c.Code "
                    ."AND j.Code_saison = c.Code_saison "
                    ."AND j.Publication = 'O' "
                    ."ORDER BY j.Code_competition, j.Date_debut, j.Lieu ";
        } else {
            $sql  = "SELECT j.Id, j.Code_competition, j.Phase, j.Niveau, j.Libelle, j.Lieu, j.Date_debut "
                    ."FROM gickp_Journees j, gickp_Competitions c "
                    ."WHERE j.Code_competition In (".$listCompet.") "
                    ."AND j.Code_saison = '".$codeSaison."' "
                    ."AND j.Code_competition = c.Code "
                    ."AND j.Code_saison = c.Code_saison "
                    ."AND j.Publication = 'O' "
                    ."ORDER BY j.Code_competition, j.Date_debut, j.Lieu ";
        }
        $arrayListJournees = array();
        $result = $myBdd->Query($sql);
        while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){
            array_push($arrayListJournees, array( 'Id' => $row['Id'], 'Code_competition' => $row['Code_competition'], 
                                                'Phase' => $row['Phase'], 'Niveau' => $row['Niveau'], 
                                                'Libelle' => $row['Libelle'], 'Lieu' => $row['Lieu'], 
                                                'Date_debut' => utyDateUsToFr($row['Date_debut']),
                                                ));            
        }
		$this->m_tpl->assign('arrayListJournees', $arrayListJournees);
        
        
		// Chargement des Informations relatives aux Journées ...
		if ($idSelJournee != '*') {
			$sql  = "SELECT j.*, c.* "
                    ."FROM gickp_Journees j, gickp_Competitions c "
                    ."WHERE j.Id = $idSelJournee "
                    ."AND j.Publication = 'O' ";
			
		} elseif ($event > 0) {
			$sql  = "SELECT j.Id, j.Code_competition, j.Phase, j.Niveau, j.Libelle, j.Lieu, j.Date_debut "
                    ."FROM gickp_Journees j, gickp_Competitions c, gickp_Evenement_Journees ej "
                    ."WHERE ej.Id_journee = j.Id "
                    ."AND ej.Id_evenement = $event "
                    ."AND j.Code_competition In (".$listCompet.") "
                    ."AND j.Code_saison = '".$codeSaison."' "
                    ." AND j.Code_competition = c.Code "
                    ." AND j.Code_saison = c.Code_saison "
                    ." AND j.Publication = 'O' "
                    ." ORDER BY j.Code_competition, j.Date_debut, j.Lieu ";
		} else {
			$sql  = "SELECT j.Id, j.Code_competition, j.Phase, j.Niveau, j.Libelle, j.Lieu, j.Date_debut "
                    ."FROM gickp_Journees j, gickp_Competitions c "
                    ."WHERE j.Code_competition In (".$listCompet.") "
                    ."AND j.Code_saison = '".$codeSaison."' "
                    ." AND j.Code_competition = c.Code "
                    ." AND j.Code_saison = c.Code_saison "
                    ." AND j.Publication = 'O' "
                    ." ORDER BY j.Code_competition, j.Date_debut, j.Lieu ";
        }
		$arrayJournees = array();
		$lstJournee = '';
        $result = $myBdd->Query($sql);
        while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){ 
			array_push($arrayJournees, array( 'Id' => $row['Id'], 'Code_competition' => $row['Code_competition'], 
                                                'Phase' => $row['Phase'], 'Niveau' => $row['Niveau'], 
                                                'Libelle' => $row['Libelle'], 'Lieu' => $row['Lieu'], 
                                                'Date_debut' => utyDateUsToFr($row['Date_debut']),
                                                
                                                ));
            if ($lstJournee) {
                $lstJournee .= ',';
            }
            $lstJournee .= $row['Id'];
		}
		$_SESSION['lstJournee'] = $lstJournee;
		
		if ($lstJournee != '') {
			$selected = '';
            // Ordre des Matchs 
			$orderMatchs = 'ORDER BY j.Code_competition, m.Date_match, m.Heure_match, m.Terrain';
			
			// Chargement des Matchs des journées ...
			$sql  = "SELECT m.Id, m.Id_journee, m.Numero_ordre, m.Date_match, m.Heure_match, m.Libelle, m.Terrain, "
                    . "m.Publication, m.Validation, m.Statut, m.Periode, m.ScoreDetailA, m.ScoreDetailB, "
                    . "cea.Libelle EquipeA, ceb.Libelle EquipeB, cea.Numero NumA, ceb.Numero NumB, cea.Code_club clubA, ceb.Code_club clubB, "
                    . "m.Terrain, m.ScoreA, m.ScoreB, m.CoeffA, m.CoeffB, "
                    . "m.Arbitre_principal, m.Arbitre_secondaire, m.Matric_arbitre_principal, m.Matric_arbitre_secondaire, "
                    . "j.Code_competition, j.Phase, j.Niveau, j.Lieu, j.Libelle LibelleJournee, j.Date_debut, c.Soustitre2, "
                    . "lcp.Nom Nom_arb_prin, lcp.Prenom Prenom_arb_prin, lcs.Nom Nom_arb_sec, lcs.Prenom Prenom_arb_sec "
                    . "FROM gickp_Matchs m "
                    . "LEFT OUTER JOIN gickp_Competitions_Equipes cea ON (m.Id_equipeA = cea.Id) "
                    . "LEFT OUTER JOIN gickp_Competitions_Equipes ceb ON (m.Id_equipeB = ceb.Id) "
                    . "LEFT OUTER JOIN gickp_Liste_Coureur lcp ON (m.Matric_arbitre_principal = lcp.Matric) "
                    . "LEFT OUTER JOIN gickp_Liste_Coureur lcs ON (m.Matric_arbitre_secondaire = lcs.Matric) "
                    . "INNER JOIN gickp_Journees j ON (m.Id_journee = j.Id) "
                    . "INNER JOIN gickp_Competitions c ON (j.Code_competition = c.Code AND j.Code_saison = c.Code_saison) "
                    . "WHERE m.Id_journee IN ($lstJournee) "
                    . "AND m.Publication = 'O' ";
            if ($terrains != '') {
                $sql .= "AND m.Terrain IN ($terrains) ";            }
			$sql .= $orderMatchs;

        	$dateDebut = '';
			$dateFin = '';
            $i = 0;
            $listMatch = '';
			$arrayMatchs = array();
			$PhaseLibelle = 0;
            $date_match = '';
            $heure_match = '';
            $categorie_match = '';
            $idTerrain = '';
            
            $result = $myBdd->Query($sql);
            while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)) {
                if ($filtreJour == '' || $row['Date_match'] == $filtreJour) {
                    if ($row['Soustitre2'] != '') {
                        $row['Categorie'] = $row['Soustitre2'];
                    } else {
                        $row['Categorie'] = $row['Code_competition'];
                    }

                    if ($row['Libelle'] != '' && strpbrk($row['Libelle'], '[')){
                        $libelle = explode(']', $row['Libelle']);
                        if ($_SESSION['lang'] == 'EN') {
                            $EquipesAffectAuto = utyEquipesAffectAuto($row['Libelle']);
                        } else {
                            $EquipesAffectAuto = utyEquipesAffectAutoFR($row['Libelle']);
                        }

                        if ($row['EquipeA'] == '' && isset($EquipesAffectAuto[0]) && $EquipesAffectAuto[0] != '') {
                            $row['EquipeA'] = $EquipesAffectAuto[0];
                        }
                        if ($row['EquipeB'] == '' && isset($EquipesAffectAuto[1]) && $EquipesAffectAuto[1] != '') {
                            $row['EquipeB'] = $EquipesAffectAuto[1];
                        }
                        if ($row['Arbitre_principal'] != '' && $row['Arbitre_principal'] != '-1') {
                            $row['Arbitre_principal'] = utyArbSansNiveau(
                                utyInitialesPrenomArbitre( 
                                        $row['Arbitre_principal'], $row['Nom_arb_prin'], 
                                        $row['Prenom_arb_prin'], $row['Matric_arbitre_principal']));
                        } elseif (isset($EquipesAffectAuto[2]) && $EquipesAffectAuto[2] != '') {
                            $row['Arbitre_principal'] = $EquipesAffectAuto[2];
                        }
                        if ($row['Arbitre_secondaire'] != '' && $row['Arbitre_secondaire'] != '-1') {
                            $row['Arbitre_secondaire'] = utyArbSansNiveau(
                                utyInitialesPrenomArbitre( 
                                        $row['Arbitre_secondaire'], $row['Nom_arb_sec'], 
                                        $row['Prenom_arb_sec'], $row['Matric_arbitre_secondaire']));
                        } elseif (isset($EquipesAffectAuto[3]) && $EquipesAffectAuto[3] != '') {
                            $row['Arbitre_secondaire'] = $EquipesAffectAuto[3];
                        }
                        $row['Libelle'] = $libelle[1];
                    }

                    $Validation = 'O';
                    if ($row['Validation'] != 'O') {
                        $Validation = 'N';
                    }

                    $MatchAutorisation = 'O';
                    if (!utyIsAutorisationJournee($row['Id_journee'])) {
                        $MatchAutorisation = 'N';
                    }

                    if ($row['Date_match'] > date("Y-m-d")) {
                        $past = 'past';
                    } else {
                        $past = '';
                    }

                    //Logos
                    $logoA = '';
                    $clubA = $row['clubA'];
                    if (is_file('img/KIP/logo/'.$clubA.'-logo.png')) {
                        $logoA = 'img/KIP/logo/'.$clubA.'-logo.png';
                    } elseif (is_file('img/Nations/'.substr($clubA, 0, 3).'.png')) {
                        $clubA = substr($clubA, 0, 3);
                        $logoA = 'img/Nations/'.$clubA.'.png';
                    }
                    $logoB = '';
                    $clubB = $row['clubB'];
                    if (is_file('img/KIP/logo/'.$clubB.'-logo.png')) {
                        $logoB = 'img/KIP/logo/'.$clubB.'-logo.png';
                    } elseif (is_file('img/Nations/'.substr($clubB, 0, 3).'.png')) {
                        $clubB = substr($clubB, 0, 3);
                        $logoB = 'img/Nations/'.$clubB.'.png';
                    }

                    if ($date_match != $row['Date_match']) {
                        $arrayDates[] = ['date' => $row['Date_match']];
                    }
                    $date_match = $row['Date_match'];

                    if ($heure_match != $row['Heure_match']) {
                        $arrayHeures[$row['Date_match']][] = ['heure' => $row['Heure_match']];
                    }
                    $heure_match = $row['Heure_match'];

                    $numCategorie[$row['Categorie']] = $row['Categorie'];
                    $categorie_match = $row['Categorie'];

                    $arrayMatchs[] = array( 
                                'Id' => $row['Id'], 'Id_journee' => $row['Id_journee'], 'Numero_ordre' => $row['Numero_ordre'], 
                                'Date_match' => utyDateUsToFr($row['Date_match']),'Date_EN' => $row['Date_match'], 'Heure_match' => $row['Heure_match'],
                                'Libelle' => $row['Libelle'], 'Terrain' => $row['Terrain'], 
                                'EquipeA' => $row['EquipeA'], 'EquipeB' => $row['EquipeB'], 
                                'NumA' => $row['NumA'], 'NumB' => $row['NumB'],
                                'ScoreA' => $row['ScoreA'], 'ScoreB' => $row['ScoreB'], 
                                'ScoreDetailA' => $row['ScoreDetailA'], 'ScoreDetailB' => $row['ScoreDetailB'], 
                                'Statut' => $row['Statut'], 'Periode' => $row['Periode'], 
                                'CoeffA' => $row['CoeffA'], 'CoeffB' => $row['CoeffB'],
                                'Arbitre_principal' => $row['Arbitre_principal'], 
                                'Arbitre_secondaire' => $row['Arbitre_secondaire'],
                                'Matric_arbitre_principal' => $row['Matric_arbitre_principal'],
                                'Matric_arbitre_secondaire' => $row['Matric_arbitre_secondaire'],
                                'Code_competition' => $row['Code_competition'],
                                'Phase' => $row['Phase'],
                                'Niveau' => $row['Niveau'],
                                'Lieu' => $row['Lieu'],
                                'LibelleJournee' => $row['LibelleJournee'],
                                'Categorie' => $row['Categorie'],
                                'MatchAutorisation' => $MatchAutorisation,
                                'Validation' => $Validation,
                                'past' => $past,
                                'clubA' => $clubA,
                                'clubB' => $clubB,
                                'logoA' => $logoA,
                                'logoB' => $logoB
                            );

                            if ($i != 0) {
                            $listMatch .= ',';
                        }
                        $listMatch .= $row['Id'];

                    if ($row['Phase'] != '' || $row['Libelle'] != '') {
                        $PhaseLibelle = 1;
                    }

                    if ($i == 0) {
                        $dateDebut = utyDateUsToFr($row['Date_match']);
                        $dateFin = utyDateUsToFr($row['Date_match']);
                    } else {
                        if (utyDateCmpFr($dateDebut, utyDateUsToFr($row['Date_match'])) > 0) {
                            $dateDebut = utyDateUsToFr($row['Date_match']);
                        }

                        if (utyDateCmpFr($dateFin, utyDateUsToFr($row['Date_match'])) < 0) {
                            $dateFin = utyDateUsToFr($row['Date_match']);
                        }
                    }
                }
                
                $arrayJours[$row['Date_match']] = 1;
                
                $i++;
			}

            $lstCategoriesArray = $numCategorie;
            sort($numCategorie);

            $nbCategories = count($numCategorie);
            $this->m_tpl->assign('nbCategories', $nbCategories);
            $this->m_tpl->assign('numCategorie', $numCategorie);
            $this->m_tpl->assign('lstCategoriesArray', $lstCategoriesArray);
            $this->m_tpl->assign('semiMatchs', count($arrayMatchs) / 2);
			$this->m_tpl->assign('listMatch', $listMatch);
			$this->m_tpl->assign('arrayDates', $arrayDates);
			$this->m_tpl->assign('arrayHeures', $arrayHeures);
			$this->m_tpl->assign('arrayMatchs', $arrayMatchs);
			$this->m_tpl->assign('arrayJours', array_keys($arrayJours));
			$this->m_tpl->assign('terrains', $terrains);
			$this->m_tpl->assign('PhaseLibelle', $PhaseLibelle);
			
		}
		
		$this->m_tpl->assign('arrayJournees', $arrayJournees);
		$this->m_tpl->assign('page', 'Categories');
        
	}
	
	
	function __construct()
	{			
		MyPage::MyPage();
        
		$this->SetTemplate("Matchs", "Matchs", true);
		$this->Load();
        
		// COSANDCO : Gestion Param Voie ...
		if (isset($_GET['voie'])) {
			$voie = (int) $_GET['voie'];
			if ($voie > 0) {
                $this->m_tpl->assign('voie', $voie);
            }
            
			$intervalle = (int) $_GET['intervalle'];
			if ($intervalle > 0) {
                $this->m_tpl->assign('intervalle', $intervalle);
			}
		}        

		$this->DisplayTemplateFrame('frame_categories');
		
		
	}
}		  	

$page = new Matchs();
