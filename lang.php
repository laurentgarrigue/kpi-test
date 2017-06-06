<?php

session_start();
include_once('commun/MyTools.php');

$referer = utyGetGet('p', 'index');;
$lang = utyGetGet('lang', 'fr');
$idMatch = utyGetGet('idMatch', 0);
$idMatch = (int) $idMatch;

if($lang == 'en') {
    $_SESSION['lang'] = 'en';
} elseif ($lang == 'fr') {
    $_SESSION['lang'] = 'fr';
} else {
    $lang = 'fr';
}

switch ($referer) {
    case 'index' :
        header('Location: index.php?lang=' . $lang);
        break;
    case 'fm2' :
        header('Location: admin/FeuilleMarque2.php?idMatch=' . $idMatch);
        break;
    case 'fm4' :
        header('Location: admin/FeuilleMarque4.php?idMatch=' . $idMatch);
        break;
}

    
