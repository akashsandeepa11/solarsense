<?php
    // --- PHP LOGIC FOR THE FORM ---

    // List of districts for the dropdown
    $all_districts = [
        "Ampara", "Anuradhapura", "Badulla", "Batticaloa", "Colombo", "Galle", "Gampaha",
        "Hambantota", "Jaffna", "Kalutara", "Kandy", "Kegalle", "Kilinochchi", "Kurunegala",
        "Mannar", "Matale", "Matara", "Monaragala", "Mullaitivu", "Nuwara Eliya",
        "Polonnaruwa", "Puttalam", "Ratnapura", "Trincomalee", "Vavuniya"
    ];

    $pageTitle = 'Solar Installer Company Registration';
    $pageDescription = 'Join Sri Lanka\'s leading solar management platform - Register your installation company to access our comprehensive tools';
    $buttonText = 'Submit Registration Request';
    $buttonIcon = 'fas fa-paper-plane';
?>

<!-- Link to the custom CSS for this page -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/add_customer_form.css">

<style>
    body {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    .registration-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, #fe9630 0%, #d67000 85%, #c26300 100%);
        z-index: -1;
    }

    .registration-background::before,
    .registration-background::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .registration-background::before {
        width: 400px;
        height: 400px;
        bottom: -200px;
        left: -150px;
    }

    .registration-background::after {
        width: 400px;
        height: 400px;
        bottom: -150px;
        left: -200px;
    }

    .registration-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }

    .registration-content-wrapper {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
    }

    .content-area {
        width: 100%;
    }

    /* Make card more prominent on colored background */
    .card {
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
    }

    /* Add logo/branding at top */
    .registration-header {
        text-align: center;
        margin-bottom: 2rem;
        color: white;
    }

    .registration-header h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .registration-header p {
        font-size: 1.2rem;
        opacity: 0.95;
    }

    /* Make logo bigger */
    .registration-header .logo-component {
        transform: scale(1.5);
        margin-bottom: 2rem;
        display: inline-block;
    }

    /* Ensure logo text is white */
    .registration-header .logo-component .logo-text,
    .registration-header .logo-component a,
    .registration-header .logo-component span {
        color: white !important;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .registration-container {
            padding: 1rem;
        }

        .registration-header h1 {
            font-size: 2rem;
        }

        .registration-header p {
            font-size: 1rem;
        }

        .registration-header .logo-component {
            transform: scale(1.2);
            margin-bottom: 1.5rem;
        }
    }
</style>

<div class="registration-background"></div>

