<main id="main" class="main">
  <div class="pagetitle">
    <h1>Holiday List</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('company-home'); ?>">Home</a></li>
        <li class="breadcrumb-item">Holiday</li>
        <li class="breadcrumb-item active">List</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="d-flex justify-content-end mb-2">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHoliday">
            <i class="bi bi-plus-circle"></i> Add Holiday
          </button>
        </div>
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Datatables</h5>
            <table class="table datatable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Date</th>
                  <th>Holiday Name</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($holidayData as $key => $holiday) { ?>
                  <tr>
                    <th><?= $key + 1; ?></th>
                    <td><?= $holiday['date']; ?></td>
                    <td><?= $holiday['holiday'] ?></td>
                    <td>
                      <?php if ($holiday['is_active'] == true) { ?>
                        <span class="badge bg-success">Active</span>
                      <?php } else { ?>
                        <span class="badge bg-secondary">In Active</span>
                      <?php } ?>
                    </td>
                    <td>
                      <button class="btn btn-sm btn-primary edit-holiday" data-id="<?= $holiday['id'] ?>" data-holiday-date="<?= $holiday['date'] ?>" data-holiday-name="<?= $holiday['holiday'] ?>"><i class="bi bi-gear-fill"></i></button>
                      <a href="<?= base_url('delete-holiday/') . $holiday['id'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></a>
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

<!-- Add Modal -->
<div class="modal fade" id="addHoliday" tabindex="-1" aria-labelledby="addHolidayLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addHolidayLabel">Add Holiday</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('do-company-add-holiday') ?>" method="post">
        <div class="modal-body">
          <div class="col-12 mt-2">
            <label for="input11" class="form-label">Date</label>
            <input type="date" class="form-control" id="input11" name="holiday_date">
          </div>
          <div class="col-12 mt-2">
            <label for="input22" class="form-label">Holiday Name</label>
            <input type="text" class="form-control" id="input22" name="holiday_name">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editHoliday" tabindex="-1" aria-labelledby="editHolidayLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editHolidayLabel">Edit Holiday</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editHolidayForm" method="post">
        <input type="hidden" name="id" id="holidayId">
        <div class="modal-body">
          <div class="col-12 mt-2">
            <label for="input11" class="form-label">Date</label>
            <input type="date" class="form-control" id="holidayDate" name="holiday_date">
          </div>
          <div class="col-12 mt-2">
            <label for="input22" class="form-label">Holiday Name</label>
            <input type="text" class="form-control" id="holidayName" name="holiday_name">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {

    $(document).on('click', '.edit-holiday', function() {
      $('#editHoliday').modal('show');
      var holidayId = $(this).data('id');
      var holidayDate = $(this).data('holiday-date');
      var holidayName = $(this).data('holiday-name');
      $('#holidayId').val(holidayId);
      $('#holidayDate').val(holidayDate);
      $('#holidayName').val(holidayName);
    });

    $(document).on('submit', '#editHolidayForm', function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url: '<?= base_url("edit-holiday") ?>',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
          if (response.status) {
            $('#editHolidayForm')[0].reset();
            $('#editHoliday').modal('hide');
            location.reload();
          } else {
            alert(response.message);
          }
        },
        error: function() {
          alert('Failed to edit holiday.');
        }
      });
    });

  });
</script>