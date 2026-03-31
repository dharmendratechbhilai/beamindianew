<main id="main" class="main">
  <div class="pagetitle">
    <h1>Attendance Details</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin-home') ?>">Home</a></li>
        <li class="breadcrumb-item">Attendance</li>
        <li class="breadcrumb-item active">Attendance Details</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Employee Data</h5>
            <div class="mb-4">
              <select class="form-select employeeSelector" aria-label="Default select example">
                <option value="">Select Employee</option>
                <?php foreach ($employeesData as $employee) { ?>
                  <option value="<?= $employee['employee_uid'] ?>"><?= $employee['first_name'] . ' ' . $employee['last_name'] ?></option>
                <?php } ?>
              </select>
            </div>
            <div id="employeeData"></div>
          </div>
        </div>
      </div>
      <div class="col-lg-8">
        <?php
        $months = [];
        for ($i = 0; $i < 12; $i++) {
          $months[] = [
            'name'  => date('F Y', strtotime("-$i month")),
            'value' => date('Y-m', strtotime("-$i month"))
          ];
        }
        ?>
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Attendance Details</h5>
            <div class="mb-4 w-50">
              <select class="form-select monthSelector" aria-label="Default select example">
                <?php foreach ($months as $month) { ?>
                  <option value="<?= $month['value'] ?>"><?= $month['name'] ?></option>
                <?php } ?>
              </select>
            </div>
            <button type="button" class="btn btn-sm btn-light border mb-2 me-1">
              Total Days <span class="badge bg-light border text-dark ms-2" id="totalDays">0</span>
            </button>
            <button type="button" class="btn btn-sm btn-light border mb-2 me-1">
              Present <span class="badge bg-success text-light ms-2" id="presentDays">0</span>
            </button>
            <button type="button" class="btn btn-sm btn-light border mb-2 me-1">
              Absent <span class="badge bg-danger text-light ms-2" id="absentDays">0</span>
            </button>
            <button type="button" class="btn btn-sm btn-light border mb-2 me-1">
              Leave <span class="badge bg-light border text-dark ms-2" id="">0</span>
            </button>
            <button type="button" class="btn btn-sm btn-light border mb-2 me-1">
              Regularize <span class="badge bg-warning text-dark ms-2" id="">0</span>
            </button>
            <div id="attendanceData"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<!-- <script>
  var currentMonthYear = "<?= date('Y-m') ?>";
  var employeeUid = '';

  $(document).ready(function() {

    $('.employeeSelector').on('change', function() {
      employeeUid = $(this).val();
      loadEmployeeDetails(employeeUid);
    });

    $('.monthSelector').on('change', function() {
      currentMonthYear = $(this).val();
      loadEmployeeDetails(employeeUid);
    });

  });

  function loadEmployeeDetails(employeeUid) {
    $.ajax({
      url: '<?= base_url("load-employee-details") ?>',
      type: 'POST',
      dataType: 'json',
      data: {
        employeeUid: employeeUid,
        currentMonthYear: currentMonthYear
      },
      beforeSend: function() {

      },
      success: function(response) {
        if (response.status) {
          $('#employeeData').html(response.employeeData);
          $('#attendanceData').html(response.attendanceData);
        } else {
          $('#employeeData').html('No data found.');
          $('#attendanceData').html('No data found.');
        }
      },
      error: function() {
        $('#employeeData').html('<div class="text-center p-3 text-danger">Failed to load data.</div>');
        $('#attendanceData').html('<div class="text-center p-3 text-danger">Failed to load data.</div>');
      }
    });

  }
</script> -->

<script>
  var currentMonthYear = "<?= date('Y-m') ?>";
  var employeeUid = '';

  $(document).ready(function() {

    $('.employeeSelector').on('change', function() {
      employeeUid = $(this).val();
      if (employeeUid) loadEmployeeDetails(employeeUid);
    });

    $('.monthSelector').on('change', function() {
      currentMonthYear = $(this).val();
      if (employeeUid) loadEmployeeDetails(employeeUid);
    });
  });

  function loadEmployeeDetails(employeeUid) {
    if (!employeeUid) return;

    $.ajax({
      url: '<?= base_url("load-employee-details") ?>',
      type: 'POST',
      dataType: 'json',
      data: {
        employeeUid: employeeUid,
        currentYearMonth: currentMonthYear
      },
      beforeSend: function() {
        $('#employeeData').html('Loading...');
        $('#attendanceData').html('Loading...');
      },
      success: function(response) {
        if (response.status) {
          $('#employeeData').html(response.employeeData);
          $('#attendanceData').html(response.attendanceData);
          $('#totalDays').html(response.total_days);
          $('#presentDays').html(response.present_days);
          $('#absentDays').html(response.absent_days);
          $('#leaveDays').html(response.leave_days);
          $('#regularizeDays').html(response.regularize_days);
        } else {
          $('#employeeData').html('No data found.');
          $('#attendanceData').html('No data found.');
        }
      },
      error: function(xhr) {
        $('#employeeData').html('<div class="text-center p-3 text-danger">Failed to load data.</div>');
        $('#attendanceData').html('<div class="text-center p-3 text-danger">Failed to load data.</div>');
        console.log(xhr.responseText);
      }
    });
  }
</script>