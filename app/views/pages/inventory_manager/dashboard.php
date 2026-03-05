<?php
/**
 * Inventory Manager Dashboard
 * Overview of inventory statistics and recent activity
 */

// Dashboard statistics
$dashboard_stats = [
    ['label' => 'Total Items', 'value' => '156', 'icon' => 'fas fa-boxes-stacked', 'color' => 'primary'],
    ['label' => 'Low Stock Items', 'value' => '12', 'icon' => 'fas fa-exclamation-triangle', 'color' => 'warning'],
    ['label' => 'Total Suppliers', 'value' => '24', 'icon' => 'fas fa-truck-fast', 'color' => 'accent'],
    ['label' => 'Pending Orders', 'value' => '8', 'icon' => 'fas fa-clock', 'color' => 'secondary'],
    ['label' => 'Stock Value', 'value' => 'LKR 2.5M', 'icon' => 'fas fa-coins', 'color' => 'success'],
    ['label' => 'This Month Purchases', 'value' => 'LKR 450K', 'icon' => 'fas fa-cart-shopping', 'color' => 'primary'],
];

// Low stock items
$low_stock_items = [
    ['id' => 1, 'name' => 'Mounting Brackets', 'current' => 5, 'minimum' => 20, 'category' => 'Accessories'],
    ['id' => 2, 'name' => 'MC4 Connectors', 'current' => 12, 'minimum' => 50, 'category' => 'Electrical'],
    ['id' => 3, 'name' => 'Cable Ties (Pack)', 'current' => 8, 'minimum' => 30, 'category' => 'Accessories'],
    ['id' => 4, 'name' => 'Junction Box', 'current' => 3, 'minimum' => 15, 'category' => 'Electrical'],
];

// Sales & Purchase Chart Data
$sales_purchase_chart_data = [
    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    'sales' => [320000, 450000, 380000, 520000, 480000, 550000],
    'purchases' => [280000, 350000, 420000, 380000, 450000, 400000],
];
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="content-area">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'Inventory Dashboard',
        'description' => 'Overview of your inventory management system',
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Stats Cards -->
    <?php
    $config = [
        'stats' => $dashboard_stats,
        'columns' => 6
    ];
    include __DIR__ . '/../../inc/components/stat_card.php';
    ?>

    <!-- Main Content Grid -->
    <div class="row mt-6">
        <!-- Sales & Purchase Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm rounded-xl h-100">
                <div class="card-header d-flex justify-between align-center py-4 px-4">
                    <h3 class="text-lg font-semibold">Sales & Purchase Overview</h3>
                    <a href="<?php echo URLROOT; ?>/inventorymanager/reports" class="btn btn-sm btn-primary-outline">View Reports</a>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 280px;">
                        <canvas id="salesPurchaseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm rounded-xl h-100">
                <div class="card-header d-flex justify-between align-center py-4 px-4">
                    <h3 class="text-lg font-semibold text-error">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Low Stock Alerts
                    </h3>
                    <a href="<?php echo URLROOT; ?>/inventorymanager/inventory" class="btn btn-sm btn-error-outline">Manage Stock</a>
                </div>
                <div class="card-body p-0">
                    <?php
                    $config = [
                        'headers' => [
                            ['key' => 'name', 'label' => 'Item'],
                            ['key' => 'category', 'label' => 'Category'],
                            ['key' => 'current', 'label' => 'Current'],
                            ['key' => 'minimum', 'label' => 'Min Required'],
                        ],
                        'rows' => $low_stock_items,
                        'columns' => [
                            [
                                'key' => 'name',
                                'render' => function($row) {
                                    return '<span class="font-medium">' . htmlspecialchars($row['name']) . '</span>';
                                }
                            ],
                            [
                                'key' => 'category',
                                'render' => function($row) {
                                    return '<span class="text-secondary">' . htmlspecialchars($row['category']) . '</span>';
                                }
                            ],
                            [
                                'key' => 'current',
                                'render' => function($row) {
                                    return '<span class="text-error font-semibold">' . $row['current'] . '</span>';
                                }
                            ],
                        ],
                        'actions' => [
                            ['label' => 'Reorder', 'icon' => 'fas fa-cart-plus', 'url' => URLROOT . '/inventorymanager/purchases?reorder={id}', 'class' => 'btn-sm btn-primary'],
                        ],
                        'empty_message' => 'No low stock items'
                    ];
                    include __DIR__ . '/../../inc/components/data_table.php';
                    ?>
                </div>
            </div>
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
                    <i class="fas fa-cart-plus mr-2"></i>Create Purchase Order
                </a>
                <a href="<?php echo URLROOT; ?>/inventorymanager/suppliers" class="btn btn-secondary">
                    <i class="fas fa-user-plus mr-2"></i>Add Supplier
                </a>
                <a href="<?php echo URLROOT; ?>/inventorymanager/reports" class="btn btn-success">
                    <i class="fas fa-file-export mr-2"></i>Generate Report
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function reorderItem(id) {
    // Redirect to purchases page with item pre-selected
    window.location.href = '<?php echo URLROOT; ?>/inventorymanager/purchases?reorder=' + id;
}

// Chart.js initialization
document.addEventListener('DOMContentLoaded', function() {
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: { padding: 15 }
            }
        }
    };

    // Sales & Purchase Chart
    const salesPurchaseCtx = document.getElementById('salesPurchaseChart');
    if (salesPurchaseCtx) {
        new Chart(salesPurchaseCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($sales_purchase_chart_data['labels']); ?>,
                datasets: [
                    {
                        label: 'Sales (LKR)',
                        data: <?php echo json_encode($sales_purchase_chart_data['sales']); ?>,
                        backgroundColor: 'rgba(34, 197, 94, 0.7)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 0,
                        borderRadius: 5
                    },
                    {
                        label: 'Purchases (LKR)',
                        data: <?php echo json_encode($sales_purchase_chart_data['purchases']); ?>,
                        backgroundColor: 'rgba(254, 150, 48, 0.7)',
                        borderColor: 'rgba(254, 150, 48, 1)',
                        borderWidth: 0,
                        borderRadius: 5
                    }
                ]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#e5e7eb' },
                        ticks: {
                            callback: function(value) {
                                return 'LKR ' + (value / 1000) + 'K';
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
</script>
