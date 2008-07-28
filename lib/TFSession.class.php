<?php
/**
 * ���å���󥪥֥�������
 * 
 * glxSession �ΤѤ�r(ry
 */
class TFSession
{
  /**
   * �ۤ�����ˡ�Ǥ� $_SESSION �ؤΥ��������˾��ͤ��ʤ���
   * �����ʥ��å��������ؤΥ������֤�
   * 
   * glxSession �ˤ����� $_SESSION �ؤΥ��������ϡ����٤�
   * ���Υ᥽�åɤ�����ͤ򥭡��ˤ��ƹԤ���
   * @access public
   * @param string $key ���å�����ѿ��Υ���
   * @return string �����ʥ��å�����ѿ�����
   */
  function getSafeSessionArrKey($key)
  {
    return 'TFSESSION__' . $key;
  }
  /**
   * ���å�����ѿ����ͤ���¸����
   * 
   * ���å�����ѿ��إ��� $key, �� $value ����¸���롣���ΤȤ� $value �� serialize() �����Τǡ�
   * �ͤ���Ф��ݤ� session_start() �ȥ��饹����Ȥν��֤򵤤ˤ���ɬ�פ��ʤ���
   * 
   * @access public
   * @param string $key ���å�����ѿ��ؤΥ�����
   * @param mixed $value ���å�����ѿ�����¸�����͡�
   */
  function register($key, $value)
  {
    $realKey = TFSession::getSafeSessionArrKey($key);
    $_SESSION[$realKey] = serialize($value);
  }
  /**
   * ���å�����ѿ����ͤ���¸���롣
   * 
   * register() �Υ��Υ˥�
   * @access public
   * @param string $key ���å�����ѿ��ؤΥ�����
   * @param mixed $value ���å�����ѿ�����¸�����͡�
   */
  function set($key, $value)
  {
    TFSession::register($key, $value);
  }
  /**
   * ���å�����ѿ������ͤ�����
   * @param string $key ���å�����ѿ��ؤΥ���
   * @return mixed ���å�����ѿ�����
   */
  function get($key)
  {
    $realKey = TFSession::getSafeSessionArrKey($key);
    return unserialize($_SESSION[$realKey]);
  }
  /**
   * ���å�����ѿ������ͤ����ơ����å���󤫤餽���ѿ���������
   * @param string $key ���å�����ѿ��ؤΥ���
   * @return string ���å�����ѿ����� 
   */
  function pop($key)
  {
    $r = TFSession::get($key);
    TFSession::unregister($key);
    return $r;
  }
  /**
   * ���å���󤫤��ѿ���������
   * @param string $key ���å�����ѿ��ؤΥ���
   */
  function unregister($key)
  {
    $realKey = TFSession::getSafeSessionArrKey($key);
    unset($_SESSION[$realKey]);
  }
  /**
   * ���å������ѿ�����¸����Ƥ���Τ��䤤��碌��
   * @param string $key ���å�����ѿ��ؤΥ���
   * @return boolean ���å������ͤ���¸����Ƥ���� TRUE, ����ʳ� FALSE
   */
  function isRegistered($key)
  {
    $realKey = TFSession::getSafeSessionArrKey($key);
    return isset($_SESSION[$realKey]);
  }
}

@session_start();
