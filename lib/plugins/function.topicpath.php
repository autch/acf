<?php

function smarty_function_topicpath($params, &$smarty)
{
  require_once $smarty->_get_plugin_filepath('block', 'a');

  $s = array();
  $ary = explode(";", $params['path']);
  $a_params = $params;
  unset($a_params['path']);
  foreach($ary as $v)
  {
    $item = explode('|', $v);
    $a_params['href'] = $item[1];
    $s[] = smarty_block_a($a_params, $item[0], $smarty);
  }
  $smarty->assign('topicpath', join(' &gt; ', $s));
  return $smarty->fetch('topicpath.tpl');
}
