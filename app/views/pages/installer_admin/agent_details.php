<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/installer_admin/agent_details.css">

<div class="agent-details-container">
    <!-- Header with Back Button -->
    <div class="agent-header mb-6">
        <div class="d-flex align-center gap-3 mb-4">
            <a href="<?php echo URLROOT; ?>/installeradmin/team" class="btn-back">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold">Agent Details</h1>
                <p class="text-secondary text-sm">View and manage service agent information</p>
            </div>
        </div>
    </div>

    <div class="row gap-6">
        <!-- Left Column: Agent Profile & Info -->
        <div class="col-md-5">
            <!-- Agent Profile Card -->
            <div class="card mb-6">
                <div class="card-body">
                    <!-- Agent Avatar -->
                    <div class="agent-profile-header text-center mb-6">
                        <div class="agent-avatar-large">
                            <img src="https://ui-avatars.com/api/?name=John+Doe&background=fe9630&color=fff&size=150" alt="John Doe">
                        </div>
                        <h2 class="text-2xl font-bold mt-4">John Doe</h2>
                        <div class="agent-role-badge">
                            <span class="badge bg-primary">Service Agent</span>
                        </div>
                        <div class="agent-status-badge mt-3">
                            <span class="status-badge status-active">
                                <i class="fas fa-circle text-success mr-1"></i>Active
                            </span>
                        </div>
                    </div>

                    <!-- Agent Info Grid -->
                    <div class="agent-info-grid mb-6">
                        <div class="info-item">
                            <label class="info-label">Email</label>
                            <p class="info-value">john@example.com</p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Contact Number</label>
                            <p class="info-value">+94 77 123 4567</p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">NIC/ID</label>
                            <p class="info-value">123456789V</p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Address</label>
                            <p class="info-value">123 Main Street, Colombo</p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">District</label>
                            <p class="info-value">Colombo</p>
                        </div>
                    </div>

                    <!-- Professional Info -->
                    <div class="professional-info border-top pt-6">
                        <h3 class="text-lg font-semibold mb-4">Professional Information</h3>
                        
                        <div class="info-item mb-4">
                            <label class="info-label">Specialization</label>
                            <p class="info-value">
                                <span class="badge bg-warning">Solar Installation</span>
                            </p>
                        </div>

                        <div class="info-item mb-4">
                            <label class="info-label">Experience</label>
                            <p class="info-value">5 years</p>
                        </div>

                        <div class="info-item mb-4">
                            <label class="info-label">Availability</label>
                            <p class="info-value">
                                <span class="badge bg-success">Full-time</span>
                            </p>
                        </div>

                        <div class="info-item">
                            <label class="info-label">Certifications</label>
                            <p class="info-value">IEC 61730, PV Certified</p>
                        </div>
                    </div>
                </div>

                <!-- Card Footer with Actions -->
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <a href="<?php echo URLROOT; ?>/installeradmin/team/edit_agent/1" class="btn btn-sm btn-primary flex-1">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-danger flex-1" onclick="showConfirmationModal('deleteAgentModal')">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Tasks & Statistics -->
        <div class="col-md-7">
            <!-- Performance Stats -->
            <div class="stats-grid mb-6 row gap-4">
                <div class="col-md-4">
                    <div class="stat-mini-card">
                        <div class="stat-number text-primary">48</div>
                        <div class="stat-label">Total Tasks</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-mini-card">
                        <div class="stat-number text-success">36</div>
                        <div class="stat-label">Completed</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-mini-card">
                        <div class="stat-number text-warning">12</div>
                        <div class="stat-label">Pending</div>
                    </div>
                </div>
            </div>

            <!-- Completion Rate Card -->
            <div class="card mb-6">
                <div class="card-body">
                    <h3 class="text-lg font-semibold mb-4">Performance Metrics</h3>
                    
                    <div class="metric-item mb-5">
                        <div class="d-flex justify-between mb-2">
                            <label class="metric-label">Completion Rate</label>
                            <span class="metric-value text-success font-bold">75%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 75%; background: linear-gradient(90deg, #22c55e, #16a34a);"></div>
                        </div>
                    </div>

                    <div class="metric-item mb-5">
                        <div class="d-flex justify-between mb-2">
                            <label class="metric-label">On-Time Delivery</label>
                            <span class="metric-value text-primary font-bold">92%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 92%; background: linear-gradient(90deg, #fe9630, #f59e0b);"></div>
                        </div>
                    </div>

                    <div class="metric-item">
                        <div class="d-flex justify-between mb-2">
                            <label class="metric-label">Customer Satisfaction</label>
                            <span class="metric-value text-accent font-bold">4.8/5</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 96%; background: linear-gradient(90deg, #00bcd4, #0097a7);"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Tasks -->
            <div class="card">
                <div class="card-header border-bottom">
                    <h3 class="card-title">Recent Tasks (Last 5)</h3>
                </div>
                <div class="card-body p-0">
                    <div class="tasks-list">
                        <!-- Task Item 1 -->
                        <div class="task-item border-bottom p-4">
                            <div class="d-flex justify-between align-center mb-2">
                                <h4 class="task-title font-semibold">Solar Panel Installation</h4>
                                <span class="task-status status-completed">
                                    <i class="fas fa-check-circle text-success mr-1"></i>Completed
                                </span>
                            </div>
                            <p class="task-customer text-secondary text-sm mb-2">Customer: Sarah Johnson</p>
                            <div class="d-flex justify-between align-center">
                                <span class="task-date text-gray-500 text-sm">Oct 18, 2025</span>
                                <span class="task-duration text-gray-500 text-sm">Duration: 4 hours</span>
                            </div>
                        </div>

                        <!-- Task Item 2 -->
                        <div class="task-item border-bottom p-4">
                            <div class="d-flex justify-between align-center mb-2">
                                <h4 class="task-title font-semibold">System Maintenance</h4>
                                <span class="task-status status-completed">
                                    <i class="fas fa-check-circle text-success mr-1"></i>Completed
                                </span>
                            </div>
                            <p class="task-customer text-secondary text-sm mb-2">Customer: Mike Wilson</p>
                            <div class="d-flex justify-between align-center">
                                <span class="task-date text-gray-500 text-sm">Oct 17, 2025</span>
                                <span class="task-duration text-gray-500 text-sm">Duration: 2 hours</span>
                            </div>
                        </div>

                        <!-- Task Item 3 -->
                        <div class="task-item border-bottom p-4">
                            <div class="d-flex justify-between align-center mb-2">
                                <h4 class="task-title font-semibold">Inverter Troubleshooting</h4>
                                <span class="task-status status-completed">
                                    <i class="fas fa-check-circle text-success mr-1"></i>Completed
                                </span>
                            </div>
                            <p class="task-customer text-secondary text-sm mb-2">Customer: Emma Davis</p>
                            <div class="d-flex justify-between align-center">
                                <span class="task-date text-gray-500 text-sm">Oct 16, 2025</span>
                                <span class="task-duration text-gray-500 text-sm">Duration: 1.5 hours</span>
                            </div>
                        </div>

                        <!-- Task Item 4 -->
                        <div class="task-item border-bottom p-4">
                            <div class="d-flex justify-between align-center mb-2">
                                <h4 class="task-title font-semibold">Panel Cleaning & Inspection</h4>
                                <span class="task-status status-in-progress">
                                    <i class="fas fa-spinner text-warning mr-1"></i>In Progress
                                </span>
                            </div>
                            <p class="task-customer text-secondary text-sm mb-2">Customer: Lisa Brown</p>
                            <div class="d-flex justify-between align-center">
                                <span class="task-date text-gray-500 text-sm">Oct 19, 2025</span>
                                <span class="task-duration text-gray-500 text-sm">Duration: In progress</span>
                            </div>
                        </div>

                        <!-- Task Item 5 -->
                        <div class="task-item p-4">
                            <div class="d-flex justify-between align-center mb-2">
                                <h4 class="task-title font-semibold">System Performance Review</h4>
                                <span class="task-status status-pending">
                                    <i class="fas fa-clock text-gray-400 mr-1"></i>Pending
                                </span>
                            </div>
                            <p class="task-customer text-secondary text-sm mb-2">Customer: Robert Taylor</p>
                            <div class="d-flex justify-between align-center">
                                <span class="task-date text-gray-500 text-sm">Oct 20, 2025</span>
                                <span class="task-duration text-gray-500 text-sm">Duration: Scheduled</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<?php
$config = [
    'modal_id' => 'deleteAgentModal',
    'title' => 'Confirm Delete',
    'icon' => 'fas fa-exclamation-triangle',
    'icon_color' => 'text-warning',
    'heading' => 'Delete Service Agent?',
    'message' => 'Are you sure you want to delete ',
    'subject' => 'John Doe',
    'message_suffix' => '? This action cannot be undone. All associated task data will be archived.',
    'confirm_text' => 'Delete Agent',
    'confirm_icon' => 'fas fa-check',
    'cancel_text' => 'Cancel',
    'cancel_icon' => 'fas fa-times',
    'confirm_action' => URLROOT . '/installeradmin/team/delete_agent/1',
    'confirm_method' => 'POST',
    'confirm_class' => 'btn-danger'
];
include __DIR__ . '/../../inc/models/confirmation_modal.php';
?>

