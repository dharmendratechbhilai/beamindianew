<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
      <a class="nav-link " href="<?= base_url('company-home'); ?>">
        <i class="bi bi-grid"></i>
        <span>Company Home</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#components-nav1" data-bs-toggle="collapse" href="#">
        <i class="bi bi-menu-button-wide"></i><span>Employee Section</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="components-nav1" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="<?= base_url('company-employee-list'); ?>">
            <i class="bi bi-circle"></i><span>Employee List</span>
          </a>
        </li>
        <!-- <li>
          <a href="<?= base_url('company-employee-list'); ?>">
            <i class="bi bi-circle"></i><span>Employee Analytics</span>
          </a>
        </li> -->
      </ul>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#components-nav2" data-bs-toggle="collapse" href="#">
        <i class="bi bi-menu-button-wide"></i><span>Emp. Attendance</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="components-nav2" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="<?= base_url('employee-today-attendance-list/') . date('Y-m-d'); ?>">
            <i class="bi bi-circle"></i><span>Todays Attendance</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('employee-attendance-details'); ?>">
            <i class="bi bi-circle"></i><span>Emp. Attendance Details</span>
          </a>
        </li>
      </ul>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#components-nav3" data-bs-toggle="collapse" href="#">
        <i class="bi bi-menu-button-wide"></i><span>Emp. Leave Section</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="components-nav3" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="<?= base_url('company-leave-list'); ?>">
            <i class="bi bi-circle"></i><span>Applied Leaves</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('company-approved-leave-list'); ?>">
            <i class="bi bi-circle"></i><span>Approved Leaves</span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('company-rejected-leave-list'); ?>">
            <i class="bi bi-circle"></i><span>Rejected Leaves</span>
          </a>
        </li>
      </ul>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#components-nav4" data-bs-toggle="collapse" href="#">
        <i class="bi bi-menu-button-wide"></i><span>Company Assets</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="components-nav4" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="<?= base_url('company-assets-list'); ?>">
            <i class="bi bi-circle"></i><span>Assets List</span>
          </a>
        </li>
      </ul>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#components-nav5" data-bs-toggle="collapse" href="#">
        <i class="bi bi-menu-button-wide"></i><span>Configuration</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="components-nav5" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li class="nav-item">
          <a class="nav-link collapsed" href="<?= base_url('company-department-list'); ?>">
            <i class="bi bi-person"></i>
            <span>Departments</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="<?= base_url('company-designation-list'); ?>">
            <i class="bi bi-person"></i>
            <span>Designations</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="<?= base_url('company-work-location-list'); ?>">
            <i class="bi bi-person"></i>
            <span>Work Locations</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="<?= base_url('company-policy-list'); ?>">
            <i class="bi bi-person"></i>
            <span>Company Policies</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="<?= base_url('company-holiday-list'); ?>">
            <i class="bi bi-person"></i>
            <span>Holidays</span>
          </a>
        </li>
      </ul>
    </li>

    <li class="nav-heading">Other SECTIONS</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="#!">
        <i class="bi bi-person"></i>
        <span>Company Profile</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="#!">
        <i class="bi bi-person"></i>
        <span>User Management</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="#!">
        <i class="bi bi-person"></i>
        <span>Company Settings</span>
      </a>
    </li>
  </ul>
</aside>