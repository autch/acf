<?php

require_once TF_LIB_PATH . '/TFSession.class.php';
require_once TF_LIB_PATH . '/TFOnetimeTicket.class.php';

define('TFTICKET_SESSION_PREFIX', 'TFTicket_');

/**
 * ワンタイムチケットの発行と検証
 * 
 * 再発行すると前のが無効になるタイプのワンタイムチケットの発行と検証を行う。
 * 
 * @package glaxis
 */
class TFTicketVerifier
{
  /**
   * チケット半券をセッションに保持する際のキーに付加するプリフィックスを保持する。
   * 
   * setPrefix() でセッションキーにプリフィクスをつけることで、ほかのセッション変数との衝突を防ぐ。
   * @var string セッションキーのプリフィックス。デフォルトは空。
   * @access private
   */
  var $prefix_;
  
  function TFTicketVerifier($prefix = '')
  {
    $this->prefix_ = $prefix;
  }
  /**
   * チケット半券をセッションに保持する際のキーに付加するプリフィックスを設定する。
   * @param string $prefix セッションキーに付加するプリフィックス。
   * @access public
   */
  function setPrefix($prefix)
  {
    $this->prefix_ = $prefix;
  }
  /**
   * デフォルトのワンタイムチケットを発行する
   * 
   * デフォルトのワンタイムチケットを生成して返す。同名で以前に発行したチケットは無効になる。
   * @access public
   * @param string $name チケットの名前。発行時と検証時で同じ名前にしなければならない。
   * @return glxOnetimeTicket 新しいワンタイムチケット。
   */
  function& createTicket($name)
  {
    $ticket =& new TFOnetimeTicket();
    $ticket->setTicket($name, $this->generateToken_($name));
    TFSession::register($this->getSessionKey($name), $ticket);
    return $ticket;
  }
  /**
   * チケットをセッションに持たせる際のキーを返す
   * 
   * 発行されたチケットをセッション変数に持たせる際のキーを $name から作って返す。
   * @access public
   * @param string $name チケットの名前。発行時と検証時は同じ名前を使うこと。
   * @return string セッション変数のキーに使う文字列
   */
  function getSessionKey($name)
  {
    return TFTICKET_SESSION_PREFIX.$this->prefix_.$name;
  }
  /**
   * チケットに使われるトークンを生成する。
   * 
   * チケットのユニーク性を保証するために発行されるトークンを返す。
   * このトークンの生成にはクライアントを特定できるような情報（セッション ID など）を混ぜてはならない。
   * @access private
   * @param string $name チケットの名前。発行時と検証時は同じ名前を使うこと。今のところ未使用
   * @return string 生成されたトークン。
   */
  function generateToken_($name)
  {
    return md5(__LINE__.microtime().mt_rand());
  }
  /**
   * $name で特定されるチケットを捨てる（無効にする）。
   * 
   * $name で特定されるチケットをセッションから消去する。
   * 以後、$name に紐づくチケットは無効となり、検証に失敗する。
   * 
   * チケット検証に失敗したときや、検証に合格した後は、
   * 必ず当該チケットをこのメソッドで無効化すること。
   * 標準ではどちらも検証の際に自動で行われる。
   * 
   * @access public
   * @param string $name チケットの名前。発行時と検証時は同じ名前を使うこと。
   */
  function disposeTicket($name)
  {
    TFSession::unregister($this->getSessionKey($name));
  }
  /**
   * 指定された入力から得たチケットを検証する。
   * 
   * $name で特定されるチケットに関して、$source から送られてきたトークンを元に検証する。
   * 検証に成功するとそのチケットを破棄して TRUE を返す。それ以外の場合は FALSE を返す。
   * 
   * @access public
   * @param string $name チケットの名前。発行時と同じ名前を使うこと。
   * @param array $source $_GET, $_POST のいずれか。
   * @param bool $clearIfValid 検証に成功したときにチケットを破棄するなら TRUE, それ以外 FALSE
   * @return bool 検証に成功したとき TRUE, それ以外 FALSE
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
   * GET/POST から得たチケットを検証する。
   * 
   * GET/POST のどちらで受け取ったチケットも検証の対象にする。
   * 検証に成功するとそのチケットを破棄して TRUE を返す。それ以外の場合は FALSE を返す。
   * 
   * @access public
   * @param string $name チケットの名前。発行時と同じ名前を使うこと。
   * @param bool $clearIfValid 検証に成功したときにチケットを破棄するなら TRUE, それ以外 FALSE
   * @return bool 検証に成功したとき TRUE, それ以外 FALSE
   */
  function validateTicketInRequest($name, $clearIfValid = TRUE)
  {
    return $this->validate_($name, $_REQUEST, $clearIfValid);
  }
  /**
   * POST から得たチケットのみを検証する。
   * 
   * POST で受け取ったチケットだけを検証の対象にする。
   * 検証に成功するとそのチケットを破棄して TRUE を返す。それ以外の場合は FALSE を返す。
   * 
   * @access public
   * @param string $name チケットの名前。発行時と同じ名前を使うこと。
   * @param bool $clearIfValid 検証に成功したときにチケットを破棄するなら TRUE, それ以外 FALSE
   * @return bool 検証に成功したとき TRUE, それ以外 FALSE
   */
  function validateTicketInPost($name, $clearIfValid = TRUE)
  {
    return $this->validate_($name, $_POST, $clearIfValid);
  }
}
