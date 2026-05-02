/* =============================================================
   public/js/admin-dashboard.js
   Chart.js 4.x — Dashboard Admin Pusat
   Grafik: Pendapatan (toggle) · Status Donut · Tren Reservasi · Performa Bengkel
============================================================= */

(function () {
    'use strict';

    const d = window.dashboardData || {};

    /* ── Global defaults ── */
    Chart.defaults.font.family = "'Segoe UI', system-ui, sans-serif";
    Chart.defaults.font.size   = 12;
    Chart.defaults.color       = '#6b7280';

    const ORANGE      = '#ff6a00';
    const ORANGE_FADE = 'rgba(255,106,0,0.13)';
    const GRAY        = '#d1d5db';
    const GRID        = 'rgba(0,0,0,0.045)';
    const TOOLTIP_BG  = '#1f2937';
    const STATUS_COLORS = ['#ff6a00', '#2563eb', '#16a34a', '#dc2626'];

    /* Shared tooltip config */
    function tooltipBase(extra) {
        return Object.assign({
            backgroundColor: TOOLTIP_BG,
            padding: 10,
            cornerRadius: 8,
            titleColor: '#e5e7eb',
            bodyColor: '#d1d5db',
        }, extra || {});
    }

    /* Shared scale presets */
    const scaleX = (opts) => Object.assign({
        grid: { display: false },
        border: { display: false },
        ticks: { color: '#9ca3af' },
    }, opts || {});

    const scaleY = (opts) => Object.assign({
        grid: { color: GRID },
        border: { display: false },
        ticks: { color: '#9ca3af', maxTicksLimit: 5 },
        beginAtZero: true,
    }, opts || {});

    /* ================================================================
       1. GRAFIK PENDAPATAN — Line (toggle: mingguan/bulanan/tahunan)
    ================================================================ */
    const ctxPendapatan = document.getElementById('chartPendapatan');
    let chartPendapatan = null;

    const periodData = {
        mingguan: {
            labels : d.pendapatanLabMingguan || ['M1','M2','M3','M4','M5','M6','M7','M8','M9','M10','M11','M12','M13','M14'],
            data   : d.pendapatanMingguan    || [12,18,14,22,19,25,28,21,30,26,33,35,29,38],
            target : d.targetMingguan        || [20,20,20,25,25,25,30,30,30,35,35,35,35,40],
        },
        bulanan: {
            labels : d.pendapatanLabBulanan  || ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            data   : d.pendapatanBulanan     || [42,55,48,72,81,94,88,110,102,125,118,140],
            target : d.targetBulanan         || [50,60,65,75,85,95,100,115,110,130,125,150],
        },
        tahunan: {
            labels : d.pendapatanLabTahunan  || ['2021','2022','2023','2024','2025'],
            data   : d.pendapatanTahunan     || [480,620,750,890,1050],
            target : d.targetTahunan         || [500,650,800,950,1100],
        },
    };

    function buildPendapatanChart(period) {
        const pd = periodData[period] || periodData.bulanan;
        if (chartPendapatan) chartPendapatan.destroy();

        chartPendapatan = new Chart(ctxPendapatan, {
            type: 'line',
            data: {
                labels: pd.labels,
                datasets: [
                    {
                        label: 'Pendapatan (jt Rp)',
                        data: pd.data,
                        borderColor: ORANGE,
                        backgroundColor: ORANGE_FADE,
                        borderWidth: 2.5,
                        pointBackgroundColor: ORANGE,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: 0.4,
                    },
                    {
                        label: 'Target (jt Rp)',
                        data: pd.target,
                        borderColor: GRAY,
                        backgroundColor: 'transparent',
                        borderWidth: 1.5,
                        borderDash: [5, 4],
                        pointRadius: 0,
                        pointHoverRadius: 4,
                        fill: false,
                        tension: 0.4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: tooltipBase({
                        callbacks: {
                            label: ctx => ` ${ctx.dataset.label}: Rp ${ctx.parsed.y.toLocaleString('id-ID')} jt`,
                        },
                    }),
                },
                scales: {
                    x: scaleX(),
                    y: scaleY({
                        ticks: {
                            color: '#9ca3af',
                            maxTicksLimit: 5,
                            callback: v => `${v} jt`,
                        },
                    }),
                },
            },
        });
    }

    if (ctxPendapatan) {
        buildPendapatanChart('bulanan');

        /* Tab switcher */
        document.querySelectorAll('.chart-tab').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.chart-tab').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                buildPendapatanChart(this.dataset.period);
            });
        });
    }

    /* ================================================================
       2. STATUS RESERVASI — Donut dengan total di tengah
    ================================================================ */
    const ctxStatus = document.getElementById('chartStatus');
    if (ctxStatus) {
        const statusLabels = d.statusLabels || ['Selesai','Dikonfirmasi','Dikerjakan','Dibatalkan'];
        const statusData   = d.statusData   || [40, 25, 22, 13];
        const total        = statusData.reduce((a, b) => a + b, 0);

        /* Set angka total di tengah */
        const totalEl = document.getElementById('donutTotalNum');
        if (totalEl) totalEl.textContent = total.toLocaleString('id-ID');

        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: STATUS_COLORS,
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverBorderWidth: 3,
                    hoverOffset: 8,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: tooltipBase({
                        callbacks: {
                            label: ctx => {
                                const pct = ((ctx.parsed / total) * 100).toFixed(1);
                                return ` ${ctx.label}: ${ctx.parsed} (${pct}%)`;
                            },
                        },
                    }),
                },
            },
        });

        /* Build custom legend */
        const legendEl = document.getElementById('legendStatus');
        if (legendEl) {
            legendEl.innerHTML = statusLabels.map((lbl, i) => {
                const pct = total ? ((statusData[i] / total) * 100).toFixed(1) : '0.0';
                return `<span class="legend-item">
                    <span class="legend-dot" style="background:${STATUS_COLORS[i]};"></span>
                    ${lbl}
                    <span class="legend-value">${pct}%</span>
                </span>`;
            }).join('');
        }
    }

    /* ================================================================
       3. TREN RESERVASI — Grouped Bar
    ================================================================ */
    const ctxTren = document.getElementById('chartTren');
    if (ctxTren) {
        new Chart(ctxTren, {
            type: 'bar',
            data: {
                labels: d.trenLabels || [],
                datasets: [
                    {
                        label: 'Masuk',
                        data: d.trenMasuk || [],
                        backgroundColor: ORANGE,
                        borderRadius: 5,
                        borderSkipped: false,
                        barPercentage: 0.58,
                        categoryPercentage: 0.7,
                    },
                    {
                        label: 'Selesai',
                        data: d.trenSelesai || [],
                        backgroundColor: GRAY,
                        borderRadius: 5,
                        borderSkipped: false,
                        barPercentage: 0.58,
                        categoryPercentage: 0.7,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: tooltipBase({
                        callbacks: {
                            label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y.toLocaleString('id-ID')}`,
                        },
                    }),
                },
                scales: {
                    x: scaleX(),
                    y: scaleY(),
                },
            },
        });
    }

    /* ================================================================
       4. PERFORMA BENGKEL — Horizontal Bar
    ================================================================ */
    const ctxBengkel = document.getElementById('chartBengkel');
    if (ctxBengkel) {
        const bengkelLabels = d.bengkelLabels || [];
        const bengkelData   = d.bengkelData   || [];
        const maxVal        = Math.max(...bengkelData, 1);

        new Chart(ctxBengkel, {
            type: 'bar',
            data: {
                labels: bengkelLabels,
                datasets: [{
                    label: 'Reservasi Selesai',
                    data: bengkelData,
                    backgroundColor: bengkelData.map((v, i) => {
                        const alpha = 0.35 + (v / maxVal) * 0.65;
                        return `rgba(255,106,0,${alpha.toFixed(2)})`;
                    }),
                    borderRadius: 4,
                    borderSkipped: false,
                    barPercentage: 0.6,
                }],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: tooltipBase({
                        callbacks: {
                            label: ctx => ` Selesai: ${ctx.parsed.x.toLocaleString('id-ID')} reservasi`,
                        },
                    }),
                },
                scales: {
                    x: scaleY({ ticks: { color: '#9ca3af', maxTicksLimit: 5 } }),
                    y: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            color: '#374151',
                            font: { weight: '600', size: 12 },
                        },
                    },
                },
            },
        });
    }

    /* ================================================================
       Period select — tren reservasi
    ================================================================ */
    const periodSelect = document.getElementById('periodSelect');
    if (periodSelect) {
        periodSelect.addEventListener('change', function () {
            /* TODO: fetch data dari endpoint dan update chartTren */
            console.log('Tren period:', this.value);
        });
    }

})();