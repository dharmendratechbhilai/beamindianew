<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Admin_model');
  }
  public function blank()
  {
    $data['title'] = "Blank Page";
    $data['content'] = $this->load->view('backend/home/blank', $data, true);
    $this->load->view('backend/layout/master_back', $data);
  }

  public function dataTable()
  {
    $data['title'] = "Data Table";
    $data['content'] = $this->load->view('backend/home/data_table', $data, true);
    $this->load->view('backend/layout/master_back', $data);
  }

  public function adminLogout()
  {
    $this->session->sess_destroy();
    redirect('admin-login');
  }

  public function adminHome()
  {
    $data['title'] = "Admin Homepage";
    $data['content'] = $this->load->view('backend/home/index', $data, true);
    $this->load->view('backend/layout/master_back', $data);
  }

  public function adminFaq()
  {
    $data['title'] = "Admin FAQ's";
    $data['content'] = $this->load->view('backend/home/faq', $data, true);
    $this->load->view('backend/layout/master_back', $data);
  }

  public function companyList()
  {
    $data['title'] = "Company List";
    $data['companies'] = $this->Root_model->fetchAll('companies');
    $data['content'] = $this->load->view('backend/company/list', $data, true);
    $this->load->view('backend/layout/master_back', $data);
  }

  public function addCompany()
  {
    $data['title'] = "Add Company";
    $data['content'] = $this->load->view('backend/company/add', $data, true);
    $this->load->view('backend/layout/master_back', $data);
  }

  public function doAddCompany()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $this->form_validation->set_rules('company_name', 'Company Name', 'required|trim');
      $this->form_validation->set_rules('ceo_name', 'CEO Name', 'required|trim');
      $this->form_validation->set_rules('contact_number', 'Contact Number', 'required|trim');
      $this->form_validation->set_rules('email', 'Email', 'required|trim');
      $this->form_validation->set_rules('address', 'Address', 'required|trim');
      $this->form_validation->set_rules('username', 'Username', 'required|trim');
      $this->form_validation->set_rules('password', 'Password', 'required|trim');
      if ($this->form_validation->run() == false) {
        $this->session->set_flashdata('fail', 'Please Enter Valid Information for Login.');
        redirect('add-company', 'refresh');
      } else {
        $data = $this->security->xss_clean($_POST);
        $insertionData = array(
          'comp_uid' => strtoupper(uniqid()),
          'username' => $data['username'],
          'password' => md5($data['password']),
          'comp_name' => $data['company_name'],
          'comp_address' => $data['address'],
          'comp_head_name' => $data['ceo_name'],
          'comp_head_phone' => $data['contact_number'],
          'comp_head_email' => $data['email'],
        );
        $isTrueUser = $this->Root_model->insert('companies', $insertionData);
        if ($isTrueUser) {

          $this->Root_model->insert('companies_policy', array(
            'comp_uid' => $insertionData['comp_uid'],
          ));

          $this->session->set_flashdata('success', 'Company Added Successfully.');
          redirect('company-list', 'refresh');
        } else {
          $this->session->set_flashdata('fail', 'Company Not Added. Try Again.');
          redirect('add-company', 'refresh');
        }
      }
    } else {
      $this->session->set_flashdata('fail', 'Only POST methods are allowed.');
      redirect('company-login', 'refresh');
    }
  }

  public function editCompany($uid)
  {
    $data['title'] = "Edit Company";
    $data['getCompany'] = $this->Root_model->fetchSingle($uid, 'comp_uid', 'companies');
    $data['content'] = $this->load->view('backend/company/edit', $data, true);
    $this->load->view('backend/layout/master_back', $data);
  }

  public function doEditCompany($uid)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $this->form_validation->set_rules('company_name', 'Company Name', 'required|trim');
      $this->form_validation->set_rules('ceo_name', 'CEO Name', 'required|trim');
      $this->form_validation->set_rules('contact_number', 'Contact Number', 'required|trim');
      $this->form_validation->set_rules('email', 'Email', 'required|trim');
      $this->form_validation->set_rules('address', 'Address', 'required|trim');
      $this->form_validation->set_rules('username', 'Username', 'required|trim');
      $this->form_validation->set_rules('password', 'Password', 'required|trim');
      if ($this->form_validation->run() == false) {
        $this->session->set_flashdata('fail', 'Please Enter Valid Information for Edit.');
        redirect('edit-company/' . $uid, 'refresh');
      } else {
        $data = $this->security->xss_clean($_POST);

        $conditions = array(
          'comp_uid' => $uid
        );

        $updationData = array(
          'username' => $data['username'],
          'comp_name' => $data['company_name'],
          'comp_address' => $data['address'],
          'comp_head_name' => $data['ceo_name'],
          'comp_head_phone' => $data['contact_number'],
          'comp_head_email' => $data['email'],
        );
        $isTrueUser = $this->Root_model->update($conditions, 'companies', $updationData);
        if ($isTrueUser) {
          $this->session->set_flashdata('success', 'Company Updated Successfully.');
          redirect('company-list', 'refresh');
        } else {
          $this->session->set_flashdata('fail', 'Company Not Updated. Try Again.');
          redirect('edit-company/' . $uid, 'refresh');
        }
      }
    } else {
      $this->session->set_flashdata('fail', 'Only POST methods are allowed.');
      redirect('company-login', 'refresh');
    }
  }

  public function deleteCompany($uid)
  {
    $isTrueUser = $this->Root_model->delete($uid, 'comp_uid', 'companies');
    if ($isTrueUser) {
      $this->session->set_flashdata('success', 'Company Deleted Successfully.');
      redirect('company-list', 'refresh');
    } else {
      $this->session->set_flashdata('fail', 'Company Not Deleted. Try Again.');
      redirect('company-list', 'refresh');
    }
  }
}
