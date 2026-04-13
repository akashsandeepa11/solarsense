<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/installer_admin/agent_details.css">

<div class="agent-details-container">
    <?php
    $config = [
        'title' => 'Agent Details',
        'description' => 'View and manage service agent information',
        'show_back' => true,
        'back_url' => URLROOT . '/installeradmin/team',
        'back_label' => 'Back to Team'
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <div class="row gap-6">
        <div class="col-md-12">
            <div class="card mb-6">
                <div class="card-body">
                    <div class="agent-profile-header text-center mb-6">
                        <div class="agent-avatar-large">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($data['agent']->full_name); ?>&background=fe9630&color=fff&size=150"
                                alt="<?php echo $data['agent']->full_name; ?>">
                        </div>
                        <h2 class="text-2xl font-bold mt-4"><?php echo $data['agent']->full_name; ?></h2>
                        <div class="agent-role-badge">
                            <span class="badge bg-primary">Service Agent</span>
                        </div>
                        <div class="agent-status-badge mt-3">
                            <span class="status-badge status-<?php echo strtolower($data['agent']->status); ?>">
                                <i
                                    class="fas fa-circle <?php echo ($data['agent']->status == 'Active') ? 'text-success' : 'text-danger'; ?> mr-1"></i>
                                <?php echo $data['agent']->status; ?>
                            </span>
                        </div>
                    </div>

                    <div class="agent-info-grid mb-6">
                        <div class="info-item">
                            <label class="info-label">Email</label>
                            <p class="info-value"><?php echo $data['agent']->email; ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Contact Number</label>
                            <p class="info-value"><?php echo $data['agent']->contact; ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">NIC/ID</label>
                            <p class="info-value"><?php echo $data['agent']->nic; ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Address</label>
                            <p class="info-value"><?php echo $data['agent']->address; ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">District</label>
                            <p class="info-value"><?php echo $data['agent']->district; ?></p>
                        </div>
                    </div>

                    <div class="professional-info border-top pt-6">
                        <h3 class="text-lg font-semibold mb-4">Professional Information</h3>

                        <div class="info-item mb-4">
                            <label class="info-label">Specialization</label>
                            <p class="info-value">
                                <span class="badge bg-warning"><?php echo $data['agent']->specialization; ?></span>
                            </p>
                        </div>

                        <div class="info-item mb-4">
                            <label class="info-label">Experience</label>
                            <p class="info-value"><?php echo $data['agent']->experience_years; ?> years</p>
                        </div>

                        <div class="info-item mb-4">
                            <label class="info-label">Availability</label>
                            <p class="info-value">
                                <span class="badge bg-success"><?php echo $data['agent']->availability; ?></span>
                            </p>
                        </div>

                        <div class="info-item">
                            <label class="info-label">Certifications</label>
                            <p class="info-value">
                                <?php echo !empty($data['agent']->certifications) ? $data['agent']->certifications : 'No certifications listed'; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <a href="<?php echo URLROOT; ?>/installeradmin/team/edit_agent/<?php echo $data['agent']->user_id; ?>"
                            class="btn btn-sm btn-primary flex-1">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-danger flex-1"
                            onclick="showConfirmationModal('deleteAgentModal')">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$config = [
    'modal_id' => 'deleteAgentModal',
    'title' => 'Confirm Delete',
    'icon' => 'fas fa-exclamation-triangle',
    'icon_color' => 'text-warning',
    'heading' => 'Delete Service Agent?',
    'message' => 'Are you sure you want to delete ',
    'subject' => $data['agent']->full_name,
    'message_suffix' => '? This action cannot be undone. All associated task data will be archived.',
    'confirm_text' => 'Delete Agent',
    'confirm_icon' => 'fas fa-check',
    'cancel_text' => 'Cancel',
    'cancel_icon' => 'fas fa-times',
    'confirm_action' => URLROOT . '/installeradmin/team/delete_agent/' . $data['agent']->user_id,
    'confirm_method' => 'POST',
    'confirm_class' => 'btn-danger'
];
include __DIR__ . '/../../inc/models/confirmation_modal.php';
?>