<?php
/**
 * Report Downloader — JavaScript Engine
 *
 * Provides the global JS object `SolarSenseReport` used by download_report_btn.php.
 * Include this file ONCE per page (ideally in the base layout or at the bottom of
 * the <body>), before any download buttons are clicked.
 *
 * The component scrapes any HTML <table> on the live page, builds a polished
 * print-ready popup window, then triggers the browser's Print/Save-as-PDF dialog.
 *
 * No external libraries are required.
 */
?>
<script>
/* ============================================================
   SolarSenseReport — Global PDF Report Downloader
   ============================================================ */
var SolarSenseReport = (function () {

    /* ── Helpers ─────────────────────────────────────────── */
    function _timestamp() {
        return new Date().toLocaleString('en-GB', {
            day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    }

    function _year() { return new Date().getFullYear(); }

    /* ── Build the print window HTML ─────────────────────── */
    function _buildHtml(opts, rowsHtml, headersHtml) {
        var title    = opts.title    || 'Report';
        var subtitle = opts.subtitle || '';
        var now      = _timestamp();
        var year     = _year();

        return '<!DOCTYPE html>\n' +
        '<html>\n' +
        '<head>\n' +
        '  <meta charset="UTF-8">\n' +
        '  <title>' + _esc(title) + ' – SolarSense</title>\n' +
        '  <style>\n' +
        '    * { margin:0; padding:0; box-sizing:border-box; }\n' +
        '    body { font-family:"Segoe UI",Arial,sans-serif; color:#212121; padding:36px; background:#fff; }\n' +
        '    .ss-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:18px; }\n' +
        '    .ss-brand { display:flex; align-items:center; gap:8px; }\n' +
        '    .ss-brand-icon { width:32px; height:32px; background:#fe9630; border-radius:8px;\n' +
        '                     display:flex; align-items:center; justify-content:center;\n' +
        '                     color:#fff; font-size:16px; font-weight:700; }\n' +
        '    .ss-brand-name { font-size:20px; font-weight:700; color:#fe9630; }\n' +
        '    .ss-meta { text-align:right; }\n' +
        '    .ss-gen-date { font-size:11px; color:#6b7280; }\n' +
        '    .ss-divider { border:none; border-top:2px solid #e5e7eb; margin:0 0 16px; }\n' +
        '    .ss-title { font-size:20px; font-weight:700; color:#111827; margin-bottom:4px; }\n' +
        '    .ss-subtitle { font-size:11px; color:#6b7280; margin-bottom:22px; }\n' +
        '    table { width:100%; border-collapse:collapse; font-size:12px; }\n' +
        '    thead tr { background:#fe9630; color:#fff; }\n' +
        '    th { padding:10px 12px; text-align:left; font-size:11px; text-transform:uppercase; letter-spacing:.5px; }\n' +
        '    td { padding:9px 12px; border-bottom:1px solid #e5e7eb; color:#374151; }\n' +
        '    tbody tr:nth-child(even) td { background:#f9fafb; }\n' +
        '    .ss-footer { margin-top:28px; font-size:10px; color:#9ca3af; text-align:center;\n' +
        '                 border-top:1px solid #e5e7eb; padding-top:12px; }\n' +
        '    @media print { @page { margin:1cm; } }\n' +
        '  </style>\n' +
        '</head>\n' +
        '<body>\n' +
        '  <div class="ss-header">\n' +
        '    <div class="ss-brand">\n' +
        '      <div class="ss-brand-icon">S</div>\n' +
        '      <span class="ss-brand-name">SolarSense</span>\n' +
        '    </div>\n' +
        '    <div class="ss-meta"><div class="ss-gen-date">Generated: ' + now + '</div></div>\n' +
        '  </div>\n' +
        '  <hr class="ss-divider">\n' +
        '  <div class="ss-title">' + _esc(title) + '</div>\n' +
        (subtitle ? '  <div class="ss-subtitle">' + _esc(subtitle) + '</div>\n' : '') +
        '  <table>\n' +
        '    <thead><tr>' + headersHtml + '</tr></thead>\n' +
        '    <tbody>' + rowsHtml + '</tbody>\n' +
        '  </table>\n' +
        '  <div class="ss-footer">SolarSense &mdash; Smart Solar Monitoring &bull; ' + year + '</div>\n' +
        '</body>\n</html>';
    }

    /* ── Escape HTML entities ─────────────────────────────── */
    function _esc(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    /* ── Scrape table headers from <th> elements ──────────── */
    function _scrapeHeaders(table) {
        var ths = table.querySelectorAll('thead th');
        var labels = [];
        ths.forEach(function (th) { labels.push(th.innerText.trim()); });
        return labels;
    }

    /* ── Scrape visible tbody rows ────────────────────────── */
    function _scrapeRows(table) {
        var trs = table.querySelectorAll('tbody tr');
        var html = '';
        trs.forEach(function (tr) {
            // Skip rows that are hidden (e.g., filtered out by JS)
            if (tr.style.display === 'none' || tr.hidden) return;
            var tds = tr.querySelectorAll('td');
            html += '<tr>';
            tds.forEach(function (td) { html += '<td>' + _esc(td.innerText.trim()) + '</td>'; });
            html += '</tr>';
        });
        return html;
    }

    /* ── Public API ──────────────────────────────────────── */
    return {
        /**
         * download(opts, btnEl)
         *
         * @param {object} opts
         *   tableSelector {string}   - CSS selector of the table
         *   title         {string}   - Report title
         *   subtitle      {string}   - Report subtitle (optional)
         *   columns       {string[]} - Override column headers (optional)
         * @param {HTMLElement} btnEl - The clicked button (for loading state)
         */
        download: function (opts, btnEl) {
            opts = opts || {};

            /* ── locate table ─────────────────────────────── */
            var table = opts.tableSelector
                ? document.querySelector(opts.tableSelector)
                : null;

            if (!table) {
                alert('Report Error: table not found (' + (opts.tableSelector || 'no selector') + ').');
                return;
            }

            /* ── loading state ────────────────────────────── */
            var originalHtml = '';
            if (btnEl) {
                originalHtml = btnEl.innerHTML;
                btnEl.disabled = true;
                btnEl.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Preparing…';
            }

            /* ── build content ────────────────────────────── */
            var columns = (opts.columns && opts.columns.length)
                ? opts.columns
                : _scrapeHeaders(table);

            var headersHtml = columns.map(function (c) {
                return '<th>' + _esc(c) + '</th>';
            }).join('');

            var rowsHtml = _scrapeRows(table);

            if (!rowsHtml) {
                if (btnEl) {
                    btnEl.disabled = false;
                    btnEl.innerHTML = originalHtml;
                }
                alert('No data to export.');
                return;
            }

            var html = _buildHtml(opts, rowsHtml, headersHtml);

            /* ── open popup & print ───────────────────────── */
            var win = window.open('', '_blank', 'width=880,height=620');
            if (!win) {
                alert('Popup blocked! Please allow popups for this site.');
                if (btnEl) {
                    btnEl.disabled = false;
                    btnEl.innerHTML = originalHtml;
                }
                return;
            }

            win.document.write(html);
            win.document.close();
            win.focus();

            var _restore = function () {
                if (btnEl) {
                    btnEl.disabled = false;
                    btnEl.innerHTML = originalHtml;
                }
            };

            win.onload = function () {
                win.print();
                setTimeout(function () { if (!win.closed) win.close(); }, 300);
                _restore();
            };

            // Fallback: if onload already fired
            setTimeout(function () {
                if (!win.closed) { win.print(); setTimeout(function () { if (!win.closed) win.close(); }, 300); }
                _restore();
            }, 900);
        }
    };
}());
</script>
