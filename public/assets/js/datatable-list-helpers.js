/**
 * Shared DataTables helpers: length menu, print-all (server-side), invoice-style print header.
 * Expects window.appStore = { logo, name, owner, phone, email, address }.
 */
(function (window, $) {
    'use strict';

    var LENGTH_MENU = [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    var DOM_STANDARD =
        "<'row align-items-center m-2'<'col-sm-12 col-md-5'B><'col-sm-12 col-md-7'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row align-items-center'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-4'i><'col-sm-12 col-md-5'p>>";

    var DOM_REPORT =
        "<'row align-items-center mx-1 mt-2 mb-3'<'col-sm-12 col-md-5'B><'col-sm-12 col-md-7'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row align-items-center mt-3'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-4'i><'col-sm-12 col-md-5'p>>";

    function escapeHtml(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function getStore() {
        return window.appStore || {
            logo: '',
            name: 'Store',
            owner: '',
            phone: '',
            email: '',
            address: ''
        };
    }

    function printAllFilteredRows(e, dt, button, config) {
        var self = this;
        var settings = dt.settings()[0];

        // Client-side tables: expand to all rows, print, then restore page length.
        if (!settings.oFeatures.bServerSide) {
            var oldLen = dt.page.len();
            dt.one('draw', function () {
                $.fn.dataTable.ext.buttons.print.action.call(self, e, dt, button, config);
                dt.page.len(oldLen).draw(false);
            });
            dt.page.len(-1).draw();
            return;
        }

        var oldStart = settings._iDisplayStart;
        var total = dt.page.info().recordsDisplay;

        dt.one('preXhr', function (ev, s, data) {
            data.start = 0;
            data.length = total > 0 ? total : -1;
        });

        dt.one('draw', function () {
            $.fn.dataTable.ext.buttons.print.action.call(self, e, dt, button, config);
            dt.one('preXhr', function (ev, s, data) {
                data.start = oldStart;
            });
            setTimeout(function () {
                dt.ajax.reload(null, false);
            }, 0);
        });

        dt.ajax.reload();
    }

    function customizePrint(win, options) {
        options = options || {};
        var store = getStore();
        var doc = win.document;
        var $body = $(doc.body);
        var now = new Date();
        var generated = now.toLocaleString(undefined, {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        var filterSummary = typeof options.getFilterSummary === 'function'
            ? (options.getFilterSummary() || '')
            : (options.filterSummary || '');
        var docTitle = options.docTitle || 'LIST';
        var metaLines = [];

        if (store.owner) {
            metaLines.push(escapeHtml(store.owner));
        }
        if (store.phone) {
            metaLines.push('Mobile: ' + escapeHtml(store.phone));
        }
        if (store.email) {
            metaLines.push('Email: ' + escapeHtml(store.email));
        }
        if (store.address) {
            metaLines.push(escapeHtml(store.address));
        }

        $('<style>').text(
            'body{font-family:DejaVu Sans,Arial,sans-serif;font-size:11px;color:#1a1a1a;margin:24px;}' +
            'h1{display:none!important;}' +
            '.print-invoice-header{width:100%;border-bottom:2px solid #1e3a5f;padding-bottom:14px;margin-bottom:14px;}' +
            '.print-invoice-header table{width:100%;border-collapse:collapse;}' +
            '.print-invoice-header td{vertical-align:top;}' +
            '.print-logo{max-height:64px;max-width:140px;}' +
            '.print-store-name{font-size:20px;font-weight:700;color:#1e3a5f;margin:0 0 4px;}' +
            '.print-store-meta{font-size:10px;color:#444;line-height:1.55;margin:0;}' +
            '.print-doc-row{display:flex;justify-content:space-between;align-items:center;margin:0 0 16px;gap:12px;}' +
            '.print-doc-badge{display:inline-block;background:#1e3a5f;color:#fff;font-size:12px;font-weight:700;letter-spacing:1px;padding:8px 14px;}' +
            '.print-doc-meta{text-align:right;font-size:10px;color:#555;line-height:1.6;}' +
            '.print-filter-line{font-size:10px;color:#555;margin:0 0 12px;}' +
            'table.dataTable{width:100%!important;border-collapse:collapse;}' +
            'table.dataTable thead th{background:#eee!important;border-bottom:1px solid #ccc;padding:6px 8px;text-align:left;}' +
            'table.dataTable tbody td{border-bottom:1px solid #e5e5e5;padding:6px 8px;}'
        ).appendTo(doc.head);

        var headerHtml =
            '<div class="print-invoice-header">' +
            '<table><tr>' +
            '<td width="28%">' +
            (store.logo
                ? '<img class="print-logo" src="' + escapeHtml(store.logo) + '" alt="' + escapeHtml(store.name) + '">'
                : '') +
            '</td>' +
            '<td width="72%" style="text-align:right;">' +
            '<p class="print-store-name">' + escapeHtml(store.name) + '</p>' +
            '<p class="print-store-meta">' + metaLines.join('<br>') + '</p>' +
            '</td></tr></table></div>' +
            '<div class="print-doc-row">' +
            '<span class="print-doc-badge">' + escapeHtml(docTitle) + '</span>' +
            '<span class="print-doc-meta">Generated: ' + escapeHtml(generated) + '</span>' +
            '</div>';

        if (filterSummary) {
            headerHtml += '<p class="print-filter-line">Filters: ' + escapeHtml(filterSummary) + '</p>';
        }

        $body.prepend(headerHtml);
    }

    function makePrintButton(options) {
        options = options || {};
        return {
            extend: 'print',
            text: 'Print',
            title: '',
            exportOptions: {
                columns: options.columns || ':visible'
            },
            customize: function (win) {
                customizePrint(win, options);
            },
            action: printAllFilteredRows
        };
    }

    function makeExportButtons(options) {
        return [
            { extend: 'copy', text: 'Copy' },
            { extend: 'excel', text: 'Excel' },
            { extend: 'pdf', text: 'PDF' },
            makePrintButton(options)
        ];
    }

    window.DtListHelpers = {
        lengthMenu: LENGTH_MENU,
        dom: DOM_STANDARD,
        reportDom: DOM_REPORT,
        printAllFilteredRows: printAllFilteredRows,
        customizePrint: customizePrint,
        makePrintButton: makePrintButton,
        makeExportButtons: makeExportButtons
    };
})(window, jQuery);
