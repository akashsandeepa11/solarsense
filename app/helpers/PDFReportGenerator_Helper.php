<?php
/**
 * PDF Report Generator Helper
 * Generates PDF reports with consistent theme and branding
 */

class PDFReportGenerator {
    
    private $title;
    private $role;
    private $period;
    private $data;
    private $filename;
    
    // Theme colors
    private $colors = [
        'primary' => '#fe9630',
        'success' => '#22c55e',
        'warning' => '#f59e0b',
        'error' => '#ef4444',
        'info' => '#3b82f6',
        'dark' => '#212121',
        'light' => '#f9fafb',
        'border' => '#e5e7eb'
    ];
    
    public function __construct($title, $role, $period = 'all', $data = []) {
        $this->title = $title;
        $this->role = $role;
        $this->period = $period;
        $this->data = $data;
        $this->filename = sanitizeFilename($title) . '_' . date('Y-m-d') . '.pdf';
    }
    
    /**
     * Generate HTML content for PDF
     */
    public function generateHTML() {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            color: ' . $this->colors['dark'] . ';
            line-height: 1.6;
        }
        
        .header {
            background: linear-gradient(135deg, ' . $this->colors['primary'] . ' 0%, #fd7e14 100%);
            color: white;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header-meta {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            opacity: 0.9;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: ' . $this->colors['primary'] . ';
            border-bottom: 2px solid ' . $this->colors['primary'] . ';
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            border: 1px solid ' . $this->colors['border'] . ';
            text-align: center;
            vertical-align: middle;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: ' . $this->colors['primary'] . ';
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6b7280;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background: ' . $this->colors['primary'] . ';
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid ' . $this->colors['border'] . ';
            font-size: 12px;
        }
        
        tr:nth-child(even) {
            background: ' . $this->colors['light'] . ';
        }
        
        .status-good {
            color: ' . $this->colors['success'] . ';
            font-weight: bold;
        }
        
        .status-warning {
            color: ' . $this->colors['warning'] . ';
            font-weight: bold;
        }
        
        .status-error {
            color: ' . $this->colors['error'] . ';
            font-weight: bold;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid ' . $this->colors['border'] . ';
            font-size: 11px;
            color: #9ca3af;
            text-align: center;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .highlight {
            background: rgba(' . hexToRgb($this->colors['primary']) . ', 0.1);
            padding: 15px;
            border-left: 4px solid ' . $this->colors['primary'] . ';
            margin-bottom: 15px;
        }
    </style>
</head>
<body>';
        
        $html .= $this->getHeaderHTML();
        $html .= $this->getContentHTML();
        $html .= $this->getFooterHTML();
        
        $html .= '</body></html>';
        
        return $html;
    }
    
    private function getHeaderHTML() {
        $generatedDate = date('F d, Y');
        $periodText = $this->getPeriodText();
        
        return "
        <div class='header'>
            <h1>📊 {$this->title}</h1>
            <div class='header-meta'>
                <span>Role: <strong>{$this->role}</strong></span>
                <span>Period: <strong>{$periodText}</strong></span>
                <span>Generated: <strong>{$generatedDate}</strong></span>
            </div>
        </div>";
    }
    
    private function getContentHTML() {
        $html = '';
        
        if (is_array($this->data) && count($this->data) > 0) {
            foreach ($this->data as $key => $value) {
                $section_title = ucfirst(str_replace('_', ' ', $key));
                
                $html .= "<div class='section'>";
                $html .= "<div class='section-title'>{$section_title}</div>";
                
                if (is_array($value)) {
                    $html .= $this->renderDataTable($value);
                } else {
                    $html .= "<div class='highlight'><strong>{$value}</strong></div>";
                }
                
                $html .= "</div>";
            }
        } else {
            $html .= "<div class='highlight'>Report data will be populated with actual metrics</div>";
        }
        
        return $html;
    }
    
    private function renderDataTable($data) {
        if (empty($data)) {
            return '';
        }
        
        $html = '<table>';
        
        // Get keys from first item if it's an associative array
        if (is_array($data[0]) && !is_numeric(array_key_first($data[0]))) {
            $headers = array_keys($data[0]);
            $html .= '<thead><tr>';
            
            foreach ($headers as $header) {
                $html .= '<th>' . ucfirst(str_replace('_', ' ', $header)) . '</th>';
            }
            
            $html .= '</tr></thead><tbody>';
            
            foreach ($data as $row) {
                $html .= '<tr>';
                foreach ($headers as $header) {
                    $value = $row[$header] ?? '';
                    $html .= '<td>' . htmlspecialchars($value) . '</td>';
                }
                $html .= '</tr>';
            }
        } else {
            // Simple list
            $html .= '<tr><th>Item</th><th>Value</th></tr>';
            foreach ($data as $label => $value) {
                $displayLabel = ucfirst(str_replace('_', ' ', $label));
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $html .= '<tr><td>' . htmlspecialchars($displayLabel) . '</td><td>' . htmlspecialchars($value) . '</td></tr>';
            }
        }
        
        $html .= '</tbody></table>';
        
        return $html;
    }
    
    private function getFooterHTML() {
        $currentYear = date('Y');
        
        return "
        <div class='footer'>
            <p>SolarSense Report System © {$currentYear}</p>
            <p>This is a confidential document. Please handle with care.</p>
        </div>";
    }
    
    private function getPeriodText() {
        $periods = [
            'last_7' => 'Last 7 Days',
            'last_30' => 'Last 30 Days',
            'last_90' => 'Last 90 Days',
            'yearly' => 'This Year',
            'all_time' => 'All Time',
            '' => 'All Periods'
        ];
        
        return $periods[$this->period] ?? ucfirst($this->period);
    }
    
    public function getFilename() {
        return $this->filename;
    }
}

/**
 * Helper function to convert hex color to RGB
 */
function hexToRgb($hex) {
    $hex = str_replace('#', '', $hex);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return "{$r}, {$g}, {$b}";
}

/**
 * Helper function to sanitize filename
 */
function sanitizeFilename($filename) {
    $filename = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', $filename));
    return $filename;
}

/**
 * Generate PDF using HTML content (using a simple HTML to PDF approach)
 * Note: For production, use TCPDF or DOMPDF library
 */
function generatePDF($html, $filename) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    // For now, return HTML that can be printed as PDF
    // In production, integrate with TCPDF or DOMPDF:
    // $pdf = new TCPDF();
    // $pdf->AddPage();
    // $pdf->writeHTML($html);
    // $pdf->Output($filename, 'D');
    
    echo $html;
}

/**
 * Generate Excel file
 */
function generateExcel($data, $filename, $title) {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $csv = "SolarSense Report\n";
    $csv .= $title . "\n";
    $csv .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";
    
    foreach ($data as $section => $items) {
        $csv .= strtoupper($section) . "\n";
        
        if (is_array($items) && !empty($items)) {
            if (is_array($items[0])) {
                // Table data
                $headers = array_keys($items[0]);
                $csv .= implode(',', $headers) . "\n";
                
                foreach ($items as $row) {
                    $csv .= implode(',', array_map(function($v) {
                        return '"' . str_replace('"', '""', $v) . '"';
                    }, $row)) . "\n";
                }
            } else {
                // Key-value data
                foreach ($items as $key => $value) {
                    $csv .= $key . "," . (is_array($value) ? json_encode($value) : $value) . "\n";
                }
            }
        }
        
        $csv .= "\n";
    }
    
    echo $csv;
}

?>
