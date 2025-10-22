<?php
/**
 * Report Card Component
 * 
 * Configuration:
 * $config = [
 *     'icon' => 'fas fa-chart-line',
 *     'title' => 'Revenue Report',
 *     'description' => 'Download your monthly revenue report',
 *     'download_url' => '#',
 *     'color' => '#fe9630' (optional)
 * ];
 */

$config = $config ?? [];
$icon = $config['icon'] ?? 'fas fa-file-pdf';
$title = $config['title'] ?? 'Report';
$description = $config['description'] ?? 'Download this report';
$download_url = $config['download_url'] ?? '#';
$color = $config['color'] ?? '#fe9630';
?>

<style>
    .report-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        height: 100%;
    }

    .report-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transform: translateY(-4px);
        border-color: <?php echo $color; ?>;
    }

    .report-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .report-card-icon {
        width: 60px;
        height: 60px;
        border-radius: 0.75rem;
        background: <?php echo $color; ?>15;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: <?php echo $color; ?>;
    }

    .report-card-content {
        flex: 1;
    }

    .report-card-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #212121;
        margin: 0;
    }

    .report-card-description {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 0.5rem 0 0 0;
    }

    .report-card-footer {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        padding-top: 0.75rem;
        border-top: 1px solid #f3f4f6;
    }

    .report-btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .report-btn-download {
        background: <?php echo $color; ?>;
        color: white;
    }

    .report-btn-download:hover {
        background: <?php echo $color; ?>dd;
        transform: scale(1.02);
    }

    .report-btn-view {
        background: #f3f4f6;
        color: #212121;
        border: 1px solid #e5e7eb;
    }

    .report-btn-view:hover {
        background: #e5e7eb;
    }

    @media (max-width: 768px) {
        .report-card {
            padding: 1rem;
        }

        .report-card-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }

        .report-card-title {
            font-size: 1rem;
        }

        .report-card-footer {
            flex-direction: column;
        }

        .report-btn {
            justify-content: center;
        }
    }
</style>

<div class="report-card">
    <div class="report-card-header">
        <div class="report-card-icon">
            <i class="<?php echo htmlspecialchars($icon); ?>"></i>
        </div>
        <div class="report-card-content">
            <h3 class="report-card-title"><?php echo htmlspecialchars($title); ?></h3>
            <p class="report-card-description"><?php echo htmlspecialchars($description); ?></p>
        </div>
    </div>
    <div class="report-card-footer">
        <button class="report-btn report-btn-download" onclick="downloadReport('<?php echo htmlspecialchars($download_url); ?>')">
            <i class="fas fa-download"></i> Download PDF
        </button>
    </div>
</div>

<script>
function downloadReport(url) {
    if (url === '#') {
        alert('Report download functionality coming soon!');
        return;
    }
    window.location.href = url;
}
</script>
