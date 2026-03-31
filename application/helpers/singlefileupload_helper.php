<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function do_upload($field_name, $upload_path, $allowed_types = 'jpg|png|jpeg', $max_size = 2048)
{
  $CI = &get_instance();
  $CI->load->library('upload');

  $config['upload_path'] = $upload_path;
  $config['allowed_types'] = $allowed_types;
  $config['max_size'] = $max_size;
  $config['encrypt_name'] = TRUE;

  $CI->upload->initialize($config);

  if (!$CI->upload->do_upload($field_name)) {
    $error = $CI->upload->display_errors();
    $res = array(
      'status' => false,
      'error' => $error,
    );
    return $res;
  } else {
    $data = $CI->upload->data();
    $res = array(
      'status' => true,
      'filename' => $data['file_name'],
    );
    return $res;
  }
}
