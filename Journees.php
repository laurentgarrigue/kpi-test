<?phpinclude_once('commun/MyPage.php');include_once('commun/MyBdd.php');include_once('commun/MyTools.php');// Gestion d'une Journeeclass Journees extends MyPage	 {		function Load()	{		$myBdd = new MyBdd();				$codeCompetGroup = utyGetSession('codeCompetGroup', 'N1H');		$codeCompetGroup = utyGetPost('codeCompetGroup', $codeCompetGroup);		$codeCompetGroup = utyGetGet('Group', $codeCompetGroup);		$this->m_tpl->assign('codeCompetGroup', $codeCompetGroup);		$_SESSION['codeCompetGroup'] = $codeCompetGroup;		$codeSaison = utyGetSaison();		$codeSaison = utyGetPost('saisonTravail', $codeSaison);		$codeSaison = utyGetGet('Saison', $codeSaison);		$_SESSION['Saison'] = $codeSaison;				$idSelJournee = utyGetSession('idSelJournee', '*');		$idSelJournee = utyGetGet('J', $idSelJournee);		$idSelJournee = utyGetPost('J', $idSelJournee);		$_SESSION['idSelJournee'] = $idSelJournee;		$this->m_tpl->assign('idSelJournee', $idSelJournee);				$filtreJour = utyGetSession('filtreJour', '');		$filtreJour = utyGetPost('filtreJour', $filtreJour);		$filtreJour = utyGetGet('filtreJour', $filtreJour);		$_SESSION['filtreJour'] = $filtreJour;		$this->m_tpl->assign('filtreJour', $filtreJour);/*		$codeCompet = utyGetSession('codeCompet', 'N1H');		$codeCompet = utyGetPost('comboCompet', $codeCompet);		if(isset($_GET['Compet']))		{			$codeCompet = $_GET['Compet'];			$idEvenement = -1;		}		if(!isset($_SESSION['codeCompet']) || $codeCompet != $_SESSION['codeCompet'])		{			$_SESSION['idSelJournee'] = '*';			$GetCompetition = $myBdd->GetCompetition($codeCompet, $codeSaison);			$codeCompetGroup = $GetCompetition['Code_ref'];		}*/		// Chargement des Saisons ...		$sql  = "Select Code, Etat, Nat_debut, Nat_fin, Inter_debut, Inter_fin ";		$sql .= "From gickp_Saison ";		$sql .= "Order By Code ";	 				$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load");		$num_results = mysql_num_rows($result);			$arraySaison = array();		for ($i=0;$i<$num_results;$i++)		{			$row = mysql_fetch_array($result);				array_push($arraySaison, array('Code' => $row['Code'], 'Etat' => $row['Etat'], 											'Nat_debut' => utyDateUsToFr($row['Nat_debut']), 'Nat_fin' => utyDateUsToFr($row['Nat_fin']), 											'Inter_debut' => utyDateUsToFr($row['Inter_debut']), 'Inter_fin' => utyDateUsToFr($row['Inter_fin']) ));		}				$this->m_tpl->assign('arraySaison', $arraySaison);		$this->m_tpl->assign('sessionSaison', $codeSaison);				// Chargement des Groupes			//Compétitions internationales		$arrayCompetitionGroupe = array();		$sql  = "Select * From gickp_Competitions_Groupes Where Id >= 1 And Id < 7 Order by Id";		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 6a");		$num_results = mysql_num_rows($result);		array_push($arrayCompetitionGroupe, array('', 'CI', '=== COMPETITIONS INTERNATIONALES ===', '' ) );		for ($i=0;$i<$num_results;$i++)		{			$row = mysql_fetch_array($result);				if ($row["Groupe"] == $codeCompetGroup)				array_push($arrayCompetitionGroupe, array($row["Id"], $row["Groupe"], $row["Libelle"], "SELECTED" ) );			else				array_push($arrayCompetitionGroupe, array($row["Id"], $row["Groupe"], $row["Libelle"], "" ) );		}			//Compétitions nationales		$sql  = "Select * From gickp_Competitions_Groupes Where Id >= 7 And Id < 25 Order by Id";		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 6a");		$num_results = mysql_num_rows($result);		array_push($arrayCompetitionGroupe, array('', 'CN', '=== COMPETITIONS NATIONALES ===', '' ) );		for ($i=0;$i<$num_results;$i++)		{			$row = mysql_fetch_array($result);				if ($row["Groupe"] == $codeCompetGroup)				array_push($arrayCompetitionGroupe, array($row["Id"], $row["Groupe"], $row["Libelle"], "SELECTED" ) );			else				array_push($arrayCompetitionGroupe, array($row["Id"], $row["Groupe"], $row["Libelle"], "" ) );		}			//Compétitions régionales		$sql  = "Select * From gickp_Competitions_Groupes Where Id >= 25 And Id < 40 Order by Id";		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 6a");		$num_results = mysql_num_rows($result);		array_push($arrayCompetitionGroupe, array('', 'CR', '=== COMPETITIONS REGIONALES ===', '' ) );		for ($i=0;$i<$num_results;$i++)		{			$row = mysql_fetch_array($result);				if ($row["Groupe"] == $codeCompetGroup)				array_push($arrayCompetitionGroupe, array($row["Id"], $row["Groupe"], $row["Libelle"], "SELECTED" ) );			else				array_push($arrayCompetitionGroupe, array($row["Id"], $row["Groupe"], $row["Libelle"], "" ) );		}			//Tournois		$sql  = "Select * From gickp_Competitions_Groupes Where Id >= 40 Order by Id";		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 6a");		$num_results = mysql_num_rows($result);		array_push($arrayCompetitionGroupe, array('', 'T', '=== TOURNOIS ===', '' ) );		for ($i=0;$i<$num_results;$i++)		{			$row = mysql_fetch_array($result);				if ($row["Groupe"] == $codeCompetGroup)				array_push($arrayCompetitionGroupe, array($row["Id"], $row["Groupe"], $row["Libelle"], "SELECTED" ) );			else				array_push($arrayCompetitionGroupe, array($row["Id"], $row["Groupe"], $row["Libelle"], "" ) );		}		$this->m_tpl->assign('arrayCompetitionGroupe', $arrayCompetitionGroupe);		// Chargement des Compétitions ...		$arrayCompetition = array();		$sql  = "Select * ";		$sql .= "From gickp_Competitions ";		$sql .= "Where Code_saison = '";		$sql .= $codeSaison;		$sql .= "' ";		$sql .= utyGetFiltreCompetition('');		$sql .= " And Publication='O' ";		$sql .= " And Code_ref = '$codeCompetGroup' ";		$sql .= " Order By Code_niveau, COALESCE(Code_ref, 'z'), GroupOrder, Code_tour, Code";	 		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 6");		$num_results = mysql_num_rows($result);		while ($aRow = mysql_fetch_array($result)) {			array_push($arrayCompetition, $aRow);			if($listCompet)				$listCompet .= ',';			$listCompet .= "'".$aRow["Code"]."'";		}		$this->m_tpl->assign('arrayCompetition', $arrayCompetition);		//print($listCompet);		if(!$listCompet)			$listCompet = "'0'";		// Chargement des Informations relatives aux Journées ...		if ($idSelJournee != '*')		{			$sql  = "Select * ";			$sql .= "From gickp_Journees ";			$sql .= "Where Id = $idSelJournee ";			$sql .= "And Publication = 'O' ";					}		else		{			$sql  = "Select j.Id, j.Code_competition, j.Phase, j.Niveau, j.Libelle, j.Lieu, j.Date_debut ";			$sql .= "From gickp_Journees j, gickp_Competitions c ";			$sql .= "Where j.Code_competition In (".$listCompet.") And j.Code_saison = '";			$sql .= $codeSaison;			$sql .= "' ";			$sql .= " And j.Code_competition = c.Code ";			$sql .= " And j.Code_saison = c.Code_saison ";			$sql .= " And j.Publication = 'O' ";			$sql .= " Order by j.Code_competition, j.Date_debut, j.Lieu ";		}				$arrayJournees = array();		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 1 : ".$sql);		$num_results = mysql_num_rows($result);				$lstJournee = '';		for ($i=0;$i<$num_results;$i++)		{			$row = mysql_fetch_array($result);	  			array_push($arrayJournees, array( 'Id' => $row['Id'], 'Code_competition' => $row['Code_competition'], 																			'Phase' => $row['Phase'], 'Niveau' => $row['Niveau'], 																			'Libelle' => $row['Libelle'], 'Lieu' => $row['Lieu'], 																			'Date_debut' => utyDateUsToFr($row['Date_debut']) ));			if ($i > 0)				$lstJournee .= ',';																							$lstJournee .= $row['Id'];		}		$_SESSION['lstJournee'] = $lstJournee;				if ($lstJournee != '')		{			// Ordre des Matchs 			//$orderMatchs = utyGetSession('orderMatchs', 'Order By a.Date_match, a.Heure_match, a.Terrain');			$orderMatchs = utyGetPost('orderMatchs', 'Order By a.Date_match, a.Heure_match, a.Terrain');			//$_SESSION['orderMatchs'] = $orderMatchs;						$arrayOrderMatchs = array();						//array_push($arrayOrderMatchs, array( 'Key' => 'Order By d.Date_debut, d.Niveau, d.Phase, d.Lieu, a.Id_journee, a.Date_match, a.Heure_match, a.Terrain', 'Value' => 'Par_Journee', 'Selected' => $selected ));			array_push($arrayOrderMatchs, array( 'Key' => 'Order By a.Date_match, a.Heure_match, a.Terrain', 'Value' => 'Par_Date_Heure_et_Terrain', 'Selected' => $selected ));			array_push($arrayOrderMatchs, array( 'Key' => 'Order By a.Numero_ordre, a.Date_match, a.Heure_match, a.Terrain', 'Value' => 'Par_Numero'));			array_push($arrayOrderMatchs, array( 'Key' => 'Order By d.Code_competition, a.Date_match, a.Heure_match, a.Terrain', 'Value' => 'Par_Competition_et_Date'));			array_push($arrayOrderMatchs, array( 'Key' => 'Order By a.Terrain, a.Date_match, a.Heure_match', 'Value' => 'Par_Terrain_et_Date'));			$this->m_tpl->assign('orderMatchs', $orderMatchs);			$this->m_tpl->assign('arrayOrderMatchs', $arrayOrderMatchs);			$orderMatchsKey1 = utyKeyOrder($orderMatchs, 0);			$this->m_tpl->assign('orderMatchsKey1', $orderMatchsKey1);						// Chargement des Matchs des journées ...			$sql  = "Select a.Id, a.Id_journee, a.Numero_ordre, a.Date_match, a.Heure_match, a.Libelle, a.Terrain, a.Publication, a.Validation, ";			$sql .= "a.Statut, a.Periode, a.ScoreDetailA, a.ScoreDetailB, ";			$sql .= "b.Libelle EquipeA, c.Libelle EquipeB, b.Numero NumA, c.Numero NumB, ";			$sql .= "a.Terrain, a.ScoreA, a.ScoreB, a.CoeffA, a.CoeffB, ";			$sql .= "a.Arbitre_principal, a.Arbitre_secondaire, a.Matric_arbitre_principal, a.Matric_arbitre_secondaire, ";			$sql .= "d.Code_competition, d.Phase, d.Niveau, d.Lieu, d.Libelle LibelleJournee, d.Date_debut ";			$sql .= "From gickp_Matchs a ";			$sql .= "Left Outer Join gickp_Competitions_Equipes b On (a.Id_equipeA = b.Id) "; 			$sql .= "Left Outer Join gickp_Competitions_Equipes c On (a.Id_equipeB = c.Id) ";			$sql .= ", gickp_Journees d ";			$sql .= "Where a.Id_journee In ($lstJournee) ";			$sql .= "And a.Id_journee = d.Id ";			$sql .= "And a.Publication = 'O' ";			if($filtreJour != '')			{				$sql .= "And a.Date_match = '".$filtreJour."' ";			}			$sql .= $orderMatchs;						$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 3 <br /><br />"); // : ".$sql);			$num_results = mysql_num_rows($result);						$dateDebut = '';			$dateFin = '';			$arrayMatchs = array();			$PhaseLibelle = 0;			for ($i=0;$i<$num_results;$i++)			{				$row = mysql_fetch_array($result);	  									$row['Soustitre2'] = $myBdd->GetSoustitre2Competition($row['Code_competition'], $codeSaison);				if($row['Soustitre2'] != '')					$row['Code_competition'] = $row['Soustitre2'];				if ($row['Libelle'] != '' && strpbrk($row['Libelle'], '['))				{					$libelle = explode(']', $row['Libelle']);					if($_SESSION['lang'] == 'EN')						$EquipesAffectAuto = utyEquipesAffectAuto($row['Libelle']);					else						$EquipesAffectAuto = utyEquipesAffectAutoFR($row['Libelle']);					if ($row['EquipeA'] == '' && isset($EquipesAffectAuto[0]) && $EquipesAffectAuto[0] != '')						$row['EquipeA'] = $EquipesAffectAuto[0];					if ($row['EquipeB'] == '' && isset($EquipesAffectAuto[1]) && $EquipesAffectAuto[1] != '')						$row['EquipeB'] = $EquipesAffectAuto[1];					$arbsup = array(" (Pool Arbitres 1)", " REG", " NAT", " INT", "-A", "-B", "-C", "-S");					if($row['Arbitre_principal'] != '' && $row['Arbitre_principal'] != '-1')						$row['Arbitre_principal'] = str_replace($arbsup, '', $row['Arbitre_principal']);					elseif (isset($EquipesAffectAuto[2]) && $EquipesAffectAuto[2] != '')						$row['Arbitre_principal'] = $EquipesAffectAuto[2];					if($row['Arbitre_secondaire'] != '' && $row['Arbitre_secondaire'] != '-1')						$row['Arbitre_secondaire'] = str_replace($arbsup, '', $row['Arbitre_secondaire']);					elseif (isset($EquipesAffectAuto[3]) && $EquipesAffectAuto[3] != '')						$row['Arbitre_secondaire'] = $EquipesAffectAuto[3];					$row['Libelle'] = $libelle[1];				}								$Validation = 'O';				if ($row['Validation'] != 'O')					$Validation = 'N';								$MatchAutorisation = 'O';				if (!utyIsAutorisationJournee($row['Id_journee']))					$MatchAutorisation = 'N';									if ($row['Date_match'] > date("Y-m-d"))					$past = 'past';				else					$past = '';								array_push($arrayMatchs, array( 'Id' => $row['Id'], 'Id_journee' => $row['Id_journee'], 'Numero_ordre' => $row['Numero_ordre'], 							'Date_match' => utyDateUsToFr($row['Date_match']), 'Heure_match' => $row['Heure_match'],							'Libelle' => $row['Libelle'], 'Terrain' => $row['Terrain'], 							'EquipeA' => $row['EquipeA'], 'EquipeB' => $row['EquipeB'], 							'NumA' => $row['NumA'], 'NumB' => $row['NumB'],							'ScoreA' => $row['ScoreA'], 'ScoreB' => $row['ScoreB'], 							'ScoreDetailA' => $row['ScoreDetailA'], 'ScoreDetailB' => $row['ScoreDetailB'], 							'Statut' => $row['Statut'], 'Periode' => $row['Periode'], 							'CoeffA' => $row['CoeffA'], 'CoeffB' => $row['CoeffB'],							'Arbitre_principal' => $row['Arbitre_principal'], 							'Arbitre_secondaire' => $row['Arbitre_secondaire'],							'Matric_arbitre_principal' => $row['Matric_arbitre_principal'],							'Matric_arbitre_secondaire' => $row['Matric_arbitre_secondaire'],							'Code_competition' => $row['Code_competition'],							'Phase' => $row['Phase'],							'Niveau' => $row['Niveau'],							'Lieu' => $row['Lieu'],							'LibelleJournee' => $row['LibelleJournee'],							'StdOrSelected' => $StdOrSelected,							'MatchAutorisation' => $MatchAutorisation,							'Publication' => $Publication,							'Validation' => $Validation,							'past' => $past	));								if($i != 0)					$listMatch .=',';				$listMatch .= $row['Id'];											if ($row['Phase'] != '' || $row['Libelle'] != '')					$PhaseLibelle = 1;																								if ($i == 0)				{					$dateDebut = utyDateUsToFr($row['Date_match']);					$dateFin = utyDateUsToFr($row['Date_match']);				}																								else				{					if (utyDateCmpFr($dateDebut, utyDateUsToFr($row['Date_match'])) > 0)						$dateDebut = utyDateUsToFr($row['Date_match']);											if (utyDateCmpFr($dateFin, utyDateUsToFr($row['Date_match'])) < 0)						$dateFin = utyDateUsToFr($row['Date_match']);				}																							}			$this->m_tpl->assign('listMatch', $listMatch);			$this->m_tpl->assign('arrayMatchs', $arrayMatchs);			$this->m_tpl->assign('PhaseLibelle', $PhaseLibelle);						if ( ($idMatch < 0) && (count($arrayMatchs) >= 1) )			{				$idMatch = $arrayMatchs[0]['Id'];			}					}				$this->m_tpl->assign('idCurrentJournee', $idJournee);		$this->m_tpl->assign('arrayJournees', $arrayJournees);		$this->m_tpl->assign('arrayJourneesAutorisees', $arrayJourneesAutorisees);		$this->m_tpl->assign('codeCurrentCompet', $codeCompet);		$this->m_tpl->assign('arrayCompet', $arrayCompet);	}			function Journees()	{				  MyPage::MyPage();				if ($ParamCmd == 'changeCompet')			$_SESSION['idMatch'] = -1; // La Combo Compétition a changé => Plus aucun match n'est sélectionné ...				$this->SetTemplate("Matchs", "Matchs", true);		$this->Load();		$this->m_tpl->assign('AlertMessage', $alertMessage);				$this->m_tpl->assign('idMatch', utyGetSession('idMatch', 0));		$this->m_tpl->assign('idJournee', utyGetSession('idJournee', 0));				$this->m_tpl->assign('Intervalle_match', utyGetSession('Intervalle_match', '40'));		$this->m_tpl->assign('Num_match', utyGetSession('Num_match', ''));		$this->m_tpl->assign('Date_match', utyGetSession('Date_match', ''));		$this->m_tpl->assign('Heure_match', utyGetSession('Heure_match', ''));		$this->m_tpl->assign('Libelle', utyGetSession('Libelle', ''));		$this->m_tpl->assign('Terrain', utyGetSession('Terrain', ''));		$this->m_tpl->assign('arbitre1', utyGetSession('arbitre1', ''));		$this->m_tpl->assign('arbitre2', utyGetSession('arbitre2', ''));		$this->m_tpl->assign('arbitre1_matric', utyGetSession('arbitre1_matric', ''));		$this->m_tpl->assign('arbitre2_matric', utyGetSession('arbitre2_matric', ''));		$this->m_tpl->assign('coeffA', utyGetSession('coeffA', 1));		$this->m_tpl->assign('coeffB', utyGetSession('coeffB', 1));				$this->DisplayTemplate('Journees');	}}		  	$page = new Journees();?>