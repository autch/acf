<?php

function smarty_modifier_a2print($string, $format = "\\1 (\\2)")
{
  return preg_replace('|(<a.*?href="(.*?)".*?>(.*?)</a>)|', $format, $string);
}
