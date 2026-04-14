<?php
$company = $data['company'] ?? null;
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/installer_admin/customer_details.css">

<div class="customer-details-container">
    <?php
    $config = [
        'title' => 'Company Profile',
        'description' => 'Detailed information and business metrics for ' . $company->company_name,
        'show_back' => true,
        'back_url' => URLROOT . '/superadmin/companies',
        'back_label' => 'Back to Companies'
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <div class="row gap-6">
        <div class="col-md-12">
            <div class="card mb-6">
                <div class="card-body">
                    <div class="customer-profile-header text-center mb-6">
                        <div class="customer-avatar-large">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($company->company_name); ?>&background=fe9630&color=fff&size=150"
                                alt="<?php echo $company->company_name; ?>">
                        </div>
                        <h2 class="text-2xl font-bold mt-4"><?php echo $company->company_name; ?></h2>
                        <div class="customer-type-badge">
                            <span class="badge bg-primary">Installer Company</span>
                        </div>
                        <div class="customer-status-badge mt-3">
                            <span class="status-badge status-active">
                                <i class="fas fa-circle text-success mr-1"></i><?php echo $company->status; ?>
                            </span>
                        </div>
                    </div>

                    <div class="customer-info-grid mb-6">
                        <div class="info-item">
                            <label class="info-label">Office Address</label>
                            <p class="info-value"><?php echo $company->address; ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">District</label>
                            <p class="info-value"><?php echo $company->district; ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Postal Code</label>
                            <p class="info-value"><?php echo $company->postal_code; ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Employees</label>
                            <p class="info-value"><?php echo $company->num_employees; ?> Members</p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Website</label>
                            <p class="info-value text-primary"><?php echo $company->website ?: 'N/A'; ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Member Since</label>
                            <p class="info-value"><?php echo date('F j, Y', strtotime($company->register_date)); ?></p>
                        </div>
                    </div>

                    <div class="solar-system-info border-top pt-6">
                        <h3 class="text-lg font-semibold mb-4">Business & Service Details</h3>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="info-label">Service Type</label>
                                <p class="info-value"><span class="badge bg-info"><?php echo $company->service_type; ?></span></p>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="info-label">Industry Experience</label>
                                <p class="info-value"><?php echo $company->years_experience; ?> Years</p>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="info-label">Projects Completed</label>
                                <p class="info-value"><?php echo $company->complete_projects; ?> Installations</p>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="info-label">Service Areas</label>
                                <p class="info-value"><?php echo $company->service_areas; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="solar-system-info border-top pt-6 mt-4">
                        <h3 class="text-lg font-semibold mb-4">Admin Contact Information</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="info-label">Login/Admin Email</label>
                                <p class="info-value"><?php echo $company->admin_email; ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="info-label">Company Phone</label>
                                <p class="info-value"><?php echo $company->contact; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <a href="<?php echo URLROOT; ?>/superadmin/companies/edit/<?php echo $company->company_id; ?>"
                            class="btn btn-sm btn-primary flex-1">
                            <i class="fas fa-edit mr-2"></i> Edit Company
                        </a>
                        <button type="button" class="btn btn-sm btn-danger flex-1" onclick="showDeleteModal()">
                            <i class="fas fa-trash mr-2"></i> Remove Company
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>