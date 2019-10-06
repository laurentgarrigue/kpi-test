<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

$config['twig']['template_dir'] = [VIEWPATH];
//$config['twig']['environment']['autoescape'] = TRUE;
$config['twig']['environment']['autoescape'] = 'html';
$config['twig']['environment']['cache'] = FALSE;
$config['twig']['environment']['debug'] = FALSE;

$config['twig']['template_ext'] = '.twig';
$config['twig']['functions_asis'] = [
        'base_url', 'site_url', 'css_url', 'js_url', 'lib_url', 'img_url', 'img',
        'version', 'lang'
    ];
$config['twig']['functions_safe'] = [
		'form_open', 'form_close', 'form_error', 'form_hidden', 'set_value',
//		'form_open_multipart', 'form_upload', 'form_submit', 'form_dropdown',
//		'set_radio', 'set_select', 'set_checkbox',
	];