<main id="main" class="main">
  <div class="pagetitle">
    <h1>Add Employee</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('company-home') ?>">Home</a></li>
        <li class="breadcrumb-item">Employee</li>
        <li class="breadcrumb-item active">Add Employee</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Add Employee</h5>
            <form action="<?= base_url('do-company-add-employee') ?>" method="post" class="row g-3">
              <input type="hidden" name="emp_uid" value="<?= strtoupper(uniqid()); ?>">
              <input type="hidden" name="emp_code" id="empcode" value="">
              <input type="hidden" name="joining_date" value="<?= date('Y-m-d') ?>">
              <div class="col-md-3">
                <label for="input1" class="form-label">Department</label>
                <select id="input1" class="form-select" name="department_uid">
                  <option selected="">Choose...</option>
                  <?php foreach ($departmentData as $dept) { ?>
                    <option value="<?= $dept['id'] ?>"><?= $dept['department_name'] ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="col-md-3">
                <label for="input2" class="form-label">Designation</label>
                <select id="input2" class="form-select" name="designation_uid">
                  <option selected="">Choose...</option>
                  <?php foreach ($designationData as $designation) { ?>
                    <option value="<?= $designation['id'] ?>"><?= $designation['designation'] ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="col-md-3">
                <label for="input4" class="form-label">Reporting Manager</label>
                <select id="input4" class="form-select" name="reporting_manager_uid">
                  <option selected="" value="NA">Choose...</option>
                  <?php foreach ($companyEmployeeData as $employee) { ?>
                    <option value="<?= $employee['employee_uid'] ?>"><?= $employee['first_name'] . ' ' . $employee['last_name'] ?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="col-md-3">
                <label for="input5" class="form-label">Employee Type</label>
                <select id="input5" class="form-select" name="employee_type">
                  <option selected="">Choose...</option>
                  <?php foreach (EMP_TYPE as $key => $value) { ?>
                    <option value="<?= $key ?>"><?= $value ?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="col-md-3">
                <label for="input6" class="form-label">First Name</label>
                <input type="text" class="form-control" id="input6" name="first_name">
              </div>
              <div class="col-md-3">
                <label for="input7" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="input7" name="middle_name">
              </div>
              <div class="col-md-3">
                <label for="input8" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="input8" name="last_name">
              </div>
              <div class="col-md-3">
                <label for="input9" class="form-label">Email</label>
                <input type="text" class="form-control" id="input9" name="email">
              </div>
              <div class="col-md-3">
                <label for="input10" class="form-label">Mobile</label>
                <input type="text" class="form-control" id="input10" name="mobile">
              </div>
              <div class="col-md-3">
                <label for="input11" class="form-label">Gender</label>
                <select id="input11" class="form-select" name="gender">
                  <option selected="">Choose...</option>
                  <option>Male</option>
                  <option>Female</option>
                  <option>Other</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="input12" class="form-label">Date of Bith</label>
                <input type="date" class="form-control" id="input12" name="dob">
              </div>
              <div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<script>
  $(document).ready(function() {
    $.ajax({
      type: "POST",
      url: "<?= base_url('get-emp-code') ?>",
      data: {
        comp_uid: "<?= $this->session->userdata('comp_uid') ?>"
      },
      dataType: 'json',
      success: function(response) {
        console.log(response);
        $('#empcode').val(response.emp_code);
      }
    });
  });
</script>