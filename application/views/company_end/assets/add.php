<main id="main" class="main">
  <div class="pagetitle">
    <h1>Assign Asset to Employee</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('company-home') ?>">Home</a></li>
        <li class="breadcrumb-item">Asset Management</li>
        <li class="breadcrumb-item active">Add</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Add</h5>
            <form action="<?= base_url('do-company-assign-asset') ?>" method="post" class="row g-3">
              <div class="col-3">
                <label for="input1" class="form-label">Employee List</label>
                <select class="form-select" aria-label="Employee Selector" id="input1" name="emp_uid">
                  <option value="">Select Employee</option>
                  <?php foreach ($employeesData as $employee) { ?>
                    <option value="<?= $employee['employee_uid'] ?>"><?= $employee['first_name'] . ' ' . $employee['last_name'] ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="col-3">
                <label for="input2" class="form-label">Issued Date</label>
                <input type="date" class="form-control" id="input2" name="issuedDate">
              </div>
              <!-- <div class="col-3">
                <label for="input2" class="form-label">Return Date</label>
                <input type="date" class="form-control" id="input2">
              </div> -->
              <div class="col-12">
                <label for="input3" class="form-label">Asset Details</label>
                <textarea class="form-control" id="input3" rows="3" name="assetDetails"></textarea>
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