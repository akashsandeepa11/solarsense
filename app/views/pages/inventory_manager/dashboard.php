<?php
/**
 * Inventory Manager Dashboard - connected to database
 */

$totalItems       = $data['total_items']        ?? 0;
$lowStockItems    = $data['low_stock_items']    ?? [];
$lowStockCount    = $data['low_stock_count']    ?? 0;
$totalStockValue  = $data['total_stock_value']  ?? 0;
$categoriesCount  = $data['categories_count']   ?? 0;
$stockByCategory  = $data['stock_by_category']  ?? [];
$recentOrders     = $data['recent_orders']      ?? [];
$totalOrdersCount = $data['total_orders_count'] ?? 0;
$totalOrdersAmt   = $data['total_orders_amount']?? 0;

// Build chart data from real DB results
$chartLabels = [];
$chartQty    = [];
$chartValue  = [];
foreach ((array)$stockByCategory as $row) {
    $chartLabels[] = $row->category    ?? $row['category']    ?? 'Unknown';
    $chartQty[]    = (int)($row->total_qty    ?? $row['total_qty']    ?? 0);
    $chartValue[]  = (float)($row->total_value ?? $row['total_value'] ?? 0);
}

$dashboard_stats = [
    ['label' => 'Total Items',    'value' => $totalItems,                                 'icon' => 'fas fa-boxes-stacked',       'color' => 'primary'],
    ['label' => 'Low Stock',      'value' => $lowStockCount,                              'icon' => 'fas fa-exclamation-triangle', 'color' => 'warning'],
    ['label' => 'Categories',     'value' => $categoriesCount,                            'icon' => 'fas fa-tags',                 'color' => 'accent'],
    ['label' => 'Stock Value',    'value' => 'LKR ' . number_format($totalStockValue, 0), 'icon' => 'fas fa-coins',                'color' => 'success'],
    ['label' => 'Total Orders',   'value' => $totalOrdersCount,                           'icon' => 'fas fa-file-invoice',         'color' => 'primary'],
    ['label' => 'Orders Value',   'value' => 'LKR ' . number_format($totalOrdersAmt, 0),  'icon' => 'fas fa-cart-shopping',        'color' => 'accent'],
];
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="content-area">
    <!-- Page Header -->
    <?php
    $config = [
        'title'       => 'Inventory Dashboard',
        'description' => 'Overview of your inventory management system',
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Stats Cards -->
    <?php
    $config = ['stats' => $dashboard_stats, 'columns' => 6];
    include __DIR__ . '/../../inc/components/stat_card.php';
    ?>

    <!-- Chart + Low Stock Row -->
    <div class="row mt-6">
        <!-- Stock by Category Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm rounded-xl h-100">
                <div class="card-header d-flex justify-between align-center py-4 px-4">
                    <h3 class="text-lg font-semibold">Stock by Category</h3>
                    <a href="<?php echo URLROOT; ?>/inventorymanager/inventory" class="btn btn-sm btn-primary-outline">View All</a>
                </div>
                <div class="card-body">
                    <?php if (empty($chartLabels)): ?>
                        <p class="text-center text-secondary py-6">No inventory data yet.</p>
                    <?php else: ?>
                        <div class="chart-container" style="height: 280px;">
                            <canvas id="stockByCategoryChart"></canvas>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm rounded-xl h-100">
                <div class="card-header d-flex justify-between align-center py-4 px-4">
                    <h3 class="text-lg font-semibold text-error">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Low Stock Alerts
                        <?php if ($lowStockCount > 0): ?>
                            <span class="badge bg-error ml-2" style="font-size:0.75rem;"><?php echo $lowStockCount; ?></span>
                        <?php endif; ?>
                    </h3>
                    <a href="<?php echo URLROOT; ?>/inventorymanager/inventory" class="btn btn-sm btn-error-outline">Manage Stock</a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($lowStockItems)): ?>
                        <p class="text-center text-secondary py-6">
                            <i class="fas fa-check-circle mr-2 text-success"></i>All items sufficiently stocked.
                        </p>
                    <?php else: ?>
                        <?php
                        $lowStockRows = array_map(function($item) {
                            return [
                                'id'       => $item->id       ?? $item['id'],
                                'name'     => $item->name     ?? $item['name'],
                                'category' => $item->category_name ?? $item['category_name'] ?? '—',
                                'current'  => $item->quantity ?? $item['quantity'],
                            ];
                        }, (array)$lowStockItems);

                        $config = [
                            'headers' => [
                                ['key' => 'name',     'label' => 'Item'],
                                ['key' => 'category', 'label' => 'Category'],
                                ['key' => 'current',  'label' => 'Qty'],
                            ],
                            'rows'    => $lowStockRows,
                            'columns' => [
                                ['key' => 'name',    'render' => function($r) { return '<span class="font-medium">' . htmlspecialchars($r['name']) . '</span>'; }],
                                ['key' => 'category','render' => function($r) { return '<span class="text-secondary">' . htmlspecialchars($r['category']) . '</span>'; }],
                                ['key' => 'current', 'render' => function($r) { return '<span class="text-error font-semibold">' . (int)$r['current'] . '</span>'; }],
                            ],
                            'actions'       => [
                                ['label' => 'View', 'icon' => 'fas fa-eye', 'url' => URLROOT . '/inventorymanager/item/{id}', 'class' => 'btn-sm btn-primary'],
                            ],
                            'empty_message' => 'No low stock items',
                        ];
                        include __DIR__ . '/../../inc/components/data_table.php';
                        ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card shadow-sm rounded-xl mb-4">
        <div class="card-header d-flex justify-between align-center py-4 px-4">
            <h3 class="text-lg font-semibold">
                <i class="fas fa-file-invoice mr-2"></i>Recent Orders
            </h3>
            <a href="<?php echo URLROOT; ?>/inventorymanager/purchases" class="btn btn-sm btn-primary-outline">View All</a>
        </div>
        <div class="card-body p-0">
            <?php if (empty($recentOrders)): ?>
                <p class="text-center text-secondary py-6">No orders yet.</p>
            <?php else: ?>
                <?php
                $orderRows = array_map(function($o) {
                    return [
                        'id'           => $o->order_id    ?? $o['order_id'],
                        'order_id'     => '#' . ($o->order_id ?? $o['order_id']),
                        'total_amount' => 'LKR ' . number_format($o->total_amount ?? $o['total_amount'] ?? 0, 2),
                        'status'       => $o->status      ?? $o['status']      ?? '—',
                        'date'         => $o->date        ?? $o['date']        ?? '—',
                    ];
                }, (array)$recentOrders);

                $config = [
                    'headers' => [
                        ['key' => 'order_id',     'label' => 'Order #'],
                        ['key' => 'date',         'label' => 'Date'],
                        ['key' => 'total_amount', 'label' => 'Total'],
                        ['key' => 'status',       'label' => 'Status'],
                    ],
                    'rows'    => $orderRows,
                    'columns' => [
                        ['key' => 'order_id',     'render' => function($r) { return '<span class="font-medium text-primary">' . htmlspecialchars($r['order_id']) . '</span>'; }],
                        ['key' => 'total_amount', 'render' => function($r) { return '<span class="font-semibold">' . htmlspecialchars($r['total_amount']) . '</span>'; }],
                        ['key' => 'status',       'render' => function($r) {
                            $map = [
                                'pending'   => ['bg-warning', 'Pending'],
                                'completed' => ['bg-success', 'Completed'],
                                'cancelled' => ['bg-error',   'Cancelled'],
                                'approved'  => ['bg-accent',  'Approved'],
                                'delivered' => ['bg-primary', 'Delivered'],
                            ];
                            $s = strtolower($r['status']);
                            [$cls, $lbl] = $map[$s] ?? ['bg-secondary', ucfirst($r['status'])];
                            return '<span class="badge ' . $cls . ' text-surface px-3 py-1 rounded-full text-xs">' . $lbl . '</span>';
                        }],
                    ],
                    'empty_message' => 'No recent orders',
                ];
                include __DIR__ . '/../../inc/components/data_table.php';
                ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card shadow-sm rounded-xl mb-4">
        <div class="card-header py-4 px-4">
            <h3 class="text-lg font-semibold">Quick Actions</h3>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-4">
                <a href="<?php echo URLROOT; ?>/inventorymanager/inventory" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>Add New Item
                </a>
                <a href="<?php echo URLROOT; ?>/inventorymanager/purchases" class="btn btn-accent">
                    <i class="fas fa-cart-plus mr-2"></i>View Purchases
                </a>
                <a href="<?php echo URLROOT; ?>/inventorymanager/inventory" class="btn btn-secondary">
                    <i class="fas fa-tags mr-2"></i>Manage Categories
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('stockByCategoryChart');
    if (!ctx) return;

    const labels    = <?php echo json_encode($chartLabels); ?>;
    const qtyData   = <?php echo json_encode($chartQty); ?>;
    const valueData = <?php echo json_encode($chartValue); ?>;

    const colors = [
        'rgba(254,150,48,0.8)','rgba(34,197,94,0.8)','rgba(59,130,246,0.8)',
        'rgba(168,85,247,0.8)','rgba(239,68,68,0.8)','rgba(20,184,166,0.8)',
        'rgba(245,158,11,0.8)','rgba(99,102,241,0.8)'
    ];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Total Qty',
                    data: qtyData,
                    backgroundColor: colors,
                    borderRadius: 6,
                    borderWidth: 0,
                    yAxisID: 'y',
                },
                {
                    label: 'Stock Value (LKR)',
                    data: valueData,
                    type: 'line',
                    fill: false,
                    borderColor: 'rgba(34,197,94,1)',
                    backgroundColor: 'rgba(34,197,94,0.5)',
                    borderRadius: 6,
                    tension: 0.3,
                    pointRadius: 4,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { padding: 15 } },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ctx.datasetIndex === 1
                                ? 'Value: LKR ' + ctx.parsed.y.toLocaleString()
                                : 'Qty: ' + ctx.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y:  { beginAtZero: true, title: { display: true, text: 'Quantity' }, grid: { color: '#e5e7eb' } },
                y1: { beginAtZero: true, position: 'right', title: { display: true, text: 'Value (LKR)' },
                      grid: { drawOnChartArea: false },
                      ticks: { callback: v => 'LKR ' + (v/1000).toFixed(0) + 'K' } },
                x:  { grid: { display: false } }
            }
        }
    });
});
</script>
