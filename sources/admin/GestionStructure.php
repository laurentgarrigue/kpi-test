<?php

include_once('../commun/MyPage.php');
include_once('../commun/MyBdd.php');
include_once('../commun/MyTools.php');

// Gestion des Equipes

class GestionStructure extends MyPageSecure	 
{	
	var $myBdd;

	function Load()
	{
		$myBdd = $this->myBdd;

		$codeCompet = utyGetSession('codeCompet');
		$_SESSION['codeCompet'] = $codeCompet;
		if ($codeCompet == '*')  
			$codeCompet = '';
		$this->m_tpl->assign('codeCompet', $codeCompet);
		
		// Chargement des Comites Régionnaux ...
		$arrayComiteReg = array();

		$sql = "SELECT Code, Libelle 
			FROM gickp_Comite_reg 
			ORDER BY Code ";	 
		$result = $myBdd->pdo->prepare($sql);
		$result->execute();
		while ($row = $result->fetch()) {
			array_push($arrayComiteReg, array('Code' => $row["Code"], 'Libelle' => $row["Code"]." - ".$row["Libelle"], 'Selected' => '' ) );
		}
		
		$this->m_tpl->assign('arrayComiteReg', $arrayComiteReg);
		
		// Chargement des Comites Departementaux ...
		$arrayComiteDep = array();

		$sql = "SELECT Code, Libelle 
			FROM gickp_Comite_dep 
			ORDER BY Code ";	 
		$result = $myBdd->pdo->prepare($sql);
		$result->execute();
		while ($row = $result->fetch()) {
			array_push($arrayComiteDep, array('Code' => $row['Code'], 'Libelle' => $row['Code']." - ".$row['Libelle'], 'Selected' => '' ) );
		}
			
		$this->m_tpl->assign('arrayComiteDep', $arrayComiteDep);
		
		// Chargement des Clubs ayant une équipe inscrite dans une compétition de polo ...
		$arrayClub = array();
		$mapParam2 = '';

		$sql = "SELECT DISTINCT c.Code, c.Libelle, c.Coord, c.Postal, c.Coord2, c.www, c.email 
			FROM gickp_Club c, gickp_Equipe e 
			WHERE c.Code = e.Code_club 
			ORDER BY c.Code ";	 
		$result = $myBdd->pdo->prepare($sql);
		$result->execute();
		while ($row = $result->fetch()) {
			array_push($arrayClub, array('Code' => $row['Code'], 
                'Libelle' => $row['Code'].' - '.$row['Libelle'], 
                'Selected' => '', 
                'Coord2' =>  $row['Coord2'], 
                'Postal' =>  $row['Postal'], 
                'Coord' =>  $row['Coord'],
                    ));
			if ($row['Coord'] != "") {
				$html = htmlspecialchars(addslashes($row['Libelle']));
				$code = $row['Code'];
                $coord = $row['Coord'];
				$postal = $row['Postal'];
				$www = $row['www'];
				$email = $row['email'];
                $mapParam2  .= "\n	
					var contentString = '<p id=\"infoWindowContent\" data-html=\"$html\" data-code=\"$code\" >$html</p>';
					var marker = new google.maps.Marker({ 
						position: new google.maps.LatLng($coord),
						map: carte,
						title: '$html',
						icon: image,
					});
                    markers['$code'] = marker;
                    coord['$code'] = \"$coord\";
                    postal['$code'] = \"$postal\";
                    www['$code'] = \"$www\";
                    email['$code'] = \"$email\";
                    
                    google.maps.event.addListener(marker,'click', (function(marker, contentString, infowindow){ 
                        return function() {
                            infowindow.setContent(contentString);
                            infowindow.open(carte, marker);
                            jq('#club').val(jq('#infoWindowContent').attr('data-code'));
                            handleSelected(false);
                        };
                    })(marker,contentString,infowindow));

				";
			}
		}
		$this->m_tpl->assign('arrayClub', $arrayClub);
        //Chargement paramètres carte ...
		$mapParam  = "image = {url: '../img/Map-Marker-Ball-Right-Azure-icon.png'};\n";
        $mapParam .= "infowindow = new google.maps.InfoWindow({});\n";
        $mapParam .= "markers = [];";
        $mapParam .= $mapParam2;
		$this->m_tpl->assign('mapParam', $mapParam);
        
		// Chargement des Clubs internationaux...
		$arrayClubInt = array();

		$sql = "SELECT DISTINCT c.Code, c.Libelle 
			FROM gickp_Club c, gickp_Comite_dep cd 
			WHERE c.Code_comite_dep = cd.Code 
			AND cd.Code_comite_reg = '98' 
			ORDER BY c.Code_comite_dep, c.Code ";	 
		$result = $myBdd->pdo->prepare($sql);
		$result->execute();
		while ($row = $result->fetch()) {
			array_push($arrayClubInt, array('Code' => $row['Code'], 'Libelle' => $row['Code'].' - '.$row['Libelle']) );
		}
		
		$this->m_tpl->assign('arrayClubInt', $arrayClubInt);

	}
		
