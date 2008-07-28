<?php

require_once TF_LIB_PATH . '/TFSession.class.php';
require_once TF_LIB_PATH . '/TFOnetimeTicket.class.php';

define('TFTICKET_SESSION_PREFIX', 'TFTicket_');

/**
 * ��󥿥�������åȤ�ȯ�Ԥȸ���
 * 
 * ��ȯ�Ԥ�������Τ�̵���ˤʤ륿���פΥ�󥿥�������åȤ�ȯ�Ԥȸ��ڤ�Ԥ���
 * 
 * @package glaxis
 */
class TFTicketVerifier
{
  /**
   * �����å�Ⱦ���򥻥å������ݻ�����ݤΥ������ղä���ץ�ե��å������ݻ����롣
   * 
   * setPrefix() �ǥ��å���󥭡��˥ץ�ե�������Ĥ��뤳�Ȥǡ��ۤ��Υ��å�����ѿ��Ȥξ��ͤ��ɤ���
   * @var string ���å���󥭡��Υץ�ե��å������ǥե���Ȥ϶���
   * @access private
   */
  var $prefix_;
  
  function TFTicketVerifier($prefix = '')
  {
    $this->prefix_ = $prefix;
  }
  /**
   * �����å�Ⱦ���򥻥å������ݻ�����ݤΥ������ղä���ץ�ե��å��������ꤹ�롣
   * @param string $prefix ���å���󥭡����ղä���ץ�ե��å�����
   * @access public
   */
  function setPrefix($prefix)
  {
    $this->prefix_ = $prefix;
  }
  /**
   * �ǥե���ȤΥ�󥿥�������åȤ�ȯ�Ԥ���
   * 
   * �ǥե���ȤΥ�󥿥�������åȤ����������֤���Ʊ̾�ǰ�����ȯ�Ԥ��������åȤ�̵���ˤʤ롣
   * @access public
   * @param string $name �����åȤ�̾����ȯ�Ի��ȸ��ڻ���Ʊ��̾���ˤ��ʤ���Фʤ�ʤ���
   * @return glxOnetimeTicket ��������󥿥�������åȡ�
   */
  function& createTicket($name)
  {
    $ticket =& new TFOnetimeTicket();
    $ticket->setTicket($name, $this->generateToken_($name));
    TFSession::register($this->getSessionKey($name), $ticket);
    return $ticket;
  }
  /**
   * �����åȤ򥻥å����˻�������ݤΥ������֤�
   * 
   * ȯ�Ԥ��줿�����åȤ򥻥å�����ѿ��˻�������ݤΥ����� $name �����ä��֤���
   * @access public
   * @param string $name �����åȤ�̾����ȯ�Ի��ȸ��ڻ���Ʊ��̾����Ȥ����ȡ�
   * @return string ���å�����ѿ��Υ����˻Ȥ�ʸ����
   */
  function getSessionKey($name)
  {
    return TFTICKET_SESSION_PREFIX.$this->prefix_.$name;
  }
  /**
   * �����åȤ˻Ȥ���ȡ�������������롣
   * 
   * �����åȤΥ�ˡ��������ݾڤ��뤿���ȯ�Ԥ����ȡ�������֤���
   * ���Υȡ�����������ˤϥ��饤����Ȥ�����Ǥ���褦�ʾ���ʥ��å���� ID �ʤɡˤ򺮤��ƤϤʤ�ʤ���
   * @access private
   * @param string $name �����åȤ�̾����ȯ�Ի��ȸ��ڻ���Ʊ��̾����Ȥ����ȡ����ΤȤ���̤����
   * @return string �������줿�ȡ�����
   */
  function generateToken_($name)
  {
    return md5(__LINE__.microtime().mt_rand());
  }
  /**
   * $name �����ꤵ�������åȤ�ΤƤ��̵���ˤ���ˡ�
   * 
   * $name �����ꤵ�������åȤ򥻥å���󤫤�õ�롣
   * �ʸ塢$name ��ɳ�Ť������åȤ�̵���Ȥʤꡢ���ڤ˼��Ԥ��롣
   * 
   * �����åȸ��ڤ˼��Ԥ����Ȥ��䡢���ڤ˹�ʤ�����ϡ�
   * ɬ�����������åȤ򤳤Υ᥽�åɤ�̵�������뤳�ȡ�
   * ɸ��ǤϤɤ���⸡�ڤκݤ˼�ư�ǹԤ��롣
   * 
   * @access public
   * @param string $name �����åȤ�̾����ȯ�Ի��ȸ��ڻ���Ʊ��̾����Ȥ����ȡ�
   */
  function disposeTicket($name)
  {
    TFSession::unregister($this->getSessionKey($name));
  }
  /**
   * ���ꤵ�줿���Ϥ������������åȤ򸡾ڤ��롣
   * 
   * $name �����ꤵ�������åȤ˴ؤ��ơ�$source ���������Ƥ����ȡ�����򸵤˸��ڤ��롣
   * ���ڤ���������Ȥ��Υ����åȤ��˴����� TRUE ���֤�������ʳ��ξ��� FALSE ���֤���
   * 
   * @access public
   * @param string $name �����åȤ�̾����ȯ�Ի���Ʊ��̾����Ȥ����ȡ�
   * @param array $source $_GET, $_POST �Τ����줫��
   * @param bool $clearIfValid ���ڤ����������Ȥ��˥����åȤ��˴�����ʤ� TRUE, ����ʳ� FALSE
   * @return bool ���ڤ����������Ȥ� TRUE, ����ʳ� FALSE
   */
  function validate_($name, &$source, $clearIfValid = TRUE)
  {
    if(!TFSession::isRegistered($this->getSessionKey($name))) return FALSE;
    $org_ticket = TFSession::get($this->getSessionKey($name));
    $req_token = isset($source[$name]) ? trim($source[$name]) : NULL;
    if($req_token)
    {
      if($org_ticket->validate($req_token))
      {
        if($clearIfValid)
          $this->disposeTicket($name);
        return TRUE;
      }
    }
    return FALSE;
  }
  /**
   * GET/POST �������������åȤ򸡾ڤ��롣
   * 
   * GET/POST �Τɤ���Ǽ�����ä������åȤ⸡�ڤ��оݤˤ��롣
   * ���ڤ���������Ȥ��Υ����åȤ��˴����� TRUE ���֤�������ʳ��ξ��� FALSE ���֤���
   * 
   * @access public
   * @param string $name �����åȤ�̾����ȯ�Ի���Ʊ��̾����Ȥ����ȡ�
   * @param bool $clearIfValid ���ڤ����������Ȥ��˥����åȤ��˴�����ʤ� TRUE, ����ʳ� FALSE
   * @return bool ���ڤ����������Ȥ� TRUE, ����ʳ� FALSE
   */
  function validateTicketInRequest($name, $clearIfValid = TRUE)
  {
    return $this->validate_($name, $_REQUEST, $clearIfValid);
  }
  /**
   * POST �������������åȤΤߤ򸡾ڤ��롣
   * 
   * POST �Ǽ�����ä������åȤ����򸡾ڤ��оݤˤ��롣
   * ���ڤ���������Ȥ��Υ����åȤ��˴����� TRUE ���֤�������ʳ��ξ��� FALSE ���֤���
   * 
   * @access public
   * @param string $name �����åȤ�̾����ȯ�Ի���Ʊ��̾����Ȥ����ȡ�
   * @param bool $clearIfValid ���ڤ����������Ȥ��˥����åȤ��˴�����ʤ� TRUE, ����ʳ� FALSE
   * @return bool ���ڤ����������Ȥ� TRUE, ����ʳ� FALSE
   */
  function validateTicketInPost($name, $clearIfValid = TRUE)
  {
    return $this->validate_($name, $_POST, $clearIfValid);
  }
}
