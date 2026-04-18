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

    <?php
    $config = [
        'headers' => [
            ['key' => 'customer', 'label' => 'Customer Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'date', 'label' => 'Date'],
            ['key' => 'contact', 'label' => 'Contact Number'],
            ['key' => 'address', 'label' => 'Address'],
            ['key' => 'status', 'label' => 'Status']
        ],
        'rows' => $quotations,
        'columns' => [
            [
                'key' => 'customer',
                'render' => function ($row) {
                    $name = $row['customer'] ?? 'Unknown';
                    $id = $row['id'] ?? 'N/A';
                    return '<div class="d-flex align-center gap-3">
                                <img src="' . getAvatarUrl($name) . '" alt="' . htmlspecialchars($name) . '" style="width: 40px; border-radius: 50%;">
                                <div class="agent-details">
                                    <div class="agent-name">' . htmlspecialchars($name) . '</div>
                                    <div class="agent-id text-xs">ID: ' . htmlspecialchars($id) . '</div>
                                </div>
                            </div>';
                }
            ],
            [
                'key' => 'status',
                'render' => function ($row) {
                    $status = $row['status'] ?? 'Pending';
                    return '<div class="d-flex align-center">
                                <span class="status-dot ' . getStatusClass($status) . ' mr-2"></span>
                                <span class="badge ' . ($status === 'Pending' ? 'badge-warning' : 'badge-success') . '">' . $status . '</span>
                            </div>';
                }
            ]
        ],
        'actions' => [
            [
                'label' => 'View',
                'icon' => 'fas fa-eye',
                // This must match the JS function name
                'onclick' => 'onclick="viewQuotationDetails({id})"',
                'class' => 'btn-sm btn-info'
            ],
            [
                'label' => 'Accept',
                'icon' => 'fas fa-check',
                'class' => 'btn-sm btn-success',
                'onclick' => 'onclick="acceptQuotation({id})"',
                'condition' => function ($row) {
                    return ($row['status'] ?? '') === 'Pending';
                }
            ],
            [
                'label' => 'Delete',
                'icon' => 'fas fa-trash',
                'class' => 'btn-icon-danger',
                'onclick' => 'onclick="openDeleteModal({id})"'
            ]
        ],
        'empty_message' => 'No quotations found.'
    ];

    include __DIR__ . '/../../inc/components/data_table.php';
    ?>
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