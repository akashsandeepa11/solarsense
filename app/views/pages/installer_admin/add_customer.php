<?php
    // --- PHP LOGIC FOR THE FORM ---

    // Handle potential POST data to repopulate the form if there's an error
    $fullName = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $contactNumber = $_POST['contactNumber'] ?? '';
    $physicalAddress = $_POST['physicalAddress'] ?? '';
    $district = $_POST['district'] ?? '';
    $systemCapacity = $_POST['systemCapacity'] ?? '';
    $panelTilt = $_POST['panelTilt'] ?? '';
    $panelAzimuth = $_POST['panelAzimuth'] ?? '';
    $installationDate = $_POST['installationDate'] ?? '';
    $panelBrand = $_POST['panelBrand'] ?? '';
    $inverterBrand = $_POST['inverterBrand'] ?? '';
    $cebAccount = $_POST['cebAccount'] ?? '';

    // List of districts for the dropdown
    $all_districts = [
        "Ampara", "Anuradhapura", "Badulla", "Batticaloa", "Colombo", "Galle", "Gampaha",
        "Hambantota", "Jaffna", "Kalutara", "Kandy", "Kegalle", "Kilinochchi", "Kurunegala",
        "Mannar", "Matale", "Matara", "Monaragala", "Mullaitivu", "Nuwara Eliya",
        "Polonnaruwa", "Puttalam", "Ratnapura", "Trincomalee", "Vavuniya"
    ];

    // Determine if this is add or edit mode
    $mode = $data['mode'] ?? 'add';
    $isEditMode = $mode === 'edit';
    $customerId = $data['customerId'] ?? '';
    $pageTitle = $isEditMode ? 'Edit Homeowner' : 'Add New Homeowner';
    $pageDescription = $isEditMode ? 'Update homeowner information' : 'Fill all required fields to register a new homeowner';
    $buttonText = $isEditMode ? 'Update Account' : 'Create Account';
    $buttonIcon = $isEditMode ? 'fas fa-save' : 'fas fa-plus';
?>

<!-- Link to the custom CSS for this page -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/add_customer_form.css">

