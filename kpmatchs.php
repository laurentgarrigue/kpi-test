<?phpinclude_once('commun/MyPage.php');include_once('commun/MyBdd.php');include_once('commun/MyTools.php');// Gestion d'une Journeeclass Matchs extends MyPage	 {		function Load()	{		$myBdd = new MyBdd();		$codeCompetGroup = utyGetSession('codeCompetGroup', 'N1H');		$codeCompetGroup = utyGetPost('Group', $codeCompetGroup);		$codeCompetGroup = utyGetGet('Group', $codeCompetGroup);		$this->m_tpl->assign('codeCompetGroup', $codeCompetGroup);        if(!isset($_SESSION['codeCompetGroup']) or $codeCompetGroup != $_SESSION['codeCompetGroup']){            $_GET['J'] = '*';            $_GET['Compet'] = '*';        }		$_SESSION['codeCompetGroup'] = $codeCompetGroup;				$codeSaison = utyGetSaison();		$codeSaison = utyGetPost('Saison', $codeSaison);		$codeSaison = utyGetGet('Saison', $codeSaison);        if($codeSaison != $_SESSION['Saison']){            $_GET['J'] = '*';            $_GET['Compet'] = '*';        }		$this->m_tpl->assign('Saison', $codeSaison);		$_SESSION['Saison'] = $codeSaison;				$idSelJournee = utyGetSession('idSelJournee', '*');		$idSelJournee = utyGetPost('J', $idSelJournee);		$idSelJournee = utyGetGet('J', $idSelJournee);		$_SESSION['idSelJournee'] = $idSelJournee;		$this->m_tpl->assign('idSelJournee', $idSelJournee);				$idSelCompet = utyGetSession('idSelCompet', '*');		$idSelCompet = utyGetPost('Compet', $idSelCompet);		$idSelCompet = utyGetGet('Compet', $idSelCompet);		$_SESSION['idSelCompet'] = $idSelCompet;		$this->m_tpl->assign('idSelCompet', $idSelCompet);				$filtreJour = utyGetGet('filtreJour', '');		$_SESSION['filtreJour'] = $filtreJour;		$this->m_tpl->assign('filtreJour', $filtreJour);		// Chargement des Saisons ...		$sql  = "SELECT Code, Etat, Nat_debut, Nat_fin, Inter_debut, Inter_fin "                ."FROM gickp_Saison "                ."ORDER BY Code DESC";		$arraySaison = array();        $result = $myBdd->Query($sql);        while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){ 			array_push($arraySaison, array('Code' => $row['Code'], 'Etat' => $row['Etat'], 											'Nat_debut' => utyDateUsToFr($row['Nat_debut']),                                            'Nat_fin' => utyDateUsToFr($row['Nat_fin']), 											'Inter_debut' => utyDateUsToFr($row['Inter_debut']),                                            'Inter_fin' => utyDateUsToFr($row['Inter_fin']) ));		}				$this->m_tpl->assign('arraySaison', $arraySaison);        		// Chargement des Groupes        $getGroups = $myBdd->GetGroups('public', $codeCompetGroup);		$this->m_tpl->assign('arrayCompetitionGroupe', $getGroups);           		// Chargement des Compétitions ...		$arrayCompetition = array();		$sql  = "SELECT * "                ."FROM gickp_Competitions "                ."WHERE Code_saison = $codeSaison "                ."AND Publication='O' "                ."AND Code_ref = '$codeCompetGroup' "                ."ORDER BY Code_niveau, COALESCE(Code_ref, 'z'), GroupOrder, Code_tour, Code ";	         $result = $myBdd->Query($sql);        $nbCompet = $myBdd->NumRows($result);        $listCompet = '';        while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){             array_push($arrayCompetition, $row);            if($idSelCompet == '*' || $idSelCompet == $row["Code"]){                if ($listCompet) {                    $listCompet .= ',';                }                $listCompet .= "'".$row["Code"]."'";            }		}        $this->m_tpl->assign('arrayCompetition', $arrayCompetition);        $this->m_tpl->assign('nbCompet', $nbCompet);                // Chargement des Compétitions du groupe        $arrayCompetitionDuGroupe = array();        if(!$listCompet)			$listCompet = "'0'";                // Chargement des journées        $sql  = "SELECT j.Id, j.Code_competition, j.Phase, j.Niveau, j.Libelle, j.Lieu, j.Date_debut "                    ."FROM gickp_Journees j, gickp_Competitions c "                    ."WHERE j.Code_competition In (".$listCompet.") "                    ."AND j.Code_saison = '".$codeSaison."' "                    ."AND j.Code_competition = c.Code "                    ."AND j.Code_saison = c.Code_saison "                    ."AND j.Publication = 'O' "                    ."ORDER BY j.Code_competition, j.Date_debut, j.Lieu ";        $arrayListJournees = array();        $result = $myBdd->Query($sql);        while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){            array_push($arrayListJournees, array( 'Id' => $row['Id'], 'Code_competition' => $row['Code_competition'],                                                 'Phase' => $row['Phase'], 'Niveau' => $row['Niveau'],                                                 'Libelle' => $row['Libelle'], 'Lieu' => $row['Lieu'],                                                 'Date_debut' => utyDateUsToFr($row['Date_debut']),                                                'Date_debut_en' => $row['Date_debut']                                                ));                    }		$this->m_tpl->assign('arrayListJournees', $arrayListJournees);                		// Chargement des Informations relatives aux Journées ...		if ($idSelJournee != '*')		{			$sql  = "SELECT j.*, c.* "                    ."FROM gickp_Journees j, gickp_Competitions c "                    ."WHERE j.Id = $idSelJournee "                    ."AND j.Publication = 'O' ";					}		else		{			$sql  = "SELECT j.Id, j.Code_competition, j.Phase, j.Niveau, j.Libelle, j.Lieu, j.Date_debut "                    ."FROM gickp_Journees j, gickp_Competitions c "                    ."WHERE j.Code_competition In (".$listCompet.") "                    ."AND j.Code_saison = '".$codeSaison."' "                    ." AND j.Code_competition = c.Code "                    ." AND j.Code_saison = c.Code_saison "                    ." AND j.Publication = 'O' "                    ." ORDER BY j.Code_competition, j.Date_debut, j.Lieu ";		}		$arrayJournees = array();		$lstJournee = '';        $result = $myBdd->Query($sql);        while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){ 			array_push($arrayJournees, array( 'Id' => $row['Id'], 'Code_competition' => $row['Code_competition'],                                                 'Phase' => $row['Phase'], 'Niveau' => $row['Niveau'],                                                 'Libelle' => $row['Libelle'], 'Lieu' => $row['Lieu'],                                                 'Date_debut' => utyDateUsToFr($row['Date_debut']),                                                                                                ));            if ($lstJournee) {                $lstJournee .= ',';            }            $lstJournee .= $row['Id'];		}		$_SESSION['lstJournee'] = $lstJournee;				if ($lstJournee != '')		{			$selected = '';            // Ordre des Matchs 			//$orderMatchs = utyGetSession('orderMatchs', 'Order By a.Date_match, a.Heure_match, a.Terrain');			$orderMatchs = utyGetPost('orderMatchs', 'ORDER BY a.Date_match, a.Heure_match, a.Terrain');			//$_SESSION['orderMatchs'] = $orderMatchs;						$arrayOrderMatchs = array();						//array_push($arrayOrderMatchs, array( 'Key' => 'Order By d.Date_debut, d.Niveau, d.Phase, d.Lieu, a.Id_journee, a.Date_match, a.Heure_match, a.Terrain', 'Value' => 'Par_Journee', 'Selected' => $selected ));			array_push($arrayOrderMatchs, array( 'Key' => 'ORDER BY a.Date_match, a.Heure_match, a.Terrain', 'Value' => 'Par_Date_Heure_et_Terrain', 'Selected' => $selected ));			array_push($arrayOrderMatchs, array( 'Key' => 'ORDER BY a.Numero_ordre, a.Date_match, a.Heure_match, a.Terrain', 'Value' => 'Par_Numero'));			array_push($arrayOrderMatchs, array( 'Key' => 'ORDER BY d.Code_competition, a.Date_match, a.Heure_match, a.Terrain', 'Value' => 'Par_Competition_et_Date'));			array_push($arrayOrderMatchs, array( 'Key' => 'ORDER BY a.Terrain, a.Date_match, a.Heure_match', 'Value' => 'Par_Terrain_et_Date'));			$this->m_tpl->assign('orderMatchs', $orderMatchs);			$this->m_tpl->assign('arrayOrderMatchs', $arrayOrderMatchs);			$orderMatchsKey1 = utyKeyOrder($orderMatchs, 0);			$this->m_tpl->assign('orderMatchsKey1', $orderMatchsKey1);						// Chargement des Matchs des journées ...			$sql  = "SELECT a.Id, a.Id_journee, a.Numero_ordre, a.Date_match, a.Heure_match, a.Libelle, a.Terrain, a.Publication, a.Validation, "                    ."a.Statut, a.Periode, a.ScoreDetailA, a.ScoreDetailB, "                    ."b.Libelle EquipeA, c.Libelle EquipeB, b.Numero NumA, c.Numero NumB, b.Code_club clubA, c.Code_club clubB, "                    ."a.Terrain, a.ScoreA, a.ScoreB, a.CoeffA, a.CoeffB, "                    ."a.Arbitre_principal, a.Arbitre_secondaire, a.Matric_arbitre_principal, a.Matric_arbitre_secondaire, "                    ."d.Code_competition, d.Phase, d.Niveau, d.Lieu, d.Libelle LibelleJournee, d.Date_debut "                    ."FROM gickp_Matchs a "                    ."LEFT OUTER JOIN gickp_Competitions_Equipes b ON (a.Id_equipeA = b.Id) "                    ."LEFT OUTER JOIN gickp_Competitions_Equipes c ON (a.Id_equipeB = c.Id) "                    .", gickp_Journees d "                    ."WHERE a.Id_journee IN ($lstJournee) "                    ."AND a.Id_journee = d.Id "                    ."AND a.Publication = 'O' ";			if($filtreJour != '')			{				$sql .= "And a.Date_match = '".$filtreJour."' ";			}			$sql .= $orderMatchs;        	$dateDebut = '';			$dateFin = '';            $i = 0;            $listMatch = '';			$arrayMatchs = array();			$PhaseLibelle = 0;            $result = $myBdd->Query($sql);            while ($row = $myBdd->FetchArray($result, $resulttype=MYSQL_ASSOC)){ 				$row['Soustitre2'] = $myBdd->GetSoustitre2Competition($row['Code_competition'], $codeSaison);				if ($row['Soustitre2'] != '') {                    $row['Code_competition'] = $row['Soustitre2'];                }                if ($row['Libelle'] != '' && strpbrk($row['Libelle'], '[')){					$libelle = explode(']', $row['Libelle']);					if ($_SESSION['lang'] == 'EN') {                        $EquipesAffectAuto = utyEquipesAffectAuto($row['Libelle']);                    } else {                        $EquipesAffectAuto = utyEquipesAffectAutoFR($row['Libelle']);                    }                    if ($row['EquipeA'] == '' && isset($EquipesAffectAuto[0]) && $EquipesAffectAuto[0] != '') {                        $row['EquipeA'] = $EquipesAffectAuto[0];                    }                    if ($row['EquipeB'] == '' && isset($EquipesAffectAuto[1]) && $EquipesAffectAuto[1] != '') {                        $row['EquipeB'] = $EquipesAffectAuto[1];                    }					if (isset($EquipesAffectAuto[2]) && $EquipesAffectAuto[2] != '') {                        $row['Arbitre_principal'] = $EquipesAffectAuto[2];                    }                    if (isset($EquipesAffectAuto[3]) && $EquipesAffectAuto[3] != '') {                        $row['Arbitre_secondaire'] = $EquipesAffectAuto[3];                    }                    if(count($libelle) > 1) {                        $row['Libelle'] = $libelle[1];                    }				}                $arbsup = array(" (Pool Arbitres 1)", " REG", " NAT", " INT", "-A", "-B", "-C", "-S");                if ($row['Arbitre_principal'] != '' && $row['Arbitre_principal'] != '-1') {                    $row['Arbitre_principal'] = str_replace($arbsup, '', $row['Arbitre_principal']);                }                if ($row['Arbitre_secondaire'] != '' && $row['Arbitre_secondaire'] != '-1') {                    $row['Arbitre_secondaire'] = str_replace($arbsup, '', $row['Arbitre_secondaire']);                }				$Validation = 'O';				if ($row['Validation'] != 'O') {                    $Validation = 'N';                }                $MatchAutorisation = 'O';				if (!utyIsAutorisationJournee($row['Id_journee'])) {                    $MatchAutorisation = 'N';                }                if ($row['Date_match'] > date("Y-m-d")) {                    $past = 'past';                } else {                    $past = '';                }                                //Logos                $logoA = '';                $clubA = $row['clubA'];                if(is_file('img/KIP/logo/'.$clubA.'-logo.png')){                    $logoA = 'img/KIP/logo/'.$clubA.'-logo.png';                }elseif(is_file('img/Nations/'.substr($clubA, 0, 3).'.png')){                    $clubA = substr($clubA, 0, 3);                    $logoA = 'img/Nations/'.$clubA.'.png';                }                $logoB = '';                $clubB = $row['clubB'];                if(is_file('img/KIP/logo/'.$clubB.'-logo.png')){                    $logoB = 'img/KIP/logo/'.$clubB.'-logo.png';                }elseif(is_file('img/Nations/'.substr($clubB, 0, 3).'.png')){                    $clubB = substr($clubB, 0, 3);                    $logoB = 'img/Nations/'.$clubB.'.png';                }                                array_push($arrayMatchs, array( 'Id' => $row['Id'], 'Id_journee' => $row['Id_journee'], 'Numero_ordre' => $row['Numero_ordre'], 							'Date_match' => utyDateUsToFr($row['Date_match']),'Date_EN' => $row['Date_match'], 'Heure_match' => $row['Heure_match'],							'Libelle' => $row['Libelle'], 'Terrain' => $row['Terrain'], 							'EquipeA' => $row['EquipeA'], 'EquipeB' => $row['EquipeB'], 							'NumA' => $row['NumA'], 'NumB' => $row['NumB'],							'ScoreA' => $row['ScoreA'], 'ScoreB' => $row['ScoreB'], 							'ScoreDetailA' => $row['ScoreDetailA'], 'ScoreDetailB' => $row['ScoreDetailB'], 							'Statut' => $row['Statut'], 'Periode' => $row['Periode'], 							'CoeffA' => $row['CoeffA'], 'CoeffB' => $row['CoeffB'],							'Arbitre_principal' => $row['Arbitre_principal'], 							'Arbitre_secondaire' => $row['Arbitre_secondaire'],							'Matric_arbitre_principal' => $row['Matric_arbitre_principal'],							'Matric_arbitre_secondaire' => $row['Matric_arbitre_secondaire'],							'Code_competition' => $row['Code_competition'],							'Phase' => $row['Phase'],							'Niveau' => $row['Niveau'],							'Lieu' => $row['Lieu'],							'LibelleJournee' => $row['LibelleJournee'],//							'StdOrSelected' => $StdOrSelected,							'MatchAutorisation' => $MatchAutorisation,//							'Publication' => $Publication,							'Validation' => $Validation,							'past' => $past,                            'clubA' => $clubA,                            'clubB' => $clubB,                            'logoA' => $logoA,                            'logoB' => $logoB                        ));								if($i != 0)					$listMatch .=',';				$listMatch .= $row['Id'];											if ($row['Phase'] != '' || $row['Libelle'] != '')					$PhaseLibelle = 1;																								if ($i == 0)				{					$dateDebut = utyDateUsToFr($row['Date_match']);					$dateFin = utyDateUsToFr($row['Date_match']);				}																								else				{					if (utyDateCmpFr($dateDebut, utyDateUsToFr($row['Date_match'])) > 0)						$dateDebut = utyDateUsToFr($row['Date_match']);											if (utyDateCmpFr($dateFin, utyDateUsToFr($row['Date_match'])) < 0)						$dateFin = utyDateUsToFr($row['Date_match']);				}																							}			$this->m_tpl->assign('listMatch', $listMatch);			$this->m_tpl->assign('arrayMatchs', $arrayMatchs);			$this->m_tpl->assign('PhaseLibelle', $PhaseLibelle);			//			if ( ($idMatch < 0) && (count($arrayMatchs) >= 1) )//			{//				$idMatch = $arrayMatchs[0]['Id'];//			}			            $i++;		}		//		$this->m_tpl->assign('idCurrentJournee', $idJournee);		$this->m_tpl->assign('arrayJournees', $arrayJournees);//		$this->m_tpl->assign('arrayJourneesAutorisees', $arrayJourneesAutorisees);//		$this->m_tpl->assign('codeCurrentCompet', $codeCompet);//		$this->m_tpl->assign('arrayCompet', $arrayCompet);	}			function Matchs()	{				  MyPage::MyPage();		//		if ($ParamCmd == 'changeCompet')//			$_SESSION['idMatch'] = -1; // La Combo Compétition a changé => Plus aucun match n'est sélectionné ...				$this->SetTemplate("Matchs", "Matchs", true);		$this->Load();//		$this->m_tpl->assign('AlertMessage', $alertMessage);				$this->m_tpl->assign('idMatch', utyGetSession('idMatch', 0));		$this->m_tpl->assign('idJournee', utyGetSession('idJournee', 0));				$this->m_tpl->assign('Intervalle_match', utyGetSession('Intervalle_match', '40'));		$this->m_tpl->assign('Num_match', utyGetSession('Num_match', ''));		$this->m_tpl->assign('Date_match', utyGetSession('Date_match', ''));		$this->m_tpl->assign('Heure_match', utyGetSession('Heure_match', ''));		$this->m_tpl->assign('Libelle', utyGetSession('Libelle', ''));		$this->m_tpl->assign('Terrain', utyGetSession('Terrain', ''));		$this->m_tpl->assign('arbitre1', utyGetSession('arbitre1', ''));		$this->m_tpl->assign('arbitre2', utyGetSession('arbitre2', ''));		$this->m_tpl->assign('arbitre1_matric', utyGetSession('arbitre1_matric', ''));		$this->m_tpl->assign('arbitre2_matric', utyGetSession('arbitre2_matric', ''));		$this->m_tpl->assign('coeffA', utyGetSession('coeffA', 1));		$this->m_tpl->assign('coeffB', utyGetSession('coeffB', 1));				$this->DisplayTemplateNew('kpmatchs');	}}		  	$page = new Matchs();