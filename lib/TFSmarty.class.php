<?php

require_once 'smarty/Smarty.class.php';

class TFSmarty extends Smarty
{
  var $_alternatives;
  function TFSmarty()
  {
    $this->Smarty();
    
    $this->template_dir = TF_SMARTY_TEMPLATES;
    $this->compile_dir = TF_SMARTY_TEMPLATES_C;
    $this->plugins_dir = array_merge(array('plugins'), explode(PATH_SEPARATOR, TF_SMARTY_PLUGINS));
    $this->_alternatives = array();
    $this->default_template_handler_func = array($this, 'defaultTemplateHandler');
    
    $this->register_block('dynamic', array($this, 'smarty_block_dynamic'),
                          FALSE);

    if(defined('TF_SMARTY_CACHING') && constant('TF_SMARTY_CACHING') != 0)
    {
      $this->caching = TF_SMARTY_CACHING;
      $this->cache_dir = TF_SMARTY_CACHE;
      $this->cache_lifetime = TF_SMARTY_CACHE_LIFETIME;
      $this->compile_check = TF_SMARTY_COMPILE_CHECK;
    } 
  }

  function addTemplatePath($dir)
  {
    $this->_alternatives[] = $dir;
  }

  /**
   * デフォルトのパスにテンプレートがなかったとき、$this->_alternatives で指定されたパスを探す処理を実装する。
   * 
   * @access private
   * @param string $resource_type テンプレートリソースの形態。ふつうは "file"
   * @param string $resource_name テンプレートリソース名。Smarty::fetch() や {include} の引数がそのまま入る。
   * @param string &$template_source 取得したテンプレートの内容を返す。
   * @param int    &$template_timestamp テンプレートの最終更新時刻を UNIX タイムスタンプで返す。
   * @param Smarty &$smarty_obj         この関数を呼び出した $this
   * @return bool テンプレートを見つけたとき TRUE, それ以外 FALSE
   */
  function defaultTemplateHandler($resource_type, $resource_name, &$template_source, &$template_timestamp, &$smarty_obj) 
  { 
    if($resource_type == 'file')
    {
      // 実はダブルチェックになっている
      $fullpath = sprintf("%s/%s", $this->template_dir, $resource_name);
      $alternatives = $smarty_obj->_alternatives;  // should be copying, not reference
      while(!is_readable($fullpath))
      {
        if(count($alternatives) == 0)
        {
          trigger_error(sprintf("TFSmarty: cannot find template: %s in %s",
            $resource_name, implode(PATH_SEPARATOR, $this->_alternatives)));
          die();
        }
        $path = array_shift($alternatives);
        $fullpath = sprintf("%s/%s", $path, $resource_name);
      } 
      $template_source = implode('', file($fullpath)); 
      $template_timestamp = filemtime($fullpath); 
      return true; 
    } 
    return false; 
  }

  function smarty_block_dynamic($param, $content, &$smarty)
  {
    return $content;
  }
}
