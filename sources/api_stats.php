<?php

include_once('commun/MyPage.php');
include_once('commun/MyBdd.php');
include_once('commun/MyTools.php');

session_start();
$myBdd = new MyBdd();
$format = $myBdd->RealEscapeString(trim(utyGetGet('format', 'csv')));
$saison = (int) $myBdd->RealEscapeString(trim(utyGetGet('saison')));
$competitions = explode(",", $myBdd->RealEscapeString(trim(utyGetGet('competitions'))));
$in  = str_repeat('?,', count($competitions) - 1) . '?';
$all = (int) $myBdd->RealEscapeString(trim(utyGetGet('all', 2)));
if($saison > 2000 && $competitions != '') {
    $arrayStats = [];
    switch($all){
        case 0: // seulement les joueurs avec des stats
            $sql = "SELECT d.Code_competition Competition, a.Matric Licence, a.Nom, 
                a.Prenom, a.Sexe, b.Numero, f.Libelle Equipe, 
                SUM(IF(b.Id_evt_match = 'B', 1, 0)) Buts,
                SUM(IF(b.Id_evt_match = 'V', 1, 0)) Vert,
                SUM(IF(b.Id_evt_match = 'J', 1, 0)) Jaune,
                SUM(IF(b.Id_evt_match = 'R', 1, 0)) Rouge,
                SUM(IF(b.Id_evt_match = 'T', 1, 0)) Tirs,
                SUM(IF(b.Id_evt_match = 'A', 1, 0)) Arrets
                FROM gickp_Liste_Coureur a, gickp_Matchs_Detail b, gickp_Matchs c, gickp_Journees d, gickp_Competitions_Equipes f 
                WHERE a.Matric = b.Competiteur 
                AND b.Id_match = c.Id AND c.Id_journee = d.Id 
                AND d.Code_competition = f.Code_compet 
                AND d.Code_saison = f.Code_saison 
                AND f.Id = IF(b.Equipe_A_B='A',c.Id_equipeA, c.Id_equipeB) 
                AND d.Code_competition IN ($in) 
                AND d.Code_saison = ?
                GROUP BY a.Matric 
                ORDER BY Buts DESC, a.Nom ";
            $entete = array('Competition', 'Licence', 'Name', 'First name', 'Gender', 'Number', 'Team', 
            'Goals', 'GC', 'YC', 'RC', 'Shots', 'Stops');
            $result = $myBdd->pdo->prepare($sql);
            $result->execute(array_merge($competitions, [$saison]));
            break;
        case 1: // Tous les joueurs : A corriger !
            $sql = "SELECT d.Code_competition Competition, a.Matric Licence, a.Nom, 
                a.Prenom, a.Sexe, b.Numero, f.Libelle Equipe, 
                SUM(IF(b.Id_evt_match = 'B', 1, 0)) Buts,
                SUM(IF(b.Id_evt_match = 'V', 1, 0)) Vert,
                SUM(IF(b.Id_evt_match = 'J', 1, 0)) Jaune,
                SUM(IF(b.Id_evt_match = 'R', 1, 0)) Rouge,
                SUM(IF(b.Id_evt_match = 'T', 1, 0)) Tirs,
                SUM(IF(b.Id_evt_match = 'A', 1, 0)) Arrets
                FROM gickp_Competitions_Equipes_Joueurs j LEFT OUTER JOIN gickp_Liste_Coureur a ON j.Matric = a.Matric 
                    LEFT OUTER JOIN gickp_Matchs_Detail b ON j.Matric = b.Competiteur, 
                    gickp_Matchs c, gickp_Journees d, gickp_Competitions_Equipes f 
                WHERE 1 = 1 
                AND b.Id_match = c.Id 
                AND c.Id_journee = d.Id 
                AND d.Code_competition = f.Code_compet 
                AND d.Code_saison = f.Code_saison 
                AND f.Id = j.Id_equipe
                AND f.Id = IF(b.Equipe_A_B='A',c.Id_equipeA, c.Id_equipeB) 
                AND d.Code_competition IN ($in) 
                AND d.Code_saison = ?
                GROUP BY a.Matric 
                ORDER BY Buts DESC, a.Nom ";
            $entete = array('Competition', 'Licence', 'Name', 'First name', 'Gender', 'Number', 'Team', 
            'Goals', 'GC', 'YC', 'RC', 'Shots', 'Stops');
            $result = $myBdd->pdo->prepare($sql);
            $result->execute(array_merge($competitions, [$saison]));
            break;
        case 2: // Tous les joueurs, sans les stats
            $sql = "SELECT f.Code_compet Competition, f.Libelle Equipe, j.Numero, j.Capitaine, a.Nom, 
                a.Prenom, a.Naissance, a.Sexe, a.Matric Licence, a.Reserve Licence_ICF
                FROM gickp_Competitions_Equipes_Joueurs j LEFT OUTER JOIN gickp_Liste_Coureur a ON j.Matric = a.Matric, 
                    gickp_Competitions_Equipes f 
                WHERE 1 = 1 
                AND f.Id = j.Id_equipe
                AND f.Code_compet IN ($in) 
                AND f.Code_saison = ?
                GROUP BY a.Matric 
                ORDER BY Equipe ASC, j.Numero, a.Nom, a.Prenom ";
            $entete = array('Competition', 'Team', 'Number', 'Captain', 'Name', 'First name', 
                'Birthdate', 'Gender', 'Licence', 'ICF#');
            $result = $myBdd->pdo->prepare($sql);
            $result->execute(array_merge($competitions, [$saison]));
            break;
        default:
            echo "Incorrect parameters (example: api_stats.php?saison=20xx&competitions=CODE1,CODE2&all=0&format=json )";
            die();
    }
    
    $arrayStats = $result->fetchAll(PDO::FETCH_ASSOC);

    if ($format == 'json') {
        header('Content-Type: application/json');
        echo json_encode($arrayStats);
    } else { // CSV
        header("Content-Type: text/csv");
        $out = fopen('php://output', 'w');
        fputcsv($out, $entete);
        foreach ($arrayStats as $row) {
            fputcsv($out, $row);
        }
        fclose($out);
    }

} else {
    echo "Incorrect parameters (example: api_stats.php?saison=20xx&competitions=CODE1,CODE2&all=0 )";
}
die();