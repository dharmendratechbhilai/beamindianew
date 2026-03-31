<main id="main" class="main">
  <div class="pagetitle">
    <h1>Data Tables</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin-home'); ?>">Home</a></li>
        <li class="breadcrumb-item">Tables</li>
        <li class="breadcrumb-item active">Data</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="d-flex justify-content-end mb-2">
          <a href="#!" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New</a>
        </div>
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Datatables</h5>
            <!-- table-bordered -->
            <!-- datatable -->
            <!-- table-striped -->
            <table class="table datatable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Position</th>
                  <th>Age</th>
                  <th>Start Date</th>
                  <th>Start Date</th>
                  <th>Start Date</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th>1</th>
                  <td>Brandon Jacob</td>
                  <td>Designer</td>
                  <td>28</td>
                  <td>2016-05-25</td>
                  <td>2016-05-25</td>
                  <td>2016-05-25</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>