/**
 * ==========================================================================
 * SSPTEX Luxury Admin Panel - Unified Data Table Engine
 * Author: Antigravity
 * Features:
 *   - Universal Pagination with customizable page sizes (10, 25, 50, 100, All)
 *   - Instant Live Search with multi-word fuzzy matching & clear trigger
 *   - Column Sorting (Numeric, Currency, Dates, Strings)
 *   - Native Excel Export (.xlsx via SheetJS with CSV fallback)
 *   - Professional PDF Export (Vector PDF with luxury branding via jsPDF/autoTable)
 *   - Print-ready report generation
 * ==========================================================================
 */

(function () {
    'use strict';

    /**
     * Toast notification utility
     */
    function showToast(message, type = 'success') {
        const existing = document.querySelector('.dt-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.className = `dt-toast ${type}`;
        const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-info';
        toast.innerHTML = `<i class="fa-solid ${icon}"></i> <span>${escapeHtml(message)}</span>`;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(10px)';
            setTimeout(() => toast.remove(), 300);
        }, 3200);
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /**
     * Determines whether a column is an action/media column that should not be sorted or exported
     */
    function isActionOrMediaColumn(th, text) {
        if (th.hasAttribute('data-dt-no-export') || th.hasAttribute('data-dt-no-sort')) {
            return true;
        }
        const cleaned = (text || '').trim().toLowerCase();
        const keywords = ['action', 'actions', 'image', 'images', 'photo', 'photos', 'media', 'preview', 'thumbnail', 'edit', 'delete'];
        return keywords.some(k => cleaned === k || cleaned.startsWith(k + ' ') || cleaned.endsWith(' ' + k));
    }

    /**
     * Clean cell text for search and exports
     */
    function getCellCleanText(cell) {
        if (!cell) return '';
        // If cell has input or select
        const inputs = cell.querySelectorAll('input, select, textarea');
        if (inputs.length > 0) {
            const vals = [];
            inputs.forEach(inp => {
                if (inp.type !== 'hidden' && inp.type !== 'checkbox' && inp.type !== 'radio') {
                    vals.push(inp.value);
                } else if ((inp.type === 'checkbox' || inp.type === 'radio') && inp.checked) {
                    vals.push(inp.value || 'checked');
                }
            });
            if (vals.length > 0) return vals.join(' ').trim();
        }

        // Clone node to safely remove script/style tags if any
        const clone = cell.cloneNode(true);
        // Remove interactive action buttons and dropdown menus from export/search
        const ignorable = clone.querySelectorAll('.btn-action, .btn-danger, .btn-edit, .btn-danger-icon, script, style, .dropdown-menu');
        ignorable.forEach(el => el.remove());

        let text = clone.textContent || clone.innerText || '';
        return text.replace(/\s+/g, ' ').trim();
    }

    /**
     * Extract page/card title for export filenames
     */
    function getTableTitle(table) {
        // Check closest card heading
        const card = table.closest('.card, .premium-card, .table-container');
        if (card) {
            const h = card.querySelector('h2, h3, .section-title');
            if (h) {
                const titleText = h.textContent.replace(/[^\w\s-]/gi, '').trim().replace(/\s+/g, '_');
                if (titleText) return titleText;
            }
        }
        // Fallback to page title or document title
        const topbarTitle = document.querySelector('.topbar-title');
        if (topbarTitle && topbarTitle.textContent) {
            return topbarTitle.textContent.replace(/[^\w\s-]/gi, '').trim().replace(/\s+/g, '_');
        }
        return 'Data_Report';
    }

    /**
     * Class to enhance a single HTML table
     */
    class LuxuryDataTable {
        constructor(table, options = {}) {
            this.table = table;
            this.options = Object.assign({
                defaultPageSize: 10,
                pageSizes: [10, 25, 50, 100, -1],
                enableExport: true,
                enableSearch: true,
                enablePagination: true,
                enableSort: true
            }, options);

            this.currentPage = 1;
            this.pageSize = this.options.defaultPageSize;
            this.searchQuery = '';
            this.sortColIndex = null;
            this.sortDirection = 'asc';

            this.thead = this.table.querySelector('thead');
            this.tbody = this.table.querySelector('tbody');

            if (!this.thead || !this.tbody) {
                // Table must have thead and tbody to be enhanced
                return;
            }

            // Extract original data rows (excluding empty-placeholder row if any)
            this.allRows = Array.from(this.tbody.querySelectorAll('tr')).filter(tr => {
                const isPlaceholder = tr.querySelector('td[colspan]') && 
                                     (tr.textContent.toLowerCase().includes('no ') || tr.textContent.toLowerCase().includes('found'));
                return !isPlaceholder;
            });

            this.filteredRows = [...this.allRows];
            this.headerCols = [];

            this.init();
        }

        init() {
            this.table.classList.add('luxury-dt-table');
            this.table.dataset.dtInitialized = 'true';

            this.buildWrapperAndControls();
            this.setupHeaderSorting();
            this.render();
        }

        buildWrapperAndControls() {
            // Container wrapper
            this.wrapper = document.createElement('div');
            this.wrapper.className = 'luxury-dt-wrapper';

            // Top Toolbar
            this.toolbar = document.createElement('div');
            this.toolbar.className = 'luxury-dt-toolbar';

            // Left actions: page size & export buttons
            const leftActions = document.createElement('div');
            leftActions.className = 'dt-left-actions';

            // Length selector
            const lengthSelector = document.createElement('div');
            lengthSelector.className = 'dt-length-selector';
            lengthSelector.innerHTML = `
                <span>Show</span>
                <select class="dt-select-size" aria-label="Entries per page">
                    <option value="10" ${this.pageSize === 10 ? 'selected' : ''}>10</option>
                    <option value="25" ${this.pageSize === 25 ? 'selected' : ''}>25</option>
                    <option value="50" ${this.pageSize === 50 ? 'selected' : ''}>50</option>
                    <option value="100" ${this.pageSize === 100 ? 'selected' : ''}>100</option>
                    <option value="-1" ${this.pageSize === -1 ? 'selected' : ''}>All</option>
                </select>
                <span>entries</span>
            `;
            this.sizeSelect = lengthSelector.querySelector('.dt-select-size');
            this.sizeSelect.addEventListener('change', (e) => {
                this.pageSize = parseInt(e.target.value, 10);
                this.currentPage = 1;
                this.render();
            });
            leftActions.appendChild(lengthSelector);

            // Export Buttons
            if (this.options.enableExport) {
                const exportGroup = document.createElement('div');
                exportGroup.className = 'dt-export-group';
                exportGroup.innerHTML = `
                    <button type="button" class="dt-btn dt-btn-excel" title="Download Excel Spreadsheet (.xlsx)">
                        <i class="fa-solid fa-file-excel"></i>
                        <span>Excel</span>
                    </button>
                    <button type="button" class="dt-btn dt-btn-pdf" title="Download Formatted PDF Document">
                        <i class="fa-solid fa-file-pdf"></i>
                        <span>PDF</span>
                    </button>
                    <button type="button" class="dt-btn dt-btn-print" title="Print Table Report">
                        <i class="fa-solid fa-print"></i>
                        <span>Print</span>
                    </button>
                `;

                exportGroup.querySelector('.dt-btn-excel').addEventListener('click', () => this.exportExcel());
                exportGroup.querySelector('.dt-btn-pdf').addEventListener('click', () => this.exportPDF());
                exportGroup.querySelector('.dt-btn-print').addEventListener('click', () => this.printTable());

                leftActions.appendChild(exportGroup);
            }

            this.toolbar.appendChild(leftActions);

            // Right actions: search box
            if (this.options.enableSearch) {
                const rightActions = document.createElement('div');
                rightActions.className = 'dt-right-actions';

                const searchBox = document.createElement('div');
                searchBox.className = 'dt-search-box';
                searchBox.innerHTML = `
                    <i class="fa-solid fa-magnifying-glass dt-search-icon"></i>
                    <input type="text" class="dt-search-input" placeholder="Search records..." aria-label="Search records">
                    <button type="button" class="dt-search-clear" title="Clear search">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                `;

                this.searchInput = searchBox.querySelector('.dt-search-input');
                this.searchClearBtn = searchBox.querySelector('.dt-search-clear');

                let debounceTimer = null;
                this.searchInput.addEventListener('input', (e) => {
                    clearTimeout(debounceTimer);
                    const val = e.target.value;
                    this.searchClearBtn.style.display = val.length > 0 ? 'inline-flex' : 'none';
                    debounceTimer = setTimeout(() => {
                        this.searchQuery = val.trim().toLowerCase();
                        this.filterData();
                    }, 150);
                });

                this.searchClearBtn.addEventListener('click', () => {
                    this.searchInput.value = '';
                    this.searchClearBtn.style.display = 'none';
                    this.searchQuery = '';
                    this.filterData();
                    this.searchInput.focus();
                });

                rightActions.appendChild(searchBox);
                this.toolbar.appendChild(rightActions);
            }

            // Scroll wrapper
            this.scrollWrapper = document.createElement('div');
            this.scrollWrapper.className = 'luxury-dt-scroll';

            // Insert wrapper into DOM
            const parent = this.table.parentNode;
            parent.insertBefore(this.wrapper, this.table);

            this.wrapper.appendChild(this.toolbar);
            this.scrollWrapper.appendChild(this.table);
            this.wrapper.appendChild(this.scrollWrapper);

            // Bottom Footer (Pagination)
            this.footer = document.createElement('div');
            this.footer.className = 'luxury-dt-footer';
            this.footer.innerHTML = `
                <div class="dt-pagination"></div>
            `;
            this.paginationEl = this.footer.querySelector('.dt-pagination');
            this.wrapper.appendChild(this.footer);

            // Hidden print header
            this.printHeader = document.createElement('div');
            this.printHeader.className = 'dt-print-header';
            this.printHeader.innerHTML = `
                <h2 style="font-size: 20px; font-weight: 800; margin: 0 0 6px 0; color: #1e293b;">SSPTEX ADMIN</h2>
                <h3 style="font-size: 15px; font-weight: 600; margin: 0 0 4px 0; color: #64748b;">${escapeHtml(getTableTitle(this.table).replace(/_/g, ' '))}</h3>
                <p style="font-size: 12px; color: #94a3b8; margin: 0 0 16px 0;">Generated on: ${new Date().toLocaleString()}</p>
            `;
            this.wrapper.insertBefore(this.printHeader, this.scrollWrapper);
        }

        setupHeaderSorting() {
            if (!this.options.enableSort) return;

            const headerRow = this.thead.querySelector('tr');
            if (!headerRow) return;

            const ths = Array.from(headerRow.querySelectorAll('th'));
            this.headerCols = ths.map((th, index) => {
                const text = th.textContent.trim();
                const isNoSort = isActionOrMediaColumn(th, text);

                if (!isNoSort) {
                    th.classList.add('dt-sortable');
                    const indicator = document.createElement('span');
                    indicator.className = 'dt-sort-indicator';
                    indicator.innerHTML = '<i class="fa-solid fa-sort"></i>';
                    th.appendChild(indicator);

                    th.addEventListener('click', () => {
                        this.handleSort(index);
                    });
                }

                return {
                    th,
                    index,
                    text,
                    sortable: !isNoSort,
                    exportable: !isActionOrMediaColumn(th, text)
                };
            });
        }

        handleSort(colIndex) {
            if (this.sortColIndex === colIndex) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColIndex = colIndex;
                this.sortDirection = 'asc';
            }

            // Update header UI
            const ths = this.thead.querySelectorAll('th');
            ths.forEach((th, idx) => {
                th.classList.remove('dt-asc', 'dt-desc');
                const ind = th.querySelector('.dt-sort-indicator');
                if (ind) {
                    ind.innerHTML = '<i class="fa-solid fa-sort"></i>';
                }
                if (idx === colIndex) {
                    th.classList.add(this.sortDirection === 'asc' ? 'dt-asc' : 'dt-desc');
                    if (ind) {
                        ind.innerHTML = this.sortDirection === 'asc' 
                            ? '<i class="fa-solid fa-sort-up"></i>' 
                            : '<i class="fa-solid fa-sort-down"></i>';
                    }
                }
            });

            // Perform sorting on filteredRows
            this.filteredRows.sort((trA, trB) => {
                const cellA = trA.children[colIndex];
                const cellB = trB.children[colIndex];
                const valA = getCellCleanText(cellA);
                const valB = getCellCleanText(cellB);

                // Detect numeric / currency
                const numA = this.parseNumeric(valA);
                const numB = this.parseNumeric(valB);

                if (numA !== null && numB !== null) {
                    return this.sortDirection === 'asc' ? numA - numB : numB - numA;
                }

                // Detect date
                const dateA = Date.parse(valA);
                const dateB = Date.parse(valB);
                if (!isNaN(dateA) && !isNaN(dateB) && valA.length > 5 && valB.length > 5) {
                    return this.sortDirection === 'asc' ? dateA - dateB : dateB - dateA;
                }

                // Default string locale compare
                const cmp = valA.localeCompare(valB, undefined, { numeric: true, sensitivity: 'base' });
                return this.sortDirection === 'asc' ? cmp : -cmp;
            });

            this.currentPage = 1;
            this.render();
        }

        parseNumeric(str) {
            if (!str) return null;
            // Clean currency symbols, commas, percent, hashtag
            const cleaned = str.replace(/[₹$,%#]/g, '').trim();
            if (/^-?\d+(\.\d+)?$/.test(cleaned)) {
                return parseFloat(cleaned);
            }
            return null;
        }

        filterData() {
            if (!this.searchQuery) {
                this.filteredRows = [...this.allRows];
            } else {
                const tokens = this.searchQuery.split(/\s+/).filter(t => t.length > 0);
                this.filteredRows = this.allRows.filter(tr => {
                    const cells = Array.from(tr.children);
                    // Combine searchable text
                    const combinedText = cells
                        .filter((cell, idx) => {
                            const colInfo = this.headerCols[idx];
                            return !colInfo || colInfo.sortable; // exclude action cells from search
                        })
                        .map(cell => getCellCleanText(cell))
                        .join(' ')
                        .toLowerCase();

                    return tokens.every(token => combinedText.includes(token));
                });
            }

            // Reapply sorting if active
            if (this.sortColIndex !== null) {
                const idx = this.sortColIndex;
                const dir = this.sortDirection;
                this.sortColIndex = null;
                this.handleSort(idx);
                if (dir === 'desc') this.handleSort(idx);
            }

            this.currentPage = 1;
            this.render();
        }

        render() {
            const totalItems = this.filteredRows.length;
            const effectivePageSize = this.pageSize === -1 ? totalItems : this.pageSize;
            const totalPages = Math.max(1, Math.ceil(totalItems / (effectivePageSize || 1)));

            if (this.currentPage > totalPages) {
                this.currentPage = totalPages;
            }

            const startIndex = (this.currentPage - 1) * effectivePageSize;
            const endIndex = this.pageSize === -1 ? totalItems : Math.min(startIndex + effectivePageSize, totalItems);

            // Hide all original rows first
            this.allRows.forEach(tr => {
                tr.style.display = 'none';
            });

            // Remove previous empty-state row if exists
            const prevEmpty = this.tbody.querySelector('.dt-empty-row');
            if (prevEmpty) prevEmpty.remove();

            if (totalItems === 0) {
                const colCount = Math.max(1, this.thead.querySelectorAll('th').length);
                const emptyTr = document.createElement('tr');
                emptyTr.className = 'dt-empty-row';
                emptyTr.innerHTML = `
                    <td colspan="${colCount}">
                        <div class="dt-empty-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <div class="dt-empty-title">No matching records found</div>
                            <div class="dt-empty-subtitle">${this.searchQuery ? `No results match your search "${escapeHtml(this.searchQuery)}"` : 'There are no records in this table.'}</div>
                        </div>
                    </td>
                `;
                this.tbody.appendChild(emptyTr);
            } else {
                // Show rows for current page and maintain sorted order
                for (let i = startIndex; i < endIndex; i++) {
                    if (this.filteredRows[i]) {
                        this.filteredRows[i].style.display = '';
                        this.tbody.appendChild(this.filteredRows[i]);
                    }
                }
            }

            // If total pages > 1 show pagination footer, otherwise hide footer cleanly
            if (totalPages > 1) {
                this.footer.style.display = 'flex';
                this.updatePagination(totalPages);
            } else {
                this.footer.style.display = 'none';
            }
        }

        updatePagination(totalPages) {
            this.paginationEl.innerHTML = '';
            if (totalPages <= 1) return;

            const createBtn = (html, page, isDisabled = false, isActive = false, title = '') => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `dt-page-btn ${isActive ? 'active' : ''} ${isDisabled ? 'disabled' : ''}`;
                btn.innerHTML = html;
                if (title) btn.title = title;
                if (!isDisabled && !isActive) {
                    btn.addEventListener('click', () => {
                        this.currentPage = page;
                        this.render();
                    });
                }
                return btn;
            };

            // First & Prev buttons
            this.paginationEl.appendChild(
                createBtn('<i class="fa-solid fa-angles-left"></i>', 1, this.currentPage === 1, false, 'First Page')
            );
            this.paginationEl.appendChild(
                createBtn('<i class="fa-solid fa-angle-left"></i>', this.currentPage - 1, this.currentPage === 1, false, 'Previous Page')
            );

            // Page numbers calculation with ellipsis
            const pages = [];
            if (totalPages <= 7) {
                for (let p = 1; p <= totalPages; p++) pages.push(p);
            } else {
                pages.push(1);
                if (this.currentPage > 3) pages.push('...');

                const startP = Math.max(2, this.currentPage - 1);
                const endP = Math.min(totalPages - 1, this.currentPage + 1);

                for (let p = startP; p <= endP; p++) {
                    if (!pages.includes(p)) pages.push(p);
                }

                if (this.currentPage < totalPages - 2) pages.push('...');
                if (!pages.includes(totalPages)) pages.push(totalPages);
            }

            pages.forEach(p => {
                if (p === '...') {
                    const span = document.createElement('span');
                    span.className = 'dt-page-ellipsis';
                    span.textContent = '...';
                    this.paginationEl.appendChild(span);
                } else {
                    this.paginationEl.appendChild(createBtn(p, p, false, p === this.currentPage));
                }
            });

            // Next & Last buttons
            this.paginationEl.appendChild(
                createBtn('<i class="fa-solid fa-angle-right"></i>', this.currentPage + 1, this.currentPage === totalPages, false, 'Next Page')
            );
            this.paginationEl.appendChild(
                createBtn('<i class="fa-solid fa-angles-right"></i>', totalPages, this.currentPage === totalPages, false, 'Last Page')
            );
        }

        /**
         * Collect clean table data for export
         */
        getExportData() {
            // Find valid exportable column indices
            const exportCols = [];
            const headers = [];

            const ths = this.thead.querySelectorAll('th');
            ths.forEach((th, idx) => {
                const text = th.textContent.trim();
                if (!isActionOrMediaColumn(th, text)) {
                    exportCols.push(idx);
                    headers.push(text);
                }
            });

            // Rows to export: use filteredRows (all filtered records, not just visible page)
            const rows = this.filteredRows.map(tr => {
                const rowData = [];
                exportCols.forEach(colIdx => {
                    const cell = tr.children[colIdx];
                    let text = getCellCleanText(cell);
                    rowData.push(text);
                });
                return rowData;
            });

            return { headers, rows, filename: `${getTableTitle(this.table)}_${new Date().toISOString().slice(0, 10)}` };
        }

        /**
         * Excel Export (.xlsx)
         */
        exportExcel() {
            const data = this.getExportData();
            if (data.rows.length === 0) {
                showToast('No records available to export', 'info');
                return;
            }

            if (window.XLSX) {
                try {
                    const aoa = [data.headers, ...data.rows];
                    const ws = window.XLSX.utils.aoa_to_sheet(aoa);

                    // Compute clean column auto-widths
                    const colWidths = data.headers.map((h, i) => {
                        let maxLen = h.length;
                        data.rows.forEach(r => {
                            if (r[i]) maxLen = Math.max(maxLen, String(r[i]).length);
                        });
                        return { wch: Math.min(Math.max(maxLen + 3, 10), 60) };
                    });
                    ws['!cols'] = colWidths;

                    const wb = window.XLSX.utils.book_new();
                    window.XLSX.utils.book_append_sheet(wb, ws, 'Report');
                    window.XLSX.writeFile(wb, `${data.filename}.xlsx`);

                    showToast(`Excel file "${data.filename}.xlsx" downloaded!`, 'success');
                    return;
                } catch (err) {
                    console.error('XLSX export error, falling back to CSV:', err);
                }
            }

            // Fallback: UTF-8 CSV with BOM for Excel
            this.exportCSV(data);
        }

        /**
         * Fallback CSV Export
         */
        exportCSV(data) {
            let csvContent = '\uFEFF'; // BOM for UTF-8 Excel support
            csvContent += data.headers.map(h => `"${h.replace(/"/g, '""')}"`).join(',') + '\r\n';
            data.rows.forEach(r => {
                csvContent += r.map(c => `"${String(c).replace(/"/g, '""')}"`).join(',') + '\r\n';
            });

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${data.filename}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);

            showToast(`Exported CSV "${data.filename}.csv"!`, 'success');
        }

        /**
         * Professional PDF Export using jsPDF and autoTable
         */
        exportPDF() {
            const data = this.getExportData();
            if (data.rows.length === 0) {
                showToast('No records available to export', 'info');
                return;
            }

            if (window.jspdf && window.jspdf.jsPDF) {
                try {
                    const { jsPDF } = window.jspdf;
                    const isLandscape = data.headers.length > 5;
                    const doc = new jsPDF({
                        orientation: isLandscape ? 'landscape' : 'portrait',
                        unit: 'mm',
                        format: 'a4'
                    });

                    const title = getTableTitle(this.table).replace(/_/g, ' ');
                    const pageWidth = doc.internal.pageSize.getWidth();

                    // Header Banner
                    doc.setFillColor(107, 15, 39); // Deep Maroon #6b0f27
                    doc.rect(0, 0, pageWidth, 24, 'F');

                    // Title
                    doc.setFont('helvetica', 'bold');
                    doc.setFontSize(14);
                    doc.setTextColor(255, 255, 255);
                    doc.text('SSPTEX ADMIN CONSOLE', 14, 11);

                    doc.setFont('helvetica', 'normal');
                    doc.setFontSize(10);
                    doc.setTextColor(243, 229, 171); // Soft Warm Gold
                    doc.text(title.toUpperCase(), 14, 18);

                    // Generated metadata on right
                    doc.setFontSize(8.5);
                    doc.setTextColor(255, 255, 255);
                    const dateStr = `Exported: ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;
                    doc.text(dateStr, pageWidth - 14, 15, { align: 'right' });

                    // Generate autoTable
                    doc.autoTable({
                        head: [data.headers],
                        body: data.rows,
                        startY: 30,
                        margin: { left: 14, right: 14, bottom: 18 },
                        theme: 'striped',
                        headStyles: {
                            fillColor: [107, 15, 39],
                            textColor: [255, 255, 255],
                            fontStyle: 'bold',
                            fontSize: 9,
                            cellPadding: 3.5
                        },
                        bodyStyles: {
                            fontSize: 8.5,
                            textColor: [30, 41, 59],
                            cellPadding: 3
                        },
                        alternateRowStyles: {
                            fillColor: [248, 250, 252]
                        },
                        didDrawPage: (hookData) => {
                            // Footer with page numbering
                            const pageNumber = doc.internal.getNumberOfPages();
                            doc.setFontSize(8);
                            doc.setTextColor(148, 163, 184);
                            doc.text(
                                `Page ${hookData.pageNumber} of ${pageNumber} • Confidential Admin Document`,
                                14,
                                doc.internal.pageSize.getHeight() - 8
                            );
                        }
                    });

                    doc.save(`${data.filename}.pdf`);
                    showToast(`PDF report "${data.filename}.pdf" downloaded!`, 'success');
                    return;
                } catch (err) {
                    console.error('jsPDF autoTable error:', err);
                }
            }

            // Fallback: trigger print
            showToast('PDF library busy, opening printable view...', 'info');
            this.printTable();
        }

        /**
         * Print Table View
         */
        printTable() {
            window.print();
        }
    }

    /**
     * Universal Initializer for all eligible tables
     */
    function initAllDataTables() {
        // Query potential tables
        const candidateTables = document.querySelectorAll(
            'table:not([data-dt-disable]):not([data-dt-initialized="true"])'
        );

        candidateTables.forEach(table => {
            // Check exclusion classes
            if (
                table.classList.contains('cat-manager-table') ||
                table.classList.contains('variant-table') ||
                table.classList.contains('items-table') ||
                table.classList.contains('totals-table') ||
                table.closest('.admin-profile-modal-backdrop') ||
                table.closest('#variant-table')
            ) {
                return;
            }

            // Must have thead and tbody with at least 1 th
            const thead = table.querySelector('thead');
            const tbody = table.querySelector('tbody');
            if (!thead || !tbody) return;
            if (thead.querySelectorAll('th').length === 0) return;

            new LuxuryDataTable(table);
        });
    }

    // Expose API to window
    window.LuxuryDataTable = LuxuryDataTable;
    window.initAllDataTables = initAllDataTables;

    // Auto-initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAllDataTables);
    } else {
        initAllDataTables();
    }
})();
