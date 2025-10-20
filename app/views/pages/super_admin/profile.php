

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
                      'value' => 'SolarSense',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-name'
                  ],
                  [
                      'id' => 'email',
                      'label' => 'Email',
                      'value' => 'solarsense@gmail.com',
                      'type' => 'email',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-email'
                  ],
                  [
                      'id' => 'phone',
                      'label' => 'Phone number',
                      'value' => '+94 777 777 777 ',
                      'editable' => true,
                      'required' => false,
                      'summaryTarget' => 'summary-phone'
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
          <h4 class="mb-1" id="summary-name">SolarSense</h4>
          <p class="text-secondary mb-1" id="summary-email">solarsense@gmail.com</p>
          <p class="mb-3" id="summary-phone">+94 777 777 77</p>
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