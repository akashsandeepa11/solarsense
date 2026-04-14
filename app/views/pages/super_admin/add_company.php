<?php
// --- PHP Setup for Form ---
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

$serviceTypeOptions = [
    'Installation Only' => 'Installation Only',
    'Maintenance Only' => 'Maintenance Only',
    'Both Installation & Maintenance' => 'Both Installation & Maintenance',
    'Full Service (Installation, Maintenance & Repairs)' => 'Full Service (Installation, Maintenance & Repairs)'
];

$pageTitle = 'Add New Solar Company';
$pageDescription = 'Register a new company with immediate platform verification and access.';
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/add_customer_form.css">

<div class="content-area">

    <?php
    $isEditMode = !empty($data['company_id']);
    $formAction = $isEditMode ? URLROOT . '/superadmin/companies/edit/' . $data['company_id']: URLROOT . '/superadmin/companies/add';
    $pageTitle = $isEditMode ? 'Update Solar Company' : 'Add New Solar Company'; 
    $pageDescription = $isEditMode ? 'Update existing company information and access details.' : 'Register a new company with immediate platform verification and access.';
    $buttonText = $isEditMode ? 'Update Company' : 'Add Company';
    $buttonIcon = $isEditMode ? 'fas fa-save' : 'fas fa-check-circle';

    $config = [
        'title' => $pageTitle,
        'description' => $pageDescription,
        'show_back' => true,
        'back_url' => URLROOT . '/superadmin/companies',
        'back_label' => 'Back to Companies'
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <div class="card shadow-lg rounded-xl">
        <div class="card-body p-10">
            <form id="add-company-form" action="<?php echo $formAction; ?>" method="post" novalidate>

                <?php if ($isEditMode): ?>
                    <input type="hidden" name="company_id" value="<?php echo $data['company_id']; ?>">
                <?php endif; ?>
                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-building text-primary mr-2"></i>Company
                        Information</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'company_name', 'name' => 'company_name', 'label' => 'Company Name', 'type' => 'text', 'icon' => 'fas fa-building', 'value' => $data['company_name'] ?? '', 'error' => $data['company_name_err'] ?? '', 'required' => true];
                            require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'address', 'name' => 'address', 'label' => 'Registered Office Address', 'type' => 'text', 'icon' => 'fas fa-house', 'value' => $data['address'] ?? '', 'error' => $data['address_err'] ?? '', 'required' => true];
                            require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'number_of_employees', 'name' => 'number_of_employees', 'label' => 'Number of Employees', 'type' => 'number', 'icon' => 'fas fa-users', 'value' => $data['number_of_employees'] ?? '', 'error' => $data['number_of_employees_err'] ?? '', 'required' => true];
                            require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'website', 'name' => 'website', 'label' => 'Company Website (Optional)', 'type' => 'url', 'icon' => 'fas fa-globe', 'value' => $data['website'] ?? '', 'error' => $data['website_err'] ?? '', 'required' => false];
                            require APPROOT . '/views/inc/components/input_field.php'; ?>
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
                            <?php $inputConfig = ['id' => 'postal_code', 'name' => 'postal_code', 'label' => 'Postal Code', 'type' => 'text', 'icon' => 'fas fa-mail-bulk', 'value' => $data['postal_code'] ?? '', 'error' => $data['postal_code_err'] ?? '', 'required' => true];
                            require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                    </div>
                </div>

                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-address-book text-primary mr-2"></i>Contact
                        Information</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'contact_number', 'name' => 'contact_number', 'label' => 'Contact Number', 'type' => 'text', 'icon' => 'fas fa-phone', 'value' => $data['contact_number'] ?? '', 'error' => $data['contact_number_err'] ?? '', 'required' => true];
                            require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'email', 'name' => 'email', 'label' => 'Company Email Address (Login Username)', 'type' => 'email', 'icon' => 'fas fa-envelope', 'value' => $data['email'] ?? '', 'error' => $data['email_err'] ?? '', 'required' => true];
                            require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                    </div>
                </div>

                <div class="form-section mb-10">
                    <h3 class="text-lg font-semibold mb-6"><i class="fas fa-solar-panel text-primary mr-2"></i>Business
                        Details</h3>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <?php
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
                            <?php $inputConfig = ['id' => 'years_of_experience', 'name' => 'years_of_experience', 'label' => 'Years of Experience', 'type' => 'number', 'icon' => 'fas fa-award', 'value' => $data['years_of_experience'] ?? '', 'error' => $data['years_of_experience_err'] ?? '', 'required' => true];
                            require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'completed_projects', 'name' => 'completed_projects', 'label' => 'Completed Projects', 'type' => 'number', 'icon' => 'fas fa-tasks', 'value' => $data['completed_projects'] ?? '', 'error' => $data['completed_projects_err'] ?? '', 'required' => true];
                            require APPROOT . '/views/inc/components/input_field.php'; ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <?php $inputConfig = ['id' => 'service_areas', 'name' => 'service_areas', 'label' => 'Service Coverage Areas', 'type' => 'text', 'icon' => 'fas fa-map', 'value' => $data['service_areas'] ?? '', 'error' => $data['service_areas_err'] ?? '', 'required' => true];
                            require APPROOT . '/views/inc/components/input_field.php'; ?>
                            <small class="text-secondary">Districts separated by commas (e.g., Colombo, Gampaha)</small>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-5 border-t d-flex justify-end gap-3">
                    <a href="<?php echo URLROOT; ?>/superadmin/companies" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="<?php echo $buttonIcon; ?> mr-2"></i> <?php echo $buttonText; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>