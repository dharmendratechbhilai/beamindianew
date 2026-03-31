<main id="main" class="main">
    <div class="pagetitle">
        <h1>Designations</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin-home') ?>">Home</a></li>
                <li class="breadcrumb-item">Configuration</li>
                <li class="breadcrumb-item active">Designations</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Designation</h5>
                        <form class="row g-2" id="designationForm">
                            <div class="col-12">
                                <label for="designationName" class="form-label">Designation Name</label>
                                <input type="text" class="form-control" id="designationName" name="designationName" placeholder="...">
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
                        <h5 class="card-title">Designation List</h5>
                        <div class="designationContainer"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editDesignation">
    Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="editDesignation" tabindex="-1" aria-labelledby="editDesignationLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editDesignationLabel">Edit Designation</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDesignationForm">
                <input type="hidden" id="editDesignationId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editDesignationName" class="form-label">Designation Name</label>
                        <input type="text" class="form-control" id="editDesignationName" name="editDesignationName">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm" id="editDesignationSubmit">Save changes</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        loadDesignationList();

        function loadDesignationList() {
            $.ajax({
                url: '<?= base_url("load-designations") ?>',
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $('.designationContainer').html('<div class="text-center p-3">Loading designations...</div>');
                },
                success: function(response) {
                    $('.designationContainer').html(response.html);
                },
                error: function() {
                    $('.designationContainer').html('<div class="text-center p-3 text-danger">Failed to load designations.</div>');
                }
            });
        }

        $(document).on('submit', '#designationForm', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: '<?= base_url("add-designation") ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                beforeSend: function() {
                    $('.designationContainer').html('<div class="text-center p-3">Loading designations...</div>');
                },
                success: function(response) {
                    $('.designationContainer').html(response.html);
                    if (response.status) {
                        $('#designationForm')[0].reset();
                        loadDesignationList();
                        // alert(response.message);
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    $('.designationContainer').html('<div class="text-center p-3 text-danger">Failed to load designations.</div>');
                }
            });
        });

        $(document).on('click', '.edit-designation', function() {
            $('#editDesignation').modal('show');
            var deptId = $(this).data('id');
            var designationName = $(this).data('designation-name');
            $('#editDesignationName').val(designationName);
            $('#editDesignationId').val(deptId);
        });

        $(document).on('submit', '#editDesignationForm', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: '<?= base_url("edit-designation") ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                beforeSend: function() {
                    $('.designationContainer').html('<div class="text-center p-3">Loading designations...</div>');
                },
                success: function(response) {
                    $('.designationContainer').html(response.html);
                    if (response.status) {
                        $('#editDesignation').modal('hide');
                        // alert(response.message);
                        loadDesignationList();
                        $('#editDesignationForm')[0].reset();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    $('.designationContainer').html('<div class="text-center p-3 text-danger">Failed to load designations.</div>');
                }
            });
        });

        $(document).on('click', '.delete-designation', function() {
            var deptId = $(this).data('id');
            if (confirm('Are you sure you want to delete this designation?')) {
                $.ajax({
                    url: '<?= base_url("delete-designation") ?>',
                    type: 'POST',
                    data: {
                        id: deptId
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('.designationContainer').html('<div class="text-center p-3">Loading designations...</div>');
                    },
                    success: function(response) {
                        $('.designationContainer').html(response.html);
                        if (response.status) {
                            // alert(response.message);
                            loadDesignationList();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        $('.designationContainer').html('<div class="text-center p-3 text-danger">Failed to load designations.</div>');
                    }
                });
            }
        });
    });
</script>