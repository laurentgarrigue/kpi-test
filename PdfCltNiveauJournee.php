<?phpinclude_once('commun/MyPage.php');include_once('commun/MyBdd.php');include_once('commun/MyTools.php');require('fpdf/fpdf.php');require_once('qrcode/qrcode.class.php');// Gestion de la Feuille de Classement par Journeeclass FeuilleCltNiveauJournee extends MyPage	 {		function FeuilleCltNiveauJournee()	{		MyPage::MyPage();		$myBdd = new MyBdd();	  		$codeCompet = utyGetSession('codeCompet', '');		$codeSaison = utyGetSaison();		//Saison		$titreDate = "Saison ".$codeSaison;        		$arrayCompetition = $myBdd->GetCompetition($codeCompet, $codeSaison);		$titreCompet = 'Compétition : '.$arrayCompetition['Libelle'].' ('.$codeCompet.')';		$qualif = $arrayCompetition['Qualifies'];		$elim = $arrayCompetition['Elimines'];        if($arrayCompetition['BandeauLink'] != '' && strpos($arrayCompetition['BandeauLink'], 'http') === FALSE ){            $arrayCompetition['BandeauLink'] = 'img/logo/' . $arrayCompetition['BandeauLink'];            if(is_file($arrayCompetition['BandeauLink'])) {                $bandeau = $arrayCompetition['BandeauLink'];            }        } elseif($arrayCompetition['BandeauLink'] != '') {            $bandeau = $arrayCompetition['BandeauLink'];        }         if($arrayCompetition['LogoLink'] != '' && strpos($arrayCompetition['LogoLink'], 'http') === FALSE ){            $arrayCompetition['LogoLink'] = 'img/logo/' . $arrayCompetition['LogoLink'];            if(is_file($arrayCompetition['LogoLink'])) {                $logo = $arrayCompetition['LogoLink'];            }        } elseif($arrayCompetition['LogoLink'] != '') {            $logo = $arrayCompetition['LogoLink'];        }                if($arrayCompetition['SponsorLink'] != '' && strpos($arrayCompetition['SponsorLink'], 'http') === FALSE ){            $arrayCompetition['SponsorLink'] = 'img/logo/' . $arrayCompetition['SponsorLink'];            if(is_file($arrayCompetition['SponsorLink'])) {                $sponsor = $arrayCompetition['SponsorLink'];            }        } elseif($arrayCompetition['SponsorLink'] != '') {            $sponsor = $arrayCompetition['SponsorLink'];        }		// Langue		$langue = parse_ini_file("commun/MyLang.ini", true);		if (utyGetGet('lang') == 'en') {            $arrayCompetition['En_actif'] = 'O';        } elseif (utyGetGet('lang') == 'fr') {            $arrayCompetition['En_actif'] = '';        }        if ($arrayCompetition['En_actif'] == 'O') {            $lang = $langue['en'];        } else {            $lang = $langue['fr'];        }        //Création		$pdf = new FPDF('P');		$pdf->Open();		$pdf->SetTitle("Classement par journée");				$pdf->SetAuthor("kayak-polo.info");		$pdf->SetCreator("kayak-polo.info");		$pdf->AddPage();		if($arrayCompetition['Sponsor_actif'] == 'O' && isset($sponsor))			$pdf->SetAutoPageBreak(true, 30);		else			$pdf->SetAutoPageBreak(true, 15);		// logo		if($arrayCompetition['Kpi_ffck_actif'] == 'O')		{			$pdf->Image('img/logoKPI-small.jpg',84,10,0,20,'jpg',"http://www.ffck.org");		}		if($arrayCompetition['Bandeau_actif'] == 'O' && isset($bandeau)){			$size = getimagesize($bandeau);			$largeur=$size[0];			$hauteur=$size[1];			$ratio=20/$hauteur;	//hauteur imposée de 20mm			$newlargeur=$largeur*$ratio;			$posi=105-($newlargeur/2);	//210mm = largeur de page			$pdf->image($bandeau, $posi, 8, 0,20);		} elseif($arrayCompetition['Logo_actif'] == 'O' && isset($logo)){			$size = getimagesize($logo);			$largeur=$size[0];			$hauteur=$size[1];			$ratio=20/$hauteur;	//hauteur imposée de 20mm			$newlargeur=$largeur*$ratio;			$posi=105-($newlargeur/2);	//210mm = largeur de page			$pdf->image($logo, $posi, 8, 0,20);		}		if($arrayCompetition['Sponsor_actif'] == 'O' && isset($sponsor)){			$size = getimagesize($sponsor);			$largeur=$size[0];			$hauteur=$size[1];			$ratio=16/$hauteur;	//hauteur imposée de 16mm			$newlargeur=$largeur*$ratio;			$posi=105-($newlargeur/2);	//210mm = largeur de page			$pdf->image($sponsor, $posi, 267, 0,16);		}		// QRCode		$qrcode = new QRcode('http://www.kayak-polo.info/Classements.php?Compet='.$codeCompet.'&Group='.$arrayCompetition['Code_ref'].'&Saison='.$codeSaison, 'L'); // error level : L, M, Q, H		//$qrcode->displayFPDF($fpdf, $x, $y, $s, $background, $color);		$qrcode->displayFPDF($pdf, 177, 240, 24);		// titre		$pdf->Ln(22);		$pdf->SetFont('Arial','B',14);		if($arrayCompetition['Titre_actif'] == 'O')			$pdf->Cell(190,5,$arrayCompetition['Libelle'],0,1,'C');		else			$pdf->Cell(190,5,$arrayCompetition['Soustitre'],0,1,'C');//		$pdf->Ln(4);		if($arrayCompetition['Soustitre2'] != '')				$pdf->Cell(190,5,$arrayCompetition['Soustitre2'],0,1,'C');		$pdf->Ln(4);		$pdf->SetFont('Arial','BI',10);		$pdf->Cell(190,5,$lang['CLASSEMENT_PAR_JOURNEE'],0,0,'C');		$pdf->Ln(4);		// données		$myBdd = new MyBdd();				$sql  = "Select a.Id, a.Libelle, a.Code_club, ";		$sql .= "b.Id_journee, b.Clt_publi, b.Pts_publi, b.J_publi, b.G_publi, b.N_publi, b.P_publi, b.F_publi, b.Plus_publi, b.Moins_publi, b.Diff_publi, b.PtsNiveau_publi, b.CltNiveau_publi, ";		$sql .= "c.Date_debut, c.Lieu ";		$sql .= "From gickp_Competitions_Equipes a, ";		$sql .= "gickp_Competitions_Equipes_Journee b Join gickp_Journees c On (b.Id_journee = c.Id) ";		$sql .= "Where a.Id = b.Id ";		$sql .= "And c.Code_competition = '";		$sql .= $codeCompet;		$sql .= "' And c.Code_saison = '";		$sql .= utyGetSaison();		$sql .= "' Order By c.Date_debut Asc, c.Lieu ASC, b.Clt_publi Asc, b.Diff_publi Desc, b.Plus_publi Desc ";	 		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load");		$num_results = mysql_num_rows($result);		$idJournee = 0;		for ($i=0;$i<$num_results;$i++)		{			$row = mysql_fetch_array($result);					$idEquipe = $row['Id'];				if ($row['Id_journee'] != $idJournee)				{					$idJournee = $row['Id_journee'];										$pdf->Ln(5);					$pdf->Cell(26,4, '', 0, 0,'C');					$pdf->SetFont('Arial','BI',9);					$pdf->Cell(61,4,utyDateUsToFr($row['Date_debut']).' - '.$row['Lieu'], 'B', 0,'L'); //     "JOURNEE ".$codeCompet.'/'.$idJournee.'/'.					$pdf->SetFont('Arial','BI',9);					$pdf->Cell(8,4, "Pts", 'B', 0,'C');					$pdf->Cell(7,4, "J", 'B', 0,'C');					$pdf->Cell(7,4, "G", 'B', 0,'C');					$pdf->Cell(7,4, "N", 'B', 0,'C');					$pdf->Cell(7,4, "P", 'B', 0,'C');					$pdf->Cell(7,4, "F", 'B', 0,'C');					$pdf->Cell(8,4, "+", 'B', 0,'C');					$pdf->Cell(8,4, "-", 'B', 0,'C');					$pdf->Cell(8,4, "+/-", 'B', 1,'C');				}				$pts = $row['Pts_publi'];				$len = strlen($pts);				if ($len > 2)				{					if (substr($pts, $len-2, 2) == '00')						$pts = substr($pts, 0, $len-2);					else						$pts = substr($pts, 0, $len-2).'.'.substr($pts, $len-2, 2);				}								$pdf->SetFont('Arial','',9);				$pdf->Cell(26,4, '', 0, 0,'C');				$pdf->Cell(61,4, $row['Clt_publi'].'. '.$row['Libelle'], 'B', 0,'L');				$pdf->Cell(8,4, $pts, 'B', 0,'C');				$pdf->Cell(7,4, $row['J_publi'], 'B', 0,'C');				$pdf->Cell(7,4, $row['G_publi'], 'B', 0,'C');				$pdf->Cell(7,4, $row['N_publi'], 'B', 0,'C');				$pdf->Cell(7,4, $row['P_publi'], 'B', 0,'C');				$pdf->Cell(7,4, $row['F_publi'], 'B', 0,'C');				$pdf->Cell(8,4, $row['Plus_publi'], 'B', 0,'C');				$pdf->Cell(8,4, $row['Moins_publi'], 'B', 0,'C');				$pdf->Cell(8,4, $row['Diff_publi'], 'B', 1,'C');		}					$pdf->Output('Classement par journee '.$codeCompet.'.pdf','I');	}}$page = new FeuilleCltNiveauJournee();