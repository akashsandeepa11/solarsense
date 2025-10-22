<div class="container-fluid p-8">
  <!-- Page Header -->
  <?php
  $config = [
      'title' => 'Administrator Profile',
      'description' => 'Manage your account settings'
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
                      'value' => 'Admin User',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-name'
                  ],
                  [
                      'id' => 'email',
                      'label' => 'Email',
                      'value' => 'admin@solarsense.com',
                      'type' => 'email',
                      'editable' => true,
                      'required' => true,
                      'summaryTarget' => 'summary-email'
                  ],
                  [
                      'id' => 'phone',
                      'label' => 'Phone number',
                      'value' => '+94 112 345 680',
                      'editable' => true,
                      'required' => false,
                      'summaryTarget' => 'summary-phone'
                  ],
                  [
                      'id' => 'admin-id',
                      'label' => 'Admin ID',
                      'value' => 'ADMIN-2024-001',
                      'editable' => false
                  ]
              ]
          ],
          [
              'title' => 'System Settings',
              'fields' => [
                  [
                      'id' => 'role',
                      'label' => 'Role',
                      'value' => 'Super Administrator',
                      'editable' => false
                  ],
                  [
                      'id' => 'status',
                      'label' => 'Account Status',
                      'value' => 'Active',
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
            <img src="<?php echo htmlspecialchars(getAvatarUrl('Admin User', 140)); ?>" alt="Profile" style="object-fit:cover;">
            <h5 class="mb-1 fw-bold" id="summary-name">Admin User</h5>
            <p class="text-muted small mb-1" id="summary-email">admin@solarsense.com</p>
            <p class="text-muted small mb-3" id="summary-phone">+94 112 345 680</p>
          </div>

          <!-- Divider -->
          <hr>

          <!-- Status Info -->
          <div class="alert alert-info alert-sm text-center mb-0">
            <small><i class="fas fa-shield-alt me-2"></i> Super Administrator Account</small>
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