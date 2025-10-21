<?php
    // --- PHP LOGIC FOR THE FORM ---

    // Handle potential POST data to repopulate the form if there's an error
    $fullName = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $contactNumber = $_POST['contactNumber'] ?? '';
    $address = $_POST['address'] ?? '';
    $district = $_POST['district'] ?? '';
    $specialization = $_POST['specialization'] ?? '';
    $experienceYears = $_POST['experienceYears'] ?? '';
    $availability = $_POST['availability'] ?? '';
    $certifications = $_POST['certifications'] ?? '';

    // List of districts for the dropdown
    $all_districts = [
        "Ampara", "Anuradhapura", "Badulla", "Batticaloa", "Colombo", "Galle", "Gampaha",
        "Hambantota", "Jaffna", "Kalutara", "Kandy", "Kegalle", "Kilinochchi", "Kurunegala",
        "Mannar", "Matale", "Matara", "Monaragala", "Mullaitivu", "Nuwara Eliya",
        "Polonnaruwa", "Puttalam", "Ratnapura", "Trincomalee", "Vavuniya"
    ];

    // Specialization options
    $specializations = [
        "Solar Installation" => "Solar Installation",
        "System Maintenance" => "System Maintenance",
        "Troubleshooting" => "Troubleshooting",
        "Electrical Work" => "Electrical Work",
        "General Service" => "General Service"
    ];

    // Availability options
    $availabilities = [
        "Full-time" => "Full-time",
        "Part-time" => "Part-time",
        "Flexible" => "Flexible"
    ];

    // Determine if this is add or edit mode
    $mode = $data['mode'] ?? 'add';
    $isEditMode = $mode === 'edit';
    $agentId = $data['agentId'] ?? '';
    $pageTitle = $isEditMode ? 'Edit Service Agent' : 'Add New Service Agent';
    $pageDescription = $isEditMode ? 'Update service agent information' : 'Fill all required fields to register a new service agent';
    $buttonText = $isEditMode ? 'Update Service Agent' : 'Add Service Agent';
    $buttonIcon = $isEditMode ? 'fas fa-save' : 'fas fa-plus';
?>

<!-- Link to the custom CSS for this page -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/installer_admin/add_service_agent.css">

<div class="content-area">

    <!-- Main Form Card -->
    <div class="card shadow-lg rounded-xl">
        <div class="card-body p-10">
            
            <!-- Form Header -->
            <div class="form-header text-center mb-10">
                <h2 class="text-2xl font-bold"><?php echo $pageTitle; ?></h2>
                <p class="text-secondary mt-1"><?php echo $pageDescription; ?></p>
            </div>

            <form id="add-agent-form" action="<?php echo URLROOT?>/installeradmin/team/<?php echo $isEditMode ? 'edit_agent/' . $agentId : 'add_service_agent'; ?>" method="post" novalidate>
                
                <!-- Hidden fields for form mode and agent ID -->
                <input type="hidden" name="mode" value="<?php echo $mode; ?>">
                <?php if($isEditMode): ?>
                    <input type="hidden" name="agentId" value="<?php echo $agentId; ?>">
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
                    </div>
                </div>

                <!-- Login Credentials Section -->
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

                <!-- Professional Details Section -->
                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-briefcase text-primary mr-2"></i>Professional Details</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php 
                            $selectConfig = [
                                'id' => 'specialization',
                                'name' => 'specialization',
                                'label' => 'Specialization',
                                'options' => $specializations,
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
                            <?php $inputConfig = ['id' => 'experienceYears', 'name' => 'experienceYears', 'label' => 'Years of Experience', 'type' => 'number', 'icon' => 'fas fa-chart-line', 'value' => $data['experienceYears'] ?? '', 'error' => $data['experienceYears_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php 
                            $selectConfig = [
                                'id' => 'availability',
                                'name' => 'availability',
                                'label' => 'Availability',
                                'options' => $availabilities,
                                'value' => $data['availability'] ?? '',
                                'icon' => 'fas fa-clock',
                                'required' => true,
                                'error' => $data['availability_err'] ?? '',
                                'placeholder' => 'Select availability'
                            ];
                            require APPROOT . '/views/inc/components/select_field.php';
                            ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'certifications', 'name' => 'certifications', 'label' => 'Certifications', 'type' => 'text', 'icon' => 'fas fa-certificate', 'value' => $data['certifications'] ?? '', 'error' => $data['certifications_err'] ?? '', 'required' => false, 'placeholder' => 'e.g., IEC 61730, PV Certified']; require APPROOT . '/views/inc/components/input_field.php'; ?>
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
