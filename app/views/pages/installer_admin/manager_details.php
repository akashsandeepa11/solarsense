<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/installer_admin/customer_details.css">

<?php
// Helper functions for status styling
function getStatusColorClass($status) {
    switch(strtolower($status ?? 'inactive')) {
        case 'active':
            return 'text-success';
        case 'inactive':
            return 'text-gray-500';
        case 'on leave':
            return 'text-warning';
        case 'away':
            return 'text-info';
        default:
            return 'text-gray-500';
    }
}

function getStatusIcon($status) {
    switch(strtolower($status ?? 'pending')) {
        case 'completed':
            return 'fa-check-circle text-success';
        case 'in progress':
        case 'pending':
            return 'fa-clock text-warning';
        case 'scheduled':
            return 'fa-calendar text-info';
        default:
            return 'fa-info-circle text-gray-500';
    }
}
?>

<div class="customer-details-container">
    <!-- Page Header -->
    <?php
    $managerType = isset($data['managerType']) ? $data['managerType'] : 'operation_managers';
    $isOperationManager = ($managerType === 'operation_managers');
    
    $config = [
        'title' => $isOperationManager ? 'Operation Manager Details' : 'Inventory Manager Details',
        'description' => $isOperationManager ? 'View and manage operation manager information' : 'View and manage inventory manager information',
        'show_back' => true,
        'back_url' => URLROOT . '/installeradmin/' . $managerType,
        'back_label' => 'Back to ' . ($isOperationManager ? 'Operation Managers' : 'Inventory Managers')
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <div class="row gap-6">
        <!-- Left Column: Manager Profile & Info -->
        <div class="col-md-5">
            <!-- Manager Profile Card -->
            <div class="card mb-6">
                <div class="card-body">
                    <!-- Manager Avatar -->
                    <div class="customer-profile-header text-center mb-6">
                        <div class="customer-avatar-large">
                            <img src="<?php echo getAvatarUrl($data['manager']['name'] ?? 'Manager'); ?>" alt="<?php echo htmlspecialchars($data['manager']['name'] ?? 'Manager'); ?>">
                        </div>
                        <h2 class="text-2xl font-bold mt-4"><?php echo htmlspecialchars($data['manager']['name'] ?? 'N/A'); ?></h2>
                        <div class="customer-type-badge">
                            <span class="badge bg-primary">
                                <?php echo $isOperationManager ? 'Operation Manager' : 'Inventory Manager'; ?>
                            </span>
                        </div>
                        <div class="customer-status-badge mt-3">
                            <span class="status-badge status-<?php echo strtolower($data['manager']['status'] ?? 'inactive'); ?>">
                                <i class="fas fa-circle <?php echo getStatusColorClass($data['manager']['status'] ?? 'inactive'); ?> mr-1"></i>
                                <?php echo ucfirst($data['manager']['status'] ?? 'N/A'); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Manager Info Grid -->
                    <div class="customer-info-grid mb-6">
                        <div class="info-item">
                            <label class="info-label">Email</label>
                            <p class="info-value"><?php echo htmlspecialchars($data['manager']['email'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Contact Number</label>
                            <p class="info-value"><?php echo htmlspecialchars($data['manager']['contact'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">NIC/ID</label>
                            <p class="info-value"><?php echo htmlspecialchars($data['manager']['nic'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Address</label>
                            <p class="info-value"><?php echo htmlspecialchars($data['manager']['address'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">District</label>
                            <p class="info-value"><?php echo htmlspecialchars($data['manager']['district'] ?? 'N/A'); ?></p>
                        </div>
                    </div>

                    <!-- Manager Specific Info -->
                    <div class="solar-system-info border-top pt-6">
                        <h3 class="text-lg font-semibold mb-4">
                            <?php echo $isOperationManager ? 'Operation Details' : 'Inventory Details'; ?>
                        </h3>
                        
                        <?php if ($isOperationManager): ?>
                            <div class="info-item mb-4">
                                <label class="info-label">Specialization</label>
                                <p class="info-value">
                                    <span class="badge bg-info"><?php echo htmlspecialchars($data['manager']['specialization'] ?? 'N/A'); ?></span>
                                </p>
                            </div>

                            <div class="info-item mb-4">
                                <label class="info-label">Experience Years</label>
                                <p class="info-value"><?php echo htmlspecialchars($data['manager']['experience_years'] ?? '0'); ?> years</p>
                            </div>

                            <div class="info-item mb-4">
                                <label class="info-label">Availability</label>
                                <p class="info-value">
                                    <span class="badge bg-success"><?php echo htmlspecialchars($data['manager']['availability'] ?? 'N/A'); ?></span>
                                </p>
                            </div>

                            <div class="info-item">
                                <label class="info-label">Certifications</label>
                                <p class="info-value"><?php echo htmlspecialchars($data['manager']['certifications'] ?? 'None'); ?></p>
                            </div>
                        <?php else: ?>
                            <div class="info-item mb-4">
                                <label class="info-label">Warehouse Location</label>
                                <p class="info-value">
                                    <span class="badge bg-info"><?php echo htmlspecialchars($data['manager']['warehouse'] ?? 'N/A'); ?></span>
                                </p>
                            </div>

                            <div class="info-item mb-4">
                                <label class="info-label">Total Inventory Items</label>
                                <p class="info-value"><?php echo htmlspecialchars($data['manager']['inventory_items'] ?? '0'); ?> items</p>
                            </div>

                            <div class="info-item mb-4">
                                <label class="info-label">Low Stock Alert Threshold</label>
                                <p class="info-value"><?php echo htmlspecialchars($data['manager']['low_stock_threshold'] ?? '10'); ?> units</p>
                            </div>

                            <div class="info-item">
                                <label class="info-label">Last Inventory Check</label>
                                <p class="info-value"><?php echo htmlspecialchars($data['manager']['last_inventory_check'] ?? 'N/A'); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Card Footer with Actions -->
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <a href="<?php echo URLROOT; ?>/installeradmin/<?php echo $managerType; ?>/edit/<?php echo $data['manager']['id'] ?? '#'; ?>" class="btn btn-sm btn-primary flex-1">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-danger flex-1" onclick="showDeleteModal()">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Performance Stats & Activity -->
        <div class="col-md-7">
            <!-- Performance Stats -->
            <div class="stats-grid mb-6 row gap-4">
                <?php if ($isOperationManager): ?>
                    <div class="col-md-4">
                        <div class="stat-mini-card">
                            <div class="stat-number text-success"><?php echo htmlspecialchars($data['manager']['performance'] ?? '0'); ?>%</div>
                            <div class="stat-label">Performance Score</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-mini-card">
                            <div class="stat-number text-primary"><?php echo htmlspecialchars($data['manager']['pending_tasks'] ?? '0'); ?></div>
                            <div class="stat-label">Pending Tasks</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-mini-card">
                            <div class="stat-number text-accent"><?php echo htmlspecialchars($data['manager']['completed_tasks'] ?? '0'); ?></div>
                            <div class="stat-label">Completed Tasks</div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-md-4">
                        <div class="stat-mini-card">
                            <div class="stat-number text-success"><?php echo htmlspecialchars($data['manager']['efficiency'] ?? '0'); ?>%</div>
                            <div class="stat-label">Efficiency Score</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-mini-card">
                            <div class="stat-number text-warning"><?php echo htmlspecialchars($data['manager']['low_stock'] ?? '0'); ?></div>
                            <div class="stat-label">Low Stock Items</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-mini-card">
                            <div class="stat-number text-info"><?php echo htmlspecialchars($data['manager']['total_orders'] ?? '0'); ?></div>
                            <div class="stat-label">Total Orders</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Performance Metrics Card -->
            <div class="card mb-6">
                <div class="card-body">
                    <h3 class="text-lg font-semibold mb-4">
                        <?php echo $isOperationManager ? 'Task Performance' : 'Inventory Performance'; ?>
                    </h3>
                    
                    <?php if ($isOperationManager): ?>
                        <div class="metric-item mb-5">
                            <div class="d-flex justify-between mb-2">
                                <label class="metric-label">Tasks Completed This Month</label>
                                <span class="metric-value text-success font-bold"><?php echo htmlspecialchars($data['manager']['monthly_tasks_completed'] ?? '0'); ?></span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo htmlspecialchars($data['manager']['monthly_tasks_completed'] ?? '0'); ?>%; background: linear-gradient(90deg, #22c55e, #16a34a);"></div>
                            </div>
                        </div>

                        <div class="metric-item mb-5">
                            <div class="d-flex justify-between mb-2">
                                <label class="metric-label">On-time Completion Rate</label>
                                <span class="metric-value text-primary font-bold"><?php echo htmlspecialchars($data['manager']['ontime_rate'] ?? '0'); ?>%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo htmlspecialchars($data['manager']['ontime_rate'] ?? '0'); ?>%; background: linear-gradient(90deg, #fe9630, #f59e0b);"></div>
                            </div>
                        </div>

                        <div class="metric-item">
                            <div class="d-flex justify-between mb-2">
                                <label class="metric-label">Quality Score</label>
                                <span class="metric-value text-accent font-bold"><?php echo htmlspecialchars($data['manager']['quality_score'] ?? '0'); ?>%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo htmlspecialchars($data['manager']['quality_score'] ?? '0'); ?>%; background: linear-gradient(90deg, #00bcd4, #0097a7);"></div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="metric-item mb-5">
                            <div class="d-flex justify-between mb-2">
                                <label class="metric-label">Stock Accuracy</label>
                                <span class="metric-value text-success font-bold"><?php echo htmlspecialchars($data['manager']['stock_accuracy'] ?? '0'); ?>%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo htmlspecialchars($data['manager']['stock_accuracy'] ?? '0'); ?>%; background: linear-gradient(90deg, #22c55e, #16a34a);"></div>
                            </div>
                        </div>

                        <div class="metric-item mb-5">
                            <div class="d-flex justify-between mb-2">
                                <label class="metric-label">Order Fulfillment Rate</label>
                                <span class="metric-value text-primary font-bold"><?php echo htmlspecialchars($data['manager']['fulfillment_rate'] ?? '0'); ?>%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo htmlspecialchars($data['manager']['fulfillment_rate'] ?? '0'); ?>%; background: linear-gradient(90deg, #fe9630, #f59e0b);"></div>
                            </div>
                        </div>

                        <div class="metric-item">
                            <div class="d-flex justify-between mb-2">
                                <label class="metric-label">Warehouse Efficiency</label>
                                <span class="metric-value text-accent font-bold"><?php echo htmlspecialchars($data['manager']['warehouse_efficiency'] ?? '0'); ?>%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo htmlspecialchars($data['manager']['warehouse_efficiency'] ?? '0'); ?>%; background: linear-gradient(90deg, #00bcd4, #0097a7);"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Activity/History -->
            <div class="card">
                <div class="card-header border-bottom">
                    <h3 class="card-title">
                        <?php echo $isOperationManager ? 'Recent Tasks (Last 5)' : 'Recent Inventory Activity (Last 5)'; ?>
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="service-list">
                        <?php 
                        $activities = isset($data['manager']['activities']) ? $data['manager']['activities'] : [];
                        if (empty($activities)): 
                        ?>
                            <div class="service-item p-4">
                                <p class="text-secondary text-center">No activities recorded yet</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($activities as $activity): ?>
                                <div class="service-item border-bottom p-4">
                                    <div class="d-flex justify-between align-center mb-2">
                                        <h4 class="service-title font-semibold"><?php echo htmlspecialchars($activity['title'] ?? 'Activity'); ?></h4>
                                        <span class="service-status status-<?php echo strtolower($activity['status'] ?? 'pending'); ?>">
                                            <i class="fas <?php echo getStatusIcon($activity['status'] ?? 'pending'); ?> mr-1"></i><?php echo ucfirst($activity['status'] ?? 'Pending'); ?>
                                        </span>
                                    </div>
                                    <p class="service-agent text-secondary text-sm mb-2"><?php echo htmlspecialchars($activity['description'] ?? ''); ?></p>
                                    <div class="d-flex justify-between align-center">
                                        <span class="service-date text-gray-500 text-sm"><?php echo htmlspecialchars($activity['date'] ?? 'N/A'); ?></span>
                                        <span class="service-cost text-gray-500 text-sm"><?php echo htmlspecialchars($activity['details'] ?? ''); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal (Custom) -->
<div id="deleteConfirmModal" class="custom-modal" style="display: none;">
    <div class="modal-overlay" onclick="closeDeleteModal()"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning mr-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close" onclick="closeDeleteModal()" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center mb-4">
                    <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                </p>
                <h4 class="text-center mb-2">Delete Manager?</h4>
                <p class="text-center text-secondary mb-4">
                    Are you sure you want to delete <strong><?php echo htmlspecialchars($data['manager']['name'] ?? 'this manager'); ?></strong>? This action cannot be undone. All associated records will be archived.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" onclick="closeDeleteModal()">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <form action="<?php echo URLROOT; ?>/installeradmin/<?php echo $managerType; ?>/delete/<?php echo $data['manager']['id'] ?? '#'; ?>" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-check mr-2"></i>Delete Manager
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Custom Modal Styles & JavaScript -->
<style>
.custom-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
    display: flex;
    align-items: center;
    justify-content: center;
}

.custom-modal.show {
    display: flex;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s ease-in-out;
}

.modal-dialog {
    position: relative;
    z-index: 1051;
    width: 90%;
    max-width: 500px;
    animation: slideUp 0.3s ease-out;
}

.modal-content {
    background-color: #ffffff;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.modal-header {
    padding: 1.5rem;
    background-color: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #212121;
    margin: 0;
}

.modal-body {
    padding: 2rem 1.5rem;
}

.modal-footer {
    padding: 1.5rem;
    background-color: #f9fafb;
    border-top: 1px solid #e5e7eb;
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

.btn-close {
    width: 1.5rem;
    height: 1.5rem;
    background: transparent;
    border: none;
    cursor: pointer;
    opacity: 0.5;
    transition: opacity 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

.btn-close:hover {
    opacity: 1;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<script>
function showDeleteModal() {
    const modal = document.getElementById('deleteConfirmModal');
    modal.classList.add('show');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteConfirmModal');
    modal.classList.remove('show');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function getStatusIcon(status) {
    switch(status.toLowerCase()) {
        case 'completed':
            return 'fa-check-circle text-success';
        case 'in progress':
        case 'pending':
            return 'fa-clock text-warning';
        case 'scheduled':
            return 'fa-calendar text-info';
        default:
            return 'fa-info-circle text-gray-500';
    }
}

function getStatusColorClass(status) {
    switch(status.toLowerCase()) {
        case 'active':
            return 'text-success';
        case 'inactive':
            return 'text-gray-500';
        case 'on leave':
            return 'text-warning';
        case 'away':
            return 'text-info';
        default:
            return 'text-gray-500';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteConfirmModal');
    const overlay = modal.querySelector('.modal-overlay');
    
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closeDeleteModal();
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('show')) {
            closeDeleteModal();
        }
    });
});
</script>
