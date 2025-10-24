<div class="container-fluid p-8">
  <!-- Page Header -->
  <?php
  $config = [
      'title' => 'My Profile',
      'description' => 'Manage your personal information'
  ];
  include __DIR__ . '/../../inc/components/page_header.php';
  ?>

  <div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
      <?php
      $profileSections = [
          [
              'title' => 'Personal Details',
              'fields' => [
                  [
                      'id' => 'full-name',
                      'label' => 'Full Name',
                      'value' => 'Jane Operation Manager',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-name'
                  ],
                  [
                      'id' => 'email',
                      'label' => 'Email',
                      'value' => 'operations@solarsense.com',
                      'type' => 'email',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-email'
                  ],
                  [
                      'id' => 'phone',
                      'label' => 'Phone number',
                      'value' => '+94 112 345 679',
                      'editable' => true,
                      'required' => false,
                      'summaryTarget' => 'summary-phone'
                  ],
                  [
                      'id' => 'employee-id',
                      'label' => 'Employee ID',
                      'value' => 'OPS-2024-001',
                      'editable' => false
                  ]
              ]
          ]
      ];
      
      foreach ($profileSections as $section):
      ?>
      <div class="card mb-4">
        <div class="card-header bg-light border-bottom">
          <h5 class="mb-0"><?php echo htmlspecialchars($section['title']); ?></h5>
        </div>
        <div class="card-body">
          <div class="row">
            <?php
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
            <img src="<?php echo htmlspecialchars(getAvatarUrl('Jane Operation', 140)); ?>" alt="Profile" style="object-fit:cover;">

            <h5 class="mb-1 fw-bold" id="summary-name">Jane Operation Manager</h5>
            <p class="text-muted small mb-1" id="summary-email">operations@solarsense.com</p>
            <p class="text-muted small mb-3" id="summary-phone">+94 112 345 679</p>
          </div>

          <!-- Divider -->
          <hr>

          <!-- Department Info -->
          <div class="text-center">
            <p class="text-muted small mb-0">Operations Department</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('.update-summary').forEach(input => {
  const id = input.id;
  const summaryMap = {
    'full-name': 'summary-name',
    'email': 'summary-email',
    'phone': 'summary-phone'
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
      }
    };
    reader.readAsDataURL(file);
  }
});
</script>