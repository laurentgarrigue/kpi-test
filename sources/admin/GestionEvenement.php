<?php

include_once('../commun/MyPage.php');
include_once('../commun/MyBdd.php');
include_once('../commun/MyTools.php');

// Gestion des Evenements

class GestionEvenement extends MyPageSecure	 
{	
	function Load()
	{
		$idEvenement = (int) utyGetSession('idEvenement', -1);
		
		// Informations pour SelectionOuiNon ...
		$_SESSION['tableOuiNon'] = 'gickp_Evenement';
		$_SESSION['whereOuiNon'] = "Where Id = ";

		// Chargement des Evenements
		$myBdd = new MyBdd();
		$arrayEvenement = array();
		
		$sql  = "Select Id, Libelle, Lieu, Date_debut, Date_fin, Publication ";
		$sql .= "From gickp_Evenement ";
		$sql .= "Order By Date_debut Desc, Libelle Desc ";	 
	
		$arrayEvenement = array();
		$result = $myBdd->Query($sql);
		while($row = $myBdd->FetchArray($result)) {
			$StdOrSelected = 'Std';
			if ($idEvenement == $row['Id'])
				$StdOrSelected = 'Selected';

			$Publication = 'O';
			if ($row['Publication'] != 'O')
				$Publication = 'N';
			
			array_push($arrayEvenement, array( 'Id' => $row['Id'], 
						'Libelle' => $row['Libelle'],  
						'Lieu' => $row['Lieu'],  
						'Date_debut' => utyDateUsToFr($row['Date_debut']), 
						'Date_fin' => utyDateUsToFr($row['Date_fin']),
						'StdOrSelected' => $StdOrSelected,
						'Publication' => $Publication ));
		}
		$_SESSION['Libelle'] = utyGetSession('Libelle','');
		$_SESSION['Lieu'] = utyGetSession('Lieu','');
		$_SESSION['Date_debut'] = utyGetSession('Date_debut','');
		$_SESSION['Date_fin'] = utyGetSession('Date_fin','');
		$this->m_tpl->assign('arrayEvenement', $arrayEvenement);
		$this->m_tpl->assign('idEvenement', $idEvenement);
		$this->m_tpl->assign('Libelle', $_SESSION['Libelle']);
		$this->m_tpl->assign('Lieu', $_SESSION['Lieu']);
		$this->m_tpl->assign('Date_debut', $_SESSION['Date_debut']);
		$this->m_tpl->assign('Date_fin', $_SESSION['Date_fin']);
	}
	
	function Add()
	{
		$libelle = utyGetPost('Libelle');
		$lieu = utyGetPost('Lieu');
		$datedebut = utyGetPost('Date_debut');
		$datefin = utyGetPost('Date_fin');

		$myBdd = new MyBdd();

		$sql  = "Insert Into gickp_Evenement (Libelle, Lieu, Date_debut, Date_fin) Values ('";
		$sql .= $myBdd->RealEscapeString($libelle);
		$sql .= "','";
		$sql .= $myBdd->RealEscapeString($lieu);
		$sql .= "','";
		$sql .= utyDateFrToUs($datedebut);
		$sql .= "','";
		$sql .=  utyDateFrToUs($datefin);
		$sql .= "')";
		$myBdd->Query($sql);
		
		$myBdd->utyJournal('Ajout Evenement', '', '', 'NULL', 'NULL', 'NULL', $libelle);
	}
	
	function Remove()
	{
		$ParamCmd = utyGetPost('ParamCmd');
		$arrayParam = explode(',', $ParamCmd);
		if (count($arrayParam) == 0)
			return; // Rien à Detruire ...
			
		$sql  = "Delete From gickp_Evenement Where Id In (";
		for ($i=0; $i<count($arrayParam); $i++)
		{
			if ($i > 0)
				$sql .= ",";
			
			$sql .= $arrayParam[$i];
		}
		$sql .= ")";
	
		$myBdd = new MyBdd();
		$myBdd->Query($sql);
		
		$myBdd->utyJournal('Suppression Evenements', '', '', $ParamCmd);
	}
	
