/* public/js/admin-layanan.js */
(function () {
    'use strict';

    const backdrop    = document.getElementById('modalBackdrop');
    const modalForm   = document.getElementById('modalForm');
    const mTitle      = document.getElementById('modalTitle');
    const mSub        = document.getElementById('modalSub');
    const mSaveText   = document.getElementById('modalSaveText');
    const mNama       = document.getElementById('mNama');
    const mDesc       = document.getElementById('mDesc');
    const mHarga      = document.getElementById('mHarga');
    const mDurasi     = document.getElementById('mDurasi');
    const mStatus     = document.getElementById('mStatusInput');
    const mTrack      = document.getElementById('mToggleTrack');
    const mLbl        = document.getElementById('mToggleLbl');
    const methodField = document.getElementById('methodField');
    const baseUrl     = document.getElementById('layananBaseUrl')?.value
                        ?? '/admin-pusat/layanan';

    /* ── Buka modal Tambah ── */
    document.getElementById('btnTambah')?.addEventListener('click', () => {
        resetModal();
        mTitle.textContent    = 'Tambah Layanan';
        mSub.textContent      = 'Isi detail layanan yang akan ditambahkan';
        mSaveText.textContent = 'Simpan Layanan';
        modalForm.action      = baseUrl;
        methodField.innerHTML = '';
        openModal();
    });

    /* ── Buka modal Edit (dipanggil dari blade) ── */
    window.openModalEdit = function (id, nama, desc, harga, durasi, status) {
        resetModal();
        mTitle.textContent    = 'Edit Layanan';
        mSub.textContent      = `Perbarui data — ${nama}`;
        mSaveText.textContent = 'Simpan Perubahan';
        modalForm.action      = `${baseUrl}/${id}`;
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

        mNama.value  = nama;
        mDesc.value  = desc;
        mHarga.value = harga;
        mDurasi.value= durasi;

        const isAktif = status === 'aktif';
        setToggle(isAktif);

        openModal();
    };

    /* ── Tutup modal ── */
    window.closeModal = function () {
        backdrop.style.display = 'none';
    };

    backdrop?.addEventListener('click', (e) => {
        if (e.target === backdrop) closeModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });

    /* ── Toggle status dalam modal ── */
    document.getElementById('mToggleWrap')?.addEventListener('click', () => {
        const isOn = mTrack.classList.toggle('on');
        mLbl.textContent = isOn ? 'Aktif' : 'Nonaktif';
        mLbl.className   = isOn ? 'toggle-lbl' : 'toggle-lbl off';
        mStatus.value    = isOn ? 'aktif' : 'nonaktif';
    });

    /* ── Filter + Search client-side ── */
    const searchInput  = document.getElementById('searchInput');
    const filterStatus = document.getElementById('filterStatus');
    const countPill    = document.getElementById('countPill');
    const emptyFilter  = document.getElementById('emptyFilter');

    function filterTable() {
        const q  = (searchInput?.value || '').toLowerCase().trim();
        const fs = filterStatus?.value || '';
        const rows = document.querySelectorAll('.layanan-row');
        let visible = 0;

        rows.forEach(row => {
            const name   = row.dataset.name   || '';
            const desc   = row.dataset.desc   || '';
            const status = row.dataset.status || '';

            const show = (
                (!q  || name.includes(q) || desc.includes(q)) &&
                (!fs || status === fs)
            );

            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (countPill) countPill.textContent = `${visible} layanan`;

        if (emptyFilter) {
            emptyFilter.style.display = visible === 0 ? 'block' : 'none';
        }
    }

    searchInput?.addEventListener('input', filterTable);
    filterStatus?.addEventListener('change', filterTable);

    /* ── Validasi submit ── */
    modalForm?.addEventListener('submit', function (e) {
        let valid = true;
        [mNama, mHarga, mDurasi].forEach(el => {
            if (!el?.value.toString().trim()) {
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

    /* ── Helpers ── */
    function openModal() {
        backdrop.style.display = 'flex';
        setTimeout(() => mNama?.focus(), 100);
    }

    function resetModal() {
        modalForm?.reset();
        methodField.innerHTML = '';
        setToggle(true);
        [mNama, mHarga, mDurasi, mDesc].forEach(el =>
            el?.classList.remove('finput-error')
        );
    }

    function setToggle(isOn) {
        mTrack.className = isOn ? 'toggle-track on' : 'toggle-track';
        mLbl.textContent = isOn ? 'Aktif' : 'Nonaktif';
        mLbl.className   = isOn ? 'toggle-lbl' : 'toggle-lbl off';
        mStatus.value    = isOn ? 'aktif' : 'nonaktif';
    }

})();