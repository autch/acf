<?php

function smarty_block_code($params, $content, &$smarty)
{
  if(is_null($content))
    return '';

  foreach($params as $k => $v)
    $smarty->assign($k, $v);
  $smarty->assign('content', $content);

  return $smarty->fetch('code.tpl');
}
