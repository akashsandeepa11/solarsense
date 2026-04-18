<?php $report = $data['report']; ?>
<div class="content-area" style="padding: 1.5rem;">
    <?php
    $config = [
        'title' => 'Service Report Detail',
        'description' => 'Viewing report for Task #' . $report->task_id . ' completed by ' . $report->agent_name,
        'show_back' => true,
        'back_url' => URLROOT . '/operationmanager/maintenance/reports',
        'back_label' => 'Back to Reports'
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <div class="card shadow-sm rounded-xl mb-4">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-12">
                    <?php
                    $textareaConfig = [
                        'label' => 'Actions Taken', 'value' => $report->actions_taken,
                        'icon' => 'fas fa-list-check', 'editable' => false, 'rows' => 4
                    ];
                    include __DIR__ . '/../../inc/components/textarea_field.php';
                    ?>
                </div>
                <div class="col-12">
                    <?php
                    $textareaConfig = [
                        'label' => 'Replaced Parts', 'value' => $report->replaced_parts ?: 'None',
                        'icon' => 'fas fa-boxes', 'editable' => false, 'rows' => 3
                    ];
                    include __DIR__ . '/../../inc/components/textarea_field.php';
                    ?>
                </div>
                <div class="col-md-6">
                    <?php
                    $inputConfig = [
                        'label' => 'Time Spent (hours)', 'value' => $report->time_spent,
                        'icon' => 'fas fa-clock', 'editable' => false
                    ];
                    include __DIR__ . '/../../inc/components/input_field.php';
                    ?>
                </div>
                <div class="col-md-6">
                    <?php
                    $inputConfig = [
                        'label' => 'Completion Date', 'value' => $report->completion_date,
                        'icon' => 'fas fa-calendar-check', 'editable' => false
                    ];
                    include __DIR__ . '/../../inc/components/input_field.php';
                    ?>
                </div>
                <div class="col-12">
                    <?php
                    $textareaConfig = [
                        'label' => 'Technician Notes', 'value' => $report->technician_notes ?: 'No additional notes.',
                        'icon' => 'fas fa-notes-medical', 'editable' => false, 'rows' => 3
                    ];
                    include __DIR__ . '/../../inc/components/textarea_field.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>