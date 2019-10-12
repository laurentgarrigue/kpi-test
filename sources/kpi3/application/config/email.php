<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Configuration EMAIL
| -------------------------------------------------------------------------
*/
 
$config['protocol'] = 'smtp';
$config['smtp_host'] = SMTP_HOST;
$config['smtp_port'] = SMTP_PORT;
$config['smtp_user'] = SMTP_USER;
$config['smtp_pass'] = SMTP_PASS;
$config['crlf'] = '\r\n';
$config['newline'] = '\r\n';
$config['mailtype'] = 'html';