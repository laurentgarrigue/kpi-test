<?php

include_once('commun/MyPage.php');
include_once('commun/MyBdd.php');
include_once('commun/MyTools.php');

define('FPDF_FONTPATH','font/');
require('fpdf/fpdf.php');

require_once('qrcode/qrcode.class.php');

// Pieds de page
class PDF extends FPDF
{
	function Footer()
	{
	    //Positionnement à 1,5 cm du bas
	    $this->SetY(-15);
	    //Police Arial italique 8
	    $this->SetFont('Arial','I',8);
	    //Numéro de page centré
		$this->Cell(137,10,'Page '.$this->PageNo(),0,0,'L');
		$this->Cell(136,5,"Print ".date("Y-m-d")."   ".date("H:i"),0,1,'R');
	}
}
 
// Liste des Matchs d'une Journee ou d'un Evenement 
class PdfListeMatchs extends MyPage	 
{	
 	function InitTitulaireEquipe($numEquipe, $idMatch, $idEquipe, $bdd)
	{
		$sql = "Select Count(*) Nb From gickp_Matchs_Joueurs Where Id_match = $idMatch And Equipe = '$numEquipe' ";
		$result = mysql_query($sql, $bdd->m_link) or die ("Erreur Select ".$sql);

		if (mysql_num_rows($result) != 1)
			return;
			
		$row = mysql_fetch_array($result);
		if ((int) $row['Nb'] > 0)
			return;
			
		$sql  = "Replace Into gickp_Matchs_Joueurs ";
		$sql .= "Select $idMatch, Matric, Numero, '$numEquipe', Capitaine From gickp_Competitions_Equipes_Joueurs ";
		$sql .= "Where Id_equipe = $idEquipe ";
		$sql .= "AND Capitaine <> 'X' ";
		$sql .= "AND Capitaine <> 'A' ";
		mysql_query($sql, $bdd->m_link) or die ("Erreur Replace InitTitulaireEquipe");
 	}
	   
