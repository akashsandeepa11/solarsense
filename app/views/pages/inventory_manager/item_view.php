<?php
/**
 * Item Detail View Page
 * Displays detailed information about a single inventory item
 */

$item = $data['item'] ?? null;

if (!$item) {
    redirect('inventorymanager/inventory');
    exit;
}

// Determine stock status
$stockStatus = ($item['quantity'] ?? 0) <= 5 ? 'low' : 'in';
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/inventory_manager/item_view.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components.css">

<div class="item-details-container">
    <!-- Page Header -->
    <?php
    $config = [
        'title'       => $item['name'],
        'description' => 'View and manage inventory item details',
        'show_back'   => true,
        'back_url'    => URLROOT . '/inventorymanager/inventory',
        'back_label'  => 'Back to Inventory'
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
                            <span class="badge bg-info"><?php echo htmlspecialchars($item['category_name'] ?? '—'); ?></span>
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
                            <p class="info-value"><?php echo htmlspecialchars($item['category_name'] ?? '—'); ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Unit Price</label>
                            <p class="info-value">LKR <?php echo number_format($item['unit_price'], 2); ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Buying Price</label>
                            <p class="info-value">LKR <?php echo number_format($item['buying_price'] ?? 0, 2); ?></p>
                        </div>
                        <?php if (!empty($item['description'])): ?>
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <label class="info-label">Description</label>
                            <p class="info-value"><?php echo htmlspecialchars($item['description']); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Card Footer with Actions -->
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <button onclick="editItem(<?php echo $item['id']; ?>)" class="btn btn-sm btn-primary flex-1">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Stock -->
        <div class="col-md-7">
            <div class="card mb-6">
                <div class="card-body text-center" style="padding: 2rem 1.5rem;">
                    <p class="info-label mb-2" style="font-size:0.85rem; color:#6b7280;">Current Stock</p>
                    <div style="font-size: 3rem; font-weight: 700; color: var(--color-primary, #fe9630); line-height:1;">
                        <?php echo (int)($item['quantity'] ?? 0); ?>
                    </div>
                    <p style="font-size:0.875rem; color:#6b7280; margin-top:0.5rem;">units remaining</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editItem(id) {
    window.location.href = '<?php echo URLROOT; ?>/inventorymanager/inventory?edit=' + id;
}
</script>
