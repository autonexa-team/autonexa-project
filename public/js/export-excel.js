'use strict';

const COLOR = {
    brand:     '#ff6a00',
    brandDark: '#e65c00',
    brandLight:'#fff7ed',
    emerald:   '#059669',
    emeraldBg: '#d1fae5',
    amber:     '#d97706',
    amberBg:   '#fef3c7',
    red:       '#dc2626',
    redBg:     '#fee2e2',
    slate800:  '#1e293b',
    slate500:  '#64748b',
    slate200:  '#e2e8f0',
    white:     '#ffffff',
};

function getTodayString() {
    const d = new Date();
    return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
}

function fmt(num) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}

function cssHeader() {
    return `background-color:${COLOR.brand};color:#fff;font-weight:bold;font-size:13px;
            text-align:center;padding:12px 16px;border:1px solid ${COLOR.brandDark};white-space:nowrap;`;
}

function cssData(isEven = false, extra = '') {
    return `background-color:${isEven ? COLOR.brandLight : COLOR.white};font-size:12px;
            padding:10px 16px;border:1px solid ${COLOR.slate200};${extra}`;
}

function cssTotal(extra = '') {
    return `background-color:${COLOR.brandLight};font-size:12px;font-weight:bold;color:${COLOR.brand};
            padding:10px 16px;border-top:2px solid ${COLOR.brand};border:1px solid ${COLOR.slate200};${extra}`;
}

function cssKop(size, color, extra = '') {
    return `font-size:${size}px;font-weight:bold;color:${color};padding:8px 0;border:none;background:none;${extra}`;
}

function cssInfo(bold = false) {
    return `font-size:12px;${bold ? 'font-weight:bold;' : ''}color:${COLOR.slate500};
            padding:4px 0;border:none;background:none;`;
}

const statusMap = {
    'pending':      { label: 'Menunggu',     color: COLOR.slate500, bg: '#f1f5f9' },
    'dikonfirmasi': { label: 'Dikonfirmasi', color: '#1e40af',      bg: '#dbeafe' },
    'in_progress':  { label: 'Dikerjakan',   color: COLOR.amber,    bg: COLOR.amberBg },
    'done':         { label: 'Selesai',      color: COLOR.emerald,  bg: COLOR.emeraldBg },
    'cancelled':    { label: 'Dibatalkan',   color: COLOR.red,      bg: COLOR.redBg },
};

