<?php

include_once('../commun/MyPage.php');
include_once('../commun/MyBdd.php');
include_once('../commun/MyTools.php');

// Gestion des Competitions
class GestionCompetition extends MyPageSecure	 
{	
	function Load()
	{
        // Langue
        $langue = parse_ini_file("../commun/MyLang.ini", true);
        if (utyGetSession('lang') == 'en') {
            $lang = $langue['en'];
        } else {
            $lang = $langue['fr'];
        }
        
		$codeSaison = utyGetSaison();
		
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
		
		$myBdd = new MyBdd();

		// Chargement des Saisons ...
		$sql  = "Select Code, Etat, Nat_debut, Nat_fin, Inter_debut, Inter_fin ";
		$sql .= "From gickp_Saison ";
		$sql .= "Order By Code DESC ";	 
		
		$result = $myBdd->Query($sql);
		$num_results = $myBdd->NumRows($result);
	
		$arraySaison = array();
		for ($i=0;$i<$num_results;$i++)
		{
			//$row = mysql_fetch_array($result);
			$row = $myBdd->FetchArray($result);
			if ($row['Etat'] == 'A') {
                $saisonActive = $row['Code'];
            }
            array_push($arraySaison, array('Code' => $row['Code'], 'Etat' => $row['Etat'], 
										'Nat_debut' => utyDateUsToFr($row['Nat_debut']), 'Nat_fin' => utyDateUsToFr($row['Nat_fin']), 
										'Inter_debut' => utyDateUsToFr($row['Inter_debut']), 'Inter_fin' => utyDateUsToFr($row['Inter_fin']) ));
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
		$sql  = "Select c.*, g.section, g.ordre, g.id ";
		$sql .= "From gickp_Competitions c, gickp_Competitions_Groupes g ";
		$sql .= "Where c.Code_saison = '";
		$sql .= $codeSaison;
		$sql .= "' ";
		$sql .= utyGetFiltreCompetition('c.');
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
		$sql .= " Order By c.Code_saison, g.section, g.ordre, g.id, COALESCE(c.Code_ref, 'z'), c.Code_tour, c.GroupOrder, c.Code";	 
        $result = $myBdd->Query($sql);
		while($row = $myBdd->FetchArray($result)) {
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

            $sql2  = "Select Count(m.Id) nbMatchs From gickp_Matchs m, gickp_Journees j ";
			$sql2 .= "Where j.Id = m.Id_journee ";
			$sql2 .= "And j.Code_competition = '".$row["Code"]."' ";
			$sql2 .= "And j.Code_saison = ".$codeSaison." ";
			$result2 = $myBdd->Query($sql2);
			$row2 = $myBdd->FetchRow($result2);
			$nbMatchs = $row2[0];
			
			array_push($arrayCompet, array( 'Code' => $row["Code"], 'Code_niveau' => $row["Code_niveau"], 'Libelle' => $row["Libelle"], 'Soustitre' => $row["Soustitre"], 'Soustitre2' => $row["Soustitre2"],
											'Code_ref' => $row["Code_ref"], 'GroupOrder' => $row["GroupOrder"], 'codeTypeClt' => $row["Code_typeclt"], 'StdOrSelected' => $StdOrSelected, 'Web' => $row["Web"], 
											'BandeauLink' => $bandeau, 'LogoLink' => $logo, 'SponsorLink' => $sponsor, 'ToutGroup' => $row["ToutGroup"], 'TouteSaisons' => $row["TouteSaisons"],
											'En_actif' => $row['En_actif'], 'Titre_actif' => $row['Titre_actif'], 'Bandeau_actif' => $row['Bandeau_actif'], 'Logo_actif' => $row['Logo_actif'], 'Sponsor_actif' => $row['Sponsor_actif'], 'Kpi_ffck_actif' => $row['Kpi_ffck_actif'], 
											'Age_min' => $row["Age_min"], 'Age_max' => $row["Age_max"], 'Sexe' => $row["Sexe"], 'Points' => $row["Points"], 'Statut' => $row['Statut'],
											'Code_tour' => $row["Code_tour"], 'Nb_equipes' => $row["Nb_equipes"], 'Verrou' => $row["Verrou"], 'Qualifies' => $row["Qualifies"], 'Elimines' => $row["Elimines"],
											'Publication' => $Publication, 'commentairesCompet' => $row["commentairesCompet"], 'nbMatchs' => $nbMatchs,
                                            'section' => $row['section'], 'sectionLabel' => $row['sectionLabel']));
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
		$sql  = "Select Code, Code_niveau, Libelle, Code_ref ";
		$sql .= "From gickp_Competitions ";
		$sql .= "Group by Code, Libelle Order By Code_ref, Code ";	 
		$result = $myBdd->Query($sql);
		$num_results = $myBdd->NumRows($result);
		
		for ($i=0;$i<$num_results;$i++)
		{
			$row = $myBdd->FetchArray($result);

			array_push($arrayCompetExist, array( 'Code' => $row["Code"], 'Libelle' => $row["Libelle"] ));
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
		if(!isset($_SESSION['en_actif'])) $_SESSION['en_actif'] = '';
		if(!isset($_SESSION['titre_actif'])) $_SESSION['titre_actif'] = 'O';
		if(!isset($_SESSION['bandeau_actif'])) $_SESSION['bandeau_actif'] = '';
		if(!isset($_SESSION['logo_actif'])) $_SESSION['logo_actif'] = '';
		if(!isset($_SESSION['sponsor_actif'])) $_SESSION['sponsor_actif'] = '';
		if(!isset($_SESSION['kpi_ffck_actif'])) $_SESSION['kpi_ffck_actif'] = 'O';
		if(!isset($_SESSION['codeRef'])) $_SESSION['codeRef'] = 'AUTRES';
		if(!isset($_SESSION['groupOrder'])) $_SESSION['groupOrder'] = '';
		if(!isset($_SESSION['codeTypeClt'])) $_SESSION['codeTypeClt'] = '';
		if(!isset($_SESSION['etape'])) $_SESSION['etape'] = '';
		if(!isset($_SESSION['qualifies'])) $_SESSION['qualifies'] = '';
		if(!isset($_SESSION['elimines'])) $_SESSION['elimines'] = '';
		if(!isset($_SESSION['points'])) $_SESSION['points'] = '4-2-1-0';
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
		$this->m_tpl->assign('en_actif', $_SESSION['en_actif']);
		$this->m_tpl->assign('titre_actif', $_SESSION['titre_actif']);
		$this->m_tpl->assign('bandeau_actif', $_SESSION['bandeau_actif']);
		$this->m_tpl->assign('logo_actif', $_SESSION['logo_actif']);
		$this->m_tpl->assign('sponsor_actif', $_SESSION['sponsor_actif']);
		$this->m_tpl->assign('kpi_ffck_actif', $_SESSION['kpi_ffck_actif']);
		$this->m_tpl->assign('codeRef', $_SESSION['codeRef']);
		$this->m_tpl->assign('groupOrder', $_SESSION['groupOrder']);
		$this->m_tpl->assign('codeTypeClt', $_SESSION['codeTypeClt']);
		$this->m_tpl->assign('etape', $_SESSION['etape']);
		$this->m_tpl->assign('qualifies', $_SESSION['qualifies']);
		$this->m_tpl->assign('elimines', $_SESSION['elimines']);
		$this->m_tpl->assign('points', $_SESSION['points']);
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
        $myBdd = new MyBdd();
        $saison = utyGetSaison();
		$codeCompet = $myBdd->RealEscapeString(utyGetPost('codeCompet'));
		$labelCompet = $myBdd->RealEscapeString(utyGetPost('labelCompet'));
		$soustitre = $myBdd->RealEscapeString(utyGetPost('soustitre'));
		$soustitre2 = $myBdd->RealEscapeString(utyGetPost('soustitre2'));
		$web = $myBdd->RealEscapeString(utyGetPost('web'));
		$bandeauLink = $myBdd->RealEscapeString(utyGetPost('bandeauLink'));
		if($bandeauLink2 = $myBdd->captureImg($bandeauLink, 'B', $codeCompet, $saison)) {
            $bandeauLink = $bandeauLink2;
        }		
        $logoLink = $myBdd->RealEscapeString(utyGetPost('logoLink'));
		if($logoLink2 = $myBdd->captureImg($logoLink, 'L', $codeCompet, $saison)) {
            $logoLink = $logoLink2;
        }
		$sponsorLink = $myBdd->RealEscapeString(utyGetPost('sponsorLink'));
		if($sponsorLink2 = $myBdd->captureImg($sponsorLink, 'S', $codeCompet, $saison)) {
            $sponsorLink = $sponsorLink2;
        }		
        $toutGroup = '';
		$touteSaisons = '';
		$en_actif = utyGetPost('en_actif');
		$titre_actif = utyGetPost('titre_actif');
		$bandeau_actif = utyGetPost('bandeau_actif');
		$logo_actif = utyGetPost('logo_actif');
		$sponsor_actif = utyGetPost('sponsor_actif');
		$kpi_ffck_actif = utyGetPost('kpi_ffck_actif');
		$niveauCompet = utyGetPost('niveauCompet');
		$codeRef = $myBdd->RealEscapeString(utyGetPost('codeRef'));
		if ($codeRef == '') {
            $codeRef = 'AUTRES';
        }
        $groupOrder = utyGetPost('groupOrder');
		$codeTypeClt = $myBdd->RealEscapeString(utyGetPost('codeTypeClt'));
		$codeTour = utyGetPost('etape');
		$qualifies = utyGetPost('qualifies');
		$elimines = utyGetPost('elimines');
		$points = utyGetPost('points');
		$statut = utyGetPost('statut');
		$publierCompet = utyGetPost('publierCompet');

		$TitreJournee = $myBdd->RealEscapeString(utyGetPost('TitreJournee'));
		$Date_debut = utyDateFrToUs(utyGetPost('Date_debut'));
		$Date_fin = utyDateFrToUs(utyGetPost('Date_fin'));
		$Lieu = $myBdd->RealEscapeString(utyGetPost('Lieu'));
		$Departement = $myBdd->RealEscapeString(utyGetPost('Departement'));
		$publierJournee = utyGetPost('publierJournee');
        

		if (strlen($codeCompet) > 0)
		{
			$sql  = "Insert Into gickp_Competitions (Code, Code_saison, Code_niveau, Libelle, Soustitre, Soustitre2, ";
			$sql .= "Web, BandeauLink, LogoLink, SponsorLink, ToutGroup, TouteSaisons, En_actif, Titre_actif, Bandeau_actif, Logo_actif, Sponsor_actif, Kpi_ffck_actif, ";
			$sql .= "Code_ref, GroupOrder, Code_typeclt, Code_tour, Qualifies, Elimines, Points, Statut, Publication) Values ('";
			$sql .= $codeCompet;
			$sql .= "','";
			$sql .= $saison;
			$sql .= "','";
			$sql .= $niveauCompet;
			$sql .= "','";
			$sql .= $labelCompet;
			$sql .= "','";
			$sql .= $soustitre;
			$sql .= "','";
			$sql .= $soustitre2;
			$sql .= "','";
			$sql .= $web;
			$sql .= "','";
			$sql .= $bandeauLink;
			$sql .= "','";
			$sql .= $logoLink;
			$sql .= "','";
			$sql .= $sponsorLink;
			$sql .= "','";
			$sql .= $toutGroup;
			$sql .= "','";
			$sql .= $touteSaisons;
			$sql .= "','";
			$sql .= $en_actif;
			$sql .= "','";
			$sql .= $titre_actif;
			$sql .= "','";
			$sql .= $bandeau_actif;
			$sql .= "','";
			$sql .= $logo_actif;
			$sql .= "','";
			$sql .= $sponsor_actif;
			$sql .= "','";
			$sql .= $kpi_ffck_actif;
			$sql .= "','";
			$sql .= $codeRef;
			$sql .= "','";
			$sql .= $groupOrder;
			$sql .= "','";
			$sql .= $codeTypeClt;
			$sql .= "','";
			$sql .= $codeTour;
			$sql .= "',";
			$sql .= $qualifies;
			$sql .= ",";
			$sql .= $elimines;
			$sql .= ",'";
			$sql .= $points;
			$sql .= "','";
			$sql .= $statut;
			$sql .= "','";
			$sql .= $publierCompet;
			$sql .= "')";
			$myBdd->Query($sql);
			
			if($Date_debut != '')
			{
				$nextIdJournee = $this->GetNextIdJournee();
				if($TitreJournee == '')
					$TitreJournee = $labelCompet;
				$sql  = "Insert Into gickp_Journees (Id, Code_competition, code_saison, Phase, Niveau, Date_debut, Date_fin, Nom, Libelle, Lieu, Plan_eau, ";
				$sql .= "Departement, Responsable_insc, Responsable_R1, Organisateur, Delegue, Publication) ";
				$sql .= "Values (".$nextIdJournee.", '";
				$sql .= $codeCompet."', ";
				$sql .= $saison . ", ";
				$sql .= "'', 1, ";
				$sql .= " '$Date_debut', '$Date_fin', '$TitreJournee', '', '$Lieu', '', ";
				$sql .= "'$Departement', '', '', '', '', '$publierJournee') ";
				$myBdd->Query($sql);
			}
		}
		
		$this->RazCompet();
		
		$myBdd->utyJournal('Ajout Compet', $saison, $myBdd->RealEscapeString($codeCompet));
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
	
	function Remove()
	{
		$ParamCmd = '';
		if (isset($_POST['ParamCmd']))
			$ParamCmd = $_POST['ParamCmd'];
			
		$arrayParam = explode(',', $ParamCmd);	//TODO	
		if (count($arrayParam) == 0)
			return; // Rien à Detruire ...
			
		$myBdd = new MyBdd();

		//Contrôle suppression possible
		$sql = "Select Id From gickp_Journees Where Code_competition In ('";
		for ($i=0;$i<count($arrayParam);$i++)
		{
			if ($i > 0)
				$sql .= "','";
			$sql .= $arrayParam[$i];
		}
		$sql .= "') and Code_saison = '".utyGetSaison()."' ";
		$result = $myBdd->Query($sql);
		if ($myBdd->NumRows($result) != 0)
			die ("Il reste des journées dans cette compétition ! Suppression impossible (<a href='javascript:history.back()'>Retour</a>)");
		
		// Suppression	
		$sql  = "Delete From gickp_Competitions Where Code_saison = '";
		$sql .= utyGetSaison();
		$sql .= "' And Code In ('";

		for ($i=0;$i<count($arrayParam);$i++)
		{
			if ($i > 0)
				$sql .= "','";
			
			$sql .= $arrayParam[$i];
			
			$myBdd->utyJournal('Suppression Compet', utyGetSaison(), $arrayParam[$i]);
		}
		$sql .= "')";
	
		$myBdd->Query($sql);

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
			$_SESSION['en_actif'] = '';
			$_SESSION['titre_actif'] = 'O';
			$_SESSION['bandeau_actif'] = '';
			$_SESSION['logo_actif'] = '';
			$_SESSION['sponsor_actif'] = '';
			$_SESSION['kpi_ffck_actif'] = 'O';
			$_SESSION['codeRef'] = '';
			$_SESSION['groupOrder'] = '';
			$_SESSION['codeTypeClt'] = '';
			$_SESSION['etape'] = '';
			$_SESSION['qualifies'] = '';
			$_SESSION['elimines'] = '';
			$_SESSION['points'] = '4-2-1-0';
			$_SESSION['statut'] = 'ATT';
			$_SESSION['commentairesCompet'] = '';
			$_SESSION['publierCompet'] = '';
	}

	function ParamCompet()
	{
		$codeCompet = utyGetPost('ParamCmd', -1);
		$_SESSION['codeCompet'] = $codeCompet;
		
		$myBdd = new MyBdd();

		$sql  = "Select Code_niveau, Libelle, Soustitre, Soustitre2, Web, BandeauLink, LogoLink, SponsorLink, ToutGroup, TouteSaisons, En_actif, Titre_actif, Bandeau_actif, Logo_actif, Sponsor_actif, Kpi_ffck_actif, ";
		$sql .= "Code_ref, GroupOrder, Code_typeclt, Code_tour, Qualifies, Elimines, Points, Statut, commentairesCompet, Publication ";
		$sql .= "From gickp_Competitions ";
		$sql .= "Where Code_saison = '";
		$sql .= utyGetSaison();
		$sql .= "' And Code = '";
		$sql .= $codeCompet;
		$sql .= "' ";
		$result = $myBdd->Query($sql);
	
		if ($myBdd->NumRows($result) == 1)
		{
			$row = $myBdd->FetchArray($result);
			
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
			$_SESSION['en_actif'] = $row['En_actif'];
			$_SESSION['touteSaisons'] = '';
			$_SESSION['titre_actif'] = $row['Titre_actif'];
			$_SESSION['bandeau_actif'] = $row['Bandeau_actif'];
			$_SESSION['logo_actif'] = $row['Logo_actif'];
			$_SESSION['sponsor_actif'] = $row['Sponsor_actif'];
			$_SESSION['kpi_ffck_actif'] = $row['Kpi_ffck_actif'];
			$_SESSION['codeRef'] = $row['Code_ref'];
			$_SESSION['groupOrder'] = $row['GroupOrder'];
			$_SESSION['codeTypeClt'] = $row['Code_typeclt'];
			$_SESSION['etape'] = $row['Code_tour'];
			$_SESSION['qualifies'] = $row['Qualifies'];
			$_SESSION['elimines'] = $row['Elimines'];
			$_SESSION['points'] = $row['Points'];
			$_SESSION['statut'] = $row['Statut'];
			$_SESSION['commentairesCompet'] = $row['commentairesCompet'];
			$_SESSION['publierCompet'] = $row['Publication'];
		}
	}

	function UpdateCompet()
	{
		$myBdd = new MyBdd();

        $saison = utyGetSaison();
        $codeCompet = $myBdd->RealEscapeString(utyGetPost('codeCompet'));
		$labelCompet = $myBdd->RealEscapeString(utyGetPost('labelCompet'));
		$soustitre = $myBdd->RealEscapeString(utyGetPost('soustitre'));
		$soustitre2 = $myBdd->RealEscapeString(utyGetPost('soustitre2'));
		$web = $myBdd->RealEscapeString(utyGetPost('web'));
		$bandeauLink = $myBdd->RealEscapeString(utyGetPost('bandeauLink'));
		if($bandeauLink2 = $myBdd->captureImg($bandeauLink, 'B', $codeCompet, $saison)) {
            $bandeauLink = $bandeauLink2;
        }		
        $logoLink = $myBdd->RealEscapeString(utyGetPost('logoLink'));
		if($logoLink2 = $myBdd->captureImg($logoLink, 'L', $codeCompet, $saison)) {
            $logoLink = $logoLink2;
        }
		$sponsorLink = $myBdd->RealEscapeString(utyGetPost('sponsorLink'));
		if($sponsorLink2 = $myBdd->captureImg($sponsorLink, 'S', $codeCompet, $saison)) {
            $sponsorLink = $sponsorLink2;
        }		
		$toutGroup = '';
		$touteSaisons = '';
		$en_actif = utyGetPost('en_actif');
		$titre_actif = utyGetPost('titre_actif');
		$bandeau_actif = utyGetPost('bandeau_actif');
		$logo_actif = utyGetPost('logo_actif');
		$sponsor_actif = utyGetPost('sponsor_actif');
		$kpi_ffck_actif = utyGetPost('kpi_ffck_actif');
		$niveauCompet = utyGetPost('niveauCompet');
		$codeRef = $myBdd->RealEscapeString(utyGetPost('codeRef'));
		$groupOrder = utyGetPost('groupOrder');
		$codeTypeClt = utyGetPost('codeTypeClt');
		$codeTour = utyGetPost('etape');
		$qualifies = utyGetPost('qualifies');
		$elimines = utyGetPost('elimines');
		$points = utyGetPost('points');
		$statut = utyGetPost('statut');
		$commentairesCompet = $myBdd->RealEscapeString(utyGetPost('commentairesCompet'));
		$publierCompet = utyGetPost('publierCompet');
		
		$sql  = "Update gickp_Competitions set Libelle = '";
		$sql .= $labelCompet;
		$sql .= "', Soustitre = '";
		$sql .= $soustitre;
		$sql .= "', Soustitre2 = '";
		$sql .= $soustitre2;
		$sql .= "', Web = '";
		$sql .= $web;
		$sql .= "', BandeauLink = '";
		$sql .= $bandeauLink;
		$sql .= "', LogoLink = '";
		$sql .= $logoLink;
		$sql .= "', SponsorLink = '";
		$sql .= $sponsorLink;
		$sql .= "', ToutGroup = '";
		$sql .= $toutGroup;
		$sql .= "', TouteSaisons = '";
		$sql .= $touteSaisons;
		$sql .= "', En_actif = '";
		$sql .= $en_actif;
		$sql .= "', Titre_actif = '";
		$sql .= $titre_actif;
		$sql .= "', Bandeau_actif = '";
		$sql .= $bandeau_actif;
		$sql .= "', Logo_actif = '";
		$sql .= $logo_actif;
		$sql .= "', Sponsor_actif = '";
		$sql .= $sponsor_actif;
		$sql .= "', Kpi_ffck_actif = '";
		$sql .= $kpi_ffck_actif;
		$sql .= "', Code_niveau = '";
		$sql .= $niveauCompet;
		$sql .= "', Code_ref = '";
		$sql .= $codeRef;
		$sql .= "', GroupOrder = '";
		$sql .= $groupOrder;
		$sql .= "', Code_typeclt = '";
		$sql .= $codeTypeClt;
		$sql .= "', Code_tour = '";
		$sql .= $codeTour;
		$sql .= "', Qualifies = '";
		$sql .= $qualifies;
		$sql .= "', Elimines = '";
		$sql .= $elimines;
		$sql .= "', Points = '";
		$sql .= $points;
		$sql .= "', Statut = '";
		$sql .= $statut;
		$sql .= "', commentairesCompet = '";
		$sql .= $commentairesCompet;
		$sql .= "', Publication = '";
		$sql .= $publierCompet;
		$sql .= "' ";
		$sql .= "Where Code_saison = '";
		$sql .= $saison;
		$sql .= "' And Code = '";
		$sql .= $codeCompet;
		$sql .= "' ";
		$result = $myBdd->Query($sql);
        
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
			$myBdd = new MyBdd();

			$sql  = "Update gickp_Competitions Set Verrou = '";
			$sql .= $Verrou;
			$sql .= "' Where Code_saison = '";
			$sql .= utyGetSaison();
			$sql .= "' And Code = '";
			$sql .= $verrouCompet;
			$sql .= "' ";
			$myBdd->Query($sql);
		}
		
		$myBdd->utyJournal('Verrou Compet', utyGetSaison(), $verrouCompet);
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

		$myBdd = new MyBdd();

		$sql  = "Update gickp_Saison Set Etat = 'I' Where Etat = 'A' ";
		$myBdd->Query($sql);

		$sql = "Update gickp_Saison Set Etat = 'A' Where Code = '".$codeSaison."' ";
		$myBdd->Query($sql);
		
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

		$myBdd = new MyBdd();

		$sql  = "INSERT INTO gickp_Saison (Code ,Etat ,Nat_debut ,Nat_fin ,Inter_debut ,Inter_fin) VALUES (";
		$sql .= "'".$newSaison."', ";
		$sql .= "'I', ";
		$sql .= "'".$newSaisonDN."', ";
		$sql .= "'".$newSaisonFN."', ";
		$sql .= "'".$newSaisonDI."', ";
		$sql .= "'".$newSaisonFI."') ";
		$myBdd->Query($sql);
		
		$myBdd->utyJournal('Ajout Saison', $newSaison);
	}
	
	function PubliCompet()
	{
		$idCompet = utyGetPost('ParamCmd', 0);
		(utyGetPost('Pub', '') != 'O') ? $changePub = 'O' : $changePub = 'N';
		
		$sql = "Update gickp_Competitions Set Publication = '$changePub' Where Code = '$idCompet' And Code_saison = '".utyGetSaison()."' ";
		$myBdd = new MyBdd();
		$myBdd->Query($sql);
		
		$myBdd->utyJournal('Publication competition', utyGetSaison(), $idCompet, 'NULL', 'NULL', 'NULL', $changePub);
	}

	function UploadLogo()
	{
		if(empty($_FILES['logo1']['tmp_name']))
			$texte = " Pas de fichier reçu - erreur ".$_FILES['logo1']['error'];
		$myBdd = new MyBdd();
		$codeSaison = utyGetSaison();
		$codeCompet = utyGetSession('codeCompet');
		$dossier = '/home/users2-new/p/poloweb/www/agil/img/logo/';
		$fichier = $codeSaison.'-'.$codeCompet.'.jpg';
		$taille_maxi = 500000;
		$taille = filesize($_FILES['logo1']['tmp_name']);
		//$erreur = $taille;
		$extensions = array('.png', '.gif', '.jpg', '.jpeg');
		$extension = strrchr($_FILES['logo1']['name'], '.'); 
		//Début des vérifications de sécurité...
		if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
		{
			 $erreur .= 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
		}
		if($taille>$taille_maxi)
		{
			$erreur .= 'Le fichier est trop gros...';
		}
		if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
		{
			if(move_uploaded_file($_FILES['logo1']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
			{
				$erreur .= 'Upload effectué avec succès !';
				$logo = "../img/logo/".$fichier;
				$sql = "Update gickp_Competitions Set LogoLink = '$logo' Where Code = '$codeCompet' And Code_saison = '$codeSaison' ";
				//$myBdd = new MyBdd();
				$myBdd->Query($sql);
				$myBdd->utyJournal('Insertion Logo', utyGetSaison(), $codeCompet, 'NULL', 'NULL', 'NULL', '');
			}
			else //Sinon (la fonction renvoie FALSE).
			{
				$erreur .= "Echec de l\'upload ! ".$texte;
			}
		}
		else
		{
			 echo $erreur;
		}		
		return($erreur);
	}

	function DropLogo()
	{
		$myBdd = new MyBdd();
		$codeSaison = utyGetSaison();
		$codeCompet = utyGetSession('codeCompet');
		$dossier = '/home/users2-new/p/poloweb/www/agil/img/logo/';
		$fichier = $codeSaison.'-'.$codeCompet.'.jpg';
		$fichier2 = 'ex-'.$codeSaison.'-'.$codeCompet.'.jpg';
		rename($dossier.$fichier, $dossier.$fichier2);
		$myBdd->utyJournal('Suppression Logo', utyGetSaison(), $codeCompet, 'NULL', 'NULL', 'NULL', '');
		return('Logo supprimé');
	}

	function FusionJoueurs()
	{
		$myBdd = new MyBdd();
		$numFusionSource = utyGetPost('numFusionSource', 0);
		$numFusionCible = utyGetPost('numFusionCible', 0);
        // buts et cartons
		$sql  = "UPDATE `gickp_Matchs_Detail` "
                . "SET `Competiteur` = $numFusionCible "
                . "WHERE `Competiteur` = $numFusionSource; ";
		$myBdd->Query($sql);
		$requete = $sql;
        // compos matchs
		$sql  = "UPDATE `gickp_Matchs_Joueurs` "
                . "SET `Matric` = $numFusionCible "
                . "WHERE `Matric` = $numFusionSource; ";
		$myBdd->Query($sql);
		$requete .= '   '.$sql;
        // feuilles de présence
		$sql  = "UPDATE `gickp_Competitions_Equipes_Joueurs` "
                . "SET `Matric` = $numFusionCible "
                . "WHERE `Matric` = $numFusionSource; ";
		$myBdd->Query($sql);
		$requete .= '   '.$sql;
        // arbitre principal
		$sql  = "UPDATE `gickp_Matchs` "
                . "SET `Matric_arbitre_principal` = $numFusionCible "
                . "WHERE `Matric_arbitre_principal` = $numFusionSource; ";
		$myBdd->Query($sql);
		$requete .= '   '.$sql;
        // arbitre secondaire
		$sql  = "UPDATE `gickp_Matchs` "
                . "SET `Matric_arbitre_secondaire` = $numFusionCible "
                . "WHERE `Matric_arbitre_secondaire` = $numFusionSource; ";
		$myBdd->Query($sql);
		$requete .= '   '.$sql;
        
        // Secretaire
		$sql  = "UPDATE `gickp_Matchs` "
                . "SET Secretaire = REPLACE(Secretaire, CONCAT('(', $numFusionSource, ')'), CONCAT('(', $numFusionCible, ')')) "
                . "WHERE Secretaire LIKE CONCAT('%(', $numFusionSource, ')%') ";
		$myBdd->Query($sql);
		$requete .= '   '.$sql;
        
        // Chronometre
		$sql  = "UPDATE `gickp_Matchs` "
                . "SET Chronometre = REPLACE(Chronometre, CONCAT('(', $numFusionSource, ')'), CONCAT('(', $numFusionCible, ')')) "
                . "WHERE Chronometre LIKE CONCAT('%(', $numFusionSource, ')%') ";
		$myBdd->Query($sql);
		$requete .= '   '.$sql;
        
        // Timeshoot
		$sql  = "UPDATE `gickp_Matchs` "
                . "SET Timeshoot = REPLACE(Timeshoot, CONCAT('(', $numFusionSource, ')'), CONCAT('(', $numFusionCible, ')')) "
                . "WHERE Timeshoot LIKE CONCAT('%(', $numFusionSource, ')%') ";
		$myBdd->Query($sql);
		$requete .= '   '.$sql;
        
        // Ligne1
		$sql  = "UPDATE `gickp_Matchs` "
                . "SET Ligne1 = REPLACE(Ligne1, CONCAT('(', $numFusionSource, ')'), CONCAT('(', $numFusionCible, ')')) "
                . "WHERE Ligne1 LIKE CONCAT('%(', $numFusionSource, ')%') ";
		$myBdd->Query($sql);
		$requete .= '   '.$sql;
        
        // Ligne2
		$sql  = "UPDATE `gickp_Matchs` "
                . "SET Ligne2 = REPLACE(Ligne2, CONCAT('(', $numFusionSource, ')'), CONCAT('(', $numFusionCible, ')')) "
                . "WHERE Ligne2 LIKE CONCAT('%(', $numFusionSource, ')%') ";
		$myBdd->Query($sql);
		$requete .= '   '.$sql;
        
        // suppression
		$sql  = "DELETE FROM `gickp_Liste_Coureur` ";
		$sql .= "WHERE `Matric` = $numFusionSource; ";
		$myBdd->Query($sql);
		$requete .= '   '.$sql;
		$myBdd->utyJournal('Fusion Joueurs', utyGetSaison(), utyGetSession('codeCompet'), 'NULL', 'NULL', 'NULL', $numFusionSource.' => '.$numFusionCible);

        return('Joueurs fusionnés : '.$requete);
	}

    function FusionEquipes() {
        $myBdd = new MyBdd();
		$numFusionEquipeSource = utyGetPost('numFusionEquipeSource', 0);
		$numFusionEquipeCible = utyGetPost('numFusionEquipeCible', 0);
        $FusionEquipeSource= $myBdd->RealEscapeString(utyGetPost('FusionEquipeSource', ''));
        $FusionEquipeCible = $myBdd->RealEscapeString(utyGetPost('FusionEquipeCible', ''));
		if ($numFusionEquipeSource == 0 or $numFusionEquipeCible == 0 or $FusionEquipeSource == '' or $FusionEquipeCible == '') {
            return;
        }
		$sql  = "UPDATE `gickp_Competitions_Equipes` ";
		$sql .= "SET `Numero` = $numFusionEquipeCible, ";
		$sql .= "`Libelle` = '".$FusionEquipeCible."' ";
		$sql .= "WHERE `Numero` = $numFusionEquipeSource; ";
		$myBdd->Query($sql);
        
        $sql  = "DELETE FROM `gickp_Equipe` ";
		$sql .= "WHERE `Numero` = $numFusionEquipeSource; ";
		$myBdd->Query($sql);

		$myBdd->utyJournal('Fusion Equipes', utyGetSaison(), utyGetSession('codeCompet'), 'NULL', 'NULL', 'NULL', $FusionEquipeSource.' => '.$FusionEquipeCible);
		return;
    }

    function DeplaceEquipe() {
        $myBdd = new MyBdd();
		$numDeplaceEquipeSource = $myBdd->RealEscapeString(utyGetPost('numDeplaceEquipeSource', 0));
		$numDeplaceEquipeCible = $myBdd->RealEscapeString(utyGetPost('numDeplaceEquipeCible', 0));
//        $DeplaceEquipeSource= $myBdd->RealEscapeString(utyGetPost('DeplaceEquipeSource', ''));
//        $DeplaceEquipeCible = $myBdd->RealEscapeString(utyGetPost('DeplaceEquipeCible', ''));
		if ($numDeplaceEquipeSource === 0) {
            return "Equipe source vide : $numDeplaceEquipeSource";
        } elseif ($numDeplaceEquipeCible === 0) {
            return "Club cible vide : $numDeplaceEquipeCible";
        }
		$sql  = "UPDATE `gickp_Competitions_Equipes` "
                . "SET Code_club = '" . $numDeplaceEquipeCible . "' "
                . "WHERE `Numero` = $numDeplaceEquipeSource; ";
		$myBdd->Query($sql);

        $sql  = "UPDATE `gickp_Equipe` "
                . "SET Code_club = '" . $numDeplaceEquipeCible . "' "
                . "WHERE `Numero` = $numDeplaceEquipeSource; ";
		$myBdd->Query($sql);

		$myBdd->utyJournal('Déplacement Equipe', utyGetSaison(), utyGetSession('codeCompet'), 'NULL', 'NULL', 'NULL', $numDeplaceEquipeSource.' => '.$numDeplaceEquipeCible);
		return $numDeplaceEquipeSource . ' => ' . $numDeplaceEquipeCible;
    }
    
	function RenomEquipe()
	{
		$myBdd = new MyBdd();
		$numRenomSource = utyGetPost('numRenomSource', 0);
		$RenomSource = utyGetPost('RenomSource', 0);
		$RenomCible = utyGetPost('RenomCible', 0);
		$sql  = "UPDATE gickp_Equipe ";
		$sql .= "SET Libelle = '".$RenomCible."' "; 
		$sql .= "WHERE Numero = '".$numRenomSource."'; ";
		$myBdd->Query($sql);
		$requete = $sql;

		$sql  = "UPDATE gickp_Competitions_Equipes ";
		$sql .= "SET Libelle = '".$RenomCible."' "; 
		$sql .= "WHERE Numero = '".$numRenomSource."'; ";
		$myBdd->Query($sql);
		$requete .= '   '.$sql;

		$myBdd->utyJournal('Rename Equipe', utyGetSaison(), utyGetSession('codeCompet'), 'NULL', 'NULL', 'NULL', $RenomSource.' => '.$RenomCible);
		return('Joueurs fusionnés : '.$requete);
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
