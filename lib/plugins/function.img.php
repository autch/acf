<?php

function smarty_function_img($params, &$smarty)
{
  if(is_null($params['src']))
  	return TF_URL_IMAGES;

  require_once $smarty->_get_plugin_filepath('modifier', 'img_url');

  $attr = '';
  $attr_params = $params;
  unset($attr_params['src']);
  foreach($attr_params as $k => $v)
  {
    $attr .= sprintf(' %s="%s"', $k, $v);
  }
  $url = smarty_modifier_img_url($params['src']);
  
  
  return sprintf('<img src="%s" %s />', $url, $attr); 
}