function exportToExcel() {
    // Ambil data yang sudah di-inject dari blade
    if (typeof laporanData === 'undefined') {
        alert('Data laporan tidak tersedia.');
        return;
    }

    const d        = laporanData;
    const periode  = `${d.dari} - ${d.sampai}`;
    const dicetak  = document.querySelector('.print-footer strong')?.innerText ?? '-';
    const now      = new Date().toLocaleString('id-ID');
    const COLS     = 6;

    const sukses = d.totalReservasi > 0
        ? Math.round((d.selesai / d.totalReservasi) * 100)
        : 0;

    // ── KOP ──────────────────────────────────────────
    const kop = `
        <tr><td colspan="${COLS}" style="${cssKop(22, COLOR.brand)}">AUTONEXA</td></tr>
        <tr><td colspan="${COLS}" style="${cssKop(13, COLOR.slate500)}">Laporan Operasional Bengkel — ${d.bengkel}</td></tr>
        <tr><td colspan="${COLS}" style="border:none;border-bottom:2px solid ${COLOR.brand};padding:0;background:none;"></td></tr>
        <tr><td style="${cssInfo(true)}">Bengkel</td>
            <td colspan="${COLS-1}" style="${cssInfo()}">${d.bengkel}</td></tr>
        <tr><td style="${cssInfo(true)}">Periode</td>
            <td colspan="${COLS-1}" style="${cssInfo()}">${periode}</td></tr>
        <tr><td style="${cssInfo(true)}">Dicetak oleh</td>
            <td colspan="${COLS-1}" style="${cssInfo()}">${dicetak} (Admin Cabang)</td></tr>
        <tr><td style="${cssInfo(true)}">Tanggal Cetak</td>
            <td colspan="${COLS-1}" style="${cssInfo()}">${now}</td></tr>
        <tr><td colspan="${COLS}" style="border:none;padding:8px;background:none;"></td></tr>
    `;

    // ── SECTION 1: RINGKASAN ─────────────────────────
    const ringkasan = [
        { metrik: 'Total Reservasi',   nilai: d.totalReservasi,                    ket: 'Periode ini',           color: COLOR.slate800 },
        { metrik: 'Reservasi Selesai', nilai: d.selesai,                           ket: `Success Rate: ${sukses}%`, color: COLOR.emerald },
        { metrik: 'Reservasi Dibatalkan', nilai: d.dibatalkan,                     ket: 'Periode ini',           color: COLOR.red },
        { metrik: 'Total Pendapatan',  nilai: fmt(d.totalPendapatan),              ket: 'Dari reservasi selesai',color: COLOR.brand },
        { metrik: 'Total Review',      nilai: d.totalReview,                       ket: 'Ulasan pelanggan',      color: COLOR.slate800 },
        { metrik: 'Rating Rata-rata',  nilai: `${Number(d.avgRating).toFixed(1)} ★`, ket: `Dari ${d.totalReview} ulasan`, color: COLOR.amber },
    ];

    const ringkasanRows = ringkasan.map((r, i) => `
        <tr>
            <td colspan="3" style="${cssData(i%2===1,'font-weight:bold;')}">${r.metrik}</td>
            <td style="${cssData(i%2===1,`text-align:center;font-weight:bold;font-size:14px;color:${r.color};`)}">${r.nilai}</td>
            <td colspan="2" style="${cssData(i%2===1)}">${r.ket}</td>
        </tr>
    `).join('');

    const secRingkasan = `
        <tr><td colspan="${COLS}" style="font-size:15px;font-weight:bold;color:${COLOR.brand};
            padding:16px 0 8px;border:none;background:none;">📊 Ringkasan Periode</td></tr>
        <tr>
            <th colspan="3" style="${cssHeader()}">Metrik</th>
            <th style="${cssHeader()}">Nilai</th>
            <th colspan="2" style="${cssHeader()}">Keterangan</th>
        </tr>
        ${ringkasanRows}
        <tr><td colspan="${COLS}" style="border:none;padding:8px;background:none;"></td></tr>
    `;

    // ── SECTION 2: PERFORMA BENGKEL ──────────────────
    const secPerforma = `
        <tr><td colspan="${COLS}" style="font-size:15px;font-weight:bold;color:${COLOR.brand};
            padding:16px 0 8px;border:none;background:none;">🏪 Ringkasan Performa Bengkel</td></tr>
        <tr>
            <th colspan="2" style="${cssHeader()}">Nama Bengkel</th>
            <th style="${cssHeader()}">Total Reservasi</th>
            <th style="${cssHeader()}">Selesai</th>
            <th style="${cssHeader()}">Dibatalkan</th>
            <th style="${cssHeader()}">Total Pendapatan</th>
        </tr>
        <tr>
            <td colspan="2" style="${cssData(false,'font-weight:bold;')}">${d.bengkel}</td>
            <td style="${cssData(false,'text-align:center;')}">${d.totalReservasi}</td>
            <td style="${cssData(false,`text-align:center;font-weight:bold;color:${COLOR.emerald};`)}">${d.selesai}</td>
            <td style="${cssData(false,`text-align:center;font-weight:bold;color:${COLOR.red};`)}">${d.dibatalkan}</td>
            <td style="${cssData(false,'text-align:right;font-weight:bold;')}">${fmt(d.totalPendapatan)}</td>
        </tr>
        <tr><td colspan="${COLS}" style="border:none;padding:8px;background:none;"></td></tr>
    `;

    // ── SECTION 3: RESERVASI ─────────────────────────
    const resRows = d.reservasis.length
        ? d.reservasis.map((r, i) => {
            const st = statusMap[r.status] ?? { label: r.status, color: COLOR.slate500, bg: '#f1f5f9' };
            return `
                <tr>
                    <td style="${cssData(i%2===1,'font-weight:bold;')}">${r.nama}</td>
                    <td style="${cssData(i%2===1)}">${r.tanggal}</td>
                    <td colspan="2" style="${cssData(i%2===1)}">${r.layanan}</td>
                    <td style="${cssData(i%2===1,`text-align:center;font-weight:bold;color:${st.color};background-color:${st.bg};`)}">${st.label}</td>
                    <td style="${cssData(i%2===1,'text-align:right;font-weight:bold;')}">${r.total_biaya > 0 ? fmt(r.total_biaya) : '—'}</td>
                </tr>`;
          }).join('')
        : `<tr><td colspan="${COLS}" style="${cssData()} text-align:center;color:${COLOR.slate500};font-style:italic;">
               Tidak ada data reservasi</td></tr>`;

    const totalBiaya = d.reservasis.reduce((s, r) => s + Number(r.total_biaya || 0), 0);

    const secReservasi = `
        <tr><td colspan="${COLS}" style="font-size:15px;font-weight:bold;color:${COLOR.brand};
            padding:16px 0 8px;border:none;background:none;">📋 Data Reservasi</td></tr>
        <tr>
            <th style="${cssHeader()}">Pelanggan</th>
            <th style="${cssHeader()}">Tanggal</th>
            <th colspan="2" style="${cssHeader()}">Layanan</th>
            <th style="${cssHeader()}">Status</th>
            <th style="${cssHeader()}">Biaya</th>
        </tr>
        ${resRows}
        <tr>
            <td colspan="5" style="${cssTotal('text-align:right;')}">TOTAL PENDAPATAN</td>
            <td style="${cssTotal('text-align:right;')}">${fmt(totalBiaya)}</td>
        </tr>
        <tr><td colspan="${COLS}" style="border:none;padding:8px;background:none;"></td></tr>
    `;

    // ── SECTION 4: PENDAPATAN HARIAN ─────────────────
    const phRows = d.pendapatanHarian.length
        ? d.pendapatanHarian.map((p, i) => `
            <tr>
                <td colspan="2" style="${cssData(i%2===1)}">${p.tanggal}</td>
                <td style="${cssData(i%2===1,'text-align:center;')}">${p.jumlah_transaksi} trx</td>
                <td colspan="3" style="${cssData(i%2===1,'text-align:right;font-weight:bold;')}">${fmt(p.total)}</td>
            </tr>`).join('')
        : `<tr><td colspan="${COLS}" style="${cssData()} text-align:center;color:${COLOR.slate500};font-style:italic;">
               Belum ada transaksi selesai</td></tr>`;

    const totalHarian = d.pendapatanHarian.reduce((s, p) => s + Number(p.total || 0), 0);

    const secPendapatan = `
        <tr><td colspan="${COLS}" style="font-size:15px;font-weight:bold;color:${COLOR.brand};
            padding:16px 0 8px;border:none;background:none;">💰 Pendapatan Harian</td></tr>
        <tr>
            <th colspan="2" style="${cssHeader()}">Tanggal</th>
            <th style="${cssHeader()}">Jumlah Transaksi</th>
            <th colspan="3" style="${cssHeader()}">Total Pendapatan</th>
        </tr>
        ${phRows}
        <tr>
            <td colspan="3" style="${cssTotal('text-align:right;')}">TOTAL</td>
            <td colspan="3" style="${cssTotal('text-align:right;')}">${fmt(totalHarian)}</td>
        </tr>
        <tr><td colspan="${COLS}" style="border:none;padding:8px;background:none;"></td></tr>
    `;

    // ── SECTION 5: REVIEW ────────────────────────────
    const rvRows = d.reviews.length
        ? d.reviews.map((rv, i) => `
            <tr>
                <td style="${cssData(i%2===1,'font-weight:bold;')}">${rv.nama}</td>
                <td style="${cssData(i%2===1,`text-align:center;font-weight:bold;color:${COLOR.amber};`)}">${rv.rating} ★</td>
                <td colspan="3" style="${cssData(i%2===1)}">${rv.komentar}</td>
                <td style="${cssData(i%2===1)}">${rv.tanggal}</td>
            </tr>`).join('')
        : `<tr><td colspan="${COLS}" style="${cssData()} text-align:center;color:${COLOR.slate500};font-style:italic;">
               Belum ada review</td></tr>`;

    const secReview = `
        <tr><td colspan="${COLS}" style="font-size:15px;font-weight:bold;color:${COLOR.brand};
            padding:16px 0 8px;border:none;background:none;">⭐ Review Pelanggan</td></tr>
        <tr>
            <th style="${cssHeader()}">Pelanggan</th>
            <th style="${cssHeader()}">Rating</th>
            <th colspan="3" style="${cssHeader()}">Komentar</th>
            <th style="${cssHeader()}">Tanggal</th>
        </tr>
        ${rvRows}
    `;

    // ── GABUNG & DOWNLOAD ─────────────────────────────
    const html = `
        <html xmlns:o="urn:schemas-microsoft-com:office:office"
              xmlns:x="urn:schemas-microsoft-com:office:excel"
              xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Calibri, Arial, sans-serif; }
                table { border-collapse: collapse; }
                td, th { mso-number-format:"@"; }
            </style>
        </head>
        <body>
            <table>${kop}${secRingkasan}${secPerforma}${secReservasi}${secPendapatan}${secReview}</table>
        </body>
        </html>`;

    const blob = new Blob([html], { type: 'application/vnd.ms-excel;charset=utf-8' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = `Laporan-${laporanData.bengkel}-${getTodayString()}.xls`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}