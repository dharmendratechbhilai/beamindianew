<main id="main" class="main">
  <div class="pagetitle">
    <h1>Employee Approved Leaves</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('company-home'); ?>">Home</a></li>
        <li class="breadcrumb-item">Leaves</li>
        <li class="breadcrumb-item active">Approved</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">List</h5>
            <table class="table datatable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Emp. Details</th>
                  <th>Emp. Contact</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Summary</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($employeesLeaveData as $key => $leave) { ?>
                  <tr>
                    <th><?= $key + 1; ?></th>
                    <td>
                      <p class="mb-0 small"><?= $leave['employee_Data']['first_name'] . $leave['employee_Data']['middle_name'] . $leave['employee_Data']['last_name'] ?></p>
                    </td>
                    <td>
                      <p class="mb-0 small"><?= $leave['employee_Data']['emp_phone'] ?></p>
                      <p class="mb-0 small"><?= $leave['employee_Data']['emp_email'] ?></p>
                    </td>
                    <td><?= $leave['from_date'] ?></td>
                    <td><?= $leave['too_date'] ?></td>
                    <td>
                      <p class="mb-0 small"><?= $leave['leave_type'] ?></p>
                      <?php if ($leave['leave_type'] == 'HALFDAY') { ?>
                        <p class="mb-0 small"><?= $leave['work_session'] ?></p>
                      <?php } ?>
                      <p class="mb-0 small"><?= $leave['total_days'] ?> Day(s)</p>
                      <p class="mb-0 small">Reason - <?= $leave['reason'] ?></p>
                    </td>
                    <td>
                      <!-- <a href="#!" class="btn btn-sm btn-success"><i class="bi bi-bookmark-check"></i></a>
                      <a href="#!" class="btn btn-sm btn-success"><i class="bi bi-x-circle"></i></a> -->
                      - - -
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>