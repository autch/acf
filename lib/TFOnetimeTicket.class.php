<?php

/**
 * ��󥿥���ȡ�����Υ���ƥ�
 * 
 * ��󥿥���ȡ������ɬ�פʥȡ�����̾�䥿���ॹ����פ��ݻ�����HTML �� hidden �������������롣
 * ���Υ��饹�� {@link glxTicketVerifier} �����������ƥ������ޥ������뤳�Ȥǥȡ����󥷥��ƥ�򥫥����ޥ������롣
 * 
 * @author M.Nishimura
 */
class TFOnetimeTicket
{
  var $name_;
  var $timestamp_;
  var $token_;
  
  function TFOnetimeTicket()
  {
    $this->name_ = NULL;
    $this->token_ = NULL;
    $this->timestamp_ = time();
  }
  /**
   * �ȡ���������ꤷ�������ॹ����פ򸽺߻�������ꤹ�롣
   * @param string $name �ȡ������̾����
   * @param string $token �ȡ�����ʸ����
   */
  function setTicket($name, $token)
  {
    $this->name_ = $name;
    $this->token_ = $token;
    $this->timestamp_ = time();
  }
  /**
   * �ȡ����������֤���
   * @return array array('name' => ̾��, 'token' => �ȡ�����, 'timestamp' => �����ॹ�����)
   */
  function getTicket()
  {
    return array('name' => $this->name_, 'token' => $this->token_, 'timestamp' => $this->timestamp_);
  }
  /**
   * HTML �ե�����ǻȤ��� hidden ���������������֤�
   * 
   * ���ץꥱ�������Ǥϡ����Υ᥽�åɤ��֤��� html �򤽤Τޤ�������ǻȤ����Ȥ��Ǥ��롣
   * @return string <input type="hidden" ...>
   */
  function makeHTMLHidden()
  {
    $html = sprintf('<input type="hidden" name="%s" value="%s" />',
                    htmlspecialchars($this->name_), htmlspecialchars($this->token_));
    return $html;
  }
  /**
   * �ȡ�����̾���֤�
   * @return string �ȡ�����̾
   */
  function getName()
  {
    return $this->name_;
  }
  /**
   * �ȡ�����ʸ������֤�
   * @return string �ȡ�����ʸ����
   */
  function getToken()
  {
    return $this->token_;
  }
  /**
   * �ȡ�����ȯ�Ի��Υ����ॹ����פ�����
   * @reutrn integer �ȡ�����ȯ�Ի��Υ����ॹ����ס�ñ�̤��á�
   */
  function getTimestamp()
  {
    return $this->timestamp_;
  }
  /**
   * $_GET �� $_POST ��������줿�ȡ�����򸡾ڤ���
   * @param string $token �ե����फ������줿�ȡ�����ʸ����
   * @param integer default_timeout �ȡ�����Υ����ॢ���ȡ�ñ�̤��äǡ��ǥե���Ȥ� 60
   * @return boolean �ȡ�����ʸ���󤬰��פ������ĥ����ॢ���Ȥ��Ƥ��ʤ���� TRUE, ����ʳ� FALSE
   */
  function validate($token, $default_timeout = 60) // in minutes
  {
    return ($token === $this->token_) && ((time() - $this->timestamp_) < $default_timeout * 60);
  }
  
}

