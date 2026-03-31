<main id="main" class="main">
  <div class="pagetitle">
    <h1>Company List</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin-home'); ?>">Home</a></li>
        <li class="breadcrumb-item">Company</li>
        <li class="breadcrumb-item active">List</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="d-flex justify-content-end mb-2">
          <a href="<?= base_url('add-company'); ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
        </div>
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Datatables</h5>
            <table class="table datatable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Company</th>
                  <th>CEO</th>
                  <th>Contact</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($companies as $key => $value) { ?>
                  <tr>
                    <th><?= $key + 1 ?></th>
                    <td>
                      <p class="mb-0"><?= $value['comp_uid'] ?></p>
                      <p class="mb-0"><?= $value['comp_name'] ?></p>
                    </td>
                    <td><?= $value['comp_head_name'] ?></td>
                    <td>
                      <p class="mb-0"><?= $value['comp_head_phone'] ?></p>
                      <p class="mb-0"><?= $value['comp_head_email'] ?></p>
                    </td>
                    <td>
                      <?php if ($value['is_active'] == 1) { ?>
                        <span class="badge bg-success">Active</span>
                      <?php } else { ?>
                        <span class="badge bg-danger">Inactive</span>
                      <?php } ?>
                    </td>
                    <td>
                      <a href="<?= base_url('edit-company/' . $value['comp_uid']) ?>" class="btn btn-sm btn-primary"><i class="bi bi-gear-fill"></i></a>
                      <a href="<?= base_url('delete-company/' . $value['comp_uid']) ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
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