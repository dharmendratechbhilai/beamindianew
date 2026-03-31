<main id="main" class="main">
  <div class="pagetitle">
    <h1>Attendance Details</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin-home') ?>">Home</a></li>
        <li class="breadcrumb-item">Todays Attendance</li>
        <li class="breadcrumb-item active">Details</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Employee Data</h5>
            <!-- Default List group -->
            <ul class="list-group">
              <li class="list-group-item">Employee uid: <?= $employeeData['employee_uid']; ?></li>
              <li class="list-group-item">Employee Code: <?= $employeeData['employee_code']; ?></li>
              <li class="list-group-item">Department: <?= $employeeData['department_name'] ?></li>
              <li class="list-group-item">Designation: <?= $employeeData['designation_name'] ?></li>
            </ul><!-- End Default List group -->
          </div>
        </div>
      </div>
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Attendance Details</h5>
            <p>Date : <?php echo date('Y-m-d') ?></p>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Policy</th>
                  <th>Standard time</th>
                  <th>Timestamp</th>
                  <th>Difference</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <!-- Punch In Row -->
                <tr>
                  <td>Punch In</td>
                  <td><b><?= date('h:i A', strtotime($policyData['in_time'])) ?></b></td>
                  <td>
                    <?php
                    if (!is_null($attendanceData['punch_in'])) {
                      echo date('h:i A', strtotime($attendanceData['punch_in']));
                    } else {
                      echo '--:--';
                    }
                    ?>
                  </td>
                  <td>
                    <?php
                    $policyIn = new DateTime($policyData['in_time']);
                    if (!is_null($attendanceData['punch_in'])) {
                      $punchIn  = new DateTime($attendanceData['punch_in']);
                      $diffIn   = $policyIn->diff($punchIn)->format('%H:%I');
                      $isLateIn = ($punchIn > $policyIn);
                      echo ($isLateIn ? '+ ' : '- ') . $diffIn . " hrs.";
                    } else {
                      echo '--:--';
                    }
                    ?>
                  </td>
                  <td>
                    <?php
                    if (!is_null($attendanceData['punch_in'])) {
                      if ($punchIn == $policyIn) {
                        echo '<span class="text-success">On Time</span>';
                      } elseif ($isLateIn) {
                        echo '<span class="text-danger">Late</span>';
                      } else {
                        echo '<span class="text-success">Early</span>';
                      }
                    } else {
                      echo '<span class="text-secondary">Pending</span>';
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>Punch Out</td>
                  <td><b><?= date('h:i A', strtotime($policyData['out_time'])) ?></b></td>
                  <td>
                    <?= !is_null($attendanceData['punch_out']) ? date('h:i A', strtotime($attendanceData['punch_out'])) : '--:--'; ?></td>
                  <td>
                    <?php
                    if (!is_null($attendanceData['punch_out'])) {
                      $policyOut = new DateTime($policyData['out_time']);
                      $punchOut  = new DateTime($attendanceData['punch_out']);
                      $diffOut   = $policyOut->diff($punchOut)->format('%H:%I');
                      $isLateOut = ($punchOut > $policyOut);
                      echo ($isLateOut ? '+ ' : '- ') . $diffOut . " hrs.";
                    } else {
                      echo "--:--";
                    }
                    ?>
                  </td>
                  <td>
                    <?php
                    if (!is_null($attendanceData['punch_out'])) {
                      if ($punchOut == $policyOut) {
                        echo '<span class="text-success">On Time</span>';
                      } elseif ($isLateOut) {
                        echo '<span class="text-success">Overstay</span>'; // stayed longer
                      } else {
                        echo '<span class="text-warning">Early Leave</span>';
                      }
                    } else {
                      echo '<span class="text-secondary">Pending</span>';
                    }
                    ?>
                  </td>
                </tr>

                <!-- Total Working Hours -->
                <tr>
                  <td>Total Working Hours</td>
                  <td><b><?= date('h:i A', strtotime($policyData['total_working_hours'])) ?></b></td>

                  <td>
                    <?php
                    if (!is_null($attendanceData['punch_out'])) {
                      echo $punchIn->diff($punchOut)->format('%H:%I') . ' hrs.';
                    } else {
                      echo '--:--';
                    }
                    ?>
                  </td>
                  <td></td>
                  <td></td>
                </tr>

                <!-- Overtime -->
                <tr>
                  <td>Over Time</td>
                  <td>
                    <?php
                    if (!is_null($attendanceData['punch_out'])) {
                      if ($punchOut > $policyOut) {
                        echo $policyOut->diff($punchOut)->format('%H:%I') . ' hrs.';
                      } else {
                        echo '--:--';
                      }
                    } else {
                      echo '--:--';
                    }
                    ?>
                  </td>
                  <td></td>
                  <td></td>
                  <td>
                    <?php
                    if (!is_null($attendanceData['punch_out'])) {
                      if ($punchOut > $policyOut) {
                        echo '<span class="text-success">OT Earned</span>';
                      } else {
                        echo '<span class="text-secondary">No OT</span>';
                      }
                    } else {
                      echo '<span class="text-secondary">Pending</span>';
                    }
                    ?>
                  </td>
                </tr>

              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </section>
</main>