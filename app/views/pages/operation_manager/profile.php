<div class="container-fluid p-8">
  <?php
  $profile_data = $data['profileData'];
  $config = [
    'title' => 'Company Profile',
    'description' => 'Manage your company information and settings'
  ];
  include __DIR__ . '/../../inc/components/page_header.php';

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
  ?>

  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/add_customer_form.css">

  <div class="card shadow-lg rounded-xl">
    <div class="card-body p-10">

      <form id="profile" action="<?php echo URLROOT ?>/operationmanager/update_profile/" method="post" novalidate>
        <div class="form-section mb-10">
          <h3 class="text-lg font-semibold mb-6"><i class="fas fa-user text-primary mr-2"></i>Personal & Contact Details
          </h3>
          <div class="row">
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'fullName', 'name' => 'fullName', 'label' => 'Full Name', 'type' => 'text', 'icon' => 'fas fa-user', 'value' => $profile_data['full_name'] ?? '', 'required' => true, 'editable' => false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'email', 'name' => 'email', 'label' => 'Email Address', 'type' => 'email', 'icon' => 'fas fa-envelope', 'value' => $profile_data['email'] ?? '', 'editable' => false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'physicalAddress', 'name' => 'physicalAddress', 'label' => 'Company Address', 'type' => 'text', 'icon' => 'fas fa-map-marker-alt', 'value' => $profile_data['address'] ?? '', 'required' => true];
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
                'placeholder' => 'Select a District'
              ];
              require APPROOT . '/views/inc/components/select_field.php';
              ?>
            </div>
          </div>
        </div>

        <div class="form-section mb-10">
          <h3 class="text-lg font-semibold mb-6"><i class="fas fa-building text-primary mr-2"></i>Company Details</h3>
          <div class="row">
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'user-id', 'name' => 'user-id', 'label' => 'Admin ID', 'type' => 'text', 'icon' => 'fas fa-id-badge', 'value' => $data['user_id'] ?? '', 'editable' => false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'company-name', 'name' => 'company-name', 'label' => 'Company Name', 'type' => 'text', 'icon' => 'fas fa-industry', 'value' => $profile_data['company_name'] ?? '', 'editable' => false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'register-date', 'name' => 'register-date', 'label' => 'Registration Date', 'type' => 'text', 'icon' => 'fas fa-calendar-alt', 'value' => $profile_data['register_date'] ?? '', 'editable' => false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'website', 'name' => 'website', 'label' => 'Company Website', 'type' => 'text', 'icon' => 'fas fa-globe', 'value' => $profile_data['website'] ?? '', 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
          </div>
        </div>

        <div class="mt-8 pt-5 border-t d-flex justify-end">
          <button type="submit" class="btn btn-primary">
            Update Company Profile
          </button>
        </div>
      </form>
    </div>
  </div>
</div>