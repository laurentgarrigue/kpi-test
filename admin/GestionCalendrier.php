<?phpinclude_once('../commun/MyPage.php');include_once('../commun/MyBdd.php');include_once('../commun/MyTools.php');// Gestion du Calendrierclass GestionCalendrier extends MyPageSecure	 {		function Load()	{		$myBdd = new MyBdd();				//Filtre mois		if (isset($_POST['filtreMois'])) {                    $_SESSION['filtreMois'] = $_POST['filtreMois'];                } else {                    $_SESSION['filtreMois'] = '';                }                $filtreMois = $_SESSION['filtreMois'];		$this->m_tpl->assign('filtreMois', $_SESSION['filtreMois']);		// Chargement des Evenements ...		$idEvenement = utyGetSession('idEvenement', -1);		$idEvenement = utyGetPost('evenement', $idEvenement);		$_SESSION['idEvenement'] = $idEvenement;		$this->m_tpl->assign('idEvenement', $idEvenement);				$sql  = "Select Id, Libelle, Date_debut ";		$sql .= "From gickp_Evenement ";		//$sql .= "Where Publication = 'O' ";		$sql .= "Order By Date_debut DESC, Libelle ";	 				$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 1");		$num_results = mysql_num_rows($result);			$arrayEvenement = array();		if (-1 == $idEvenement) {                    array_push($arrayEvenement, array('Id' => -1, 'Libelle' => '* - Tous les événements', 'Selection' => 'SELECTED'));                } else {                    array_push($arrayEvenement, array('Id' => -1, 'Libelle' => '* - Tous les événements', 'Selection' => ''));                }                for ($i=0;$i<$num_results;$i++)		{			$row = mysql_fetch_array($result);	  			//if ($row["Publication"] == 'O')			//	$PublicEvt = ' (PUBLIC)';			//else				$PublicEvt = '';			                                if ($row["Id"] == $idEvenement) {                                    array_push($arrayEvenement, array('Id' => $row['Id'], 'Libelle' => $row['Id'] . ' - ' . $row['Libelle'] . $PublicEvt, 'Selection' => 'SELECTED'));                                } else {                                    array_push($arrayEvenement, array('Id' => $row['Id'], 'Libelle' => $row['Id'] . ' - ' . $row['Libelle'] . $PublicEvt, 'Selection' => ''));                                }                }		$this->m_tpl->assign('arrayEvenement', $arrayEvenement);		// Mode Evenement 		$modeEvenement = utyGetSession('modeEvenement', '1');		$modeEvenement = utyGetPost('choixModeEvenement', $modeEvenement);		$_SESSION['modeEvenement'] = $modeEvenement;		$this->m_tpl->assign('modeEvenement', $modeEvenement);						// Chargement des Compétitions ...		$codeCompet = utyGetSession('codeCompet', '*');		// si changement de compétition, RAZ journée sélectionnée		if (isset($_POST['codeCompet']))	// @COSANDCO_WAMPSER			if ($codeCompet != $_POST['codeCompet']) $_SESSION['idSelJournee'] = '*';		$codeCompet = utyGetPost('competition', $codeCompet);		$_SESSION['codeCompet'] = $codeCompet;				if ( ($modeEvenement == 1) && ($idEvenement != -1) )		{			// Mode Filtrage => La Combo Competition est chargée avec uniquement les compétitions de l'Evenement ...			$sql  = "Select Distinct a.Code_niveau, a.Code, a.Code_ref, a.Code_tour, a.Libelle, a.Soustitre, a.Soustitre2, a.Titre_actif ";			$sql .= "From gickp_Competitions a, gickp_Journees b, gickp_Evenement_Journees c, gickp_Competitions_Groupes g ";			$sql .= "Where a.Code = b.Code_competition ";			$sql .= "And a.Code_saison = b.Code_saison ";			$sql .= "And b.Id = c.Id_journee ";			$sql .= "And c.Id_evenement  = $idEvenement ";			$sql .= utyGetFiltreCompetition('a.');						$sql .= " And a.Code_niveau Like '".utyGetSession('AfficheNiveau')."%' ";			if(utyGetSession('AfficheCompet') == 'NCF')				$sql .= " And (a.Code Like 'N%' OR a.Code Like 'CF%') ";			else				$sql .= " And a.Code Like '".utyGetSession('AfficheCompet')."%' ";			$sql .= " And a.Code_ref = g.Groupe ";			$sql .= " Order By a.Code_saison, a.Code_niveau, g.Id, COALESCE(a.Code_ref, 'z'), a.Code_tour, a.GroupOrder, a.Code";	 		}		else		{			// Mode Association => La Combo Competition est complete ...			$sql  = "Select c.Code_niveau, c.Code_ref, c.Code_tour, c.Code, c.Libelle, c.Soustitre, c.Soustitre2, c.Titre_actif ";			$sql .= "From gickp_Competitions c, gickp_Competitions_Groupes g ";			$sql .= "Where c.Code_saison = '";			$sql .= utyGetSaison();			$sql .= "' ";			$sql .= utyGetFiltreCompetition('c.');			$sql .= " And c.Code_niveau Like '".utyGetSession('AfficheNiveau')."%' ";			if(utyGetSession('AfficheCompet') == 'NCF')				$sql .= " And (c.Code Like 'N%' OR c.Code Like 'CF%') ";			else				$sql .= " And c.Code Like '".utyGetSession('AfficheCompet')."%' ";			$sql .= " And c.Code_ref = g.Groupe ";			$sql .= " Order By c.Code_saison, c.Code_niveau, g.Id, COALESCE(c.Code_ref, 'z'), c.Code_tour, c.GroupOrder, c.Code";	 		}				$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 2 <br />".$sql);		$num_results = mysql_num_rows($result);			$arrayCompetition = array();		if ("*" == $codeCompet)			array_push($arrayCompetition, array( 'Code' => '*', 'Libelle' => '* - Toutes les compétitions', 'Selection' => 'SELECTED' ) );		else			array_push($arrayCompetition, array( 'Code' => '*', 'Libelle' => '* - Toutes les compétitions', 'Selection' => '' ) );					$listeCompet = "('";			for ($i=0;$i<$num_results;$i++)		{			$row = mysql_fetch_array($result);			// Titre			if($row["Titre_actif"] != 'O' && $row["Soustitre"] != '')				$Libelle = $row["Soustitre"];			else				$Libelle = $row["Libelle"];			if($row["Soustitre2"] != '')				$Libelle .= ' - '.$row["Soustitre2"];			$listeCompet .= $row["Code"]."','";						if ($row["Code"] == $codeCompet)				array_push($arrayCompetition, array( 'Code' => $row['Code'], 'Libelle' => $row['Code'].' - '.$Libelle, 'Selection' => 'SELECTED' ) );			else				array_push($arrayCompetition, array( 'Code' => $row['Code'], 'Libelle' => $row['Code'].' - '.$Libelle, 'Selection' => '' ) );		}		$this->m_tpl->assign('arrayCompetition', $arrayCompetition);				$listeCompet .= "')";		// Les différents tris de compétition ...		$orderCompet = utyGetSession('orderCompet', 'Date_debut, Niveau, Phase, Lieu, Libelle, Id');		$orderCompet = utyGetPost('competitionOrder', $orderCompet);		$_SESSION['orderCompet'] = $orderCompet;					$arrayCompetitionOrder = array();		if ("Date_debut, Niveau, Phase, Lieu, Libelle" == $orderCompet)			array_push($arrayCompetitionOrder, array('Code' => 'Date_debut, Niveau, Phase, Lieu, Libelle, Id', 'Libelle' => 'Par Date croissante', 'Selection' => 'SELECTED' ) );		else			array_push($arrayCompetitionOrder, array('Code' => 'Date_debut, Niveau, Phase, Lieu, Libelle, Id', 'Libelle' => 'Par Date croissante', 'Selection' => '' ) );					if ("Date_debut Desc, Niveau, Phase, Lieu, Libelle" == $orderCompet)			array_push($arrayCompetitionOrder, array('Code' => 'Date_debut Desc, Niveau, Phase, Lieu, Libelle', 'Libelle' => 'Par Date décroissante', 'Selection' => 'SELECTED' ) );		else			array_push($arrayCompetitionOrder, array('Code' => 'Date_debut Desc, Niveau, Phase, Lieu, Libelle', 'Libelle' => 'Par Date décroissante', 'Selection' => '' ) );					if ("Libelle, Niveau, Phase" == $orderCompet)			array_push($arrayCompetitionOrder, array('Code' => 'Libelle, Niveau, Phase', 'Libelle' => 'Par Libelle', 'Selection' => 'SELECTED' ) );		else			array_push($arrayCompetitionOrder, array('Code' => 'Libelle, Niveau, Phase', 'Libelle' => 'Par Libelle', 'Selection' => '' ) );					if ("Id, Niveau, Phase" == $orderCompet)			array_push($arrayCompetitionOrder, array('Code' => 'Id, Niveau, Phase', 'Libelle' => 'Par Numéro', 'Selection' => 'SELECTED' ) );		else			array_push($arrayCompetitionOrder, array('Code' => 'Id, Niveau, Phase', 'Libelle' => 'Par Numéro', 'Selection' => '' ) );					if ("Niveau, Phase, Date_debut" == $orderCompet)			array_push($arrayCompetitionOrder, array('Code' => 'Niveau, Phase, Date_debut', 'Libelle' => 'Par Niveau', 'Selection' => 'SELECTED' ) );		else			array_push($arrayCompetitionOrder, array('Code' => 'Niveau, Phase, Date_debut', 'Libelle' => 'Par Niveau', 'Selection' => '' ) );		$this->m_tpl->assign('arrayCompetitionOrder', $arrayCompetitionOrder);					$arrayEvenementJournees = array();		if ( ($modeEvenement == '2') && ($idEvenement != -1) )		{			// Mode Association ... => Chargement des Journées de l'Evenement ...			$sql = "Select Id_journee from gickp_Evenement_Journees Where Id_evenement = $idEvenement "; 					$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 3");			$num_results = mysql_num_rows($result);						for ($i=0;$i<$num_results;$i++)			{				$row = mysql_fetch_array($result);	  				array_push($arrayEvenementJournees, $row['Id_journee']);			}		}			// Chargement des Journees ...		$arrayJournees = array();				$sql  = "Select Id, Code_competition, Type, Phase, Niveau, Date_debut, Date_fin, Nom, Libelle, Lieu, Plan_eau, Departement, ";		$sql .= "Responsable_insc, Responsable_R1, Organisateur, Delegue, ChefArbitre, Publication ";		$sql .= "From gickp_Journees ";		$sql .= "Where Code_competition Is Not Null ";		// Contrôle compétitions autorisées		$sql .= "And Code_Competition In ".$listeCompet." ";				if ($codeCompet != "*")		{			$sql .= "And Code_competition = '";			$sql .= $codeCompet;			$sql .= "' ";		}				$sql .= "And Code_saison = '";		$sql .= utyGetSaison();		$sql .= "' ";		if($filtreMois > 0)			$sql .= "And (MONTH(Date_debut) = $filtreMois OR MONTH(Date_fin) = $filtreMois) ";		if ( ($idEvenement != -1) && ($modeEvenement == '1') )		{			$sql .= "And Id In (Select Id_Journee From gickp_Evenement_Journees Where Id_evenement = $idEvenement) ";		}		// Limite l'affichage		if ($idEvenement == -1 && $codeCompet == '*')		{			$sql .= "And Code_competition != 'POUBELLE' ";			$sql .= "And (Date_fin - Date_debut) < 20 ";					}		if (strlen($orderCompet) > 0)			$sql .= "Order By $orderCompet";					$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load 4");		$num_results = mysql_num_rows($result);				for ($i=0;$i<$num_results;$i++)		{			$row = mysql_fetch_array($result);	  			//			$codeCompetition = $row['Code_competition'];//			if (strlen($row['Phase']) > 0)//				$codeCompetition .= '/'.$row['Phase'].'('.$row['Niveau'].')';							$Checked = '';			if ($modeEvenement == '2')			{				// Mode Association ...				for ($j=0;$j<Count($arrayEvenementJournees);$j++)				{					if ($row['Id'] == $arrayEvenementJournees[$j])					{						$Checked = 'checked';						break;					}				}			}			$bAutorisation = utyIsAutorisationJournee($row['Id']);			array_push($arrayJournees, array( 'Id' => $row['Id'],                                 'Autorisation' => $bAutorisation,	                                'Code_competition' => $row['Code_competition'],                                'Phase' => $row['Phase'],                                'Niveau' => $row['Niveau'],                                'Date_debut' => utyDateUsToFr($row['Date_debut']),                                 'Date_fin' => utyDateUsToFr($row['Date_fin']),                                 'Nom' => $row['Nom'],                                 'Libelle' => $row['Libelle'],                                 'Type' => $row['Type'],                                 'Lieu' => $row['Lieu'],                                 'Plan_eau' => $row['Plan_eau'],                                 'Departement' => $row['Departement'],                                 'Responsable_insc' => $row['Responsable_insc'],                                 'Responsable_R1' => $row['Responsable_R1'],                                 'Delegue' => $row['Delegue'],                                 'ChefArbitre' => $row['ChefArbitre'],                                 'Organisateur' => $row['Organisateur'],                                'Publication' => $row['Publication'],                                'Checked' => $Checked ) );                    //    if($lstJournee){                    //        $lstJournee .= ',';                    //    }                    //    $lstJournee .= $row['Id'];		}						$this->m_tpl->assign('arrayJournees', $arrayJournees);                // $_SESSION['lstJournee'] = $lstJournee;	}		function Remove()	{		$ParamCmd = utyGetPost('ParamCmd');					$arrayParam = split ('[,]', $ParamCmd);				if (count($arrayParam) == 0)			return; // Rien à Detruire ...					$myBdd = new MyBdd();		//Contrôle suppression possible		$sql = "Select Id From gickp_Matchs Where Id_journee In (";		for ($i=0;$i<count($arrayParam);$i++)		{			if ($i > 0)				$sql .= ",";			$sql .= $arrayParam[$i];			//if ($arrayParam[$i] > 20080000)			//	die ("Il s'agit d'une journée fédérale ! Déplacez-là plutôt dans la competition 'POUBELLE'. Suppression impossible (<a href='javascript:history.back()'>Retour</a>)");		}		$sql .= ") ";		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Select");		if (mysql_num_rows($result) != 0)			die ("Il reste des matchs dans cette journée ! Suppression impossible (<a href='javascript:history.back()'>Retour</a>)");				// Suppression			$sql = "Delete From gickp_Journees Where Id In (";		for ($i=0;$i<count($arrayParam);$i++)		{			if ($i > 0)				$sql .= ",";						$sql .= $arrayParam[$i];					$myBdd->utyJournal('Suppression journee', '', '', 'NULL', 'NULL', $arrayParam[$i]);		}		$sql .= ")";			mysql_query($sql, $myBdd->m_link) or die ("Erreur Delete");	}	function GetNextIdJournee()	{		$myBdd = new MyBdd();		$sql  = "Select max(Id) maxId From gickp_Journees Where Id < 19000001 ";		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Select");			if (mysql_num_rows($result) == 1)		{			$row = mysql_fetch_array($result);	  			return ((int) $row['maxId'])+1;		}		else		{			return 1;		}	}			function Duplicate()	{		$idJournee = utyGetPost('ParamCmd');		if ($idJournee != 0)		{			$nextIdJournee = $this->GetNextIdJournee();						$myBdd = new MyBdd();				$sql  = "Insert Into gickp_Journees (Id, Code_competition, code_saison, Phase, Niveau, Date_debut, Date_fin, Nom, Libelle, Type, Lieu, Plan_eau, ";			$sql .= "Departement, Responsable_insc, Responsable_R1, Organisateur, Delegue, ChefArbitre) ";			$sql .= "Select $nextIdJournee, Code_competition, code_saison, Phase, Niveau, Date_debut, Date_fin, Nom, Libelle, Type, Lieu, Plan_eau, ";			$sql .= "Departement, Responsable_insc, Responsable_R1, Organisateur, Delegue, ChefArbitre ";			$sql .= "From gickp_Journees Where Id = $idJournee ";			mysql_query($sql, $myBdd->m_link) or die ("Erreur Insert");		}							if (isset($_SESSION['ParentUrl']))		{			$target = $_SESSION['ParentUrl'];			header("Location: http://".$_SERVER['HTTP_HOST'].$target);				exit;			}		$myBdd->utyJournal('Dupplication journee', utyGetSaison(), '', '', $nextIdJournee); // A compléter (saison, compétition, options)	}		function ParamJournee()	{		$_SESSION['ParentUrl'] = $_SERVER['PHP_SELF'];		$idJournee = (int) utyGetPost('ParamCmd', 0);		$_SESSION['idJournee'] = $idJournee;				header("Location: GestionParamJournee.php");			exit;		}			function AddEvenementJournee()	{		$idJournee = (int) utyGetPost('ParamCmd', 0);				$idEvenement = utyGetPost('idEvenement', -1);		if ($idEvenement == -1)			return;		$idEvenement = (int) $idEvenement;				$sql = "Replace Into gickp_Evenement_Journees (Id_Evenement, Id_Journee) Values ($idEvenement, $idJournee)";		$myBdd = new MyBdd();			mysql_query($sql, $myBdd->m_link) or die ("Erreur Replace");				$myBdd->utyJournal('Evenement +journee', '', '', 'NULL', $idEvenement, $idJournee);	}		function RemoveEvenementJournee()	{		$idJournee = (int) utyGetPost('ParamCmd', 0);				$idEvenement = utyGetSession('idEvenement', -1);		if ($idEvenement == -1)			return;		$idEvenement = (int) $idEvenement;				$sql = "Delete From gickp_Evenement_Journees Where Id_Evenement = $idEvenement And Id_Journee = $idJournee ";		$myBdd = new MyBdd();		mysql_query($sql, $myBdd->m_link) or die ("Erreur Delete");				$myBdd->utyJournal('Evenement -journee', '', '', 'NULL', $idEvenement, $idJournee);	}		function PubliJournee()	{		$idJournee = (int) utyGetPost('ParamCmd', 0);		(utyGetPost('Pub', '') != 'O') ? $changePub = 'O' : $changePub = 'N';				$sql = "Update gickp_Journees Set Publication = '$changePub' Where Id = $idJournee ";		$myBdd = new MyBdd();		mysql_query($sql, $myBdd->m_link) or die ("Erreur Update ".$sql);				$myBdd->utyJournal('Publication journee', '', '', 'NULL', 'NULL', $idJournee, $changePub);	}	function PubliMultiJournees()	{		$ParamCmd = '';		if (isset($_POST['ParamCmd']))			$ParamCmd = $_POST['ParamCmd'];					$arrayParam = split ('[,]', $ParamCmd);				if (count($arrayParam) == 0)			return; // Rien à changer ...		$myBdd = new MyBdd();				// Change Publication			for ($i=0;$i<count($arrayParam);$i++)		{			$sql = "Select Publication From gickp_Journees Where Id = ".$arrayParam[$i]." ";			$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Select");			if (mysql_num_rows($result) != 1)				continue;			$row = mysql_fetch_array($result);				($row['Publication']=='O') ? $changePub = 'N' : $changePub = 'O';			$sql = "Update gickp_Journees Set Publication = '$changePub' Where Id = '".$arrayParam[$i]."' ";			mysql_query($sql, $myBdd->m_link) or die ("Erreur Update<br>".$sql);			$myBdd->utyJournal('Publication journee', utyGetSaison(), '', 'NULL', 'NULL', $arrayParam[$i], $changePub);		}	}		function GestionCalendrier()	{				  MyPageSecure::MyPageSecure(10);				$alertMessage = '';	  		$Cmd = utyGetPost('Cmd');				if (strlen($Cmd) > 0)		{			if ($Cmd == 'Duplicate')				($_SESSION['Profile'] <= 4) ? $this->Duplicate() : $alertMessage = 'Vous n avez pas les droits pour cette action.';							if ($Cmd == 'Remove')				($_SESSION['Profile'] <= 4) ? $this->Remove() : $alertMessage = 'Vous n avez pas les droits pour cette action.';							if ($Cmd == 'ParamJournee')				($_SESSION['Profile'] <= 10) ? $this->ParamJournee() : $alertMessage = 'Vous n avez pas les droits pour cette action.';							if ($Cmd == 'AddEvenementJournee')				($_SESSION['Profile'] <= 3) ? $this->AddEvenementJournee() : $alertMessage = 'Vous n avez pas les droits pour cette action.';							if ($Cmd == 'RemoveEvenementJournee')				($_SESSION['Profile'] <= 3) ? $this->RemoveEvenementJournee() : $alertMessage = 'Vous n avez pas les droits pour cette action.';							if ($Cmd == 'PubliJournee')				($_SESSION['Profile'] <= 4) ? $this->PubliJournee() : $alertMessage = 'Vous n avez pas les droits pour cette action.';							if ($Cmd == 'PubliMultiJournees')				($_SESSION['Profile'] <= 4) ? $this->PubliMultiJournees() : $alertMessage = 'Vous n avez pas les droits pour cette action.';							if ($alertMessage == '')			{				header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);					exit;			}		}				$this->SetTemplate("Gestion des journées / phase / poules", "Journees_phases", false);		$this->Load();		$this->m_tpl->assign('AlertMessage', $alertMessage);		$this->DisplayTemplate('GestionCalendrier');	}}		  	$page = new GestionCalendrier();?>