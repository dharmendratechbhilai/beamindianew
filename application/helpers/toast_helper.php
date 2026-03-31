<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('set_toast_message')) {
  function set_toast_message($message)
  {
    $CI = &get_instance();
    $CI->session->set_flashdata('toast_message', $message);
  }
}
