<?php if ($this->session->flashdata('toast_message')): ?>
		<div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
  <div id="toastMessage" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <img width="20" src="<?= base_url(); ?>/assets/thunder.png" class="rounded me-2" alt="...">
      <strong class="me-auto"><?= SITE_NAME; ?></strong>
      <small><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></small>
    </div>
    <div class="toast-body" style="font-size:14px;">
		<?php echo $this->session->flashdata('toast_message'); ?>
    </div>
  </div>
</div>

  <script>
          document.addEventListener("DOMContentLoaded", function() {
              var toastElement = document.getElementById("toastMessage");
              var toast = new bootstrap.Toast(toastElement);
              toast.show();
          });
      </script>
<?php endif; ?>