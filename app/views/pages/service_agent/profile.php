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

  $all_districts = [
    "Ampara",
    "Anuradhapura",
    "Badulla",
    "Batticaloa",
    "Colombo",
    "Galle",
    "Gampaha",
    "Hambantota",
    "Jaffna",
    "Kalutara",
    "Kandy",
    "Kegalle",
    "Kilinochchi",
    "Kurunegala",
    "Mannar",
    "Matale",
    "Matara",
    "Monaragala",
    "Mullaitivu",
    "Nuwara Eliya",
    "Polonnaruwa",
    "Puttalam",
    "Ratnapura",
    "Trincomalee",
    "Vavuniya"
  ];

  $statusOptions = ["Active", "Inactive"];
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
              <?php $inputConfig = ['id' => 'contactNumber', 'name' => 'contactNumber', 'label' => 'Contact Number', 'type' => 'tel', 'icon' => 'fas fa-phone', 'value' => $profile_data['contact'] ?? '', 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'physicalAddress', 'name' => 'physicalAddress', 'label' => 'Physical Address', 'type' => 'text', 'icon' => 'fas fa-map-marker-alt', 'value' => $data['address'] ?? '', 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-12 form-group">
              <?php
              $districtOptions = array_combine($all_districts, $all_districts);
              $selectConfig = [
                'id' => 'district',
                'name' => 'district',
                'label' => 'District',
                'options' => $districtOptions,
                'value' => $profile_data['district'] ?? '',
                'icon' => 'fas fa-map-marker-alt',
                'required' => true,
                'error' => $data['district_err'] ?? '',
                'placeholder' => 'Select a District'
              ];
              require APPROOT . '/views/inc/components/select_field.php';
              ?>
            </div>
          </div>
        </div>
        <!-- Companies Details -->
        <div class="form-section mb-10">
          <h3 class="text-lg font-semibold mb-6"><i class="fas fa-user text-primary mr-2"></i>Company Details</h3>
          <div class="row">
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'agent-id', 'name' => 'agent-id', 'label' => 'Agent ID', 'type' => 'text', 'icon' => 'fas fa-user', 'value' => $profile_data['user_id'] ?? '', 'required' => true, 'editable'=>false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'company-name', 'name' => 'company-name', 'label' => 'Company Name', 'type' => 'text', 'icon' => 'fas fa-envelope', 'value' => $profile_data['company_name'] ?? '','editable'=>false , 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'register-date', 'name' => 'register-date', 'label' => 'Register Date', 'type' => 'text', 'icon' => 'fas fa-envelope', 'value' => $profile_data['register_date'] ?? '', 'editable'=>false, 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'specialization', 'name' => 'specialization', 'label' => 'Specialization', 'type' => 'text', 'icon' => 'fas fa-envelope', 'value' => $profile_data['specialization'] ?? '', 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-12 form-group">
              <?php
              $selectConfig = [
                'id' => 'status',
                'name' => 'status',
                'label' => 'Status',
                'options' => $statusOptions,
                'value' => $profile_data['status'] ?? '',
                'icon' => 'fas fa-map-marker-alt',
                'required' => true,
                'placeholder' => 'Change Status'
              ];
              require APPROOT . '/views/inc/components/select_field.php';
              ?>
            </div>
          </div>
        </div>
        <!-- Submit Button -->
        <div class="mt-8 pt-5 border-t d-flex justify-end">
          <button type="submit" class="btn btn-primary">
            <i class="button"></i> Update Profile
          </button>
        </div>
      </form>
    </div>
  </div>