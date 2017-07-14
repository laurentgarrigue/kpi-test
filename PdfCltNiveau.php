<?phpinclude_once('commun/MyPage.php');include_once('commun/MyBdd.php');include_once('commun/MyTools.php');define('FPDF_FONTPATH','font/');require('fpdf/fpdf.php');require_once('qrcode/qrcode.class.php');// Gestion de la Feuille de Classementclass FeuilleCltNiveau extends MyPage	 {		function FeuilleCltNiveau()	{		MyPage::MyPage();	  		$myBdd = new MyBdd();				$codeCompet = utyGetSession('codeCompet', '');		$codeSaison = utyGetSaison();		$codeSaison = utyGetGet('S', $codeSaison);		$arrayCompetition = $myBdd->GetCompetition($codeCompet, $codeSaison);		$titreCompet = 'Compétition : '.$arrayCompetition['Libelle'].' ('.$codeCompet.')';		$qualif = $arrayCompetition['Qualifies'];		$elim = $arrayCompetition['Elimines'];		$sponsor = str_replace('http://www.kayak-polo.info/','',$arrayCompetition['SponsorLink']);		$logo = str_replace('http://www.kayak-polo.info/','',$arrayCompetition['LogoLink']);		//		$logo = "img/logo/".$codeSaison.'-'.$codeCompet.'.jpg';		//Saison			$titreDate = "Saison ".$codeSaison;		// Langue		$langue = parse_ini_file("commun/MyLang.ini", true);		if(utyGetGet('lang') == 'en')			$arrayCompetition['En_actif'] = 'O';		elseif(utyGetGet('lang') == 'fr')			$arrayCompetition['En_actif'] = '';					if($arrayCompetition['En_actif'] == 'O')			$lang = $langue['en'];		else			$lang = $langue['fr'];		//Création		$pdf = new FPDF('P');		$pdf->Open();		$pdf->SetTitle("Classement");				$pdf->SetAuthor("kayak-polo.info");		$pdf->SetCreator("kayak-polo.info");		$pdf->AddPage();		if($arrayCompetition['Sponsor_actif'] == 'O' && $sponsor != '')			$pdf->SetAutoPageBreak(true, 30);		else			$pdf->SetAutoPageBreak(true, 15);				// logo		if($arrayCompetition['Kpi_ffck_actif'] == 'O')		{			$pdf->Image('css/banniere1.jpg',10,10,0,12,'jpg',"http://www.kayak-polo.info");			$pdf->Image('img/ffck2.jpg',163,10,0,12,'jpg',"http://www.ffck.org");		}		if($arrayCompetition['Logo_actif'] == 'O' && $logo != '')  //&& file_exists($logo)		{			$size = getimagesize($logo);			$largeur=$size[0];			$hauteur=$size[1];			$ratio=20/$hauteur;	//hauteur imposée de 20mm			$newlargeur=$largeur*$ratio;			$posi=105-($newlargeur/2);	//210mm = largeur de page			$pdf->image($logo, $posi, 8, 0,20);		}		if($arrayCompetition['Sponsor_actif'] == 'O' && $sponsor != '')  //&& file_exists($sponsor)//		if(file_exists($logo))		{			$size = getimagesize($sponsor);			$largeur=$size[0];			$hauteur=$size[1];			$ratio=16/$hauteur;	//hauteur imposée de 16mm			$newlargeur=$largeur*$ratio;			$posi=105-($newlargeur/2);	//210mm = largeur de page			$pdf->image($sponsor, $posi, 267, 0,16);		}		// QRCode		$qrcode = new QRcode('http://www.kayak-polo.info/Classements.php?Compet='.$codeCompet.'&Group='.$arrayCompetition['Code_ref'].'&Saison='.$codeSaison, 'L'); // error level : L, M, Q, H		//$qrcode->displayFPDF($fpdf, $x, $y, $s, $background, $color);		$qrcode->displayFPDF($pdf, 177, 240, 24);		// titre		$pdf->Ln(22);		$pdf->SetFont('Arial','B',14);		if($arrayCompetition['Titre_actif'] == 'O')			$pdf->Cell(190,5,$arrayCompetition['Libelle'],0,1,'C');		else			$pdf->Cell(190,5,$arrayCompetition['Soustitre'],0,1,'C');//		$pdf->Ln(4);		if($arrayCompetition['Soustitre2'] != '')				$pdf->Cell(190,5,$arrayCompetition['Soustitre2'],0,1,'C');		$pdf->Ln(4);		$pdf->SetFont('Arial','BI',10);		$pdf->Cell(190,5,$lang['CLASSEMENT_GENERAL'],0,0,'C');		$pdf->Ln(10);		//données				$sql  = "Select Id, Libelle, Code_club, Clt_publi, Pts_publi, J_publi, G_publi, N_publi, P_publi, F_publi, Plus_publi, Moins_publi, Diff_publi, PtsNiveau_publi, CltNiveau_publi ";		$sql .= "From gickp_Competitions_Equipes ";		$sql .= "Where Code_compet = '";		$sql .= $codeCompet;		$sql .= "' And Code_saison = '";		$sql .= $codeSaison;		$sql .= "' And CltNiveau_publi != 0 ";		$sql .= "Order By CltNiveau_publi Asc, Diff_publi Desc ";	 			$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load");		$num_results = mysql_num_rows($result);				// recalcul des éliminés		$elim = $num_results - $elim;		$pdf->SetFont('Arial','B',13);		$pdf->Cell(55, 6, '','',0,'L');		$pdf->Cell(30, 6, '#', 0,0,'C');		$pdf->Cell(10, 5, '', 0,'0','C'); //Pays		$pdf->Cell(60, 6, $lang['Equipe'],0,1,'L');		$pdf->Ln(4);		for ($i=0;$i<$num_results;$i++)		{			$row = mysql_fetch_array($result);				$separation = 0;			//Séparation qualifiés			if (($i+1) > $qualif && $qualif != 0)			{				$pdf->Ln(2);				$qualif =0;				$separation = 1;			}			//Séparation éliminés			if (($i+1) > $elim && $elim != 0)			{				if ($separation != 1)				{					$pdf->Ln(2);				}				$elim =0;			}						$pdf->SetFont('Arial','B',12);			$pdf->Cell(55, 6, '',0,'0','L');			// médailles			if($row['CltNiveau_publi'] <= 3 && $row['CltNiveau_publi'] != 0 && $arrayCompetition['Code_tour'] == 'F')				$pdf->image('./img/medal'.$row['CltNiveau_publi'].'.gif', $pdf->GetX(), $pdf->GetY()+1, 3, 3);						$pdf->Cell(30, 6, $row['CltNiveau_publi'], 0,'0','C');						// drapeaux			if ($arrayCompetition['Code_niveau'] == 'INT')			{				$pays = substr($row['Code_club'], 0, 3);				if(is_numeric($pays[0]) || is_numeric($pays[1]) || is_numeric($pays[2]))					$pays = 'FRA';				$pdf->image('./img/Pays/'.$pays.'.png', $pdf->GetX(), $pdf->GetY()+1, 7, 4);				$pdf->Cell(10, 6, '', 0,'0','C'); //Pays			}			else				$pdf->Cell(10, 6, '', 0,'0','C');							$pdf->Cell(60,6, $row['Libelle'],0,1,'L');		}					$pdf->SetFont('Arial','I',8);		$pdf->SetXY(165, 270);		if($lang == $langue[EN])			$pdf->Write(4, date('Y-m-d H:i'));		else			$pdf->Write(4, date('d/m/Y à H:i'));					$pdf->Output('Classement '.$codeCompet.'.pdf','I');	}}$page = new FeuilleCltNiveau();