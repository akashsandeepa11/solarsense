

<div class="container-fluid p-8">
  <!-- Page Header -->
  <?php
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
                      'value' => 'SolarTech Solutions Ltd.',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-name'
                  ],
                  [
                      'id' => 'business-email',
                      'label' => 'Business Email',
                      'value' => 'info@solartech.lk',
                      'type' => 'email',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-email'
                  ],
                  [
                      'id' => 'contact-number',
                      'label' => 'Contact Number',
                      'value' => '+94 112 345 678',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-phone'
                  ],
                  [
                      'id' => 'business-address',
                      'label' => 'Business Address',
                      'value' => '123 Green Energy Park, Colombo 05, Sri Lanka',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-location'
                  ],
                  [
                      'id' => 'registration-number',
                      'label' => 'Business Registration No.',
                      'value' => 'BRG12345678',
                      'editable' => false
                  ]
              ]
          ],
          [
              'title' => 'Business Details',
              'fields' => [
                  [
                      'id' => 'established-date',
                      'label' => 'Established Date',
                      'value' => '2010',
                      'type' => 'number',
                      'editable' => false
                  ],
                  [
                      'id' => 'employee-count',
                      'label' => 'Number of Employees',
                      'value' => '50+',
                      'editable' => true
                  ],
                  [
                      'id' => 'service-areas',
                      'label' => 'Service Areas',
                      'value' => 'Western Province, Southern Province',
                      'editable' => true,
                      'fieldType' => 'textarea'
                  ],
                  [
                      'id' => 'certification',
                      'label' => 'Certifications',
                      'value' => 'ISO 9001:2015, SEA Certified',
                      'editable' => false
                  ],
                  [
                      'id' => 'specialization',
                      'label' => 'Specialization',
                      'value' => 'Commercial Solar Installations',
                      'editable' => true,
                      'fieldType' => 'select',
                      'options' => [
                          'Residential Solar' => 'Residential Solar Installations',
                          'Commercial Solar' => 'Commercial Solar Installations',
                          'Industrial Solar' => 'Industrial Solar Installations',
                          'Hybrid Systems' => 'Hybrid Systems',
                          'All Services' => 'All Services'
                      ]
                  ],
                  [
                      'id' => 'website',
                      'label' => 'Website',
                      'value' => 'www.solartech.lk',
                      'type' => 'url',
                      'editable' => true
                  ]
              ]
          ],
          [
              'title' => 'Performance Metrics',
              'fields' => [
                  [
                      'id' => 'installations-completed',
                      'label' => 'Total Installations',
                      'value' => '500+',
                      'editable' => false
                  ],
                  [
                      'id' => 'avg-rating',
                      'label' => 'Average Rating',
                      'value' => '4.8/5.0',
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
            <h5 class="mb-1 fw-bold" id="summary-name">SolarTech Solutions Ltd.</h5>
            <p class="text-primary small mb-1" id="registration-display">BRG12345678</p>
            <p class="text-muted small mb-1" id="summary-email">info@solartech.lk</p>
            <p class="text-muted small mb-1" id="summary-location">123 Green Energy Park, Colombo 05, Sri Lanka</p>
            <p class="text-muted small mb-1" id="summary-phone">+94 112 345 678</p>
            <p class="text-muted small mb-3" id="certification-display">ISO 9001:2015, SEA Certified</p>
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

          <div class="p-3 bg-light rounded">
            <div class="mb-2">
              <small class="text-muted">Service Areas</small>
              <p class="text-primary small mb-0" id="service-areas-display">Western Province, Southern Province</p>
            </div>
            <div>
              <small class="text-muted">Experience</small>
              <p class="text-primary small mb-0" id="experience-display">Since 2010</p>
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
    'registration-number': 'registration-display',
    'certification': 'certification-display',
    'service-areas': 'service-areas-display',
    'established-date': 'experience-display'
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



