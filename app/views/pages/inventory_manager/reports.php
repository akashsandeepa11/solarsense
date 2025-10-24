<?php
// InventoryManager Reports Page

require_once APPROOT . '/config/constants.php';
require_once APPROOT . '/helpers/ReportData_Helper.php';

$role = ROLE_INVENTORY_MANAGER;
$report_data = getReportDataByRole($role) ?? [];
?>

<link rel="stylesheet" href="<?php echo URLROOT ?>/public/css/components.css">

<style>
    :root {
        --primary: #fe9630;
        --success: #22c55e;
        --warning: #f59e0b;
        --error: #ef4444;
        --info: #3b82f6;
        --accent: #06b6d4;
        --purple: #8b5cf6;
        --yellow: #eab308;
        --bg-light: #f9fafb;
        --border: #e5e7eb;
        --text-dark: #212121;
        --text-muted: #6b7280;
    }

    .reports-container {
        background: var(--bg-light);
        min-height: 100vh;
        padding: 2rem;
    }

    .report-section {
        margin-bottom: 3rem;
    }

    .report-section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0 0 1.5rem 0;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--primary);
        display: inline-block;
    }

    .filter-bar {
        background: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        border: 1px solid var(--border);
        margin-bottom: 2rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-dark);
    }

    .filter-select {
        padding: 0.5rem 0.75rem;
        border: 1px solid var(--border);
        border-radius: 0.375rem;
        font-size: 0.875rem;
        background: white;
        cursor: pointer;
        min-width: 200px;
        transition: all 0.2s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(254, 150, 48, 0.1);
    }

    .download-buttons {
        display: flex;
        gap: 0.75rem;
        margin-left: auto;
    }

    .btn-download {
        padding: 0.5rem 1rem;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 0.375rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    .btn-download:hover {
        background: #fd7e14;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(254, 150, 48, 0.3);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-box {
        background: white;
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    .data-table {
        background: white;
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .data-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: var(--primary);
        color: white;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border);
        font-size: 0.875rem;
    }

    .data-table tr:hover {
        background: var(--bg-light);
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-excellent, .status-good, .status-active, .status-in-stock {
        background: rgba(34, 197, 94, 0.1);
        color: var(--success);
    }

    .status-warning, .status-low {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .status-critical, .status-error, .status-offline, .status-out-of-stock {
        background: rgba(239, 68, 68, 0.1);
        color: var(--error);
    }

    @media (max-width: 768px) {
        .reports-container {
            padding: 1rem;
        }

        .filter-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .download-buttons {
            margin-left: 0;
            flex-direction: column;
        }

        .btn-download {
            justify-content: center;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="reports-container">
    <!-- Page Header -->
    <?php
    $config = [
        'title' => 'Reports',
        'description' => 'View and download your inventory reports',
        'show_back' => false
    ];
    include __DIR__ . '/../../inc/components/page_header.php';
    ?>

    <!-- Filter & Download Bar -->
    <div class="filter-bar">
        <div class="filter-group">
            <label class="filter-label">Report Period</label>
            <select class="filter-select" id="period-filter">
                <option value="">All Periods</option>
                <option value="last_7">Last 7 Days</option>
                <option value="last_30">Last 30 Days</option>
                <option value="last_90">Last 90 Days</option>
                <option value="yearly">This Year</option>
                <option value="all_time">All Time</option>
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">Format</label>
            <select class="filter-select" id="format-filter">
                <option value="pdf">PDF</option>
                <option value="excel">Excel</option>
                <option value="csv">CSV</option>
            </select>
        </div>
        <div class="download-buttons">
            <button class="btn-download" onclick="downloadAllReports()">
                <i class="fas fa-download"></i> Download All
            </button>
            <button class="btn-download" onclick="window.print()" style="background: #6b7280;">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <!-- Inventory Summary -->
    <div class="report-section">
        <h2 class="report-section-title">Inventory Summary</h2>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['inventory_summary']['total_items']; ?></div>
                <div class="stat-label">Total Items</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['inventory_summary']['in_stock']; ?></div>
                <div class="stat-label">In Stock</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['inventory_summary']['low_stock']; ?></div>
                <div class="stat-label">Low Stock</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['inventory_summary']['out_of_stock']; ?></div>
                <div class="stat-label">Out of Stock</div>
            </div>
        </div>
    </div>

    <!-- Stock Items -->
    <div class="report-section">
        <h2 class="report-section-title">Stock Items</h2>
        <div class="data-table">
            <table>
                <thead>
                    <tr>
                        <th>Item Code</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Unit Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['stock_items'] as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['code']); ?></td>
                        <td><?php echo htmlspecialchars($item['description']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><span class="status-badge status-<?php echo strtolower($item['status']); ?>"><?php echo $item['status']; ?></span></td>
                        <td><?php echo $item['unit_price']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Suppliers -->
    <div class="report-section">
        <h2 class="report-section-title">Suppliers</h2>
        <div class="data-table">
            <table>
                <thead>
                    <tr>
                        <th>Supplier Name</th>
                        <th>Contact</th>
                        <th>Items Supplied</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['suppliers'] as $supplier): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($supplier['name']); ?></td>
                        <td><?php echo htmlspecialchars($supplier['contact']); ?></td>
                        <td><?php echo $supplier['items_supplied']; ?></td>
                        <td><span class="status-badge status-<?php echo strtolower($supplier['status']); ?>"><?php echo $supplier['status']; ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Stock Movements -->
    <div class="report-section">
        <h2 class="report-section-title">Recent Stock Movements</h2>
        <div class="data-table">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Item</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['stock_movements'] as $movement): ?>
                    <tr>
                        <td><?php echo $movement['date']; ?></td>
                        <td><?php echo htmlspecialchars($movement['item']); ?></td>
                        <td><?php echo $movement['type']; ?></td>
                        <td><?php echo $movement['quantity']; ?></td>
                        <td><?php echo $movement['balance']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Cost Analysis -->
    <div class="report-section">
        <h2 class="report-section-title">Cost Analysis</h2>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['cost_analysis']['total_value']; ?></div>
                <div class="stat-label">Total Inventory Value</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['cost_analysis']['monthly_cost']; ?></div>
                <div class="stat-label">Monthly Cost</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['cost_analysis']['reorder_cost']; ?></div>
                <div class="stat-label">Reorder Cost</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['cost_analysis']['storage_cost']; ?></div>
                <div class="stat-label">Storage Cost</div>
            </div>
        </div>
    </div>
</div>

<script>
function downloadAllReports() {
    const period = document.getElementById('period-filter')?.value || '';
    const format = document.getElementById('format-filter')?.value || 'pdf';
    
    console.log(`Downloading InventoryManager reports as ${format} for period: ${period || 'All Periods'}`);
    alert(`✓ Generating Inventory Manager Report\nFormat: ${format.toUpperCase()}\nPeriod: ${period || 'All Periods'}\n\nReport will be downloaded shortly...`);
}
</script>
