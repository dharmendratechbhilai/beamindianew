<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Auth_model');
  }

  public function index()
  {
    $this->load->view('company_end/Auth/login');
  }

  public function doCompanyLogin()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $this->form_validation->set_rules('username', 'User Name', 'required|trim');
      $this->form_validation->set_rules('password', 'Password', 'required|trim');
      if ($this->form_validation->run() == false) {
        $this->session->set_flashdata('fail', 'Please Enter Valid Information for Login.');
        redirect('company-login', 'refresh');
      } else {
        $data = $this->security->xss_clean($_POST);
        $formData = array(
          'username' => $data['username'],
          'password' => md5($data['password']),
        );
        $isTrueUser = $this->Auth_model->checkCompanyAuthentication($formData);
        if ($isTrueUser) {
          $this->session->set_flashdata('success', 'Admin Login Successful.');
          redirect('company-home', 'refresh');
        } else {
          $this->session->set_flashdata('fail', 'Login Failed. Wrong Credentials');
          redirect('company-login', 'refresh');
        }
      }
    } else {
      $this->session->set_flashdata('fail', 'Only POST methods are allowed.');
      redirect('company-login', 'refresh');
    }
  }

  public function companyLogout()
  {
    $this->session->sess_destroy();
    redirect('company-login', 'refresh');
  }

  public function adminLogin()
  {
    $this->load->view('backend/adminAuth/login');
  }

  public function doAdminLogin()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $this->form_validation->set_rules('username', 'User Name', 'required|trim');
      $this->form_validation->set_rules('password', 'Password', 'required|trim');
      if ($this->form_validation->run() == false) {
        $this->session->set_flashdata('fail', 'Please Enter Valid Information for Login.');
        redirect('admin-login', 'refresh');
      } else {
        $data = $this->security->xss_clean($_POST);
        $formData = array(
          'username' => $data['username'],
          'password' => md5($data['password']),
        );
        $isTrueUser = $this->Auth_model->checkAuthentication($formData);
        if ($isTrueUser) {
          $this->session->set_flashdata('success', 'Admin Login Successful.');
          redirect('admin-home', 'refresh');
        } else {
          $this->session->set_flashdata('fail', 'Login Failed. Wrong Credentials');
          redirect('admin-login', 'refresh');
        }
      }
    } else {
      $this->session->set_flashdata('fail', 'Only POST methods are allowed.');
      redirect('admin-login', 'refresh');
    }
  }
}
