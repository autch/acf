<?php

require_once dirname(__FILE__) . '/../bulletin/TFBulletinArticles.class.php';

function smarty_function_load_news($params, &$smarty)
{
  $articles = TFBulletinArticles::getNotExpiredArticles('', 10);
  $smarty->assign($params['assign'], $articles);
}
