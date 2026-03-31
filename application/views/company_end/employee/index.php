<main id="main" class="main">
  <div class="pagetitle">
    <h1>Employee List</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('company-home'); ?>">Home</a></li>
        <li class="breadcrumb-item">Employee</li>
        <li class="breadcrumb-item active">Employee List</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="d-flex justify-content-end mb-2">
          <a href="<?= base_url('company-add-employee'); ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
        </div>
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Employee List</h5>
            <table class="table datatable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Emp. Details</th>
                  <th>Emp. Contact</th>
                  <th>Dept. / Desg.</th>
                  <th>Join / Seperation Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($companyEmployeeData as $key => $emp) { ?>
                  <tr>
                    <th><?= $key + 1 ?></th>
                    <td>
                      <p class="mb-0"><small><?= $emp['employee_code'] ?>
                          <?php
                          switch ($emp['employement_status']) {
                            case 'EMPLOYED':
                              echo '<span class="badge bg-success">EMPLOYED</span>';
                              break;
                            case 'RETIRED':
                              echo '<span class="badge bg-secondary">RETIRED</span>';
                              break;
                            case 'TERMINATED':
                              echo '<span class="badge bg-danger">TERMINATED</span>';
                              break;
                          }
                          ?>
                        </small></p>
                      <p class="mb-0"><small><?= $emp['employee_uid'] ?></small></p>
                      <p class="mb-0"><small><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></small></p>
                      <p class="mb-0"><small><?= $emp['employee_type'] ?></small></p>
                    </td>
                    <td>
                      <p class="mb-0"><small><?= $emp['emp_phone'] ?></small></p>
                      <p class="mb-0"><small><?= $emp['emp_email'] ?></small></p>
                    </td>
                    <td>
                      <?php
                      $dept = '';
                      foreach ($departmentData as $deptData) {
                        if ($deptData['id'] == $emp['department']) {
                          $dept = $deptData['department_name'];
                          break;
                        }
                      }
                      ?>
                      <p class="mb-0"><small><?= $dept ?></small></p>
                      <?php
                      $desg = '';
                      foreach ($designationData as $desgData) {
                        if ($desgData['id'] == $emp['designation']) {
                          $desg = $desgData['designation'];
                          break;
                        }
                      }
                      ?>
                      <p class="mb-0"><small><?= $desg ?></small></p>
                    </td>
                    <td>
                      <p class="mb-0"><small>- <?= $emp['joining_date'] ?></small></p>
                      <p class="mb-0"><small>- <?= $emp['separation_date'] ?></small></p>
                    </td>
                    <td>
                      <div class="d-flex flex-column gap-1 align-items-center">
                        <a href="<?= base_url('company-employee-details/' . $emp['id']) ?>" class="btn btn-warning btn-sm border"><i class="bi bi-gear-fill"></i></a>
                      </div>
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