<?php
/*
 * 
 */

require_once '../config.inc.php';

$controller_name = basename($_SERVER['SCRIPT_NAME']);

switch($controller_name)
{
  default:
    $dir = dirname($_SERVER['SCRIPT_NAME']);
    $_SERVER['SCRIPT_NAME'] = $dir . ($dir == '/' ? 'page' : '/page');
    // fall thru
  case 'page':
    require_once TF_LIB_PATH . '/TFFrontController.class.php';
    $controller =& new TFFrontController($controller_name);
    break;
}

$controller->run();
