<?php
// SuperAdmin Reports Page

require_once APPROOT . '/config/constants.php';
require_once APPROOT . '/helpers/ReportData_Helper.php';

$role = ROLE_SUPER_ADMIN;
$report_data = getReportDataByRole($role) ?? [];

// Define color scheme
$colors = [
    'primary' => '#fe9630',
    'success' => '#22c55e',
    'warning' => '#f59e0b',
    'error' => '#ef4444',
    'info' => '#3b82f6',
    'accent' => '#06b6d4',
    'purple' => '#8b5cf6',
    'yellow' => '#eab308',
];
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

    .reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
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

    .status-active {
        background: rgba(34, 197, 94, 0.1);
        color: var(--success);
    }

    .status-pending {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info);
    }

    @media (max-width: 768px) {
        .reports-container {
            padding: 1rem;
        }

        .reports-grid {
            grid-template-columns: 1fr;
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
        'description' => 'View and download system-wide reports and analytics',
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

    <!-- System Overview Reports -->
    <div class="report-section">
        <h2 class="report-section-title">System Overview</h2>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['platform_overview']['total_companies'] ?? '0'; ?></div>
                <div class="stat-label">Total Companies</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['platform_overview']['total_users'] ?? '0'; ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo number_format($report_data['platform_overview']['active_systems'] ?? 0); ?></div>
                <div class="stat-label">Active Systems</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['platform_overview']['platform_uptime'] ?? 'N/A'; ?></div>
                <div class="stat-label">Platform Uptime</div>
            </div>
        </div>
    </div>

    <!-- Companies Report -->
    <div class="report-section">
        <h2 class="report-section-title">Companies Report</h2>
        <div class="data-table">
            <table>
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Status</th>
                        <th>Systems</th>
                        <th>Users</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['companies_report'] ?? [] as $company): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($company['name'] ?? ''); ?></td>
                        <td><span class="status-badge status-<?php echo strtolower($company['status'] ?? ''); ?>"><?php echo $company['status'] ?? 'N/A'; ?></span></td>
                        <td><?php echo $company['systems'] ?? '0'; ?></td>
                        <td><?php echo $company['users'] ?? '0'; ?></td>
                        <td><?php echo $company['revenue'] ?? 'N/A'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Users Report -->
    <div class="report-section">
        <h2 class="report-section-title">Users by Role</h2>
        <div class="data-table">
            <table>
                <thead>
                    <tr>
                        <th>User Role</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['users_report'] ?? [] as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['role'] ?? ''); ?></td>
                        <td><?php echo $user['count'] ?? '0'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Verification & Compliance Reports -->
    <div class="report-section">
        <h2 class="report-section-title">Verification & Compliance</h2>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['verification_report']['pending_verifications'] ?? '0'; ?></div>
                <div class="stat-label">Pending Verifications</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['verification_report']['completed_verifications'] ?? '0'; ?></div>
                <div class="stat-label">Completed</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['complaints_report']['resolved'] ?? '0'; ?></div>
                <div class="stat-label">Resolved Complaints</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['complaints_report']['resolution_rate'] ?? '0'; ?>%</div>
                <div class="stat-label">Resolution Rate</div>
            </div>
        </div>
    </div>

    <!-- Energy Generation Report -->
    <div class="report-section">
        <h2 class="report-section-title">Energy Generation Report</h2>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['energy_generation']['today'] ?? 'N/A'; ?></div>
                <div class="stat-label">Today</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['energy_generation']['this_week'] ?? 'N/A'; ?></div>
                <div class="stat-label">This Week</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['energy_generation']['this_month'] ?? 'N/A'; ?></div>
                <div class="stat-label">This Month</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo $report_data['energy_generation']['co2_avoided'] ?? 'N/A'; ?></div>
                <div class="stat-label">CO₂ Avoided</div>
            </div>
        </div>
    </div>
</div>

<script>
function downloadAllReports() {
    const period = document.getElementById('period-filter')?.value || '';
    const format = document.getElementById('format-filter')?.value || 'pdf';
    
    if (format === 'print') {
        window.print();
        return;
    }

    // Log the download request
    console.log(`Downloading SuperAdmin reports as ${format} for period: ${period || 'All Periods'}`);
    
    // Show success message
    alert(`✓ Generating SuperAdmin Report\nFormat: ${format.toUpperCase()}\nPeriod: ${period || 'All Periods'}\n\nReport will be downloaded shortly...`);
    
    // In production, this would submit to a backend endpoint
    // For now, show how to implement it:
    // fetch('<?php echo URLROOT; ?>/reports/download-superadmin', {
    //     method: 'POST',
    //     headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    //     body: `period=${period}&format=${format}`
    // }).then(res => res.blob()).then(blob => {
    //     const url = window.URL.createObjectURL(blob);
    //     const a = document.createElement('a');
    //     a.href = url;
    //     a.download = `SuperAdmin_Report_${new Date().toISOString().split('T')[0]}.${format}`;
    //     a.click();
    // });
}
</script>
