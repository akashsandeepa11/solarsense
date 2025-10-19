<style>
.star {
  width: 20px;
  height: 20px;
  background: gold;
  clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 
                     79% 91%, 50% 70%, 21% 91%, 32% 57%, 
                     2% 35%, 39% 35%);
  display: inline-block;
}

.star.inactive {
  background: #ccc;
}
</style>

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
                      'value' => 'Alexa Rawles Rogdrigo',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-name'
                  ],
                  [
                      'id' => 'email',
                      'label' => 'Email',
                      'value' => 'alexarawles@gmail.com',
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
              'title' => 'Work Details',
              'fields' => [
                  [
                      'id' => 'agent-id',
                      'label' => 'Agent ID',
                      'value' => 'CMB23039D',
                      'editable' => false
                  ],
                  [
                      'id' => 'work-since',
                      'label' => 'Work Since',
                      'value' => '03/04/2020',
                      'editable' => false
                  ],
                  [
                      'id' => 'experience',
                      'label' => 'Experience',
                      'value' => '10 Years',
                      'editable' => false,
                      'summaryTarget' => 'summary-experience'
                  ],
                  [
                      'id' => 'total-works',
                      'label' => 'Total Works',
                      'value' => '46',
                      'editable' => false,
                      'summaryTarget' => 'summary-works'
                  ]
              ]
          ],
          [
              'title' => 'Social Media',
              'fields' => [
                  [
                      'id' => 'linkedin',
                      'label' => 'LinkedIn',
                      'value' => 'https://www.linkedin.com/in/nadith-nemal-profile/',
                      'type' => 'url',
                      'editable' => true,
                      'summaryTarget' => 'summary-linkedin-url'
                  ],
                  [
                      'id' => 'facebook',
                      'label' => 'Facebook',
                      'value' => 'https://www.facebook.com/johndoe',
                      'type' => 'url',
                      'editable' => true,
                      'summaryTarget' => 'summary-facebook-url'
                  ],
                  [
                      'id' => 'x',
                      'label' => 'X',
                      'value' => 'https://x.com/johndoe',
                      'type' => 'url',
                      'editable' => true,
                      'summaryTarget' => 'summary-x-url'
                  ]
              ]
          ]
      ];
      
      // Render all profile sections
      foreach ($profileSections as $section):
      ?>
      <div class="card mb-4 p-2">
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
          <h4 class="mb-1" id="summary-name">Alexa Rawles</h4>
          <p class="text-secondary mb-1" id="summary-email">alexarawles@gmail.com</p>
          <p class="text-secondary mb-1" id="summary-location">Colombo 07, Sri Lanka</p>
          <p class="mb-3" id="summary-phone">+54 548 654 65</p>

          <!-- Rating -->
          <div class="d-flex justify-center gap-2 mb-4">
            <span class="star"></span>
            <span class="star"></span>
            <span class="star"></span>
            <span class="star"></span>
            <span class="star inactive"></span>
          </div>
          
          <!-- Social -->
          <div class="d-flex justify-center gap-4 mb-4">
            <a href="https://www.linkedin.com/in/nadith-nemal-profile/" target="_blank" id="summary-linkedin" class="text-secondary">
              <i class="fab fa-linkedin fa-2x"></i>
            </a>
            <a href="https://www.facebook.com/johndoe" target="_blank" id="summary-facebook" class="text-secondary">
              <i class="fab fa-facebook fa-2x"></i>
            </a>
            <a href="https://x.com/johndoe" target="_blank" id="summary-x" class="text-secondary">
              <i class="fa-brands fa-x-twitter fa-2x"></i>
            </a>
          </div>

          <!-- Stats -->
          <div class="d-flex align-center justify-center mt-4">
            <div class="text-center">
              <h5 class="mb-1" id="summary-experience">10 Years</h5>
              <p class="text-secondary m-0">Experience</p>
            </div>

            <div class="mx-4" style="width:1px;height:70px;background:rgba(0,0,0,0.1)"></div>

            <div class="text-center">
              <h5 class="mb-1" id="summary-works">46</h5>
              <p class="text-secondary m-0">Total Works</p>
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
        // For social media URLs, update the href attribute
        if (summaryTarget.includes('url')) {
          const socialId = summaryTarget.replace('-url', '');
          document.getElementById(socialId).href = value;
        } else {
          // For regular fields, update the text content
          document.getElementById(summaryTarget).textContent = value;
        }
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