	function PdfListeMatchs()
	{
		MyPage::MyPage();

 		$filtreJour = utyGetSession('filtreJour', '');
		$filtreJour = utyGetPost('filtreJour', $filtreJour);
		$filtreJour = utyGetGet('filtreJour', $filtreJour);
		
		$filtreTerrain = utyGetSession('filtreTerrain', '');
		$filtreTerrain = utyGetPost('filtreTerrain', $filtreTerrain);
		$filtreTerrain = utyGetGet('filtreTerrain', $filtreTerrain);

 	// Chargement des Matchs des journées ...
		$myBdd = new MyBdd();
		$lstJournee = utyGetSession('lstJournee', 0);
		$idEvenement = utyGetSession('idEvenement', -1);
		$idEvenement = utyGetGet('idEvenement', $idEvenement);
		if(isset($_GET['idEvenement'])){
			$lstJournee = '';
			$sql = "SELECT Id_journee FROM gickp_Evenement_Journees WHERE Id_evenement = ".$_GET['idEvenement'];
			$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load =>  ".$sql);
			$num_results = mysql_num_rows($result);
			for ($j=0;$j<$num_results;$j++)
			{
				$row = mysql_fetch_array($result);
				if ($lstJournee != '')
					$lstJournee .= ',';
				$lstJournee .= $row['Id_journee'];
			}
		}
		$codeSaison = utyGetSaison();
		$codeSaison = utyGetGet('S', $codeSaison);
		$orderMatchs = utyGetSession('orderMatchs', 'Order By a.Date_match, d.Lieu, a.Heure_match, a.Terrain');
		$laCompet = utyGetSession('codeCompet', 0);
		$laCompet = utyGetGet('Compet', $laCompet);
		if($laCompet != 0)
		{
			$lstJournee = 0;
			$idEvenement = -1;
		}
		$codeCompet = $laCompet;
		$sql  = "Select a.Id, a.Id_journee, a.Id_equipeA, a.Id_equipeB, a.Numero_ordre, a.Date_match, a.Heure_match, ";
		$sql .= "a.Libelle, a.Terrain, b.Libelle EquipeA, c.Libelle EquipeB, a.Terrain, a.ScoreA, a.ScoreB, ";
		$sql .= "a.Arbitre_principal, a.Arbitre_secondaire, a.Matric_arbitre_principal, a.Matric_arbitre_secondaire, a.Validation, ";
		$sql .= "d.Code_competition, d.Phase, d.Niveau, d.Lieu, d.Libelle LibelleJournee ";
		$sql .= "From gickp_Matchs a ";
		$sql .= "Left Outer Join gickp_Competitions_Equipes b On (a.Id_equipeA = b.Id) "; 
		$sql .= "Left Outer Join gickp_Competitions_Equipes c On (a.Id_equipeB = c.Id) ";
		$sql .= ", gickp_Journees d ";
		if($lstJournee == 0)
			$sql .= "Where d.Code_competition = '".$laCompet."' And d.Code_saison = $codeSaison ";
		else
			$sql .= "Where a.Id_journee In ($lstJournee) ";
		if($filtreJour != '')
		{
			$sql .= "And a.Date_match = '".$filtreJour."' ";
		}
		if($filtreTerrain != '')
		{
			$sql .= "And a.Terrain = '".$filtreTerrain."' ";
		}
		$sql .= "And a.Id_journee = d.Id ";
		$sql .= "And a.Publication = 'O' ";
		$sql .= $orderMatchs;
		
		$orderMatchsKey1 = utyKeyOrder($orderMatchs, 0);
		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load =>  ".$sql);
		$num_results = mysql_num_rows($result);
		
		$PhaseLibelle = 0;
		for ($j=0;$j<$num_results;$j++)
		{
			$row1 = mysql_fetch_array($result);	  
			if (trim($row1['Phase']) != '')
				$PhaseLibelle = 1;
			$lastCompetEvt = $row1['Code_competition'];
		}
		$Oldrupture = "";
		// Chargement des infos de l'évènement ou de la compétition
		$titreEvenementCompet = '';
		if ($idEvenement != -1)
		{
			$libelleEvenement = $myBdd->GetEvenementLibelle($idEvenement);
			$titreEvenementCompet = 'Event : '.$libelleEvenement;
			$arrayCompetition = $myBdd->GetCompetition($lastCompetEvt, $codeSaison);
		}
		else
		{
			$arrayCompetition = $myBdd->GetCompetition($codeCompet, $codeSaison);
			if($arrayCompetition['Titre_actif'] == 'O')
				$titreEvenementCompet = $arrayCompetition['Libelle'];
			else
				$titreEvenementCompet = $arrayCompetition['Soustitre'];
			if($arrayCompetition['Soustitre2'] != '')
				$titreEvenementCompet .= ' - '.$arrayCompetition['Soustitre2'];
			//$titreEvenementCompet = 'Compétition : '.$arrayCompetition['Libelle'].' ('.$codeCompet.')';
		}
		$logo = str_replace('http://www.kayak-polo.info/','',$arrayCompetition['LogoLink']);
		$sponsor = str_replace('http://www.kayak-polo.info/','',$arrayCompetition['SponsorLink']);
		// Entête PDF ...	  
 		$pdf = new PDF('L');
		$pdf->Open();
		$pdf->SetTitle("Liste des Matchs");
		$pdf->SetAuthor("Kayak-polo.info");
		$pdf->SetCreator("Kayak-polo.info width FPDF");
		$pdf->SetTopMargin(30);
		$pdf->AddPage();
		if($arrayCompetition['Sponsor_actif'] == 'O' && $sponsor != '')
			$pdf->SetAutoPageBreak(true, 28);
		else
			$pdf->SetAutoPageBreak(true, 15);
		// Affichage
			$qr_x = 262;
			// logo
			if($arrayCompetition['Kpi_ffck_actif'] == 'O')
			{
				$pdf->Image('css/banniere1.jpg',10,8,72,15,'jpg',"http://www.kayak-polo.info");
				$pdf->Image('img/ffck2.jpg',252,8,0,15,'jpg',"http://www.ffck.org");
				$qr_x = 236;
			}
			if($arrayCompetition['Logo_actif'] == 'O' && $logo != '')  //&& file_exists($logo)
			{
				$size = getimagesize($logo);
				$largeur=$size[0];
				$hauteur=$size[1];
				$ratio=20/$hauteur;	//hauteur imposée de 20mm
				$newlargeur=$largeur*$ratio;
				$posi=149-($newlargeur/2);	//297mm = largeur de page
				$pdf->image($logo, $posi, 8, 0,20);
			}
			if($arrayCompetition['Sponsor_actif'] == 'O' && $sponsor != '')  //&& file_exists($sponsor)
			{
				$size = getimagesize($sponsor);
				$largeur=$size[0];
				$hauteur=$size[1];
				$ratio=16/$hauteur;	//hauteur imposée de 16mm
				$newlargeur=$largeur*$ratio;
				$posi=149-($newlargeur/2);	//297mm = largeur de page
				$pdf->image($sponsor, $posi, 180, 0,16);
			}
		// QRCode
		$qrcode = new QRcode('http://www.kayak-polo.info/Journee.php?Compet='.$codeCompet.'&Group='.$arrayCompetition['Code_ref'].'&Saison='.$codeSaison, 'L'); // error level : L, M, Q, H
		//$qrcode->displayFPDF($fpdf, $x, $y, $s, $background, $color);
		$qrcode->displayFPDF($pdf, $qr_x, 9, 21);

		$titreDate = "Season ".$codeSaison;
		// titre
		$pdf->SetFont('Arial','BI',12);
		$pdf->Cell(137,5,$titreEvenementCompet,0,0,'L');
		$pdf->Cell(136,5,$titreDate,0,1,'R');
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(273,6,"Game list",0,1,'C');
		$pdf->Ln(3);
		$heure1 = '';
		if($num_results > 0)
			mysql_data_seek($result,0);
		for ($i=0;$i<$num_results;$i++)
		{
			$row = mysql_fetch_array($result);
			
			$row['Soustitre2'] = $myBdd->GetSoustitre2Competition($row['Code_competition'], $codeSaison);
			if($row['Soustitre2'] != '')
				$row['Code_competition'] = $row['Soustitre2'];
			$phase_match = $row['Phase'];
			if ($row['Libelle'] != '')
			{
				$libelle = explode(']', $row['Libelle']);
				if($libelle[1] != '')
					$phase_match .= "  |  ".$libelle[1];
				//Codes équipes	
				$EquipesAffectAuto = utyEquipesAffectAuto($row['Libelle']);
			}
			if ($row['Id_equipeA'] >= 1)
				$this->InitTitulaireEquipe('A', $row['Id'], $row['Id_equipeA'], $myBdd);
			elseif (isset($EquipesAffectAuto[0]) && $EquipesAffectAuto[0] != '')
				$row['EquipeA'] = $EquipesAffectAuto[0];
			if ($row['Id_equipeB'] >= 1)
				$this->InitTitulaireEquipe('B', $row['Id'], $row['Id_equipeB'], $myBdd);
			elseif (isset($EquipesAffectAuto[1]) && $EquipesAffectAuto[1] != '')
				$row['EquipeB'] = $EquipesAffectAuto[1];
			$arbsup = array(" (Pool Arbitres 1)", " REG", " NAT", " INT", "-A", "-B", "-C", "-S");
			if($row['Arbitre_principal'] != '' && $row['Arbitre_principal'] != '-1')
				$row['Arbitre_principal'] = str_replace($arbsup, '', $row['Arbitre_principal']);
			elseif (isset($EquipesAffectAuto[2]) && $EquipesAffectAuto[2] != '')
				$row['Arbitre_principal'] = $EquipesAffectAuto[2];
			if($row['Arbitre_secondaire'] != '' && $row['Arbitre_secondaire'] != '-1')
				$row['Arbitre_secondaire'] = str_replace($arbsup, '', $row['Arbitre_secondaire']);
			elseif (isset($EquipesAffectAuto[3]) && $EquipesAffectAuto[3] != '')
				$row['Arbitre_secondaire'] = $EquipesAffectAuto[3];
				// rupture ligne
				if ($orderMatchsKey1 == "Numero_ordre")
				{
					$rupture = $row['Date_match'];
				}else{
					$rupture = $row[$orderMatchsKey1];
				}
				
				if ($rupture != $Oldrupture)
				{
					if ($Oldrupture != '')
					{
						$pdf->Cell(22,3, '',0,0,'C');
						$pdf->Cell(227,3,'','T','1','C');
						$pdf->AddPage();
						// logo
						if($arrayCompetition['Kpi_ffck_actif'] == 'O')
						{
							$pdf->Image('css/banniere1.jpg',10,8,72,15,'jpg',"http://www.kayak-polo.info");
							$pdf->Image('img/ffck2.jpg',252,8,0,15,'jpg',"http://www.ffck.org");
						}
						if($arrayCompetition['Logo_actif'] == 'O' && $logo != '')  //&& file_exists($logo)
						{
							$size = getimagesize($logo);
							$largeur=$size[0];
							$hauteur=$size[1];
							$ratio=20/$hauteur;	//hauteur imposée de 20mm
							$newlargeur=$largeur*$ratio;
							$posi=149-($newlargeur/2);	//297mm = largeur de page
							$pdf->image($logo, $posi, 8, 0,20);
						}
						if($arrayCompetition['Sponsor_actif'] == 'O' && $sponsor != '')  //&& file_exists($sponsor)
						{
							$size = getimagesize($sponsor);
							$largeur=$size[0];
							$hauteur=$size[1];
							$ratio=16/$hauteur;	//hauteur imposée de 16mm
							$newlargeur=$largeur*$ratio;
							$posi=149-($newlargeur/2);	//297mm = largeur de page
							$pdf->image($sponsor, $posi, 180, 0,16);
						}
					}
					$Oldrupture = $rupture;
					$pdf->Cell(60,5, '','','0','L');
					switch ($orderMatchsKey1)
					{
					case "Code_competition" :
						$pdf->SetFont('Arial','B',9);
						$pdf->Cell(150, 5, utyGetLabelCompetition($rupture)." (".$rupture.")", 'LTBR','1','C');
						//$pdf->Cell(22,5, '',0,0,'C');
						$pdf->Cell(8,5, '#','LTRB','0','C');
						$pdf->Cell(16,5, 'Date','TRB','0','C');
						$pdf->Cell(10,5, 'Time','TRB','0','C');
						if ($PhaseLibelle == 1)
							$pdf->Cell(52,5, 'Phase | Game','TRB','0','C');
						else
							$pdf->Cell(52,5, 'Place','TRB','0','C');
						$pdf->Cell(11,5, 'Pitch.','TRB','0','C');
						$pdf->Cell(35,5, 'Team A','TRB','0','C');
						$pdf->Cell(14,5, 'Scores','TRB','0','C');
						$pdf->Cell(35,5, 'Team B','TRB',0,'C');
						$pdf->Cell(45,5, 'First referee','TRB','0','C');
						$pdf->Cell(45,5, 'Second referee','TRB','1','C');
						$pdf->SetFont('Arial','',8);
						break;
					case "Terrain" :
						$pdf->SetFont('Arial','B',9);
						$pdf->Cell(150, 5, "Pitch : ".$rupture, 'LTBR',1,'C');
						//$pdf->Cell(22,5, '',0,0,'C');
						$pdf->Cell(8,5, '#','LTRB','0','C');
						$pdf->Cell(16,5, 'Date','TRB','0','C');
						$pdf->Cell(10,5, 'Time','TRB','0','C');
						$pdf->Cell(17,5, 'Cat.','TRB','0','C');
						if ($PhaseLibelle == 1)
							$pdf->Cell(50,5, 'Phase | Game','TRB','0','C');
						else
							$pdf->Cell(50,5, 'Place','TRB','0','C');
						$pdf->Cell(35,5, 'Team A','TRB','0','C');
						$pdf->Cell(14,5, 'Scores','TRB','0','C');
						$pdf->Cell(35,5, 'Team B','TRB',0,'C');
						$pdf->Cell(45,5, 'First referee','TRB','0','C');
						$pdf->Cell(45,5, 'Second referee','TRB','1','C');
						$pdf->SetFont('Arial','',8);
						break;
					default :
						$pdf->SetFont('Arial','B',9);
						$rupture2 = new DateTime($rupture);
						$pdf->Cell(150, 5, date_format($rupture2, 'l jS F, Y').' - '.html_entity_decode($row['Lieu']), 'LTBR','1','C');
						//$pdf->Cell(22,5, '',0,0,'C');
						$pdf->Cell(8,5, '#','LTRB','0','C');
						$pdf->Cell(10,5, 'Time','TRB','0','C');
						$pdf->Cell(17,5, 'Cat.','TRB','0','C');
						if ($PhaseLibelle == 1)
							$pdf->Cell(50,5, 'Phase | Game','TRB','0','C');
						else
							$pdf->Cell(50,5, 'Place','TRB','0','C');
						$pdf->Cell(12,5, 'Pitch','TRB','0','C');
						$pdf->Cell(35,5, 'Team A','TRB','0','C');
						$pdf->Cell(14,5, 'Scores','TRB','0','C');
						$pdf->Cell(35,5, 'Team B','TRB',0,'C');
						$pdf->Cell(46,5, 'First referee','TRB','0','C');
						$pdf->Cell(46,5, 'Second referee','TRB','1','C');
						break;
					}
				}

			switch ($orderMatchsKey1)
			{
			case "Code_competition" :
				$pdf->SetFont('Arial','',8);
				//$pdf->Cell(22,5, '',0,0,'C');
				$pdf->Cell(8,5, $row['Numero_ordre'],'LTBR','0','C');
				$pdf->Cell(16,5, utyDateUsToFr($row['Date_match']),'TBR','0','C');
				$pdf->Cell(10,5, $row['Heure_match'],'TBR','0','C');
				if ($PhaseLibelle == 1)
					$pdf->Cell(52,5, $phase_match,'TBR','0','C');
				else
					$pdf->Cell(52,5, html_entity_decode($row['Lieu']),'TRB','0','C');
				$pdf->Cell(11,5, $row['Terrain'],'TBR','0','C');
				$pdf->Cell(35,5, $row['EquipeA'],'TBR','0','C');
				if ($row['ScoreA']!='?' && $row['Validation'] == 'O')
					$pdf->Cell(7,5, $row['ScoreA'],'TBR','0','C');
				else
					$pdf->Cell(7,5, "",'TBR','0','C');
				if ($row['ScoreB']!='?' && $row['Validation'] == 'O')
					$pdf->Cell(7,5, $row['ScoreB'],'TBR','0','C');
				else
					$pdf->Cell(7,5, "",'TBR','0','C');
				$pdf->Cell(35,5, $row['EquipeB'],'TBR',0,'C');
				$pdf->SetFont('Arial','I',6);
				if ($row['Arbitre_principal']=='-1')
					$pdf->Cell(45,5, '','TBR',0,'C');
				else
					$pdf->Cell(45,5, $row['Arbitre_principal'],'TBR','0','C');
				if ($row['Arbitre_secondaire']=='-1')
					$pdf->Cell(45,5, '','TBR',1,'C');
				else
					$pdf->Cell(45,5, $row['Arbitre_secondaire'],'TBR',1,'C');
				break;
			case "Terrain" :
				$pdf->SetFont('Arial','',8);
				//$pdf->Cell(22,5, '',0,0,'C');
				$pdf->Cell(8,5, $row['Numero_ordre'],'LTBR','0','C');
				$pdf->Cell(16,5, utyDateUsToFr($row['Date_match']),'TBR','0','C');
				$pdf->Cell(10,5, $row['Heure_match'],'TBR','0','C');
				$pdf->Cell(17,5, $row['Code_competition'],'TBR','0','C');
				if ($PhaseLibelle == 1)
					$pdf->Cell(50,5, $phase_match,'TBR','0','C');
				else
					$pdf->Cell(50,5, html_entity_decode($row['Lieu']),'TRB','0','C');
				$pdf->Cell(35,5, $row['EquipeA'],'TBR','0','C');
				
				if ($row['ScoreA']!='?' && $row['Validation'] == 'O')
					$pdf->Cell(7,5, $row['ScoreA'],'TBR','0','C');
				else
					$pdf->Cell(7,5, "",'TBR','0','C');
				if ($row['ScoreB']!='?' && $row['Validation'] == 'O')
					$pdf->Cell(7,5, $row['ScoreB'],'TBR','0','C');
				else
					$pdf->Cell(7,5, "",'TBR','0','C');
				$pdf->Cell(35,5, $row['EquipeB'],'TBR',0,'C');
				$pdf->SetFont('Arial','I',6);
				if ($row['Arbitre_principal']=='-1')
					$pdf->Cell(45,5, '','TBR',0,'C');
				else
					$pdf->Cell(45,5, $row['Arbitre_principal'],'TBR',0,'C');
				if ($row['Arbitre_secondaire']=='-1')
					$pdf->Cell(45,5, '','TBR',1,'C');
				else
					$pdf->Cell(45,5, $row['Arbitre_secondaire'],'TBR',1,'C');
				break;
			default :
				$heure2 = $row['Heure_match'];
				if($heure1 == $heure2)
					$ltbr = '';
				else
					$ltbr = 'T';
				$heure1 = $heure2;
				$pdf->SetFont('Arial','',8);
				//$pdf->Cell(22,5, '',0,0,'C');
				$pdf->Cell(8,5, $row['Numero_ordre'],'LR'.$ltbr,'0','C');
				$pdf->Cell(10,5, $row['Heure_match'],'R'.$ltbr,'0','C');
				$pdf->Cell(17,5, $row['Code_competition'],'R'.$ltbr,'0','C');
				if ($PhaseLibelle == 1)
					$pdf->Cell(50,5, $phase_match,'R'.$ltbr,'0','C');
				else
					$pdf->Cell(50,5, html_entity_decode($row['Lieu']),'R'.$ltbr,'0','C');
				$pdf->Cell(12,5, $row['Terrain'],'R'.$ltbr,'0','C');
				$pdf->Cell(35,5, $row['EquipeA'],'R'.$ltbr,'0','C');
				if ($row['ScoreA']!='?' && $row['Validation'] == 'O')
					$pdf->Cell(7,5, $row['ScoreA'],'R'.$ltbr,'0','C');
				else
					$pdf->Cell(7,5, "",'R'.$ltbr,'0','C');
				if ($row['ScoreB']!='?' && $row['Validation'] == 'O')
					$pdf->Cell(7,5, $row['ScoreB'],'R'.$ltbr,'0','C');
				else
					$pdf->Cell(7,5, "",'R'.$ltbr,'0','C');
				$pdf->Cell(35,5, $row['EquipeB'],'R'.$ltbr,0,'C');
				$pdf->SetFont('Arial','I',6);
				if ($row['Arbitre_principal']=='-1')
					$pdf->Cell(46,5, '','R'.$ltbr,0,'C');
				else
					$pdf->Cell(46,5, $row['Arbitre_principal'],'R'.$ltbr,0,'C');
				if ($row['Arbitre_secondaire']=='-1')
					$pdf->Cell(46,5, '','R'.$ltbr,1,'C');
				else
					$pdf->Cell(46,5, $row['Arbitre_secondaire'],'R'.$ltbr,1,'C');
				break;
			}
		}
		//$pdf->Cell(22,3, '',0,0,'C');
		$pdf->Cell(271,3,'','T','1','C');
		$pdf->Output('Game list'.'.pdf','I');
	}
}

$page = new PdfListeMatchs();
