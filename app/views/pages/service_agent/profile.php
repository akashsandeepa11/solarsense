<div class="container-fluid p-8">
  <!-- Page Header -->
  <?php
  $config = [
      'title' => 'My Profile',
      'description' => 'Manage your personal information and work details'
  ];
  include __DIR__ . '/../../inc/components/page_header.php';
  ?>

  <?php
  $profile_data = $data['profileData'];
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
                      'value' =>  $profile_data['full_name'],
                      'type' => 'text',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-name'
                  ],
                  [
                      'id' => 'email',
                      'label' => 'Email',
                      'value' => $profile_data['email'],
                      'type' => 'email',
                      'editable' => false,
                      'required' => true,
                      'summaryTarget' => 'summary-email'
                  ],
                  [
                      'id' => 'phone',
                      'label' => 'Phone number',
                      'value' => $profile_data['contact'],
                      'type' => 'tel',
                      'editable' => true,
                      'required' => false,
                      'summaryTarget' => 'summary-phone'
                  ],
                  [
                      'id' => 'address',
                      'label' => 'Address',
                      'value' => $profile_data['address'],
                      'type' => 'text',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-location'
                  ],
                  [
                      'id' => 'district',
                      'label' => 'District',
                      'value' => $profile_data['district'],
                      'type' => 'text',
                      'editable' => false
                  ]
              ]
          ],
          [
              'title' => 'Company Details',
              'fields' => [
                  [
                      'id' => 'agent-id',
                      'label' => 'Agent ID',
                      'value' => $profile_data['user_id'],
                      'type' => 'text',
                      'editable' => false
                  ],
                  [
                      'id' => 'company-name',
                      'label' => 'Company Name',
                      'value' => $profile_data['company_name'],
                      'type' => 'text',
                      'editable' => false
                  ],
                  [
                      'id' => 'register-date',
                      'label' => 'Registered on',
                      'value' => $profile_data['register_date'],
                      'type' => 'text',
                      'editable' => false
                  ],
                  [
                      'id' => 'specialization',
                      'label' => 'Specialization',
                      'value' => $profile_data['specialization'],
                      'type' => 'text',
                      'editable' => false,
                      'summaryTarget' => 'summary-specialization'
                  ],
                  [
                      'id' => 'status',
                      'label' => 'Status',
                      'value' => $profile_data['status'],
                      'type' => 'text',
                      'editable' => true,
                      'summaryTarget' => 'summary-status'
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

          <!-- Profile Info -->
          <div class="text-center">
            <img src="<?php echo htmlspecialchars(getAvatarUrl('Alexa Rawles', 140)); ?>" alt="Profile" style="object-fit:cover;">
            <h5 class="mb-1 fw-bold" id="summary-name">Alexa Rawles</h5>
            <p class="text-muted small mb-1" id="summary-email">alexarawles@gmail.com</p>
            <p class="text-muted small mb-3" id="summary-location">Colombo 07, Sri Lanka</p>
          </div>

          <!-- Rating -->
          <!-- <div class="d-flex justify-content-center gap-1 mb-4">
            <i class="fas fa-star" style="color: #fbbf24;"></i>
            <i class="fas fa-star" style="color: #fbbf24;"></i>
            <i class="fas fa-star" style="color: #fbbf24;"></i>
            <i class="fas fa-star" style="color: #fbbf24;"></i>
            <i class="fas fa-star" style="color: #d1d5db;"></i>
          </div> -->

          <!-- Divider -->
          <hr>

          <!-- Stats -->
          <div class="row text-center">
            <div class="col-12">
              <div class="mb-3">
                <h6 class="fw-bold mb-1" id="summary-specialization">Specialization</h6>
                <small class="text-muted">Specialization</small>
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
    'address': 'summary-location',
    'experience': 'summary-specialization',
    'total-works': 'summary-status'
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
