<?php

$path = explode(PATH_SEPARATOR, ini_get('include_path'));
$path[] = TF_LIB_PATH . '/pear/';
ini_set('include_path', implode(PATH_SEPARATOR, $path));

require_once 'HTML/BBCodeParser.php';

function smarty_modifier_bbcode($string)
{
  $parser =& new HTML_BBCodeParser(array('filters' => 'Basic,Email,Links,Lists,Extended'));
  return $parser->qparse($string);
}