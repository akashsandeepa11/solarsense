<?php
    $task_id= $data['task_id'];
?>

<div class="content-area" style="padding: 1.5rem;">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'Service Report',
        'description' => 'Complete the service report for task ' . $task_id,
        'show_back' => true,
        'back_url' => URLROOT . '/serviceagent/tasks',
        'back_label' => 'Back to Tasks'
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>
    
    <form action="<?php echo URLROOT; ?>/serviceagent/tasks/maintenance_report/<?php echo $task_id; ?>" method="POST">
        <input type="hidden" name="report_form_submitted" value="1">


        <!-- Service Details Section -->
        <div class="card shadow-sm rounded-xl mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-0 font-semibold d-flex align-items-center">
                    <i class="fas fa-tools text-primary mr-2"></i>
                    Service Details
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-12">
                        <?php
                        $textareaConfig = [
                            'id' => 'actions-taken',
                            'name' => 'actions_taken',
                            'label' => 'Actions Taken',
                            'value' => '',
                            'icon' => 'fas fa-list-check',
                            'editable' => true,
                            'required' => true,
                            'rows' => 4,
                            'placeholder' => 'Describe all actions performed during service...'
                        ];
                        include __DIR__ . '/../../inc/components/textarea_field.php';
                        ?>
                    </div>
                    <div class="col-12">
                        <?php
                        $textareaConfig = [
                            'id' => 'replaced-parts',
                            'name' => 'replaced_parts',
                            'label' => 'Replaced Parts',
                            'value' => '',
                            'icon' => 'fas fa-boxes',
                            'editable' => true,
                            'required' => false,
                            'rows' => 3,
                            'placeholder' => 'List any parts that were replaced...'
                        ];
                        include __DIR__ . '/../../inc/components/textarea_field.php';
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        $inputConfig = [
                            'id' => 'time-spent',
                            'name' => 'time_spent',
                            'label' => 'Time Spent (hours)',
                            'type' => 'number',
                            'value' => '',
                            'icon' => 'fas fa-clock',
                            'editable' => true,
                            'required' => true,
                            'inputClass' => 'step="0.5" min="0"'
                        ];
                        include __DIR__ . '/../../inc/components/input_field.php';
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        $inputConfig = [
                            'id' => 'completion-date',
                            'name' => 'completion_date',
                            'label' => 'Completion Date',
                            'type' => 'date',
                            'value' => date('Y-m-d'),
                            'icon' => 'fas fa-calendar-check',
                            'editable' => true,
                            'required' => true
                        ];
                        include __DIR__ . '/../../inc/components/input_field.php';
                        ?>
                    </div>
                    <div class="col-12">
                        <?php
                        $textareaConfig = [
                            'id' => 'technician-notes',
                            'name' => 'technician_notes',
                            'label' => 'Technician Notes',
                            'value' => '',
                            'icon' => 'fas fa-notes-medical',
                            'editable' => true,
                            'required' => false,
                            'rows' => 4,
                            'placeholder' => 'Add any additional notes or observations...'
                        ];
                        include __DIR__ . '/../../inc/components/textarea_field.php';
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Section -->
        <div class="card shadow-sm rounded-xl mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-0 font-semibold d-flex align-items-center">
                    <i class="fas fa-check-circle text-success mr-2"></i>
                    Verification
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <?php
                        $selectConfig = [
                            'id' => 'final-status',
                            'name' => 'final_status',
                            'label' => 'Final Status',
                            'value' => '',
                            'icon' => 'fas fa-flag-checkered',
                            'editable' => true,
                            'required' => true,
                            'placeholder' => 'Select final status',
                            'options' => [
                                'completed' => 'Completed Successfully',
                                'partial' => 'Partially Completed',
                                'pending-parts' => 'Pending Parts',
                                'requires-followup' => 'Requires Follow-up'
                            ]
                        ];
                        include __DIR__ . '/../../inc/components/select_field.php';
                        ?>
                    </div>
                   
                </div>

                <!-- Additional Information -->
                <div class="alert alert-info mt-4 border-0 rounded-lg" style="background-color: #e0f2fe;">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle text-info mt-1 mr-3"></i>
                        <div>
                            <strong class="d-block mb-1">Important:</strong>
                            <p class="mb-0 text-sm">Please ensure all service details are accurate before submitting. This report will be sent to the customer and stored in the system for future reference.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-end gap-3 mb-4">
            <a href="<?php echo URLROOT; ?>/serviceagent/tasks" class="btn btn-secondary px-4">
                <i class="fas fa-times mr-2"></i>Cancel
            </a>
            <button type="submit" name="submit_report" value="1" class="btn btn-success px-4">
                <i class="fas fa-paper-plane mr-2"></i>Submit Report
            </button>
        </div>
    </form>
</div>

<style>
.alert-info {
    padding: 1rem;
}

.alert-info i {
    font-size: 1.25rem;
}

/* Service form section spacing improvements */
.card-body .row {
    gap: 1.5rem 0;
    row-gap: 1.5rem;
}

.card-body [class*="col-"] {
    display: flex;
    flex-direction: column;
}

.card-body [class*="col-"] > * {
    width: 100%;
}

/* Ensure consistent spacing between all form fields */
.card-body [class*="col-"] {
    margin-bottom: 0;
}

/* Card styling consistency */
.card {
    border: 1px solid #e5e7eb;
}

.card-header h5 i {
    width: 24px;
}

/* Button styling */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-success {
    background-color: #22c55e;
    border-color: #22c55e;
}

.btn-success:hover {
    background-color: #16a34a;
    border-color: #16a34a;
}

.btn-secondary {
    background-color: #6b7280;
    border-color: #6b7280;
}

.btn-secondary:hover {
    background-color: #4b5563;
    border-color: #4b5563;
}

/* Responsive Design */
@media (max-width: 768px) {
    .content-area {
        padding: 1rem !important;
    }

    .card-header,
    .card-body {
        padding: 1rem !important;
    }

    .d-flex.gap-3 {
        flex-direction: column;
        width: 100%;
    }

    .d-flex.gap-3 .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

