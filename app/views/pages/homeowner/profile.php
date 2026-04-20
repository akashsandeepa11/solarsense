<div class="container-fluid p-8">
  <?php
  $user_data = $data['user_data'];
  $config = [
    'title' => 'My Profile',
    'description' => 'Manage your personal information and solar system specifications'
  ];
  include __DIR__ . '/../../inc/components/page_header.php';

  $all_districts = [
    "Ampara", "Anuradhapura", "Badulla", "Batticaloa", "Colombo", "Galle", "Gampaha",
    "Hambantota", "Jaffna", "Kalutara", "Kandy", "Kegalle", "Kilinochchi", "Kurunegala",
    "Mannar", "Matale", "Matara", "Monaragala", "Mullaitivu", "Nuwara Eliya",
    "Polonnaruwa", "Puttalam", "Ratnapura", "Trincomalee", "Vavuniya"
  ];
  ?>

  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/add_customer_form.css">

  <div class="card shadow-lg rounded-xl">
    <div class="card-body p-10">

      <form id="profile" action="<?php echo URLROOT ?>/homeowner/update_profile/" method="post" novalidate>
        
        <div class="form-section mb-10">
          <h3 class="text-lg font-semibold mb-6"><i class="fas fa-user text-primary mr-2"></i>Personal & Contact Details</h3>
          <div class="row">
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'full-name', 'name' => 'full-name', 'label' => 'Full Name', 'type' => 'text', 'icon' => 'fas fa-user', 'value' => $user_data->full_name ?? '', 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'email', 'name' => 'email', 'label' => 'Email Address', 'type' => 'email', 'icon' => 'fas fa-envelope', 'value' => $user_data->email ?? '', 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-4 form-group">
              <?php $inputConfig = ['id' => 'phone', 'name' => 'phone', 'label' => 'Contact Number', 'type' => 'tel', 'icon' => 'fas fa-phone', 'value' => $user_data->phone_number ?? '', 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>

            <div class="col-md-4 form-group">
              <?php
              $districtOptions = array_combine($all_districts, $all_districts);
              $selectConfig = [
                'id' => 'district', 'name' => 'district', 'label' => 'District',
                'options' => $districtOptions, 'value' => $user_data->district ?? '',
                'icon' => 'fas fa-map-marker-alt', 'required' => true, 'placeholder' => 'Select District'
              ];
              require APPROOT . '/views/inc/components/select_field.php';
              ?>
            </div>
            <div class="col-md-12 form-group">
              <?php $inputConfig = ['id' => 'address', 'name' => 'address', 'label' => 'Physical Address', 'type' => 'text', 'icon' => 'fas fa-home', 'value' => $user_data->address ?? '', 'required' => true];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
          </div>
        </div>

        <div class="form-section mb-10">
          <h3 class="text-lg font-semibold mb-6"><i class="fas fa-solar-panel text-primary mr-2"></i>Solar System Specifications</h3>
          <div class="row">
            <div class="col-md-4 form-group">
              <?php $inputConfig = ['id' => 'capacity', 'name' => 'capacity', 'label' => 'System Capacity (kWp)', 'type' => 'text', 'icon' => 'fas fa-bolt', 'value' => $user_data->system_capacity ?? 'N/A', 'editable' => false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-4 form-group">
              <?php $inputConfig = ['id' => 'tilt', 'name' => 'tilt', 'label' => 'Panel Tilt (°)', 'type' => 'text', 'icon' => 'fas fa-angle-double-up', 'value' => $user_data->system_tilt ?? 'N/A', 'editable' => false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-4 form-group">
              <?php $inputConfig = ['id' => 'azimuth', 'name' => 'azimuth', 'label' => 'Panel Azimuth (°)', 'type' => 'text', 'icon' => 'fas fa-compass', 'value' => $user_data->system_azimuth ?? 'N/A', 'editable' => false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'panel_brand', 'name' => 'panel_brand', 'label' => 'Panel Brand', 'type' => 'text', 'icon' => 'fas fa-industry', 'value' => $user_data->panel_brand ?? 'N/A', 'editable' => false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'inverter_brand', 'name' => 'inverter_brand', 'label' => 'Inverter Brand', 'type' => 'text', 'icon' => 'fas fa-microchip', 'value' => $user_data->inverter_brand ?? 'N/A', 'editable' => false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-12 form-group">
              <?php $inputConfig = ['id' => 'installation_date', 'name' => 'installation_date', 'label' => 'Installation Date', 'type' => 'text', 'icon' => 'fas fa-calendar-check', 'value' => $user_data->installation_date ?? 'N/A', 'editable' => false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
          </div>
        </div>

        <div class="form-section mb-10">
          <h3 class="text-lg font-semibold mb-6"><i class="fas fa-file-invoice-dollar text-primary mr-2"></i>Utility & Account Details</h3>
          <div class="row">
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'ceb_account', 'name' => 'ceb_account', 'label' => 'CEB Account Number', 'type' => 'text', 'icon' => 'fas fa-hashtag', 'value' => $user_data->ceb_account ?? 'N/A', 'editable' => false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
            </div>
            <div class="col-md-6 form-group">
              <?php $inputConfig = ['id' => 'provider', 'name' => 'provider', 'label' => 'Utility Provider', 'type' => 'text', 'icon' => 'fas fa-plug', 'value' => 'Ceylon Electricity Board', 'editable' => false];
              require APPROOT . '/views/inc/components/input_field.php'; ?>
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