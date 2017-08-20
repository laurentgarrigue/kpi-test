<?php

include_once('../commun/MyPage.php');
include_once('../commun/MyBdd.php');
include_once('../commun/MyTools.php');

require('../fpdf/fpdf.php');

// Gestion de la Feuille de Classement

class FeuilleCltNiveau extends MyPage	 
{	
	function FeuilleCltNiveau()
	{
		MyPage::MyPage();

		$myBdd = new MyBdd();
		
		$codeCompet = utyGetSession('codeCompet', '');
		//Saison
		$codeSaison = utyGetSaison();
        $titreDate = "Saison ".$codeSaison;
        
		$arrayCompetition = $myBdd->GetCompetition($codeCompet, $codeSaison);

		$titreCompet = 'Compétition : '.$arrayCompetition['Libelle'].' ('.$codeCompet.')';
		$qualif = $arrayCompetition['Qualifies'];
		$elim = $arrayCompetition['Elimines'];
        if($arrayCompetition['BandeauLink'] != '' && strpos($arrayCompetition['BandeauLink'], 'http') === FALSE ){
            $arrayCompetition['BandeauLink'] = '../img/logo/' . $arrayCompetition['BandeauLink'];
            if(is_file($arrayCompetition['BandeauLink'])) {
                $bandeau = $arrayCompetition['BandeauLink'];
            }
        } elseif($arrayCompetition['BandeauLink'] != '') {
            $bandeau = $arrayCompetition['BandeauLink'];
        } 
        if($arrayCompetition['LogoLink'] != '' && strpos($arrayCompetition['LogoLink'], 'http') === FALSE ){
            $arrayCompetition['LogoLink'] = '../img/logo/' . $arrayCompetition['LogoLink'];
            if(is_file($arrayCompetition['LogoLink'])) {
                $logo = $arrayCompetition['LogoLink'];
            }
        } elseif($arrayCompetition['LogoLink'] != '') {
            $logo = $arrayCompetition['LogoLink'];
        }
        
        if($arrayCompetition['SponsorLink'] != '' && strpos($arrayCompetition['SponsorLink'], 'http') === FALSE ){
            $arrayCompetition['SponsorLink'] = '../img/logo/' . $arrayCompetition['SponsorLink'];
            if(is_file($arrayCompetition['SponsorLink'])) {
                $sponsor = $arrayCompetition['SponsorLink'];
            }
        } elseif($arrayCompetition['SponsorLink'] != '') {
            $sponsor = $arrayCompetition['SponsorLink'];
        }

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
		$pdf = new FPDF('L');
		$pdf->Open();
		$pdf->SetTitle("Classement general");
		
		$pdf->SetAuthor("kayak-polo.info");
		$pdf->SetCreator("kayak-polo.info avec FPDF");
		$pdf->AddPage();
		if($arrayCompetition['Sponsor_actif'] == 'O' && isset($sponsor)){
			$pdf->SetAutoPageBreak(true, 30);
        } else {
			$pdf->SetAutoPageBreak(true, 15);
        }
        
		// logo
		if($arrayCompetition['Kpi_ffck_actif'] == 'O')
		{
			$pdf->Image('../img/logoKPI-small.jpg',125,10,0,20,'jpg',"http://www.ffck.org");
		}

		if($arrayCompetition['Bandeau_actif'] == 'O' && isset($bandeau)){
			$size = getimagesize($bandeau);
			$largeur=$size[0];
			$hauteur=$size[1];
			$ratio=20/$hauteur;	//hauteur imposée de 20mm
			$newlargeur=$largeur*$ratio;
			$posi=149-($newlargeur/2);	//210mm = largeur de page
			$pdf->image($bandeau, $posi, 8, 0,20);
		} elseif($arrayCompetition['Logo_actif'] == 'O' && isset($logo)){
			$size = getimagesize($logo);
			$largeur=$size[0];
			$hauteur=$size[1];
			$ratio=20/$hauteur;	//hauteur imposée de 20mm
			$newlargeur=$largeur*$ratio;
			$posi=149-($newlargeur/2);	//210mm = largeur de page
			$pdf->image($logo, $posi, 8, 0,20);
		}

		if($arrayCompetition['Sponsor_actif'] == 'O' && isset($sponsor)){
			$size = getimagesize($sponsor);
			$largeur=$size[0];
			$hauteur=$size[1];
			$ratio=16/$hauteur;	//hauteur imposée de 16mm
			$newlargeur=$largeur*$ratio;
			$posi=149-($newlargeur/2);	//210mm = largeur de page
			$pdf->image($sponsor, $posi, 180, 0,16);
		}

		// titre
		$pdf->Ln(22);



		$pdf->SetFont('Arial','B',14);
		if($arrayCompetition['Titre_actif'] == 'O')
			$pdf->Cell(273,5,$arrayCompetition['Libelle'],0,1,'C');
		else
			$pdf->Cell(273,5,$arrayCompetition['Soustitre'],0,1,'C');

		$pdf->Ln(4);
		if($arrayCompetition['Soustitre2'] != '')	
			$pdf->Cell(273,5,$arrayCompetition['Soustitre2'],0,1,'C');
//		else	

//			$pdf->Cell(273,5,$categorie,0,1,'C');
		$pdf->Ln(4);
		$pdf->SetFont('Arial','BI',10);
		$pdf->Cell(273,5,$lang['CLASSEMENT_PROVISOIRE'],0,0,'C');
		$pdf->Ln(10);
		
		//données

		$sql  = "Select Id, Libelle, Code_club, Clt, Pts, J, G, N, P, F, Plus, Moins, Diff, PtsNiveau, CltNiveau ";
		$sql .= "From gickp_Competitions_Equipes ";
		$sql .= "Where Code_compet = '";
		$sql .= $codeCompet;
		$sql .= "' And Code_saison = '";
		$sql .= $codeSaison;

		$sql .= "' Order By Clt Asc, Diff Desc ";	 
	
		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load");
		$num_results = mysql_num_rows($result);
		
		// recalcul des éliminés
		$elim = $num_results - $elim;


		$pdf->SetFont('Arial','BI',11);

		$pdf->Cell(16,5, '', 0,0);
		$pdf->Cell(20,5, 'Rang', 'B',0,'C');
		$pdf->Cell(55,5, 'Equipe','B',0,'L');
		$pdf->Cell(20,5, 'Points','B',0,'C');
		$pdf->Cell(18,5, 'Joués','B',0,'C');
		$pdf->Cell(18,5, 'Gagnés','B',0,'C');
		$pdf->Cell(18,5, 'Nuls','B',0,'C');
		$pdf->Cell(18,5, 'Perdus','B',0,'C');
		$pdf->Cell(18,5, 'Forfaits','B',0,'C');
		$pdf->Cell(18,5, '+','B',0,'C');
		$pdf->Cell(18,5, '-','B',0,'C');
		$pdf->Cell(20,5, '+/-','B',1,'C');






		for ($i=0;$i<$num_results;$i++)
		{
				$row = mysql_fetch_array($result);	
				$separation = 0;
				//Séparation qualifiés
				if (($i+1) > $qualif && $qualif != 0)
				{
					$pdf->Cell(16,5, '', 0,0);
					$pdf->Cell(241,1, '', 0,1);
					$qualif =0;
					$separation = 1;
				}
				//Séparation éliminés
				if (($i+1) > $elim && $elim != 0)
				{
					if ($separation != 1)
					{
						$pdf->Cell(16,5, '', 0,0);
						$pdf->Cell(241,1, '', 0,1);
					}
					$elim =0;
				}
					
				
				$pts = $row['Pts'];
				$len = strlen($pts);
				if ($len > 2)
				{
					if (substr($pts, $len-2, 2) == '00')
						$pts = substr($pts, 0, $len-2);
					else
						$pts = substr($pts, 0, $len-2).'.'.substr($pts, $len-2, 2);
				}
				
				$pdf->Cell(16, 6, '', 0,0);

				$pdf->SetFont('Arial','',11);
				$pdf->Cell(20, 6, $row['Clt'].'.', 'B',0,'C');
				$pdf->Cell(55, 6, $row['Libelle'],'B',0,'L');
				$pdf->Cell(20, 6, $pts,'B',0,'C');
				$pdf->SetFont('Arial','',11);
				$pdf->Cell(18, 6, $row['J'],'B',0,'C');
				$pdf->Cell(18, 6, $row['G'],'B',0,'C');
				$pdf->Cell(18, 6, $row['N'],'B',0,'C');
				$pdf->Cell(18, 6, $row['P'],'B',0,'C');
				$pdf->Cell(18, 6, $row['F'],'B',0,'C');
				$pdf->Cell(18, 6, $row['Plus'],'B',0,'C');
				$pdf->Cell(18, 6, $row['Moins'],'B',0,'C');
				$pdf->Cell(20, 6, $row['Diff'],'B',1,'C');
		}
			
		$pdf->SetFont('Arial','I',8);
        if($arrayCompetition['Sponsor_actif'] == 'O' && isset($sponsor)){
            $pdf->SetXY(250, 175);
        } else {
            $pdf->SetXY(250, 185);
        }
		if($lang == $langue['en'])
			$pdf->Write(4, date('Y-m-d H:i'));
		else
			$pdf->Write(4, date('d/m/Y à H:i'));
		$pdf->Output('Classement '.$codeCompet.'.pdf','I');
	}
}

$page = new FeuilleCltNiveau();
