<?php
    // --- PHP LOGIC FOR THE FORM ---

    // Determine manager type from URL or data
    $managerType = $data['managerType'] ?? 'operation_managers';
    $isOperationManager = $managerType === 'operation_managers';
    $isInventoryManager = $managerType === 'inventory_managers';

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
    $managerId = $data['managerId'] ?? '';
    
    $managerTitle = $isOperationManager ? 'Operation Manager' : 'Inventory Manager';
    $pageTitle = $isEditMode ? "Edit $managerTitle" : "Add New $managerTitle";
    $pageDescription = $isEditMode ? 'Update manager information' : 'Fill all required fields to register a new manager';
    $buttonText = $isEditMode ? 'Update Manager' : 'Create Manager';
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
        'show_back' => true,
        'back_url' => URLROOT . '/installeradmin/managers/' . $managerType,
        'back_label' => 'Back to Managers'
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Main Form Card -->
    <div class="card shadow-lg rounded-xl">
        <div class="card-body p-10">

            <form id="manager-form" action="<?php echo URLROOT?>/installeradmin/managers/<?php echo $managerType . '/' . ($isEditMode ? 'edit/' . $managerId : 'add'); ?>" method="post" novalidate>
                
                <!-- Hidden fields for form mode and manager ID -->
                <input type="hidden" name="mode" value="<?php echo $mode; ?>">
                <input type="hidden" name="managerType" value="<?php echo $managerType; ?>">
                <?php if($isEditMode): ?>
                    <input type="hidden" name="managerId" value="<?php echo $managerId; ?>">
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
                            <?php $inputConfig = ['id' => 'address', 'name' => 'address', 'label' => 'Address', 'type' => 'text', 'icon' => 'fas fa-map-marker-alt', 'value' => $data['address'] ?? '', 'error' => $data['address_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
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
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'joinDate', 'name' => 'joinDate', 'label' => 'Join Date', 'type' => 'date', 'icon' => 'fas fa-calendar-alt', 'value' => $data['joinDate'] ?? '', 'error' => $data['joinDate_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
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

                <?php if($isOperationManager): ?>
                <!-- Operation Manager Specific Details -->
                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-cogs text-primary mr-2"></i>Operation Manager Details</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php 
                            $specializationOptions = [
                                'Installation' => 'Installation',
                                'Maintenance' => 'Maintenance',
                                'Troubleshooting' => 'Troubleshooting',
                                'Inspection' => 'Inspection',
                                'All Operations' => 'All Operations'
                            ];
                            $selectConfig = [
                                'id' => 'specialization',
                                'name' => 'specialization',
                                'label' => 'Specialization',
                                'options' => $specializationOptions,
                                'value' => $data['specialization'] ?? '',
                                'icon' => 'fas fa-tools',
                                'required' => true,
                                'error' => $data['specialization_err'] ?? '',
                                'placeholder' => 'Select specialization'
                            ];
                            require APPROOT . '/views/inc/components/select_field.php';
                            ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php 
                            $experienceLevelOptions = [
                                'Junior' => 'Junior (0-2 years)',
                                'Mid-Level' => 'Mid-Level (2-5 years)',
                                'Senior' => 'Senior (5-10 years)',
                                'Expert' => 'Expert (10+ years)'
                            ];
                            $selectConfig = [
                                'id' => 'experienceLevel',
                                'name' => 'experienceLevel',
                                'label' => 'Experience Level',
                                'options' => $experienceLevelOptions,
                                'value' => $data['experienceLevel'] ?? '',
                                'icon' => 'fas fa-award',
                                'required' => true,
                                'error' => $data['experienceLevel_err'] ?? '',
                                'placeholder' => 'Select experience level'
                            ];
                            require APPROOT . '/views/inc/components/select_field.php';
                            ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'teamSize', 'name' => 'teamSize', 'label' => 'Team Size', 'type' => 'number', 'icon' => 'fas fa-users', 'value' => $data['teamSize'] ?? '', 'error' => $data['teamSize_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php 
                            $statusOptions = [
                                'Active' => 'Active',
                                'On Leave' => 'On Leave',
                                'Away' => 'Away',
                                'Inactive' => 'Inactive'
                            ];
                            $selectConfig = [
                                'id' => 'status',
                                'name' => 'status',
                                'label' => 'Status',
                                'options' => $statusOptions,
                                'value' => $data['status'] ?? 'Active',
                                'icon' => 'fas fa-circle',
                                'required' => true,
                                'error' => $data['status_err'] ?? '',
                                'placeholder' => 'Select status'
                            ];
                            require APPROOT . '/views/inc/components/select_field.php';
                            ?>
                        </div>
                        <div class="col-md-12 form-group">
                            <?php $inputConfig = ['id' => 'certifications', 'name' => 'certifications', 'label' => 'Certifications (Optional)', 'type' => 'text', 'icon' => 'fas fa-certificate', 'value' => $data['certifications'] ?? '', 'error' => $data['certifications_err'] ?? '', 'required' => false]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                            <small class="text-secondary">Separate multiple certifications with commas</small>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($isInventoryManager): ?>
                <!-- Inventory Manager Specific Details -->
                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-warehouse text-primary mr-2"></i>Inventory Manager Details</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'warehouseLocation', 'name' => 'warehouseLocation', 'label' => 'Warehouse Location', 'type' => 'text', 'icon' => 'fas fa-warehouse', 'value' => $data['warehouseLocation'] ?? '', 'error' => $data['warehouseLocation_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'warehouseCapacity', 'name' => 'warehouseCapacity', 'label' => 'Warehouse Capacity (sq ft)', 'type' => 'number', 'icon' => 'fas fa-ruler-combined', 'value' => $data['warehouseCapacity'] ?? '', 'error' => $data['warehouseCapacity_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php 
                            $experienceLevelOptions = [
                                'Junior' => 'Junior (0-2 years)',
                                'Mid-Level' => 'Mid-Level (2-5 years)',
                                'Senior' => 'Senior (5-10 years)',
                                'Expert' => 'Expert (10+ years)'
                            ];
                            $selectConfig = [
                                'id' => 'experienceLevel',
                                'name' => 'experienceLevel',
                                'label' => 'Experience Level',
                                'options' => $experienceLevelOptions,
                                'value' => $data['experienceLevel'] ?? '',
                                'icon' => 'fas fa-award',
                                'required' => true,
                                'error' => $data['experienceLevel_err'] ?? '',
                                'placeholder' => 'Select experience level'
                            ];
                            require APPROOT . '/views/inc/components/select_field.php';
                            ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php 
                            $statusOptions = [
                                'Active' => 'Active',
                                'On Leave' => 'On Leave',
                                'Away' => 'Away',
                                'Inactive' => 'Inactive'
                            ];
                            $selectConfig = [
                                'id' => 'status',
                                'name' => 'status',
                                'label' => 'Status',
                                'options' => $statusOptions,
                                'value' => $data['status'] ?? 'Active',
                                'icon' => 'fas fa-circle',
                                'required' => true,
                                'error' => $data['status_err'] ?? '',
                                'placeholder' => 'Select status'
                            ];
                            require APPROOT . '/views/inc/components/select_field.php';
                            ?>
                        </div>
                        <div class="col-md-12 form-group">
                            <?php $inputConfig = ['id' => 'managedCategories', 'name' => 'managedCategories', 'label' => 'Managed Categories (Optional)', 'type' => 'text', 'icon' => 'fas fa-boxes', 'value' => $data['managedCategories'] ?? '', 'error' => $data['managedCategories_err'] ?? '', 'required' => false]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                            <small class="text-secondary">E.g., Solar Panels, Inverters, Batteries, etc. (Separate with commas)</small>
                        </div>
                        <div class="col-md-12 form-group">
                            <?php $inputConfig = ['id' => 'certifications', 'name' => 'certifications', 'label' => 'Certifications (Optional)', 'type' => 'text', 'icon' => 'fas fa-certificate', 'value' => $data['certifications'] ?? '', 'error' => $data['certifications_err'] ?? '', 'required' => false]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                            <small class="text-secondary">Separate multiple certifications with commas</small>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Emergency Contact Section -->
                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-phone-square-alt text-primary mr-2"></i>Emergency Contact (Optional)</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'emergencyContactName', 'name' => 'emergencyContactName', 'label' => 'Emergency Contact Name', 'type' => 'text', 'icon' => 'fas fa-user', 'value' => $data['emergencyContactName'] ?? '', 'error' => $data['emergencyContactName_err'] ?? '', 'required' => false]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'emergencyContactNumber', 'name' => 'emergencyContactNumber', 'label' => 'Emergency Contact Number', 'type' => 'tel', 'icon' => 'fas fa-phone', 'value' => $data['emergencyContactNumber'] ?? '', 'error' => $data['emergencyContactNumber_err'] ?? '', 'required' => false]; require APPROOT . '/views/inc/components/input_field.php'; ?>
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
