<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Company Login | <?= SITE_NAME; ?></title>
  <meta content="#" name="description">
  <meta content="#" name="keywords">

  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= base_url() ?>">
  <meta property="og:title" content="<?= SITE_NAME; ?>">
  <meta property="og:description" content="#">
  <meta property="og:image" content="<?= base_url(); ?>/assets/thunder.png">

  <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url(); ?>/assets/thunder.png">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <?php include_once 'application/views/backend/shared/css.php'; ?>

  <style>
    body {
      font-family: "Poppins", sans-serif;
      /* background: url('assets/backend/img/login-bg.jpg') center center fixed; */
      background-size: cover;
      color: #444444;
    }
  </style>
</head>

<body>
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-6 col-md-6 d-flex flex-column align-items-center justify-content-center">
              <div class="d-flex justify-content-center py-4">
                <a href="<?= base_url(); ?>" class="logo d-flex align-items-center w-auto">
                  <!-- <img src="<?= base_url(); ?>assets/backend/img/logo.png" alt=""> -->
                  <span class="">COMPANY PANEL</span>
                </a>
              </div><!-- End Logo -->
              <div class="card mb-3">
                <div class="card-body">
                  <form class="row g-3" action="<?= base_url('do-company-login') ?>" method="post">
                    <div class="col-12 pt-4">
                      <label for="yourUsername" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <input type="text" name="username" class="form-control" id="yourUsername" required>
                        <div class="invalid-feedback">Please enter your username.</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>
                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe" required>
                        <label class="form-check-label" for="rememberMe"><small>Please accept our <a href="#!">Privacy policy</a> to proceed with login procedure.</small></label>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">LOGIN <i class="bi bi-arrow-right"></i></button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="credits">
                <small>Powered by <a href="<?= base_url() ?>"><?= SITE_NAME; ?>.</a></small>
              </div>
              <div class="credits">
                <small><?= date('d-m-Y H:i:s'); ?> - <?= date_default_timezone_get(); ?></small>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>

  <?php include_once 'application/views/backend/shared/js.php'; ?>

</body>

</html>