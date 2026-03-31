<!doctype html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">

  <title><?= $title; ?> | <?= SITE_NAME; ?></title>

  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= base_url() ?>">
  <meta property="og:title" content="<?= SITE_NAME; ?>">
  <meta property="og:description" content="#">
  <meta property="og:image" content="<?= base_url(); ?>/assets/thunder.png">

  <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url(); ?>/assets/thunder.png">

  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <?php include_once 'application/views/backend/shared/css.php'; ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>

<body>

  <?php if ($this->session->flashdata('success') || $this->session->flashdata('fail')) { ?>
    <script>
      $(document).ready(function() {
        toastr.options = {
          "closeButton": true,
          "debug": false,
          "newestOnTop": false,
          "progressBar": true,
          "positionClass": "toast-top-right",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        };

        <?php if ($this->session->flashdata('success')) { ?>
          toastr.success('<?= $this->session->flashdata('success') ?>');
        <?php } ?>

        <?php if ($this->session->flashdata('fail')) { ?>
          toastr.error('<?= $this->session->flashdata('fail') ?>');
        <?php } ?>

      });
    </script>
    <?php $this->session->unset_userdata('success'); ?>
    <?php $this->session->unset_userdata('fail'); ?>
  <?php } ?>


  <!-- <div class="container d-block d-sm-none">
    <div class="row py-5">
      <div class="col-md-12">
        <div class="card">
          <h5 class="card-header">Rendering Limitation. <i class="fa fa-exclamation-triangle text-danger" aria-hidden="true"></i></h5>
          <div class="card-body">
            <h5 class="card-title pt-0 pb-0">Please note that the admin panel pages are optimized for desktop view and may not display optimally on mobile devices.</h5>
          </div>
        </div>
      </div>
    </div>
  </div> -->

  <!-- <div class="d-none d-sm-block"> -->

  <?php include_once 'application/views/backend/shared/header.php'; ?>

  <?php include_once 'application/views/backend/shared/sidebar.php'; ?>

  <?= $content; ?>

  <?php include_once 'application/views/backend/shared/footer.php'; ?>

  <?php include_once 'application/views/backend/shared/js.php'; ?>

  <!-- </div> -->

</body>

</html>