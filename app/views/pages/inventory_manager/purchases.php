<?php
/**
 * Purchases Management Page
 * Manage purchase orders and procurement
 */

// Sample purchase orders data
$purchase_orders = [
    ['id' => 'PO-1001', 'supplier' => 'SolarTech Solutions', 'items' => 5, 'total' => 'LKR 450,000', 'date' => '2025-02-25', 'status' => 'pending'],
    ['id' => 'PO-1002', 'supplier' => 'Battery World', 'items' => 3, 'total' => 'LKR 180,000', 'date' => '2025-02-24', 'status' => 'approved'],
    ['id' => 'PO-1003', 'supplier' => 'Power Solutions', 'items' => 8, 'total' => 'LKR 320,000', 'date' => '2025-02-22', 'status' => 'delivered'],
    ['id' => 'PO-1004', 'supplier' => 'ABC Electricals', 'items' => 2, 'total' => 'LKR 85,000', 'date' => '2025-02-20', 'status' => 'completed'],
    ['id' => 'PO-1005', 'supplier' => 'GreenEnergy Supplies', 'items' => 4, 'total' => 'LKR 210,000', 'date' => '2025-02-18', 'status' => 'cancelled'],
];

// Purchase stats
$purchase_stats = [
    ['label' => 'Total Orders (Month)', 'value' => '24', 'icon' => 'fas fa-file-invoice', 'color' => 'primary'],
    ['label' => 'Pending Approval', 'value' => '5', 'icon' => 'fas fa-clock', 'color' => 'warning'],
    ['label' => 'In Transit', 'value' => '8', 'icon' => 'fas fa-truck', 'color' => 'accent'],
    ['label' => 'Total Spent (Month)', 'value' => 'LKR 2.1M', 'icon' => 'fas fa-coins', 'color' => 'success'],
];
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components.css">

<div class="content-area">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'Purchase Orders',
        'description' => 'Manage purchase orders and procurement',
        'buttons' => [
            ['label' => 'New Purchase Order', 'url' => '#', 'icon' => 'fas fa-plus', 'class' => 'btn-primary', 'onclick' => 'showCreateOrderModal()'],
        ]
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Stats Cards -->
    <?php
    $config = [
        'stats' => $purchase_stats,
        'columns' => 4
    ];
    include __DIR__ . '/../../inc/components/stat_card.php';
    ?>

    <!-- Filter Bar -->
    <div class="card shadow-lg rounded-xl mb-4 mt-6">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-4 align-center">
                <input type="text" id="searchInput" placeholder="Search by PO number or supplier..." class="form-control" style="max-width: 300px;">
                <select id="supplierFilter" class="form-control" style="max-width: 200px;">
                    <option value="all">All Suppliers</option>
                    <option value="solartech">SolarTech Solutions</option>
                    <option value="battery">Battery World</option>
                    <option value="power">Power Solutions</option>
                </select>
                <select id="statusFilter" class="form-control" style="max-width: 180px;">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="delivered">Delivered</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <button class="btn btn-primary" onclick="filterOrders()">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Purchase Orders Table -->
    <div class="d-flex justify-between align-center mb-4 mt-6">
        <h3 class="text-xl font-semibold">Purchase Orders</h3>
        <button class="btn btn-sm btn-secondary" onclick="exportOrders()">
            <i class="fas fa-download mr-2"></i>Export
        </button>
    </div>

    <?php
    $config = [
        'headers' => [
            ['key' => 'id', 'label' => 'PO Number'],
            ['key' => 'supplier', 'label' => 'Supplier'],
            ['key' => 'items', 'label' => 'Items'],
            ['key' => 'total', 'label' => 'Total'],
            ['key' => 'date', 'label' => 'Date'],
            ['key' => 'status', 'label' => 'Status'],
        ],
        'rows' => $purchase_orders,
        'columns' => [
            [
                'key' => 'id',
                'render' => function($row) {
                    return '<span class="font-medium text-primary">' . htmlspecialchars($row['id']) . '</span>';
                }
            ],
            [
                'key' => 'items',
                'render' => function($row) {
                    return $row['items'] . ' items';
                }
            ],
            [
                'key' => 'total',
                'render' => function($row) {
                    return '<span class="font-semibold">' . htmlspecialchars($row['total']) . '</span>';
                }
            ],
            [
                'key' => 'status',
                'render' => function($row) {
                    $statusConfig = match($row['status']) {
                        'pending' => ['class' => 'bg-warning', 'label' => 'Pending'],
                        'approved' => ['class' => 'bg-accent', 'label' => 'Approved'],
                        'delivered' => ['class' => 'bg-primary', 'label' => 'Delivered'],
                        'completed' => ['class' => 'bg-success', 'label' => 'Completed'],
                        'cancelled' => ['class' => 'bg-error', 'label' => 'Cancelled'],
                        default => ['class' => 'bg-secondary', 'label' => ucfirst($row['status'])]
                    };
                    return '<span class="badge ' . $statusConfig['class'] . ' text-surface px-3 py-1 rounded-full text-xs">' . $statusConfig['label'] . '</span>';
                }
            ],
        ],
        'actions' => [
            ['label' => 'View', 'icon' => 'fas fa-eye', 'url' => URLROOT . '/inventorymanager/purchases/view/{id}', 'class' => 'btn-sm btn-info'],
            ['label' => 'Approve', 'icon' => 'fas fa-check', 'url' => URLROOT . '/inventorymanager/purchases/approve/{id}', 'class' => 'btn-sm btn-success'],
        ],
        'empty_message' => 'No purchase orders found'
    ];
    include __DIR__ . '/../../inc/components/data_table.php';
    ?>
