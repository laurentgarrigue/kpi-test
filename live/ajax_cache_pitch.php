<?php
include_once('base.php');
include_once('create_cache_match.php');

$event = $_GET['event'];
$match = $_GET['match'];
$pitch = $_GET['pitch'];

$db = new MyBase();

$_GET['cache'] = 1;
$cache = new CacheMatch($_GET);
$cache->Pitch($event, $pitch, $match);
$cache->Match($db, $match);

echo "OK Match $match Pitch $pitch Event $event";
?>