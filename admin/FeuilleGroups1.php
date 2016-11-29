<?phpinclude_once('../commun/MyPage.php');include_once('../commun/MyBdd.php');include_once('../commun/MyTools.php');define('FPDF_FONTPATH','font/');require('../fpdf/fpdf.php');// Pieds de pageclass PDF extends FPDF{	function Footer()	{	    //Positionnement à 1,5 cm du bas	    $this->SetY(-15);	    //Police Arial italique 8	    $this->SetFont('Arial','I',8);	    //Numéro de page centré	    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');	}} // Gestion de la Feuille de Classementclass FeuilleGroups extends MyPage	 {		function FeuilleGroups()	{		MyPage::MyPage();	  		$myBdd = new MyBdd();				$codeCompet = utyGetSession('codeCompet', '');		$codeSaison = utyGetSaison();		$arrayCompetition = $myBdd->GetCompetition($codeCompet, $codeSaison);		$titreCompet = 'Compétition : '.$arrayCompetition['Libelle'].' ('.$codeCompet.')';		//$qualif = $arrayCompetition['Qualifies'];		//$elim = $arrayCompetition['Elimines'];				//Saison			$titreDate = "Saison ".$codeSaison;		// Langue		$langue = parse_ini_file("../commun/MyLang.ini", true);		if($_GET['lang'] == 'en')			$arrayCompetition['En_actif'] = 'O';		elseif($_GET['lang'] == 'fr')			$arrayCompetition['En_actif'] = '';					if($arrayCompetition['En_actif'] == 'O')			$lang = $langue['en'];		else			$lang = $langue['fr'];		//Création		$pdf = new FPDF('P');		$pdf->Open();		$pdf->SetTitle("Groupes");				$pdf->SetAuthor("Poloweb.org");		$pdf->SetCreator("Poloweb.org avec FPDF");		$pdf->AddPage();		if($arrayCompetition['Sponsor_actif'] == 'O' && $sponsor != '')			$pdf->SetAutoPageBreak(true, 40);		else			$pdf->SetAutoPageBreak(true, 15);				// logo		if($arrayCompetition['Kpi_ffck_actif'] == 'O')		{			$pdf->Image('../css/banniere1.jpg',10,10,0,12,'jpg',"http://www.kayak-polo.info");			$pdf->Image('../img/ffck2.jpg',163,10,0,12,'jpg',"http://www.ffck.org");		}		$logo = str_replace('http://www.kayak-polo.info/','../',$arrayCompetition['LogoLink']);		if($arrayCompetition['Logo_actif'] == 'O' && $logo != '')  //&& file_exists($logo)		{			$size = getimagesize($logo);			$largeur=$size[0];			$hauteur=$size[1];			$ratio=20/$hauteur;	//hauteur imposée de 20mm			$newlargeur=$largeur*$ratio;			$posi=105-($newlargeur/2);	//210mm = largeur de page			$pdf->image($logo, $posi, 8, 0,20);		}		$sponsor = str_replace('http://www.kayak-polo.info/','../',$arrayCompetition['SponsorLink']);		if($arrayCompetition['Sponsor_actif'] == 'O' && $sponsor != '')  //&& file_exists($sponsor)		{			$size = getimagesize($sponsor);			$largeur=$size[0];			$hauteur=$size[1];			$ratio=16/$hauteur;	//hauteur imposée de 16mm			$newlargeur=$largeur*$ratio;			$posi=105-($newlargeur/2);	//210mm = largeur de page			$pdf->image($sponsor, $posi, 274, 0,16);		}		// titre		$pdf->Ln(22);		$pdf->SetFont('Arial','B',14);		if($arrayCompetition['Titre_actif'] == 'O')			$pdf->Cell(186,5,$arrayCompetition['Libelle'],0,1,'C');		else			$pdf->Cell(186,5,$arrayCompetition['Soustitre'],0,1,'C');		$pdf->Ln(2);		if($arrayCompetition['Soustitre2'] != '')				$pdf->Cell(186,5,$arrayCompetition['Soustitre2'],0,1,'C');		else				$pdf->Cell(186,5,$codeCompet,0,1,'C');		$pdf->Ln(2);//		$pdf->SetFont('Arial','BI',10);//		$pdf->Cell(186,5,$lang['POULES'],0,0,'C');//		$pdf->Ln(10);		//données				$sql  = "Select Id, Libelle, Code_club, Poule, Tirage ";		$sql .= "From gickp_Competitions_Equipes ";		$sql .= "Where Code_compet = '";		$sql .= $codeCompet;		$sql .= "' And Code_saison = '";		$sql .= $codeSaison;		$sql .= "' Order By Poule, Tirage, Libelle ";	 			$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load<br><br>".$sql);		$num_results = mysql_num_rows($result);				// recalcul des éliminés		//$elim = $num_results - $elim;		$poule = '';		$demi = $num_results/2;//		$pdf->Cell(63, 6, '','',0,'L');//		$pdf->Cell(10, 6, '#', 0,0,'C');//		$pdf->Cell(10, 5, '', 0,'0','C'); //Pays//		$pdf->Cell(60, 6, $lang['Equipe'],0,1,'L');		$pdf->Ln(6);//		$pdf->Cell(186,4, '','',1);				for ($i=0;$i<$num_results;$i++)		{			$row = mysql_fetch_array($result);							if($poule != $row['Poule'])			{				if($i >= 20 && $demi != 0)				{					$pdf->AddPage();					$demi = 0;				}				$pdf->SetFont('Arial','BI',13);				$pdf->Cell(186,3, '','',1);				$pdf->Cell(180,6, $lang['Poule'].' '.$row['Poule'],0,1,'C');				$pdf->Cell(186,2, '','',1);			}			$poule = $row['Poule'];			$pdf->SetFont('Arial','B',12);			$pdf->Cell(63, 6, '',0,'0','L');			if($row['Tirage'] > 0)				$pdf->Cell(10, 6, $row['Tirage'], 0,'0','C');			else				$pdf->Cell(10, 6, '', 0,'0','C');			// drapeaux			if ($arrayCompetition['Code_niveau'] == 'INT')			{				$pays = substr($row['Code_club'], 0, 3);				if(is_numeric($pays[0]) || is_numeric($pays[1]) || is_numeric($pays[2]))					$pays = 'FRA';				$pdf->image('../img/Pays/'.$pays.'.png', $pdf->GetX(), $pdf->GetY()+1, 7, 4);				$pdf->Cell(10, 6, '', 0,'0','C'); //Pays			}			else				$pdf->Cell(10, 6, '', 0,'0','C');			$pdf->Cell(60,6, $row['Libelle'],0,1,'L');		}					$pdf->Output('Groupes '.$codeCompet,'I');	}}$page = new FeuilleGroups();?>