<?php
$customer = $data['customer'] ?? null;
$serviceAgents = $data['service_agents'] ?? [];
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/installer_admin/customer_details.css">

<div class="customer-details-container">
    <?php if ($data['user']['role'] === ROLE_OPERATION_MANAGER): 
        $config = [
        'title' => 'Customer Details',
        'description' => 'View and manage solar customer information',
        'show_back' => true,
        'back_url' => URLROOT . '/operationmanager/fleet',
        'back_label' => 'Back to Fleet'
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>
    <?php endif; ?>
    <?php if ($data['user']['role'] === ROLE_INSTALLER_ADMIN):
    $config = [
        'title' => 'Customer Details',
        'description' => 'View and manage solar customer information',
        'show_back' => true,
        'back_url' => URLROOT . '/installeradmin/fleet',
        'back_label' => 'Back to Fleet'
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>
    <?php endif; ?>

    <div class="row gap-6">
        <div class="col-md-12">
            <div class="card mb-6">
                <div class="card-body">
                    <div class="customer-profile-header text-center mb-6">
                        <div class="customer-avatar-large">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($customer->full_name); ?>&background=22c55e&color=fff&size=150"
                                alt="<?php echo $customer->full_name; ?>">
                        </div>
                        <h2 class="text-2xl font-bold mt-4"><?php echo $customer->full_name; ?></h2>
                        <div class="customer-type-badge">
                            <span class="badge bg-success">Residential Customer</span>
                        </div>
                        <div class="customer-status-badge mt-3">
                            <span class="status-badge status-active">
                                <i class="fas fa-circle text-success mr-1"></i>Active
                            </span>
                        </div>
                    </div>

                    <div class="customer-info-grid mb-6">
                        <div class="info-item">
                            <label class="info-label">Email</label>
                            <p class="info-value"><?php echo $customer->email; ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Contact Number</label>
                            <p class="info-value"><?php echo $customer->contact; ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">NIC/ID</label>
                            <p class="info-value"><?php echo $customer->nic; ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Address</label>
                            <p class="info-value"><?php echo $customer->address; ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">District</label>
                            <p class="info-value"><?php echo $customer->district; ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">CEB Account</label>
                            <p class="info-value"><?php echo $customer->ceb_account; ?></p>
                        </div>
                    </div>

                    <div class="solar-system-info border-top pt-6">
                        <h3 class="text-lg font-semibold mb-4">Solar System Information</h3>

                        <div class="info-item mb-4">
                            <label class="info-label">System Size</label>
                            <p class="info-value">
                                <span class="badge bg-info"><?php echo $customer->system_capacity; ?> kWp</span>
                            </p>
                        </div>

                        <div class="info-item mb-4">
                            <label class="info-label">Installation Date</label>
                            <p class="info-value"><?php echo date('F j, Y', strtotime($customer->installation_date)); ?>
                            </p>
                        </div>

                        <div class="info-item mb-4">
                            <label class="info-label">Hardware</label>
                            <p class="info-value">
                                Panels: <?php echo $customer->panel_brand; ?><br>
                                Inverter: <?php echo $customer->inverter_brand; ?>
                            </p>
                        </div>

                        <div class="info-item">
                            <label class="info-label">Panel Alignment</label>
                            <p class="info-value">Tilt: <?php echo $customer->tilt; ?>° | Azimuth:
                                <?php echo $customer->azimuth; ?>°</p>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <a href="<?php echo URLROOT; ?>/installeradmin/fleet/edit_customer/<?php echo $customer->user_id; ?>"
                            class="btn btn-sm btn-primary flex-1">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-danger flex-1" onclick="showDeleteModal()">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="deleteConfirmModal" class="custom-modal" style="display: none;">
    <div class="modal-overlay" onclick="closeDeleteModal()"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning mr-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close" onclick="closeDeleteModal()"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <p class="text-center mb-4"><i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i></p>
                <h4 class="text-center mb-2">Delete Customer?</h4>
                <p class="text-center text-secondary mb-4">
                    Are you sure you want to delete <strong><?php echo $customer->full_name; ?></strong>? This action
                    cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                <form
                    action="<?php echo URLROOT; ?>/installeradmin/fleet/delete_customer/<?php echo $customer->user_id; ?>"
                    method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-sm btn-danger">Delete Customer</button>
                </form>
            </div>
        </div>
    </div>
</div>