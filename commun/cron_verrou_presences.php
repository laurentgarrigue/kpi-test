<?php
	header ('Content-type:text/html; charset=utf-8');
	$time1 = time();
	include_once('../commun/MyBdd.php');
	
	$myBdd = new MyBdd();
	
		$sql  = "SELECT DISTINCT(Code_competition) ";
		$sql .= "FROM `gickp_Journees` ";
		$sql .= "WHERE 1 ";
		$sql .= "AND Code_saison = 2015 ";
		$sql .= "AND Date_debut > CURDATE() ";
		$sql .= "AND DATEDIFF(Date_debut, CURDATE()) < 7 ; ";
		//$sql .= "AND (Code_competition LIKE 'N%' OR Code_competition LIKE 'CF%') ";
		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load : ".$sql);
		$num_results = mysql_num_rows($result);
		for ($i=0;$i<$num_results;$i++)
		{
			$row = mysql_fetch_array($result);
			if(isset($codeCompet))
				$codeCompet .= ',';
			$codeCompet .= '"'.$row['Code_competition'].'"';
		}
		if(isset($codeCompet)){
			$sql  = "UPDATE gickp_Competitions SET Verrou = 'O' WHERE Code_saison = 2015 AND Code IN ($codeCompet) ";
			mysql_query($sql, $myBdd->m_link) or die ("Erreur Load : ".$sql);
		}
	
		$sql  = "SELECT DISTINCT(Code_competition) ";
		$sql .= "FROM `gickp_Journees` ";
		$sql .= "WHERE 1 ";
		$sql .= "AND Code_saison = 2015 ";
		$sql .= "AND Date_fin < CURDATE() ";
		$sql .= "AND DATEDIFF(CURDATE(), Date_fin) < 3 ; ";
		//$sql .= "AND (Code_competition LIKE 'N%' OR Code_competition LIKE 'CF%') ";
		$result = mysql_query($sql, $myBdd->m_link) or die ("Erreur Load : ".$sql);
		$num_results = mysql_num_rows($result);
		for ($i=0;$i<$num_results;$i++)
		{
			$row = mysql_fetch_array($result);
			if(isset($codeCompet2))
				$codeCompet2 .= ',';
			$codeCompet2 .= '"'.$row['Code_competition'].'"';
		}
		if(isset($codeCompet2)){
			$sql  = "UPDATE gickp_Competitions SET Verrou = 'N' WHERE Code_saison = 2015 AND Code IN ($codeCompet2) ";
			mysql_query($sql, $myBdd->m_link) or die ("Erreur Load : ".$sql);
		}

	$fp = fopen("log_cron.txt","a");
	fputs($fp, "\n"); // on va a la ligne
	fputs($fp, date('Y-m-d H:s') . " - " . "Verrou comp&eacute;titions : $codeCompet, d&eacute;verrou comp&eacute;titions : $codeCompet2"); // on ecrit la ligne
	fclose($fp);
