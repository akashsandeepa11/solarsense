<?php
$order_id = $data['order_id'];
$order    = $data['order'];
$items    = $order->items ?? [];
?>

<div class="content-area" style="padding: 1.5rem;">
    <!-- Page Header -->
    <?php
    $config = [
        'title'       => 'Installation Report — Order #' . $order_id,
        'description' => 'Complete the installation report for this job',
        'show_back'   => true,
        'back_url'    => URLROOT . '/serviceagent/deliveries',
        'back_label'  => 'Back to Installation Tasks'
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Order / Installation Summary Card -->
    <div class="card shadow-sm rounded-xl mb-4">
        <div class="card-header bg-white border-0 p-4">
            <h5 class="mb-0 font-semibold d-flex align-items-center">
                <i class="fas fa-solar-panel text-primary mr-2"></i>Items to Install
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="text-secondary text-sm mb-1">Order #</label>
                    <p class="font-semibold mb-0">#<?php echo $order_id; ?></p>
                </div>
                <div class="col-md-4">
                    <label class="text-secondary text-sm mb-1">Customer</label>
                    <p class="font-semibold mb-0"><?php echo htmlspecialchars($order->customer_name ?? '—'); ?></p>
                </div>
                <div class="col-md-4">
                    <label class="text-secondary text-sm mb-1">Order Date</label>
                    <p class="font-semibold mb-0"><?php echo $order->date ? date('M d, Y', strtotime($order->date)) : '—'; ?></p>
                </div>
                <div class="col-md-4">
                    <label class="text-secondary text-sm mb-1">Total Amount</label>
                    <p class="font-semibold mb-0">LKR <?php echo number_format($order->total_amount ?? 0, 2); ?></p>
                </div>
            </div>

            <!-- Items Table -->
            <?php if (!empty($items)): ?>
            <h6 class="font-semibold mb-3"><i class="fas fa-list mr-2 text-primary"></i>Items to Install on Solar System</h6>
            <div class="table-responsive">
                <table class="data-table" style="font-size:0.9rem;">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item->item_name); ?></td>
                            <td class="text-center"><?php echo $item->quantity; ?></td>
                            <td class="text-right">LKR <?php echo number_format($item->unit_price, 2); ?></td>
                            <td class="text-right">LKR <?php echo number_format($item->line_total, 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right font-semibold" style="padding-right:1rem;">Total</td>
                            <td class="text-right font-semibold">LKR <?php echo number_format($order->total_amount ?? 0, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Report Form -->
    <form action="<?php echo URLROOT; ?>/serviceagent/deliveries/delivery_report/<?php echo $order_id; ?>" method="POST">
        <input type="hidden" name="delivery_report_submitted" value="1">

        <!-- Installation Details Section -->
        <div class="card shadow-sm rounded-xl mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-0 font-semibold d-flex align-items-center">
                    <i class="fas fa-screwdriver-wrench text-primary mr-2"></i>Installation Details
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-12">
                        <?php
                        $textareaConfig = [
                            'id'          => 'actions-taken',
                            'name'        => 'actions_taken',
                            'label'       => 'Installation Steps Taken',
                            'value'       => $data['actions_taken'],
                            'icon'        => 'fas fa-list-check',
                            'editable'    => true,
                            'required'    => true,
                            'rows'        => 4,
                            'placeholder' => 'Describe all installation steps performed on the solar system...',
                            'error'       => $data['actions_taken_err'] ?? ''
                        ];
                        include __DIR__ . '/../../inc/components/textarea_field.php';
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        $inputConfig = [
                            'id'         => 'time-spent',
                            'name'       => 'time_spent',
                            'label'      => 'Time Spent (hours)',
                            'type'       => 'number',
                            'value'      => $data['time_spent'],
                            'icon'       => 'fas fa-clock',
                            'editable'   => true,
                            'required'   => true,
                            'inputClass' => 'step="0.5" min="0"',
                            'error'      => $data['time_spent_err'] ?? ''
                        ];
                        include __DIR__ . '/../../inc/components/input_field.php';
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        $inputConfig = [
                            'id'       => 'completion-date',
                            'name'     => 'completion_date',
                            'label'    => 'Completion Date',
                            'type'     => 'date',
                            'value'    => $data['completion_date'],
                            'icon'     => 'fas fa-calendar-check',
                            'editable' => true,
                            'required' => true,
                            'error'    => $data['completion_date_err'] ?? ''
                        ];
                        include __DIR__ . '/../../inc/components/input_field.php';
                        ?>
                    </div>
                    <div class="col-12">
                        <?php
                        $textareaConfig = [
                            'id'          => 'technician-notes',
                            'name'        => 'technician_notes',
                            'label'       => 'Technician Notes',
                            'value'       => $data['technician_notes'],
                            'icon'        => 'fas fa-notes-medical',
                            'editable'    => true,
                            'required'    => false,
                            'rows'        => 3,
                            'placeholder' => 'Add any notes about the installation, issues encountered, or observations...'
                        ];
                        include __DIR__ . '/../../inc/components/textarea_field.php';
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification / Status Section -->
        <div class="card shadow-sm rounded-xl mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h5 class="mb-0 font-semibold d-flex align-items-center">
                    <i class="fas fa-check-circle text-success mr-2"></i>Installation Status
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <?php
                        $selectConfig = [
                            'id'          => 'final-status',
                            'name'        => 'final_status',
                            'label'       => 'Installation Status',
                            'value'       => $data['final_status'],
                            'icon'        => 'fas fa-flag-checkered',
                            'editable'    => true,
                            'required'    => true,
                            'placeholder' => 'Select installation status',
                            'options'     => [
                                'installed'         => 'Installed Successfully',
                                'partial'           => 'Partially Installed',
                                'pending-parts'     => 'Waiting for Parts',
                                'requires-followup' => 'Requires Follow-up',
                                'failed'            => 'Installation Failed',
                            ],
                            'error'       => $data['final_status_err'] ?? ''
                        ];
                        include __DIR__ . '/../../inc/components/select_field.php';
                        ?>
                    </div>
                </div>

                <div class="alert alert-info mt-4 border-0 rounded-lg" style="background-color: #e0f2fe;">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle text-info mt-1 mr-3"></i>
                        <div>
                            <strong class="d-block mb-1">Important:</strong>
                            <p class="mb-0 text-sm">Please ensure all installation details are accurate before submitting. Submitting this report will mark the order as completed and update the homeowner's solar system records.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-end gap-3 mb-4">
            <a href="<?php echo URLROOT; ?>/serviceagent/deliveries" class="btn btn-secondary px-4">
                <i class="fas fa-times mr-2"></i>Cancel
            </a>
            <button type="submit" class="btn btn-success px-4">
                <i class="fas fa-paper-plane mr-2"></i>Submit Installation Report
            </button>
        </div>
    </form>
</div>

<style>
.alert-info { padding: 1rem; }
.alert-info i { font-size: 1.25rem; }
.card-body .row { gap: 1.5rem 0; row-gap: 1.5rem; }
.card-body [class*="col-"] { display: flex; flex-direction: column; }
.card-body [class*="col-"] > * { width: 100%; }
.card { border: 1px solid #e5e7eb; }
.card-header h5 i { width: 24px; }
.btn { display: inline-flex; align-items: center; gap: .5rem; padding: .625rem 1.5rem; border-radius: .5rem; font-weight: 500; transition: all .2s; }
.btn-success { background-color: #22c55e; border-color: #22c55e; }
.btn-success:hover { background-color: #16a34a; border-color: #16a34a; }
.btn-secondary { background-color: #6b7280; border-color: #6b7280; }
.btn-secondary:hover { background-color: #4b5563; border-color: #4b5563; }
.text-right { text-align: right; }
tfoot td { padding: .75rem; border-top: 2px solid #e5e7eb; }
@media(max-width:768px) {
    .content-area { padding: 1rem !important; }
    .card-header, .card-body { padding: 1rem !important; }
    .d-flex.gap-3 { flex-direction: column; width: 100%; }
    .d-flex.gap-3 .btn { width: 100%; justify-content: center; }
}
</style>
