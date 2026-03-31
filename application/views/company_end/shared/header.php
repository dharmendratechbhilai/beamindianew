  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="<?= base_url('company-home') ?>" class="logo d-flex align-items-center">
        <span class="d-none d-lg-block">COMPANY PANEL</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="<?= base_url('assets/user.jpg') ?>" alt="Profile" class="">
            <span class="d-none d-md-block dropdown-toggle">COMPANY</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>COMPANY</h6>
              <span><?= SITE_NAME; ?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?= base_url('company-faq') ?>">
                <i class="bi bi-question-circle"></i>
                <span class="text-success">Need Help?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?= base_url('company-logout') ?>">
                <i class="bi bi-box-arrow-right"></i>
                <span class="text-danger"><b>LOGOUT</b></span>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </header>