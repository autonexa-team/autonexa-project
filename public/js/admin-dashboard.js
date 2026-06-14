/* =============================================================
   public/js/admin-dashboard.js
   Chart.js 4.x — Dashboard Admin Pusat
   Grafik: Pendapatan (toggle) · Status Donut · Tren Reservasi · Performa Bengkel
============================================================= */

(function () {
    'use strict';

    /* ── Ambil data dari blade ── */
    const D = window.dashboardData || {};

    /* ── Warna brand ── */
    const BRAND   = '#ff6a00';
    const BRAND_A = 'rgba(255,106,0,0.12)';
    const GRAY    = '#e5e7eb';
    const NAVY    = '#1F2E4D';
    const TEXT    = '#6b7280';
    const GRID    = 'rgba(0,0,0,0.05)';

    /* ── Global Chart.js defaults ── */
    Chart.defaults.font.family  = "'Plus Jakarta Sans', 'Geist', system-ui, sans-serif";
    Chart.defaults.font.size    = 12;
    Chart.defaults.color        = TEXT;
    Chart.defaults.plugins.legend.display = false;

    /* ================================================================
       HELPER: buat gradient fill untuk line chart
    ================================================================ */
    function makeGradient(ctx, color1, color2) {
        const grad = ctx.createLinearGradient(0, 0, 0, 260);
        grad.addColorStop(0,   color1);
        grad.addColorStop(1,   color2);
        return grad;
    }

    /* ================================================================
       HELPER: opsi axis standar
    ================================================================ */
    function xAxis(labels) {
        return {
            grid : { display: false },
            ticks: { color: TEXT, maxRotation: 0 },
        };
    }

    function yAxis(unit) {
        return {
            grid : { color: GRID, drawBorder: false },
            ticks: {
                color: TEXT,
                callback: v => unit ? `${v} ${unit}` : v,
            },
            beginAtZero: true,
        };
    }

    /* ================================================================
       1. GRAFIK PENDAPATAN (Line + Area toggle)
    ================================================================ */
    const ctxP = document.getElementById('chartPendapatan');
    if (!ctxP) return;

    /* State periode aktif */
    let periodAktif = 'bulanan';

    /* Data per periode */
    const periodeMap = {
        mingguan : {
            labels : D.pendapatanLabMingguan || [],
            data   : D.pendapatanMingguan    || [],
            target : D.targetMingguan        || [],
        },
        bulanan  : {
            labels : D.pendapatanLabBulanan  || [],
            data   : D.pendapatanBulanan     || [],
            target : D.targetBulanan         || [],
        },
        tahunan  : {
            labels : D.pendapatanLabTahunan  || [],
            data   : D.pendapatanTahunan     || [],
            target : D.targetTahunan         || [],
        },
    };

    /* Fallback — pastikan ada data supaya grafik tidak kosong */
    Object.keys(periodeMap).forEach(key => {
        const p = periodeMap[key];
        if (!p.data.length) {
            p.data   = [0];
            p.target = [0];
            if (!p.labels.length) p.labels = ['-'];
        }
    });

    /* Cek apakah semua nilai 0 (tampilkan notice) */
    function semua0(arr) { return arr.every(v => Number(v) === 0); }

    /* Buat chart pendapatan */
    const grad = makeGradient(
        ctxP.getContext('2d'),
        'rgba(255,106,0,0.18)',
        'rgba(255,106,0,0)'
    );

    const chartPendapatan = new Chart(ctxP, {
        type: 'line',
        data: {
            labels  : periodeMap.bulanan.labels,
            datasets: [
                {
                    label          : 'Pendapatan (Rp juta)',
                    data           : periodeMap.bulanan.data,
                    borderColor    : BRAND,
                    backgroundColor: grad,
                    borderWidth    : 2.5,
                    pointRadius    : 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: BRAND,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    tension        : 0.4,
                    fill           : true,
                    order          : 1,
                },
                {
                    label          : 'Target',
                    data           : periodeMap.bulanan.target,
                    borderColor    : GRAY,
                    backgroundColor: 'transparent',
                    borderWidth    : 1.5,
                    borderDash     : [6, 4],
                    pointRadius    : 0,
                    tension        : 0,
                    fill           : false,
                    order          : 2,
                },
            ],
        },
        options: {
            responsive         : true,
            maintainAspectRatio: false,
            interaction        : { mode: 'index', intersect: false },
            plugins: {
                legend : { display: false },
                tooltip: {
                    backgroundColor: '#1a1a1a',
                    titleColor     : '#fff',
                    bodyColor      : 'rgba(255,255,255,0.75)',
                    padding        : 10,
                    cornerRadius   : 8,
                    callbacks: {
                        label: ctx =>
                            ` ${ctx.dataset.label}: Rp ${ctx.parsed.y} rb`,
                    },
                },
            },
            scales: {
                x: xAxis(),
                y: {
                    ...yAxis('rb'),
                    ticks: {
                        color: TEXT,
                        callback: v => `${v} rb`,
                    },
                },
            },
        },
    });

    /* Tampilkan notice jika semua data 0 */
    function updateEmptyNotice(data) {
        let notice = document.getElementById('chartEmptyNotice');
        if (semua0(data)) {
            if (!notice) {
                notice = document.createElement('div');
                notice.id = 'chartEmptyNotice';
                notice.style.cssText = `
                    position:absolute;inset:0;display:flex;flex-direction:column;
                    align-items:center;justify-content:center;
                    font-size:13px;color:#9ca3af;pointer-events:none;gap:6px;
                `;
                notice.innerHTML = `
                    <i class="bi bi-bar-chart" style="font-size:28px;opacity:.3;"></i>
                    <span>Belum ada data pendapatan</span>
                    <span style="font-size:11px;">Data akan muncul setelah ada reservasi yang selesai</span>
                `;
                ctxP.parentElement.style.position = 'relative';
                ctxP.parentElement.appendChild(notice);
            }
            notice.style.display = 'flex';
        } else if (notice) {
            notice.style.display = 'none';
        }
    }

    updateEmptyNotice(periodeMap.bulanan.data);

    /* Toggle periode */
    document.querySelectorAll('.chart-tab').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.chart-tab').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            periodAktif = this.dataset.period;
            const p     = periodeMap[periodAktif];

            chartPendapatan.data.labels         = p.labels;
            chartPendapatan.data.datasets[0].data = p.data;
            chartPendapatan.data.datasets[1].data = p.target;
            chartPendapatan.update('active');

            updateEmptyNotice(p.data);
        });
    });

    /* ================================================================
       2. GRAFIK PERFORMA BENGKEL (Horizontal Bar)
    ================================================================ */
    const ctxB = document.getElementById('chartBengkel');
    if (!ctxB) return;

    const bengkelLabels = D.bengkelLabels || ['Belum ada data'];
    const bengkelData   = D.bengkelData   || [0];

    /* Warna bar: highlight tertinggi */
    const maxVal  = Math.max(...bengkelData, 1);
    const barColors = bengkelData.map((v, i) =>
        i === 0 ? BRAND : `rgba(255,106,0,${0.25 + (v / maxVal) * 0.55})`
    );

    new Chart(ctxB, {
        type: 'bar',
        data: {
            labels  : bengkelLabels,
            datasets: [{
                label          : 'Reservasi',
                data           : bengkelData,
                backgroundColor: barColors,
                borderRadius   : 6,
                borderSkipped  : false,
                barThickness   : 22,
            }],
        },
        options: {
            indexAxis          : 'y',          /* ← horizontal bar */
            responsive         : true,
            maintainAspectRatio: false,
            plugins: {
                legend : { display: false },
                tooltip: {
                    backgroundColor: '#1a1a1a',
                    titleColor     : '#fff',
                    bodyColor      : 'rgba(255,255,255,0.75)',
                    padding        : 10,
                    cornerRadius   : 8,
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.x} reservasi`,
                    },
                },
            },
            scales: {
                x: {
                    grid      : { color: GRID, drawBorder: false },
                    ticks     : { color: TEXT, precision: 0 },
                    beginAtZero: true,
                },
                y: {
                    grid : { display: false },
                    ticks: {
                        color    : TEXT,
                        font     : { weight: '600' },
                        /* Truncate nama panjang */
                        callback : v => {
                            const lbl = bengkelLabels[v] || v;
                            return lbl.length > 18 ? lbl.substring(0, 18) + '…' : lbl;
                        },
                    },
                },
            },
        },
    });
    
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