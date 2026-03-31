<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
      <a class="nav-link " href="<?= base_url('admin-home'); ?>">
        <i class="bi bi-grid"></i>
        <span>DASHBOARD HOME</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#components-nav1" data-bs-toggle="collapse" href="#">
        <i class="bi bi-menu-button-wide"></i><span>Company Section</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="components-nav1" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="<?= base_url('company-list'); ?>">
            <i class="bi bi-circle"></i><span>Company List</span>
          </a>
        </li>
      </ul>
    </li>

    <li class="nav-heading">Other options</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="<?= base_url('admin-change-password'); ?>">
        <i class="bi bi-person"></i>
        <span>Change Password?</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="#!">
        <i class="bi bi-person"></i>
        <span>Logout</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="<?= base_url('blank'); ?>">
        <i class="bi bi-person"></i>
        <span>Blank Page</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="<?= base_url('data-table'); ?>">
        <i class="bi bi-person"></i>
        <span>Data Table</span>
      </a>
    </li>
  </ul>
</aside>