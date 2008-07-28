<?php

function smarty_block_a($params, $content, &$smarty)
{
  if(is_null($content))
    return '';
  $attr = '';
  $attr_params = $params;
  unset($attr_params['href']);
  unset($attr_params['controller']);
  foreach($attr_params as $k => $v)
  {
    $attr .= sprintf(' %s="%s"', $k, $v);
  }  
  
  if(preg_match("@^.+:@", $params['href']))
    $url = $params['href']; 
  else
  {
    $matches = array();
    if(preg_match('/^\{(\w*)\}/', $params['href'], $matches))
    {
      $params['controller'] = $matches[1];
      $params['href'] = preg_replace('/^\{\w*\}/', '', $params['href']);
    }

    if(!isset($params['controller']))
    {
      $url = TF_URL_CONTROLLER;
    }
    else
      $url = TF_URL_ROOT . $params['controller'];

    $url .= $params['href'];
  }

  return sprintf('<a href="%s" %s>%s</a>', $url, $attr, $content); 
}
