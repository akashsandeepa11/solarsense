<?php
// --- PHP Setup for Data ---
$quotations = isset($data['quotations']) ? $data['quotations'] : [];

// Summary Card Data
$pending = count(array_filter($quotations, fn($q) => ($q['status'] ?? '') === 'Pending'));
$approved = count(array_filter($quotations, fn($q) => ($q['status'] ?? '') === 'Approved'));

$summary_cards = [
    ['label' => 'Pending Quotations', 'value' => $pending, 'icon' => 'fas fa-clock', 'color' => 'warning'],
    ['label' => 'Approved Quotations', 'value' => $approved, 'icon' => 'fas fa-check-circle', 'color' => 'success'],
    ['label' => 'Total Quotations', 'value' => count($quotations), 'icon' => 'fas fa-file-invoice-dollar', 'color' => 'primary']
];

// Function to determine the status dot color
function getStatusClass($status)
{
    switch ($status) {
        case 'Approved':
            return 'bg-success';
        case 'Pending':
            return 'bg-warning';
        default:
            return 'bg-secondary';
    }
}
?>

<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/components.css">
<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/pages/installer/dashboard.css">

<style>
    /* Status Dot Styling */
    .status-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .status-dot.bg-success { background-color: #22c55e !important; }
    .status-dot.bg-warning { background-color: #f59e0b !important; }
    .status-dot.bg-secondary { background-color: #9ca3af !important; }

    .agent-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        min-width: 0;
    }
    .agent-name {
        font-size: 0.95rem;
        color: #212121;
        font-weight: 600;
    }
</style>

<div class="container-fluid p-8">
    <?php
    $config = [
        'title'       => 'Quotation Management',
        'description' => 'Manage system quotations for your clients.',
        'buttons'     => [
            [
                'label'   => 'Download PDF',
                'icon'    => 'fas fa-file-pdf',
                'class'   => 'btn-outline-primary btn-md',
                'onclick' => 'onclick="SolarSenseReport.download({tableSelector:\'.data-table\',title:\'Quotation Management Report\',subtitle:\'Client quotations overview\',columns:[\'Customer Name\',\'Email\',\'Date\',\'Contact\',\'Address\',\'Status\']},this)"'
            ]
        ]
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <?php
    $config = [
        'search' => [
            'id' => 'searchQuotations',
            'name' => 'search',
            'label' => 'Search Quotations',
            'placeholder' => 'Search by customer or ID...'
        ],
        'filters' => [
            [
                'id' => 'filterStatus',
                'name' => 'status',
                'label' => 'Status Filter',
                'options' => [
                    ['value' => '', 'label' => 'All Status'],
                    ['value' => 'Pending', 'label' => 'Pending'],
                    ['value' => 'Approved', 'label' => 'Approved']
                ]
            ]
        ],
        'form_action' => URLROOT . '/operationmanager/quotation',
        'form_method' => 'GET',
        'auto_submit' => true,
        'reset_on_clear' => true
    ];
    include __DIR__ . '/../../inc/components/filter_bar.php';
    ?>

    <?php
    $config = [
        'stats' => $summary_cards,
        'columns' => 4
    ];
    include __DIR__ . '/../../inc/components/stat_card.php';
    ?>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Contact Number</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($quotations)): ?>
                            <tr><td colspan="7" class="text-center p-6 text-secondary">No quotations found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($quotations as $row): ?>
                                <?php
                                $qid    = $row['id'] ?? 'N/A';
                                $name   = $row['customer'] ?? 'Unknown';
                                $email  = $row['email'] ?? '';
                                $date   = $row['date'] ?? '';
                                $phone  = $row['contact'] ?? '';
                                $addr   = $row['address'] ?? '';
                                $status = $row['status'] ?? 'Pending';
                                ?>
                                <tr class="data-table-row">
                                    <td>
                                        <div class="d-flex align-center gap-3">
                                            <img src="<?php echo getAvatarUrl($name); ?>" alt="<?php echo htmlspecialchars($name); ?>" style="width:40px;border-radius:50%;">
                                            <div class="agent-details">
                                                <div class="agent-name"><?php echo htmlspecialchars($name); ?></div>
                                                <div class="agent-id text-xs">ID: <?php echo htmlspecialchars($qid); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($email); ?></td>
                                    <td><?php echo htmlspecialchars($date); ?></td>
                                    <td><?php echo htmlspecialchars($phone); ?></td>
                                    <td><?php echo htmlspecialchars($addr); ?></td>
                                    <td>
                                        <div class="d-flex align-center">
                                            <span class="status-dot <?php echo getStatusClass($status); ?> mr-2"></span>
                                            <span class="badge <?php echo $status === 'Pending' ? 'badge-warning' : 'badge-success'; ?>"><?php echo $status; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="actions-menu d-flex gap-2 justify-center">
                                            <button class="btn-icon btn-sm btn-info" title="View" onclick="viewQuotationDetails(<?php echo (int)$qid; ?>)"><i class="fas fa-eye"></i></button>
                                            <?php if ($status === 'Pending'): ?>
                                            <button class="btn-icon btn-sm btn-success" title="Accept" onclick="acceptQuotation(<?php echo (int)$qid; ?>)"><i class="fas fa-check"></i></button>
                                            <?php endif; ?>
                                            <button class="btn-icon btn-icon-danger" title="Delete" onclick="openDeleteModal(<?php echo (int)$qid; ?>)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="viewQuotationModal" class="custom-modal" style="display: none;">
    <div class="modal-overlay" onclick="closeQuotationModal()"></div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-invoice-dollar text-primary mr-2"></i>Quotation Details</h5>
                <button type="button" class="btn-close" onclick="closeQuotationModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h6 class="text-secondary font-bold uppercase text-xs mb-3" style="letter-spacing: 0.05em;">Customer Information</h6>
                        <p class="mb-2 text-sm"><strong>Name:</strong> <span id="modal-customer"></span></p>
                        <p class="mb-2 text-sm"><strong>Email:</strong> <span id="modal-email"></span></p>
                        <p class="mb-2 text-sm"><strong>Phone:</strong> <span id="modal-contact"></span></p>
                        <p class="mb-2 text-sm"><strong>Address:</strong> <span id="modal-address"></span></p>
                    </div>
                    <div class="col-md-6 mb-4">
                        <h6 class="text-secondary font-bold uppercase text-xs mb-3" style="letter-spacing: 0.05em;">System Preferences</h6>
                        <p class="mb-2 text-sm"><strong>Bill Range:</strong> <span id="modal-bill"></span></p>
                        <p class="mb-2 text-sm"><strong>Roof Type:</strong> <span id="modal-roof"></span></p>
                        <p class="mb-2 text-sm"><strong>Property Type:</strong> <span id="modal-property"></span></p>
                        <p class="mb-2 text-sm"><strong>Existing System:</strong> <span id="modal-existing"></span></p>
                    </div>
                </div>
                <div class="border-top pt-3 d-flex justify-between align-center">
                    <span class="text-xs text-secondary">Submission Date: <span id="modal-date"></span></span>
                    <span id="modal-status-badge" class="badge"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeQuotationModal()">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Critical: Initialize the data variable for JS
    const quotationData = <?php echo json_encode($quotations); ?>;

    function viewQuotationDetails(id) {
        // Find the record in the localized data array
        const quote = quotationData.find(q => q.id == id);
        if (!quote) return;

        // Populate Modal Fields
        document.getElementById('modal-customer').textContent = quote.customer || 'N/A';
        document.getElementById('modal-email').textContent = quote.email || 'N/A';
        document.getElementById('modal-contact').textContent = quote.contact || 'N/A';
        document.getElementById('modal-address').textContent = quote.address || 'N/A';
        document.getElementById('modal-bill').textContent = formatLabel(quote.bill_range);
        document.getElementById('modal-roof').textContent = formatLabel(quote.roof_type);
        document.getElementById('modal-property').textContent = formatLabel(quote.property_type);
        document.getElementById('modal-existing').textContent = quote.existing_system || 'No';
        document.getElementById('modal-date').textContent = quote.date || 'N/A';

        // Set status badge styling
        const badge = document.getElementById('modal-status-badge');
        badge.textContent = quote.status;
        badge.className = 'badge ' + (quote.status === 'Pending' ? 'badge-warning' : 'badge-success');

        // Trigger the standard modal show function
        showConfirmationModal('viewQuotationModal');
    }

    function closeQuotationModal() {
        hideConfirmationModal('viewQuotationModal');
    }

    function acceptQuotation(id) {
        if (confirm('Approve this quotation?')) {
            window.location.href = '<?php echo URLROOT; ?>/operationmanager/quotation/approve/' + id;
        }
    }

    function openDeleteModal(id) {
        if (confirm('Delete this quotation?')) {
            window.location.href = '<?php echo URLROOT; ?>/operationmanager/quotation/delete/' + id;
        }
    }

    // Helper to clean up database technical strings (e.g. 'very-high' to 'Very High')
    function formatLabel(token) {
        if (!token) return "N/A";
        return token.toString().replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }
</script>

<?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>