<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

$config['twig']['template_dir'] = VIEWPATH;
$config['twig']['template_ext'] = 'php';
$config['twig']['environment']['autoescape'] = TRUE;
//$config['twig']['environment']['autoescape'] = 'html';
$config['twig']['environment']['cache'] = FALSE;
$config['twig']['environment']['debug'] = FALSE;