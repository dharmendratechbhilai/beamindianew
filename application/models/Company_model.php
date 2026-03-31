<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Company_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
  }

  public function fetchLastEmployeeCode($comp_uid)
  {
    $this->db->select('employee_code')
      ->from('employees')
      ->where('comp_uid', $comp_uid)
      ->order_by('employee_code', 'desc')
      ->limit(1);

    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      return $query->row()->employee_code;
    } else {
      return false;
    }
  }

  public function fetchTodayAttendanceList($comp_uid, $date)
  {
    $this->db->select('
    employees_attendance.*,
    employees.employee_code,
    employees.first_name,
    employees.middle_name,
    employees.last_name,
    employees.department,
    employees.designation,
    ');
    $this->db->from('employees_attendance');
    $this->db->where('employees_attendance.comp_uid', $comp_uid);
    $this->db->where('employees_attendance.attendance_date', $date);
    $this->db->join('employees', 'employees.employee_uid = employees_attendance.employee_uid', 'inner');
    return $this->db->get()->result_array();
  }

  public function fetchCurrentYearMonthAttendance($comp_uid, $emp_uid, $currentYearMonth)
  {
    $startDate = $currentYearMonth . '-01';
    $endDate   = date("Y-m-t", strtotime($startDate));
    $this->db->where('comp_uid', $comp_uid);
    $this->db->where('employee_uid', $emp_uid);
    $this->db->where('attendance_date >=', $startDate);
    $this->db->where('attendance_date <=', $endDate);
    return $this->db->get('employees_attendance')->result_array();
  }

  // Not in use.
  public function checkEmployeeAppliedLeave($comp_uid, $emp_uid)
  {
    $startDate = date('Y-m-01');
    $endDate   = date("Y-m-t");
    $this->db->where('comp_uid', $comp_uid);
    $this->db->where('employee_uid', $emp_uid);
    $this->db->where('from_date <=', $endDate);
    $this->db->where('too_date >=', $startDate);
    return $this->db->get('employees_leaves')->result_array();
  }

  public function fetchCurrentYearLeave($comp_uid, $emp_uid, $currentYear)
  {
    $startDate = $currentYear . '-01-01';
    $endDate   = $currentYear . '-12-31';
    $this->db->where('comp_uid', $comp_uid);
    $this->db->where('employee_uid', $emp_uid);
    $this->db->where('created_at >=', $startDate);
    $this->db->where('created_at <=', $endDate);
    return $this->db->get('employees_leaves')->result_array();
  }
}
