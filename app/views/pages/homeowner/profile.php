

<div class="container my-6">
  <div class="row">
    <!-- Left Column -->
    <div class="col-8">
      <?php
      // One array for all profile fields, grouped by section
      $profileSections = [
          [
              'title' => 'Personal Details',
              'fields' => [
                  [
                      'id' => 'full-name',
                      'label' => 'Full Name',
                      'value' => 'Nadith Nemal',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-name'
                  ],
                  [
                      'id' => 'email',
                      'label' => 'Email',
                      'value' => 'nadithnemal2002@gmail.com',
                      'type' => 'email',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-email'
                  ],
                  [
                      'id' => 'phone',
                      'label' => 'Phone number',
                      'value' => '+54 548 654 65',
                      'editable' => true,
                      'required' => false,
                      'summaryTarget' => 'summary-phone'
                  ],
                  [
                      'id' => 'address',
                      'label' => 'Address',
                      'value' => 'No. 47, Lakeview Lane, Colombo 07, Sri Lanka',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-location'
                  ],
                  [
                      'id' => 'district',
                      'label' => 'District',
                      'value' => 'Colombo',
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
                      'value' => '5 kWp',
                      'editable' => false
                  ],
                  [
                      'id' => 'panel-tilt',
                      'label' => 'Panel Tilt',
                      'value' => '30°',
                      'editable' => false
                  ],
                  [
                      'id' => 'panel-azimuth',
                      'label' => 'Panel Azimuth',
                      'value' => 'North',
                      'editable' => false
                  ],
                  [
                      'id' => 'installation-date',
                      'label' => 'Installation Date',
                      'value' => '01/05/2017',
                      'editable' => false
                  ],
                  [
                      'id' => 'panel-brand',
                      'label' => 'Panel Brand',
                      'value' => 'SunPower',
                      'editable' => false
                  ],
                  [
                      'id' => 'inverter-brand',
                      'label' => 'Inverter Brand',
                      'value' => 'SMA',
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
                      'value' => '123456VC',
                      'editable' => false
                  ],
                  [
                      'id' => 'provider',
                      'label' => 'Provider',
                      'value' => 'Ceylon Electricity Board',
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
          <h4 class="mb-1" id="summary-name">Nadith Nemal</h4>
          <p class="text-secondary mb-1" id="summary-email">nadithnemal2002@gmail.com</p>
          <p class="text-secondary mb-1" id="summary-location">No. 47, Lakeview Lane, Colombo 07, Sri Lanka</p>
          <p class="mb-3" id="summary-phone">+54 548 654 65</p>

           <!-- Stats -->
          <div class="d-flex align-center justify-center mt-4">
            <div class="text-center">
              <h5 class="mb-1">5 kWp</h5>
              <p class="text-secondary m-0">System Capacity</p>
            </div>

            <div class="mx-4" style="width:1px;height:70px;background:rgba(0,0,0,0.1)"></div>

            <div class="text-center">
              <h5 class="mb-1">123456VC</h5>
              <p class="text-secondary m-0">CEB Account</p>
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
      
      if (summaryTarget) {
        document.getElementById(summaryTarget).textContent = value;
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