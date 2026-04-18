<!-- Link to custom CSS file for this page -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/pages/homeowner/uploadsms.css">

<div class="content-area">
    <!-- Page Header -->
    <?php
    $pageHeaderConfig = [
        'title' => 'Upload CEB Message',
        'description' => 'Import your electricity bill SMS to track consumption and optimize your solar system',
        'show_back' => true,
        'back_url' => URLROOT . '/homeowner/dashboard',
        'back_label' => 'Back to Dashboard'
    ];
    $config = $pageHeaderConfig;
    require APPROOT . '/views/inc/components/page_header.php';
    ?>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Left Column - Upload Form -->
        <div class="col-lg-8">
            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <h3 class="card-title text-2xl font-semibold mb-6">Import CEB Bill Message</h3>

                    <form id="smsUploadForm" action="<?php echo URLROOT ?>/homeowner/dashboard/uploadsms" method="POST" novalidate enctype="multipart/form-data">
                        <!-- SMS Content Textarea -->
                        <div class="mb-4">
                            <?php
                            $sampleSMS = "A/C No: 0510021204 (D1-NET MET)\nMR.V.S. RAMANAYAKA\n\nB/F: Rs. -3,630.80\nPayments: Rs. 0.00\nOutstanding: Rs. -3,630.80 by 2025-01-28\n\nReading Date: 2025-02-05 (438)\nB/F Units: 3474\nReadings: 43015(E), 39508(I)\nPrv. Readings: 42451(E), 38976(I)\nConsumption: 564 Unit\nCharge: Rs. 87.50\nSSC Levy: Rs. 2.24\nMonthly Bill: Rs. 89.74\n\nTotal Due: Rs. -3,541.06\n\nC/F Units: 3506";

                            $textareaConfig = [
                                'id' => 'smsContent',
                                'name' => 'smsContent',
                                'label' => 'Paste CEB SMS Message',
                                'value' => $sampleSMS,
                                'error' => $data['smsContent_err'] ?? '',
                                'rows' => 6,
                                'placeholder' => 'Copy and paste the entire SMS message from CEB exactly as received',
                                'required' => true,
                                'editable' => true,
                                'wrapperClass' => 'mb-3'
                            ];
                            $config = $textareaConfig;
                            require APPROOT . '/views/inc/components/textarea_field.php';
                            ?>
                            <small class="text-secondary d-block mt-2">
                                <i class="fas fa-circle-info mr-1"></i>
                                Paste the entire SMS message exactly as you received it from CEB
                            </small>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 mt-6">
                            <button type="submit" class="btn btn-primary rounded-lg">
                                <i class="fas fa-cloud-upload-alt mr-2"></i>Upload Message
                            </button>
                            <button type="reset" class="btn btn-secondary rounded-lg">
                                <i class="fas fa-redo mr-2"></i>Clear
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Instructions & Info -->
        <div class="col-lg-4">
            <!-- Instructions Card -->
            <div class="card shadow-lg rounded-xl mb-4">
                <div class="card-body">
                    <h3 class="card-title text-xl font-semibold mb-4">
                        How to Upload
                    </h3>
                    <ol class="list-steps">
                        <li>Open the SMS from CEB on your phone</li>
                        <li>Select and copy the entire message</li>
                        <li>Paste it in the text box</li>
                        <li>Click "Upload Message"</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Uploads Section -->
    <div class="row mt-6">
        <div class="col-12">
            <div class="card shadow-lg rounded-xl">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h3 class="card-title text-2xl font-semibold mb-0">Recent Uploads</h3>
                        <?php if (!empty($data['recentUploads'])): ?>
                        <button id="btn-download-pdf" onclick="printSMSTable()" class="btn btn-sm btn-outline-primary rounded-lg d-flex align-items-center gap-2">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </button>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($data['recentUploads'])): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 recent-uploads-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-sm font-semibold text-secondary">Reading Date</th>
                                        <th class="text-sm font-semibold text-secondary">Export Reading</th>
                                        <th class="text-sm font-semibold text-secondary">Import Reading</th>
                                        <th class="text-sm font-semibold text-secondary">Consumption</th>
                                        <th class="text-sm font-semibold text-secondary">Bill Amount</th>
                                        <!-- <th class="text-sm font-semibold text-secondary">Status</th>
                                        <th class="text-sm font-semibold text-secondary">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['recentUploads'] as $upload): ?>
                                        <tr>
                                            <td class="text-sm">
                                                <?php echo htmlspecialchars($upload->reading_date); ?>
                                            </td>
                                            <td class="text-sm">
                                                <?php echo htmlspecialchars($upload->export_reading); ?>
                                            </td>
                                            <td class="text-sm">
                                                <?php echo htmlspecialchars($upload->import_reading); ?>
                                            </td>
                                            <td class="text-sm">
                                                <?php echo htmlspecialchars($upload->consumption_units); ?>
                                            </td>
                                            <td class="text-sm font-semibold">
                                                Rs. <?php echo htmlspecialchars($upload->monthly_bill); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-6">
                            <i class="fas fa-inbox text-4xl text-secondary opacity-50 mb-3 d-block"></i>
                            <p class="text-secondary">No messages uploaded yet. Start by uploading your first CEB message!
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===================== Print Script ===================== -->
<script>
function printSMSTable() {
    var btn = document.getElementById('btn-download-pdf');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Preparing...';
    }

    // Collect table rows from the live page table
    var rows = document.querySelectorAll('.recent-uploads-table tbody tr');
    var rowsHTML = '';
    rows.forEach(function(row) {
        var cells = row.querySelectorAll('td');
        rowsHTML += '<tr>';
        cells.forEach(function(td) { rowsHTML += '<td>' + td.innerText + '</td>'; });
        rowsHTML += '</tr>';
    });

    var now = new Date().toLocaleString('en-GB', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });

    var win = window.open('', '_blank', 'width=850,height=600');
    win.document.write(`<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>CEB SMS Upload History - SolarSense</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; color: #1a1a2e; padding: 36px; background: #fff; }
    .logo-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
    .brand { font-size: 22px; font-weight: 700; color: #4f46e5; }
    .gen-date { font-size: 11px; color: #6b7280; }
    h2 { font-size: 18px; font-weight: 700; margin: 12px 0 4px; color: #111827; }
    .subtitle { font-size: 11px; color: #6b7280; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; font-size: 12px; }
    thead tr { background: #4f46e5; color: #fff; }
    th { padding: 9px 12px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
    td { padding: 8px 12px; border-bottom: 1px solid #e5e7eb; color: #374151; }
    tbody tr:nth-child(even) td { background: #f9fafb; }
    .footer { margin-top: 24px; font-size: 10px; color: #9ca3af; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    @media print { @page { margin: 1cm; } }
  </style>
</head>
<body>
  <div class="logo-row">
    <span class="brand">&#9728; SolarSense</span>
    <span class="gen-date">Generated: ${now}</span>
  </div>
  <h2>CEB SMS Upload History</h2>
  <p class="subtitle">A complete record of your uploaded CEB electricity bill messages</p>
  <table>
    <thead>
      <tr>
        <th>Reading Date</th>
        <th>Export Reading</th>
        <th>Import Reading</th>
        <th>Consumption (Units)</th>
        <th>Bill Amount (Rs.)</th>
      </tr>
    </thead>
    <tbody>${rowsHTML}</tbody>
  </table>
  <p class="footer">SolarSense &mdash; Smart Solar Monitoring &bull; ${new Date().getFullYear()}</p>
</body>
</html>`);

    win.document.close();
    win.focus();
    // Wait for content to render, then print
    win.onload = function() {
        win.print();
        win.close();
    };
    // Fallback if onload already fired
    setTimeout(function() {
        if (!win.closed) { win.print(); win.close(); }
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-file-pdf"></i> Download PDF';
        }
    }, 800);
}
</script>