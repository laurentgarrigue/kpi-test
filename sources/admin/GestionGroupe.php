<?php

include_once('../commun/MyPage.php');
include_once('../commun/MyBdd.php');
include_once('../commun/MyTools.php');

// Gestion des Evenements

class GestionGroupe extends MyPageSecure	 
{	
	function Load()
	{
		$idGroupe = (int) utyGetSession('idGroupe', -1);
		
		// Chargement des Groupes
		$myBdd = new MyBdd();
		$arrayGroupes = array();
		
		$sql  = "Select * ";
		$sql .= "From gickp_Competitions_Groupes ";
		$sql .= "Order By section, ordre ";	 
	
		$arrayGroupes = array();
        $result = $myBdd->Query($sql);
        while ($row = $myBdd->FetchArray($result)){ 
			if($idGroupe == $row['id']) {
                $row['selected'] = 'selected';
                $groupe = $row;
            } else {
                $row['selected'] = '';
            }
			array_push($arrayGroupes, $row);
		}
		
		$this->m_tpl->assign('groupe', $groupe);
		$this->m_tpl->assign('arrayGroupes', $arrayGroupes);
	}
	
	function Add()
	{
		$libelle = utyGetPost('Libelle');
		$section = utyGetPost('section');
		$ordre = utyGetPost('ordre');
		$Code_niveau = utyGetPost('Code_niveau');
		$Groupe = utyGetPost('Groupe');
		
		$myBdd = new MyBdd();

		$sql  = "INSERT INTO gickp_Competitions_Groupes set Libelle = '";
		$sql .= $myBdd->RealEscapeString($libelle);
		$sql .= "', section = '";
		$sql .= $myBdd->RealEscapeString($section);
		$sql .= "', ordre = '";
		$sql .= $myBdd->RealEscapeString($ordre);
		$sql .= "', Code_niveau = '";
		$sql .= $myBdd->RealEscapeString($Code_niveau);
		$sql .= "', Groupe = '";
		$sql .= $myBdd->RealEscapeString($Groupe);
		$sql .= "'";
		
		$myBdd->Query($sql);
		$this->Raz();
		$myBdd->utyJournal('Ahout Groupe', '', '', $Groupe);
        return "Ajout effectué.";
	}
	
	function Remove($idGroupe)
	{
        $myBdd = new MyBdd();
        $sql  = "SELECT c.Code_saison, c.Code "
                . "FROM gickp_Competitions c, gickp_Competitions_Groupes g "
                . "WHERE c.Code_ref = g.Groupe "
                . "AND g.id = $idGroupe ";
        $result = $myBdd->Query($sql);
        $num_results = $myBdd->NumRows($result);
        
        if($num_results > 0) {
            $conflict = '';
            while ($row = $myBdd->FetchArray($result)){ 
                $conflict .= ' ' . $row['Code_saison'] . '-' . $row['Code'];
            }
            return "Il existe des compétitions dans ce groupe :$conflict. Suppression impossible !";
        }
        
        $sql  = "DELETE FROM gickp_Competitions_Groupes "
                . "WHERE id = $idGroupe ";
        $result = $myBdd->Query($sql);
        return "Suppression effectuée.";
    }
	
	function Edit($idGroupe)
	{
        $_SESSION['idGroupe'] = $idGroupe;
	}

	function Raz()
	{
        $_SESSION['idGroupe'] = -1;
	}

	function Update()
	{
		$idGroupe = utyGetPost('idGroupe');
		$libelle = utyGetPost('Libelle');
		$section = utyGetPost('section');
		$ordre = utyGetPost('ordre');
		$Code_niveau = utyGetPost('Code_niveau');
		$Groupe = utyGetPost('Groupe');
		
		$myBdd = new MyBdd();

		$sql  = "Update gickp_Competitions_Groupes set Libelle = '";
		$sql .= $myBdd->RealEscapeString($libelle);
		$sql .= "', section = '";
		$sql .= $myBdd->RealEscapeString($section);
		$sql .= "', ordre = '";
		$sql .= $myBdd->RealEscapeString($ordre);
		$sql .= "', Code_niveau = '";
		$sql .= $myBdd->RealEscapeString($Code_niveau);
		$sql .= "', Groupe = '";
		$sql .= $myBdd->RealEscapeString($Groupe);
		$sql .= "' Where id = ";
		$sql .= $idGroupe;
		
		$result = $myBdd->Query($sql);
		$this->Raz();
		$myBdd->utyJournal('Modif Groupe', '', '', $idGroupe);
        return "Mise à jour effectuée.";
	}

	function __construct()
	{			
        MyPageSecure::MyPageSecure(1);
		
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
				($_SESSION['Profile'] <= 2) ? $alertMessage = $this->Add() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'Remove')
				($_SESSION['Profile'] <= 1) ? $alertMessage = $this->Remove($ParamCmd) : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'Edit')
				($_SESSION['Profile'] <= 2) ? $this->Edit($ParamCmd) : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'Update')
				($_SESSION['Profile'] <= 2) ? $alertMessage = $this->Update() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'Raz')
				($_SESSION['Profile'] <= 2) ? $this->Raz() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($alertMessage == '')
			{
				header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);	
				exit;
			}
		}

		$this->SetTemplate("Gestion_des_groupes", "Competitions", false);
		$this->Load();
		$this->m_tpl->assign('AlertMessage', $alertMessage);
		$this->DisplayTemplate('GestionGroupe');
	}
}		  	

$page = new GestionGroupe();
