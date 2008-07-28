<?php

function smarty_function_last_modified($params, &$smarty)
{
  $filename = TF_CURRENT_TEMPLATE;
  if(!empty($params['file']))
  	$filename = $params['file'];
  	
  $timestamp = filemtime($filename);

// Disabled for RSS
//  if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])
//    && !isset($_SERVER['HTTP_RANGE']))
//  {
//    $if_modified_since = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
//    if($if_modified_since >= $timestamp)
//    {
//      header("HTTP/1.0 304 Not Modified");
//      exit();
//    }
//  }

  if(!isset($params['no_lastmod']) || $params['no_lastmod'] == FALSE)
  {  	
  	header(sprintf("Last-Modified: %s GMT", gmdate("D, d M Y H:i:s", $timestamp)));
  }
  
  return $timestamp;
}