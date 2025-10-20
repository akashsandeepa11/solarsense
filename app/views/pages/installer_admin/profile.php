

<div class="container my-6">
  <div class="row">
    <!-- Left Column -->
    <div class="col-8">
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
                      'editable' => true
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
                      'editable' => true
                  ],
                  [
                      'id' => 'website',
                      'label' => 'Website',
                      'value' => 'www.solartech.lk',
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
      <div class="card mb-4 p-2" >
        <h5 class="card-title px-4 pt-4"><?php echo htmlspecialchars($section['title']); ?></h5>
        <div class="card-body">
            <?php
            // Render fields for this section
            foreach ($section['fields'] as $field) {
                require APPROOT . '/views/inc/components/profile_input_field.php';
            }
            ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Right Column -->
    <div class="col-4">
      <div class="card text-center">
        <div class="card-body">
          <!-- Avatar Upload -->
          <div class="d-flex flex-column align-center mb-4">
            <div class="rounded-full bg-secondary mx-auto" style="width:120px;height:120px;background-size:cover;background-position:center" id="profile-avatar"></div>
            <input type="file" id="avatar-upload" accept="image/*" hidden>
            <label for="avatar-upload" class="text-primary mt-2 cursor-pointer">Change</label>
          </div>

          <!-- Profile Info -->
          <h4 class="mb-1" id="summary-name">SolarTech Solutions Ltd.</h4>
          <p class="text-primary mb-1" id="registration-display">BRG12345678</p>
          <p class="text-secondary mb-1" id="summary-email">info@solartech.lk</p>
          <p class="text-secondary mb-1" id="summary-location">123 Green Energy Park, Colombo 05, Sri Lanka</p>
          <p class="mb-1" id="summary-phone">+94 112 345 678</p>
          <p class="text-secondary mb-3" id="certification-display">ISO 9001:2015, SEA Certified</p>

          <!-- Business Stats -->
          <div class="d-flex flex-wrap justify-center mt-4">
            <div class="text-center px-3">
              <h5 class="mb-1">500+</h5>
              <p class="text-secondary m-0">Installations</p>
            </div>

            <div class="mx-3" style="width:1px;height:70px;background:rgba(0,0,0,0.1)"></div>

            <div class="text-center px-3">
              <h5 class="mb-1">4.8/5.0</h5>
              <p class="text-secondary m-0">Rating</p>
            </div>

            <div class="w-100 mt-3 pt-3 border-top">
              <div class="text-center mb-2">
                <p class="text-secondary m-0">Service Areas</p>
                <small class="text-primary">Western Province, Southern Province</small>
              </div>
              <div class="text-center">
                <p class="text-secondary m-0">Experience</p>
                <small class="text-primary">Since 2010</small>
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Select all edit buttons
const buttons = document.querySelectorAll('.edit-btn'); 

buttons.forEach(button => {
  button.addEventListener('click', () => {
    const inputs = document.querySelectorAll('.form-control'); 
    const selectedInput = button.parentElement.querySelector('.form-control'); 

    // Lock all other inputs
    inputs.forEach(input => {
      if (input !== selectedInput) {
        input.setAttribute('readonly', true);
        input.style.border = 'none';
      }
    });

    // Enable the clicked input
    selectedInput.removeAttribute('readonly');  
    selectedInput.style.border = '1px solid var(--color-primary)'; 
    selectedInput.focus();                       

    // Update right profile card in real-time
    selectedInput.addEventListener('input', () => {
      const value = selectedInput.value;
      const summaryTarget = selectedInput.getAttribute('data-summary-target');
      const fieldId = selectedInput.id;
      
      // Update standard summary fields
      if (summaryTarget) {
        document.getElementById(summaryTarget).textContent = value;
      }
      
      // Update additional display fields
      if (fieldId === 'registration-number' && document.getElementById('registration-display')) {
        document.getElementById('registration-display').textContent = value;
      }
      if (fieldId === 'certification' && document.getElementById('certification-display')) {
        document.getElementById('certification-display').textContent = value;
      }
      if (fieldId === 'service-areas') {
        const serviceAreas = document.querySelector('.text-primary:first-of-type');
        if (serviceAreas) serviceAreas.textContent = value;
      }
      if (fieldId === 'established-date') {
        const experience = document.querySelector('.text-primary:last-of-type');
        if (experience) experience.textContent = `Since ${value}`;
      }
    });
  });
});

const avatarUpload = document.getElementById('avatar-upload');
const profileAvatar = document.getElementById('profile-avatar');

avatarUpload.addEventListener('change', function () {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      profileAvatar.style.backgroundImage = `url(${e.target.result})`;
    };
    reader.readAsDataURL(file);
  }
});
</script>