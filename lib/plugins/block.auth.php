<?php

require_once TF_LIB_PATH . '/auth/TFAuthManager.class.php';

function smarty_block_auth($params, $content, &$smarty)
{
  $auth =& new TFAuthManager();
  
  $realm = trim(isSetDefault($params['realm'], 'default'));
  $action = trim(isSetDefault($params['action'], 'validate'));
  $uid_key = trim(isSetDefault($params['uid_key'], 'user_id'));
  $pw_key = trim(isSetDefault($params['pw_key'], 'password'));

  $user_id = trim(isSetDefault($_POST[$uid_key], ''));
  $password = trim(isSetDefault($_POST[$pw_key], ''));
  
  switch(strtolower($action))
  {
    case 'login':
      break;
    case 'logout':
      $auth->logout($realm);
      break;
    case 'validate':
      // fall thru
    default:
      if($auth->isLoggedIn($realm))
      {
      }
      else
      {
      }
  }
}
