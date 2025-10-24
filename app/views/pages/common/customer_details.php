
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/installer_admin/customer_details.css">

<div class="customer-details-container">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'Customer Details',
        'description' => 'View and manage solar customer information',
        'show_back' => true,
        'back_url' => URLROOT . '/installeradmin/fleet',
        'back_label' => 'Back to Fleet'
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <div class="row gap-6">
        <!-- Left Column: Customer Profile & Info -->
        <div class="col-md-5">
            <!-- Customer Profile Card -->
            <div class="card mb-6">
                <div class="card-body">
                    <!-- Customer Avatar -->
                    <div class="customer-profile-header text-center mb-6">
                        <div class="customer-avatar-large">
                            <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=22c55e&color=fff&size=150" alt="Sarah Johnson">
                        </div>
                        <h2 class="text-2xl font-bold mt-4">Sarah Johnson</h2>
                        <div class="customer-type-badge">
                            <span class="badge bg-success">Residential Customer</span>
                        </div>
                        <div class="customer-status-badge mt-3">
                            <span class="status-badge status-active">
                                <i class="fas fa-circle text-success mr-1"></i>Active
                            </span>
                        </div>
                    </div>

                    <!-- Customer Info Grid -->
                    <div class="customer-info-grid mb-6">
                        <div class="info-item">
                            <label class="info-label">Email</label>
                            <p class="info-value">sarah.johnson@example.com</p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Contact Number</label>
                            <p class="info-value">+94 77 456 7890</p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">NIC/ID</label>
                            <p class="info-value">987654321V</p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Address</label>
                            <p class="info-value">45 Residential Lane, Colombo 7</p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">District</label>
                            <p class="info-value">Colombo</p>
                        </div>
                    </div>

                    <!-- Solar System Info -->
                    <div class="solar-system-info border-top pt-6">
                        <h3 class="text-lg font-semibold mb-4">Solar System Information</h3>
                        
                        <div class="info-item mb-4">
                            <label class="info-label">System Size</label>
                            <p class="info-value">
                                <span class="badge bg-info">5.5 kWp</span>
                            </p>
                        </div>

                        <div class="info-item mb-4">
                            <label class="info-label">Installation Date</label>
                            <p class="info-value">March 15, 2024</p>
                        </div>

                        <div class="info-item mb-4">
                            <label class="info-label">Warranty Status</label>
                            <p class="info-value">
                                <span class="badge bg-success">Valid (5 years)</span>
                            </p>
                        </div>

                        <div class="info-item">
                            <label class="info-label">Last Maintenance</label>
                            <p class="info-value">Oct 18, 2025</p>
                        </div>
                    </div>
                </div>

                <!-- Card Footer with Actions -->
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <a href="<?php echo URLROOT; ?>/installeradmin/customers/edit/1" class="btn btn-sm btn-primary flex-1">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-danger flex-1" onclick="showDeleteModal()">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: System Stats & Activity -->
        <div class="col-md-7">
            <!-- Performance Stats -->
            <div class="stats-grid mb-6 row gap-4">
                <div class="col-md-4">
                    <div class="stat-mini-card">
                        <div class="stat-number text-success">102%</div>
                        <div class="stat-label">System Health</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-mini-card">
                        <div class="stat-number text-primary">18,450</div>
                        <div class="stat-label">Energy Generated (kWh)</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-mini-card">
                        <div class="stat-number text-accent">Rs2,345</div>
                        <div class="stat-label">Savings (YTD)</div>
                    </div>
                </div>
            </div>

            <!-- System Performance Card -->
            <div class="card mb-6">
                <div class="card-body">
                    <h3 class="text-lg font-semibold mb-4">System Performance</h3>
                    
                    <div class="metric-item mb-5">
                        <div class="d-flex justify-between mb-2">
                            <label class="metric-label">Daily Generation</label>
                            <span class="metric-value text-success font-bold">24.5 kWh</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 89%; background: linear-gradient(90deg, #22c55e, #16a34a);"></div>
                        </div>
                    </div>

                    <div class="metric-item mb-5">
                        <div class="d-flex justify-between mb-2">
                            <label class="metric-label">Monthly Average</label>
                            <span class="metric-value text-primary font-bold">685 kWh</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 76%; background: linear-gradient(90deg, #fe9630, #f59e0b);"></div>
                        </div>
                    </div>

                    <div class="metric-item">
                        <div class="d-flex justify-between mb-2">
                            <label class="metric-label">System Efficiency</label>
                            <span class="metric-value text-accent font-bold">94.2%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 94%; background: linear-gradient(90deg, #00bcd4, #0097a7);"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service History -->
            <div class="card">
                <div class="card-header border-bottom">
                    <h3 class="card-title">Service History (Last 5)</h3>
                </div>
                <div class="card-body p-0">
                    <div class="service-list">
                        <!-- Service Item 1 -->
                        <div class="service-item border-bottom p-4">
                            <div class="d-flex justify-between align-center mb-2">
                                <h4 class="service-title font-semibold">System Installation</h4>
                                <span class="service-status status-completed">
                                    <i class="fas fa-check-circle text-success mr-1"></i>Completed
                                </span>
                            </div>
                            <p class="service-agent text-secondary text-sm mb-2">Agent: John Doe</p>
                            <div class="d-flex justify-between align-center">
                                <span class="service-date text-gray-500 text-sm">Mar 15, 2024</span>
                                <span class="service-cost text-gray-500 text-sm">Cost: $5,500</span>
                            </div>
                        </div>

                        <!-- Service Item 2 -->
                        <div class="service-item border-bottom p-4">
                            <div class="d-flex justify-between align-center mb-2">
                                <h4 class="service-title font-semibold">Quarterly Maintenance</h4>
                                <span class="service-status status-completed">
                                    <i class="fas fa-check-circle text-success mr-1"></i>Completed
                                </span>
                            </div>
                            <p class="service-agent text-secondary text-sm mb-2">Agent: Mike Wilson</p>
                            <div class="d-flex justify-between align-center">
                                <span class="service-date text-gray-500 text-sm">Jul 10, 2024</span>
                                <span class="service-cost text-gray-500 text-sm">Cost: $150</span>
                            </div>
                        </div>

                        <!-- Service Item 3 -->
                        <div class="service-item border-bottom p-4">
                            <div class="d-flex justify-between align-center mb-2">
                                <h4 class="service-title font-semibold">Panel Cleaning & Inspection</h4>
                                <span class="service-status status-completed">
                                    <i class="fas fa-check-circle text-success mr-1"></i>Completed
                                </span>
                            </div>
                            <p class="service-agent text-secondary text-sm mb-2">Agent: Emma Davis</p>
                            <div class="d-flex justify-between align-center">
                                <span class="service-date text-gray-500 text-sm">Oct 02, 2024</span>
                                <span class="service-cost text-gray-500 text-sm">Cost: $100</span>
                            </div>
                        </div>

                        <!-- Service Item 4 -->
                        <div class="service-item border-bottom p-4">
                            <div class="d-flex justify-between align-center mb-2">
                                <h4 class="service-title font-semibold">Inverter Firmware Update</h4>
                                <span class="service-status status-completed">
                                    <i class="fas fa-check-circle text-success mr-1"></i>Completed
                                </span>
                            </div>
                            <p class="service-agent text-secondary text-sm mb-2">Agent: Lisa Brown</p>
                            <div class="d-flex justify-between align-center">
                                <span class="service-date text-gray-500 text-sm">Oct 15, 2025</span>
                                <span class="service-cost text-gray-500 text-sm">Cost: Free</span>
                            </div>
                        </div>

                        <!-- Service Item 5 -->
                        <div class="service-item p-4">
                            <div class="d-flex justify-between align-center mb-2">
                                <h4 class="service-title font-semibold">Performance Review & Optimization</h4>
                                <span class="service-status status-scheduled">
                                    <i class="fas fa-calendar text-info mr-1"></i>Scheduled
                                </span>
                            </div>
                            <p class="service-agent text-secondary text-sm mb-2">Agent: Robert Taylor</p>
                            <div class="d-flex justify-between align-center">
                                <span class="service-date text-gray-500 text-sm">Nov 10, 2025</span>
                                <span class="service-cost text-gray-500 text-sm">Cost: $200</span>
                            </div>
                        </div>
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
                <h4 class="text-center mb-2">Delete Customer?</h4>
                <p class="text-center text-secondary mb-4">
                    Are you sure you want to delete <strong>Sarah Johnson</strong>? This action cannot be undone. All associated service history will be archived.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" onclick="closeDeleteModal()">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <form action="<?php echo URLROOT; ?>/installeradmin/customers/delete/1" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-check mr-2"></i>Delete Customer
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
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteConfirmModal');
    modal.classList.remove('show');
    modal.style.display = 'none';
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

// Close modal when clicking overlay
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteConfirmModal');
    const overlay = modal.querySelector('.modal-overlay');
    
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closeDeleteModal();
        }
    });
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('show')) {
            closeDeleteModal();
        }
    });
});
</script>
