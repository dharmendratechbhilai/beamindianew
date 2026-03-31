<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Api extends RestController
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('Api_model');
    $this->load->model('Company_model');

    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With');
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
      header('HTTP/1.1 200 OK');
      exit();
    }
  }

  //JWT Token 
  private function generateToken($data)
  {
    return $this->jwt_lib->generateToken($data);
  }

  //JWT Token
  private function validateToken()
  {
    $token = $this->input->get_request_header('Authorization', TRUE);
    if (!$token) {
      $this->response([
        'status' => FALSE,
        'message' => 'Unauthorized: Access Token is missing in the caller header.',
        'data' => [],
      ], 401);
      exit;
    }

    $received_Token = str_replace("Bearer ", "", $token);
    $decodedToken = $this->jwt_lib->decodeToken($received_Token);

    if (isset($decodedToken['error'])) {
      $this->response([
        'status' => FALSE,
        'message' => 'Unauthorized: ' . $decodedToken['error'],
        'data' => [],
      ], 401);
    }
    return $decodedToken;
  }

  public function check_get()
  {
    $this->response([
      'status' => TRUE,
      'message' => 'Test successful',
    ], 200);
  }
  public function sendLoginOtp_post()
  {
    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);
    $phone = $data['phone'];
    $employeeDetail = $this->Root_model->fetchSingleMultipleConditions(['emp_phone' => $phone], 'employees');
    if (!$employeeDetail) {
      $this->response([
        'status' => FALSE,
        'message' => 'Employee not found.',
        'data' => [],
      ], 404);
      return;
    }
    $otp = send_otp($phone);
    $otpInsertionData = [
      'mobile_num' => $phone,
      'otp' => $otp,
      'expired_at' => date('Y-m-d H:i:s', time() + OTP_EXPIRY_TIME), // OTP Expiry Time - 10 minutes
    ];
    $this->Root_model->insert('otp', $otpInsertionData);
    $this->response([
      'status' => TRUE,
      'message' => 'Employee found.',
      'data' => ['otp' => $otp],
    ], 200);
  }

  public function verifyLoginOtp_post()
  {
    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);

    $phone = $data['phone'];
    $otp = $data['otp'];
    $employeeDetail = $this->Root_model->fetchSingleMultipleConditions(['emp_phone' => $phone], 'employees');

    if (!$employeeDetail) {
      $this->response([
        'status' => FALSE,
        'message' => 'Employee not found.',
        'data' => [],
      ], 404);
      return;
    }
    $otpData = $this->Api_model->verifyOtpModel($phone, $otp);
    if (is_null($otpData)) {
      $this->response([
        'status' => FALSE,
        'message' => 'Something went wrong.',
        'data' => [],
      ], 404);
      return;
    }
    if ($otpData['otp'] != $otp) {
      $this->response([
        'status' => FALSE,
        'message' => 'Invalid OTP.',
        'data' => [],
      ], 401);
      return;
    }
    $issuedAt = time();
    $accessTokenExpiry = $issuedAt + ACCESS_TOKEN_LIFE;
    $refreshTokenExpiry = $issuedAt + REFRESH_TOKEN_LIFE;
    $basePayload = [
      'comp_uid' => $employeeDetail['comp_uid'],
      'emp_uid' => $employeeDetail['employee_uid'],
      'employement_status' => $employeeDetail['employement_status'],
      'leave_count' => $employeeDetail['leave_counter'],
      'compoff_count' => $employeeDetail['comp_off_counter'],
      'iat'           => $issuedAt
    ];
    $accessTokenPayload = array_merge($basePayload, ['exp' => $accessTokenExpiry]);
    $refreshTokenPayload = array_merge($basePayload, ['exp' => $refreshTokenExpiry]);
    $accessToken  = $this->generateToken($accessTokenPayload);
    $refreshToken = $this->generateToken($refreshTokenPayload);
    $updateConditions = ['employee_uid' => $employeeDetail['employee_uid']];
    $updateData = [
      'access_token'  => $accessToken,
      'refresh_token' => $refreshToken
    ];
    $isTokenUpdated = $this->Root_model->update($updateConditions, 'employees', $updateData);
    if ($isTokenUpdated) {
      $this->response([
        'status' => TRUE,
        'message' => 'Employee login successful.',
        'data' => ['access_token' => $accessToken, 'refresh_token' => $refreshToken],
      ], 200);
    } else {
      $this->response([
        'status' => FALSE,
        'message' => 'Token updation Failed.',
        'data' => [],
      ], 500);
    }
  }

  public function fetchEmployeeDetails_post()
  {
    $this->validateToken();

    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);

    $comp_uid = $data['comp_uid'];
    $emp_uid = $data['emp_uid'];

    $employeeDetail = $this->Root_model->fetchSingleMultipleConditions(['comp_uid' => $comp_uid, 'employee_uid' => $emp_uid], 'employees');

    $report_manager =  $this->Root_model->fetchSingleMultipleConditions(['comp_uid' => $comp_uid, 'employee_uid' => $employeeDetail['reporting_manager_uid']], 'employees', 'first_name, middle_name, last_name');
    $names = array_filter([
      $report_manager['first_name'] ?? '',
      $report_manager['middle_name'] ?? '',
      $report_manager['last_name'] ?? ''
    ]);

    $employeeDetail['report_manager'] = implode(' ', $names);

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
      if ($value['id'] == $employeeDetail['department']) {
        $employeeDetail['department_name'] = $value['department_name'];
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
      if ($value['id'] == $employeeDetail['designation']) {
        $employeeDetail['designation_name'] = $value['designation'];
      }
    }
    $this->response([
      'status' => TRUE,
      'message' => 'Employee details fetched successfully.',
      'data' => $employeeDetail,
    ], 200);
  }

  public function fetchEmployeeAttendanceAndMonthlySummary_get()
  {
    $tokenData = $this->validateToken();
    $comp_uid = $tokenData['comp_uid'];
    $emp_uid  = $tokenData['emp_uid'];

    $today = date('Y-m-d');

    $currentMonth = date('F');
    $currentMonthNum = date('m');
    $currentYear = date('Y');
    $totalDays = date('t');
    $dayPassed = date('j');

    $presentDays = 0;
    $absentDays = 0;
    $halfDays = 0;
    $leaveDays = 0;
    $lopDays = 0;
    $regularizeDays = 0;
    $lateDays = 0;
    $earlyExitDays = 0;
    $punchIn = false;
    $punchOut = false;
    $punchInTime = '--:--';
    $punchOutTime = '--:--';

    $attendanceData = $this->Root_model->fetchSpecificMultipleConditions(
      [
        'comp_uid' => $comp_uid,
        'employee_uid' => $emp_uid
      ],
      'employees_attendance'
    );

    if ($attendanceData) {
      foreach ($attendanceData as $attendance) {
        // Filter only current month records
        if (
          date('m', strtotime($attendance['attendance_date'])) != $currentMonthNum ||
          date('Y', strtotime($attendance['attendance_date'])) != $currentYear
        ) {
          continue;
        }

        if ($attendance['attendance_date'] == $today) {
          if (!is_null($attendance['punch_in'])) {
            $punchIn = true;
            $punchInTime = $attendance['punch_in'];
          }
          if (!is_null($attendance['punch_out'])) {
            $punchOut = true;
            $punchOutTime = $attendance['punch_out'];
          }
        }

        switch ($attendance['status']) {
          case 'PRESENT':
            $presentDays++;
            break;
          case 'ABSENT':
            $absentDays++;
            break;
          case 'HALF-DAY':
            $halfDays++;
            break;
          case 'LEAVE':
            $leaveDays++;
            break;
          case 'LOP':
            $lopDays++;
            break;
          case 'REGULARIZE':
            $regularizeDays++;
            break;
        }
        if (!empty($attendance['is_late']) && $attendance['is_late'] == 1) {
          $lateDays++;
        }

        if (!empty($attendance['early_exit']) && $attendance['early_exit'] == 1) {
          $earlyExitDays++;
        }
      }
    }

    $responseData = [
      'punchintime' => $punchInTime,
      'punchin' => $punchIn,
      'punchout' => $punchOut,
      'punchouttime' => $punchOutTime,
      'monthlySummary' => [
        'month'        => $currentMonth,
        'dayPassed' => $dayPassed . '/' . $totalDays . ' Days',
        'present'      => $presentDays . ' Days',
        'absent'       => $absentDays . ' Days',
        'halfday'      => $halfDays . ' Requests',
        'leaveTaken'   => $leaveDays . ' Days',
        'regularize' => $regularizeDays . ' Requests',
        'lop'          => $lopDays . ' Days',
        'late'         => $lateDays . ' Times',
        'early'        => $earlyExitDays . ' Times',
      ]
    ];

    $this->response([
      'status'  => true,
      'message' => 'Employee attendance and monthly summary fetched successfully.',
      'data'    => $responseData,
    ], 200);
  }

  public function fetchEmployeeAttendanceDetails_post()
  {
    $this->validateToken();
    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);

    $comp_uid = $data['comp_uid'];
    $emp_uid = $data['emp_uid'];

    $employeeDetail = $this->Root_model->fetchSingleMultipleConditions(['comp_uid' => $comp_uid, 'employee_uid' => $emp_uid, 'attendance_date' => date('Y-m-d')], 'employees_attendance', 'comp_uid,employee_uid,attendance_date,punchin_location_id,punchin_location,punch_in,punch_out');
    $this->response([
      'status' => TRUE,
      'message' => 'Employee Todays Attendance fetched successfully.',
      'data' => $employeeDetail,
    ], 200);
  }
  public function checkEmployeeWithinRange_post()
  {
    $this->validateToken();
    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);

    $comp_uid = $data['comp_uid'];
    $employee_uid = $data['emp_uid'];
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];

    $response = checkEmpWithinRadius($latitude, $longitude, ['comp_uid' => $comp_uid, 'employee_uid' => $employee_uid]);
    if ($response['status'] == false) {
      $this->response([
        'status' => FALSE,
        'message' => $response['message'],
        'data' => [],
      ], 404);
      return;
    } else {
      $this->response([
        'status' => TRUE,
        'message' => $response['message'],
        'data' => $response,
      ], 200);
    }
  }

  /*public function employeeAttendanceSwipeIn_post()
  {
    $this->validateToken();
    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);

    $comp_uid = $data['comp_uid'];
    $employee_uid = $data['emp_uid'];
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];
    $work_location_id = $data['work_location_id'];
    $work_location_name = $data['work_location_name'];

    $updationData = [
      'punch_in' => date('H:i:s'),
      'punch_in_coordinates' => "{$latitude},{$longitude}",
      'punchin_location_id' => $work_location_id,
      'punchin_location' => $work_location_name,
    ];

    $conditions = [
      'comp_uid' => $comp_uid,
      'employee_uid' => $employee_uid,
      'attendance_date' => date('Y-m-d'),
    ];

    $isupdated = $this->Root_model->update($conditions, 'employees_attendance', $updationData);
    if ($isupdated) {
      $this->response([
        'status' => TRUE,
        'message' => 'Employee attendance swipe in successfully.',
        'data' => [],
      ], 200);
    } else {
      $this->response([
        'status' => FALSE,
        'message' => 'Failed to swipe in employee attendance.',
        'data' => [],
      ], 500);
    }
  } */

  public function employeeAttendanceSwipeIn_post()
  {
    $this->validateToken();
    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);

    $comp_uid = $data['comp_uid'];
    $employee_uid = $data['emp_uid'];
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];
    $work_location_id = $data['work_location_id'];
    $work_location_name = $data['work_location_name'];
    $today_date = date('Y-m-d');

    $existing_record = $this->db->where([
      'comp_uid' => $comp_uid,
      'employee_uid' => $employee_uid,
      'attendance_date' => $today_date
    ])->get('employees_attendance')->row();

    if ($existing_record && !empty($existing_record->punch_in)) {
      $employeeDetail = $this->Root_model->fetchSingleMultipleConditions(['comp_uid' => $comp_uid, 'employee_uid' => $employee_uid, 'attendance_date' => date('Y-m-d')], 'employees_attendance', 'comp_uid,employee_uid,attendance_date,punchin_location_id,punchin_location,punch_in,punch_out');
      $this->response([
        'status' => FALSE,
        'message' => 'You have already punched in for today.',
        'data' => $employeeDetail,
      ], 200);
      return;
    }

    $attendanceData = [
      'comp_uid' => $comp_uid,
      'employee_uid' => $employee_uid,
      'attendance_date' => $today_date,
      'punch_in' => date('H:i:s'),
      'punch_in_coordinates' => "{$latitude},{$longitude}",
      'punchin_location_id' => $work_location_id,
      'punchin_location' => $work_location_name,
    ];

    if (!$existing_record) {
      $result = $this->Root_model->insert('employees_attendance', $attendanceData);
      $employeeDetail = $this->Root_model->fetchSingleMultipleConditions(['comp_uid' => $comp_uid, 'employee_uid' => $employee_uid, 'attendance_date' => date('Y-m-d')], 'employees_attendance', 'comp_uid,employee_uid,attendance_date,punchin_location_id,punchin_location,punch_in,punch_out');
      $msg = "Employee attendance swipe in successfully";
    } else {
      $employeeDetail = $this->Root_model->fetchSingleMultipleConditions(['comp_uid' => $comp_uid, 'employee_uid' => $employee_uid, 'attendance_date' => date('Y-m-d')], 'employees_attendance', 'comp_uid,employee_uid,attendance_date,punchin_location_id,punchin_location,punch_in,punch_out');
      $conditions = [
        'comp_uid' => $comp_uid,
        'employee_uid' => $employee_uid,
        'attendance_date' => $today_date,
      ];
      $result = $this->Root_model->update($conditions, 'employees_attendance', $attendanceData);
      $msg = "Employee attendance swipe in successfully";
    }

    if ($result) {
      $this->response([
        'status' => TRUE,
        'message' => $msg,
        'data' => $employeeDetail,
      ], 200);
    } else {
      $this->response([
        'status' => FALSE,
        'message' => 'Failed to process attendance.',
        'data' => [],
      ], 500);
    }
  }

  /* public function employeeAttendanceSwipeOut_post()
  {
    $this->validateToken();
    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);

    $comp_uid = $data['comp_uid'];
    $employee_uid = $data['emp_uid'];
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];
    $date = date('Y-m-d');
    $work_location_id = $data['work_location_id'];
    $work_location_name = $data['work_location_name'];

    $updationData = [
      'punch_out' => date('H:i:s'),
      'punch_out_coordinates' => "{$latitude},{$longitude}",
      'punchout_location_id' => $work_location_id,
      'punchout_location' => $work_location_name,
    ];
    $isupdated = $this->Root_model->update(['comp_uid' => $comp_uid, 'employee_uid' => $employee_uid, 'attendance_date' => $date], 'employees_attendance', $updationData);
    if ($isupdated) {
      $this->response([
        'status' => TRUE,
        'message' => 'Employee attendance swipe out successfully.',
        'data' => [],
      ], 200);
    } else {
      $this->response([
        'status' => FALSE,
        'message' => 'Failed to swipe out employee attendance.',
        'data' => [],
      ], 500);
    }
  } */

  public function employeeAttendanceSwipeOut_post()
  {
    $this->validateToken();
    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);
    if (empty($data)) {
      $this->response(['status' => FALSE, 'message' => 'Invalid Request Data'], 400);
      return;
    }

    $comp_uid = $data['comp_uid'];
    $employee_uid = $data['emp_uid'];
    $today_date = date('Y-m-d');

    $conditions = [
      'comp_uid' => $comp_uid,
      'employee_uid' => $employee_uid,
      'attendance_date' => $today_date
    ];
    $attendance_record = $this->Root_model->fetchSingleMultipleConditions($conditions, 'employees_attendance');
    if (!$attendance_record) {
      $this->response([
        'status' => FALSE,
        'message' => 'No swipe-in record found for today.',
        'data' => [],
      ], 404);
      return;
    }
    if (!empty($attendance_record['punch_out']) && $attendance_record['punch_out'] !== '00:00:00') {
      $employeeDetail = $this->Root_model->fetchSingleMultipleConditions(['comp_uid' => $comp_uid, 'employee_uid' => $employee_uid, 'attendance_date' => date('Y-m-d')], 'employees_attendance', 'comp_uid,employee_uid,attendance_date,punchin_location_id,punchin_location,punch_in,punch_out');
      $this->response([
        'status' => FALSE,
        'message' => 'You have already swiped out for today. Update not allowed.',
        'data' => $employeeDetail,
      ], 400);
      return;
    }
    $updationData = [
      'punch_out' => date('H:i:s'),
      'punch_out_coordinates' => ($data['latitude'] ?? '0') . ',' . ($data['longitude'] ?? '0'),
      'punchout_location_id' => $data['work_location_id'] ?? null,
      'punchout_location' => $data['work_location_name'] ?? null,
    ];
    $isupdated = $this->Root_model->update($conditions, 'employees_attendance', $updationData);
    if ($isupdated) {
      $employeeDetail = $this->Root_model->fetchSingleMultipleConditions(['comp_uid' => $comp_uid, 'employee_uid' => $employee_uid, 'attendance_date' => date('Y-m-d')], 'employees_attendance', 'comp_uid,employee_uid,attendance_date,punchin_location_id,punchin_location,punch_in,punch_out');
      $this->response([
        'status' => TRUE,
        'message' => 'Employee attendance swipe out successfully.',
        'data' => $employeeDetail,
      ], 200);
    } else {
      $this->response([
        'status' => FALSE,
        'message' => 'Failed to swipe out employee attendance.',
        'data' => [],
      ], 500);
    }
  }

  public function employeeLeaveDetails_get()
  {
    $tokenData = $this->validateToken();
    $comp_uid = $tokenData['comp_uid'];
    $emp_uid = $tokenData['emp_uid'];
    $employement_status = $tokenData['employement_status'];
    $leaveCount = $tokenData['leave_count'];
    $compOffCount = $tokenData['compoff_count'];

    if ($employement_status == 'PROBATION') {
      $totalPaidLeaves = $leaveCount;
      $totalCompOff = $compOffCount;
      $leaveUsed = 0;

      $this->response([
        'status' => TRUE,
        'message' => 'Employee leave Data.',
        'data' => [
          'totalPaidLeaves' => $totalPaidLeaves,
          'totalCompOff' => $totalCompOff,
          'leaveUsed' => $leaveUsed
        ],
      ], 200);
    } else {
      $companyPolicyData = get_cache('company_policy' . $comp_uid);
      if (!$companyPolicyData) {
        $companyPolicyData = $this->Root_model->fetchSingleMultipleConditions(
          ['comp_uid' => $comp_uid],
          'companies_policies'
        );
        update_cache('company_policy' . $comp_uid, $companyPolicyData);
      }

      if (empty($companyPolicyData)) {
        return $this->response([
          'status' => FALSE,
          'message' => 'Company policy not found.'
        ], 404);
      }
      $totalPaidLeaves = $companyPolicyData['monthly_leave_accrual'];
      $totalCompOff = $compOffCount;
      $leaveUsed = max(0, $companyPolicyData['monthly_leave_accrual'] - $leaveCount);

      $this->response([
        'status' => TRUE,
        'message' => 'Employee leave Data.',
        'data' => [
          'totalPaidLeaves' => $totalPaidLeaves,
          'totalCompOff' => $totalCompOff,
          'leaveUsed' => $leaveUsed
        ],
      ], 200);
    }
  }

  public function employeeApplyforLeave_post()
  {
    $tokenData = $this->validateToken();
    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);

    $comp_uid = $tokenData['comp_uid'];
    $emp_uid = $tokenData['emp_uid'];
    $employement_status = $tokenData['employement_status'];
    $leaveCount = (float) $tokenData['leave_count'];
    $compOffCount = $tokenData['compoff_count'];

    $insertionData = [];
    $updationData = ['leave_counter' => $leaveCount]; // default
    $requestedDays = (float) $data['total_days'];
    if ($employement_status == 'PROBATION' || $leaveCount == 0) {
      // Only LOP
      $insertionData[] = [
        'comp_uid'      => $comp_uid,
        'employee_uid'  => $emp_uid,
        'from_date'     => $data['from_date'],
        'too_date'      => $data['too_date'],
        'total_days'    => $requestedDays,
        'reason'        => $data['reason'],
        'leave_type'    => 'LOP',
        'work_session'  => $data['work_session'],
      ];
    } elseif ($leaveCount >= $requestedDays) {
      // Fully paid leave
      $insertionData[] = [
        'comp_uid'      => $comp_uid,
        'employee_uid'  => $emp_uid,
        'from_date'     => $data['from_date'],
        'too_date'      => $data['too_date'],
        'total_days'    => $requestedDays,
        'reason'        => $data['reason'],
        'leave_type'    => $data['leave_type'],
        'work_session'  => $data['work_session'],
      ];
      $updationData = [
        'leave_counter' => $leaveCount - $requestedDays
      ];
    } elseif ($leaveCount > 0 && $leaveCount < $requestedDays) {
      // Mixed: partly paid, partly LOP
      $paidPortion = $leaveCount;
      $lopPortion  = $requestedDays - $leaveCount;

      // PAID PART
      $insertionData[] = [
        'comp_uid'      => $comp_uid,
        'employee_uid'  => $emp_uid,
        'from_date'     => $data['from_date'],
        'too_date'      => $data['too_date'],
        'total_days'    => $paidPortion,
        'reason'        => $data['reason'],
        'leave_type'    => $data['leave_type'],
        'work_session'  => $data['work_session'],
      ];
      // UPDATE LEAVE BALANCE
      $updationData = [
        'leave_counter' => 0
      ];
      // LOP PART
      $insertionData[] = [
        'comp_uid'      => $comp_uid,
        'employee_uid'  => $emp_uid,
        'from_date'     => $data['from_date'],
        'too_date'      => $data['too_date'],
        'total_days'    => $lopPortion,
        'reason'        => $data['reason'],
        'leave_type'    => 'LOP',
        'work_session'  => $data['work_session'],
      ];
    }
    // Insert
    $isInserted = $this->db->insert_batch('employees_leaves', $insertionData);
    if ($isInserted) {
      $this->Root_model->update([
        'comp_uid'     => $comp_uid,
        'employee_uid' => $emp_uid
      ], 'employees', $updationData);
      return $this->response([
        'status'  => TRUE,
        'message' => 'Employee leave application sent successfully.',
        'data'    => [],
      ], 200);
    } else {
      return $this->response([
        'status'  => FALSE,
        'message' => 'Failed to apply for leave.',
        'data'    => [],
      ], 500);
    }
  }

  public function employeeApplyForRegularization_post()
  {
    $tokenData = $this->validateToken();
    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);

    $comp_uid = $tokenData['comp_uid'];
    $emp_uid = $tokenData['emp_uid'];

    $insertionData = [
      'comp_uid'      => $comp_uid,
      'employee_uid'  => $emp_uid,
      'date'     => $data['date'],
      'time_one'      => $data['time_one'],
      'time_two'      => $data['time_two'],
      'request_reason' => $data['request_reason'],
    ];
    $isInserted = $this->Root_model->insert('employees_regularization_request', $insertionData);
    if ($isInserted) {
      return $this->response([
        'status'  => TRUE,
        'message' => 'Employee Regularization request sent successfully.',
        'data'    => [],
      ], 200);
    } else {
      return $this->response([
        'status'  => FALSE,
        'message' => 'Failed to apply for Regularization.',
        'data'    => [],
      ], 500);
    }
  }

  public function EmployeeConfirmAssetHolding_post()
  {
    $tokenData = $this->validateToken();
    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);

    $comp_uid = $tokenData['comp_uid'];
    $emp_uid = $tokenData['emp_uid'];

    $status = $data['status'];
    $asset_id = $data['asset_id'];

    $updateConditions = ['id' => $asset_id];
    $updateData = [
      'employee_confirmation'  => $status,
    ];
    $isUpdated = $this->Root_model->update($updateConditions, 'companies_assets', $updateData);
    if ($isUpdated) {
      $this->response([
        'status' => TRUE,
        'message' => 'Employee confirmation successfully captured.',
        'data' => [],
      ], 200);
    } else {
      $this->response([
        'status' => FALSE,
        'message' => 'Employee Confirmation Failed.',
        'data' => [],
      ], 500);
    }
  }

  public function EmployeeCompleteAttendanceByMonthYear_get()
  {
    $tokenData = $this->validateToken();
    $comp_uid = $tokenData['comp_uid'];
    $emp_uid = $tokenData['emp_uid'];

    $currentYear = $this->input->get('year');
    $currentMonth = $this->input->get('month');
    $currentYearMonth = $currentYear . '-' . $currentMonth;

    $responseData = [];

    $employeeAttendanceData = $this->Company_model->fetchCurrentYearMonthAttendance($comp_uid, $emp_uid, $currentYearMonth);

    $companyPolicyData = get_cache('company_policy' . $comp_uid);
    if (!$companyPolicyData) {
      $companyPolicyData = $this->Root_model->fetchSingleMultipleConditions(
        ['comp_uid' => $comp_uid],
        'companies_policies'
      );
      update_cache('company_policy' . $comp_uid, $companyPolicyData);
    }

    foreach ($employeeAttendanceData as $attendance) {

      $date = $attendance['attendance_date'];
      $dt = new DateTime($date);
      $day = $dt->format('l'); // Weekday name
      $date     = $dt->format('j'); // Day of month
      $month   = $dt->format('F'); // Month name
      $year    = $dt->format('Y'); // Year
      $lateby = '00:00';
      $earlyby = '00:00';
      if (!empty($attendance['late_entry'])) {
        $officeIn  = new DateTime($companyPolicyData['in_time']);
        $punchIn   = new DateTime($attendance['punch_in']);
        if ($punchIn > $officeIn) {
          $interval = $officeIn->diff($punchIn);
          $lateby = $interval->format('%H:%I');
        }
      }

      if (!empty($attendance['early_exit'])) {
        $officeOut = new DateTime($companyPolicyData['out_time']);
        $punchOut  = new DateTime($attendance['punch_out']);
        if ($punchOut < $officeOut) {
          $interval = $punchOut->diff($officeOut);
          $earlyby = $interval->format('%H:%I');
        }
      }

      $responseData[] = [
        'day' => $day,
        'date' => $date,
        'month' => $month,
        'year' => $year,
        'status' => $attendance['status'],
        'checkin' => $attendance['punch_in'],
        'checkout' => $attendance['punch_out'],
        'checkinlocation' => $attendance['punchin_location'],
        'checkoutlocation' => $attendance['punchout_location'],
        'totalworkinghrs' => $attendance['working_hours'],
        'late' => (bool)$attendance['late_entry'],
        'lateby' => $lateby,
        'early' => (bool)$attendance['early_exit'],
        'earlyby' => $earlyby,
        'overtime' => $attendance['overtime']
      ];
    }
    return $this->response([
      'status' => TRUE,
      'message' => 'Employee attendance data fetched successfully.',
      'data' => $responseData,
    ], 200);
  }

  public function EmployeeLeaveAndYearlySummary_get()
  {
    $tokenData = $this->validateToken();
    $comp_uid = $tokenData['comp_uid'];
    $emp_uid  = $tokenData['emp_uid'];

    $dt = new DateTime();
    $year = $dt->format('Y');

    $companyPolicyData = get_cache('company_policy' . $comp_uid);
    if (!$companyPolicyData) {
      $companyPolicyData = $this->Root_model->fetchSingleMultipleConditions(
        ['comp_uid' => $comp_uid],
        'companies_policies'
      );
      update_cache('company_policy' . $comp_uid, $companyPolicyData);
    }

    $leaveData = [];
    $monthly = $companyPolicyData['monthly_leave_accrual'];
    $available = (int) ($tokenData['leave_count'] ?? 0);
    $approved = 0;
    $pending = 0;
    $rejected = 0;
    $lop = 0;

    $getLeaveData = $this->Company_model->fetchCurrentYearLeave(
      $comp_uid,
      $emp_uid,
      $year
    );

    if (!empty($getLeaveData)) {
      foreach ($getLeaveData as $leave) {
        $leaveData[] = [
          'from'       => $leave['from_date'],
          'to'         => $leave['too_date'],
          'daycount'   => $leave['total_days'],
          'status'     => $leave['status'],
          'type'       => $leave['leave_type'],
          'requeston'  => $leave['created_at'],
        ];

        switch (strtoupper(trim($leave['status']))) {
          case 'APPROVED':
            $approved++;
            if (strtoupper($leave['leave_type']) === 'LOP') {
              $lop++;
            }
            break;

          case 'PENDING':
            $pending++;
            break;

          case 'REJECTED':
            $rejected++;
            break;
        }
      }
    }

    $responseData = [
      'year' => $year,
      'leaveSummary' => [
        'monthly' => $monthly,
        'available' => $available,
        'approved'  => $approved,
        'pending'   => $pending,
        'rejected'  => $rejected,
        'lop'       => $lop,
      ],
      'leaveHistory' => $leaveData
    ];

    $this->response([
      'status'  => true,
      'message' => 'Employee leave and yearly summary fetched successfully.',
      'data'    => $responseData,
    ], 200);
  }

  public function updateCompanyLat_post()
  {

    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);
    if (empty($data)) {
      $this->response(['status' => FALSE, 'message' => 'Invalid Request Data'], 400);
      return;
    }

    $comp_uid = $data['comp_uid'];
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];
    $updateConditions = ['comp_uid' => $comp_uid];
    $updateData = [
      'latitude'  => $latitude,
      'longitude'  => $longitude,
    ];
    $isUpdated = $this->Root_model->update($updateConditions, 'companies_work_location', $updateData);
  }

  public function updateCompanyLat1_post()
  {

    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);
    if (empty($data)) {
      $this->response(['status' => FALSE, 'message' => 'Invalid Request Data'], 400);
      return;
    }

    $id = $data['id'];
    $comp_uid = $data['comp_uid'];
    $updateConditions = ['id' => $id];
    $updateData = [
      'comp_uid'  => $comp_uid
    ];
    $isUpdated = $this->Root_model->update($updateConditions, 'companies_work_location', $updateData);
  }
  public function fetch_CompanyLat_post()
  {
    $this->validateToken();
    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);

    $comp_uid = $data['comp_uid'];

    $employeeDetail = $this->Root_model->fetch_CompanyLat(['comp_uid' => $comp_uid], 'companies_work_location', 'id,comp_uid,location,latitude,longitude,allowed_radius');
    $this->response([
      'status' => TRUE,
      'message' => 'Company Details',
      'data' => $employeeDetail,
    ], 200);
  }

  public function updateCompanyLat2_post()
  {

    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);
    if (empty($data)) {
      $this->response(['status' => FALSE, 'message' => 'Invalid Request Data'], 400);
      return;
    }

    $id = $data['id'];
    $allowed_radius = $data['allowed_radius'];
    $updateConditions = ['id' => $id];
    $updateData = [
      'allowed_radius'  => $allowed_radius
    ];
    $isUpdated = $this->Root_model->update($updateConditions, 'companies_work_location', $updateData);
  }
  public function updateCompanyLat3_post()
  {

    $json_data = $this->input->raw_input_stream;
    $data = json_decode($json_data, TRUE);
    if (empty($data)) {
      $this->response(['status' => FALSE, 'message' => 'Invalid Request Data'], 400);
      return;
    }

    $id = $data['id'];
    $allowed_radius = $data['allowed_radius'];
    $updateConditions = ['id' => $id];
    $updateData = [
      'allowed_radius'  => $allowed_radius
    ];
    $isUpdated = $this->Root_model->update($updateConditions, 'companies_work_location', $updateData);
  }
}
