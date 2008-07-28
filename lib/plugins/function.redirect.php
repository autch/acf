<?php

function smarty_function_redirect($params, &$smarty)
{
  header("HTTP/1.0 307 Redirect permanently");
  header('Location: ' . $params['url']);
  exit();
}