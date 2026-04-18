<?php
/**
 * Download Report Button Component
 *
 * Renders a styled "Download PDF" button that, when clicked, opens a
 * print-ready version of any table in a popup window.
 *
 * Requires:  report_downloader.php  (the JS engine) to be included on the
 *            same page — either via the base layout or manually.
 *
 * @param array $config {
 *   @type string   table_selector   CSS selector of the <table> to export.  Required.
 *   @type string   report_title     Title shown at the top of the PDF.       Required.
 *   @type string   report_subtitle  Subtitle / description line.             Optional.
 *   @type string[] columns          Ordered list of column header labels.    Optional (auto-reads <th>).
 *   @type string   btn_id           id="" for the button.  Default: auto-generated.
 *   @type string   btn_label        Button text.            Default: 'Download PDF'.
 *   @type string   btn_class        Extra CSS classes.      Default: 'btn btn-sm btn-outline-primary'.
 *   @type string   btn_icon         FA icon class.         Default: 'fas fa-file-pdf'.
 * }
 *
 * Usage example:
 *   <?php
 *   $config = [
 *     'table_selector' => '.recent-uploads-table',
 *     'report_title'   => 'CEB SMS Upload History',
 *     'report_subtitle'=> 'A complete record of your uploaded CEB electricity bill messages',
 *     'columns'        => ['Reading Date','Export Reading','Import Reading','Consumption','Bill Amount'],
 *     'btn_id'         => 'btn-download-pdf',
 *   ];
 *   require APPROOT . '/views/inc/components/download_report_btn.php';
 *   ?>
 */

if (!isset($config)) $config = [];

// ── Resolve config ──────────────────────────────────────────────────────────
$drb_tableSelector = $config['table_selector']  ?? '';
$drb_title         = $config['report_title']    ?? 'Report';
$drb_subtitle      = $config['report_subtitle'] ?? '';
$drb_columns       = $config['columns']         ?? [];          // [] = auto-read from <th>
$drb_btnId         = $config['btn_id']          ?? 'ss-report-btn-' . substr(md5($drb_tableSelector . $drb_title), 0, 6);
$drb_label         = $config['btn_label']       ?? 'Download PDF';
$drb_class         = $config['btn_class']       ?? 'btn btn-sm btn-outline-primary rounded-lg';
$drb_icon          = $config['btn_icon']        ?? 'fas fa-file-pdf';

// Encode the JS-side options as a JSON object
$drb_jsOptions = htmlspecialchars(json_encode([
    'tableSelector' => $drb_tableSelector,
    'title'         => $drb_title,
    'subtitle'      => $drb_subtitle,
    'columns'       => $drb_columns,
], JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
?>

<button
    id="<?php echo htmlspecialchars($drb_btnId); ?>"
    class="<?php echo htmlspecialchars($drb_class); ?> d-flex align-items-center gap-2"
    onclick="SolarSenseReport.download(<?php echo $drb_jsOptions; ?>, this)"
    type="button">
    <i class="<?php echo htmlspecialchars($drb_icon); ?>"></i>
    <?php echo htmlspecialchars($drb_label); ?>
</button>