<div class="content-area">

    <!-- Page Header -->
    <?php
    $config = [
        'title' => $pageTitle,
        'description' => $pageDescription,
        'show_back' => $isEditMode,
        'back_url' => URLROOT . '/installeradmin/fleet',
        'back_label' => 'Back to Fleet'
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Main Form Card -->
    <div class="card shadow-lg rounded-xl">
        <div class="card-body p-10">

            <form id="registration-form" action="<?php echo URLROOT?>/installeradmin/fleet/<?php echo $isEditMode ? 'edit_customer/' . $customerId : 'add_customer'; ?>" method="post" novalidate>
                
                <!-- Hidden fields for form mode and customer ID -->
                <input type="hidden" name="mode" value="<?php echo $mode; ?>">
                <?php if($isEditMode): ?>
                    <input type="hidden" name="customerId" value="<?php echo $customerId; ?>">
                <?php endif; ?>
                
                <!-- Personal & Contact Details Section -->
                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-user text-primary mr-2"></i>Personal & Contact Details</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'fullName', 'name' => 'fullName', 'label' => 'Full Name', 'type' => 'text', 'icon' => 'fas fa-user', 'value' => $data['fullName'] ?? '', 'error' => $data['fullName_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'email', 'name' => 'email', 'label' => 'Email Address (Username)', 'type' => 'email', 'icon' => 'fas fa-envelope', 'value' => $data['email'] ?? '', 'error' => $data['email_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'contactNumber', 'name' => 'contactNumber', 'label' => 'Contact Number', 'type' => 'tel', 'icon' => 'fas fa-phone', 'value' => $data['contactNumber'] ?? '', 'error' => $data['contactNumber_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'nic', 'name' => 'nic', 'label' => 'NIC/ID Number', 'type' => 'text', 'icon' => 'fas fa-id-card', 'value' => $data['nic'] ?? '', 'error' => $data['nic_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-12 form-group">
                            <?php $inputConfig = ['id' => 'physicalAddress', 'name' => 'physicalAddress', 'label' => 'Physical Address', 'type' => 'text', 'icon' => 'fas fa-map-marker-alt', 'value' => $data['physicalAddress'] ?? '', 'error' => $data['physicalAddress_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-12 form-group">
                            <?php 
                            $districtOptions = array_combine($all_districts, $all_districts);
                            $selectConfig = [
                                'id' => 'district',
                                'name' => 'district',
                                'label' => 'District',
                                'options' => $districtOptions,
                                'value' => $data['district'] ?? '',
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

                <!-- Credentials Section -->
                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-lock text-primary mr-2"></i>Login Credentials <?php if($isEditMode): ?><span class="text-secondary text-sm">(Leave blank to keep current password)</span><?php endif; ?></h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'password', 'name' => 'password', 'label' => 'Password', 'type' => 'password', 'icon' => 'fas fa-lock', 'value' => $data['password'] ?? '', 'error' => $data['password_err'] ?? '', 'required' => !$isEditMode]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'confirmPassword', 'name' => 'confirmPassword', 'label' => 'Confirm Password', 'type' => 'password', 'icon' => 'fas fa-lock', 'value' => $data['confirmPassword'] ?? '', 'error' => $data['confirmPassword_err'] ?? '', 'required' => !$isEditMode]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                    </div>
                </div>

                <!-- Solar System Specifications Section -->
                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-solar-panel text-primary mr-2"></i>Solar System Specifications</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'systemCapacity', 'name' => 'systemCapacity', 'label' => 'System Capacity (kWp)', 'type' => 'number', 'icon' => 'fas fa-bolt', 'value' => $data['systemCapacity'] ?? '', 'error' => $data['systemCapacity_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'panelTilt', 'name' => 'panelTilt', 'label' => 'Panel Tilt (Degrees)', 'type' => 'number', 'icon' => 'fas fa-layer-group', 'value' => $data['panelTilt'] ?? '', 'error' => $data['panelTilt_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php 
                            $azimuthOptions = [
                                '180' => 'South (180°)',
                                '135' => 'South-West (135°)',
                                '225' => 'South-East (225°)',
                                '90' => 'East (90°)',
                                '270' => 'West (270°)',
                                '0' => 'North (0°)',
                                '45' => 'North-East (45°)',
                                '315' => 'North-West (315°)'
                            ];
                            $selectConfig = [
                                'id' => 'panelAzimuth',
                                'name' => 'panelAzimuth',
                                'label' => 'Panel Azimuth (Orientation)',
                                'options' => $azimuthOptions,
                                'value' => $data['panelAzimuth'] ?? '',
                                'icon' => 'fas fa-compass',
                                'required' => true,
                                'error' => $data['panelAzimuth_err'] ?? '',
                                'placeholder' => 'Select orientation'
                            ];
                            require APPROOT . '/views/inc/components/select_field.php';
                            ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'installationDate', 'name' => 'installationDate', 'label' => 'Installation Date', 'type' => 'date', 'icon' => 'fas fa-calendar-alt', 'value' => $data['installationDate'] ?? '', 'error' => $data['installationDate_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'panelBrand', 'name' => 'panelBrand', 'label' => 'Panel Brand & Model', 'type' => 'text', 'icon' => 'fas fa-tag', 'value' => $data['panelBrand'] ?? '', 'error' => $data['panelBrand_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'inverterBrand', 'name' => 'inverterBrand', 'label' => 'Inverter Brand & Model', 'type' => 'text', 'icon' => 'fas fa-microchip', 'value' => $data['inverterBrand'] ?? '', 'error' => $data['inverterBrand_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                    </div>
                </div>

                <!-- Utility Account Section -->
                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-file-invoice-dollar text-primary mr-2"></i>Utility Account Details</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'cebAccount', 'name' => 'cebAccount', 'label' => 'CEB Account Number', 'type' => 'text', 'icon' => 'fas fa-file-invoice-dollar', 'value' => $data['cebAccount'] ?? '', 'error' => $data['cebAccount_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group" style="display: flex; align-items: flex-end;">
                            <div class="form-control" style="border: 1px solid #ced4da; padding: 0.7rem 0.75rem; border-radius: 0.75rem; background: #f8f9fa;">
                                <strong>Utility Provider:</strong> Ceylon Electricity Board (CEB)
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 pt-5 border-t d-flex justify-end">
                    <button type="submit" class="btn <?php echo $isEditMode ? 'btn-warning' : 'btn-primary'; ?>">
                        <i class="<?php echo $buttonIcon; ?> mr-2"></i> <?php echo $buttonText; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
