<?phpinclude_once('commun/MyPage.php');include_once('commun/MyBdd.php');include_once('commun/MyTools.php');// Gestion d'une Journeeclass Matchs extends MyPage	 {		function Load()	{		$myBdd = new MyBdd();        		$codeCompetGroup = utyGetGet('Group', 'N1H');        $Compets = utyGetGet('Compets', '');        $event = utyGetGet('event', 0);		$this->m_tpl->assign('group', $codeCompetGroup);		$this->m_tpl->assign('event', $event);        		$codeCompet = utyGetGet('Compet', $codeCompet);		$_SESSION['idSelCompet'] = $codeCompet;		$this->m_tpl->assign('codeCompet', $codeCompet);        $codeSaison = utyGetSaison();		$codeSaison = utyGetPost('saisonTravail', $codeSaison);		$codeSaison = utyGetGet('Saison', $codeSaison);		$_SESSION['Saison'] = $codeSaison;		$this->m_tpl->assign('Saison', $codeSaison);                $Round = utyGetGet('Round', '*');		$this->m_tpl->assign('Round', $Round);		        if($event > 0) {            $sql .= "SELECT DISTINCT(j.Code_competition), j.Code_saison "                    . "FROM gickp_Journees j, gickp_Evenement_Journees ej "                    . "WHERE j.Id = ej.Id_journee "                    . "AND ej.Id_evenement = $event ";            $result = $myBdd->Query($sql);            while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){                 if($Compets != '') {                    $Compets .= ',';                }                $Compets .= $row['Code_competition'];            }        }        		$this->m_tpl->assign('codeCompetGroup', $codeCompetGroup);        if((!isset($_SESSION['codeCompetGroup']) or $codeCompetGroup != $_SESSION['codeCompetGroup'])                && $event == 0) {            $_GET['J'] = '*';            $_GET['Compet'] = '*';        }		$_SESSION['codeCompetGroup'] = $codeCompetGroup;		        if($Compets != '') {            $Compets = explode(',', $Compets);            foreach ($Compets as $value) {                $Compets1[] = "'" . $value . "'";            }            $Compets = implode(',', $Compets1);        }        		$codeSaison = utyGetGet('Saison', utyGetSaison());        if($codeSaison != $_SESSION['Saison']){            $_GET['J'] = '*';            $_GET['Compet'] = '*';        }		$this->m_tpl->assign('Saison', $codeSaison);        		$idSelJournee = utyGetGet('J', '*');		$this->m_tpl->assign('idSelJournee', $idSelJournee);				$idSelCompet = utyGetGet('Compet', '*');		$this->m_tpl->assign('idSelCompet', $idSelJournee);				$arbitres = utyGetGet('arbitres', 1);		$this->m_tpl->assign('arbitres', $arbitres);		$filtreJour = utyGetSession('filtreJour', '');		$filtreJour = utyGetGet('filtreJour', $filtreJour);        $_SESSION['filtreJour'] = $filtreJour;		$this->m_tpl->assign('filtreJour', $filtreJour);                $terrains = utyGetSession('terrains', '1,2,3,4');        $terrains = utyGetGet('terrains', $terrains);        $_SESSION['terrains'] = $terrains;        $this->m_tpl->assign('terrains', $terrains);                $lstTerrainsArray = explode(',', $terrains);        $i = 1;        foreach ($lstTerrainsArray as $value) {            $switchTerrains[$value] = $i;            $i++;        }        $nbTerrains = count($lstTerrainsArray);        $this->m_tpl->assign('nbTerrains', $nbTerrains);        $this->m_tpl->assign('lstTerrainsArray', $lstTerrainsArray);                if (utyGetGet('navGroup', false)) {            $arrayNavGroup = $myBdd->GetOtherCompetitions($codeCompet, $codeSaison, true, $event);            $this->m_tpl->assign('arrayNavGroup', $arrayNavGroup);            $this->m_tpl->assign('navGroup', 1);        }                $arrayDates = [];        $arrayHeures = [];        $arrayTerrains = [];        		// Chargement des Compétitions ...		$arrayCompetition = array();		$sql  = "SELECT * "                ."FROM gickp_Competitions "                ."WHERE Code_saison = $codeSaison "                ."AND (Publication='O' OR Code_ref = 'M') ";        if($Compets == '') {            $sql .= "AND Code_ref = '$codeCompetGroup' ";        } else {            $sql .= "AND Code IN ($Compets) ";        }        $sql .= "ORDER BY Code_niveau, COALESCE(Code_ref, 'z'), GroupOrder, Code_tour, Code ";        $result = $myBdd->Query($sql);        $nbCompet = $myBdd->NumRows($result);        $listCompet = '';        while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){             array_push($arrayCompetition, $row);            if($idSelCompet == '*' || $idSelCompet == $row["Code"]) {                if ($listCompet) {                    $listCompet .= ',';                }                $listCompet .= "'".$row["Code"]."'";            }		}        $this->m_tpl->assign('arrayCompetition', $arrayCompetition);        $this->m_tpl->assign('nbCompet', $nbCompet);                // Chargement des Compétitions du groupe        $arrayCompetitionDuGroupe = array();        if (!$listCompet) {            $listCompet = "'0'";        }        // Chargement des journées        if ($event > 0) {            $sql  = "SELECT j.Id, j.Code_competition, j.Phase, j.Niveau, j.Libelle, j.Lieu, j.Date_debut "                    ."FROM gickp_Journees j, gickp_Competitions c, gickp_Evenement_Journees ej "                    ."WHERE ej.Id_journee = j.Id "                    ."AND ej.Id_evenement = $event "                    ."AND j.Code_competition In (".$listCompet.") "                    ."AND j.Code_saison = '".$codeSaison."' "                    ."AND j.Code_competition = c.Code "                    ."AND j.Code_saison = c.Code_saison "                    ."AND j.Publication = 'O' "                    ."ORDER BY j.Code_competition, j.Date_debut, j.Lieu ";        } else {            $sql  = "SELECT j.Id, j.Code_competition, j.Phase, j.Niveau, j.Libelle, j.Lieu, j.Date_debut "                    ."FROM gickp_Journees j, gickp_Competitions c "                    ."WHERE j.Code_competition In (".$listCompet.") "                    ."AND j.Code_saison = '".$codeSaison."' "                    ."AND j.Code_competition = c.Code "                    ."AND j.Code_saison = c.Code_saison "                    ."AND j.Publication = 'O' "                    ."ORDER BY j.Code_competition, j.Date_debut, j.Lieu ";        }        $arrayListJournees = array();        $result = $myBdd->Query($sql);        while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){            array_push($arrayListJournees, array( 'Id' => $row['Id'], 'Code_competition' => $row['Code_competition'],                                                 'Phase' => $row['Phase'], 'Niveau' => $row['Niveau'],                                                 'Libelle' => $row['Libelle'], 'Lieu' => $row['Lieu'],                                                 'Date_debut' => utyDateUsToFr($row['Date_debut']),                                                ));                    }		$this->m_tpl->assign('arrayListJournees', $arrayListJournees);                		// Chargement des Informations relatives aux Journées ...		if ($idSelJournee != '*') {			$sql  = "SELECT j.*, c.* "                    ."FROM gickp_Journees j, gickp_Competitions c "                    ."WHERE j.Id = $idSelJournee "                    ."AND j.Publication = 'O' ";					} elseif ($event > 0) {			$sql  = "SELECT j.Id, j.Code_competition, j.Phase, j.Niveau, j.Libelle, j.Lieu, j.Date_debut "                    ."FROM gickp_Journees j, gickp_Competitions c, gickp_Evenement_Journees ej "                    ."WHERE ej.Id_journee = j.Id "                    ."AND ej.Id_evenement = $event "                    ."AND j.Code_competition In (".$listCompet.") "                    ."AND j.Code_saison = '".$codeSaison."' "                    ." AND j.Code_competition = c.Code "                    ." AND j.Code_saison = c.Code_saison "                    ." AND j.Publication = 'O' "                    ." ORDER BY j.Code_competition, j.Date_debut, j.Lieu ";		} else {			$sql  = "SELECT j.Id, j.Code_competition, j.Phase, j.Niveau, j.Libelle, j.Lieu, j.Date_debut "                    ."FROM gickp_Journees j, gickp_Competitions c "                    ."WHERE j.Code_competition In (".$listCompet.") "                    ."AND j.Code_saison = '".$codeSaison."' "                    ." AND j.Code_competition = c.Code "                    ." AND j.Code_saison = c.Code_saison "                    ." AND j.Publication = 'O' "                    ." ORDER BY j.Code_competition, j.Date_debut, j.Lieu ";        }		$arrayJournees = array();		$lstJournee = '';        $result = $myBdd->Query($sql);        while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){ 			array_push($arrayJournees, array( 'Id' => $row['Id'], 'Code_competition' => $row['Code_competition'],                                                 'Phase' => $row['Phase'], 'Niveau' => $row['Niveau'],                                                 'Libelle' => $row['Libelle'], 'Lieu' => $row['Lieu'],                                                 'Date_debut' => utyDateUsToFr($row['Date_debut']),                                                                                                ));            if ($lstJournee) {                $lstJournee .= ',';            }            $lstJournee .= $row['Id'];		}		$_SESSION['lstJournee'] = $lstJournee;				if ($lstJournee != '') {			$selected = '';            // Ordre des Matchs 			$orderMatchs = 'ORDER BY a.Date_match, a.Heure_match, a.Terrain';						// Chargement des Matchs des journées ...			$sql  = "SELECT a.Id, a.Id_journee, a.Numero_ordre, a.Date_match, a.Heure_match, a.Libelle, a.Terrain, "                    . "a.Publication, a.Validation, a.Statut, a.Periode, a.ScoreDetailA, a.ScoreDetailB, "                    . "b.Libelle EquipeA, c.Libelle EquipeB, b.Numero NumA, c.Numero NumB, b.Code_club clubA, "                    . "c.Code_club clubB, a.Terrain, a.ScoreA, a.ScoreB, a.CoeffA, a.CoeffB, "                    . "a.Arbitre_principal, a.Arbitre_secondaire, a.Matric_arbitre_principal, a.Matric_arbitre_secondaire, "                    . "d.Code_competition, d.Phase, d.Niveau, d.Lieu, d.Libelle LibelleJournee, d.Date_debut, "                    . "e.Nom Nom_arb_prin, e.Prenom Prenom_arb_prin, f.Nom Nom_arb_sec, f.Prenom Prenom_arb_sec "                    . "FROM gickp_Matchs a "                    . "LEFT OUTER JOIN gickp_Competitions_Equipes b ON (a.Id_equipeA = b.Id) "                    . "LEFT OUTER JOIN gickp_Competitions_Equipes c ON (a.Id_equipeB = c.Id) "                    . "LEFT OUTER JOIN gickp_Liste_Coureur e ON (a.Matric_arbitre_principal = e.Matric) "                    . "LEFT OUTER JOIN gickp_Liste_Coureur f ON (a.Matric_arbitre_secondaire = f.Matric) "                    . ", gickp_Journees d "                    . "WHERE a.Id_journee IN ($lstJournee) "                    . "AND a.Id_journee = d.Id "                    . "AND a.Publication = 'O' "                    . "AND a.Terrain IN ($terrains) ";			$sql .= $orderMatchs;        	$dateDebut = '';			$dateFin = '';            $i = 0;            $listMatch = '';			$arrayMatchs = array();			$PhaseLibelle = 0;//            $terrains = [];            $date_match = '';            $heure_match = '';            $terrain_match = '';            $idTerrain = '';                        $result = $myBdd->Query($sql);            while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)) {                if ($filtreJour == '' || $row['Date_match'] == $filtreJour) {                    $row['Soustitre2'] = $myBdd->GetSoustitre2Competition($row['Code_competition'], $codeSaison);                    if ($row['Soustitre2'] != '') {                        $row['Code_competition'] = $row['Soustitre2'];                    }                    if ($row['Libelle'] != '' && strpbrk($row['Libelle'], '[')){                        $libelle = explode(']', $row['Libelle']);                        if ($_SESSION['lang'] == 'EN') {                            $EquipesAffectAuto = utyEquipesAffectAuto($row['Libelle']);                        } else {                            $EquipesAffectAuto = utyEquipesAffectAutoFR($row['Libelle']);                        }                        if ($row['EquipeA'] == '' && isset($EquipesAffectAuto[0]) && $EquipesAffectAuto[0] != '') {                            $row['EquipeA'] = $EquipesAffectAuto[0];                        }                        if ($row['EquipeB'] == '' && isset($EquipesAffectAuto[1]) && $EquipesAffectAuto[1] != '') {                            $row['EquipeB'] = $EquipesAffectAuto[1];                        }                        if ($row['Arbitre_principal'] != '' && $row['Arbitre_principal'] != '-1') {                            $row['Arbitre_principal'] = utyArbSansNiveau(                                utyInitialesPrenomArbitre(                                         $row['Arbitre_principal'], $row['Nom_arb_prin'],                                         $row['Prenom_arb_prin'], $row['Matric_arbitre_principal']));                        } elseif (isset($EquipesAffectAuto[2]) && $EquipesAffectAuto[2] != '') {                            $row['Arbitre_principal'] = $EquipesAffectAuto[2];                        }                        if ($row['Arbitre_secondaire'] != '' && $row['Arbitre_secondaire'] != '-1') {                            $row['Arbitre_secondaire'] = utyArbSansNiveau(                                utyInitialesPrenomArbitre(                                         $row['Arbitre_secondaire'], $row['Nom_arb_sec'],                                         $row['Prenom_arb_sec'], $row['Matric_arbitre_secondaire']));                        } elseif (isset($EquipesAffectAuto[3]) && $EquipesAffectAuto[3] != '') {                            $row['Arbitre_secondaire'] = $EquipesAffectAuto[3];                        }                        $row['Libelle'] = $libelle[1];                    }                    $Validation = 'O';                    if ($row['Validation'] != 'O') {                        $Validation = 'N';                    }                    $MatchAutorisation = 'O';                    if (!utyIsAutorisationJournee($row['Id_journee'])) {                        $MatchAutorisation = 'N';                    }                    if ($row['Date_match'] > date("Y-m-d")) {                        $past = 'past';                    } else {                        $past = '';                    }                    //Logos                    $logoA = '';                    $clubA = $row['clubA'];                    if (is_file('img/KIP/logo/'.$clubA.'-logo.png')) {                        $logoA = 'img/KIP/logo/'.$clubA.'-logo.png';                    } elseif (is_file('img/Nations/'.substr($clubA, 0, 3).'.png')) {                        $clubA = substr($clubA, 0, 3);                        $logoA = 'img/Nations/'.$clubA.'.png';                    }                    $logoB = '';                    $clubB = $row['clubB'];                    if (is_file('img/KIP/logo/'.$clubB.'-logo.png')) {                        $logoB = 'img/KIP/logo/'.$clubB.'-logo.png';                    } elseif (is_file('img/Nations/'.substr($clubB, 0, 3).'.png')) {                        $clubB = substr($clubB, 0, 3);                        $logoB = 'img/Nations/'.$clubB.'.png';                    }                    if ($date_match != $row['Date_match']) {                        $arrayDates[] = ['date' => $row['Date_match']];                    }                    $date_match = $row['Date_match'];                    if ($heure_match != $row['Heure_match']) {                        $arrayHeures[$row['Date_match']][] = ['heure' => $row['Heure_match']];                    }                    $heure_match = $row['Heure_match'];                    $numTerrain = $switchTerrains[$row['Terrain']];                    if ($terrain_match != $numTerrain) {                        $arrayTerrains[$row['Date_match']][$row['Heure_match']][] = ['terrain' => $row['Terrain']];                    }                    $terrain_match = $numTerrain;                    $arrayMatchs[$row['Date_match']][$row['Heure_match']][$numTerrain] = array(                                 'Id' => $row['Id'], 'Id_journee' => $row['Id_journee'], 'Numero_ordre' => $row['Numero_ordre'],                                 'Date_match' => utyDateUsToFr($row['Date_match']),'Date_EN' => $row['Date_match'], 'Heure_match' => $row['Heure_match'],                                'Libelle' => $row['Libelle'], 'Terrain' => $row['Terrain'],                                 'EquipeA' => $row['EquipeA'], 'EquipeB' => $row['EquipeB'],                                 'NumA' => $row['NumA'], 'NumB' => $row['NumB'],                                'ScoreA' => $row['ScoreA'], 'ScoreB' => $row['ScoreB'],                                 'ScoreDetailA' => $row['ScoreDetailA'], 'ScoreDetailB' => $row['ScoreDetailB'],                                 'Statut' => $row['Statut'], 'Periode' => $row['Periode'],                                 'CoeffA' => $row['CoeffA'], 'CoeffB' => $row['CoeffB'],                                'Arbitre_principal' => $row['Arbitre_principal'],                                 'Arbitre_secondaire' => $row['Arbitre_secondaire'],                                'Matric_arbitre_principal' => $row['Matric_arbitre_principal'],                                'Matric_arbitre_secondaire' => $row['Matric_arbitre_secondaire'],                                'Code_competition' => $row['Code_competition'],                                'Phase' => $row['Phase'],                                'Niveau' => $row['Niveau'],                                'Lieu' => $row['Lieu'],                                'LibelleJournee' => $row['LibelleJournee'],                                'MatchAutorisation' => $MatchAutorisation,                                'Validation' => $Validation,                                'past' => $past,                                'clubA' => $clubA,                                'clubB' => $clubB,                                'logoA' => $logoA,                                'logoB' => $logoB                            );                            if ($i != 0) {                            $listMatch .= ',';                        }                        $listMatch .= $row['Id'];                    if ($row['Phase'] != '' || $row['Libelle'] != '') {                        $PhaseLibelle = 1;                    }                    if ($i == 0) {                        $dateDebut = utyDateUsToFr($row['Date_match']);                        $dateFin = utyDateUsToFr($row['Date_match']);                    } else {                        if (utyDateCmpFr($dateDebut, utyDateUsToFr($row['Date_match'])) > 0) {                            $dateDebut = utyDateUsToFr($row['Date_match']);                        }                        if (utyDateCmpFr($dateFin, utyDateUsToFr($row['Date_match'])) < 0) {                            $dateFin = utyDateUsToFr($row['Date_match']);                        }                    }                }                                $arrayJours[$row['Date_match']] = 1;			}			$nb_terrains = count($arrayTerrains);			$this->m_tpl->assign('listMatch', $listMatch);			$this->m_tpl->assign('arrayDates', $arrayDates);			$this->m_tpl->assign('arrayHeures', $arrayHeures);			$this->m_tpl->assign('arrayTerrains', $arrayTerrains);			$this->m_tpl->assign('arrayMatchs', $arrayMatchs);			$this->m_tpl->assign('arrayJours', array_keys($arrayJours));			$this->m_tpl->assign('terrains', $terrains);			$this->m_tpl->assign('PhaseLibelle', $PhaseLibelle);			            $i++;		}				$this->m_tpl->assign('arrayJournees', $arrayJournees);		$this->m_tpl->assign('page', 'Terrains');        //        echo '<pre>';//        var_dump($arrayJournees); //        echo '</pre>';//        die();	}			function Matchs()	{					MyPage::MyPage();        		$this->SetTemplate("Matchs", "Matchs", true);		$this->Load();        		// COSANDCO : Gestion Param Voie ...		if (isset($_GET['voie']))		{			$voie = (int) $_GET['voie'];			if ($voie > 0)			{                $this->m_tpl->assign('voie', $voie);			}		}        		$this->DisplayTemplateFrame('frame_terrains');					}}		  	$page = new Matchs();