	function AddCD()
	{
		$comiteReg = utyGetPost('comiteReg');
		$codeCD = utyGetPost('codeCD');
		$libelleCD = utyGetPost('libelleCD');

		$myBdd = $this->myBdd;
			
		$sql = "INSERT INTO gickp_Comite_dep (Code, Libelle, Code_comite_reg) 
			VALUES (?, ?, ?) ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array($codeCD, $libelleCD, $comiteReg));
			
		$myBdd->utyJournal('Ajout CD', '', '', null, null, null, $codeCD);
	}
	
	function AddClub()
	{
		$myBdd = $this->myBdd;

		$comiteDep = utyGetPost('comiteDep');
		$codeClub = utyGetPost('codeClub');
		$libelleClub = utyGetPost('libelleClub');
		$coord2 = utyGetPost('coord2');
		$postal2 = utyGetPost('postal2');
		$www2 = utyGetPost('www2');
		$email2 = utyGetPost('email2');
		$libelleEquipe2 = utyGetPost('libelleEquipe2');
		$affectEquipe = utyGetPost('affectEquipe');
		$codeSaison = $myBdd->GetActiveSaison();
			
		$sql = "INSERT INTO gickp_Club 
			(Code, Libelle, Code_comite_dep, Coord, Postal, www, email) 
			VALUES (?, ?, ?, ?, ?, ?, ?) ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array($codeClub, $libelleClub, $comiteDep, $coord2, $postal2, $www2, $email2));
		
		$myBdd->utyJournal('Ajout Club', '', '', null, null, null, $codeClub);
		
		if ($libelleEquipe2 != '') {
			$sql = "INSERT INTO gickp_Equipe (Code_club, Libelle) 
				VALUES ('".$codeClub."', '".$libelleEquipe2."')";
			$result = $myBdd->pdo->prepare($sql);
			$result->execute(array($codeClub, $libelleEquipe2));
			$selectValue = $myBdd->pdo->lastInsertId();

			$myBdd->utyJournal('Ajout Equipe', '', '', null, null, null, $libelleEquipe2);
			
			if ($affectEquipe != '') {
				if ((int) $selectValue == 0)
					return;
				$sql = "INSERT INTO gickp_Competitions_Equipes 
					(Code_compet, Code_saison, Libelle, Code_club, Numero) 
					SELECT ?, ?, Libelle, Code_club, Numero 
					FROM gickp_Equipe 
					WHERE Numero = ? ";
				$result = $myBdd->pdo->prepare($sql);
				$result->execute(array($affectEquipe, $codeSaison, $selectValue));
			}
		}
			
	}
	
	function UpdateClub()
	{
		$club = utyGetPost('club');
		$coord = utyGetPost('coord');
		$www = utyGetPost('www');
		$email = utyGetPost('email');
		$coord2 = utyGetPost('coord2');
		$postal = utyGetPost('postal');

		$myBdd = $this->myBdd;
			
		$sql = "UPDATE gickp_Club 
			SET Coord = ?, Coord2 = ?, Postal = ?, 
			www = ?, email = ? 
			WHERE Code = ? ";
		$result = $myBdd->pdo->prepare($sql);
		$result->execute(array($coord, $coord2, $postal, $www, $email, $club));
			
		$myBdd->utyJournal('Modification Club', '', '', null, null, null, $club);
	}

	function __construct()
	{			
	  	MyPageSecure::MyPageSecure(10);
		
		$this->myBdd = new MyBdd();
		
		$alertMessage = '';
		
		$Cmd = '';
		if (isset($_POST['Cmd']))
			$Cmd = $_POST['Cmd'];

		if (strlen($Cmd) > 0)
		{
			if ($Cmd == 'AddCD')
				($_SESSION['Profile'] <= 2) ? $this->AddCD() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'AddClub')
				($_SESSION['Profile'] <= 2) ? $this->AddClub() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($Cmd == 'UpdateClub')
				($_SESSION['Profile'] <= 3 or $_SESSION['User'] == '229824' or $_SESSION['User'] == '115989') ? $this->UpdateClub() : $alertMessage = 'Vous n avez pas les droits pour cette action.';
				
			if ($alertMessage == '')
			{
				header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);	
				exit;
			}
		}

		$this->SetTemplate("Gestion_des_structures", "Clubs", false);
		$this->Load();
		$this->m_tpl->assign('AlertMessage', $alertMessage);
		$this->DisplayTemplateMap('GestionStructure');
	}
}		  	

$page = new GestionStructure();
