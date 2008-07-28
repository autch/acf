<?php

function smarty_function_load_table($params, &$smarty)
{
  $category = NULL;
  $items = array();
  $links = array();
  $keys = explode(',', $params['cols']);
  $fp = fopen($params['src'], "r");
  if(!$fp)
  {
    trigger_error("Cannot read " . $params['src']);
    return FALSE;
  }
  while(($line = fgets($fp, 1024)) !== FALSE)
  {
    if($line{0} == '#') continue;
    $array = explode("\t", chop($line));
    if($category != $array[0] && $array[0] !== '')
    {
      if(!empty($items))
        $links[] = array('category' => $category, 'items' => $items);
      $category = $array[0];
      $items = array();
    }
    $item = array();
    array_shift($array);
    foreach($array as $i => $v)
      $item[$keys[$i]] = $v;
    $items[] = $item;
  }
  fclose($fp);
  if(!empty($items))
    $links[] = array('category' => $category, 'items' => $items);

  $smarty->assign($params['assign'], $links);
}
