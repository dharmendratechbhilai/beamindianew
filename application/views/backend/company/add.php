<main id="main" class="main">
  <div class="pagetitle">
    <h1>Add Company</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin-home') ?>">Home</a></li>
        <li class="breadcrumb-item">Company</li>
        <li class="breadcrumb-item active">Add</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Add Company</h5>
            <form action="<?= base_url('do-add-company'); ?>" method="post">
              <div class="row g-3">
                <div class="col-md-8">
                  <div class="form-group">
                    <label for="company_name">Company Name</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="ceo_name">CEO Name</label>
                    <input type="text" class="form-control" id="ceo_name" name="ceo_name" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="email">Contact Email</label>
                    <input type="text" class="form-control" id="email" name="email" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="username">Company Login username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="password">Company Login password</label>
                    <input type="text" class="form-control" id="password" name="password" value="QWERTY" readonly>
                  </div>
                </div>
              </div>
              <div class="mt-3">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" class="btn btn-danger">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>