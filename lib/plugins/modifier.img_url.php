<?php

function smarty_modifier_img_url($string)
{
  if(preg_match('|^http://|', $string))
  {
    $prefix = '';
  }
  else if(preg_match('|^//|', $string))
  {
    $prefix = TF_URL_ROOT;
    $string = preg_replace('|^//|', '', $string);
  }
  else
  {
    $prefix = TF_URL_IMAGES;
  }
  return $prefix . $string;
}

