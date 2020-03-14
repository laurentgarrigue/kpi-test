<?php

include_once('../commun/MyPage.php');
include_once('../commun/MyBdd.php');
include_once('../commun/MyTools.php');

require('../fpdf/fpdf.php');

// Gestion de la Feuille de Classement
class FeuilleCltNiveau extends MyPage {

    function __construct() {
        MyPage::MyPage();
        $myBdd = new MyBdd();

        $codeCompet = utyGetSession('codeCompet', '');
        //Saison
        $codeSaison = utyGetSaison();
        $titreDate = "Saison " . $codeSaison;
        $arrayCompetition = $myBdd->GetCompetition($codeCompet, $codeSaison);
        $titreCompet = 'Compétition : ' . $arrayCompetition['Libelle'] . ' (' . $codeCompet . ')';
        $qualif = $arrayCompetition['Qualifies'];
        $elim = $arrayCompetition['Elimines'];

        $visuels = utyGetVisuels($arrayCompetition, TRUE);

        // Langue
        $langue = parse_ini_file("../commun/MyLang.ini", true);
        if (utyGetGet('lang') == 'en') {
            $arrayCompetition['En_actif'] = 'O';
        } elseif (utyGetGet('lang') == 'fr') {
            $arrayCompetition['En_actif'] = '';
        }

        if ($arrayCompetition['En_actif'] == 'O') {
            $lang = $langue['en'];
        } else {
            $lang = $langue['fr'];
        }

        //Création
        $pdf = new FPDF('P');
        $pdf->Open();
        $pdf->SetTitle("Detail par equipe");
        $pdf->SetAuthor("kayak-polo.info");
        $pdf->SetCreator("kayak-polo.info");
        $pdf->AddPage();
        if ($arrayCompetition['Sponsor_actif'] == 'O' && isset($visuels['sponsor'])) {
            $pdf->SetAutoPageBreak(true, 30);
        } else {
            $pdf->SetAutoPageBreak(true, 15);
        }

        // Bandeau
        if ($arrayCompetition['Bandeau_actif'] == 'O' && isset($visuels['bandeau'])) {
            $img = redimImage($visuels['bandeau'], 210, 10, 16, 'C');
            $pdf->Image($img['image'], $img['positionX'], 8, 0, $img['newHauteur']);
            // KPI + Logo    
        } elseif ($arrayCompetition['Kpi_ffck_actif'] == 'O' && $arrayCompetition['Logo_actif'] == 'O' && isset($visuels['logo'])) {
            $pdf->Image('../img/logoKPI-small.jpg', 10, 10, 0, 16, 'jpg', "https://www.kayak-polo.info");
            $img = redimImage($visuels['logo'], 210, 10, 16, 'R');
            $pdf->Image($img['image'], $img['positionX'], 8, 0, $img['newHauteur']);
            // KPI
        } elseif ($arrayCompetition['Kpi_ffck_actif'] == 'O') {
            $pdf->Image('../img/logoKPI-small.jpg', 84, 10, 0, 16, 'jpg', "https://www.kayak-polo.info");
            // Logo
        } elseif ($arrayCompetition['Logo_actif'] == 'O' && isset($visuels['logo'])) {
            $img = redimImage($visuels['logo'], 210, 10, 16, 'C');
            $pdf->Image($img['image'], $img['positionX'], 8, 0, $img['newHauteur']);
        }
        // Sponsor
        if ($arrayCompetition['Sponsor_actif'] == 'O' && isset($visuels['sponsor'])) {
            $img = redimImage($visuels['sponsor'], 210, 10, 16, 'C');
            $pdf->Image($img['image'], $img['positionX'], 267, 0, $img['newHauteur']);
        }

        // titre
        $pdf->Ln(22);
        $pdf->SetFont('Arial', 'B', 14);
        if ($arrayCompetition['Titre_actif'] == 'O') {
            $pdf->Cell(190, 5, $arrayCompetition['Libelle'], 0, 1, 'C');
        } else {
            $pdf->Cell(190, 5, $arrayCompetition['Soustitre'], 0, 1, 'C');
        }
        if ($arrayCompetition['Soustitre2'] != '') {
            $pdf->Cell(190, 5, $arrayCompetition['Soustitre2'], 0, 1, 'C');
        }
        $pdf->Ln(4);
        $pdf->SetFont('Arial', 'BI', 10);
        $pdf->Cell(190, 5, $lang['DETAIL_PAR_EQUIPE'] . ' ' . $lang['PROVISOIRE'], 0, 0, 'C');
        $pdf->Ln(10);

        //données
        $sql = "Select Id, Libelle, Code_club, Clt, Pts, J, G, N, P, F, Plus, Moins, Diff, PtsNiveau, CltNiveau ";
        $sql .= "From gickp_Competitions_Equipes ";
        $sql .= "Where Code_compet = '";
        $sql .= $codeCompet;
        $sql .= "' And Code_saison = '";
        $sql .= $codeSaison;

        $sql .= "' Order By CltNiveau Asc, Diff Desc ";
        $result = $myBdd->Query($sql);
        $num_results = $myBdd->NumRows($result);

        for ($i = 0; $i < $num_results; $i++) {
            $row = $myBdd->FetchArray($result);

            $idEquipe = $row['Id'];
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(55, 6, '', 0, '0', 'L');
            // médailles
            if ($row['CltNiveau'] <= 3 && $row['CltNiveau'] != 0 && $arrayCompetition['Code_tour'] == 'F') {
                $pdf->image('../img/medal' . $row['CltNiveau'] . '.gif', $pdf->GetX(), $pdf->GetY() + 1, 3, 3);
            }
            $pdf->Cell(30, 6, $row['CltNiveau'] . '.', 0, '0', 'C');
            // drapeaux
            if ($arrayCompetition['Code_niveau'] == 'INT') {
                $pays = substr($row['Code_club'], 0, 3);
                if (is_numeric($pays[0]) || is_numeric($pays[1]) || is_numeric($pays[2])) {
                    $pays = 'FRA';
                }
                $pdf->image('../img/Pays/' . $pays . '.png', $pdf->GetX(), $pdf->GetY() + 1, 7, 4);
                $pdf->Cell(10, 6, '', 0, '0', 'C'); //Pays
            } else {
                $pdf->Cell(10, 6, '', 0, '0', 'C');
            }

            $pdf->Cell(60, 6, $row['Libelle'], 0, 1, 'L');
            //Détail
            $sql = "Select a.Id_equipeA, a.ScoreA, c.Libelle LibelleA, ";
            $sql .= "       a.Id_equipeB, a.ScoreB, d.Libelle LibelleB, ";
            $sql .= " a.Id, a.Id_journee, b.Niveau, b.Phase ";
            $sql .= "From gickp_Journees b, gickp_Matchs a ";
            $sql .= "Left Outer Join gickp_Competitions_Equipes c On (c.Id = a.Id_equipeA) ";
            $sql .= "Left Outer Join gickp_Competitions_Equipes d On (d.Id = a.Id_equipeB) ";
            $sql .= "Where a.Id_journee = b.Id ";
            $sql .= "And b.Code_competition = '";
            $sql .= $codeCompet;
            $sql .= "' And b.Code_saison = '";
            $sql .= $codeSaison;
            $sql .= "' And (a.Id_equipeA = $idEquipe Or a.Id_equipeB = $idEquipe) ";

            $sql .= "Order by b.Niveau, b.Phase ";
            $result2 = $myBdd->Query($sql);
            $num_results2 = $myBdd->NumRows($result2);
    
            $oldNiveauPhase = '';
            $pdf->SetFont('Arial', 'B', 10);

            for ($j = 0; $j < $num_results2; $j++) {
                $row2 = $myBdd->FetchArray($result2);
                if (($row2['ScoreA'] == '') || ($row2['ScoreA'] == '?')) {
                    continue;
                } // Score non valide ...
                if (($row2['ScoreB'] == '') || ($row2['ScoreB'] == '?')) {
                    continue;
                } // Score non valide ...
                $niveauPhase = $row2['Niveau'] . '/' . $row2['Phase'];
                if ($niveauPhase != $oldNiveauPhase) {
                    // Rupture Niveau-Phase ...
                    $oldNiveauPhase = $niveauPhase;
                    $pdf->Ln(2);
                    $pdf->SetFont('Arial', 'BI', 10);
                    $pdf->Cell(190, 5, $row2['Phase'], 0, 1, 'C');
                }
                if ($row2['ScoreA'] > $row2['ScoreB']) {
                    $pdf->SetFont('Arial', 'B', 9);
                } else {
                    $pdf->SetFont('Arial', '', 9);
                }
                $pdf->Cell(89, 4, $row2['LibelleA'], 0, 0, 'R');
                $pdf->Cell(5, 4, $row2['ScoreA'], 0, 0, 'C');
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(2, 4, '-', 0, 0, 'C');
                if ($row2['ScoreA'] < $row2['ScoreB']) {
                    $pdf->SetFont('Arial', 'B', 9);
                } else {
                    $pdf->SetFont('Arial', '', 9);
                }
                $pdf->Cell(5, 4, $row2['ScoreB'], 0, 0, 'C');
                $pdf->Cell(89, 4, $row2['LibelleB'], 0, 1, 'L');
            }
            $pdf->Ln(8);
        }

        $pdf->Output('Détail par équipe ' . $codeCompet . '.pdf', 'I');
    }

}

$page = new FeuilleCltNiveau();
