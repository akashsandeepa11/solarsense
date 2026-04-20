<div class="container-fluid p-8">
  <!-- Page Header -->
  <?php
  $profile_data = $data['user_data'];
  $config = [
    'title' => 'Administrator Profile',
    'description' => 'Manage your account settings'
  ];
  include __DIR__ . '/../../inc/components/page_header.php';
  ?>

  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/add_customer_form.css">

  <!-- Main Form Card -->
  <div class="card shadow-lg rounded-xl">
    <div class="card-body p-10">

      <form id="profile" action="<?php echo URLROOT ?>/serviceagent/update_profile/" method="post" novalidate>
        <!-- Personal & Contact Details Section -->
        <div class="form-section mb-10">
          <h3 class="text-lg font-semibold mb-6"><i class="fas fa-user text-primary mr-2"></i>Personal & Contact Details
          </h3>
          <div class="row">
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'fullName', 'name' => 'fullName', 'label' => 'Full Name', 'type' => 'text', 'icon' => 'fas fa-user', 'value' => $profile_data['full_name'] ?? '', 'required' => true, 'editable'=>false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'email', 'name' => 'email', 'label' => 'Email Address (Username)', 'type' => 'email', 'icon' => 'fas fa-envelope', 'value' => $profile_data['email'] ?? '', 'editable'=>false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'userID', 'name' => 'userID', 'label' => 'userID', 'type' => 'text', 'icon' => 'fas fa-phone', 'value' => $data['user_id'] ?? '', 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>