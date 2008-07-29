<?php
/**
 * セッションオブジェクト
 * 
 * glxSession のぱくr(ry
 */
class TFSession
{
  /**
   * ほかの方法での $_SESSION へのアクセスに衝突しない、
   * 安全なセッション配列へのキーを返す
   * 
   * glxSession における $_SESSION へのアクセスは、すべて
   * このメソッドの戻り値をキーにして行う。
   * @access public
   * @param string $key セッション変数のキー
   * @return string 安全なセッション変数キー
   */
  function getSafeSessionArrKey($key)
  {
    return 'TFSESSION__' . $key;
  }
  /**
   * セッション変数へ値を保存する
   * 
   * セッション変数へキー $key, 値 $value を保存する。このとき $value は serialize() されるので、
   * 値を取り出す際に session_start() とクラス宣言との順番を気にする必要がない。
   * 
   * @access public
   * @param string $key セッション変数へのキー。
   * @param mixed $value セッション変数へ保存する値。
   */
  function register($key, $value)
  {
    $realKey = TFSession::getSafeSessionArrKey($key);
    $_SESSION[$realKey] = serialize($value);
  }
  /**
   * セッション変数へ値を保存する。
   * 
   * register() のシノニム
   * @access public
   * @param string $key セッション変数へのキー。
   * @param mixed $value セッション変数へ保存する値。
   */
  function set($key, $value)
  {
    TFSession::register($key, $value);
  }
  /**
   * セッション変数から値を得る
   * @param string $key セッション変数へのキー
   * @return mixed セッション変数の値
   */
  function get($key)
  {
    $realKey = TFSession::getSafeSessionArrKey($key);
    return unserialize($_SESSION[$realKey]);
  }
  /**
   * セッション変数から値を得て、セッションからその変数を削除する
   * @param string $key セッション変数へのキー
   * @return string セッション変数の値 
   */
  function pop($key)
  {
    $r = TFSession::get($key);
    TFSession::unregister($key);
    return $r;
  }
  /**
   * セッションから変数を削除する
   * @param string $key セッション変数へのキー
   */
  function unregister($key)
  {
    $realKey = TFSession::getSafeSessionArrKey($key);
    unset($_SESSION[$realKey]);
  }
  /**
   * セッションに変数が保存されているのか問い合わせる
   * @param string $key セッション変数へのキー
   * @return boolean セッションに値が保存されていれば TRUE, それ以外 FALSE
   */
  function isRegistered($key)
  {
    $realKey = TFSession::getSafeSessionArrKey($key);
    return isset($_SESSION[$realKey]);
  }
}

@session_start();
