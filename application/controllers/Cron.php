<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Admin_model');
  }

  // Every morning 2:00 AM
  public function initiateAttendance()
  {
    $allEmployees = $this->Root_model->fetchSpecificMultipleConditions(['employement_status' => 'EMPLOYED'], 'employees');
    $date = date('Y-m-d');
    // No employees found
    if (empty($allEmployees)) {
      return false;
    }
    // Prepare batch array
    $batchData = [];
    foreach ($allEmployees as $emp) {
      $batchData[] = [
        'comp_uid'        => $emp['comp_uid'],      // If available in employees table
        'employee_uid'    => $emp['employee_uid'],  // Assuming field exists
        'attendance_date' => $date
      ];
    }
    // Insert in batch
    if (!empty($batchData)) {
      $this->db->insert_batch('employees_attendance', $batchData);
    }
    return true;
  }

  // Every Night 11:30 PM
  public function checkEmployeeAttendance()
  {
    $date = date('Y-m-d');
    $allCompanies = $this->Root_model->fetchSpecificMultipleConditions(['is_active' => true], 'companies');
    // Load company policies indexed
    $policyData = [];
    foreach ($allCompanies as $company) {
      $policyData[$company['comp_uid']] = $this->Root_model->fetchSingleMultipleConditions(['comp_uid' => $company['comp_uid']], 'companies_policies');
    }
    $allEmployeesAttendance = $this->Root_model->fetchSpecificMultipleConditions(['attendance_date' => $date], 'employees_attendance');
    if (!$allEmployeesAttendance) {
      return;
    }

    $batchUpdate = [];
    foreach ($allEmployeesAttendance as $attendance) {
      $compUid = $attendance['comp_uid'];
      $policy  = $policyData[$compUid] ?? null;
      $status      = 'PENDING';
      $lateEntry   = 0;
      $earlyExit   = 0;
      $overtime    = '00:00:00';
      $punchIn  = $attendance['punch_in'];
      $punchOut = $attendance['punch_out'];

      // ABSENT cases
      if (empty($punchIn) && empty($punchOut)) {
        $status = 'ABSENT';
        $workingHours = '00:00:00';
      } elseif (empty($punchIn) || empty($punchOut)) {
        $status = 'ABSENT';
        $workingHours = '00:00:00';
      } else {
        // PRESENT
        $status = 'PRESENT';
        $punchInTime  = new DateTime($punchIn);
        $punchOutTime = new DateTime($punchOut);
        // Working hours
        $workingHours = $punchInTime->diff($punchOutTime)->format('%H:%I');

        $workingHours = new DateTime($workingHours);

        if ($workingHours < '04:00:00') {
          $status = 'ABSENT';
        } elseif ($workingHours > '04:00:00' && $workingHours < '08:00:00') {
          $status = 'HALF_DAY';
        }

        // Late Entry and Early Exit
        if ($policy) {
          $policyInTime  = new DateTime($policy['in_time']);
          $policyOutTime = new DateTime($policy['out_time']);

          if ($punchInTime > $policyInTime) {
            $lateEntry = 1;
          }
          if ($punchOutTime < $policyOutTime) {
            $earlyExit = 1;
          }
          // Overtime
          if ($punchOutTime > $policyOutTime) {
            $overtime = $policyOutTime->diff($punchOutTime)->format('%H:%I');
          }
        }
      }
      $batchUpdate[] = [
        'id'             => $attendance['id'],
        'status'         => $status,
        'working_hours'  => $workingHours,
        'late_entry'     => $lateEntry,
        'early_exit'     => $earlyExit,
        'overtime'       => $overtime,
      ];
    }
    // Update batch
    if (!empty($batchUpdate)) {
      $this->db->update_batch('employees_attendance', $batchUpdate, 'id');
    }
  }

  // 1st of every month.
  public function monthlyLeaveAccural()
  {
    $allEmployees = $this->Root_model->fetchSpecificMultipleConditions(['employement_status' => 'EMPLOYED'], 'employees');
    // No employees found
    if (empty($allEmployees)) {
      return false;
    }
    // Prepare batch array
    $batchData = [];
    foreach ($allEmployees as $emp) {
      $companyPolicyData = get_cache('company_policy' . $emp['comp_uid']);
      if (!$companyPolicyData) {
        $companyPolicyData = $this->Root_model->fetchSingleMultipleConditions(
          ['comp_uid' => $emp['comp_uid']],
          'companies_policies'
        );
        update_cache('company_policy' . $emp['comp_uid'], $companyPolicyData);
      }
      $batchData[] = [
        'id' => $emp['id'],
        'leave_counter' => (float)$companyPolicyData['monthly_leave_accrual']
      ];
    }
    // Execute Batch Update
    if (!empty($batchData)) {
      $this->db->update_batch('employees', $batchData, 'id');
    }
    return true;
  }

  // Every day.
  public function EmployeeProbationCheck()
  {
    $allEmployees = $this->Root_model->fetchAll('employees');
    $today = new DateTime();

    if (empty($allEmployees)) {
      return false;
    }

    $batchData = [];
    foreach ($allEmployees as $emp) {

      if ($emp['employement_status'] === 'EMPLOYED') {
        continue;
      }

      $companyPolicyData = get_cache('company_policy' . $emp['comp_uid']);
      if (!$companyPolicyData) {
        $companyPolicyData = $this->Root_model->fetchSingleMultipleConditions(
          ['comp_uid' => $emp['comp_uid']],
          'companies_policies'
        );
        update_cache('company_policy' . $emp['comp_uid'], $companyPolicyData);
      }

      // Use DateTime for accurate month calculation
      $joiningDate = new DateTime($emp['joining_date']);
      $diff = $joiningDate->diff($today);

      // Total months passed = months + (years × 12)
      $totalMonths = $diff->m + ($diff->y * 12);

      // If employee completed 3 or more months
      if ($totalMonths >= $companyPolicyData['probation_period']) {
        $batchData[] = [
          'id' => $emp['id'], // primary key
          'employement_status' => 'EMPLOYED'
        ];
      }
    }
    if (!empty($batchData)) {
      $this->db->update_batch('employees', $batchData, 'id');
    }
    return true;
  }
}
