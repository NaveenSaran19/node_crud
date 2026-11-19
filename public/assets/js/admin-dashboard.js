/**
 * Admin Dashboard - Sales Chart & Export Logic
 */

let salesChart = null;
let currentLabels = [];
let currentData = [];
let salesDataEndpoint = '';

function initSalesDashboard() {
    const chartCanvas = document.getElementById('salesChart');
    if (!chartCanvas) return;

    if (typeof Chart === 'undefined') {
        const wrap = chartCanvas.parentElement;
        if (wrap) {
            wrap.innerHTML = '<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #94a3b8; font-size: 14px; gap: 8px;"><i class="fa-solid fa-chart-line" style="font-size: 32px; color: #cbd5e1;"></i><span>Chart library is loading or network is offline. Check internet connection for CDN.</span></div>';
        }
        return;
    }

    try {
        currentLabels = JSON.parse(chartCanvas.getAttribute('data-labels') || '[]');
        currentData = JSON.parse(chartCanvas.getAttribute('data-values') || '[]');
    } catch (e) {
        console.error('Failed to parse sales chart initial data', e);
        currentLabels = [];
        currentData = [];
    }

    salesDataEndpoint = chartCanvas.getAttribute('data-endpoint') || '';

    const ctx = chartCanvas.getContext('2d');
    salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: currentLabels,
            datasets: [{
                label: 'Revenue (₹)',
                data: currentData,
                borderColor: '#7a1530',
                backgroundColor: 'rgba(122, 21, 48, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return '₹ ' + value;
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    const salesPeriod = document.getElementById('salesPeriod');
    if (salesPeriod) {
        salesPeriod.addEventListener('change', onPeriodChange);
    }

    const applyBtn = document.getElementById('btnApplyCustomRange');
    if (applyBtn) {
        applyBtn.addEventListener('click', updateChart);
    }

    const excelBtn = document.getElementById('btnExportExcel');
    if (excelBtn) {
        excelBtn.addEventListener('click', exportExcel);
    }

    const pdfBtn = document.getElementById('btnExportPdf');
    if (pdfBtn) {
        pdfBtn.addEventListener('click', exportPDF);
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSalesDashboard);
} else {
    initSalesDashboard();
}

function onPeriodChange() {
    const period = document.getElementById('salesPeriod').value;
    const customContainer = document.getElementById('customDateContainer');
    if (period === 'custom') {
        if (customContainer) customContainer.style.display = 'flex';
    } else {
        if (customContainer) customContainer.style.display = 'none';
        updateChart();
    }
}

async function updateChart() {
    const periodSelect = document.getElementById('salesPeriod');
    if (!periodSelect || !salesDataEndpoint) return;

    const period = periodSelect.value;
    let url = `${salesDataEndpoint}?period=${encodeURIComponent(period)}`;

    if (period === 'custom') {
        const start = document.getElementById('startDate')?.value;
        const end = document.getElementById('endDate')?.value;
        if (!start || !end) {
            alert('Please select both start and end dates');
            return;
        }
        url += `&start_date=${encodeURIComponent(start)}&end_date=${encodeURIComponent(end)}`;
    }

    try {
        const response = await fetch(url);
        const result = await response.json();

        currentLabels = result.labels || [];
        currentData = result.data || [];

        if (salesChart) {
            salesChart.data.labels = currentLabels;
            salesChart.data.datasets[0].data = currentData;
            salesChart.update();
        }
    } catch (e) {
        console.error('Failed to update chart', e);
    }
}

function getExportData() {
    const data = [];
    for (let i = 0; i < currentLabels.length; i++) {
        data.push({
            "Period": currentLabels[i],
            "Revenue (INR)": currentData[i]
        });
    }
    return data;
}

function exportExcel() {
    if (typeof XLSX === 'undefined') {
        alert('Excel export library not loaded.');
        return;
    }
    const period = document.getElementById('salesPeriod')?.value || 'report';
    const ws = XLSX.utils.json_to_sheet(getExportData());
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Sales Report");
    XLSX.writeFile(wb, `Sales_Report_${period}.xlsx`);
}

function exportPDF() {
    if (!window.jspdf || !window.jspdf.jsPDF) {
        alert('PDF export library not loaded.');
        return;
    }
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const periodSelect = document.getElementById('salesPeriod');
    const periodText = periodSelect ? periodSelect.options[periodSelect.selectedIndex]?.text : '';
    const periodVal = periodSelect?.value || 'report';

    doc.setFontSize(18);
    doc.text("Sales Report", 14, 22);
    doc.setFontSize(11);
    doc.text("Period: " + periodText, 14, 30);

    const data = getExportData();
    let y = 45;

    doc.setFont(undefined, 'bold');
    doc.text("Date/Period", 14, y);
    doc.text("Revenue", 100, y);
    doc.setFont(undefined, 'normal');

    y += 10;

    data.forEach(row => {
        doc.text(String(row["Period"]), 14, y);
        doc.text("Rs. " + String(row["Revenue (INR)"]), 100, y);
        y += 10;
    });

    doc.save(`Sales_Report_${periodVal}.pdf`);
}
