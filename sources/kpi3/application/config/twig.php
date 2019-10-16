<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

$config['twig']['template_dir'] = [VIEWPATH];
//$config['twig']['environment']['autoescape'] = TRUE;
$config['twig']['environment']['autoescape'] = 'html';
$config['twig']['environment']['cache'] = FALSE;
$config['twig']['environment']['debug'] = FALSE;

$config['twig']['template_ext'] = '.twig';
$config['twig']['functions_asis'] = [
        'base_url', 'site_url', 'css_url', 'js_url', 'lib_url', 'img_url', 'img',
        'version', 'lang', 'config_item', 'sprintf', 
        'is_admin', 'in_group'
    ];
$config['twig']['functions_safe'] = [
		'form_open', 'form_close', 'form_error', 'form_hidden', 'set_value',
        'form_input', 'form_submit', 'form_label'
//		'form_open_multipart', 'form_upload',  'form_dropdown',
//		'set_radio', 'set_select', 'set_checkbox',
	];