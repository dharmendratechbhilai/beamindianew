<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Company extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Company_model');
  }

  public function AttendanceCronJob()
  {
    $today = date('Y-m-d');
    $attendanceCheckingData = $this->Root_model->fetchSpecificMultipleConditions(['attendance_date' => $today], 'employees_attendance');
    if (empty($attendanceCheckingData)) {
      return;
    } else {
      foreach ($attendanceCheckingData as $attendance) {
        $attendanceStatus = '';
        $companyPolicyData = get_cache('company_policy' . $attendance['comp_uid']);
        if (!$companyPolicyData) {
          $companyPolicyData = $this->Root_model->fetchSingleMultipleConditions(
            ['comp_uid' => $attendance['comp_uid']],
            'companies_policies'
          );
          update_cache('company_policy' . $attendance['comp_uid'], $companyPolicyData);
        }

        $policyIn   = strtotime($companyPolicyData['in_time']);
        $punchIn    = strtotime($attendance['punch_in']);
        $gracePeriod = GRACE_PERIOD; // in minutes

        if ($punchIn > ($policyIn + $gracePeriod)) {
          $attendanceStatus = 'PRESENT';
          $lateEntry = TRUE;
        } else {
          $attendanceStatus = 'PRESENT';
          $lateEntry = FALSE;
        }



        $this->Root_model->update(['attendance_uid' => $attendance['attendance_uid']], 'employees_attendance', ['status' => $attendanceStatus]);
      }
    }
  }

  public function load_departments()
  {
    $comp_uid = $this->session->userdata('comp_uid');

    if (get_cache('department' . $comp_uid)) {
      $departmentsData = get_cache('department' . $comp_uid);
    } else {
      $departmentsData = $this->Root_model->fetchSpecificMultipleConditions(
        ['comp_uid' => $comp_uid],
        'companies_department'
      );
      update_cache('department-' . $comp_uid, $departmentsData);
    }

    if (empty($departmentsData)) {
      echo json_encode(['html' => '<p class="text-danger mb-0">No departments data found.</p>']);
      return;
    }

    $html = '<table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Department Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>';
    $count = 1;
    foreach ($departmentsData as $dept) {
      $deptName = htmlspecialchars($dept['department_name'] ?? 'N/A');
      $html .= "<tr>
                      <td>{$count}</td>
                      <td>{$deptName}</td>
                      <td>
                          <button class='btn btn-sm btn-primary edit-dept' data-id='{$dept['id']}' data-dept-name='{$dept['department_name']}'>Edit</button>
                          <button class='btn btn-sm btn-danger delete-dept' data-id='{$dept['id']}'>Delete</button>
                      </td>
                    </tr>";
      $count++;
    }
    $html .= '</tbody></table>';
    echo json_encode(['html' => $html]);
  }

  public function errorPage()
  {
    $this->load->view('error_page');
  }

  public function index()
  {
    $data['title'] = "Homepage";
    $data['content'] = $this->load->view('company_end/home/index', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function companyDepartmentList()
  {
    $data['title'] = "Department List";
    $data['content'] = $this->load->view('company_end/configuration/department', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function add_department()
  {
    $data = $this->input->post();
    $comp_uid = $this->session->userdata('comp_uid');

    if (empty($data['departmentName'])) {
      echo json_encode(['status' => false, 'message' => 'Department name is required.']);
      return;
    }

    $insertionData = [
      'comp_uid' => $comp_uid,
      'department_name' => $data['departmentName']
    ];

    $isinserted = $this->Root_model->insert(
      'companies_department',
      $insertionData
    );
    if ($isinserted) {
      echo json_encode(['status' => true, 'message' => 'Department added successfully.']);
    } else {
      echo json_encode(['status' => false, 'message' => 'Failed to add department.']);
    }
  }

  public function edit_department()
  {
    $data = $this->input->post();
    $comp_uid = $this->session->userdata('comp_uid');

    if (empty($data['editDepartmentName'])) {
      echo json_encode(['status' => false, 'message' => 'Department name is required.']);
      return;
    }
    $insertionData = [
      'comp_uid' => $comp_uid,
      'department_name' => $data['editDepartmentName']
    ];

    $isinserted = $this->Root_model->update(
      ['id' => $data['id']],
      'companies_department',
      $insertionData
    );
    if ($isinserted) {
      echo json_encode(['status' => true, 'message' => 'Department updated successfully.']);
    } else {
      echo json_encode(['status' => false, 'message' => 'Failed to update department.']);
    }
  }

  public function delete_department()
  {
    $data = $this->input->post();
    $comp_uid = $this->session->userdata('comp_uid');

    $isinserted = $this->Root_model->delete(
      $data['id'],
      'id',
      'companies_department'
    );
    if ($isinserted) {
      echo json_encode(['status' => true, 'message' => 'Department deleted successfully.']);
    } else {
      echo json_encode(['status' => false, 'message' => 'Failed to delete department.']);
    }
  }

  public function companyDesignationList()
  {
    $data['title'] = "Designation List";
    $data['content'] = $this->load->view('company_end/configuration/designation', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function load_designations()
  {
    $comp_uid = $this->session->userdata('comp_uid');

    if (get_cache('designations' . $comp_uid)) {
      $designationsData = get_cache('designations' . $comp_uid);
    } else {
      $designationsData = $this->Root_model->fetchSpecificMultipleConditions(
        ['comp_uid' => $comp_uid],
        'companies_designation'
      );
      update_cache('designations-' . $comp_uid, $designationsData);
    }

    if (empty($designationsData)) {
      echo json_encode(['html' => '<p class="text-danger mb-0">No designations data found.</p>']);
      return;
    }

    $html = '<table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Designation Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>';
    $count = 1;
    foreach ($designationsData as $designation) {
      $designationName = htmlspecialchars($designation['designation'] ?? 'N/A');
      $html .= "<tr>
                      <td>{$count}</td>
                      <td>{$designationName}</td>
                      <td>
                          <button class='btn btn-sm btn-primary edit-designation' data-id='{$designation['id']}' data-designation-name='{$designation['designation']}'>Edit</button>
                          <button class='btn btn-sm btn-danger delete-designation' data-id='{$designation['id']}'>Delete</button>
                      </td>
                    </tr>";
      $count++;
    }
    $html .= '</tbody></table>';
    echo json_encode(['html' => $html]);
  }

  public function add_designation()
  {
    $data = $this->input->post();
    $comp_uid = $this->session->userdata('comp_uid');

    if (empty($data['designationName'])) {
      echo json_encode(['status' => false, 'message' => 'Designation name is required.']);
      return;
    }

    $insertionData = [
      'comp_uid' => $comp_uid,
      'designation' => $data['designationName']
    ];

    $isinserted = $this->Root_model->insert(
      'companies_designation',
      $insertionData
    );
    if ($isinserted) {
      echo json_encode(['status' => true, 'message' => 'Designation added successfully.']);
    } else {
      echo json_encode(['status' => false, 'message' => 'Failed to add designation.']);
    }
  }

  public function edit_designation()
  {
    $data = $this->input->post();
    $comp_uid = $this->session->userdata('comp_uid');

    if (empty($data['editDesignationName'])) {
      echo json_encode(['status' => false, 'message' => 'Designation name is required.']);
      return;
    }
    $insertionData = [
      'comp_uid' => $comp_uid,
      'designation' => $data['editDesignationName']
    ];

    $isinserted = $this->Root_model->update(
      ['id' => $data['id']],
      'companies_designation',
      $insertionData
    );
    if ($isinserted) {
      echo json_encode(['status' => true, 'message' => 'Designation updated successfully.']);
    } else {
      echo json_encode(['status' => false, 'message' => 'Failed to update designation.']);
    }
  }

  public function delete_designation()
  {
    $data = $this->input->post();
    $comp_uid = $this->session->userdata('comp_uid');

    $isinserted = $this->Root_model->delete(
      $data['id'],
      'id',
      'companies_designation'
    );
    if ($isinserted) {
      echo json_encode(['status' => true, 'message' => 'Designation deleted successfully.']);
    } else {
      echo json_encode(['status' => false, 'message' => 'Failed to delete designation.']);
    }
  }

  public function companyWorkLocationList()
  {
    $data['title'] = "Work Location List";
    $data['content'] = $this->load->view('company_end/configuration/work_location', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function load_work_locations()
  {
    $comp_uid = $this->session->userdata('comp_uid');

    if (get_cache('work_locations' . $comp_uid)) {
      $workLocationsData = get_cache('work_locations' . $comp_uid);
    } else {
      $workLocationsData = $this->Root_model->fetchSpecificMultipleConditions(
        ['comp_uid' => $comp_uid],
        'companies_work_location'
      );
      update_cache('work_locations-' . $comp_uid, $workLocationsData);
    }

    if (empty($workLocationsData)) {
      echo json_encode(['html' => '<p class="text-danger mb-0">No work locations data found.</p>']);
      return;
    }

    $html = '<table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Work Location Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>';
    $count = 1;
    foreach ($workLocationsData as $workLocation) {
      $workLocationName = htmlspecialchars($workLocation['location'] ?? 'N/A');
      $html .= "<tr>
                      <td>{$count}</td>
                      <td>{$workLocationName}</td>
                      <td>
                          <button class='btn btn-sm btn-primary edit-work-location' data-id='{$workLocation['id']}' data-work-location-name='{$workLocation['location']}'>Edit</button>
                          <button class='btn btn-sm btn-danger delete-work-location' data-id='{$workLocation['id']}'>Delete</button>
                      </td>
                    </tr>";
      $count++;
    }
    $html .= '</tbody></table>';
    echo json_encode(['html' => $html]);
  }

  public function add_work_location()
  {
    $data = $this->input->post();
    $comp_uid = $this->session->userdata('comp_uid');

    if (empty($data['workLocationName'])) {
      echo json_encode(['status' => false, 'message' => 'Work location name is required.']);
      return;
    }

    $insertionData = [
      'comp_uid' => $comp_uid,
      'location' => $data['workLocationName']
    ];

    $isinserted = $this->Root_model->insert(
      'companies_work_location',
      $insertionData
    );
    if ($isinserted) {
      echo json_encode(['status' => true, 'message' => 'Work location added successfully.']);
    } else {
      echo json_encode(['status' => false, 'message' => 'Failed to add work location.']);
    }
  }

  public function edit_work_location()
  {
    $data = $this->input->post();
    $comp_uid = $this->session->userdata('comp_uid');
    if (empty($data['editWorkLocationName'])) {
      echo json_encode(['status' => false, 'message' => 'Work location name is required.']);
      return;
    }
    $insertionData = [
      'comp_uid' => $comp_uid,
      'location' => $data['editWorkLocationName']
    ];
    $isinserted = $this->Root_model->update(
      ['id' => $data['id']],
      'companies_work_location',
      $insertionData
    );
    if ($isinserted) {
      echo json_encode(['status' => true, 'message' => 'Work location updated successfully.']);
    } else {
      echo json_encode(['status' => false, 'message' => 'Failed to update work location.']);
    }
  }

  public function delete_work_location()
  {
    $data = $this->input->post();
    $isinserted = $this->Root_model->delete(
      $data['id'],
      'id',
      'companies_work_location'
    );
    if ($isinserted) {
      echo json_encode(['status' => true, 'message' => 'Work location deleted successfully.']);
    } else {
      echo json_encode(['status' => false, 'message' => 'Failed to delete work location.']);
    }
  }

  public function companyPolicyList()
  {
    $data['title'] = "Policy List";
    $comp_uid = $this->session->userdata('comp_uid');
    $data['companyPolicyData'] = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid], 'companies_policies');
    $data['content'] = $this->load->view('company_end/configuration/policy', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function updateCompanyPolicy()
  {
    $data = $this->input->post();
    $comp_uid = $this->session->userdata('comp_uid');
    $insertionData = [
      $data['policy_key'] => $data['policy_value']
    ];
    $isinserted = $this->Root_model->update(
      ['comp_uid' => $comp_uid],
      'companies_policies',
      $insertionData
    );
    if ($isinserted) {
      echo json_encode(['status' => true, 'message' => 'Policy updated successfully.']);
    } else {
      echo json_encode(['status' => false, 'message' => 'Failed to update policy.']);
    }
  }

  public function companyEmployeeList()
  {
    $data['title'] = "Employee List";
    $comp_uid = $this->session->userdata('comp_uid');
    $data['companyEmployeeData'] = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid], 'employees');
    $data['departmentData'] = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid], 'companies_department');
    $data['designationData'] = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid], 'companies_designation');
    $data['workLocationData'] = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid], 'companies_work_location');
    $data['content'] = $this->load->view('company_end/employee/index', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function companyAddEmployee()
  {
    $data['title'] = "Add Employee";
    $comp_uid = $this->session->userdata('comp_uid');
    $data['companyEmployeeData'] = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid], 'employees');
    $data['departmentData'] = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid], 'companies_department');
    $data['designationData'] = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid], 'companies_designation');
    $data['content'] = $this->load->view('company_end/employee/add', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function getEmpCode()
  {
    $comp_uid = $this->session->userdata('comp_uid');
    $lastCode = $this->Company_model->fetchLastEmployeeCode($comp_uid);
    if ($lastCode === false) {
      $newEmpCode = "EMP00001";
    } else {
      $num = (int) substr($lastCode, -5);
      $nextNum = $num + 1;
      $newEmpCode = "EMP" . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
    }
    echo json_encode(['emp_code' => $newEmpCode]);
  }

  public function doCompanyAddEmployee()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $this->form_validation->set_rules('emp_uid', 'Employee UID', 'required|trim');
      $this->form_validation->set_rules('emp_code', 'Employee Code', 'required|trim');
      $this->form_validation->set_rules('joining_date', 'Joining Date', 'required|trim');
      $this->form_validation->set_rules('department_uid', 'Department', 'required|trim');
      $this->form_validation->set_rules('designation_uid', 'Designation', 'required|trim');
      $this->form_validation->set_rules('reporting_manager_uid', 'Reporting Manager', 'required|trim');
      $this->form_validation->set_rules('employee_type', 'Employee Type', 'required|trim');
      $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
      $this->form_validation->set_rules('middle_name', 'Middle Name', 'trim');
      $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
      $this->form_validation->set_rules('email', 'Email', 'required|trim|is_unique[employees.emp_email]');
      $this->form_validation->set_rules('mobile', 'Mobile', 'required|trim|is_unique[employees.emp_phone]');
      $this->form_validation->set_rules('gender', 'Gender', 'required|trim');
      $this->form_validation->set_rules('dob', 'Date of Birth', 'required|trim');

      if ($this->form_validation->run() == false) {
        $this->session->set_flashdata('fail', 'Employee Added Failed. Wrong Credentials');
        redirect('company-add-employee', 'refresh');
      } else {
        $data = $this->input->post();
        $comp_uid = $this->session->userdata('comp_uid');
        $insertionData = [
          'comp_uid' => $comp_uid,
          'employee_uid' => $data['emp_uid'],
          'employee_code' => $data['emp_code'],
          'joining_date' => $data['joining_date'],
          'department' => $data['department_uid'],
          'designation' => $data['designation_uid'],
          'reporting_manager_uid' => $data['reporting_manager_uid'],
          'employee_type' => $data['employee_type'],
          'first_name' => $data['first_name'],
          'middle_name' => $data['middle_name'],
          'last_name' => $data['last_name'],
          'emp_email' => $data['email'],
          'emp_phone' => $data['mobile'],
          'gender' => $data['gender'],
          'date_of_birth' => $data['dob'],
          'employement_status' => 'EMPLOYED',
        ];
        $isinserted = $this->Root_model->insert('employees', $insertionData);
        if ($isinserted) {
          $this->session->set_flashdata('success', 'Employee Added Successfully.');
          redirect('company-employee-list', 'refresh');
        } else {
          $this->session->set_flashdata('fail', 'Employee Added Failed. Wrong Credentials');
          redirect('company-add-employee', 'refresh');
        }
      }
    } else {
      $this->session->sess_destroy();
      redirect('company-login', 'refresh');
    }
  }

  public function companyEmployeeDetails($id)
  {
    $data['title'] = "Employee Details";
    $data['employeeData'] = $this->Root_model->fetchSingleMultipleConditions(['id' => $id], 'employees');
    $comp_uid = $this->session->userdata('comp_uid');
    $data['companyEmployeeData'] = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid], 'employees');

    if (get_cache('department' . $comp_uid)) {
      $departmentsData = get_cache('department' . $comp_uid);
    } else {
      $departmentsData = $this->Root_model->fetchSpecificMultipleConditions(
        ['comp_uid' => $comp_uid],
        'companies_department'
      );
      update_cache('department' . $comp_uid, $departmentsData);
    }
    foreach ($departmentsData as $key => $value) {
      if ($value['id'] == $data['employeeData']['department']) {
        $data['employeeData']['department_name'] = $value['department_name'];
      }
    }

    $data['departmentData'] = $departmentsData;

    if (get_cache('designation' . $comp_uid)) {
      $designationsData = get_cache('designation' . $comp_uid);
    } else {
      $designationsData = $this->Root_model->fetchSpecificMultipleConditions(
        ['comp_uid' => $comp_uid],
        'companies_designation'
      );
      update_cache('designation' . $comp_uid, $designationsData);
    }
    foreach ($designationsData as $key => $value) {
      if ($value['id'] == $data['employeeData']['designation']) {
        $data['employeeData']['designation_name'] = $value['designation'];
      }
    }

    $data['designationData'] = $designationsData;

    $data['employeeAddressData'] = $this->Root_model->fetchSingleMultipleConditions(
      ['comp_uid' => $comp_uid, 'employee_uid' => $data['employeeData']['employee_uid']],
      'employees_address'
    );

    $data['employeeAccountData'] = $this->Root_model->fetchSingleMultipleConditions(
      ['comp_uid' => $comp_uid, 'employee_uid' => $data['employeeData']['employee_uid']],
      'employees_bank_details'
    );

    $data['employeeSalaryData'] = $this->Root_model->fetchSingleMultipleConditions(
      ['comp_uid' => $comp_uid, 'employee_uid' => $data['employeeData']['employee_uid']],
      'employees_salaries'
    );

    $data['content'] = $this->load->view('company_end/employee/details', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function updateEmployeeBasicDetails($id)
  {
    $data = $this->input->post();
    $comp_uid = $data['company_uid'];
    $employee_uid = $data['employee_uid'];

    $updationData = [
      'first_name' => $data['first_name'],
      'middle_name' => $data['middle_name'],
      'last_name' => $data['last_name'],
      'reporting_manager_uid' => $data['reporting_manager_uid'],
      'department' => $data['department_uid'],
      'designation' => $data['designation_uid'],
      'employee_type' => $data['employee_type'],
      'emp_phone' => $data['phone'],
      'emp_email' => $data['email'],
      'gender' => $data['gender'],
      'date_of_birth' => $data['dob'],
      'employement_status' => $data['employement_status'],
    ];

    $isUpdated = $this->Root_model->update([
      'comp_uid' => $comp_uid,
      'employee_uid' => $employee_uid,
    ], 'employees', $updationData);

    if ($isUpdated) {
      $this->session->set_flashdata('success', 'Employee Updated Successfully.');
      redirect('company-employee-details/' . $id, 'refresh');
    } else {
      $this->session->set_flashdata('fail', 'Employee Updated Failed. Wrong Credentials');
      redirect('company-employee-details/' . $id, 'refresh');
    }
  }

  public function updateEmployeeAddressDetails($id)
  {
    $data = $this->input->post();
    $updationData = [
      'comp_uid' => $data['comp_uid'],
      'employee_uid' => $data['emp_uid'],
      'address_type' => $data['address_type'],
      'street_address' => $data['street_address'],
      'city' => $data['city'],
      'district' => $data['district'],
      'state' => $data['state'],
      'pincode' => $data['pincode'],
    ];

    $data['employeeAddressData'] = $this->Root_model->fetchSingleMultipleConditions(
      ['comp_uid' => $data['comp_uid'], 'employee_uid' => $data['emp_uid']],
      'employees_address'
    );

    if (empty($data['employeeAddressData'])) {
      $isinserted = $this->Root_model->insert('employees_address', $updationData);
    } else {
      $isinserted = $this->Root_model->update(['id' => $data['employeeAddressData']['id']], 'employees_address', $updationData);
    }

    if ($isinserted) {
      $this->session->set_flashdata('success', 'Employee Updated Successfully.');
      redirect('company-employee-details/' . $id, 'refresh');
    } else {
      $this->session->set_flashdata('fail', 'Employee Updated Failed. Wrong Credentials');
      redirect('company-employee-details/' . $id, 'refresh');
    }
  }

  public function updateEmployeeBankDetails($id)
  {
    $data = $this->input->post();
    $updationData = [
      'comp_uid' => $data['comp_uid'],
      'employee_uid' => $data['emp_uid'],
      'bank_name' => $data['bank_name'],
      'ifsc_code' => $data['ifsc_code'],
      'account_holder_name' => $data['account_holder_name'],
      'account_num' => $data['account_number'],
      'upi_id' => $data['upi_id'],
    ];

    $data['employeeAccountData'] = $this->Root_model->fetchSingleMultipleConditions(
      ['comp_uid' => $data['comp_uid'], 'employee_uid' => $data['emp_uid']],
      'employees_bank_details'
    );

    if (empty($data['employeeAccountData'])) {
      $isinserted = $this->Root_model->insert('employees_bank_details', $updationData);
    } else {
      $isinserted = $this->Root_model->update(['id' => $data['employeeAccountData']['id']], 'employees_bank_details', $updationData);
    }

    if ($isinserted) {
      $this->session->set_flashdata('success', 'Employee Updated Successfully.');
      redirect('company-employee-details/' . $id, 'refresh');
    } else {
      $this->session->set_flashdata('fail', 'Employee Updated Failed. Wrong Credentials');
      redirect('company-employee-details/' . $id, 'refresh');
    }
  }

  public function updateEmployeeSalaryDetails($id)
  {
    $data = $this->input->post();
    $updationData = [
      'comp_uid' => $data['comp_uid'],
      'employee_uid' => $data['emp_uid'],
      'basic_salary' => $data['basic_salary'],
      'hra' => $data['house_rent'],
      'da' => $data['dearness_allowance'],
      'travel_allowance' => $data['travel_allowance'],
      'medical_allowance' => $data['medical_allowance'],
      'other_allowance' => $data['other_allowance'],
      'epf' => $data['employee_provident_fund'],
      'esi' => $data['employee_state_insurance'],
      'pt' => $data['professional_tax'],
    ];

    $data['employeeSalaryData'] = $this->Root_model->fetchSingleMultipleConditions(
      ['comp_uid' => $data['comp_uid'], 'employee_uid' => $data['emp_uid']],
      'employees_salaries'
    );

    if (empty($data['employeeSalaryData'])) {
      $isinserted = $this->Root_model->insert('employees_salaries', $updationData);
    } else {
      $isinserted = $this->Root_model->update(['id' => $data['employeeSalaryData']['id']], 'employees_salaries', $updationData);
    }

    if ($isinserted) {
      $this->session->set_flashdata('success', 'Employee Updated Successfully.');
      redirect('company-employee-details/' . $id, 'refresh');
    } else {
      $this->session->set_flashdata('fail', 'Employee Updated Failed. Wrong Credentials');
      redirect('company-employee-details/' . $id, 'refresh');
    }
  }

  public function employeeTodayAttendanceList($date)
  {
    $data['title'] = "Employee Today Attendance List";
    $comp_uid = $this->session->userdata('comp_uid');

    // Fetch attendance + employee details
    $attendanceData = $this->Company_model->fetchTodayAttendanceList($comp_uid, $date);

    $departmentsData = get_cache('department' . $comp_uid);
    if (!$departmentsData) {
      $departmentsData = $this->Root_model->fetchSpecificMultipleConditions(
        ['comp_uid' => $comp_uid],
        'companies_department'
      );
      update_cache('department' . $comp_uid, $departmentsData);
    }
    // Convert to ID-indexed array (fast lookup)
    $departmentsIndexed = array_column($departmentsData, null, 'id');

    $designationsData = get_cache('designation' . $comp_uid);
    if (!$designationsData) {
      $designationsData = $this->Root_model->fetchSpecificMultipleConditions(
        ['comp_uid' => $comp_uid],
        'companies_designation'
      );
      update_cache('designation' . $comp_uid, $designationsData);
    }
    $designationsIndexed = array_column($designationsData, null, 'id');

    // ------- Load Work Locations -------
    $workLocationsData = get_cache('work_location' . $comp_uid);
    if (!$workLocationsData) {
      $workLocationsData = $this->Root_model->fetchSpecificMultipleConditions(
        ['comp_uid' => $comp_uid],
        'companies_work_location'
      );
      update_cache('work_location' . $comp_uid, $workLocationsData);
    }
    $workLocationsIndexed = array_column($workLocationsData, null, 'id');

    $companyPolicyData = get_cache('company_policy' . $comp_uid);
    if (!$companyPolicyData) {
      $companyPolicyData = $this->Root_model->fetchSingleMultipleConditions(
        ['comp_uid' => $comp_uid],
        'companies_policies'
      );
      update_cache('company_policy' . $comp_uid, $companyPolicyData);
    }

    foreach ($attendanceData as $i => $row) {

      $deptId = $row['department'] ?? null;
      $attendanceData[$i]['department_name'] =
        $departmentsIndexed[$deptId]['department_name'] ?? '';

      $desigId = $row['designation'] ?? null;
      $attendanceData[$i]['designation_name'] =
        $designationsIndexed[$desigId]['designation'] ?? '';

      $locId = $row['work_location'] ?? null;
      $attendanceData[$i]['work_location_name'] =
        $workLocationsIndexed[$locId]['location'] ?? '';
      if (empty($row['punch_out'])) {
        // NULL or empty punch-out
        $attendanceData[$i]['in_time_status'] = 'PENDING';
        $attendanceData[$i]['late_by'] = '00:00:00';
      } else {
        $punchIn    = strtotime($row['punch_in']);
        $policyIn   = strtotime($companyPolicyData['in_time']);
        if ($punchIn > $policyIn) {
          $attendanceData[$i]['in_time_status'] = 'LATE';
          $diffSeconds = $punchIn - $policyIn;
          $attendanceData[$i]['late_by'] = gmdate("H:i:s", $diffSeconds);
        } else {
          $attendanceData[$i]['in_time_status'] = 'ON TIME';
          $attendanceData[$i]['late_by'] = '00:00:00';
        }
      }

      if (empty($row['punch_out'])) {
        // NULL or empty punch-out
        $attendanceData[$i]['out_time_status'] = 'PENDING';
        $attendanceData[$i]['early_by'] = '00:00:00';
      } else {
        $punchOut  = strtotime($row['punch_out']);
        $policyOut = strtotime($companyPolicyData['out_time']);

        // EARLY if employee left before policy time
        if ($punchOut < $policyOut) {
          $attendanceData[$i]['out_time_status'] = 'EARLY';
          $diffSeconds = $policyOut - $punchOut;
          $attendanceData[$i]['early_by'] = gmdate("H:i:s", $diffSeconds);
        } else {
          $attendanceData[$i]['out_time_status'] = 'ON TIME';
          $attendanceData[$i]['early_by'] = '00:00:00';
        }
      }
    }

    $data['attendanceData'] = $attendanceData;
    $data['date'] = $date;
    $data['content'] = $this->load->view('company_end/attendance/today', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function employeeAttendanceDetails()
  {
    $data['title'] = "Employee Attendance Details";
    $comp_uid = $this->session->userdata('comp_uid');
    $employeesData = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid], 'employees');
    $data['employeesData'] = $employeesData;
    $data['content'] = $this->load->view('company_end/employee/emp_attendance_details', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function todayAttendanceDetails($emp_uid)
  {
    $data['title'] = "Employee Attendance Details";
    $comp_uid = $this->session->userdata('comp_uid');
    $data['comp_uid'] = $comp_uid;
    $data['emp_uid'] = $emp_uid;
    $data['employeeData'] = $this->Root_model->fetchSingleMultipleConditions(['comp_uid' => $comp_uid, 'employee_uid' => $emp_uid], 'employees');
    $data['attendanceData'] = $this->Root_model->fetchSingleMultipleConditions(['comp_uid' => $comp_uid, 'employee_uid' => $emp_uid, 'attendance_date' => date('Y-m-d')], 'employees_attendance');


    if (get_cache('department' . $comp_uid)) {
      $departmentsData = get_cache('department' . $comp_uid);
    } else {
      $departmentsData = $this->Root_model->fetchSpecificMultipleConditions(
        ['comp_uid' => $comp_uid],
        'companies_department'
      );
      update_cache('department' . $comp_uid, $departmentsData);
    }
    foreach ($departmentsData as $key => $value) {
      if ($value['id'] == $data['employeeData']['department']) {
        $data['employeeData']['department_name'] = $value['department_name'];
      }
    }

    $data['departmentData'] = $departmentsData;

    if (get_cache('designation' . $comp_uid)) {
      $designationsData = get_cache('designation' . $comp_uid);
    } else {
      $designationsData = $this->Root_model->fetchSpecificMultipleConditions(
        ['comp_uid' => $comp_uid],
        'companies_designation'
      );
      update_cache('designation' . $comp_uid, $designationsData);
    }
    foreach ($designationsData as $key => $value) {
      if ($value['id'] == $data['employeeData']['designation']) {
        $data['employeeData']['designation_name'] = $value['designation'];
      }
    }


    $companyPolicyData = get_cache('company_policy' . $comp_uid);
    if (!$companyPolicyData) {
      $companyPolicyData = $this->Root_model->fetchSingleMultipleConditions(
        ['comp_uid' => $comp_uid],
        'companies_policies'
      );
      update_cache('company_policy' . $comp_uid, $companyPolicyData);
    }

    $data['policyData'] = $companyPolicyData;
    $data['gracePeriod'] = GRACE_PERIOD; // in minutes

    $data['content'] = $this->load->view('company_end/attendance/details', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function loadEmployeeDetails()
  {
    $emp_uid = $this->input->post('employeeUid');
    $currentYearMonth = $this->input->post('currentYearMonth');
    $comp_uid = $this->session->userdata('comp_uid');
    $employeeData = $this->Root_model->fetchSingleMultipleConditions(['comp_uid' => $comp_uid, 'employee_uid' => $emp_uid], 'employees');

    if (get_cache('department' . $comp_uid)) {
      $departmentsData = get_cache('department' . $comp_uid);
    } else {
      $departmentsData = $this->Root_model->fetchSpecificMultipleConditions(
        ['comp_uid' => $comp_uid],
        'companies_department'
      );
      update_cache('department' . $comp_uid, $departmentsData);
    }
    foreach ($departmentsData as $key => $value) {
      if ($value['id'] == $employeeData['department']) {
        $employeeData['department_name'] = $value['department_name'];
      }
    }

    if (get_cache('designation' . $comp_uid)) {
      $designationsData = get_cache('designation' . $comp_uid);
    } else {
      $designationsData = $this->Root_model->fetchSpecificMultipleConditions(
        ['comp_uid' => $comp_uid],
        'companies_designation'
      );
      update_cache('designation' . $comp_uid, $designationsData);
    }
    foreach ($designationsData as $key => $value) {
      if ($value['id'] == $employeeData['designation']) {
        $employeeData['designation_name'] = $value['designation'];
      }
    }

    $emp_html = '';
    $emp_html .= '<ul class="list-group">';
    $emp_html .= '<li class="list-group-item"><small>Employee uid : </small> <span>' . $employeeData['employee_uid'] . '</span></li>';
    $emp_html .= '<li class="list-group-item"><small>Employee Code : </small> <span>' . $employeeData['employee_code'] . '</span></li>';
    $emp_html .= '<li class="list-group-item"><small>Department : </small> <span>' . $employeeData['department_name'] . '</span></li>';
    $emp_html .= '<li class="list-group-item"><small>Designation : </small> <span>' . $employeeData['designation_name'] . '</span></li>';
    $emp_html .= '</ul>';

    $attendanceData = $this->Company_model->fetchCurrentYearMonthAttendance($comp_uid, $emp_uid, $currentYearMonth);



    $startDate  = $currentYearMonth . '-01';
    $total_days = date('t', strtotime($startDate));
    $present_days = 0;
    $absent_days = 0;
    $halfday_days = 0;
    $leave_days = 0;
    $regularize_days = 0;

    $attendance_html = '';
    $attendance_html .= '<table class="table table-bordered">';
    $attendance_html .= '<thead>';
    $attendance_html .= '<tr>';
    $attendance_html .= '<th scope="col">Date</th>';
    $attendance_html .= '<th scope="col">Punch in</th>';
    $attendance_html .= '<th scope="col">Punch out</th>';
    $attendance_html .= '<th scope="col">Status</th>';
    $attendance_html .= '</tr>';
    $attendance_html .= '</thead>';
    $attendance_html .= '<tbody>';
    foreach ($attendanceData as $key => $value) {
      switch ($value['status']) {
        case 'PRESENT':
          $present_days++;
          break;

        case 'ABSENT':
          $absent_days++;
          break;

        case 'HALF-DAY':
          // Half-day counts as 0.5 present and 0.5 absent (adjust based on your logic)
          $halfday_days++;
          break;

        case 'REGULARIZE':
          $regularize_days++;
          break;

        case 'LEAVE':
          $leave_days++;
          break;

        default:
          // Unknown or empty status
          break;
      }

      $attendance_html .= '<tr>';
      $attendance_html .= '<td>' . $value['attendance_date'] . '</td>';
      $attendance_html .= '<td>' . ($value['punch_in']  ?? '-') . '</td>';
      $attendance_html .= '<td>' . ($value['punch_out'] ?? '-') . '</td>';

      $attendance_html .= '<td>' . $value['status'] . '</td>';
      $attendance_html .= '</tr>';
    }
    $attendance_html .= '</tbody>';
    $attendance_html .= '</table>';

    echo json_encode([
      'status' => true,
      'employeeData' => $emp_html,
      'attendanceData' => $attendance_html,
      'total_days' => $total_days,
      'present_days' => $present_days,
      'absent_days' => $absent_days,
      'leave_days' => $leave_days,
      'regularize_days' => $regularize_days,
    ]);
  }

  public function companyLeaveList()
  {
    $data['title'] = "Employee Leaves";
    $comp_uid = $this->session->userdata('comp_uid');
    $employeesLeaveData = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid, 'status' => 'PENDING'], 'employees_leaves');

    if (!empty($employeesLeaveData)) {
      foreach ($employeesLeaveData as &$employeeLeave) {
        $employeeLeave['employee_Data'] = $this->Root_model->fetchSingleMultipleConditions(['comp_uid' => $comp_uid, 'employee_uid' => $employeeLeave['employee_uid']], 'employees');
      }
    }
    $data['employeesLeaveData'] = $employeesLeaveData;
    $data['content'] = $this->load->view('company_end/leave/index', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function companyApprovedLeaveList()
  {
    $data['title'] = "Employee Approved Leaves";
    $comp_uid = $this->session->userdata('comp_uid');
    $employeesLeaveData = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid, 'status' => 'APPROVED'], 'employees_leaves');

    if (!empty($employeesLeaveData)) {
      foreach ($employeesLeaveData as &$employeeLeave) {
        $employeeLeave['employee_Data'] = $this->Root_model->fetchSingleMultipleConditions(['comp_uid' => $comp_uid, 'employee_uid' => $employeeLeave['employee_uid']], 'employees');
      }
    }
    $data['employeesLeaveData'] = $employeesLeaveData;
    $data['content'] = $this->load->view('company_end/leave/approved', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function companyRejectedLeaveList()
  {
    $data['title'] = "Employee Rejected Leaves";
    $comp_uid = $this->session->userdata('comp_uid');
    $employeesLeaveData = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid, 'status' => 'REJECTED'], 'employees_leaves');

    if (!empty($employeesLeaveData)) {
      foreach ($employeesLeaveData as &$employeeLeave) {
        $employeeLeave['employee_Data'] = $this->Root_model->fetchSingleMultipleConditions(['comp_uid' => $comp_uid, 'employee_uid' => $employeeLeave['employee_uid']], 'employees');
      }
    }
    $data['employeesLeaveData'] = $employeesLeaveData;
    $data['content'] = $this->load->view('company_end/leave/rejected', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function companyAssetsList()
  {
    $data['title'] = "Company Asset Management";
    $comp_uid = $this->session->userdata('comp_uid');
    $assetsData = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid], 'companies_assets');

    if (!empty($assetsData)) {
      foreach ($assetsData as &$employeeLeave) {
        $employeeLeave['employee_Data'] = $this->Root_model->fetchSingleMultipleConditions(['comp_uid' => $comp_uid, 'employee_uid' => $employeeLeave['employee_uid']], 'employees');
      }
    }
    $data['assetsData'] = $assetsData;
    $data['content'] = $this->load->view('company_end/assets/index', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function companyAddAsset()
  {
    $data['title'] = "Add Employee Asset";
    $comp_uid = $this->session->userdata('comp_uid');
    $employeesData = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid], 'employees');
    $data['employeesData'] = $employeesData;
    $data['content'] = $this->load->view('company_end/assets/add', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function doCompanyAssignAsset()
  {
    $data = $this->security->xss_clean($_POST);
    $insertionData = [
      'comp_uid' => $this->session->userdata('comp_uid'),
      'employee_uid' => $data['emp_uid'],
      'asset_details' => $data['assetDetails'],
      'issue_date' => $data['issuedDate'],
    ];

    $isinserted = $this->Root_model->insert('companies_assets', $insertionData);
    if ($isinserted) {
      $this->session->set_flashdata('success', 'Asset Assigned Successfully.');
      redirect('company-assets-list', 'refresh');
    } else {
      $this->session->set_flashdata('fail', 'Asset Assignment Failed.');
      redirect('company-add-asset', 'refresh');
    }
  }

  public function editAssetAssignment($id)
  {
    $data['title'] = "Edit Employee Asset";
    $comp_uid = $this->session->userdata('comp_uid');
    $data['assetData'] = $this->Root_model->fetchSingleMultipleConditions(['id' => $id], 'companies_assets');
    $data['employeesData'] = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid], 'employees');
    $data['content'] = $this->load->view('company_end/assets/edit', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function doCompanyEditAssignAsset($id)
  {
    $data = $this->security->xss_clean($_POST);
    $assetReturned = isset($data['is_active']) ? 1 : 0;
    $updationData = [
      'employee_uid' => $data['emp_uid'],
      'asset_details' => $data['assetDetails'],
      'issue_date' => $data['issuedDate'],
      'is_active' => $assetReturned
    ];
    $isinserted = $this->Root_model->update(['id' => $id], 'companies_assets', $updationData);
    if ($isinserted) {
      $this->session->set_flashdata('success', 'Assignment updated Successfully.');
      redirect('company-assets-list', 'refresh');
    } else {
      $this->session->set_flashdata('fail', 'Assignment update Failed.');
      redirect('edit-asset-assignment/' . $id, 'refresh');
    }
  }

  public function companyHolidayList()
  {
    $data['title'] = "Company holiday list";
    $comp_uid = $this->session->userdata('comp_uid');
    $data['holidayData'] = $this->Root_model->fetchSpecificMultipleConditions(['comp_uid' => $comp_uid], 'companies_holidays');
    $data['content'] = $this->load->view('company_end/holiday/index', $data, true);
    $this->load->view('company_end/layout/master_back', $data);
  }

  public function doCompanyAddHoliday()
  {
    $data = $this->security->xss_clean($_POST);
    $insertionData = [
      'comp_uid' => $this->session->userdata('comp_uid'),
      'date' => $data['holiday_date'],
      'holiday' => $data['holiday_name'],
    ];
    $isinserted = $this->Root_model->insert('companies_holidays', $insertionData);
    if ($isinserted) {
      $this->session->set_flashdata('success', 'Holiday listed Successfully.');
      redirect('company-holiday-list', 'refresh');
    } else {
      $this->session->set_flashdata('fail', 'Holiday listing Failed.');
      redirect('company-holiday-list', 'refresh');
    }
  }

  public function editHoliday()
  {
    $holidayId = $this->input->post('id');
    $holidayDate = $this->input->post('holiday_date');
    $holidayName = $this->input->post('holiday_name');

    $updationData = [
      'date' => $holidayDate,
      'holiday' => $holidayName,
    ];

    $isinserted = $this->Root_model->update(['id' => $holidayId], 'companies_holidays', $updationData);
    if ($isinserted) {
      echo json_encode(['status' => true, 'message' => 'Holiday updated successfully.']);
    } else {
      echo json_encode(['status' => false, 'message' => 'Failed to update Holiday data.']);
    }
  }

  public function deleteHoliday($id)
  {
    $ifDeleted = $this->Root_model->delete($id, 'id', 'companies_holidays');
    if ($ifDeleted) {
      $this->session->set_flashdata('success', 'Holiday deleted Successfully.');
      redirect('company-holiday-list', 'refresh');
    } else {
      $this->session->set_flashdata('fail', 'Holiday deletion Failed.');
      redirect('company-holiday-list', 'refresh');
    }
  }
}
