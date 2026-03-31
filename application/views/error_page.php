<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>404 || Page Not Found</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    .jumbotron {
      padding: 2rem;
    }

    @media only screen and (max-width: 768px) {
      .display-4 {
        font-size: 2.5rem;
        font-weight: 300;
        line-height: 1.2;
      }

      .lead {
        font-size: 1rem;
        font-weight: 300;
      }

      .jumbotron {
        padding: 1rem;
      }

      .image-center {
        text-align: center;
      }
    }
  </style>
</head>

<body>
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="jumbotron bg-white">
          <div class="row">
            <div class="col-md-4 image-center">
              <img src="<?= base_url('assets/pageNotFound.png'); ?>" alt="404 Error Image" width="100%">
            </div>
            <div class="col-md-8 my-auto">
              <h1 class="display-4 mb-3">Oops! That page can't be found.</h1>
              <p class="lead">We're sorry for this inconvenience, but we are not able to find the page you are looking
                for. Probably the page is moved to somewhere else or an unknown error occurred.</p>
              <p class="lead pt-3">
                <a class="btn btn-outline-primary" href="<?= base_url(); ?>" role="button">
                  <i class="fa fa-undo" aria-hidden="true"></i> Back to Home
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>