</div>

<!-- Create Order Modal -->
<div id="createOrderModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 700px;">
        <h3 class="text-xl font-semibold mb-4">Create Purchase Order</h3>
        <form id="createOrderForm" method="POST" action="<?php echo URLROOT; ?>/inventorymanager/create_purchase">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-control" required>
                            <option value="">-- Select Supplier --</option>
                            <option value="1">SolarTech Solutions</option>
                            <option value="2">Battery World</option>
                            <option value="3">Power Solutions</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Expected Delivery Date</label>
                        <input type="date" name="expected_date" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Items</label>
                <div id="orderItems">
                    <div class="d-flex gap-4 mb-2 align-center">
                        <select name="items[0][item_id]" class="form-control" style="flex: 2;">
                            <option value="">Select Item</option>
                        </select>
                        <input type="number" name="items[0][quantity]" class="form-control" placeholder="Qty" min="1" style="flex: 1;">
                        <input type="number" name="items[0][unit_price]" class="form-control" placeholder="Unit Price" step="0.01" style="flex: 1;">
                        <button type="button" class="btn btn-sm btn-error" onclick="removeOrderItem(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addOrderItem()">
                    <i class="fas fa-plus mr-2"></i>Add Item
                </button>
            </div>

            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>

            <div class="d-flex justify-end gap-4 mt-4">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createOrderModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Order</button>
            </div>
        </form>
    </div>
</div>

<script>
let itemIndex = 1;

function showCreateOrderModal() {
    document.getElementById('createOrderModal').style.display = 'flex';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function addOrderItem() {
    const container = document.getElementById('orderItems');
    const itemHtml = `
        <div class="d-flex gap-4 mb-2 align-center">
            <select name="items[${itemIndex}][item_id]" class="form-control" style="flex: 2;">
                <option value="">Select Item</option>
            </select>
            <input type="number" name="items[${itemIndex}][quantity]" class="form-control" placeholder="Qty" min="1" style="flex: 1;">
            <input type="number" name="items[${itemIndex}][unit_price]" class="form-control" placeholder="Unit Price" step="0.01" style="flex: 1;">
            <button type="button" class="btn btn-sm btn-error" onclick="removeOrderItem(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', itemHtml);
    itemIndex++;
}

function removeOrderItem(btn) {
    btn.closest('.d-flex').remove();
}

function filterOrders() {
    console.log('Filtering orders...');
}

function exportOrders() {
    alert('Export functionality - To be implemented');
}

function viewOrder(id) {
    alert('View order details: ' + id);
}

function approveOrder(id) {
    if (confirm('Approve this purchase order?')) {
        console.log('Approving order:', id);
    }
}

function cancelOrder(id) {
    if (confirm('Cancel this purchase order?')) {
        console.log('Cancelling order:', id);
    }
}

function receiveOrder(id) {
    if (confirm('Mark this order as received?')) {
        console.log('Receiving order:', id);
    }
}
</script>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: #fff;
    padding: 2rem;
    border-radius: 1rem;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 9999px;
}
</style>
