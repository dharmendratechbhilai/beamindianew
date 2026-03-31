<main id="main" class="main">
  <div class="pagetitle">
    <h1>Edit Assignment</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('company-home') ?>">Home</a></li>
        <li class="breadcrumb-item">Asset Management</li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Edit</h5>
            <form action="<?= base_url('do-company-edit-assign-asset/') . $assetData['id'] ?>" method="post" class="row g-3">
              <div class="col-3">
                <label for="input1" class="form-label">Employee List</label>
                <select class="form-select" aria-label="Employee Selector" id="input1" name="emp_uid">
                  <?php foreach ($employeesData as $employee) { ?>
                    <option <?= ($assetData['employee_uid'] == $employee['employee_uid']) ? "selected" : "" ?> value="<?= $employee['employee_uid'] ?>"><?= $employee['first_name'] . ' ' . $employee['last_name'] ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="col-3">
                <label for="input2" class="form-label">Issued Date</label>
                <input type="date" class="form-control" id="input2" name="issuedDate" value="<?= $assetData['issue_date']; ?>">
              </div>
              <div class="col-3">
                <label for="input2" class="form-label">Return Date</label>
                <input type="date" class="form-control" id="input2" name="returnDate" value="<?= $assetData['return_date']; ?>">
              </div>
              <div class="col-12">
                <label for="input3" class="form-label">Asset Details</label>
                <textarea class="form-control" id="input3" rows="3" name="assetDetails"><?= $assetData['asset_details']; ?></textarea>
              </div>
              <div>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="is_active" value="1" <?= $assetData['is_active'] ? "checked" : "" ?>>
                  <label class=" form-check-label" for="flexSwitchCheckChecked">Is Assignment Active?</label>
                </div>
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