<?php

require_once 'TFSmarty.class.php';

// utility
function isSetDefault(&$var, $default = NULL)
{
  return isset($var) ? $var : $default;
}

function isEmptyDefault(&$var, $default = NULL)
{
  return empty($var) ? $default : $var;
}

function getRequestMethod()
{
  return $_SERVER['REQUEST_METHOD'];
}

class TFFrontController
{
  var $_smarty;
  var $_name;

  function TFFrontController($controller_name)
  {
    $this->_smarty = new TFSmarty();
    $this->_name = $controller_name;
    define('TF_URL_CONTROLLER', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);
    define('TF_CONTROLLER_NAME', $this->_name);
  }
  function run()
  {
    $path_info = isSetDefault($_SERVER['PATH_INFO'], '');
    if(!preg_match('|^[-_/.a-zA-Z0-9]+$|', $path_info)) $path_info = '/';
    if($path_info == '') $path_info = '/';
    $template = TF_DOCUMENT_ROOT . $path_info;

    $this->doService($template);
  }
  function doService($template)
  {
    if(is_dir($template))
    {
      $template .= DIRECTORY_SEPARATOR . 'index.html';
    }
    $template .= '.tpl';
    if(!file_exists($template))
    {
      $this->show_error(404, 'Not Found');
    }
    if(!is_readable($template))
    {
      $this->show_error(403, 'Forbidden');
    }
    if(strpos(realpath($template), TF_DOCUMENT_ROOT) !== 0)
    {
      die("[SECURITY] possible directory traversal attempt!");
    }
    
    $this->show($template);   
  }
  function showPage($template)
  {
    $this->show($template);
  }
  function show($template_filename)
  {
    define('TF_CURRENT_TEMPLATE', $template_filename);
    $this->_smarty->display($template_filename, $_SERVER['QUERY_STRING']);
  }
  function show_error($code, $status)
  {
    header(sprintf("HTTP/1.0 %03d %s", $code, $status));
    $template = sprintf("%s/error%03d.html.tpl", TF_DOCUMENT_ROOT, $code);
    if(is_readable($template))
      $this->show($template);
    exit();
  }
  function& getSmarty()
  {
    return $this->_smarty;
  }
}
