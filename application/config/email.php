<?php

defined('BASEPATH') or exit('No direct script access allowed');

$config = array(
  'protocol' => 'smtp',
  'smtp_host' => 'smtp.gmail.com',
  'smtp_port' => 465,
  'smtp_user' => '...',
  'smtp_pass' => '...',
  'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
  'mailtype' => 'html', //plaintext 'text' mails or 'html'
  'smtp_timeout' => '15', //in seconds
  'charset' => 'iso-8859-1',
  'newline'   => "\r\n",
  'wordwrap' => TRUE
);
