

<div class="container-fluid p-8">
  <!-- Page Header -->
  <?php
  $user_data = $data['user_data'];
  $config = [
      'title' => 'My Profile',
      'description' => 'Manage your personal information and solar system details'
  ];
  include __DIR__ . '/../../inc/components/page_header.php';
  ?>

  <div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
      <?php
      // One array for all profile fields, grouped by section
      $profileSections = [
          [
              'title' => 'Personal Details',
              'fields' => [
                  [
                      'id' => 'full-name',
                      'label' => 'Full Name',
                      'value' => $user_data->full_name ?? '',
                      'type' => 'text',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-name'
                  ],
                  [
                      'id' => 'email',
                      'label' => 'Email',
                      'value' => $user_data->email ?? '',
                      'type' => 'email',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-email'
                  ],
                  [
                      'id' => 'phone',
                      'label' => 'Phone number',
                      'value' => $user_data->phone_number ?? '',
                      'type' => 'tel',
                      'editable' => true,
                      'required' => false,
                      'summaryTarget' => 'summary-phone'
                  ],
                  [
                      'id' => 'address',
                      'label' => 'Address',
                      'value' => $user_data->address ?? '',
                      'type' => 'text',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-location'
                  ],
                  [
                      'id' => 'district',
                      'label' => 'District',
                      'value' => $user_data->district ?? '',
                      'type' => 'text',
                      'editable' => false
                  ]
              ]
          ],
          [
              'title' => 'System Specification',
              'fields' => [
                  [
                      'id' => 'system-capacity',
                      'label' => 'System Capacity',
                      'value' => $user_data->system_capacity ? $user_data->system_capacity . ' kWp' : '',
                      'type' => 'text',
                      'editable' => false
                  ],
                  [
                      'id' => 'panel-tilt',
                      'label' => 'Panel Tilt',
                      'value' => $user_data->system_tilt ? $user_data->system_tilt . '°' : '',
                      'type' => 'text',
                      'editable' => false
                  ],
                  [
                      'id' => 'panel-azimuth',
                      'label' => 'Panel Azimuth',
                      'value' => $user_data->system_azimuth ? $user_data->system_azimuth . '°' : '',
                      'type' => 'text',
                      'editable' => false
                  ],
                  [
                      'id' => 'installation-date',
                      'label' => 'Installation Date',
                      'value' => $user_data->installation_date ?? '',
                      'type' => 'date',
                      'editable' => false
                  ],
                  [
                      'id' => 'panel-brand',
                      'label' => 'Panel Brand',
                      'value' => $user_data->panel_brand ?? '',
                      'type' => 'text',
                      'editable' => false
                  ],
                  [
                      'id' => 'inverter-brand',
                      'label' => 'Inverter Brand',
                      'value' => $user_data->inverter_brand ?? '',
                      'type' => 'text',
                      'editable' => false
                  ]
              ]
          ],
          [
              'title' => 'Utility Details',
              'fields' => [
                  [
                      'id' => 'ceb-account',
                      'label' => 'CEB Account',
                      'value' => $user_data->ceb_account ?? '',
                      'type' => 'text',
                      'editable' => false
                  ],
                  [
                      'id' => 'provider',
                      'label' => 'Provider',
                      'value' => 'Ceylon Electricity Board',
                      'type' => 'text',
                      'editable' => false
                  ]
              ]
          ]
      ];
      
      // Render all profile sections
      foreach ($profileSections as $section):
      ?>
      <div class="card mb-4">
        <div class="card-header bg-light border-bottom">
          <h5 class="mb-0"><?php echo htmlspecialchars($section['title']); ?></h5>
        </div>
        <div class="card-body">
          <div class="row">
            <?php
            // Render fields for this section
            foreach ($section['fields'] as $field) {
                $inputConfig = [
                    'id' => $field['id'],
                    'name' => $field['id'],
                    'label' => $field['label'],
                    'value' => $field['value'],
                    'type' => $field['type'] ?? 'text',
                    'required' => $field['required'] ?? false,
                    'editable' => $field['editable'] ?? true,
                    'wrapperClass' => 'mb-3'
                ];
                
                // Add data attribute for summary updates
                if (!empty($field['summaryTarget'])) {
                    $inputConfig['inputClass'] = 'update-summary';
                }
            ?>
              <div class="col-md-6">
                <?php include APPROOT . '/views/inc/components/input_field.php'; ?>
              </div>
            <?php
            }
            ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
      <div class="card sticky-top" style="top: 20px;">
        <div class="card-body">
          <!-- Avatar Upload - Centered -->

          <!-- Profile Info -->
          <div class="text-center">
            <img src="<?php echo htmlspecialchars(getAvatarUrl($user_data->full_name, 140)); ?>" alt="Profile" style="object-fit:cover;">

            <h5 class="mb-1 fw-bold" id="summary-name"><?php echo htmlspecialchars($user_data->full_name); ?></h5>
            <p class="text-muted small mb-1" id="summary-email"><?php echo htmlspecialchars($user_data->email ); ?></p>
            <p class="text-muted small mb-1" id="summary-location"><?php echo htmlspecialchars($user_data->address); ?></p>
            <p class="text-muted small mb-4" id="summary-phone"><?php echo htmlspecialchars($user_data->phone_number); ?></p>
          </div>

          <!-- Divider -->
          <hr>

          <!-- Stats -->
          <div class="row text-center">
            <div class="col-6">
              <div class="mb-3">
                <h6 class="fw-bold mb-1">5 kWp</h6>
                <small class="text-muted">System Capacity</small>
              </div>
            </div>
            <div class="col-6">
              <div class="mb-3">
                <h6 class="fw-bold mb-1">123456VC</h6>
                <small class="text-muted">CEB Account</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Update summary card when inputs change
document.querySelectorAll('.update-summary').forEach(input => {
  const id = input.id;
  const summaryMap = {
    'full-name': 'summary-name',
    'email': 'summary-email',
    'phone': 'summary-phone',
    'address': 'summary-location'
  };
  
  if (summaryMap[id]) {
    input.addEventListener('input', function() {
      const target = document.getElementById(summaryMap[id]);
      if (target) {
        target.textContent = this.value;
      }
    });
  }
});

// Avatar upload handler
const avatarUpload = document.getElementById('avatar-upload');
const profileAvatar = document.getElementById('profile-avatar');

avatarUpload.addEventListener('change', function () {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      const img = profileAvatar.querySelector('img');
      if (img) {
        img.src = e.target.result;
      } else {
        profileAvatar.style.backgroundImage = `url(${e.target.result})`;
      }
    };
    reader.readAsDataURL(file);
  }
});
</script>