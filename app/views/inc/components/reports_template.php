<?php
/**
 * Reusable Reports Template
 * This component generates role-specific reports with consistent theming
 */

$role = $role ?? ROLE_SUPER_ADMIN;
$report_sections = $report_sections ?? [];

// Include the report data helper
require_once APPROOT . '/helpers/ReportData_Helper.php';

// Color scheme
$colors = [
    'primary' => '#fe9630',
    'success' => '#22c55e',
    'warning' => '#f59e0b',
    'error' => '#ef4444',
    'info' => '#3b82f6',
    'accent' => '#06b6d4',
    'purple' => '#8b5cf6',
    'yellow' => '#eab308',
    'bg_light' => '#f9fafb',
    'border' => '#e5e7eb',
    'text_dark' => '#212121',
    'text_muted' => '#6b7280'
];
?>

<style>
    :root {
        --primary: <?php echo $colors['primary']; ?>;
        --success: <?php echo $colors['success']; ?>;
        --warning: <?php echo $colors['warning']; ?>;
        --error: <?php echo $colors['error']; ?>;
        --info: <?php echo $colors['info']; ?>;
        --accent: <?php echo $colors['accent']; ?>;
        --purple: <?php echo $colors['purple']; ?>;
        --yellow: <?php echo $colors['yellow']; ?>;
        --bg-light: <?php echo $colors['bg_light']; ?>;
        --border: <?php echo $colors['border']; ?>;
        --text-dark: <?php echo $colors['text_dark']; ?>;
        --text-muted: <?php echo $colors['text_muted']; ?>;
    }

    .reports-container {
        background: var(--bg-light);
        min-height: 100vh;
        padding: 2rem;
    }

    .reports-header {
        margin-bottom: 2rem;
    }

    .reports-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0 0 0.5rem 0;
    }

    .reports-subtitle {
        color: var(--text-muted);
        font-size: 1rem;
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
        min-width: 200px;
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
        align-items: center;
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

    .btn-download:active {
        transform: translateY(0);
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

    .status-pending {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info);
    }

    .highlight-box {
        background: linear-gradient(135deg, rgba(254, 150, 48, 0.05), rgba(253, 126, 20, 0.05));
        border: 1px solid var(--border);
        border-left: 4px solid var(--primary);
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .highlight-box-title {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .highlight-box-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
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

        .filter-group {
            min-width: auto;
        }

        .download-buttons {
            flex-direction: column;
            width: 100%;
        }

        .btn-download {
            width: 100%;
            justify-content: center;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media print {
        .filter-bar, .download-buttons {
            display: none;
        }

        .reports-container {
            background: white;
            padding: 0;
        }

        .report-section {
            page-break-inside: avoid;
        }
    }
</style>

<div class="reports-container">
    <!-- Page Header -->
    <?php
    $page_config = [
        'title' => $page_title ?? 'Reports',
        'description' => $page_description ?? 'View and download your reports',
        'show_back' => false
    ];
    include __DIR__ . '/page_header.php';
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
                <option value="print">Print</option>
            </select>
        </div>
        <div class="download-buttons">
            <button class="btn-download" onclick="downloadReport()">
                <i class="fas fa-download"></i> Download Report
            </button>
            <button class="btn-download" onclick="window.print()" style="background: #6b7280;">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <!-- Report Content Sections -->
    <?php if (!empty($report_sections)): ?>
        <?php foreach ($report_sections as $section): ?>
            <div class="report-section">
                <h2 class="report-section-title"><?php echo htmlspecialchars($section['title']); ?></h2>
                
                <!-- Stats Grid if provided -->
                <?php if (!empty($section['stats'])): ?>
                    <div class="stats-grid">
                        <?php foreach ($section['stats'] as $stat): ?>
                            <div class="stat-box">
                                <div class="stat-value"><?php echo htmlspecialchars($stat['value']); ?></div>
                                <div class="stat-label"><?php echo htmlspecialchars($stat['label']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Highlight Box if provided -->
                <?php if (!empty($section['highlight'])): ?>
                    <div class="highlight-box">
                        <div class="highlight-box-title"><?php echo htmlspecialchars($section['highlight']['title']); ?></div>
                        <div class="highlight-box-value"><?php echo htmlspecialchars($section['highlight']['value']); ?></div>
                    </div>
                <?php endif; ?>

                <!-- Data Table if provided -->
                <?php if (!empty($section['data'])): ?>
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <?php foreach ($section['columns'] ?? array_keys($section['data'][0]) as $column): ?>
                                        <th><?php echo htmlspecialchars($column); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($section['data'] as $row): ?>
                                    <tr>
                                        <?php foreach ($section['columns'] ?? array_keys($row) as $column): ?>
                                            <td>
                                                <?php 
                                                    $value = $row[$column] ?? '';
                                                    
                                                    // Check if value should be rendered as status badge
                                                    if (in_array($column, ['status', 'Status']) && !empty($value)) {
                                                        $status_class = 'status-' . strtolower(str_replace(' ', '-', $value));
                                                        echo '<span class="status-badge ' . $status_class . '">' . htmlspecialchars($value) . '</span>';
                                                    } else {
                                                        echo htmlspecialchars($value);
                                                    }
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function downloadReport() {
    const period = document.getElementById('period-filter')?.value || 'all';
    const format = document.getElementById('format-filter')?.value || 'pdf';
    
    if (format === 'print') {
        window.print();
        return;
    }

    // Create form to submit to download endpoint
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo URLROOT; ?>/reports/download';
    
    const periodInput = document.createElement('input');
    periodInput.type = 'hidden';
    periodInput.name = 'period';
    periodInput.value = period;
    form.appendChild(periodInput);
    
    const formatInput = document.createElement('input');
    formatInput.type = 'hidden';
    formatInput.name = 'format';
    formatInput.value = format;
    form.appendChild(formatInput);
    
    const titleInput = document.createElement('input');
    titleInput.type = 'hidden';
    titleInput.name = 'title';
    titleInput.value = document.querySelector('.reports-title')?.textContent || 'Report';
    form.appendChild(titleInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Alternative: Print current report as PDF
function printAsPDF() {
    const periodText = document.getElementById('period-filter')?.options[document.getElementById('period-filter').selectedIndex]?.text || 'All Periods';
    const title = document.querySelector('.reports-title')?.textContent || 'Report';
    
    window.print();
}

// Filter functionality
document.getElementById('period-filter')?.addEventListener('change', function() {
    console.log('Period changed to:', this.value);
});

document.getElementById('format-filter')?.addEventListener('change', function() {
    console.log('Format changed to:', this.value);
});
</script>