<div class="registration-container">
    <div class="registration-content-wrapper">
        <!-- Branding Header -->
        <div class="registration-header">
            <?php
                        $logoVariant = 'white';
                        $logoWrapperClass = 'logo-component logo-component--footer';
                        $logoLinkClass = 'd-flex align-center text-decoration-none hover:no-underline';
                        require APPROOT . '/views/inc/components/logo.php';
                        unset($logoVariant, $logoWrapperClass, $logoLinkClass);
                        ?>
            <p>Installer Company Registration Portal</p>
        </div>

        <div class="content-area">

    <!-- Main Form Card -->
    <div class="card shadow-lg rounded-xl">
        <div class="card-body p-10">
            
            <!-- Form Header -->
            <div class="form-header text-center mb-10">
                <h2 class="text-2xl font-bold"><?php echo $pageTitle; ?></h2>
                <p class="text-secondary mt-1"><?php echo $pageDescription; ?></p>
            </div>

            <form id="installerRequest-form" action="<?php echo URLROOT?>/superadmin/add_installer_verification" method="post" novalidate enctype="multipart/form-data">
                
                <!-- Company Information Section -->
                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-building text-primary mr-2"></i>Company Information</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'company_name', 'name' => 'company_name', 'label' => 'Company Name', 'type' => 'text', 'icon' => 'fas fa-building', 'value' => $data['company_name'] ?? '', 'error' => $data['company_name_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'business_registration_number', 'name' => 'business_registration_number', 'label' => 'Business Registration Number', 'type' => 'text', 'icon' => 'fas fa-certificate', 'value' => $data['business_registration_number'] ?? '', 'error' => $data['business_registration_number_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'tax_identification_number', 'name' => 'tax_identification_number', 'label' => 'Tax Identification Number (TIN)', 'type' => 'text', 'icon' => 'fas fa-file-invoice', 'value' => $data['tax_identification_number'] ?? '', 'error' => $data['tax_identification_number_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'year_established', 'name' => 'year_established', 'label' => 'Year Established', 'type' => 'number', 'icon' => 'fas fa-calendar', 'value' => $data['year_established'] ?? '', 'error' => $data['year_established_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'number_of_employees', 'name' => 'number_of_employees', 'label' => 'Number of Employees', 'type' => 'number', 'icon' => 'fas fa-users', 'value' => $data['number_of_employees'] ?? '', 'error' => $data['number_of_employees_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'website', 'name' => 'website', 'label' => 'Company Website (Optional)', 'type' => 'url', 'icon' => 'fas fa-globe', 'value' => $data['website'] ?? '', 'error' => $data['website_err'] ?? '', 'required' => false]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-address-book text-primary mr-2"></i>Contact Information</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'email', 'name' => 'email', 'label' => 'Company Email Address', 'type' => 'email', 'icon' => 'fas fa-envelope', 'value' => $data['email'] ?? '', 'error' => $data['email_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'contact', 'name' => 'contact', 'label' => 'Primary Contact Number', 'type' => 'tel', 'icon' => 'fas fa-phone', 'value' => $data['contact'] ?? '', 'error' => $data['contact_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'alternative_contact', 'name' => 'alternative_contact', 'label' => 'Alternative Contact Number', 'type' => 'tel', 'icon' => 'fas fa-phone-alt', 'value' => $data['alternative_contact'] ?? '', 'error' => $data['alternative_contact_err'] ?? '', 'required' => false]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'fax', 'name' => 'fax', 'label' => 'Fax Number (Optional)', 'type' => 'tel', 'icon' => 'fas fa-fax', 'value' => $data['fax'] ?? '', 'error' => $data['fax_err'] ?? '', 'required' => false]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-12 form-group">
                            <?php $inputConfig = ['id' => 'address', 'name' => 'address', 'label' => 'Registered Office Address', 'type' => 'text', 'icon' => 'fas fa-map-marker-alt', 'value' => $data['address'] ?? '', 'error' => $data['address_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
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
                                'icon' => 'fas fa-map-marked-alt',
                                'required' => true,
                                'error' => $data['district_err'] ?? '',
                                'placeholder' => 'Select a District'
                            ];
                            require APPROOT . '/views/inc/components/select_field.php';
                            ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'postal_code', 'name' => 'postal_code', 'label' => 'Postal Code', 'type' => 'text', 'icon' => 'fas fa-mail-bulk', 'value' => $data['postal_code'] ?? '', 'error' => $data['postal_code_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                    </div>
                </div>

                <!-- Primary Contact Person Section -->
                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-user-tie text-primary mr-2"></i>Primary Contact Person</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'contact_person_name', 'name' => 'contact_person_name', 'label' => 'Full Name', 'type' => 'text', 'icon' => 'fas fa-user', 'value' => $data['contact_person_name'] ?? '', 'error' => $data['contact_person_name_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'contact_person_designation', 'name' => 'contact_person_designation', 'label' => 'Designation/Position', 'type' => 'text', 'icon' => 'fas fa-id-badge', 'value' => $data['contact_person_designation'] ?? '', 'error' => $data['contact_person_designation_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'contact_person_email', 'name' => 'contact_person_email', 'label' => 'Email Address', 'type' => 'email', 'icon' => 'fas fa-envelope', 'value' => $data['contact_person_email'] ?? '', 'error' => $data['contact_person_email_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'contact_person_phone', 'name' => 'contact_person_phone', 'label' => 'Mobile Number', 'type' => 'tel', 'icon' => 'fas fa-mobile-alt', 'value' => $data['contact_person_phone'] ?? '', 'error' => $data['contact_person_phone_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'contact_person_nic', 'name' => 'contact_person_nic', 'label' => 'NIC Number', 'type' => 'text', 'icon' => 'fas fa-id-card', 'value' => $data['contact_person_nic'] ?? '', 'error' => $data['contact_person_nic_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                    </div>
                </div>

                <!-- Login Credentials Section -->
                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-lock text-primary mr-2"></i>Login Credentials</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'password', 'name' => 'password', 'label' => 'Password', 'type' => 'password', 'icon' => 'fas fa-lock', 'value' => $data['password'] ?? '', 'error' => $data['password_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                            <small class="text-secondary">Must be at least 8 characters with uppercase, lowercase, and numbers</small>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'confirm_password', 'name' => 'confirm_password', 'label' => 'Confirm Password', 'type' => 'password', 'icon' => 'fas fa-lock', 'value' => $data['confirm_password'] ?? '', 'error' => $data['confirm_password_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                    </div>
                </div>

                <!-- Business Details Section -->
                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-solar-panel text-primary mr-2"></i>Business Details</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php 
                            $serviceTypeOptions = [
                                'Installation Only' => 'Installation Only',
                                'Maintenance Only' => 'Maintenance Only',
                                'Both Installation & Maintenance' => 'Both Installation & Maintenance',
                                'Full Service (Installation, Maintenance & Repairs)' => 'Full Service (Installation, Maintenance & Repairs)'
                            ];
                            $selectConfig = [
                                'id' => 'service_type',
                                'name' => 'service_type',
                                'label' => 'Service Type',
                                'options' => $serviceTypeOptions,
                                'value' => $data['service_type'] ?? '',
                                'icon' => 'fas fa-cogs',
                                'required' => true,
                                'error' => $data['service_type_err'] ?? '',
                                'placeholder' => 'Select service type'
                            ];
                            require APPROOT . '/views/inc/components/select_field.php';
                            ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'years_of_experience', 'name' => 'years_of_experience', 'label' => 'Years of Experience in Solar Industry', 'type' => 'number', 'icon' => 'fas fa-award', 'value' => $data['years_of_experience'] ?? '', 'error' => $data['years_of_experience_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'completed_projects', 'name' => 'completed_projects', 'label' => 'Number of Completed Projects', 'type' => 'number', 'icon' => 'fas fa-tasks', 'value' => $data['completed_projects'] ?? '', 'error' => $data['completed_projects_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'service_areas', 'name' => 'service_areas', 'label' => 'Service Coverage Areas', 'type' => 'text', 'icon' => 'fas fa-map', 'value' => $data['service_areas'] ?? '', 'error' => $data['service_areas_err'] ?? '', 'required' => true]; require APPROOT . '/views/inc/components/input_field.php'; ?>
                            <small class="text-secondary">Enter districts separated by commas (e.g., Colombo, Gampaha, Kandy)</small>
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="form-label">Certifications & Licenses</label>
                            <textarea id="certifications" name="certifications" class="form-control" rows="3" placeholder="List all relevant certifications, licenses, and accreditations"><?php echo $data['certifications'] ?? ''; ?></textarea>
                            <?php if(!empty($data['certifications_err'])): ?>
                                <span class="error-message"><?php echo $data['certifications_err']; ?></span>
                            <?php endif; ?>
                            <small class="text-secondary">Include: CEB approvals, ISO certifications, technical qualifications, etc.</small>
                        </div>
                    </div>
                </div>

                <!-- Documents Upload Section (Optional) -->
                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-file-upload text-primary mr-2"></i>Supporting Documents (Optional)</h3>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                You can upload supporting documents later during the verification process. Accepted formats: PDF, JPG, PNG (Max 5MB per file)
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="form-section mb-10">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            I declare that all information provided is accurate and complete. I agree to the <a href="#" class="text-primary">Terms & Conditions</a> and <a href="#" class="text-primary">Privacy Policy</a> of SolarSense.
                        </label>
                        <?php if(!empty($data['terms_err'])): ?>
                            <span class="error-message"><?php echo $data['terms_err']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 pt-5 border-t d-flex justify-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="<?php echo $buttonIcon; ?> mr-2"></i> <?php echo $buttonText; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

        </div>
    </div>
</div>
