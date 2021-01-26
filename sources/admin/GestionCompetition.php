<?php

include_once('../commun/MyPage.php');
include_once('../commun/MyBdd.php');
include_once('../commun/MyTools.php');

// Gestion des Competitions
class GestionCompetition extends MyPageSecure	 
{	
	var $myBdd;

	function Load()
	{
		$myBdd = $this->myBdd;

        // Langue
        $langue = parse_ini_file("../commun/MyLang.ini", true);
        if (utyGetSession('lang') == 'en') {
            $lang = $langue['en'];
        } else {
            $lang = $langue['fr'];
        }
        
		$codeSaison = $myBdd->GetActiveSaison();
		
		$AuthSaison = utyGetSession('AuthSaison','');
		$this->m_tpl->assign('AuthSaison', $AuthSaison);

		$editCompet = utyGetSession('editCompet','');
		$this->m_tpl->assign('editCompet', $editCompet);

        $codeCompet = utyGetSession('codeCompet', -1);
        $codeCompet = utyGetPost('codeCompet',$codeCompet);
        
        
			
		//Filtre affichage niveau
		$_SESSION['AfficheNiveau'] = utyGetSession('AfficheNiveau','');

		$_SESSION['AfficheNiveau'] = utyGetPost('AfficheNiveau',$_SESSION['AfficheNiveau']);
		$this->m_tpl->assign('AfficheNiveau', $_SESSION['AfficheNiveau']);

		//Filtre affichage type compet
		$AfficheCompet = utyGetSession('AfficheCompet','');
		$AfficheCompet = utyGetPost('AfficheCompet', $AfficheCompet);
        $_SESSION['AfficheCompet'] = $AfficheCompet;
		$this->m_tpl->assign('AfficheCompet', $AfficheCompet);

		// Informations pour SelectionOuiNon ...
		$_SESSION['tableOuiNon'] = 'gickp_Competitions';
	
		$where = "Where Code_saison = '";
		$where .= $codeSaison;
		$where .= "' And Code = ";

		$_SESSION['whereOuiNon'] = $where;
		
		// Chargement des Saisons ...
		$sql  = "SELECT Code, Etat, Nat_debut, Nat_fin, Inter_debut, Inter_fin 
			FROM gickp_Saison 
			ORDER BY Code DESC ";	 
		
		$arraySaison = array();
		foreach ($myBdd->pdo->query($sql) as $row) {
			if ($row['Etat'] == 'A') {
                $saisonActive = $row['Code'];
			}
			if($lang == 'en') {
				$row['Nat_debut'] = utyDateUsToFr($row['Nat_debut']);
				$row['Nat_fin'] = utyDateUsToFr($row['Nat_fin']);
				$row['Inter_debut'] = utyDateUsToFr($row['Inter_debut']);
				$row['Inter_fin'] = utyDateUsToFr($row['Inter_fin']);
			}
			array_push($arraySaison, 
				array('Code' => $row['Code'], 'Etat' => $row['Etat'], 
				'Nat_debut' => $row['Nat_debut'], 
				'Nat_fin' => $row['Nat_fin'], 
				'Inter_debut' => $row['Inter_debut'], 
				'Inter_fin' => $row['Inter_fin'] 
			));
		}
		
		$this->m_tpl->assign('arraySaison', $arraySaison);
		$this->m_tpl->assign('sessionSaison', $codeSaison);
		$this->m_tpl->assign('saisonActive', $saisonActive);
		
		// Chargement des groupes competitions
        $arrayGroupCompet = $myBdd->GetGroups('admin');
		$this->m_tpl->assign('arrayGroupCompet', $arrayGroupCompet);
        
		// Chargement des Compétitions
		$label = $myBdd->getSections();
		$arrayCompet = array();
		$sqlFiltreCompetition = utyGetFiltreCompetition('c.');
		$sqlAfficheCompet = '';
		$arrayAfficheCompet = [];
		if ($AfficheCompet == 'N') {
			$sqlAfficheCompet = " AND c.Code LIKE 'N%' ";
		} elseif ($AfficheCompet == 'CF') {
			$sqlAfficheCompet = " AND c.Code LIKE 'CF%' ";
		} elseif ($AfficheCompet == 'M') {
			$sqlAfficheCompet = " AND c.Code_ref = 'M' ";
		} elseif ($AfficheCompet > 0) {
			$sqlAfficheCompet = " AND g.section = ? ";
			$arrayAfficheCompet = [$AfficheCompet];
		}
		$sql = "SELECT c.*, g.section, g.ordre, g.id, GROUP_CONCAT(rc.Matric) rcs 
			FROM gickp_Competitions_Groupes g, gickp_Competitions c  
			LEFT OUTER JOIN gickp_Rc rc ON 
				(c.Code_saison = rc.Code_saison AND c.Code = rc.Code_competition) 
			WHERE c.Code_saison = ?  
			$sqlFiltreCompetition 
			AND c.Code_niveau LIKE ? 
			AND c.Code_ref = g.Groupe 
			$sqlAfficheCompet 
			GROUP BY c.Code 
			ORDER BY c.Code_saison, g.section, g.ordre, 
				COALESCE(c.Code_ref, 'z'), c.Code_tour, c.GroupOrder, c.Code";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array_merge(
			[$codeSaison], 
			[utyGetSession('AfficheNiveau').'%'], 
			$arrayAfficheCompet
		));
		while ($row = $result->fetch()) { 
            $row['sectionLabel'] = $label[$row['section']];
			$bandeau = '/img/logo/B-'.$row['Code_ref'].'-'.$codeSaison.'.jpg';
			$logo = '/img/logo/L-'.$row['Code_ref'].'-'.$codeSaison.'.jpg';
			$sponsor = '/img/logo/S-'.$row['Code_ref'].'-'.$codeSaison.'.jpg';
			$StdOrSelected = 'Std';
			if ($codeCompet == $row['Code'])
			{
				if ($editCompet != '') {
                    $StdOrSelected = 'Selected';
                } else {
                    $StdOrSelected = 'Selected2';
                }
            }

			$Publication = 'O';
			if ($row['Publication'] != 'O') {
                $Publication = 'N';
            }

            $sql2 = "SELECT COUNT(m.Id) nbMatchs 
				FROM gickp_Matchs m, gickp_Journees j 
				WHERE j.Id = m.Id_journee 
				AND j.Code_competition = :Code_competition
				AND j.Code_saison = :Code_saison ";
			$result2 = $myBdd->pdo->prepare($sql2);
			$result2->execute(array(
				':Code_competition' => $row["Code"],
				':Code_saison' => $codeSaison
			));
			$nbMatchs = $result2->fetchColumn();
			if ($row['rcs'] != '') {
				$rcs = 1;
			} else {
				$rcs = 0;
			}

			array_push($arrayCompet, array( 'Code' => $row["Code"], 'Code_niveau' => $row["Code_niveau"], 
				'Libelle' => $row["Libelle"], 'Soustitre' => $row["Soustitre"], 'Soustitre2' => $row["Soustitre2"],
				'Code_ref' => $row["Code_ref"], 'GroupOrder' => $row["GroupOrder"], 'codeTypeClt' => $row["Code_typeclt"], 
				'StdOrSelected' => $StdOrSelected, 'Web' => $row["Web"], 'BandeauLink' => $bandeau, 'LogoLink' => $logo, 
				'SponsorLink' => $sponsor, 'ToutGroup' => $row["ToutGroup"], 'TouteSaisons' => $row["TouteSaisons"],
				'En_actif' => $row['En_actif'], 'Titre_actif' => $row['Titre_actif'], 'Bandeau_actif' => $row['Bandeau_actif'], 
				'Logo_actif' => $row['Logo_actif'], 'Sponsor_actif' => $row['Sponsor_actif'], 
				'Kpi_ffck_actif' => $row['Kpi_ffck_actif'], 'Age_min' => $row["Age_min"], 'Age_max' => $row["Age_max"], 
				'Sexe' => $row["Sexe"], 'Points' => $row["Points"], 'goalaverage' => $row['goalaverage'], 'Statut' => $row['Statut'],
				'Code_tour' => $row["Code_tour"], 'Nb_equipes' => $row["Nb_equipes"], 'Verrou' => $row["Verrou"], 
				'Qualifies' => $row["Qualifies"], 'Elimines' => $row["Elimines"],
				'Publication' => $Publication, 'commentairesCompet' => $row["commentairesCompet"], 'nbMatchs' => $nbMatchs,
				'section' => $row['section'], 'sectionLabel' => $row['sectionLabel'], 'rcs' => $rcs));
		}
		$this->m_tpl->assign('arrayCompet', $arrayCompet);
		$this->m_tpl->assign('sectionLabels', $label);

        $arrayTypeClt = array();
        if(utyGetSession('lang') == 'en') {
            array_push($arrayTypeClt, array('CHPT', 'CHPT - Round-trip games (Championship)', ''));
            array_push($arrayTypeClt, array('CP', 'CP - Playoff games (Cup, Tournament...)', ''));
        } else {
            array_push($arrayTypeClt, array('CHPT', 'CHPT - Matchs aller-retour (Championnat)', ''));
            array_push($arrayTypeClt, array('CP', 'CP - Matchs à élimination (Coupe,Tournoi...)', ''));
        }

		$this->m_tpl->assign('arrayTypeClt', $arrayTypeClt);

		// Chargement des Codes Compétitions existants
		$arrayCompetExist = array();
		$sql  = "SELECT Code, Code_niveau, Libelle, Code_ref 
			FROM gickp_Competitions 
			GROUP BY Code, Libelle 
			ORDER BY Code_ref, Code ";	 
    	foreach ($myBdd->pdo->query($sql) as $row) {
			array_push($arrayCompetExist, array( 
				'Code' => $row["Code"], 'Libelle' => $row["Libelle"] 
			));
		}
		$this->m_tpl->assign('arrayCompetExist', $arrayCompetExist);

		$this->m_tpl->assign('codeCompet', $codeCompet);
		if(!isset($_SESSION['niveauCompet'])) $_SESSION['niveauCompet'] = '';
		if(!isset($_SESSION['labelCompet'])) $_SESSION['labelCompet'] = '';
		if(!isset($_SESSION['soustitre'])) $_SESSION['soustitre'] = '';
		if(!isset($_SESSION['soustitre2'])) $_SESSION['soustitre2'] = '';
		if(!isset($_SESSION['web'])) $_SESSION['web'] = '';
		if(!isset($_SESSION['bandeauLink'])) $_SESSION['bandeauLink'] = '';
		if(!isset($_SESSION['logoLink'])) $_SESSION['logoLink'] = '';
		if(!isset($_SESSION['sponsorLink'])) $_SESSION['sponsorLink'] = '';
		if(!isset($_SESSION['toutGroup'])) $_SESSION['toutGroup'] = '';
		if(!isset($_SESSION['touteSaisons'])) $_SESSION['touteSaisons'] = '';
		if(!isset($_SESSION['checken'])) $_SESSION['checken'] = 'O';
		if(!isset($_SESSION['checktitre'])) $_SESSION['checktitre'] = 'O';
		if(!isset($_SESSION['checkbandeau'])) $_SESSION['checkbandeau'] = 'O';
		if(!isset($_SESSION['checklogo'])) $_SESSION['checklogo'] = 'O';
		if(!isset($_SESSION['checksponsor'])) $_SESSION['checksponsor'] = 'O';
		if(!isset($_SESSION['checkkpiffck'])) $_SESSION['checkkpiffck'] = 'O';
		if(!isset($_SESSION['codeRef'])) $_SESSION['codeRef'] = 'AUTRES';
		if(!isset($_SESSION['groupOrder'])) $_SESSION['groupOrder'] = '';
		if(!isset($_SESSION['codeTypeClt'])) $_SESSION['codeTypeClt'] = '';
		if(!isset($_SESSION['etape'])) $_SESSION['etape'] = '';
		if(!isset($_SESSION['qualifies'])) $_SESSION['qualifies'] = '';
		if(!isset($_SESSION['elimines'])) $_SESSION['elimines'] = '';
		if(!isset($_SESSION['points'])) $_SESSION['points'] = '4-2-1-0';
		if(!isset($_SESSION['goalaverage'])) $_SESSION['goalaverage'] = 'gen';
		if(!isset($_SESSION['statut'])) $_SESSION['statut'] = 'ATT';
		if(!isset($_SESSION['commentairesCompet'])) $_SESSION['commentairesCompet'] = '';
		if(!isset($_SESSION['publierCompet'])) $_SESSION['publierCompet'] = '';
		$this->m_tpl->assign('niveauCompet', $_SESSION['niveauCompet']);
		$this->m_tpl->assign('labelCompet', $_SESSION['labelCompet']);
		$this->m_tpl->assign('soustitre', $_SESSION['soustitre']);
		$this->m_tpl->assign('soustitre2', $_SESSION['soustitre2']);
		$this->m_tpl->assign('web', $_SESSION['web']);
		$this->m_tpl->assign('bandeauLink', $_SESSION['bandeauLink']);
		$this->m_tpl->assign('logoLink', $_SESSION['logoLink']);
		$this->m_tpl->assign('sponsorLink', $_SESSION['sponsorLink']);
		$this->m_tpl->assign('toutGroup', $_SESSION['toutGroup']);
		$this->m_tpl->assign('touteSaisons', $_SESSION['touteSaisons']);
		$this->m_tpl->assign('checken', $_SESSION['checken']);
		$this->m_tpl->assign('checktitre', $_SESSION['checktitre']);
		$this->m_tpl->assign('checkbandeau', $_SESSION['checkbandeau']);
		$this->m_tpl->assign('checklogo', $_SESSION['checklogo']);
		$this->m_tpl->assign('checksponsor', $_SESSION['checksponsor']);
		$this->m_tpl->assign('checkkpiffck', $_SESSION['checkkpiffck']);
		$this->m_tpl->assign('codeRef', $_SESSION['codeRef']);
		$this->m_tpl->assign('groupOrder', $_SESSION['groupOrder']);
		$this->m_tpl->assign('codeTypeClt', $_SESSION['codeTypeClt']);
		$this->m_tpl->assign('etape', $_SESSION['etape']);
		$this->m_tpl->assign('qualifies', $_SESSION['qualifies']);
		$this->m_tpl->assign('elimines', $_SESSION['elimines']);
		$this->m_tpl->assign('points', $_SESSION['points']);
		$this->m_tpl->assign('goalaverage', $_SESSION['goalaverage']);
		$this->m_tpl->assign('statut', $_SESSION['statut']);
		$this->m_tpl->assign('commentairesCompet', $_SESSION['commentairesCompet']);
		$this->m_tpl->assign('publierCompet', $_SESSION['publierCompet']);
		
		//Logo uploaded
		if($codeCompet != -1)
		{
            if (isset($bandeau) && file_exists($bandeau)) {
                $this->m_tpl->assign('bandeau', $bandeau);
            }
            if (isset($logo) && file_exists($logo)) {
                $this->m_tpl->assign('logo', $logo);
            }
            if (isset($sponsor) && file_exists($sponsor)) {
                $this->m_tpl->assign('sponsor', $sponsor);
            }
		}
	}
	
	function Add()
	{
        $myBdd = $this->myBdd;
		$saison = $myBdd->GetActiveSaison();
		$codeCompet = utyGetPost('codeCompet');
		$bandeauLink = utyGetPost('bandeauLink');
		if($bandeauLink2 = captureImg($bandeauLink, 'B', $codeCompet, $saison)) {
            $bandeauLink = $bandeauLink2;
        }		
        $logoLink = utyGetPost('logoLink');
		if($logoLink2 = captureImg($logoLink, 'L', $codeCompet, $saison)) {
            $logoLink = $logoLink2;
        }
		$sponsorLink = utyGetPost('sponsorLink');
		if($sponsorLink2 = captureImg($sponsorLink, 'S', $codeCompet, $saison)) {
            $sponsorLink = $sponsorLink2;
        }		
		$codeRef = utyGetPost('codeRef');
		if ($codeRef == '') {
            $codeRef = 'AUTRES';
        }

		$TitreJournee = utyGetPost('TitreJournee');
		$Date_debut = utyDateFrToUs(utyGetPost('Date_debut'));
		$Date_fin = utyDateFrToUs(utyGetPost('Date_fin'));
		$Lieu = utyGetPost('Lieu');
		$Departement = utyGetPost('Departement');
		$publierJournee = utyGetPost('publierJournee');
        

		if (strlen($codeCompet) > 0) {
			$sql = "INSERT INTO gickp_Competitions 
				(Code, Code_saison, Code_niveau, Libelle, Soustitre, 
				Soustitre2, Web, BandeauLink, LogoLink, SponsorLink, ToutGroup, TouteSaisons, 
				En_actif, Titre_actif, Bandeau_actif, Logo_actif, 
				Sponsor_actif, Kpi_ffck_actif, Code_ref, GroupOrder, 
				Code_typeclt, Code_tour, Qualifies, Elimines, 
				Points, goalaverage, Statut, Publication) 
				VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
			$result = $myBdd->pdo->prepare($sql);
			$result->execute(array(
				$codeCompet, $saison, utyGetPost('niveauCompet'), utyGetPost('labelCompet'), utyGetPost('soustitre'), 
				utyGetPost('soustitre2'), utyGetPost('web'), $bandeauLink, $logoLink, $sponsorLink, '', '', 
				utyGetPost('checken'), utyGetPost('checktitre'), utyGetPost('checkbandeau'), utyGetPost('checklogo'), 
				utyGetPost('checksponsor'), utyGetPost('checkkpiffck'), $codeRef, utyGetPost('groupOrder'),
				utyGetPost('codeTypeClt'), utyGetPost('etape'), utyGetPost('qualifies'), utyGetPost('elimines'), 
				utyGetPost('points'), utyGetPost('goalaverage'), utyGetPost('statut'), utyGetPost('publierCompet')
			));

			if ($Date_debut != '') {
				$nextIdJournee = $myBdd->GetNextIdJournee();
				if($TitreJournee == '') {
					$TitreJournee = utyGetPost('labelCompet');
				}
				$sql = "INSERT INTO gickp_Journees (Id, Code_competition, code_saison, Phase, Niveau, Date_debut, 
					Date_fin, Nom, Libelle, Lieu, Plan_eau, Departement, Responsable_insc, Responsable_R1, 
					Organisateur, Delegue, Publication) 
					VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
				$result = $myBdd->pdo->prepare($sql);
				$result->execute(array(
					$nextIdJournee, $codeCompet, $saison, '', 1, $Date_debut, 
					$Date_fin, $TitreJournee, '', $Lieu, '', $Departement, '', '', 
					'', '', $publierJournee
				));
			}
		}
		
		$this->RazCompet();
		
		$myBdd->utyJournal('Ajout Compet', $saison, $codeCompet);
	}
	
	function Remove()
	{
		$myBdd = $this->myBdd;
		$saison = $myBdd->GetActiveSaison();

		$ParamCmd = utyGetPost('ParamCmd', '');
			
		$arrayParam = explode(',', $ParamCmd);		
		if (count($arrayParam) == 0) {
			return; // Rien à Detruire ...
		} else {
			$listParams = "";
			for ($i=0; $i<count($arrayParam); $i++) {
				if ($i > 0)
					$listParams .= ",";
				$listParams .= "'".$arrayParam[$i]."'";
			}
		}
			
		//Contrôle suppression possible
		$in  = str_repeat('?,', count($arrayParam) - 1) . '?';
		$sql = "SELECT Id 
			FROM gickp_Journees 
			WHERE Code_competition IN ($in) 
			AND Code_saison = ? ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array_merge($arrayParam, [$saison]));
		if ($result->rowCount() > 0) {
			die ("Il reste des journées dans cette compétition ! Suppression impossible (<a href='javascript:history.back()'>Retour</a>)");
		}

		// Suppression	
		$sql = "DELETE FROM gickp_Competitions 
			WHERE Code IN ($in) 
			AND Code_saison = ? ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array_merge($arrayParam, [$saison]));

		$myBdd->utyJournal('Suppression Compet', $myBdd->GetActiveSaison(), $arrayParam[$i]);

	}
	
	function RazCompet()
	{
			$_SESSION['editCompet'] = '';
			$_SESSION['niveauCompet'] = '';
			$_SESSION['labelCompet'] = '';
			$_SESSION['soustitre'] = '';
			$_SESSION['soustitre2'] = '';
			$_SESSION['web'] = '';
			$_SESSION['bandeauLink'] = '';
			$_SESSION['logoLink'] = '';
			$_SESSION['sponsorLink'] = '';
			$_SESSION['toutGroup'] = '';
			$_SESSION['touteSaisons'] = '';
			$_SESSION['checken'] = 'O';
			$_SESSION['checktitre'] = 'O';
			$_SESSION['checkbandeau'] = 'O';
			$_SESSION['checklogo'] = 'O';
			$_SESSION['checksponsor'] = 'O';
			$_SESSION['checkkpiffck'] = 'O';
			$_SESSION['codeRef'] = '';
			$_SESSION['groupOrder'] = '';
			$_SESSION['codeTypeClt'] = '';
			$_SESSION['etape'] = '';
			$_SESSION['qualifies'] = '';
			$_SESSION['elimines'] = '';
			$_SESSION['points'] = '4-2-1-0';
			$_SESSION['goalaverage'] = 'gen';
			$_SESSION['statut'] = 'ATT';
			$_SESSION['commentairesCompet'] = '';
			$_SESSION['publierCompet'] = '';
	}

	function ParamCompet()
	{
		$myBdd = $this->myBdd;
		$saison = $myBdd->GetActiveSaison();

		$codeCompet = utyGetPost('ParamCmd', -1);
		$_SESSION['codeCompet'] = $codeCompet;

		$sql  = "SELECT Code_niveau, Libelle, Soustitre, Soustitre2, Web, BandeauLink, LogoLink, 
			SponsorLink, ToutGroup, TouteSaisons, En_actif, Titre_actif, Bandeau_actif, Logo_actif, 
			Sponsor_actif, Kpi_ffck_actif, Code_ref, GroupOrder, Code_typeclt, Code_tour, Qualifies, 
			Elimines, Points, goalaverage, Statut, commentairesCompet, Publication 
			FROM gickp_Competitions 
			WHERE Code_saison = ? 
			AND Code = ? ";
			$result = $myBdd->pdo->prepare($sql);
			$result->execute(array($saison, $codeCompet));
	
		if ($row = $result->fetch()) {
			$_SESSION['editCompet'] = 1;
			$_SESSION['codeCompet'] = $codeCompet;
			$_SESSION['niveauCompet'] = $row['Code_niveau'];
			$_SESSION['labelCompet'] = $row['Libelle'];
			$_SESSION['soustitre'] = $row['Soustitre'];
			$_SESSION['soustitre2'] = $row['Soustitre2'];
			$_SESSION['web'] = $row['Web'];
			$_SESSION['bandeauLink'] = $row['BandeauLink'];
			$_SESSION['logoLink'] = $row['LogoLink'];
			$_SESSION['sponsorLink'] = $row['SponsorLink'];
			$_SESSION['toutGroup'] = '';
			$_SESSION['checken'] = $row['En_actif'];
			$_SESSION['touteSaisons'] = '';
			$_SESSION['checktitre'] = $row['Titre_actif'];
			$_SESSION['checkbandeau'] = $row['Bandeau_actif'];
			$_SESSION['checklogo'] = $row['Logo_actif'];
			$_SESSION['checksponsor'] = $row['Sponsor_actif'];
			$_SESSION['checkkpiffck'] = $row['Kpi_ffck_actif'];
			$_SESSION['codeRef'] = $row['Code_ref'];
			$_SESSION['groupOrder'] = $row['GroupOrder'];
			$_SESSION['codeTypeClt'] = $row['Code_typeclt'];
			$_SESSION['etape'] = $row['Code_tour'];
			$_SESSION['qualifies'] = $row['Qualifies'];
			$_SESSION['elimines'] = $row['Elimines'];
			$_SESSION['points'] = $row['Points'];
			$_SESSION['goalaverage'] = $row['goalaverage'];
			$_SESSION['statut'] = $row['Statut'];
			$_SESSION['commentairesCompet'] = $row['commentairesCompet'];
			$_SESSION['publierCompet'] = $row['Publication'];
		}
	}

	function UpdateCompet()
	{
		$myBdd = $this->myBdd;
		$saison = $myBdd->GetActiveSaison();
		$codeCompet = utyGetPost('codeCompet');

		$bandeauLink = utyGetPost('bandeauLink');
		if($bandeauLink2 = captureImg($bandeauLink, 'B', $codeCompet, $saison)) {
            $bandeauLink = $bandeauLink2;
        }		
        $logoLink = utyGetPost('logoLink');
		if($logoLink2 = captureImg($logoLink, 'L', $codeCompet, $saison)) {
            $logoLink = $logoLink2;
        }
		$sponsorLink = utyGetPost('sponsorLink');
		if($sponsorLink2 = captureImg($sponsorLink, 'S', $codeCompet, $saison)) {
            $sponsorLink = $sponsorLink2;
        }		
		$codeRef = utyGetPost('codeRef');
		if ($codeRef == '') {
            $codeRef = 'AUTRES';
        }
		
		$sql  = "UPDATE gickp_Competitions 
			SET Code_niveau = ?, Libelle = ?, Soustitre = ?, 
			Soustitre2 = ?, Web = ?, BandeauLink = ?, LogoLink = ?, SponsorLink = ?, ToutGroup = ?, TouteSaisons = ?, 
			En_actif = ?, Titre_actif = ?, Bandeau_actif = ?, Logo_actif = ?, 
			Sponsor_actif = ?, Kpi_ffck_actif = ?, Code_ref = ?, GroupOrder = ?, 
			Code_typeclt = ?, Code_tour = ?, Qualifies = ?, Elimines = ?, 
			Points = ?, goalaverage = ?, Statut = ?, Publication = ?, commentairesCompet = ?
			WHERE Code = ? 
			AND Code_saison = ? ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array(
			utyGetPost('niveauCompet'), utyGetPost('labelCompet'), utyGetPost('soustitre'), 
			utyGetPost('soustitre2'), utyGetPost('web'), $bandeauLink, $logoLink, $sponsorLink, '', '', 
			utyGetPost('checken'), utyGetPost('checktitre'), utyGetPost('checkbandeau'), utyGetPost('checklogo'), 
			utyGetPost('checksponsor'), utyGetPost('checkkpiffck'), $codeRef, utyGetPost('groupOrder'),
			utyGetPost('codeTypeClt'), utyGetPost('etape'), utyGetPost('qualifies'), utyGetPost('elimines'), 
			utyGetPost('points'), utyGetPost('goalaverage'), utyGetPost('statut'), utyGetPost('publierCompet'), utyGetPost('commentairesCompet'),
			$codeCompet, $saison 
		));
        
        
		$this->RazCompet();
		$myBdd->utyJournal('Modif Competition', $saison, $codeCompet);
	}

	function Verrou()
	{
		$verrouCompet = utyGetPost('verrouCompet');
		$Verrou = utyGetPost('Verrou');
		($Verrou == 'O') ? $Verrou = '' : $Verrou = 'O';

		if (strlen($verrouCompet) > 0)
		{
			$myBdd = $this->myBdd;
			$saison = $myBdd->GetActiveSaison();

			$sql  = "UPDATE gickp_Competitions 
				SET Verrou = ?
				WHERE Code_saison = ?
				AND Code = ? ";
			$result = $myBdd->pdo->prepare($sql);
			$result->execute(array($Verrou, $saison, $verrouCompet));
		}
		
		$myBdd->utyJournal('Verrou Compet', $myBdd->GetActiveSaison(), $verrouCompet);
	}

	function SetSessionSaison()
	{
		$codeSaison = utyGetPost('ParamCmd', '');
		if (strlen($codeSaison) == 0)
			return;
		
		$_SESSION['Saison'] = $codeSaison;
	}

	function SetActiveSaison()
	{
		$codeSaison = utyGetPost('ParamCmd', '');
		if (strlen($codeSaison) == 0)
			return;

		$myBdd = $this->myBdd;

		$sql  = "UPDATE gickp_Saison 
			SET Etat = 'I' 
			WHERE Etat = 'A' ";
		$myBdd->pdo->exec($sql);

		$sql = "UPDATE gickp_Saison 
			SET Etat = 'A' 
			WHERE Code = ? ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array($codeSaison));
		
		$myBdd->utyJournal('Change Saison Active', $codeSaison);
	}

	function AddSaison()
	{
		$newSaison = utyGetPost('newSaison', '');
		$newSaisonDN = utyDateFrToUs(utyGetPost('newSaisonDN', ''));
		$newSaisonFN = utyDateFrToUs(utyGetPost('newSaisonFN', ''));
		$newSaisonDI = utyDateFrToUs(utyGetPost('newSaisonDI', ''));
		$newSaisonFI = utyDateFrToUs(utyGetPost('newSaisonFI', ''));

		if (strlen($newSaison) == 0)
			return;

		$myBdd = $this->myBdd;

		$sql  = "INSERT INTO gickp_Saison (Code ,Etat ,Nat_debut ,Nat_fin ,Inter_debut ,Inter_fin) 
			VALUES (?, ?, ?, ?, ?, ?) ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array(
			$newSaison, 'I', $newSaisonDN, $newSaisonFN, $newSaisonDI, $newSaisonFI
		));
		
		$myBdd->utyJournal('Ajout Saison', $newSaison);
	}
	
	function PubliCompet()
	{
		$myBdd = $this->myBdd;

		$idCompet = utyGetPost('ParamCmd', 0);
		$saison = $myBdd->GetActiveSaison();
		(utyGetPost('Pub', '') != 'O') ? $changePub = 'O' : $changePub = 'N';
		
		$sql = "UPDATE gickp_Competitions 
			SET Publication = :changePub 
			WHERE Code = :idCompet 
			AND Code_saison = :saison ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array(
			':changePub' => $changePub,
			':idCompet' => $idCompet,
			':saison' => $saison
		));
		
		$myBdd->utyJournal('Publication competition', $saison, $idCompet, null, null, null, $changePub);
	}

	function UploadLogo()
	{
		$myBdd = $this->myBdd;
		if(empty($_FILES['logo1']['tmp_name']))	{
			$texte = " Pas de fichier reçu - erreur ".$_FILES['logo1']['error'];
		}
		$codeSaison = $myBdd->GetActiveSaison();
		$codeCompet = utyGetSession('codeCompet');
		$dossier = '/home/users2-new/p/poloweb/www/agil/img/logo/';
		$fichier = $codeSaison.'-'.$codeCompet.'.jpg';
		$taille_maxi = 500000;
		$taille = filesize($_FILES['logo1']['tmp_name']);
		$erreur = '';
		$extensions = array('.png', '.gif', '.jpg', '.jpeg');
		$extension = strrchr($_FILES['logo1']['name'], '.'); 
		//Début des vérifications de sécurité...
		if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
		{
			 $erreur .= 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
		}
		if($taille>$taille_maxi) {
			$erreur .= 'Le fichier est trop gros...';
		}
		if(!isset($erreur)) { //S'il n'y a pas d'erreur, on upload
			//Si la fonction renvoie TRUE, c'est que ça a fonctionné...
			if(move_uploaded_file($_FILES['logo1']['tmp_name'], $dossier . $fichier)) {
				$erreur .= 'Upload effectué avec succès !';
				$logo = "../img/logo/".$fichier;
				$sql = "UPDATE gickp_Competitions 
					SET LogoLink = ? 
					WHERE Code = ? 
					AND Code_saison = ? ";
				$result = $myBdd->pdo->prepare($sql);
				$result->execute(array($logo, $codeCompet, $codeSaison));
				$myBdd->utyJournal('Insertion Logo', $myBdd->GetActiveSaison(), $codeCompet, null, null, null, '');
			} else { //Sinon (la fonction renvoie FALSE).
				$erreur .= "Echec de l\'upload ! ".$texte;
			}
		} else {
			echo $erreur;
		}		
		return($erreur);
	}

	function DropLogo()
	{
		$myBdd = $this->myBdd;
		$codeSaison = $myBdd->GetActiveSaison();
		$codeCompet = utyGetSession('codeCompet');
		$dossier = '/home/users2-new/p/poloweb/www/agil/img/logo/';
		$fichier = $codeSaison.'-'.$codeCompet.'.jpg';
		$fichier2 = 'ex-'.$codeSaison.'-'.$codeCompet.'.jpg';
		rename($dossier.$fichier, $dossier.$fichier2);
		$myBdd->utyJournal('Suppression Logo', $myBdd->GetActiveSaison(), $codeCompet, null, null, null, '');
		return('Logo supprimé');
	}

	function FusionJoueurs()
	{
		$myBdd = $this->myBdd;
		$numFusionSource = utyGetPost('numFusionSource', 0);
		$numFusionCible = utyGetPost('numFusionCible', 0);
        // buts et cartons
		$sql  = "UPDATE gickp_Matchs_Detail 
			SET Competiteur = ? 
			WHERE Competiteur = ? ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array($numFusionCible, $numFusionSource));

        // compos matchs
		$sql  = "UPDATE gickp_Matchs_Joueurs 
			SET Matric = ? 
			WHERE Matric = ? ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array($numFusionCible, $numFusionSource));

        // feuilles de présence
		$sql  = "UPDATE gickp_Competitions_Equipes_Joueurs 
			SET Matric = ? 
			WHERE Matric = ? ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array($numFusionCible, $numFusionSource));

        // arbitre principal
		$sql  = "UPDATE gickp_Matchs 
			SET Matric_arbitre_principal = ? 
			WHERE Matric_arbitre_principal = ? ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array($numFusionCible, $numFusionSource));

        // arbitre secondaire
		$sql  = "UPDATE gickp_Matchs 
			SET Matric_arbitre_secondaire = ? 
			WHERE Matric_arbitre_secondaire = ? ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array($numFusionCible, $numFusionSource));
        
        // Secretaire
		$sql  = "UPDATE gickp_Matchs 
			SET Secretaire = REPLACE(Secretaire, :source, :cible) 
			WHERE Secretaire LIKE :source2 ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array(
			':cible' => '('.$numFusionCible.')', 
			':source' => '('.$numFusionSource.')',
			':source2' => '%('.$numFusionSource.')%'
		));
        
        // Chronometre
		$sql  = "UPDATE gickp_Matchs 
			SET Chronometre = REPLACE(Chronometre, :source, :cible) 
			WHERE Chronometre LIKE :source2 ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array(
			':cible' => '('.$numFusionCible.')', 
			':source' => '('.$numFusionSource.')',
			':source2' => '%('.$numFusionSource.')%'
		));
        
        // Timeshoot
		$sql  = "UPDATE gickp_Matchs 
			SET Timeshoot = REPLACE(Timeshoot, :source, :cible) 
			WHERE Timeshoot LIKE :source2 ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array(
			':cible' => '('.$numFusionCible.')', 
			':source' => '('.$numFusionSource.')',
			':source2' => '%('.$numFusionSource.')%'
		));
        
        // Ligne1
		$sql  = "UPDATE gickp_Matchs 
			SET Ligne1 = REPLACE(Ligne1, :source, :cible) 
			WHERE Ligne1 LIKE :source2 ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array(
			':cible' => '('.$numFusionCible.')', 
			':source' => '('.$numFusionSource.')',
			':source2' => '%('.$numFusionSource.')%'
		));
        
        // Ligne2
		$sql  = "UPDATE gickp_Matchs 
			SET Ligne2 = REPLACE(Ligne2, :source, :cible) 
			WHERE Ligne2 LIKE :source2 ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array(
			':cible' => '('.$numFusionCible.')', 
			':source' => '('.$numFusionSource.')',
			':source2' => '%('.$numFusionSource.')%'
		));
        
        // suppression
		$sql  = "DELETE FROM gickp_Liste_Coureur 
			WHERE Matric = ?; ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array($numFusionSource));

		$myBdd->utyJournal('Fusion Joueurs', $myBdd->GetActiveSaison(), utyGetSession('codeCompet'), null, null, null, $numFusionSource.' => '.$numFusionCible);

        return('Joueurs fusionnés');
	}

	function FusionEquipes() 
	{
        $myBdd = $this->myBdd;
		$numFusionEquipeSource = utyGetPost('numFusionEquipeSource', 0);
		$numFusionEquipeCible = utyGetPost('numFusionEquipeCible', 0);
        $FusionEquipeSource= utyGetPost('FusionEquipeSource', '');
        $FusionEquipeCible = utyGetPost('FusionEquipeCible', '');
		if ($numFusionEquipeSource == 0 or $numFusionEquipeCible == 0 or $FusionEquipeSource == '' or $FusionEquipeCible == '') {
            return;
        }
		$sql = "UPDATE gickp_Competitions_Equipes 
			SET `Numero` = ?, 
			Libelle = ?  
			WHERE Numero = ?; ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array($numFusionEquipeCible, $FusionEquipeCible, $numFusionEquipeSource));
		
		
		$sql = "DELETE FROM gickp_Equipe 
			WHERE Numero = ?; ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array($numFusionEquipeSource));

		$myBdd->utyJournal('Fusion Equipes', $myBdd->GetActiveSaison(), utyGetSession('codeCompet'), null, null, null, $FusionEquipeSource.' => '.$FusionEquipeCible);
		return;
    }

	function DeplaceEquipe() 
	{
        $myBdd = $this->myBdd;
		$numDeplaceEquipeSource = utyGetPost('numDeplaceEquipeSource', 0);
		$numDeplaceEquipeCible = utyGetPost('numDeplaceEquipeCible', 0);
		if ($numDeplaceEquipeSource === 0) {
            return "Equipe source vide : $numDeplaceEquipeSource";
        } elseif ($numDeplaceEquipeCible === 0) {
            return "Club cible vide : $numDeplaceEquipeCible";
        }
		$sql  = "UPDATE gickp_Competitions_Equipes 
			SET Code_club = ?  
			WHERE Numero = ?; ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array($numDeplaceEquipeCible, $numDeplaceEquipeSource));
				
		$sql  = "UPDATE gickp_Equipe 
			SET Code_club = ?  
			WHERE Numero = ?; ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array($numDeplaceEquipeCible, $numDeplaceEquipeSource));
		
		$myBdd->utyJournal('Déplacement Equipe', $myBdd->GetActiveSaison(), utyGetSession('codeCompet'), null, null, null, $numDeplaceEquipeSource.' => '.$numDeplaceEquipeCible);

		return $numDeplaceEquipeSource . ' => ' . $numDeplaceEquipeCible;
    }
    
	function RenomEquipe()
	{
		$myBdd = $this->myBdd;
		$numRenomSource = utyGetPost('numRenomSource', 0);
		$RenomSource = utyGetPost('RenomSource', 0);
		$RenomCible = utyGetPost('RenomCible', 0);

		$sql  = "UPDATE gickp_Equipe 
			SET Libelle = ?  
			WHERE Numero = ?; ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array($RenomCible, $numRenomSource));
		
		$sql  = "UPDATE gickp_Competitions_Equipes 
			SET Libelle = ?  
			WHERE Numero = ?; ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array($RenomCible, $numRenomSource));

		$myBdd->utyJournal('Rename Equipe', $myBdd->GetActiveSaison(), utyGetSession('codeCompet'), null, null, null, $RenomSource.' => '.$RenomCible);
		return('Joueurs fusionnés');
	}

	function ChangeAuthSaison()
	{
		$AuthSaison = utyGetSession('AuthSaison');
		if($AuthSaison == 'O')
			$AuthSaison = '';
		else
			$AuthSaison = 'O';
		$_SESSION['AuthSaison'] = $AuthSaison;
	}

	function __construct()
	{			
        MyPageSecure::MyPageSecure(10);
		
        if ($_SESSION['Profile'] == 9) {
            header("Location: SelectFeuille.php");
			exit;
		}
		
		$this->myBdd = new MyBdd();
        
		$alertMessage = '';

		$Cmd = utyGetPost('Cmd', '');

		if (strlen($Cmd) > 0)
		{
			if ($Cmd == 'Add')
				($_SESSION['Profile'] <= 3) ? $this->Add() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'UploadLogo')
				($_SESSION['Profile'] <= 3) ? $alertMessage = $this->UploadLogo() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'DropLogo')
				($_SESSION['Profile'] <= 3) ? $alertMessage = $this->DropLogo() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'Remove')
				($_SESSION['Profile'] <= 2) ? $this->Remove() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'ParamCompet')
				($_SESSION['Profile'] <= 3) ? $this->ParamCompet() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'UpdateCompet')
				($_SESSION['Profile'] <= 3) ? $this->UpdateCompet() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'RazCompet')
				($_SESSION['Profile'] <= 3) ? $this->RazCompet() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'PubliCompet')
				($_SESSION['Profile'] <= 4) ? $this->PubliCompet() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'Verrou')
				($_SESSION['Profile'] <= 3) ? $this->Verrou() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'SessionSaison')
				($_SESSION['Profile'] <= 10) ? $this->SetSessionSaison() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'ActiveSaison')
				($_SESSION['Profile'] <= 2) ? $this->SetActiveSaison() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'AddSaison')
				($_SESSION['Profile'] <= 2) ? $this->AddSaison() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'FusionJoueurs')
				($_SESSION['Profile'] == 1) ? $this->FusionJoueurs() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'RenomEquipe')
				($_SESSION['Profile'] == 1) ? $this->RenomEquipe() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'FusionEquipes')
				($_SESSION['Profile'] == 1) ? $this->FusionEquipes() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'DeplaceEquipe')
				($_SESSION['Profile'] == 1) ? $alertMessage = $this->DeplaceEquipe() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'ChangeAuthSaison')
				($_SESSION['Profile'] <= 2) ? $this->ChangeAuthSaison() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($alertMessage == '')
			{
				header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);	
				exit;
			}
		}

		$this->SetTemplate("Gestion_des_competitions", "Competitions", false);
		$this->Load();
		$this->m_tpl->assign('AlertMessage', $alertMessage);
		$this->DisplayTemplate('GestionCompetition');
	}
}		  	

$page = new GestionCompetition();
