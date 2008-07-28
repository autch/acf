<?php

function smarty_block_file($params, $content, &$smarty)
{
  if(is_null($content))
    return '';

  require_once $smarty->_get_plugin_filepath('block', 'a');

  $a_params = $params;
  $a_params['controller'] = '';

  return smarty_block_a($a_params, $content, $smarty);
}
