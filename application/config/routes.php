<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'Auth';
$route['404_override'] = 'Base/errorPage';
$route['translate_uri_dashes'] = FALSE;

/*
|--------------------------------------------------------------------------
| Company panel URL's
|--------------------------------------------------------------------------
*/

$route['company-login'] = 'Auth';
$route['do-company-login'] = 'Auth/doCompanyLogin';
$route['company-logout'] = 'Auth/companyLogout';

$route['company-home'] = 'Company/index';

$route['company-department-list'] = 'Company/companyDepartmentList';
$route['load-departments'] = 'Company/load_departments';
$route['add-department'] = 'Company/add_department';
$route['edit-department'] = 'Company/edit_department';
$route['delete-department'] = 'Company/delete_department';

$route['company-designation-list'] = 'Company/companyDesignationList';
$route['load-designations'] = 'Company/load_designations';
$route['add-designation'] = 'Company/add_designation';
$route['edit-designation'] = 'Company/edit_designation';
$route['delete-designation'] = 'Company/delete_designation';

$route['company-work-location-list'] = 'Company/companyWorkLocationList';
$route['load-work-locations'] = 'Company/load_work_locations';
$route['add-work-location'] = 'Company/add_work_location';
$route['edit-work-location'] = 'Company/edit_work_location';
$route['delete-work-location'] = 'Company/delete_work_location';

$route['company-policy-list'] = 'Company/companyPolicyList';
$route['update-company-policy'] = 'Company/updateCompanyPolicy';

$route['company-employee-list'] = 'Company/companyEmployeeList';
$route['company-add-employee'] = 'Company/companyAddEmployee';
$route['get-emp-code'] = 'Company/getEmpCode';
$route['do-company-add-employee'] = 'Company/doCompanyAddEmployee';
$route['company-employee-details/(:any)'] = 'Company/companyEmployeeDetails/$1';

$route['update-employee-basic-details/(:any)'] = 'Company/updateEmployeeBasicDetails/$1';
$route['update-employee-current-address-details/(:any)'] = 'Company/updateEmployeeCurrentAddressDetails/$1';
$route['update-employee-bank-details/(:any)'] = 'Company/updateEmployeeBankDetails/$1';
$route['update-employee-salary-details/(:any)'] = 'Company/updateEmployeeSalaryDetails/$1';

$route['update-employee-address-details/(:any)'] = 'Company/updateEmployeeAddressDetails/$1';
$route['employee-today-attendance-list/(:any)'] = 'Company/employeeTodayAttendanceList/$1';
$route['today-attendance-details/(:any)'] = 'Company/todayAttendanceDetails/$1';

$route['company-approve-attendance'] = 'Company/companyApproveAttendance';
$route['company-reject-attendance'] = 'Company/companyRejectAttendance';
$route['employee-attendance-details'] = 'Company/employeeAttendanceDetails';

$route['load-employee-details'] = 'Company/loadEmployeeDetails';

$route['company-leave-list'] = 'Company/companyLeaveList';
$route['company-approved-leave-list'] = 'Company/companyApprovedLeaveList';
$route['company-rejected-leave-list'] = 'Company/companyRejectedLeaveList';

$route['company-accept-leave/(:any)'] = 'Company/companyAcceptLeave/$1';
$route['company-reject-leave/(:any)'] = 'Company/companyRejectLeave/$1';

$route['company-assets-list'] = 'Company/companyAssetsList';
$route['company-add-asset'] = 'Company/companyAddAsset';
$route['do-company-assign-asset'] = 'Company/doCompanyAssignAsset';
$route['edit-asset-assignment/(:any)'] = 'Company/editAssetAssignment/$1';
$route['do-company-edit-assign-asset/(:any)'] = 'Company/doCompanyEditAssignAsset/$1';

$route['company-holiday-list'] = 'Company/companyHolidayList';
$route['do-company-add-holiday'] = 'Company/doCompanyAddHoliday';
$route['edit-holiday'] = 'Company/editHoliday';
$route['delete-holiday/(:any)'] = 'Company/deleteHoliday/$1';




























/*
|--------------------------------------------------------------------------
| Admin panel URL's
|--------------------------------------------------------------------------
*/
$route['blank'] = 'Admin/blank';
$route['data-table'] = 'Admin/dataTable';
// ================================================

$route['admin-login'] = 'Auth/adminLogin';
$route['do-admin-login'] = 'Auth/doAdminLogin';
$route['admin-logout'] = 'Admin/adminLogout';

//Homepage
$route['admin-home'] = 'Admin/adminHome';
$route['admin-faq'] = 'Admin/adminFaq';

$route['company-list'] = 'Admin/companyList';
$route['add-company'] = 'Admin/addCompany';
$route['do-add-company'] = 'Admin/doAddCompany';

$route['edit-company/(:any)'] = 'Admin/editCompany/$1';
$route['do-edit-company/(:any)'] = 'Admin/doEditCompany/$1';
$route['delete-company/(:any)'] = 'Admin/deleteCompany/$1';
