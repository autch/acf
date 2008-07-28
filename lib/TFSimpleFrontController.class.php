<?php

require_once TF_LIB_PATH . '/TFFrontController.class.php';
require_once TF_LIB_PATH . '/TFADOdb.class.php';

class TFSimpleFrontController extends TFFrontController
{
  var $_module_root;
  function TFSimpleFrontController($name)
  {
    parent::TFFrontController($name);
    $this->setModuleRoot(dirname(__FILE__));
  }
  function setModuleRoot($dir)
  {
    $this->_module_root = $dir;
  }
  function run()
  {
    $this->_smarty->addTemplatePath($this->_module_root . '/templates');

    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    if(!preg_match('|^[a-zA-Z0-9_/]+$|', $path_info)) $path_info = '/';
    if($path_info == '') $path_info = '/';

    $items = explode('/', $path_info);
    $action = 'list'; $id = NULL;
    if(!empty($items[1]) && !is_numeric($items[1]))
    {
      $action = trim($items[1]);
    }
    else if(!empty($items[1]) && is_numeric($items[1]))
    {
      $id = intval($items[1]);
      if(!empty($items[2]))
        $action = trim($items[2]);
      else
        $action = "show";
    }
    $this->doService($action, $id, $items);
  } 
  function doService($action, $id, &$items)
  {
    $path = $this->_module_root . '/actions/' . ucfirst(strtolower($action)) . 'Action.class.php';
    if(!file_exists($path))
    {
      $this->show_error(404, 'Not Found');
    }
    if(!is_readable($path))
    {
      $this->show_error(403, 'Forbidden');
    }
    include_once $path;
    $classname = ucfirst(strtolower($action)) . 'Action';
    $obj =& new $classname;
    if($obj->isSecure() && !isset($_SERVER['PHP_AUTH_USER']))
    {
      $this->show_error(403, 'Forbidden');
    }
    $obj->execute($this, $id, $items);
  }
  function runAction($action, $id, &$items)
  {
    $this->doService($action, $id, $items);
  }
}

class Action
{
  function execute(&$controller, $id, $params = NULL)
  {
  }
  
  function isSecure()
  {
    return FALSE;
  } 
}
