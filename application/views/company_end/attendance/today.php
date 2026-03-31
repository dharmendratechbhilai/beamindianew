<main id="main" class="main">
  <div class="pagetitle">
    <h1>Today Attendance List</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin-home'); ?>">Home</a></li>
        <li class="breadcrumb-item">Attendance</li>
        <li class="breadcrumb-item active">Today's Attendance List</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Today's Attendance List (<?= $date ?>)</h5>
            <table class="table table-striped datatable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Status</th>
                  <th>Name</th>
                  <th>Dept./Desg.</th>
                  <th>Work Location</th>
                  <th>Punch In</th>
                  <th>Punch Out</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($attendanceData as $key => $value) { ?>
                  <tr>
                    <th><?= $key + 1 ?></th>
                    <td>
                      <?php
                      if ($value['status'] === 'PENDING') { ?>
                        <?php if ($value['in_time_status'] === 'PENDING') { ?>
                          <span class="badge bg-warning text-dark">IN <?= $value['in_time_status'] ?></span>
                        <?php } else { ?>
                          <span class="badge <?= $value['in_time_status'] === 'ON TIME' ? 'bg-success' : 'bg-danger' ?>"><?= $value['in_time_status'] ?> IN <?= $value['late_by'] ?></span>
                        <?php } ?>
                        <br>
                        <?php if ($value['out_time_status'] === 'PENDING') { ?>
                          <span class="badge bg-warning text-dark">OUT <?= $value['out_time_status'] ?></span>
                        <?php } else { ?>
                          <span class="badge <?= $value['out_time_status'] === 'ON TIME' ? 'bg-success' : 'bg-danger' ?>">OUT <?= $value['out_time_status'] ?> <?= $value['early_by'] ?></span>
                        <?php } ?>
                      <?php } else { ?>
                        <span class="badge <?= $value['status'] === 'APPROVE' ? 'bg-success' : 'bg-danger' ?>"><?= $value['status'] ?></span>
                      <?php } ?>
                    </td>
                    <td>
                      <p class="mb-0"><small><?= $value['employee_code'] ?></small></p>
                      <p class="mb-0"><small><?= $value['first_name'] . ' ' . $value['middle_name'] . ' ' . $value['last_name'] ?></small></p>
                    </td>
                    <td>
                      <p class="mb-0"><small><?= $value['department_name'] ?></small></p>
                      <p class="mb-0"><small><?= $value['designation_name'] ?></small></p>
                    </td>
                    <td>
                      <p class="mb-0"><small>In : <?= $value['punchin_location'] ?? '...' ?></small></p>
                      <p class="mb-0"><small>Out : <?= $value['punchout_location'] ?? '...' ?></small></p>
                    </td>
                    <td>
                      <p class="mb-0"><small><?= $value['punch_in'] ?></small></p>
                    </td>
                    <td>
                      <p class="mb-0"><small><?= $value['punch_out'] ?></small></p>
                    </td>
                    <td>
                      <a href="<?= base_url('today-attendance-details/' . $value['employee_uid']) ?>" class="btn btn-sm btn-primary">View Details</a>
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