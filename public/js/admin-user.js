/* public/js/admin-user.js — Manajemen User Admin Cabang */
(function () {
    'use strict';

    const searchInput   = document.getElementById('searchInput');
    const filterStatus  = document.getElementById('filterStatus');
    const filterAssign  = document.getElementById('filterAssign');
    const tableBody     = document.getElementById('userTableBody');
    const countPill     = document.getElementById('countPill');
    const modalBackdrop = document.getElementById('modalBackdrop');
    const modalForm     = document.getElementById('modalForm');
    const mBengkel      = document.getElementById('mBengkel');
    const bengkelFree   = window.bengkelFreeData || [];

    /* ================================================================
       FILTER CLIENT-SIDE
    ================================================================ */
    function filterTable() {
        const q  = (searchInput?.value || '').toLowerCase().trim();
        const fs = filterStatus?.value || '';
        const fa = filterAssign?.value || '';

        const rows   = Array.from(tableBody?.querySelectorAll('tr[data-id]') || []);
        let visible  = 0;

        rows.forEach(row => {
            const nama   = row.dataset.nama   || '';
            const email  = row.dataset.email  || '';
            const status = row.dataset.status || '';
            const assign = row.dataset.assign || '';

            const show = (
                (!q  || nama.includes(q) || email.includes(q)) &&
                (!fs || status === fs) &&
                (!fa || assign === fa)
            );

            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (countPill) countPill.textContent = `${visible} admin`;

        /* Empty state filter */
        let emptyRow = tableBody?.querySelector('.filter-empty');
        if (visible === 0 && rows.length > 0) {
            if (!emptyRow) {
                emptyRow = document.createElement('tr');
                emptyRow.className = 'filter-empty';
                emptyRow.innerHTML = `<td colspan="6" class="td-empty">
                    <i class="bi bi-search" style="font-size:24px;opacity:0.3;display:block;margin-bottom:8px;"></i>
                    Tidak ada admin yang sesuai filter
                </td>`;
                tableBody.appendChild(emptyRow);
            }
        } else {
            emptyRow?.remove();
        }
    }

    searchInput?.addEventListener('input', filterTable);
    filterStatus?.addEventListener('change', filterTable);
    filterAssign?.addEventListener('change', filterTable);

    /* ================================================================
       MODAL — TAMBAH
    ================================================================ */
    document.getElementById('btnTambah')?.addEventListener('click', function () {
        resetModal();
        document.getElementById('modalTitle').textContent    = 'Tambah Admin Cabang';
        document.getElementById('modalSub').textContent      = 'Buat akun admin cabang baru';
        document.getElementById('modalSaveText').textContent = 'Simpan Admin';
        document.getElementById('mPassLabel').innerHTML      =
            'Password <span class="freq">*</span>';
        document.getElementById('mPassHelper').textContent   = 'Minimal 8 karakter';

        /* Route: POST /admin-pusat/user */
        modalForm.action = '/admin-pusat/user';
        document.getElementById('methodField').innerHTML = '';

        /* Populate bengkel dropdown dengan semua bengkel bebas */
        populateBengkelDropdown(null);

        openModal();
    });

    /* ================================================================
       MODAL — EDIT
    ================================================================ */
    window.editUser = function (id, nama, email, isActive, bengkelId, bengkelNama) {
        resetModal();
        document.getElementById('modalTitle').textContent    = 'Edit Admin Cabang';
        document.getElementById('modalSub').textContent      = nama;
        document.getElementById('modalSaveText').textContent = 'Simpan Perubahan';
        document.getElementById('mPassLabel').innerHTML      =
            'Password <span style="color:#9ca3af;font-weight:400">(kosongkan jika tidak diubah)</span>';
        document.getElementById('mPassHelper').textContent   =
            'Isi hanya jika ingin mengubah password';

        document.getElementById('mNama').value  = nama;
        document.getElementById('mEmail').value = email;
        document.getElementById('modalUserId').value = id;


        /* Route: PUT /admin-pusat/user/{id} */
        modalForm.action = `/admin-pusat/user/${id}`;
        document.getElementById('methodField').innerHTML =
            '<input type="hidden" name="_method" value="PUT">';

        /* Populate bengkel: tambahkan bengkel user saat ini juga */
        populateBengkelDropdown(bengkelId, bengkelNama);
        if (bengkelId) mBengkel.value = bengkelId;

        openModal();
    };

    /* ================================================================
       HELPERS
    ================================================================ */
    function resetModal() {
        modalForm?.reset();
        document.getElementById('modalUserId').value = '';
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('eyeIcon').className = 'bi bi-eye';
        document.getElementById('mPass').type = 'password';
    }

    function populateBengkelDropdown(currentBengkelId, currentBengkelNama) {
        if (!mBengkel) return;
        mBengkel.innerHTML = '<option value="">Tidak assign bengkel (opsional)</option>';

        /* Tambahkan bengkel yang saat ini dipegang user (kalau edit) */
        if (currentBengkelId && currentBengkelNama) {
            const opt = document.createElement('option');
            opt.value = currentBengkelId;
            opt.textContent = `${currentBengkelNama} — (saat ini)`;
            mBengkel.appendChild(opt);
        }

        /* Bengkel bebas dari server */
        bengkelFree.forEach(b => {
            if (b.id == currentBengkelId) return; /* sudah ditambahkan di atas */
            const opt = document.createElement('option');
            opt.value = b.id;
            opt.textContent = `${b.nama} — ${b.kota}`;
            mBengkel.appendChild(opt);
        });
    }

    function openModal()  { if (modalBackdrop) modalBackdrop.style.display = 'flex'; }
    window.closeModal = function () {
        if (modalBackdrop) modalBackdrop.style.display = 'none';
    };

    modalBackdrop?.addEventListener('click', e => {
        if (e.target === modalBackdrop) window.closeModal();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') window.closeModal();
    });


    /* Toggle password visibility */
    window.togglePassVisibility = function () {
        const input = document.getElementById('mPass');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    };

    /* ================================================================
       CLIENT-SIDE VALIDATION
    ================================================================ */
    modalForm?.addEventListener('submit', function (e) {
        let valid = true;
        const isEdit = Boolean(document.getElementById('modalUserId').value);

        const checks = [
            { id: 'mNama',  val: document.getElementById('mNama')?.value.trim() },
            { id: 'mEmail', val: document.getElementById('mEmail')?.value.trim() },
        ];

        /* Password wajib hanya untuk tambah baru */
        if (!isEdit) {
            checks.push({ id: 'mPass', val: document.getElementById('mPass')?.value });
        }

        checks.forEach(({ id, val }) => {
            const el = document.getElementById(id);
            if (!val) {
                el?.classList.add('finput-error');
                valid = false;
            } else {
                el?.classList.remove('finput-error');
            }
        });

        /* Cek panjang password kalau diisi */
        const pass = document.getElementById('mPass')?.value;
        if (pass && pass.length < 8) {
            document.getElementById('mPass')?.classList.add('finput-error');
            alert('Password minimal 8 karakter.');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            document.querySelector('.finput-error')?.focus();
        }
    });

})();