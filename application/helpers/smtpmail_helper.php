<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function send_mail($to, $subject, $message, $from = null)
{
  $CI = &get_instance();
  $CI->load->config('email');
  $CI->load->library('email');

  // Set a default sender if not provided
  if (!$from) {
    $from = $CI->config->item('smtp_user'); // Assuming your email config has 'smtp_user' for the sender
  }

  // Set email headers and body
  $CI->email->set_newline("\r\n");
  $CI->email->from($from);
  $CI->email->to($to);
  $CI->email->subject($subject);
  $CI->email->message($message);

  // Attempt to send email and return the result
  if ($CI->email->send()) {
    return true;
  } else {
    // Log email error message for debugging
    log_message('error', 'Email failed to send. Error: ' . $CI->email->print_debugger());
    return false;
  }
}
