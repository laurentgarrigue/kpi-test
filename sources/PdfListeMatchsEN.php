<?php
include_once('commun/MyPage.php');
include_once('commun/MyBdd.php');
include_once('commun/MyTools.php');

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
		$this->Cell(136,5,"Print ".date("Y-m-d")."   ".date("H:i", strtotime($_SESSION['tzOffset'])),0,1,'R');
	}
}
 
// Liste des Matchs d'une Journee ou d'un Evenement 
class PdfListeMatchs extends MyPage	 
{	
	function __construct()
	{
		MyPage::MyPage();
  	// Chargement des Matchs des journées ...
 		$filtreJour = utyGetSession('filtreJour', '');
		$filtreJour = utyGetPost('filtreJour', $filtreJour);
		$filtreJour = utyGetGet('filtreJour', $filtreJour);
		
		$filtreTerrain = utyGetSession('filtreTerrain', '');
		$filtreTerrain = utyGetPost('filtreTerrain', $filtreTerrain);
		$filtreTerrain = utyGetGet('filtreTerrain', $filtreTerrain);

		$myBdd = new MyBdd();
		$codeSaison = $myBdd->GetActiveSaison();
		$codeSaison = utyGetGet('S', $codeSaison);
		$lstJournee = utyGetSession('lstJournee', 0);
		$idEvenement = utyGetSession('idEvenement', -1);
		$idEvenement = utyGetGet('idEvenement', $idEvenement);
		$laCompet = utyGetSession('codeCompet', 0);
		$laCompet = utyGetGet('Compet', $laCompet);
        $Group = utyGetGet('Group', '');
		$orderMatchs = utyGetSession('orderMatchs', 'ORDER BY a.Date_match, d.Lieu, a.Heure_match, a.Terrain');
        $titreEvenementCompet = '';

        if($idEvenement > 0) {
			$lstJournee = [];
			$sql = "SELECT Id_journee 
                FROM gickp_Evenement_Journees 
                WHERE Id_evenement = ? ";
            $result = $myBdd->pdo->prepare($sql);
            $result->execute(array($idEvenement));
            while ($row = $result->fetch()) {
                $lstJournee[] = $row['Id_journee'];
			}
            $in  = str_repeat('?,', count($lstJournee) - 1) . '?';
            $sql = "SELECT a.Id, a.Id_journee, a.Id_equipeA, a.Id_equipeB, a.Numero_ordre, a.Date_match, 
                a.Heure_match, a.Libelle, a.Terrain, ce1.Libelle EquipeA, ce2.Libelle EquipeB, a.Terrain, 
                a.ScoreA, a.ScoreB, a.Arbitre_principal, a.Arbitre_secondaire, a.Matric_arbitre_principal, 
                a.Matric_arbitre_secondaire, a.Validation, d.Code_competition, d.Phase, d.Niveau, d.Lieu, 
                d.Libelle LibelleJournee, e.Nom Nom_arb_prin, e.Prenom Prenom_arb_prin, f.Nom Nom_arb_sec, 
                f.Prenom Prenom_arb_sec, c.Soustitre2 
                FROM gickp_Competitions c, gickp_Matchs a 
                LEFT OUTER JOIN gickp_Journees d ON (a.Id_journee = d.Id)
                LEFT OUTER JOIN gickp_Competitions_Equipes ce1 ON (a.Id_equipeA = ce1.Id) 
                LEFT OUTER JOIN gickp_Competitions_Equipes ce2 ON (a.Id_equipeB = ce2.Id) 
                LEFT OUTER JOIN gickp_Liste_Coureur e ON (a.Matric_arbitre_principal = e.Matric) 
                LEFT OUTER JOIN gickp_Liste_Coureur f ON (a.Matric_arbitre_secondaire = f.Matric) 
                WHERE c.Code = d.Code_competition
                AND c.Code_saison = d.Code_saison 
                AND a.Publication = 'O' 
                AND a.Id_journee IN ($in) ";
            $merge = $lstJournee;
            if ($filtreJour != '') {
                $sql .= "AND a.Date_match = ? ";
                $merge = array_merge($merge, [$filtreJour]);
            }
            if($filtreTerrain != '') {
                $sql .= "AND a.Terrain = ? ";
                $merge = array_merge($merge, [$filtreTerrain]);
            }
            $sql .= $orderMatchs;
            $result = $myBdd->pdo->prepare($sql);
            $result->execute($merge);
        } elseif($laCompet !== 0 && $laCompet !== '*') {
			$lstJournee = 0;
			$idEvenement = -1;
            $sql = "SELECT a.Id, a.Id_journee, a.Id_equipeA, a.Id_equipeB, a.Numero_ordre, a.Date_match, 
                a.Heure_match, a.Libelle, a.Terrain, ce1.Libelle EquipeA, ce2.Libelle EquipeB, a.Terrain, 
                a.ScoreA, a.ScoreB, a.Arbitre_principal, a.Arbitre_secondaire, a.Matric_arbitre_principal, 
                a.Matric_arbitre_secondaire, a.Validation, d.Code_competition, d.Phase, d.Niveau, d.Lieu, 
                d.Libelle LibelleJournee, e.Nom Nom_arb_prin, e.Prenom Prenom_arb_prin, f.Nom Nom_arb_sec, 
                f.Prenom Prenom_arb_sec, c.Soustitre2 
                FROM gickp_Competitions c, gickp_Matchs a 
                LEFT OUTER JOIN gickp_Journees d ON (a.Id_journee = d.Id)
                LEFT OUTER JOIN gickp_Competitions_Equipes ce1 ON (a.Id_equipeA = ce1.Id) 
                LEFT OUTER JOIN gickp_Competitions_Equipes ce2 ON (a.Id_equipeB = ce2.Id) 
                LEFT OUTER JOIN gickp_Liste_Coureur e ON (a.Matric_arbitre_principal = e.Matric) 
                LEFT OUTER JOIN gickp_Liste_Coureur f ON (a.Matric_arbitre_secondaire = f.Matric) 
                WHERE c.Code = d.Code_competition
                AND c.Code_saison = d.Code_saison 
                AND a.Publication = 'O' 
                AND d.Code_competition = ? 
                AND d.Code_saison = ? ";
            $merge = array_merge([$laCompet], [$codeSaison]);
            if($filtreJour != '') {
                $sql .= "AND a.Date_match = ? ";
                $merge = array_merge($merge, [$filtreJour]);
            }
            if($filtreTerrain != '') {
                $sql .= "AND a.Terrain = ? ";
                $merge = array_merge($merge, [$filtreTerrain]);
            }
            $sql .= $orderMatchs;
            $result = $myBdd->pdo->prepare($sql);
            $result->execute($merge);
		} else {
			$lstCompet = [];
            $sql = "SELECT c.Code, g.Libelle 
                FROM gickp_Competitions c, gickp_Competitions_Groupes g 
                WHERE c.Code_saison = '" . $codeSaison . "' 
                AND c.Code_ref = '" . $Group . "' 
                AND c.Code_ref = g.Groupe ";
            $result = $myBdd->pdo->prepare($sql);
            $result->execute($merge);
            while ($row = $result->fetch()) {
                $lstCompet[] = $row['Code'];
                $laCompet = $row['Code'];
                $titreEvenementCompet = $row['Libelle'];
		    }
            $in  = str_repeat('?,', count($lstCompet) - 1) . '?';
            $sql = "SELECT a.Id, a.Id_journee, a.Id_equipeA, a.Id_equipeB, a.Numero_ordre, a.Date_match, 
                a.Heure_match, a.Libelle, a.Terrain, ce1.Libelle EquipeA, ce2.Libelle EquipeB, a.Terrain, 
                a.ScoreA, a.ScoreB, a.Arbitre_principal, a.Arbitre_secondaire, a.Matric_arbitre_principal, 
                a.Matric_arbitre_secondaire, a.Validation, d.Code_competition, d.Phase, d.Niveau, d.Lieu, 
                d.Libelle LibelleJournee, e.Nom Nom_arb_prin, e.Prenom Prenom_arb_prin, f.Nom Nom_arb_sec, 
                f.Prenom Prenom_arb_sec, c.Soustitre2 
                FROM gickp_Competitions c, gickp_Matchs a 
                LEFT OUTER JOIN gickp_Journees d ON (a.Id_journee = d.Id)
                LEFT OUTER JOIN gickp_Competitions_Equipes ce1 ON (a.Id_equipeA = ce1.Id) 
                LEFT OUTER JOIN gickp_Competitions_Equipes ce2 ON (a.Id_equipeB = ce2.Id) 
                LEFT OUTER JOIN gickp_Liste_Coureur e ON (a.Matric_arbitre_principal = e.Matric) 
                LEFT OUTER JOIN gickp_Liste_Coureur f ON (a.Matric_arbitre_secondaire = f.Matric) 
                WHERE c.Code = d.Code_competition
                AND c.Code_saison = d.Code_saison 
                AND a.Publication = 'O' 
                AND d.Code_competition IN ($in) 
                AND d.Code_saison = ? ";
            $merge = array_merge($lstCompet, [$codeSaison]);
            if($filtreJour != '') {
                $sql .= "AND a.Date_match = ? ";
                $merge = array_merge($merge, [$filtreJour]);
            }
            if($filtreTerrain != '') {
                $sql .= "AND a.Terrain = ? ";
                $merge = array_merge($merge, [$filtreTerrain]);
            }
            $sql .= $orderMatchs;
            $result = $myBdd->pdo->prepare($sql);
            $result->execute($merge);
        }
		$codeCompet = $laCompet;
		
		$orderMatchsKey1 = utyKeyOrder($orderMatchs, 0);
        $num_results = $result->rowCount();
        
        $PhaseLibelle = 0;
        $resultarray = $result->fetchAll(PDO::FETCH_BOTH);
        foreach ($resultarray as $key => $row1) {
			if (trim($row1['Phase']) != '') {
				$PhaseLibelle = 1;
            }
			$lastCompetEvt = $row1['Code_competition'];
		}
		$Oldrupture = "";
		// Chargement des infos de l'évènement ou de la compétition
		if ($idEvenement > 0) {
			$libelleEvenement = $myBdd->GetEvenementLibelle($idEvenement);
			$titreEvenementCompet = 'Event : '.$libelleEvenement;
			$arrayCompetition = $myBdd->GetCompetition($lastCompetEvt, $codeSaison);
		} elseif($titreEvenementCompet == '') {
			$arrayCompetition = $myBdd->GetCompetition($codeCompet, $codeSaison);
			if ($arrayCompetition['Titre_actif'] == 'O') {
				$titreEvenementCompet = $arrayCompetition['Libelle'];
            } else {
				$titreEvenementCompet = $arrayCompetition['Soustitre'];
            }
            if ($arrayCompetition['Soustitre2'] != '') {
                $titreEvenementCompet .= ' - ' . $arrayCompetition['Soustitre2'];
            }
		} else {
			$arrayCompetition = $myBdd->GetCompetition($codeCompet, $codeSaison);
		}

        $visuels = utyGetVisuels($arrayCompetition, FALSE);

		// Entête PDF ...	  
 		$pdf = new PDF('L');
		$pdf->Open();

		$pdf->SetTitle("Game list");
		$pdf->SetAuthor("Kayak-polo.info");
		$pdf->SetCreator("Kayak-polo.info width FPDF");
		$pdf->SetTopMargin(30);
		$pdf->AddPage();
		if ($arrayCompetition['Sponsor_actif'] == 'O' && isset($visuels['sponsor'])) {
			$pdf->SetAutoPageBreak(true, 28);
        } else {
			$pdf->SetAutoPageBreak(true, 15);
        }
		// Affichage
        $qr_x = 262;
        // Bandeau
        if($arrayCompetition['Bandeau_actif'] == 'O' && isset($visuels['bandeau'])){
            if (is_file($visuels['bandeau'])) {
                $img = redimImage($visuels['bandeau'], 262, 10, 20, 'C');
                $pdf->Image($img['image'], $img['positionX'], 8, 0, $img['newHauteur']);
            }
        // KPI + Logo    
        } elseif($arrayCompetition['Kpi_ffck_actif'] == 'O' && $arrayCompetition['Logo_actif'] == 'O' && isset($visuels['logo'])) {
            $pdf->Image('img/logoKPI-small.jpg', 40, 10, 0, 20, 'jpg', "https://www.kayak-polo.info");
            if (is_file($visuels['logo'])) {
                $img = redimImage($visuels['logo'], 262, 10, 20, 'R');
                $pdf->Image($img['image'], $img['positionX'], 8, 0, $img['newHauteur']);
            }
        // KPI
        } elseif($arrayCompetition['Kpi_ffck_actif'] == 'O') {
            $pdf->Image('img/logoKPI-small.jpg', 125, 10, 0, 20, 'jpg', "https://www.kayak-polo.info");
        // Logo
        } elseif($arrayCompetition['Logo_actif'] == 'O' && isset($visuels['logo'])){
            if (is_file($visuels['logo'])) {
                $img = redimImage($visuels['logo'], 262, 10, 20, 'C');
                $pdf->Image($img['image'], $img['positionX'], 8, 0, $img['newHauteur']);
            }
        }
        // Sponsor
        if($arrayCompetition['Sponsor_actif'] == 'O' && isset($visuels['sponsor'])){
            if (is_file($visuels['sponsor'])) {
                $img = redimImage($visuels['sponsor'], 297, 10, 16, 'C');
                $pdf->Image($img['image'], $img['positionX'], 184, 0, $img['newHauteur']);
            }
        }

		// QRCode
		$qrcode = new QRcode('https://www.kayak-polo.info/kpmatchs.php?Compet='.$codeCompet.'&Group='.$arrayCompetition['Code_ref'].'&Saison='.$codeSaison.'&lang=en', 'L'); // error level : L, M, Q, H
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
        
        foreach ($resultarray as $key => $row) {
			
			if ($row['Soustitre2'] != '') {
				$row['Code_competition'] = $row['Soustitre2'];
            }
			$phase_match = $row['Phase'];
			if ($row['Libelle'] != '')
			{
				$libelle = explode(']', $row['Libelle']);
				if ($libelle[1] != '') {
                    $phase_match .= "  |  " . $libelle[1];
                }
				//Codes équipes	
				$EquipesAffectAuto = utyEquipesAffectAuto($row['Libelle']);
			}
			if ($row['Id_equipeA'] >= 1) {
				$myBdd->InitTitulaireEquipe('A', $row['Id'], $row['Id_equipeA']);
            } elseif (isset($EquipesAffectAuto[0]) && $EquipesAffectAuto[0] != '') {
				$row['EquipeA'] = $EquipesAffectAuto[0];
            }
            if ($row['Id_equipeB'] >= 1) {
				$myBdd->InitTitulaireEquipe('B', $row['Id'], $row['Id_equipeB']);
            } elseif (isset($EquipesAffectAuto[1]) && $EquipesAffectAuto[1] != '') {
				$row['EquipeB'] = $EquipesAffectAuto[1];
            }
			if ($row['Arbitre_principal'] != '' && $row['Arbitre_principal'] != '-1') {
				$row['Arbitre_principal'] = utyArbSansNiveau(
                    utyInitialesPrenomArbitre( 
                            $row['Arbitre_principal'], $row['Nom_arb_prin'], 
                            $row['Prenom_arb_prin'], $row['Matric_arbitre_principal']));
            } elseif (isset($EquipesAffectAuto[2]) && $EquipesAffectAuto[2] != '') {
				$row['Arbitre_principal'] = $EquipesAffectAuto[2];
            }
            if ($row['Arbitre_secondaire'] != '' && $row['Arbitre_secondaire'] != '-1') {
				$row['Arbitre_secondaire'] = utyArbSansNiveau(
                    utyInitialesPrenomArbitre( 
                            $row['Arbitre_secondaire'], $row['Nom_arb_sec'], 
                            $row['Prenom_arb_sec'], $row['Matric_arbitre_secondaire']));
            } elseif (isset($EquipesAffectAuto[3]) && $EquipesAffectAuto[3] != '') {
				$row['Arbitre_secondaire'] = $EquipesAffectAuto[3];
            }
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
						$pdf->Cell(273,3,'','T','1','C');
						$pdf->AddPage();
                        // Bandeau
                        if($arrayCompetition['Bandeau_actif'] == 'O' && isset($visuels['bandeau'])){
                            if (is_file($visuels['bandeau'])) {
                                $img = redimImage($visuels['bandeau'], 297, 10, 20, 'C');
                                $pdf->Image($img['image'], $img['positionX'], 8, 0, $img['newHauteur']);
                            }
                        // KPI + Logo    
                        } elseif($arrayCompetition['Kpi_ffck_actif'] == 'O' && $arrayCompetition['Logo_actif'] == 'O' && isset($visuels['logo'])) {
                            $pdf->Image('img/logoKPI-small.jpg', 10, 10, 0, 20, 'jpg', "https://www.kayak-polo.info");
                            if (is_file($visuels['logo'])) {
                                $img = redimImage($visuels['logo'], 297, 10, 20, 'R');
                                $pdf->Image($img['image'], $img['positionX'], 8, 0, $img['newHauteur']);
                            }
                        // KPI
                        } elseif($arrayCompetition['Kpi_ffck_actif'] == 'O') {
                            $pdf->Image('img/logoKPI-small.jpg', 125, 10, 0, 20, 'jpg', "https://www.kayak-polo.info");
                        // Logo
                        } elseif($arrayCompetition['Logo_actif'] == 'O' && isset($visuels['logo'])){
                            if (is_file($visuels['logo'])) {
                                $img = redimImage($visuels['logo'], 297, 10, 20, 'C');
                                $pdf->Image($img['image'], $img['positionX'], 8, 0, $img['newHauteur']);
                            }
                        }
                        // Sponsor
                        if($arrayCompetition['Sponsor_actif'] == 'O' && isset($visuels['sponsor'])){
                            if (is_file($visuels['sponsor'])) {
                                $img = redimImage($visuels['sponsor'], 297, 10, 16, 'C');
                                $pdf->Image($img['image'], $img['positionX'], 184, 0, $img['newHauteur']);
                            }
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
                            if ($PhaseLibelle == 1) {
                                $pdf->Cell(52, 5, 'Phase | Game', 'TRB', '0', 'C');
                            } else {
                                $pdf->Cell(52, 5, 'Place', 'TRB', '0', 'C');
                            }
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
                            if ($PhaseLibelle == 1) {
                                $pdf->Cell(50, 5, 'Phase | Game', 'TRB', '0', 'C');
                            } else {
                                $pdf->Cell(50, 5, 'Place', 'TRB', '0', 'C');
                            }
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
                            if ($PhaseLibelle == 1) {
                                $pdf->Cell(50, 5, 'Phase | Game', 'TRB', '0', 'C');
                            } else {
                                $pdf->Cell(50, 5, 'Place', 'TRB', '0', 'C');
                            }
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
                    if ($PhaseLibelle == 1) {
                            $pdf->Cell(52, 5, $phase_match, 'TBR', '0', 'C');
                        } else {
                            $pdf->Cell(52, 5, html_entity_decode($row['Lieu']), 'TRB', '0', 'C');
                        }
                    $pdf->Cell(11,5, $row['Terrain'],'TBR','0','C');
                    $pdf->Cell(35,5, $row['EquipeA'],'TBR','0','C');
                    if ($row['ScoreA'] != '?' && $row['Validation'] == 'O') {
                            $pdf->Cell(7, 5, $row['ScoreA'], 'TBR', '0', 'C');
                        } else {
                            $pdf->Cell(7, 5, "", 'TBR', '0', 'C');
                        }
                        if ($row['ScoreB'] != '?' && $row['Validation'] == 'O') {
                            $pdf->Cell(7, 5, $row['ScoreB'], 'TBR', '0', 'C');
                        } else {
                            $pdf->Cell(7, 5, "", 'TBR', '0', 'C');
                        }
                    $pdf->Cell(35,5, $row['EquipeB'],'TBR',0,'C');
                    $pdf->SetFont('Arial','I',6);
                    if ($row['Arbitre_principal'] == '-1') {
                            $pdf->Cell(45, 5, '', 'TBR', 0, 'C');
                        } else {
                            $pdf->Cell(45, 5, $row['Arbitre_principal'], 'TBR', '0', 'C');
                        }
                        if ($row['Arbitre_secondaire'] == '-1') {
                            $pdf->Cell(45, 5, '', 'TBR', 1, 'C');
                        } else {
                            $pdf->Cell(45, 5, $row['Arbitre_secondaire'], 'TBR', 1, 'C');
                        }
                    break;
                case "Terrain" :
                    $pdf->SetFont('Arial','',8);
                    //$pdf->Cell(22,5, '',0,0,'C');
                    $pdf->Cell(8,5, $row['Numero_ordre'],'LTBR','0','C');
                    $pdf->Cell(16,5, utyDateUsToFr($row['Date_match']),'TBR','0','C');
                    $pdf->Cell(10,5, $row['Heure_match'],'TBR','0','C');
                    $pdf->Cell(17,5, $row['Code_competition'],'TBR','0','C');
                    if ($PhaseLibelle == 1) {
                        $pdf->Cell(50, 5, $phase_match, 'TBR', '0', 'C');
                    } else {
                        $pdf->Cell(50, 5, html_entity_decode($row['Lieu']), 'TRB', '0', 'C');
                    }
                    $pdf->Cell(35,5, $row['EquipeA'],'TBR','0','C');

                    if ($row['ScoreA'] != '?' && $row['Validation'] == 'O') {
                        $pdf->Cell(7, 5, $row['ScoreA'], 'TBR', '0', 'C');
                    } else {
                        $pdf->Cell(7, 5, "", 'TBR', '0', 'C');
                    }
                    if ($row['ScoreB'] != '?' && $row['Validation'] == 'O') {
                        $pdf->Cell(7, 5, $row['ScoreB'], 'TBR', '0', 'C');
                    } else {
                        $pdf->Cell(7, 5, "", 'TBR', '0', 'C');
                    }
                    $pdf->Cell(35,5, $row['EquipeB'],'TBR',0,'C');
                    $pdf->SetFont('Arial','I',6);
                    if ($row['Arbitre_principal'] == '-1') {
                        $pdf->Cell(45, 5, '', 'TBR', 0, 'C');
                    } else {
                        $pdf->Cell(45, 5, $row['Arbitre_principal'], 'TBR', 0, 'C');
                    }
                    if ($row['Arbitre_secondaire'] == '-1') {
                        $pdf->Cell(45, 5, '', 'TBR', 1, 'C');
                    } else {
                        $pdf->Cell(45, 5, $row['Arbitre_secondaire'], 'TBR', 1, 'C');
                    }
                    break;
                default :
                    $heure2 = $row['Heure_match'];
                    if ($heure1 == $heure2) {
                        $ltbr = '';
                    } else {
                        $ltbr = 'T';
                    }
                    $heure1 = $heure2;
                    $pdf->SetFont('Arial','',8);
                    //$pdf->Cell(22,5, '',0,0,'C');
                    $pdf->Cell(8,5, $row['Numero_ordre'],'LR'.$ltbr,'0','C');
                    $pdf->Cell(10,5, $row['Heure_match'],'R'.$ltbr,'0','C');
                    $pdf->Cell(17,5, $row['Code_competition'],'R'.$ltbr,'0','C');
                    if ($PhaseLibelle == 1) {
                        $pdf->Cell(50, 5, $phase_match, 'R' . $ltbr, '0', 'C');
                    } else {
                        $pdf->Cell(50, 5, html_entity_decode($row['Lieu']), 'R' . $ltbr, '0', 'C');
                    }
                    $pdf->Cell(12,5, $row['Terrain'],'R'.$ltbr,'0','C');
                    $pdf->Cell(35,5, $row['EquipeA'],'R'.$ltbr,'0','C');
                    if ($row['ScoreA'] != '?' && $row['Validation'] == 'O') {
                        $pdf->Cell(7, 5, $row['ScoreA'], 'R' . $ltbr, '0', 'C');
                    } else {
                        $pdf->Cell(7, 5, "", 'R' . $ltbr, '0', 'C');
                    }
                    if ($row['ScoreB'] != '?' && $row['Validation'] == 'O') {
                        $pdf->Cell(7, 5, $row['ScoreB'], 'R' . $ltbr, '0', 'C');
                    } else {
                        $pdf->Cell(7, 5, "", 'R' . $ltbr, '0', 'C');
                    }
                    $pdf->Cell(35,5, $row['EquipeB'],'R'.$ltbr,0,'C');
                    $pdf->SetFont('Arial','I',6);
                    if ($row['Arbitre_principal'] == '-1') {
                        $pdf->Cell(46, 5, '', 'R' . $ltbr, 0, 'C');
                    } else {
                        $pdf->Cell(46, 5, $row['Arbitre_principal'], 'R' . $ltbr, 0, 'C');
                    }
                    if ($row['Arbitre_secondaire'] == '-1') {
                        $pdf->Cell(46, 5, '', 'R' . $ltbr, 1, 'C');
                    } else {
                        $pdf->Cell(46, 5, $row['Arbitre_secondaire'], 'R' . $ltbr, 1, 'C');
                    }
                    break;
			}
		}
		//$pdf->Cell(22,3, '',0,0,'C');
		$pdf->Cell(273,3,'','T','1','C');
		$pdf->Output('Game list'.'.pdf','I');
	}
}

$page = new PdfListeMatchs();
