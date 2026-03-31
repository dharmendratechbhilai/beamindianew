<main id="main" class="main">
  <div class="pagetitle">
    <h1>Employee Profile</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('company-home') ?>">Home</a></li>
        <li class="breadcrumb-item">Employee</li>
        <li class="breadcrumb-item active">Profile</li>
      </ol>
    </nav>
  </div>

  <section class="section profile">
    <div class="row">
      <div class="col-xl-4">
        <div class="card">
          <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
            <img src="<?= base_url('assets/user.jpg') ?>" alt="Profile" class="rounded-circle">
            <h2><?= $employeeData['first_name'] . ' ' . $employeeData['last_name'] ?></h2>
            <h3><?= $employeeData['department_name'] ?></h3>
          </div>
        </div>
      </div>

      <div class="col-xl-8">
        <div class="card">
          <div class="card-body pt-3">
            <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#basic-details" aria-selected="true" role="tab">
                  Basic Details
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#address" aria-selected="false" tabindex="-1" role="tab">
                  Address Details
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bank-documents" aria-selected="false" tabindex="-1" role="tab">
                  Bank Account Details
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#salary" aria-selected="false" tabindex="-1" role="tab">
                  salary Details
                </button>
              </li>
            </ul>
            <div class="tab-content pt-2">
              <div class="tab-pane fade show active pt-3 profile-edit" id="basic-details" role="tabpanel">
                <form action="<?= base_url('update-employee-basic-details/' . $employeeData['id']) ?>" method="post">
                  <div class="row mb-3">
                    <label for="company_uid" class="col-md-4 col-lg-3 col-form-label">Company uid</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="company_uid" type="text" class="form-control" id="company_uid" value="<?= $employeeData['comp_uid'] ?>" readonly>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="employee_uid" class="col-md-4 col-lg-3 col-form-label">Employee uid</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="employee_uid" type="text" class="form-control" id="employee_uid" value="<?= $employeeData['employee_uid'] ?>" readonly>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="emp_code" class="col-md-4 col-lg-3 col-form-label">Employee code</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="emp_code" type="text" class="form-control" id="emp_code" value="<?= $employeeData['employee_code'] ?>" readonly>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="first_name" type="text" class="form-control mb-1" id="fullName" value="<?= $employeeData['first_name'] ?>">
                      <input name="middle_name" type="text" class="form-control mb-1" id="fullName" value="<?= $employeeData['middle_name'] ?>">
                      <input name="last_name" type="text" class="form-control mb-1" id="fullName" value="<?= $employeeData['last_name'] ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="emp_code" class="col-md-4 col-lg-3 col-form-label">Reporting Manager</label>
                    <div class="col-md-8 col-lg-9">
                      <select id="input4" class="form-select" name="reporting_manager_uid">
                        <?php foreach ($companyEmployeeData as $employee) { ?>
                          <option <?= $employee['employee_uid'] == $employeeData['reporting_manager_uid'] ? 'selected' : '' ?> value="<?= $employee['id'] ?>"><?= $employee['first_name'] . ' ' . $employee['last_name'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="emp_code" class="col-md-4 col-lg-3 col-form-label">Department</label>
                    <div class="col-md-8 col-lg-9">
                      <select id="input4" class="form-select" name="department_uid">
                        <?php foreach ($departmentData as $department) { ?>
                          <option <?= $department['id'] == $employeeData['department'] ? 'selected' : '' ?> value="<?= $department['id'] ?>"><?= $department['department_name'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="emp_code" class="col-md-4 col-lg-3 col-form-label">Designation</label>
                    <div class="col-md-8 col-lg-9">
                      <select id="input4" class="form-select" name="designation_uid">
                        <?php foreach ($designationData as $designation) { ?>
                          <option <?= $designation['id'] == $employeeData['designation'] ? 'selected' : '' ?> value="<?= $designation['id'] ?>"><?= $designation['designation'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="input3" class="col-md-4 col-lg-3 col-form-label">Employee Type</label>
                    <div class="col-md-8 col-lg-9">
                      <select id="input3" class="form-select" name="employee_type">
                        <?php foreach (EMP_TYPE as $key => $value) { ?>
                          <option <?= $key == $employeeData['employee_type'] ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="phone" type="text" class="form-control" id="Phone" value="<?= $employeeData['emp_phone'] ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="email" type="text" class="form-control" id="email" value="<?= $employeeData['emp_email'] ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="input11" class="col-md-4 col-lg-3 col-form-label">Gender</label>
                    <div class="col-md-8 col-lg-9">
                      <select id="input11" class="form-select" name="gender" value="<?= $employeeData['gender'] ?>">
                        <option <?= $employeeData['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option <?= $employeeData['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                        <option <?= $employeeData['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="input12" class="col-md-4 col-lg-3 col-form-label">Date of Bith</label>
                    <div class="col-md-8 col-lg-9">
                      <input type="date" class="form-control" id="input12" name="dob" value="<?= $employeeData['date_of_birth'] ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="input12" class="col-md-4 col-lg-3 col-form-label">Employment Status</label>
                    <div class="col-md-8 col-lg-9">
                      <select id="input12" class="form-select" name="employement_status">
                        <option <?= $employeeData['employement_status'] == 'EMPLOYED' ? 'selected' : '' ?>>Employed</option>
                        <option <?= $employeeData['employement_status'] == 'RETIRED' ? 'selected' : '' ?>>Retired</option>
                        <option <?= $employeeData['employement_status'] == 'TERMINATED' ? 'selected' : '' ?>>Terminated</option>
                      </select>
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                      Update Changes
                    </button>
                  </div>
                </form>
              </div>

              <div class="tab-pane fade profile-edit pt-3" id="address" role="tabpanel">
                <form action="<?= base_url('update-employee-address-details/' . $employeeData['id']) ?>" method="post">
                  <input type="hidden" name="comp_uid" value="<?= $employeeData['comp_uid'] ?>">
                  <input type="hidden" name="emp_uid" value="<?= $employeeData['employee_uid'] ?>">
                  <input type="hidden" name="address_type" value="CURRENT">
                  <div class="row mb-3">
                    <label for="street_address" class="col-md-4 col-lg-3 col-form-label">Street Address</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="street_address" type="text" class="form-control" id="street_address" value="<?= $employeeAddressData['street_address'] ?? '' ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="city" class="col-md-4 col-lg-3 col-form-label">City</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="city" type="text" class="form-control" id="city" value="<?= $employeeAddressData['city'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="district" class="col-md-4 col-lg-3 col-form-label">District</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="district" type="text" class="form-control" id="district" value="<?= $employeeAddressData['district'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="state" class="col-md-4 col-lg-3 col-form-label">State</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="state" type="text" class="form-control" id="state" value="<?= $employeeAddressData['state'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="zip_code" class="col-md-4 col-lg-3 col-form-label">Zip Code</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="pincode" type="text" class="form-control" id="zip_code" value="<?= $employeeAddressData['pincode'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                      Update Changes
                    </button>
                  </div>
                </form>
              </div>

              <div class="tab-pane fade pt-3 profile-edit" id="bank-documents" role="tabpanel">
                <form action="<?= base_url('update-employee-bank-details/' . $employeeData['id']) ?>" method="post">
                  <input type="hidden" name="comp_uid" value="<?= $employeeData['comp_uid'] ?>">
                  <input type="hidden" name="emp_uid" value="<?= $employeeData['employee_uid'] ?>">
                  <div class="row mb-3">
                    <label for="bank_name" class="col-md-4 col-lg-3 col-form-label">Bank Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="bank_name" type="text" class="form-control" id="bank_name" value="<?= $employeeAccountData['bank_name'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="ifsc_code" class="col-md-4 col-lg-3 col-form-label">IFSC Code</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="ifsc_code" type="text" class="form-control" id="ifsc_code" value="<?= $employeeAccountData['ifsc_code'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="account_holder_name" class="col-md-4 col-lg-3 col-form-label">Acc. Holder Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="account_holder_name" type="text" class="form-control" id="account_holder_name" value="<?= $employeeAccountData['account_holder_name'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="account_number" class="col-md-4 col-lg-3 col-form-label">Acc. Number</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="account_number" type="text" class="form-control" id="account_number" value="<?= $employeeAccountData['account_num'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="upi_id" class="col-md-4 col-lg-3 col-form-label">UPI ID</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="upi_id" type="text" class="form-control" id="upi_id" value="<?= $employeeAccountData['upi_id'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                      Save Changes
                    </button>
                  </div>
                </form>
              </div>

              <div class="tab-pane fade pt-3 profile-edit" id="salary" role="tabpanel">
                <form action="<?= base_url('update-employee-salary-details/' . $employeeData['id']) ?>" method="post">
                  <input type="hidden" name="comp_uid" value="<?= $employeeData['comp_uid'] ?>">
                  <input type="hidden" name="emp_uid" value="<?= $employeeData['employee_uid'] ?>">
                  <div class="row mb-3">
                    <label for="basic_salary" class="col-md-4 col-lg-3 col-form-label">Basic Salary</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="basic_salary" type="text" class="form-control" id="basic_salary" value="<?= $employeeSalaryData['basic_salary'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="house_rent" class="col-md-4 col-lg-3 col-form-label">House Rent Allowance</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="house_rent" type="text" class="form-control" id="house_rent" value="<?= $employeeSalaryData['hra'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="dearness_allowance" class="col-md-4 col-lg-3 col-form-label">Dearness Allowance</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="dearness_allowance" type="text" class="form-control" id="dearness_allowance" value="<?= $employeeSalaryData['da'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="travel_allowance" class="col-md-4 col-lg-3 col-form-label">Travel Allowance</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="travel_allowance" type="text" class="form-control" id="travel_allowance" value="<?= $employeeSalaryData['travel_allowance'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="medical_allowance" class="col-md-4 col-lg-3 col-form-label">Medical Allowance</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="medical_allowance" type="text" class="form-control" id="medical_allowance" value="<?= $employeeSalaryData['medical_allowance'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="other_allowance" class="col-md-4 col-lg-3 col-form-label">Other Allowance</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="other_allowance" type="text" class="form-control" id="other_allowance" value="<?= $employeeSalaryData['other_allowance'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="employee_provident_fund" class="col-md-4 col-lg-3 col-form-label">Employee Provident Fund</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="employee_provident_fund" type="text" class="form-control" id="employee_provident_fund" value="<?= $employeeSalaryData['epf'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="employee_state_insurance" class="col-md-4 col-lg-3 col-form-label">Employee State Insurance</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="employee_state_insurance" type="text" class="form-control" id="employee_state_insurance" value="<?= $employeeSalaryData['esi'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="professional_tax" class="col-md-4 col-lg-3 col-form-label">Professional Tax</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="professional_tax" type="text" class="form-control" id="professional_tax" value="<?= $employeeSalaryData['pt'] ?? ''; ?>">
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                      Update Changes
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>