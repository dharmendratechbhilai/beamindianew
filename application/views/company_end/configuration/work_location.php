<main id="main" class="main">
  <div class="pagetitle">
    <h1>Work Location</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin-home') ?>">Home</a></li>
        <li class="breadcrumb-item">Configuration</li>
        <li class="breadcrumb-item active">Work Location</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Add Work Location</h5>
            <form class="row g-2" id="workLocationForm">
              <div class="col-12">
                <label for="workLocationName" class="form-label">Work Location Name</label>
                <input type="text" class="form-control" id="workLocationName" name="workLocationName" placeholder="...">
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Work Location List</h5>
            <div class="workLocationContainer"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<!-- Modal -->
<div class="modal fade" id="editWorkLocation" tabindex="-1" aria-labelledby="editWorkLocationLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editWorkLocationLabel">Edit Work Location</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editWorkLocationForm">
        <input type="hidden" id="editWorkLocationId" name="id">
        <div class="modal-body">
          <div class="mb-3">
            <label for="editWorkLocationName" class="form-label">Work Location Name</label>
            <input type="text" class="form-control" id="editWorkLocationName" name="editWorkLocationName">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-sm" id="editWorkLocationSubmit">Save changes</button>
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {

    loadWorkLocationList();

    function loadWorkLocationList() {
      $.ajax({
        url: '<?= base_url("load-work-locations") ?>',
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
          $('.workLocationContainer').html('<div class="text-center p-3">Loading work locations...</div>');
        },
        success: function(response) {
          $('.workLocationContainer').html(response.html);
        },
        error: function() {
          $('.workLocationContainer').html('<div class="text-center p-3 text-danger">Failed to load work locations.</div>');
        }
      });
    }

    $(document).on('submit', '#workLocationForm', function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url: '<?= base_url("add-work-location") ?>',
        type: 'POST',
        data: formData,
        dataType: 'json',
        beforeSend: function() {
          $('.workLocationContainer').html('<div class="text-center p-3">Loading work locations...</div>');
        },
        success: function(response) {
          $('.workLocationContainer').html(response.html);
          if (response.status) {
            $('#workLocationForm')[0].reset();
            loadWorkLocationList();
            // alert(response.message);
          } else {
            alert(response.message);
          }
        },
        error: function() {
          $('.workLocationContainer').html('<div class="text-center p-3 text-danger">Failed to load work locations.</div>');
        }
      });
    });

    $(document).on('click', '.edit-work-location', function() {
      $('#editWorkLocation').modal('show');
      var workLocationId = $(this).data('id');
      var workLocationName = $(this).data('work-location-name');
      $('#editWorkLocationName').val(workLocationName);
      $('#editWorkLocationId').val(workLocationId);
    });

    $(document).on('submit', '#editWorkLocationForm', function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url: '<?= base_url("edit-work-location") ?>',
        type: 'POST',
        data: formData,
        dataType: 'json',
        beforeSend: function() {
          $('.workLocationContainer').html('<div class="text-center p-3">Loading work locations...</div>');
        },
        success: function(response) {
          $('.workLocationContainer').html(response.html);
          if (response.status) {
            $('#editWorkLocation').modal('hide');
            // alert(response.message);
            loadWorkLocationList();
            $('#editWorkLocationForm')[0].reset();
          } else {
            alert(response.message);
          }
        },
        error: function() {
          $('.workLocationContainer').html('<div class="text-center p-3 text-danger">Failed to load work locations.</div>');
        }
      });
    });

    $(document).on('click', '.delete-work-location', function() {
      var deptId = $(this).data('id');
      if (confirm('Are you sure you want to delete this designation?')) {
        $.ajax({
          url: '<?= base_url("delete-work-location") ?>',
          type: 'POST',
          data: {
            id: deptId
          },
          dataType: 'json',
          beforeSend: function() {
            $('.workLocationContainer').html('<div class="text-center p-3">Loading work locations...</div>');
          },
          success: function(response) {
            $('.workLocationContainer').html(response.html);
            if (response.status) {
              // alert(response.message);
              loadWorkLocationList();
            } else {
              alert(response.message);
            }
          },
          error: function() {
            $('.workLocationContainer').html('<div class="text-center p-3 text-danger">Failed to load work locations.</div>');
          }
        });
      }
    });
  });
</script>