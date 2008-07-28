<?php

$path = explode(PATH_SEPARATOR, ini_get('include_path'));
array_unshift($path, TF_LIB_PATH . '/pear/');
ini_set('include_path', implode(PATH_SEPARATOR, $path));

require_once dirname(__FILE__) . '/TFSmarty.class.php';

require_once 'Mail.php';
require_once 'Mail/mime.php';
require_once 'Mail/RFC822.php';

/*
 * 漢字対応メール送信クラス
 * 
 * memo: http://dozo.matrix.jp/pear/index.php/PEAR/Mail_Mime.html
 */
class TFMailer
{
  var $_smarty;
  var $_mime;
  var $_headers;
  function TFMailer($crlf = "\n")
  {
    $this->_smarty =& new TFSmarty();
    $this->_smarty->addTemplatePath(dirname(__FILE__) . '/mail_tpl');
    
    $this->_mime =& new Mail_mime($crlf);
    $this->_headers = array();
    
    $this->setHeader("X-Mailer", sprintf("TFMailer (PHP/%s; %s; %s)", PHP_VERSION, PHP_OS, PHP_SAPI));
  }
  
  function assign($name, $var)
  {
    $this->_smarty->assign($name, $var);
  }
  
  function fetchTXTBody($template_filename, $source_encoding = 'EUC-JP')
  {
    $body = mb_convert_encoding($this->_smarty->fetch($template_filename), 'ISO-2022-JP', $source_encoding);
    $this->_mime->setTXTBody($body);
    return $body;
  }
  
  function setTXTBody($body, $source_encoding = 'EUC-JP')
  {
    $body = mb_convert_encoding($body, 'ISO-2022-JP', $source_encoding);
    $this->_mime->setTXTBody($body);
    return $body;
  }
  
  // ヘッダの値をエンコードするときに使う
  function _encodeSubject($subject, $source_encoding = 'EUC-JP')
  {
    $original = mb_internal_encoding();
    $subject = mb_convert_encoding($subject, "ISO-2022-JP", $source_encoding);
    mb_internal_encoding("ISO-2022-JP");
    $subject = mb_encode_mimeheader($subject, "ISO-2022-JP");
    mb_internal_encoding($original);
    
    return $subject;
  }
  
  function _encodeAddressHeader($address, $source_encoding = 'EUC-JP')
  {
    if(trim($address->personal) != "")
    {
      return trim(sprintf("%s <%s@%s>",
                     $this->_encodeSubject($address->personal, $source_encoding),
                     $address->mailbox, $address->host));
    }
    else
    {
      return sprintf("%s@%s", $address->mailbox, $address->host);
    }
  }

  function _encodeAddressHeaders($source, $source_encoding = 'EUC-JP')
  {
    $addresses = Mail_RFC822::parseAddressList($source, 'localhost', FALSE, FALSE);
    $values = array();
    foreach($addresses as $address)
      $values[] = $this->_encodeAddressHeader($address, $source_encoding);
    return implode(', ', $values);
  }
  
  // 普通のヘッダを指定するときに使う
  function setHeader($name, $value)
  {
    $this->_headers[$name] = $this->_encodeSubject($value);
  }

  // メールアドレスを含んだヘッダを指定するときに使う
  function setAddressHeader($name, $value)
  {
    $this->_headers[$name] = $this->_encodeAddressHeaders($value);
  }
  
  function send($to, $method = 'mail')
  {
    $params = array(
      "html_charset" => "EUC-JP",
      "text_charset" => "ISO-2022-JP",
      "head_charset" => "ISO-2022-JP",
    );

    $body = $this->_mime->get($params);
    $headers = $this->_mime->headers($this->_headers);
 
    $mail =& Mail::factory($method);
    
    if(is_array($to))
      $to = array_map(array($this, '_encodeAddressHeaders'), $to, array_fill(0, count($to), 'EUC-JP'));
    else
      $to = $this->_encodeAddressHeaders($to);
    return $mail->send($to, $headers, $body);
  }
}

