<main id="main" class="main">
  <div class="pagetitle">
    <h1>Policy Details</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('company-home') ?>">Home</a></li>
        <li class="breadcrumb-item">Configuration</li>
        <li class="breadcrumb-item active">Policy</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Policy Details</h5>
            <!-- Bordered Table -->
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Policy Name</th>
                  <th scope="col">Policy Description</th>
                  <th scope="col">Policy Value</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">1</th>
                  <td>Monthly Leave Accural</td>
                  <td>
                    <p class="mb-0 small">Defines how many paid leaves an employee earns each month. The system automatically adds this number to the employee’s leave balance every month.</p>
                  </td>
                  <td>
                    <input type="number" class="form-control" id="monthly_leave_accrual" value="<?= $companyPolicyData[0]['monthly_leave_accrual']; ?>">
                  </td>
                  <td>
                    <button class="btn btn-primary updatePolicy" data-policy="monthly_leave_accrual"><i class="bi bi-arrow-clockwise"></i></button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">2</th>
                  <td>Probation Period</td>
                  <td>
                    <p class="mb-0 small">Specifies the duration (in months) of the probationary period for new employees, during which certain benefits or leaves may not apply.</p>
                  </td>
                  <td>
                    <input type="number" class="form-control" id="probation_period" value="<?= $companyPolicyData[0]['probation_period']; ?>">
                  </td>
                  <td>
                    <button class="btn btn-primary updatePolicy" data-policy="probation_period"><i class="bi bi-arrow-clockwise"></i></button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">3</th>
                  <td>Leave Carry Forward</td>
                  <td>
                    <p class="mb-0 small">Determines whether unused leaves at the end of the year can be carried over to the next year or not.</p>
                  </td>
                  <td>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="leave_carry_forward" <?= $companyPolicyData[0]['leave_carry_forward'] == 1 ? 'checked' : '' ?>>
                    </div>
                  </td>
                  <td>
                    <button class="btn btn-primary updatePolicy" data-policy="leave_carry_forward"><i class="bi bi-arrow-clockwise"></i></button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">4</th>
                  <td>Leave Encashment</td>
                  <td>
                    <p class="mb-0 small">Allows employees to encash their unused earned leaves either during employment or at the time of separation, based on company policy.</p>
                  </td>
                  <td>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="leave_encashment" <?= $companyPolicyData[0]['leave_encashment'] == 1 ? 'checked' : '' ?>>
                    </div>
                  </td>
                  <td>
                    <button class="btn btn-primary updatePolicy" data-policy="leave_encashment"><i class="bi bi-arrow-clockwise"></i></button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">5</th>
                  <td>Half Day Leave</td>
                  <td>
                    <p class="mb-0 small">Enables employees to apply for half-day leave instead of a full day, useful for short personal commitments or emergencies.</p>
                  </td>
                  <td>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="half_day_leave" <?= $companyPolicyData[0]['half_day_leave'] == 1 ? 'checked' : '' ?>>
                    </div>
                  </td>
                  <td>
                    <button class="btn btn-primary updatePolicy" data-policy="half_day_leave"><i class="bi bi-arrow-clockwise"></i></button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">6</th>
                  <td>Leave Application</td>
                  <td>
                    <p class="mb-0 small">Sets the minimum number of days prior to the leave start date that an employee must apply for leave.</p>
                  </td>
                  <td>
                    <input type="number" class="form-control" id="leave_application" value="<?= $companyPolicyData[0]['leave_application']; ?>">
                  </td>
                  <td>
                    <button class="btn btn-primary updatePolicy" data-policy="leave_application"><i class="bi bi-arrow-clockwise"></i></button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">7</th>
                  <td>Medical Certificate</td>
                  <td>
                    <p class="mb-0 small">Indicates after how many consecutive sick leave days a medical certificate is required for approval.</p>
                  </td>
                  <td>
                    <input type="number" class="form-control" id="medical_certificate" value="<?= $companyPolicyData[0]['medical_certificate']; ?>">
                  </td>
                  <td>
                    <button class="btn btn-primary updatePolicy" data-policy="medical_certificate"><i class="bi bi-arrow-clockwise"></i></button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">8</th>
                  <td>Over Time</td>
                  <td>
                    <p class="mb-0 small">Enables or disables overtime tracking. When enabled, the system records extra working hours beyond standard working time.</p>
                  </td>
                  <td>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="over_time" <?= $companyPolicyData[0]['over_time'] == 1 ? 'checked' : '' ?>>
                    </div>
                  </td>
                  <td>
                    <button class="btn btn-primary updatePolicy" data-policy="over_time"><i class="bi bi-arrow-clockwise"></i></button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">9</th>
                  <td>Over Time Pay</td>
                  <td>
                    <p class="mb-0 small">Determines the multiplier of the employee's hourly basic pay used to calculate overtime wages (e.g. 0.5 = half pay, 1 = normal rate, 2 = double rate).</p>
                  </td>
                  <td>
                    <input type="number" class="form-control" step="0.5" id="over_time_pay" value="<?= $companyPolicyData[0]['over_time_pay']; ?>">
                  </td>
                  <td>
                    <button class="btn btn-primary updatePolicy" data-policy="over_time_pay"><i class="bi bi-arrow-clockwise"></i></button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">10</th>
                  <td>Punch in time</td>
                  <td>
                    <p class="mb-0 small">Specify the time you began your shift. This information is essential for tracking employee presence and punctuality.</p>
                  </td>
                  <td>
                    <input type="time" class="form-control" id="in_time" value="<?= $companyPolicyData[0]['in_time']; ?>">
                  </td>
                  <td>
                    <button class="btn btn-primary updatePolicy" data-policy="in_time"><i class="bi bi-arrow-clockwise"></i></button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">11</th>
                  <td>Punch out time</td>
                  <td>
                    <p class="mb-0 small">Provide the time you left the workplace or signed off for the day. This helps maintain accurate attendance and timing reports.</p>
                  </td>
                  <td>
                    <input type="time" class="form-control" id="out_time" value="<?= $companyPolicyData[0]['out_time']; ?>">
                  </td>
                  <td>
                    <button class="btn btn-primary updatePolicy" data-policy="out_time"><i class="bi bi-arrow-clockwise"></i></button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">12</th>
                  <td>Punch in Grace Period</td>
                  <td>
                    <p class="mb-0 small">...</p>
                  </td>
                  <td>
                    <input type="time" class="form-control" id="punch_in_grace_period" value="<?= $companyPolicyData[0]['punch_in_grace_period']; ?>">
                  </td>
                  <td>
                    <button class="btn btn-primary updatePolicy" data-policy="punch_in_grace_period"><i class="bi bi-arrow-clockwise"></i></button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">13</th>
                  <td>Total Working hours</td>
                  <td>
                    <p class="mb-0 small">...</p>
                  </td>
                  <td>
                    <input type="time" class="form-control" id="total_working_hours" value="<?= $companyPolicyData[0]['total_working_hours']; ?>">
                  </td>
                  <td>
                    <button class="btn btn-primary updatePolicy" data-policy="total_working_hours"><i class="bi bi-arrow-clockwise"></i></button>
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- End Bordered Table -->
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<script>
  $(document).ready(function() {

    $(document).on("click", ".updatePolicy", function(e) {
      e.preventDefault();

      // Get policy name and value
      let policyKey = $(this).data("policy");
      // let policyValue = $("#" + policyKey).val();

      let $el = $("#" + policyKey);
      // Determine value based on input type
      let policyValue;
      if ($el.is(":checkbox")) {
        policyValue = $el.prop("checked") ? 1 : 0; // store as 1 or 0
      } else {
        policyValue = $el.val();
      }

      $.ajax({
        url: "<?= base_url("update-company-policy") ?>",
        type: "POST",
        data: {
          policy_key: policyKey,
          policy_value: policyValue
        },
        dataType: "json",
        beforeSend: function() {
          // Optional loading effect
          $("#" + policyKey).prop("disabled", true);
        },
        success: function(response) {
          if (response.success) {
            alert("Policy updated successfully!");
          } else {
            alert(response.message || "Failed to update policy.");
          }
        },
        error: function() {
          alert("Something went wrong while updating the policy.");
        },
        complete: function() {
          $("#" + policyKey).prop("disabled", false);
        }
      });
    });
  });
</script>