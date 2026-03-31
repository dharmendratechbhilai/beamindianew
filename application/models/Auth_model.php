<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
    // Your own constructor code
  }

  function checkAuthentication($formData)
  {
    $this->db->where('username', $formData['username']);
    $this->db->where('password', $formData['password']);
    $this->db->where('is_active', 1);
    $query = $this->db->get('admin');
    if ($query->num_rows() == 1) {
      $result = $query->row();
      $sessionData = array(
        'id' => $result->id,
        'name' => $result->admin_name,
        'username' => $result->username,
        'phone' => $result->admin_phone,
        'email' => $result->admin_email,
        'admin-validated' => TRUE,
      );
      $this->session->set_userdata($sessionData);
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function checkCompanyAuthentication($formData)
  {
    $this->db->where('username', $formData['username']);
    $this->db->where('password', $formData['password']);
    $this->db->where('is_active', 1);
    $query = $this->db->get('companies');
    if ($query->num_rows() == 1) {
      $result = $query->row();
      $sessionData = array(
        'id' => $result->id,
        'comp_uid' => $result->comp_uid,
        'name' => $result->comp_name,
        'phone' => $result->comp_head_phone,
        'email' => $result->comp_head_email,
        'company-validated' => TRUE,
      );
      $this->session->set_userdata($sessionData);
      return TRUE;
    } else {
      return FALSE;
    }
  }
}
