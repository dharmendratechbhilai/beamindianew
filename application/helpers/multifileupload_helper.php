<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function upload_files($upload_path, $files)
{
  $CI = &get_instance();
  $CI->load->library('upload');
  $config['upload_path'] = $upload_path;
  $config['allowed_types'] = 'jpg|png|jpeg|webp';
  $config['max_size'] = 10000;
  $config['encrypt_name'] = TRUE;

  $CI->upload->initialize($config);

  $upload_errors = array();
  $file_names = array();

  foreach ($files['name'] as $key => $value) {
    $_FILES['userfile']['name'] = $files['name'][$key];
    $_FILES['userfile']['type'] = $files['type'][$key];
    $_FILES['userfile']['tmp_name'] = $files['tmp_name'][$key];
    $_FILES['userfile']['error'] = $files['error'][$key];
    $_FILES['userfile']['size'] = $files['size'][$key];

    if (!$CI->upload->do_upload()) {
      $upload_errors[] = $CI->upload->display_errors();
    } else {
      $file_names[] = $upload_path . $CI->upload->data('file_name');
    }
  }
  if (!empty($upload_errors)) {
    $res = array(
      'status' => false,
      'error' => implode(', ', $upload_errors),
    );
    return $res;
  } else {
    $res = array(
      'status' => true,
      'data' => $file_names,
    );
    return $res;
  }
}
