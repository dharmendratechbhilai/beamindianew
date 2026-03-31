<main id="main" class="main">
  <div class="pagetitle">
    <h1>Departments</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin-home') ?>">Home</a></li>
        <li class="breadcrumb-item">Configuration</li>
        <li class="breadcrumb-item active">Departments</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Add Department</h5>
            <form class="row g-2" id="departmentForm">
              <div class="col-12">
                <label for="departmentName" class="form-label">Department Name</label>
                <input type="text" class="form-control" id="departmentName" name="departmentName" placeholder="...">
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
            <h5 class="card-title">Department List</h5>
            <div class="departmentContainer"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editDepartment">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="editDepartment" tabindex="-1" aria-labelledby="editDepartmentLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editDepartmentLabel">Edit Department</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editDepartmentForm">
        <input type="hidden" id="editDepartmentId" name="id">
        <div class="modal-body">
          <div class="mb-3">
            <label for="editDepartmentName" class="form-label">Department Name</label>
            <input type="text" class="form-control" id="editDepartmentName" name="editDepartmentName">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-sm" id="editDepartmentSubmit">Save changes</button>
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {

    loadDepartmentList();

    function loadDepartmentList() {
      $.ajax({
        url: '<?= base_url("load-departments") ?>',
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
          $('.departmentContainer').html('<div class="text-center p-3">Loading departments...</div>');
        },
        success: function(response) {
          $('.departmentContainer').html(response.html);
        },
        error: function() {
          $('.departmentContainer').html('<div class="text-center p-3 text-danger">Failed to load departments.</div>');
        }
      });
    }

    $(document).on('submit', '#departmentForm', function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url: '<?= base_url("add-department") ?>',
        type: 'POST',
        data: formData,
        dataType: 'json',
        beforeSend: function() {
          $('.departmentContainer').html('<div class="text-center p-3">Loading departments...</div>');
        },
        success: function(response) {
          $('.departmentContainer').html(response.html);
          if (response.status) {
            $('#departmentForm')[0].reset();
            loadDepartmentList();
            // alert(response.message);
          } else {
            alert(response.message);
          }
        },
        error: function() {
          $('.departmentContainer').html('<div class="text-center p-3 text-danger">Failed to load departments.</div>');
        }
      });
    });

    $(document).on('click', '.edit-dept', function() {
      $('#editDepartment').modal('show');
      var deptId = $(this).data('id');
      var deptName = $(this).data('dept-name');
      $('#editDepartmentName').val(deptName);
      $('#editDepartmentId').val(deptId);
    });

    $(document).on('submit', '#editDepartmentForm', function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url: '<?= base_url("edit-department") ?>',
        type: 'POST',
        data: formData,
        dataType: 'json',
        beforeSend: function() {
          $('.departmentContainer').html('<div class="text-center p-3">Loading departments...</div>');
        },
        success: function(response) {
          $('.departmentContainer').html(response.html);
          if (response.status) {
            $('#editDepartment').modal('hide');
            // alert(response.message);
            loadDepartmentList();
            $('#editDepartmentForm')[0].reset();
          } else {
            alert(response.message);
          }
        },
        error: function() {
          $('.departmentContainer').html('<div class="text-center p-3 text-danger">Failed to load departments.</div>');
        }
      });
    });

    $(document).on('click', '.delete-dept', function() {
      var deptId = $(this).data('id');
      if (confirm('Are you sure you want to delete this department?')) {
        $.ajax({
          url: '<?= base_url("delete-department") ?>',
          type: 'POST',
          data: {
            id: deptId
          },
          dataType: 'json',
          beforeSend: function() {
            $('.departmentContainer').html('<div class="text-center p-3">Loading departments...</div>');
          },
          success: function(response) {
            $('.departmentContainer').html(response.html);
            if (response.status) {
              // alert(response.message);
              loadDepartmentList();
            } else {
              alert(response.message);
            }
          },
          error: function() {
            $('.departmentContainer').html('<div class="text-center p-3 text-danger">Failed to load departments.</div>');
          }
        });
      }
    });
  });
</script>