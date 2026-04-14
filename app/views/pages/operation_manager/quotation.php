<?php
// --- PHP Setup for Data ---
$quotations = isset($data['quotations']) ? $data['quotations'] : [];

// Summary Card Data
$pending = count(array_filter($quotations, fn($q) => $q['status'] === 'Pending'));
$approved = count(array_filter($quotations, fn($q) => $q['status'] === 'Approved'));

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

    /* Agent Details Layout */
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
    .agent-id {
        font-size: 0.85rem;
        color: #6b7280;
    }
</style>

<div class="container-fluid p-8">
    <?php
    $config = [
        'title' => 'Quotation Management',
        'description' => 'Manage system quotations for your clients.',
        'buttons' => [
            [
                'label' => 'Create New Quotation',
                'url' => '#',
                'icon' => 'fas fa-plus',
                'class' => 'btn-primary btn-md',
                'onclick' => 'onclick="showAddModal()"'
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
                    return '<div class="d-flex align-center gap-3">
                                <img src="' . getAvatarUrl($row['customer']) . '" alt="' . htmlspecialchars($row['customer']) . '" style="width: 40px; border-radius: 50%;">
                                <div class="agent-details">
                                    <div class="agent-name">' . htmlspecialchars($row['customer']) . '</div>
                                    <div class="agent-id text-xs">' . htmlspecialchars($row['id']) . '</div>
                                </div>
                            </div>';
                }
            ],
            [
                'key' => 'status',
                'render' => function ($row) {
                    return '<div class="d-flex align-center">
                                <span class="status-dot ' . getStatusClass($row['status']) . ' mr-2"></span>
                                <span class="badge ' . ($row['status'] === 'Pending' ? 'badge-warning' : 'badge-success') . '">' . $row['status'] . '</span>
                            </div>';
                }
            ]
        ],
        'actions' => [
            [
                'label' => 'View',
                'icon' => 'fas fa-eye',
                'url' => URLROOT . '/operationmanager/quotation/details/{id}',
                'class' => 'btn-sm btn-info'
            ],
            [
                'label' => 'Accept',
                'icon' => 'fas fa-check',
                'class' => 'btn-sm btn-success',
                'onclick' => 'onclick="acceptQuotation(\'{id}\')"',
                'condition' => function($row) { return $row['status'] === 'Pending'; }
            ],
            [
                'label' => 'Delete',
                'icon' => 'fas fa-trash',
                'class' => 'btn-icon-danger',
                'onclick' => 'onclick="openDeleteModal(\'{id}\')"'
            ]
        ],
        'empty_message' => 'No quotations found.'
    ];

    include __DIR__ . '/../../inc/components/data_table.php';
    ?>
</div>

<script>
    function showAddModal() { /* Implement existing add modal trigger */ }

    function acceptQuotation(id) {
        if (confirm('Approve this quotation?')) {
            // Add your update logic here
            window.location.href = '<?php echo URLROOT; ?>/operationmanager/quotation/approve/' + id;
        }
    }

    function openDeleteModal(id) {
        if (confirm('Delete this quotation?')) {
            // Add your delete logic here
            window.location.href = '<?php echo URLROOT; ?>/operationmanager/quotation/delete/' + id;
        }
    }
</script>