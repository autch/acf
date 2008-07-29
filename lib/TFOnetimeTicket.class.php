<?php

/**
 * ワンタイムトークンのコンテナ
 * 
 * ワンタイムトークンに必要なトークン名やタイムスタンプを保持し、HTML の hidden タグを生成する。
 * このクラスと {@link glxTicketVerifier} を派生させてカスタマイズすることでトークンシステムをカスタマイズする。
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
   * トークンを設定し、タイムスタンプを現在時刻に設定する。
   * @param string $name トークンの名前。
   * @param string $token トークン文字列
   */
  function setTicket($name, $token)
  {
    $this->name_ = $name;
    $this->token_ = $token;
    $this->timestamp_ = time();
  }
  /**
   * トークン情報を返す。
   * @return array array('name' => 名前, 'token' => トークン, 'timestamp' => タイムスタンプ)
   */
  function getTicket()
  {
    return array('name' => $this->name_, 'token' => $this->token_, 'timestamp' => $this->timestamp_);
  }
  /**
   * HTML フォームで使える hidden タグを生成して返す
   * 
   * アプリケーションでは、このメソッドが返した html をそのまま埋め込んで使うことができる。
   * @return string <input type="hidden" ...>
   */
  function makeHTMLHidden()
  {
    $html = sprintf('<input type="hidden" name="%s" value="%s" />',
                    htmlspecialchars($this->name_), htmlspecialchars($this->token_));
    return $html;
  }
  /**
   * トークン名を返す
   * @return string トークン名
   */
  function getName()
  {
    return $this->name_;
  }
  /**
   * トークン文字列を返す
   * @return string トークン文字列
   */
  function getToken()
  {
    return $this->token_;
  }
  /**
   * トークン発行時のタイムスタンプを得る
   * @reutrn integer トークン発行時のタイムスタンプ。単位は秒。
   */
  function getTimestamp()
  {
    return $this->timestamp_;
  }
  /**
   * $_GET や $_POST から得られたトークンを検証する
   * @param string $token フォームから得られたトークン文字列
   * @param integer default_timeout トークンのタイムアウト。単位は秒で、デフォルトは 60
   * @return boolean トークン文字列が一致し、かつタイムアウトしていなければ TRUE, それ以外 FALSE
   */
  function validate($token, $default_timeout = 60) // in minutes
  {
    return ($token === $this->token_) && ((time() - $this->timestamp_) < $default_timeout * 60);
  }
  
}

