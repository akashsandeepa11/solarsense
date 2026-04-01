

<div class="container-fluid p-8">
  <!-- Page Header -->
  <?php
  $profile_data = $data['user_data'];
  $config = [
      'title' => 'Company Profile',
      'description' => 'Manage your company information and settings'
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
              'title' => 'Company Information',
              'fields' => [
                  [
                      'id' => 'company-name',
                      'label' => 'Company Name',
                      'value' => $profile_data->full_name,
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-name'
                  ],
                  [
                      'id' => 'business-email',
                      'label' => 'Business Email',
                      'value' => $profile_data->email,
                      'type' => 'email',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-email'
                  ],
                  [
                      'id' => 'contact-number',
                      'label' => 'Contact Number',
                      'value' => $profile_data->phone_number,
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-phone'
                  ],
                  [
                      'id' => 'business-address',
                      'label' => 'Business Address',
                      'value' => $profile_data->address,
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-location'
                  ],
                  [
                      'id' => 'website',
                      'label' => 'Website',
                      'value' => $profile_data->website,
                      'type' => 'url',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-website'
                  ], 
                  [
                      'id' => 'district',
                      'label' => 'District',
                      'value' => $profile_data->district,
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-district'
                  ],
                  [
                      'id' => 'register_date',
                      'label' => 'Register Date',
                      'value' => $profile_data->register_date,
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-register-date'
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
                // Handle different field types
                if (isset($field['fieldType']) && $field['fieldType'] === 'select') {
                    // Select field
                    $selectConfig = [
                        'id' => $field['id'],
                        'name' => $field['id'],
                        'label' => $field['label'],
                        'value' => $field['value'],
                        'options' => $field['options'] ?? [],
                        'required' => $field['required'] ?? false,
                        'editable' => $field['editable'] ?? true,
                        'wrapperClass' => 'mb-3'
                    ];
                ?>
                  <div class="col-md-6">
                    <?php include APPROOT . '/views/inc/components/select_field.php'; ?>
                  </div>
                <?php
                } elseif (isset($field['fieldType']) && $field['fieldType'] === 'textarea') {
                    // Textarea field
                    $textareaConfig = [
                        'id' => $field['id'],
                        'name' => $field['id'],
                        'label' => $field['label'],
                        'value' => $field['value'],
                        'required' => $field['required'] ?? false,
                        'editable' => $field['editable'] ?? true,
                        'wrapperClass' => 'mb-3',
                        'rows' => 3
                    ];
                    
                    if (!empty($field['summaryTarget'])) {
                        $textareaConfig['textareaClass'] = 'update-summary';
                    }
                ?>
                  <div class="col-md-6">
                    <?php include APPROOT . '/views/inc/components/textarea_field.php'; ?>
                  </div>
                <?php
                } else {
                    // Regular input field
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
          <!-- <div class="d-flex flex-column rounded-full align-items-center mb-4"> -->
            <!-- <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:140px;height:140px;background-color:#f3f4f6;overflow:hidden;flex-shrink:0;" id="profile-avatar"> -->
              <!-- </div> -->
              <!-- <input type="file" id="avatar-upload" accept="image/*" hidden> -->
            <!-- <label for="avatar-upload" class="btn btn-sm btn-outline-primary cursor-pointer">
              <i class="fas fa-camera me-1"></i> Change Logo
            </label> -->
          <!-- </div> -->
          
          <!-- Profile Info -->
          <div class="text-center">
            <img src="<?php echo htmlspecialchars(getAvatarUrl('SolarTech Solutions Ltd.', 140)); ?>" alt="Profile" style="object-fit:cover;">
            <h5 class="mb-1 fw-bold" id="summary-name"><?php echo htmlspecialchars($profile_data->full_name); ?></h5>
            <p class="text-muted small mb-1" id="summary-email"><?php echo htmlspecialchars($profile_data->email); ?></p>
            <p class="text-muted small mb-1" id="summary-location"><?php echo htmlspecialchars($profile_data->address); ?></p>
            <p class="text-muted small mb-1" id="summary-phone"><?php echo htmlspecialchars($profile_data->phone_number); ?></p>
          </div>

          <!-- Divider -->
          <hr>

          <!-- Business Stats -->
          <div class="row text-center mb-3">
            <div class="col-6">
              <div>
                <h6 class="fw-bold mb-1">500+</h6>
                <small class="text-muted">Installations</small>
              </div>
            </div>
            <div class="col-6">
              <div>
                <h6 class="fw-bold mb-1">4.8/5.0</h6>
                <small class="text-muted">Rating</small>
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
    'company-name': 'summary-name',
    'business-email': 'summary-email',
    'contact-number': 'summary-phone',
    'business-address': 'summary-location',
    'website': 'summary-website',
    'district': 'summary-district',
    'register_date': 'summary-register-date'
  };
  
  if (summaryMap[id]) {
    input.addEventListener('input', function() {
      const target = document.getElementById(summaryMap[id]);
      if (target) {
        if (id === 'established-date') {
          target.textContent = `Since ${this.value}`;
        } else {
          target.textContent = this.value;
        }
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