	function PubliEvt()
	{
		$idEvt = (int) utyGetPost('ParamCmd', -1);
		(utyGetPost('Pub', '') != 'O') ? $changePub = 'O' : $changePub = 'N';
		
		$sql = "Update gickp_Evenement Set Publication = '$changePub' Where Id = '$idEvt' ";
		$myBdd = new MyBdd();
		$myBdd->Query($sql);
		
		$myBdd->utyJournal('Publication evenement', utyGetSaison(), '', $idEvt, 'NULL', 'NULL', $changePub);
	}
	
	function RazEvt()
	{
			$_SESSION['idEvenement'] = -1;
			$_SESSION['Libelle'] = '';
			$_SESSION['Lieu'] = '';
			$_SESSION['Date_debut'] = '';
			$_SESSION['Date_fin'] = '';
	}

	function ParamEvt()
	{
		$idEvenement = utyGetPost('ParamCmd', -1);
		$_SESSION['idEvenement'] = $idEvenement;
		
		$myBdd = new MyBdd();

		$sql  = "Select Libelle, Lieu, Date_debut, Date_fin, Publication ";
		$sql .= "From gickp_Evenement ";
		$sql .= "Where Id = ";
		$sql .= $idEvenement;
		
		$result = $myBdd->Query($sql);
		if ($myBdd->NumRows($result) == 1)
		{
			$row = $myBdd->FetchArray($result);	  
			
			$_SESSION['idEvenement'] = $idEvenement;
			$_SESSION['Libelle'] = $row['Libelle'];
			$_SESSION['Lieu'] = $row['Lieu'];
			$_SESSION['Date_debut'] = utyDateUsToFr($row['Date_debut']);
			$_SESSION['Date_fin'] = utyDateUsToFr($row['Date_fin']);
		}
	}

	function UpdateEvt()
	{
		$idEvenement = utyGetPost('idEvenement');
		$libelle = utyGetPost('Libelle');
		$lieu = utyGetPost('Lieu');
		$datedebut = utyGetPost('Date_debut');
		$datefin = utyGetPost('Date_fin');
		
		$myBdd = new MyBdd();

		$sql  = "Update gickp_Evenement set Libelle = '";
		$sql .= $myBdd->RealEscapeString($libelle);
		$sql .= "', Lieu = '";
		$sql .= $myBdd->RealEscapeString($lieu);
		$sql .= "', Date_debut = '";
		$sql .= utyDateFrToUs($datedebut);
		$sql .= "', Date_fin = '";
		$sql .=  utyDateFrToUs($datefin);
		$sql .= "' ";
		$sql .= "Where Id = ";
		$sql .= $idEvenement;
		
		$myBdd->Query($sql);
		$this->RazEvt();
		$myBdd->utyJournal('Modif Evenement', '', '', $idEvenement);
	}

	function __construct()
	{
	  	MyPageSecure::MyPageSecure(2);
		
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
				
			if ($Cmd == 'Remove')
				($_SESSION['Profile'] <= 1) ? $this->Remove() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'PubliEvt')
				($_SESSION['Profile'] <= 2) ? $this->PubliEvt() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'ParamEvt')
				($_SESSION['Profile'] <= 2) ? $this->ParamEvt() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'UpdateEvt')
				($_SESSION['Profile'] <= 2) ? $this->UpdateEvt() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'RazEvt')
				($_SESSION['Profile'] <= 2) ? $this->RazEvt() : $alertMessage = 'Vous n avez pas les droits pour cette action.';

				if ($alertMessage == '')
			{
				header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);	
				exit;
			}
		}

		$this->SetTemplate("Gestion_des_evenements", "Evenements", false);
		$this->Load();
		$this->m_tpl->assign('AlertMessage', $alertMessage);
		$this->DisplayTemplate('GestionEvenement');
	}
}		  	

$page = new GestionEvenement();
