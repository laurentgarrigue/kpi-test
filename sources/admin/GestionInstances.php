<?phpinclude_once('../commun/MyPage.php');include_once('../commun/MyBdd.php');include_once('../commun/MyTools.php');// Gestion des instancesclass GestionInstances extends MyPageSecure	 {		function Load()	{		$myBdd = new MyBdd();		$idJournee = utyGetGet('idJournee', 0);		$idJournee = utyGetPost('idJournee', $idJournee);						//Chargement infos journées		$sql = "SELECT j.Id, j.Code_competition, j.Type, j.Phase, j.Niveau, j.Date_debut, j.Date_fin, 				j.Nom, j.Libelle, j.Lieu, j.Plan_eau, j.Departement, 				j.Responsable_insc, j.Responsable_R1, j.Organisateur, j.Delegue, j.ChefArbitre, j.Publication				FROM gickp_Journees j				WHERE Id = $idJournee ";				// TODO: Ajouter le représentant des chefs d'équipes, entraîneurs et compétiteurs				// TODO: Ajouter le représentant du président de la CNA (président du Jury d'appel) ?		//echo $sql;                $result = $myBdd->Query($sql);		$row = $myBdd->FetchArray($result, MYSQL_ASSOC);		//array_push($arrayJournee, $row);		$this->m_tpl->assign('arrayJournee', $row);	}		function Fonction()	{		$ParamCmd = utyGetPost('ParamCmd');	}			function GestionInstances()	{					MyPageSecure::MyPageSecure(8);				$alertMessage = '';		$Cmd = utyGetPost('Cmd');				if (strlen($Cmd) > 0)		{			if ($Cmd == 'Fonction')				($_SESSION['Profile'] <= 1) ? $this->Fonction() : $alertMessage = 'Vous n avez pas les droits pour cette action.';							if ($alertMessage == '')			{				header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);					exit;			}		}				$this->SetTemplate("Instances_de_la_journee", "Journees_phases", false);		$this->Load();		$this->m_tpl->assign('AlertMessage', $alertMessage);		$this->DisplayTemplate('GestionInstances');	}}		  	$page = new GestionInstances();