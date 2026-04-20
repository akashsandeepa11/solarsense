<div class="container-fluid p-8">
  <?php
  $config = [
    'title' => 'My Profile',
    'description' => 'Manage your personal information and warehouse details'
  ];
  include __DIR__ . '/../../inc/components/page_header.php';
  ?>

  <?php
  $profile_data = $data['profileData'];

  $all_districts = [
    "Ampara", "Anuradhapura", "Badulla", "Batticaloa", "Colombo", "Galle", "Gampaha",
    "Hambantota", "Jaffna", "Kalutara", "Kandy", "Kegalle", "Kilinochchi", "Kurunegala",
    "Mannar", "Matale", "Matara", "Monaragala", "Mullaitivu", "Nuwara Eliya",
    "Polonnaruwa", "Puttalam", "Ratnapura", "Trincomalee", "Vavuniya"
  ];

  $statusOptions = ["Active", "Inactive"];
  $expLevels = ["Junior", "Intermediate", "Senior", "Expert"];
  ?>

  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/add_customer_form.css">

  <div class="card shadow-lg rounded-xl">
    <div class="card-body p-10">

      <form id="profile" action="<?php echo URLROOT ?>/inventorymanager/update_profile/" method="post" novalidate>
        
        <div class="form-section mb-10">
          <h3 class="text-lg font-semibold mb-6"><i class="fas fa-user text-primary mr-2"></i>Personal & Contact Details</h3>
          <div class="row">
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'fullName', 'name' => 'fullName', 'label' => 'Full Name', 'type' => 'text', 'icon' => 'fas fa-user', 'value' => $profile_data['full_name'] ?? '', 'required' => true, 'editable'=>false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'email', 'name' => 'email', 'label' => 'Email Address (Username)', 'type' => 'email', 'icon' => 'fas fa-envelope', 'value' => $profile_data['email'] ?? '', 'editable'=>false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-4 form-group">
              <?php $inputConfig = ['id' => 'nic', 'name' => 'nic', 'label' => 'NIC Number', 'type' => 'text', 'icon' => 'fas fa-id-card', 'value' => $profile_data['nic'] ?? '', 'editable'=>false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-4 form-group">
              <?php $inputConfig = ['id' => 'contactNumber', 'name' => 'contactNumber', 'label' => 'Contact Number', 'type' => 'tel', 'icon' => 'fas fa-phone', 'value' => $profile_data['contact'] ?? '', 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-4 form-group">
              <?php
              $districtOptions = array_combine($all_districts, $all_districts);
              $selectConfig = [
                'id' => 'district', 'name' => 'district', 'label' => 'District',
                'options' => $districtOptions, 'value' => $profile_data['district'] ?? '',
                'icon' => 'fas fa-map-marker-alt', 'required' => true, 'placeholder' => 'Select District'
              ];
              require APPROOT . '/views/inc/components/select_field.php';
              ?>
            </div>
            <div class="col-md-12 form-group">
              <?php $inputConfig = ['id' => 'physicalAddress', 'name' => 'physicalAddress', 'label' => 'Physical Address', 'type' => 'text', 'icon' => 'fas fa-home', 'value' => $profile_data['address'] ?? '', 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
          </div>
        </div>

        <div class="form-section mb-10">
          <h3 class="text-lg font-semibold mb-6"><i class="fas fa-warehouse text-primary mr-2"></i>Warehouse & Professional Details</h3>
          <div class="row">
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'warehouse_location', 'name' => 'warehouse_location', 'label' => 'Warehouse Location', 'type' => 'text', 'icon' => 'fas fa-map-pin', 'value' => $profile_data['warehouse_location'] ?? '', 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'warehouse_capacity', 'name' => 'warehouse_capacity', 'label' => 'Warehouse Capacity', 'type' => 'text', 'icon' => 'fas fa-cubes', 'value' => $profile_data['warehouse_capacity'] ?? '', 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-4 form-group">
              <?php
              $expOptions = array_combine($expLevels, $expLevels);
              $selectConfig = [
                'id' => 'exp_level', 'name' => 'exp_level', 'label' => 'Experience Level',
                'options' => $expOptions, 'value' => $profile_data['exp_level'] ?? '',
                'icon' => 'fas fa-user-graduate', 'required' => true
              ];
              require APPROOT . '/views/inc/components/select_field.php';
              ?>
            </div>
            <div class="col-md-8 form-group">
              <?php $inputConfig = ['id' => 'managed_categories', 'name' => 'managed_categories', 'label' => 'Managed Categories', 'type' => 'text', 'icon' => 'fas fa-tags', 'value' => $profile_data['managed_categories'] ?? ''];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-12 form-group">
              <?php $inputConfig = ['id' => 'certifications', 'name' => 'certifications', 'label' => 'Certifications', 'type' => 'text', 'icon' => 'fas fa-certificate', 'value' => $profile_data['certifications'] ?? ''];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
          </div>
        </div>

        <div class="form-section mb-10">
          <h3 class="text-lg font-semibold mb-6"><i class="fas fa-exclamation-triangle text-primary mr-2"></i>Emergency Contact</h3>
          <div class="row">
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'emergency_name', 'name' => 'emergency_name', 'label' => 'Emergency Contact Name', 'type' => 'text', 'icon' => 'fas fa-user-shield', 'value' => $profile_data['emergency_name'] ?? '', 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'emergency_contact', 'name' => 'emergency_contact', 'label' => 'Emergency Contact Number', 'type' => 'tel', 'icon' => 'fas fa-phone-alt', 'value' => $profile_data['emergency_contact'] ?? '', 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
          </div>
        </div>

        <div class="form-section mb-10">
          <h3 class="text-lg font-semibold mb-6"><i class="fas fa-info-circle text-primary mr-2"></i>System Information</h3>
          <div class="row">
            <div class="col-md-4 form-group">
              <?php $inputConfig = ['id' => 'company-name', 'name' => 'company-name', 'label' => 'Company Name', 'type' => 'text', 'icon' => 'fas fa-building', 'value' => $profile_data['company_name'] ?? '','editable'=>false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-4 form-group">
              <?php $inputConfig = ['id' => 'register-date', 'name' => 'register-date', 'label' => 'Registered Date', 'type' => 'text', 'icon' => 'fas fa-calendar-alt', 'value' => $profile_data['register_date'] ?? '', 'editable'=>false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-4 form-group">
              <?php
              $selectConfig = [
                'id' => 'status', 'name' => 'status', 'label' => 'Account Status',
                'options' => array_combine($statusOptions, $statusOptions), 'value' => $profile_data['status'] ?? '',
                'icon' => 'fas fa-toggle-on', 'required' => true
              ];
              require APPROOT . '/views/inc/components/select_field.php';
              ?>
            </div>
          </div>
        </div>

        <div class="mt-8 pt-5 border-t d-flex justify-end">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-2"></i> Update Profile
          </button>
        </div>
      </form>
    </div>
  </div>
</div>