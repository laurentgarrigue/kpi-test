<?phpinclude_once('commun/MyPage.php');include_once('commun/MyBdd.php');include_once('commun/MyTools.php');define('FPDF_FONTPATH','font/');require('fpdf/fpdf.php');// Pieds de pageclass PDF extends FPDF{	function Footer()	{	    //Positionnement à 1,5 cm du bas	    $this->SetY(-15);	    //Police Arial italique 8	    $this->SetFont('Arial','I',8);	    //Numéro de page centré	    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');	}}// Gestion de la Feuille de Classement par Niveauclass FeuilleCltNiveauNiveau extends MyPage	 {		function FeuilleCltNiveauNiveau()	{		MyPage::MyPage();	  		$codeCompet = utyGetSession('codeCompet', '');		$codeSaison = utyGetSaison();		$codeSaison = utyGetGet('S', $codeSaison);		//Saison			$titreDate = "Saison ".$codeSaison;		//Création		$pdf = new PDF('P');		$pdf->Open();		$pdf->SetTitle("Classement par niveau");				$pdf->SetAuthor("kayak-polo.info");		$pdf->SetCreator("kayak-polo.info");		$pdf->AddPage();		$pdf->SetAutoPageBreak(true, 15);		// logo		$pdf->Image($_SERVER['DOCUMENT_ROOT'].'/css/banniere1.jpg',10,8,72,15,'jpg',"http://www.kayak-polo.info");		$pdf->Image($_SERVER['DOCUMENT_ROOT'].'/css/logo_ffck4.png',176,8,24,15,'png',"http://www.ffck.org");		$pdf->Ln(14);		// titre		$pdf->SetFont('Arial','BI',9);		$pdf->Cell(95,5,"Compétition à élimination",'LT','0','L');		$pdf->Cell(95,5,$titreDate,'TR','1','R');		$pdf->SetFont('Arial','B',12);		$pdf->Cell(190,5,utyGetLabelCompetition($codeCompet),'LR','1','C');		$pdf->Cell(190,5,"Classement par niveau",'LRB','1','C');		$pdf->SetFont('Arial','BI',8);		$pdf->Cell(95,5,"Edité le ".date("d/m/Y")." à ".date("H:i"),'0','0','L');		$pdf->Cell(95,5,"Classement officiel",'0','1','R');		// données		$myBdd = new MyBdd();				$sql  = "Select a.Id, a.Libelle, a.Code_club, ";		$sql .= "b.Niveau, b.Clt_publi, b.Pts_publi, b.J_publi, b.G_publi, b.N_publi, b.P_publi, b.F_publi, b.Plus_publi, b.Moins_publi, b.Diff_publi, b.PtsNiveau_publi, b.CltNiveau_publi ";		$sql .= "From gickp_Competitions_Equipes a, ";		$sql .= "gickp_Competitions_Equipes_Niveau b ";		$sql .= "Where a.Id = b.Id ";		$sql .= "And a.Code_compet = '";		$sql .= $codeCompet;		$sql .= "' And a.Code_saison = '";		$sql .= $codeSaison;		$sql .= "' Order By b.Niveau Asc, b.CltNiveau_publi Asc, b.Diff_publi Desc ";	 			$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load");		$num_results = mysql_num_rows($result);			$niveau = -1;		for ($i=0;$i<$num_results;$i++)		{				$row = mysql_fetch_array($result);									$idEquipe = $row['Id'];				if ($row['Niveau'] != $niveau)				{						$niveau = $row['Niveau'];												$pdf->Ln(5);						$pdf->SetFont('Arial','B',12);						$pdf->Cell(100,5,"Niveau ".$niveau, 'LTBR','0','C');						$pdf->SetFont('Arial','B',9);						$pdf->Cell(10,5, "Pts", 'LTBR','0','C');						$pdf->Cell(10,5, "J", 'LTBR','0','C');						$pdf->Cell(10,5, "G", 'LTBR','0','C');						$pdf->Cell(10,5, "N", 'LTBR','0','C');						$pdf->Cell(10,5, "P", 'LTBR','0','C');						$pdf->Cell(10,5, "F", 'LTBR','0','C');						$pdf->Cell(10,5, "Plus", 'LTBR','0','C');						$pdf->Cell(10,5, "Moins", 'LTBR','0','C');						$pdf->Cell(10,5, "Diff", 'LTBR','1','C');				}								$pts = $row['Pts_publi'];				$len = strlen($pts);				if ($len > 2)				{					if (substr($pts, $len-2, 2) == '00')						$pts = substr($pts, 0, $len-2);					else						$pts = substr($pts, 0, $len-2).'.'.substr($pts, $len-2, 2);				}				$pdf->SetFont('Arial','B',9);				$pdf->Cell(20, 5, $row['CltNiveau_publi'], 'LTBR','0','C');				$pdf->Cell(80,5, $row['Libelle'],'RTB','0','C');				$pdf->Cell(10,5, $pts, 'LTBR','0','C');				$pdf->Cell(10,5, $row['J_publi'], 'LTBR','0','C');				$pdf->Cell(10,5, $row['G_publi'], 'LTBR','0','C');				$pdf->Cell(10,5, $row['N_publi'], 'LTBR','0','C');				$pdf->Cell(10,5, $row['P_publi'], 'LTBR','0','C');				$pdf->Cell(10,5, $row['F_publi'], 'LTBR','0','C');				$pdf->Cell(10,5, $row['Plus_publi'], 'LTBR','0','C');				$pdf->Cell(10,5, $row['Moins_publi'], 'LTBR','0','C');				$pdf->Cell(10,5, $row['Diff_publi'], 'LTBR','1','C');		}					$pdf->Output('Classement par niveau '.$codeCompet,'I');	}}$page = new FeuilleCltNiveauNiveau();?>