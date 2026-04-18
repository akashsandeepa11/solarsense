<?php
$items      = $data['items']       ?? [];
$orders     = $data['orders']      ?? [];
$orderStats = $data['order_stats'] ?? null;

$totalItems    = count($items);
$totalQty      = array_sum(array_map(fn($r) => (int)$r->quantity, $items));
$stockValue    = array_sum(array_map(fn($r) => (float)$r->quantity * (float)$r->unit_price, $items));
$lowStockCount = count(array_filter($items, fn($r) => (int)$r->quantity <= 5));

$totalOrders     = $orderStats->total_orders     ?? 0;
$pendingOrders   = $orderStats->pending_count    ?? 0;
$completedOrders = $orderStats->completed_count  ?? 0;
$ordersValue     = $orderStats->total_value      ?? 0;
?>

<link rel="stylesheet" href="<?php echo URLROOT ?>/css/pages/homeowner/service.css">
<link rel="stylesheet" href="<?php echo URLROOT ?>/css/pages/homeowner/reports.css">

<div class="content-area">

    <?php
    $config = [
        'title'       => 'Reports',
        'description' => 'Inventory stock and purchase order reports',
    ];
    require APPROOT . '/views/inc/components/page_header.php';
    ?>

    <!-- ── SECTION 1 : Inventory ──────────────────────────────────── -->
    <div class="rp-section-header mb-4">
        <div>
            <h2 class="rp-section-title">Inventory Stock</h2>
            <p class="rp-section-sub">
                <?php echo $totalItems ?> items &bull;
                <?php echo number_format($totalQty) ?> total units &bull;
                Stock value: Rs. <?php echo number_format($stockValue, 2) ?>
                <?php if ($lowStockCount > 0): ?>
                    &bull; <span style="color:#d97706;"><?php echo $lowStockCount ?> low stock</span>
                <?php endif ?>
            </p>
        </div>
        <?php
        $config = [
            'table_selector' => '.inv-report-table',
            'report_title'   => 'Inventory Stock Report',
            'report_subtitle'=> 'Current stock levels and item details',
            'columns'        => ['Item ID', 'Name', 'Category', 'Qty', 'Unit Price (Rs.)', 'Total Value (Rs.)', 'Status'],
            'btn_id'         => 'btn-inv-pdf',
        ];
        require APPROOT . '/views/inc/components/download_report_btn.php';
        ?>
    </div>

    <div class="card shadow-lg rounded-xl mb-6">
        <div class="card-body p-0">
            <?php if (!empty($items)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 inv-report-table">
                        <thead class="table-light">
                            <tr>
                                <th>Item ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Qty</th>
                                <th>Unit Price (Rs.)</th>
                                <th>Total Value (Rs.)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                            <?php
                            $qty       = (int)$item->quantity;
                            $unitPrice = (float)$item->unit_price;
                            $totalVal  = $qty * $unitPrice;
                            $isLow     = $qty <= 5;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item->inventory_id) ?></td>
                                <td class="font-semibold"><?php echo htmlspecialchars($item->item_name) ?></td>
                                <td><?php echo htmlspecialchars($item->category_name ?? '—') ?></td>
                                <td><?php echo $qty ?></td>
                                <td><?php echo number_format($unitPrice, 2) ?></td>
                                <td><?php echo number_format($totalVal, 2) ?></td>
                                <td>
                                    <span class="badge <?php echo $isLow ? 'badge-warning' : 'badge-success' ?>">
                                        <?php echo $isLow ? 'Low Stock' : 'In Stock' ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-box-open text-4xl text-secondary opacity-50 mb-3 d-block"></i>
                    <p class="text-secondary">No inventory items found.</p>
                </div>
            <?php endif ?>
        </div>
    </div>

    <!-- ── SECTION 2 : Purchase Orders ───────────────────────────── -->
    <div class="rp-section-header mb-4 mt-6">
        <div>
            <h2 class="rp-section-title">Purchase Orders</h2>
            <p class="rp-section-sub">
                <?php echo $totalOrders ?> orders &bull;
                Completed: <?php echo $completedOrders ?> &bull;
                Pending: <?php echo $pendingOrders ?> &bull;
                Total value: Rs. <?php echo number_format((float)$ordersValue, 2) ?>
            </p>
        </div>
        <?php
        $config = [
            'table_selector' => '.orders-report-table',
            'report_title'   => 'Purchase Orders Report',
            'report_subtitle'=> 'All customer orders from the store',
            'columns'        => ['Order #', 'Items', 'Total (Rs.)', 'Date', 'Status'],
            'btn_id'         => 'btn-orders-pdf',
        ];
        require APPROOT . '/views/inc/components/download_report_btn.php';
        ?>
    </div>

    <div class="card shadow-lg rounded-xl mb-6">
        <div class="card-body p-0">
            <?php if (!empty($orders)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 orders-report-table">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Items</th>
                                <th>Total (Rs.)</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <?php
                            $statusClass = match(strtolower($order->status ?? '')) {
                                'completed' => 'badge-success',
                                'cancelled' => 'badge-danger',
                                default     => 'badge-warning',
                            };
                            ?>
                            <tr>
                                <td class="font-semibold">#<?php echo htmlspecialchars($order->order_id) ?></td>
                                <td><?php echo (int)($order->item_count ?? 0) ?> item<?php echo $order->item_count != 1 ? 's' : '' ?></td>
                                <td class="font-semibold"><?php echo number_format((float)$order->total_amount, 2) ?></td>
                                <td><?php echo htmlspecialchars($order->date ?? '—') ?></td>
                                <td>
                                    <span class="badge <?php echo $statusClass ?>">
                                        <?php echo ucfirst(htmlspecialchars($order->status ?? 'pending')) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-shopping-cart text-4xl text-secondary opacity-50 mb-3 d-block"></i>
                    <p class="text-secondary">No purchase orders found.</p>
                </div>
            <?php endif ?>
        </div>
    </div>

</div>

<?php require APPROOT . '/views/inc/components/report_downloader.php'; ?>
