<?php

/**
 * ADOConnection をキャッシュする factory
 * 
 * 接続先 DB の指定を文字列として与え、以前に同じ接続先で ADOConnection を
 * 作ったことがあればそれをそのまま返す。
 * 
 */
class TFADOdb
{  
  /**
   * $dsn に応じて ADOConnection を作成し返す。
   * 以前に同じ $dsn で使ったことがあればそれをそのまま返す。
   * @access public
   * @static
   */
  function& getADOdb($dsn)
  {
    static $cache = array();

    if(isset($cache[$dsn]))
    {
      return $cache[$dsn];
    }
    else
    {
      require_once 'adodb/adodb.inc.php';
      $adodb =& NewADOConnection($dsn);
      if($adodb !== FALSE)
      {
        $cache[$dsn] = $adodb;
      }
      return $adodb;
    }
  }
}
