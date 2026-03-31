<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
  }

  public function verifyOtpModel($phone, $otp)
  {
    $this->db->where('mobile_num', $phone);
    $this->db->where('otp', $otp);
    $this->db->where('expired_at >=', date('Y-m-d H:i:s')); // check expiry
    $query = $this->db->get('otp');
    return $query->row_array();
  }
}
