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
   * �ǥե���ȤΥѥ��˥ƥ�ץ졼�Ȥ��ʤ��ä��Ȥ���$this->_alternatives �ǻ��ꤵ�줿�ѥ���õ��������������롣
   * 
   * @access private
   * @param string $resource_type �ƥ�ץ졼�ȥ꥽�����η��֡��դĤ��� "file"
   * @param string $resource_name �ƥ�ץ졼�ȥ꥽����̾��Smarty::fetch() �� {include} �ΰ��������Τޤ����롣
   * @param string &$template_source ���������ƥ�ץ졼�Ȥ����Ƥ��֤���
   * @param int    &$template_timestamp �ƥ�ץ졼�Ȥκǽ���������� UNIX �����ॹ����פ��֤���
   * @param Smarty &$smarty_obj         ���δؿ���ƤӽФ��� $this
   * @return bool �ƥ�ץ졼�Ȥ򸫤Ĥ����Ȥ� TRUE, ����ʳ� FALSE
   */
  function defaultTemplateHandler($resource_type, $resource_name, &$template_source, &$template_timestamp, &$smarty_obj) 
  { 
    if($resource_type == 'file')
    {
      // �¤ϥ��֥�����å��ˤʤäƤ���
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
