<?php
/**
 * Item Detail View Page
 * Displays detailed information about a single inventory item
 */

// Sample item data (would come from controller/model)
$item = $data['item'] ?? [
    'id' => 46,
    'name' => 'Solar Panel 450W',
    'category' => 'Electrical',
    'quantity' => 34,
    'unit_price' => 10000.00,
    'opening_stock' => 40,
    'on_the_way' => 15,
    'threshold' => 12,
    'expiry_date' => '2027-04-13',
    'image' => null,
    'supplier_name' => 'SolarTech Solutions',
    'supplier_contact' => '011 234 5678',
];

// Purchase history sample data
$purchase_history = $data['purchase_history'] ?? [
    ['id' => 'PO-1001', 'date' => '2025-02-20', 'supplier' => 'SolarTech', 'qty' => 20, 'amount' => 'LKR 200,000'],
    ['id' => 'PO-985', 'date' => '2025-01-15', 'supplier' => 'SolarTech', 'qty' => 20, 'amount' => 'LKR 200,000'],
];

// Determine stock status
$stockStatus = $item['quantity'] <= $item['threshold'] ? 'low' : 'in';
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/inventory_manager/item_view.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components.css">

<div class="item-details-container">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => $item['name'],
        'description' => 'View and manage inventory item details',
        'show_back' => true,
        'back_url' => URLROOT . '/inventorymanager/inventory',
        'back_label' => 'Back to Inventory'
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <div class="row gap-6">
        <!-- Left Column: Item Profile & Info -->
        <div class="col-md-5">
            <!-- Item Profile Card -->
            <div class="card mb-6">
                <div class="card-body">
                    <!-- Item Image -->
                    <div class="item-profile-header text-center mb-6">
                        <div class="item-image-large">
                            <?php if (!empty($item['image'])): ?>
                                <img src="<?php echo URLROOT . '/img/inventory/' . $item['image']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <?php else: ?>
                                <i class="fas fa-box-open"></i>
                            <?php endif; ?>
                        </div>
                        <h2 class="text-2xl font-bold mt-4"><?php echo htmlspecialchars($item['name']); ?></h2>
                        <div class="item-category-badge">
                            <span class="badge bg-info"><?php echo htmlspecialchars($item['category']); ?></span>
                        </div>
                        <div class="item-status-badge mt-3">
                            <?php if ($stockStatus === 'low'): ?>
                                <span class="status-badge status-low-stock">
                                    <i class="fas fa-exclamation-circle mr-1"></i>Low Stock
                                </span>
                            <?php else: ?>
                                <span class="status-badge status-in-stock">
                                    <i class="fas fa-check-circle mr-1"></i>In Stock
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Item Info Grid -->
                    <div class="item-info-grid mb-6">
                        <div class="info-item">
                            <label class="info-label">Product ID</label>
                            <p class="info-value"><?php echo htmlspecialchars($item['id']); ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Product Category</label>
                            <p class="info-value"><?php echo htmlspecialchars($item['category']); ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Unit Price</label>
                            <p class="info-value">LKR <?php echo number_format($item['unit_price'], 2); ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Expiry Date</label>
                            <p class="info-value"><?php echo htmlspecialchars($item['expiry_date'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Threshold Value</label>
                            <p class="info-value text-error"><?php echo htmlspecialchars($item['threshold']); ?></p>
                        </div>
                    </div>

                    <!-- Supplier Info -->
                    <div class="border-top pt-6">
                        <h3 class="section-header">Supplier Details</h3>
                        
                        <div class="info-item">
                            <label class="info-label">Supplier Name</label>
                            <p class="info-value"><?php echo htmlspecialchars($item['supplier_name']); ?></p>
                        </div>

                        <div class="info-item">
                            <label class="info-label">Contact Number</label>
                            <p class="info-value"><?php echo htmlspecialchars($item['supplier_contact']); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Card Footer with Actions -->
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <button onclick="editItem(<?php echo $item['id']; ?>)" class="btn btn-sm btn-primary flex-1">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </button>
                        <button onclick="downloadItem(<?php echo $item['id']; ?>)" class="btn btn-sm btn-secondary flex-1">
                            <i class="fas fa-download mr-2"></i> Download
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Stats & Activity -->
        <div class="col-md-7">
            <!-- Stock Progress -->
            <?php 
            $openingStock = $item['opening_stock'] ?? 0;
            $remainingStock = $item['quantity'] ?? 0;
            $percentage = $openingStock > 0 ? round(($remainingStock / $openingStock) * 100) : 0;
            $progressColor = $percentage > 50 ? 'success' : ($percentage > 25 ? 'warning' : 'error');
            ?>
            <div class="stock-progress-card card mb-6">
                <div class="card-body">
                    <div class="stock-progress-header">
                        <span class="stock-label">Stock Level</span>
                        <span class="stock-percentage text-<?php echo $progressColor; ?>"><?php echo $percentage; ?>%</span>
                    </div>
                    <div class="stock-progress-bar">
                        <div class="stock-progress-fill bg-<?php echo $progressColor; ?>" style="width: <?php echo $percentage; ?>%"></div>
                    </div>
                    <div class="stock-progress-details">
                        <div class="stock-detail">
                            <span class="detail-value"><?php echo $remainingStock; ?></span>
                            <span class="detail-label">Remaining</span>
                        </div>
                        <div class="stock-detail">
                            <span class="detail-value"><?php echo $openingStock; ?></span>
                            <span class="detail-label">Opening</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purchases Card -->
            <div class="card">
                <div class="card-header border-bottom">
                    <h3 class="card-title">Purchases</h3>
                </div>
                <div class="card-body p-0">
                    <?php
                    $config = [
                        'headers' => [
                            ['key' => 'id', 'label' => 'PO Number'],
                            ['key' => 'date', 'label' => 'Date'],
                            ['key' => 'supplier', 'label' => 'Supplier'],
                            ['key' => 'qty', 'label' => 'Qty'],
                            ['key' => 'amount', 'label' => 'Amount'],
                        ],
                        'rows' => $purchase_history,
                        'actions' => [
                            ['label' => 'View', 'icon' => 'fas fa-eye', 'url' => URLROOT . '/inventorymanager/purchases/{id}', 'class' => 'btn-sm btn-info'],
                        ],
                        'empty_message' => 'No purchase history found'
                    ];
                    include __DIR__ . '/../../inc/components/data_table.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editItem(id) {
    window.location.href = '<?php echo URLROOT; ?>/inventorymanager/inventory?edit=' + id;
}

function downloadItem(id) {
    // Download item details as PDF
    alert('Download functionality will be implemented');
}
</script>
