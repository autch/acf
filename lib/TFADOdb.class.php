<?php

/**
 * ADOConnection �򥭥�å��夹�� factory
 * 
 * ��³�� DB �λ����ʸ����Ȥ���Ϳ����������Ʊ����³��� ADOConnection ��
 * ��ä����Ȥ�����Ф���򤽤Τޤ��֤���
 * 
 */
class TFADOdb
{  
  /**
   * $dsn �˱����� ADOConnection ��������֤���
   * ������Ʊ�� $dsn �ǻȤä����Ȥ�����Ф���򤽤Τޤ��֤���
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
