<?php
    // --- PHP LOGIC FOR THE FORM ---

    // Handle potential POST data to repopulate the form if there's an error
    $companyName = $_POST['companyName'] ?? '';
    $email = $_POST['email'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $address = $_POST['address'] ?? '';
    // $district = $_POST['district'] ?? '';
    // $systemCapacity = $_POST['systemCapacity'] ?? '';
    // $panelTilt = $_POST['panelTilt'] ?? '';
    // $panelAzimuth = $_POST['panelAzimuth'] ?? '';
    // $installationDate = $_POST['installationDate'] ?? '';
    // $panelBrand = $_POST['panelBrand'] ?? '';
    // $inverterBrand = $_POST['inverterBrand'] ?? '';
    // $cebAccount = $_POST['cebAccount'] ?? '';

    // Determine if this is add or edit mode

    // $mode = $data['mode'] ?? 'add';
    // // $isEditMode = $mode === 'edit';
    // $customerId = $data['customerId'] ?? '';
    $pageTitle = 'Welcome to SolarSense!';
    $pageDescription = 'Please fill all required fields';
    // $buttonText = $isEditMode ? 'Update Account' : 'Create Account';
    // $buttonIcon = $isEditMode ? 'fas fa-save' : 'fas fa-plus';
?>

<!-- Link to the custom CSS for this page -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/add_customer_form.css">

<div class="content-area">

    <!-- Main Form Card -->
    <div class="card shadow-lg rounded-xl">
        <div class="card-body p-10">
            
            <!-- Form Header -->
            <div class="form-header text-center mb-10">
                <h2 class="text-2xl font-bold"><?php echo $pageTitle; ?></h2>
                <p class="text-secondary mt-1"><?php echo $pageDescription; ?></p>
            </div>

            <form id="prospectiveInstallerRequest-form" action="<?php echo URLROOT?>/superadmin/add_installer_verification" method="post" novalidate>
                
                <!-- Hidden fields for form mode and customer ID -->
                
                <!-- Personal & Contact Details Section -->
                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-user text-primary mr-2"></i>Personal & Contact Details</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'companyName', 'name' => 'companyName', 'label' => 'Full Name', 'type' => 'text', 'icon' => 'fas fa-user', 'value' => $data['companyName'] ?? '', 'error' => $data['companyName_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'email', 'name' => 'email', 'label' => 'Email Address (Username)', 'type' => 'email', 'icon' => 'fas fa-envelope', 'value' => $data['email'] ?? '', 'error' => $data['email_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'contact', 'name' => 'contact', 'label' => 'Contact Number', 'type' => 'tel', 'icon' => 'fas fa-phone', 'value' => $data['contact'] ?? '', 'error' => $data['contact_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-12 form-group">
                            <?php $inputConfig = ['id' => 'address', 'name' => 'address', 'label' => 'Physical Address', 'type' => 'text', 'icon' => 'fas fa-map-marker-alt', 'value' => $data['address'] ?? '', 'error' => $data['address_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 pt-5 border-t d-flex justify-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="<?php echo $buttonIcon; ?> mr-2"></i> <?php echo $buttonText; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
