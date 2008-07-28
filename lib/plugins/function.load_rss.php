<?php

require_once TF_LIB_PATH . '/magpierss/rss_fetch.inc';
require_once TF_LIB_PATH . '/magpierss/rss_utils.inc';

function smarty_function_load_rss($params, &$smarty)
{
  $rss = fetch_rss($params['url']);
  if(!$rss) return;

  $items = array();
  foreach($rss->items as $key => $value)
  {
    mb_c_e_for_all($rss->items[$key]);
    $items[$key] = $rss->items[$key];
    $items[$key]['timestamp'] = parse_w3cdtf($rss->items[$key]['dc']['date']);
    $items[$key]['date'] = date(empty($params['date'])
                                ? 'Y-m-d H:i:s' : $params['date'],
                                $items[$key]['timestamp']);
  }
  if(is_array($items)) usort($items, 'compare_rssitem');
  if($params['limit'] > 0) $items = array_slice($items, 0, $params['limit']);

  $smarty->assign($params['assign'], $items);
}

function mb_c_e_for_all(&$deep_array)
{
  foreach($deep_array as $key => $value)
  {
    if(is_array($deep_array[$key]))
      mb_c_e_for_all($deep_array[$key]);
    else if(is_string($deep_array[$key]))
      $deep_array[$key] = mb_convert_encoding($deep_array[$key],
                                              mb_internal_encoding(),
                                              'UTF-8');
  }
}

function compare_rssitem(&$x, &$y)
{
  if($x['timestamp'] == $y['timestamp']) return 0;
  return ($x['timestamp'] > $y['timestamp']) ? -1 : 1;
}

