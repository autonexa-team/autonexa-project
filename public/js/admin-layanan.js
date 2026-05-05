/* public/js/admin-layanan.js
   Manajemen Layanan — filter client-side, modal, toggle status
*/
(function () {
    'use strict';

    /* ── Elemen ── */
    const searchInput   = document.getElementById('searchInput');
    const filterStatus  = document.getElementById('filterStatus');
    const sortBy        = document.getElementById('sortBy');
    const tableBody     = document.getElementById('layananTableBody');
    const countPill     = document.getElementById('countPill');
    const modalBackdrop = document.getElementById('modalBackdrop');
    const modalForm     = document.getElementById('modalForm');
    const mToggleTrack  = document.getElementById('mToggleTrack');
    const mToggleLbl    = document.getElementById('mToggleLbl');
    const mStatusInput  = document.getElementById('mStatusInput');

    /* ================================================================
       FILTER + SORT CLIENT-SIDE (UX cepat tanpa reload)
    ================================================================ */
    function filterTable() {
        const q   = (searchInput?.value || '').toLowerCase().trim();
        const st  = filterStatus?.value || '';
        const so  = sortBy?.value || '';

        const rows = Array.from(tableBody?.querySelectorAll('tr[data-id]') || []);
        let visible = [];

        rows.forEach(row => {
            const nama   = row.dataset.nama   || '';
            const desc   = row.dataset.desc   || '';
            const status = row.dataset.status || '';

            const matchQ  = !q  || nama.includes(q) || desc.includes(q);
            const matchSt = !st || status === st;
            const show    = matchQ && matchSt;

            row.style.display = show ? '' : 'none';
            if (show) visible.push(row);
        });

        /* Sort visible rows */
        if (so && visible.length > 1) {
            visible.sort((a, b) => {
                if (so === 'harga-asc')   return parseInt(a.dataset.harga)  - parseInt(b.dataset.harga);
                if (so === 'harga-desc')  return parseInt(b.dataset.harga)  - parseInt(a.dataset.harga);
                if (so === 'durasi-asc')  return parseInt(a.dataset.durasi) - parseInt(b.dataset.durasi);
                if (so === 'durasi-desc') return parseInt(b.dataset.durasi) - parseInt(a.dataset.durasi);
                if (so === 'nama-asc')    return a.dataset.nama.localeCompare(b.dataset.nama);
                return 0;
            });
            visible.forEach(row => tableBody.appendChild(row));
        }

        if (countPill) countPill.textContent = `${visible.length} layanan`;

        /* Empty state */
        let emptyRow = tableBody?.querySelector('.empty-filter-row');
        if (visible.length === 0) {
            if (!emptyRow) {
                emptyRow = document.createElement('tr');
                emptyRow.className = 'empty-filter-row';
                emptyRow.innerHTML = `<td colspan="5" class="td-empty">
                    <i class="bi bi-search" style="font-size:24px;opacity:0.3;display:block;margin-bottom:8px;"></i>
                    Tidak ada layanan yang sesuai filter
                </td>`;
                tableBody.appendChild(emptyRow);
            }
        } else {
            emptyRow?.remove();
        }
    }

    searchInput?.addEventListener('input', filterTable);
    filterStatus?.addEventListener('change', filterTable);
    sortBy?.addEventListener('change', filterTable);

    /* Sortable header click */
    document.querySelectorAll('.th-sortable').forEach(th => {
        th.addEventListener('click', function () {
            const col = this.dataset.sort;
            const cur = sortBy?.value || '';
            const asc = `${col}-asc`;
            const desc = `${col}-desc`;
            if (sortBy) {
                sortBy.value = (cur === asc) ? desc : asc;
                filterTable();
            }
            document.querySelectorAll('.th-sortable').forEach(t => t.classList.remove('sorted'));
            this.classList.add('sorted');
        });
    });

    /* ================================================================
       MODAL
    ================================================================ */
    window.editLayanan = function (id, nama, harga, durasi, desc, status) {
        document.getElementById('modalTitle').textContent  = 'Edit Layanan';
        document.getElementById('modalSaveText').textContent = 'Simpan Perubahan';
        document.getElementById('modalLayananId').value    = id;

        /* Set action ke route update */
        modalForm.action = `/admin-pusat/layanan/${id}`;
        document.getElementById('methodField').innerHTML   =
            '<input type="hidden" name="_method" value="PUT">';

        document.getElementById('mNama').value  = nama;
        document.getElementById('mHarga').value = harga;
        document.getElementById('mDurasi').value = durasi;
        document.getElementById('mDesc').value  = desc;

        const isAktif = status === 'aktif';
        mToggleTrack.className  = 'toggle-track' + (isAktif ? ' on' : '');
        mToggleLbl.textContent  = isAktif ? 'Aktif' : 'Nonaktif';
        mToggleLbl.className    = isAktif ? 'toggle-lbl' : 'toggle-lbl off';
        mStatusInput.value      = status;

        openModal();
    };

    document.getElementById('btnTambahLayanan')?.addEventListener('click', function () {
        document.getElementById('modalTitle').textContent  = 'Tambah Layanan';
        document.getElementById('modalSaveText').textContent = 'Simpan Layanan';
        document.getElementById('modalLayananId').value    = '';
        document.getElementById('methodField').innerHTML   = '';

        modalForm.action = '/admin-pusat/layanan';
        modalForm.reset();

        mToggleTrack.className = 'toggle-track on';
        mToggleLbl.textContent = 'Aktif';
        mToggleLbl.className   = 'toggle-lbl';
        mStatusInput.value     = 'aktif';

        openModal();
    });

    function openModal()  { if (modalBackdrop) modalBackdrop.style.display = 'flex'; }
    window.closeModal = function () {
        if (modalBackdrop) modalBackdrop.style.display = 'none';
    };

    /* Klik backdrop (luar modal) untuk tutup */
    modalBackdrop?.addEventListener('click', function (e) {
        if (e.target === this) window.closeModal();
    });

    /* ESC untuk tutup */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') window.closeModal();
    });

    /* Toggle status di modal */
    document.getElementById('mToggleWrap')?.addEventListener('click', function () {
        const isOn = mToggleTrack.classList.toggle('on');
        mToggleLbl.textContent = isOn ? 'Aktif' : 'Nonaktif';
        mToggleLbl.className   = isOn ? 'toggle-lbl' : 'toggle-lbl off';
        mStatusInput.value     = isOn ? 'aktif' : 'nonaktif';
    });

    /* Client-side validation */
    modalForm?.addEventListener('submit', function (e) {
        const nama   = document.getElementById('mNama').value.trim();
        const harga  = document.getElementById('mHarga').value;
        const durasi = document.getElementById('mDurasi').value;
        let valid = true;

        [['mNama', nama], ['mHarga', harga], ['mDurasi', durasi]].forEach(([id, val]) => {
            const el = document.getElementById(id);
            if (!val) {
                el?.classList.add('finput-error');
                valid = false;
            } else {
                el?.classList.remove('finput-error');
            }
        });

        if (!valid) {
            e.preventDefault();
            document.querySelector('.finput-error')?.focus();
        }
    });

})();