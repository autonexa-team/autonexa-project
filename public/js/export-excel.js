'use strict';

const COLOR = {
    brand:      '#ff6a00',
    brandDark:  '#e65c00',
    brandLight: '#fff7ed',
    emerald:    '#059669',
    emeraldBg:  '#d1fae5',
    amber:      '#d97706',
    amberBg:    '#fef3c7',
    red:        '#dc2626',
    redBg:      '#fee2e2',
    slate800:   '#1e293b',
    slate500:   '#64748b',
    slate200:   '#e2e8f0',
    white:      '#ffffff',
    rowEven:    '#fff7ed',
    rowOdd:     '#ffffff',
};

function getTodayString() {
    const now = new Date();
    const y   = now.getFullYear();
    const m   = String(now.getMonth() + 1).padStart(2, '0');
    const d   = String(now.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
}

function cssKopJudul() {
    return `
        font-size: 26px;
        font-weight: bold;
        color: ${COLOR.brand};
        padding: 16px 0 4px 0;
        border: none;
        background: none;
    `;
}

function cssKopSub() {
    return `
        font-size: 14px;
        color: ${COLOR.slate500};
        padding: 4px 0;
        border: none;
        background: none;
    `;
}

function cssSectionTitle() {
    return `
        font-size: 16px;
        font-weight: bold;
        color: ${COLOR.brand};
        padding: 20px 0 8px 0;
        border: none;
        background: none;
    `;
}

function cssInfoLabel() {
    return `
        font-size: 13px;
        font-weight: bold;
        color: ${COLOR.slate500};
        padding: 5px 16px 5px 0;
        border: none;
        background: none;
        white-space: nowrap;
    `;
}

function cssInfoValue() {
    return `
        font-size: 13px;
        color: ${COLOR.slate800};
        padding: 5px 0;
        border: none;
        background: none;
    `;
}

function cssHeader() {
    return `
        background-color: ${COLOR.brand};
        color: ${COLOR.white};
        font-weight: bold;
        font-size: 14px;
        text-align: center;
        vertical-align: middle;
        padding: 14px 18px;
        border: 1px solid ${COLOR.brandDark};
        white-space: nowrap;
    `;
    // ]tambah padding teks agar kolom otomatis lebar
    function pad(text, spaces = 8) {
        const sp = '&nbsp;'.repeat(spaces);
        return `${sp}${text}${sp}`;
    }
}

function cssData(isEven = false, extra = '') {
    return `
        background-color: ${isEven ? COLOR.rowEven : COLOR.rowOdd};
        font-size: 13px;
        padding: 12px 18px;
        vertical-align: middle;
        border: 1px solid ${COLOR.slate200};
        ${extra}
    `;
}

function cssTotal(extra = '') {
    return `
        background-color: ${COLOR.brandLight};
        font-size: 13px;
        font-weight: bold;
        color: ${COLOR.brand};
        padding: 12px 18px;
        vertical-align: middle;
        border-top: 2px solid ${COLOR.brand};
        border-bottom: 1px solid ${COLOR.slate200};
        border-left: 1px solid ${COLOR.slate200};
        border-right: 1px solid ${COLOR.slate200};
        ${extra}
    `;
}

function cssDivider(colspan) {
    return `<tr><td colspan="${colspan}" style="border:none;padding:10px 0;background:none;"></td></tr>`;
}

function cssLine(colspan) {
    return `<tr><td colspan="${colspan}" style="border:none;border-bottom:2px solid ${COLOR.brand};padding:0;background:none;"></td></tr>`;
}

function exportToExcel(userName, userRole, periode) {
    const dicetak = `${userName} (${userRole})`;
    const now     = new Date().toLocaleString('id-ID');
    const COLS    = 5; 

    // ── STATUS COLOR ──
    const statusStyle = {
        'Selesai':    { color: COLOR.emerald, bg: COLOR.emeraldBg },
        'Dikerjakan': { color: COLOR.amber,   bg: COLOR.amberBg   },
        'Batal':      { color: COLOR.red,     bg: COLOR.redBg     },
    };

    // ════════════════════════════════════════
    // KOP LAPORAN
    // ════════════════════════════════════════
    const kop = `
        <tr>
            <td colspan="${COLS}" style="${cssKopJudul()}">AUTONEXA</td>
        </tr>
        <tr>
            <td colspan="${COLS}" style="${cssKopSub()}">Laporan Analisis Data Operasional Bengkel</td>
        </tr>
        ${cssLine(COLS)}
        <tr>
            <td style="${cssInfoLabel()}">Periode</td>
            <td colspan="${COLS - 1}" style="${cssInfoValue()}">${periode}</td>
        </tr>
        <tr>
            <td style="${cssInfoLabel()}">Dicetak oleh</td>
            <td colspan="${COLS - 1}" style="${cssInfoValue()}">${dicetak}</td>
        </tr>
        <tr>
            <td style="${cssInfoLabel()}">Tanggal Cetak</td>
            <td colspan="${COLS - 1}" style="${cssInfoValue()}">${now}</td>
        </tr>
        ${cssDivider(COLS)}
    `;

    // ════════════════════════════════════════
    // SECTION 1: RINGKASAN
    // ════════════════════════════════════════
    const ringkasanData = [
        { metrik: 'Total Reservasi',   nilai: '1.248',          ket: '+14.5% vs bulan lalu', color: COLOR.slate800 },
        { metrik: 'Reservasi Selesai', nilai: '1.102',          ket: 'Success Rate: 88%',    color: COLOR.emerald  },
        { metrik: 'Total Pendapatan',  nilai: 'Rp 485.200.000', ket: '+8.2% vs bulan lalu',  color: COLOR.brand    },
        { metrik: 'Rating Rata-rata',  nilai: '4.6 ★',          ket: 'Dari 842 ulasan',      color: COLOR.amber    },
    ];

    const ringkasanRows = ringkasanData.map((r, i) => `
        <tr>
            <td colspan="2" style="${cssData(i % 2 === 1, 'font-weight:bold;')}">${r.metrik}</td>
            <td style="${cssData(i % 2 === 1, `text-align:center; font-weight:bold; font-size:14px; color:${r.color};`)}">${r.nilai}</td>
            <td colspan="2" style="${cssData(i % 2 === 1)}">${r.ket}</td>
        </tr>
    `).join('');

    const sectionRingkasan = `
        <tr>
            <td colspan="${COLS}" style="${cssSectionTitle()}">📊 Ringkasan Periode</td>
        </tr>
        <tr>
            <th colspan="2" style="${cssHeader()} width:300px;">Metrik</th>
            <th style="${cssHeader()} width:250px;">Nilai</th>
            <th colspan="2" style="${cssHeader()} width:350px;">Keterangan</th>
        </tr>
        ${ringkasanRows}
        ${cssDivider(COLS)}
    `;

    // ════════════════════════════════════════
    // SECTION 2: PERFORMA CABANG
    // ════════════════════════════════════════
    const cabangData = [
        { nama: 'AutoNexa Cabang Sudirman',      res: 542, selesai: 480, pendapatan: 'Rp 215.500.000', rating: '4.8 ★', rColor: COLOR.amber },
        { nama: 'AutoNexa Cabang Kebon Jeruk',   res: 425, selesai: 380, pendapatan: 'Rp 165.250.000', rating: '4.5 ★', rColor: COLOR.amber },
        { nama: 'AutoNexa Cabang Kelapa Gading', res: 281, selesai: 242, pendapatan: 'Rp 104.450.000', rating: '4.2 ★', rColor: COLOR.red   },
    ];

    const cabangRows = cabangData.map((c, i) => `
        <tr>
            <td colspan="2" style="${cssData(i % 2 === 1, 'font-weight:bold;')}">${c.nama}</td>
            <td style="${cssData(i % 2 === 1, 'text-align:center;')}">${c.res}</td>
            <td style="${cssData(i % 2 === 1, `text-align:center; font-weight:bold; color:${COLOR.emerald};`)}">${c.selesai}</td>
            <td style="${cssData(i % 2 === 1, 'text-align:right; font-weight:bold;')}">${c.pendapatan}</td>
        </tr>
    `).join('');

    // Baris rating terpisah agar tidak overflow
    const cabangRating = cabangData.map((c, i) => `
        <tr style="display:none"></tr>
    `).join(''); 

    const sectionPerforma = `
        <tr>
            <td colspan="${COLS}" style="${cssSectionTitle()}">🏪 Performa Cabang Bengkel</td>
        </tr>
        <tr>
            <th colspan="2" style="${cssHeader()} width:380px;">Nama Bengkel</th>
            <th style="${cssHeader()} width:200px;">Total Reservasi</th>
            <th style="${cssHeader()} width:220px;">Reservasi Selesai</th>
            <th style="${cssHeader()} width:250px;">Total Pendapatan</th>
        </tr>
        ${cabangRows}
        <tr>
            <td colspan="2" style="${cssTotal('text-align:right; font-size:12px;')}">TOTAL KESELURUHAN</td>
            <td style="${cssTotal('text-align:center;')}">1.248</td>
            <td style="${cssTotal(`text-align:center; color:${COLOR.emerald};`)}">1.102</td>
            <td style="${cssTotal('text-align:right;')}">Rp 485.200.000</td>
        </tr>
        ${cssDivider(COLS)}
    `;

    // ════════════════════════════════════════
    // SECTION 3: RESERVASI TERBARU
    // ════════════════════════════════════════
    const reservasiData = [
        { nama: 'Andi Saputra',  cabang: 'Cab. Sudirman',      layanan: 'Servis Berkala', status: 'Selesai',    biaya: 'Rp 1.250.000' },
        { nama: 'Budi Setiawan', cabang: 'Cab. Kebon Jeruk',   layanan: 'Ganti Oli',      status: 'Dikerjakan', biaya: '—'            },
        { nama: 'Citra Kirana',  cabang: 'Cab. Kelapa Gading', layanan: 'Tune Up Plus',   status: 'Selesai',    biaya: 'Rp 850.000'   },
    ];

    const reservasiRows = reservasiData.map((r, i) => {
        const st = statusStyle[r.status] ?? { color: COLOR.slate800, bg: COLOR.white };
        return `
            <tr>
                <td style="${cssData(i % 2 === 1, 'font-weight:bold;')}">${r.nama}</td>
                <td style="${cssData(i % 2 === 1, `color:${COLOR.slate500};`)}">${r.cabang}</td>
                <td style="${cssData(i % 2 === 1)}">${r.layanan}</td>
                <td style="${cssData(i % 2 === 1, `
                    text-align:center;
                    font-weight:bold;
                    color:${st.color};
                    background-color:${st.bg};
                `)}">${r.status}</td>
                <td style="${cssData(i % 2 === 1, `
                    text-align:right;
                    font-weight:bold;
                    color:${r.biaya === '—' ? COLOR.slate500 : COLOR.slate800};
                `)}">${r.biaya}</td>
            </tr>
        `;
    }).join('');

    const sectionReservasi = `
        <tr>
            <td colspan="${COLS}" style="${cssSectionTitle()}">📋 Sampel Reservasi Terbaru</td>
        </tr>
        <tr>
            <th style="${cssHeader()} width:220px;">Pelanggan</th>
            <th style="${cssHeader()} width:250px;">Cabang</th>
            <th style="${cssHeader()} width:220px;">Layanan</th>
            <th style="${cssHeader()} width:180px;">Status</th>
            <th style="${cssHeader()} width:220px;">Biaya (Rp)</th>
        </tr>
        ${reservasiRows}
    `;

    // ════════════════════════════════════════
    // GABUNG JADI 1 SHEET
    // ════════════════════════════════════════
    const template = `
        <html xmlns:o="urn:schemas-microsoft-com:office:office"
              xmlns:x="urn:schemas-microsoft-com:office:excel"
              xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta charset="UTF-8">
            <!--[if gte mso 9]>
            <xml>
                <x:ExcelWorkbook>
                    <x:ExcelWorksheets>
                        <x:ExcelWorksheet>
                            <x:Name>Laporan AutoNexa</x:Name>
                            <x:WorksheetOptions>
                                <x:Zoom>150</x:Zoom>
                                <x:DisplayGridlines/>
                            </x:WorksheetOptions>
                            <x:PageSetup>
                                <x:Scale>100</x:Scale>
                                <x:Orientation>Landscape</x:Orientation>
                            </x:PageSetup>
                            <x:ColInfo>
                                <x:Index>1</x:Index>
                                <x:Width>8000</x:Width>
                            </x:ColInfo>
                            <x:ColInfo>
                                <x:Index>2</x:Index>
                                <x:Width>8000</x:Width>
                            </x:ColInfo>
                            <x:ColInfo>
                                <x:Index>3</x:Index>
                                <x:Width>7000</x:Width>
                            </x:ColInfo>
                            <x:ColInfo>
                                <x:Index>4</x:Index>
                                <x:Width>7000</x:Width>
                            </x:ColInfo>
                            <x:ColInfo>
                                <x:Index>5</x:Index>
                                <x:Width>7000</x:Width>
                            </x:ColInfo>
                        </x:ExcelWorksheet>
                    </x:ExcelWorksheets>
                </x:ExcelWorkbook>
            </xml>
            <![endif]-->
            <style>
                body  { font-family: Calibri, Arial, sans-serif; }
                table { border-collapse: collapse; min-width: 700px; }
                td, th { mso-number-format: "@"; } /* paksa semua cell sebagai teks agar tidak auto-format */
            </style>
        </head>
        <body>
            <table>
                ${kop}
                ${sectionRingkasan}
                ${sectionPerforma}
                ${sectionReservasi}
            </table>
        </body>
        </html>
    `;

    // ── Download ──
    const blob = new Blob([template], {
        type: 'application/vnd.ms-excel;charset=utf-8',
    });

    const url     = URL.createObjectURL(blob);
    const link    = document.createElement('a');
    link.href     = url;
    link.download = `Laporan-AutoNexa-${getTodayString()}.xls`;

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);

    console.info(`[AutoNexa] Excel diexport: ${link.download}`);
}