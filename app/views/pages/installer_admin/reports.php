<?php
// InstallerAdmin Reports Page

require_once APPROOT . '/config/constants.php';
require_once APPROOT . '/helpers/ReportData_Helper.php';

$role = ROLE_INSTALLER_ADMIN;
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

    .status-excellent, .status-good, .status-active {
        background: rgba(34, 197, 94, 0.1);
        color: var(--success);
    }

    .status-warning {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .status-critical, .status-error, .status-offline {
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
        'description' => 'View and download your installation company reports',
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

    <!-- Fleet Status -->
    <div class="report-section">
        <h2 class="report-section-title">Fleet Status Overview</h2>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['fleet_status']['total_systems'] ?? '0'; ?></div>
                <div class="stat-label">Total Systems</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['fleet_status']['excellent'] ?? '0'; ?></div>
                <div class="stat-label">Excellent</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['fleet_status']['good'] ?? '0'; ?></div>
                <div class="stat-label">Good</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['fleet_status']['warning'] ?? '0'; ?></div>
                <div class="stat-label">Warnings</div>
            </div>
        </div>
    </div>

    <!-- Systems List -->
    <div class="report-section">
        <h2 class="report-section-title">Installed Systems</h2>
        <div class="data-table">
            <table>
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Performance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['systems_list'] ?? [] as $system): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($system['name']); ?></td>
                        <td><?php echo htmlspecialchars($system['location']); ?></td>
                        <td><?php echo $system['capacity']; ?></td>
                        <td><span class="status-badge status-<?php echo strtolower($system['status']); ?>"><?php echo $system['status']; ?></span></td>
                        <td><?php echo $system['performance']; ?>%</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Team Members -->
    <div class="report-section">
        <h2 class="report-section-title">Team Performance</h2>
        <div class="data-table">
            <table>
                <thead>
                    <tr>
                        <th>Team Member</th>
                        <th>Role</th>
                        <th>Tasks Completed</th>
                        <th>Pending Tasks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['team_members'] ?? [] as $member): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($member['name']); ?></td>
                        <td><?php echo htmlspecialchars($member['role']); ?></td>
                        <td><?php echo $member['tasks_completed']; ?></td>
                        <td><?php echo $member['tasks_pending']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Financial & Operational Metrics -->
    <div class="report-section">
        <h2 class="report-section-title">Financial & Operational Metrics</h2>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['revenue_metrics']['total_revenue'] ?? 'N/A'; ?></div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['revenue_metrics']['monthly_revenue'] ?? 'N/A'; ?></div>
                <div class="stat-label">Monthly Revenue</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['maintenance_metrics']['completed'] ?? '0'; ?></div>
                <div class="stat-label">Maintenance Completed</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['maintenance_metrics']['scheduled'] ?? '0'; ?></div>
                <div class="stat-label">Scheduled</div>
            </div>
        </div>
    </div>
</div>

<script>
function downloadAllReports() {
    const period = document.getElementById('period-filter')?.value || '';
    const format = document.getElementById('format-filter')?.value || 'pdf';
    
    console.log(`Downloading InstallerAdmin reports as ${format} for period: ${period || 'All Periods'}`);
    alert(`✓ Generating InstallerAdmin Report\nFormat: ${format.toUpperCase()}\nPeriod: ${period || 'All Periods'}\n\nReport will be downloaded shortly...`);
}
</script>
