<main id="main" class="main">
  <div class="pagetitle">
    <h1>Company Assets</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('company-home'); ?>">Home</a></li>
        <li class="breadcrumb-item">Assets Management</li>
        <li class="breadcrumb-item active">Data</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="d-flex justify-content-end mb-2">
          <a href="<?= base_url('company-add-asset') ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Load Asset</a>
        </div>
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">List</h5>
            <table class="table datatable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Emp. Details</th>
                  <th>Emp. Contact</th>
                  <th>Asset Details</th>
                  <th>Issue Date</th>
                  <th>Return Date</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($assetsData as $key => $asset) { ?>
                  <tr>
                    <th><?= $key + 1; ?></th>
                    <td>
                      <p class="mb-0 small"><?= $asset['employee_Data']['first_name'] . ' ' . $asset['employee_Data']['middle_name'] . ' ' . $asset['employee_Data']['last_name'] ?></p>
                    </td>
                    <td>
                      <p class="mb-0 small"><?= $asset['employee_Data']['emp_phone'] ?></p>
                      <p class="mb-0 small"><?= $asset['employee_Data']['emp_email'] ?></p>
                    </td>
                    <td><?= $asset['asset_details'] ?></td>
                    <td><?= $asset['issue_date'] ?></td>
                    <td><?= $asset['return_date'] ?></td>
                    <td>
                      <?php if ($asset['is_active'] == true) { ?>
                        <span class="badge bg-success">Borrowed</span>
                      <?php } else { ?>
                        <span class="badge bg-secondary">Returned</span>
                      <?php } ?>

                      <?php if ($asset['employee_confirmation'] == true) { ?>
                        <span class="badge bg-success">CONFIRMED</span>
                      <?php } else { ?>
                        <span class="badge bg-danger">NOT CONFIRMED</span>
                      <?php } ?>
                    </td>
                    <td>
                      <a href="<?= base_url('edit-asset-assignment/') . $asset['id'] ?>" class="btn btn-sm btn-success"><i class="bi bi-gear-fill"></i></a>
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