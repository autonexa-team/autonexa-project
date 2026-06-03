/**
 * admin-reservasi.js
 * Letakkan di: public/js/admin-reservasi.js
 *
 * Fitur:
 * 1. Search real-time (nama, bengkel, keluhan)
 * 2. Filter dropdown status
 * 3. Kombinasi search + filter
 * 4. Counter update
 * 5. Empty state
 */

document.addEventListener('DOMContentLoaded', function () {

    // ── ELEMEN ──────────────────────────────────────────────────
    const searchInput   = document.getElementById('rvSearch');
    const clearBtn      = document.getElementById('rvClearSearch');
    const filterStatus  = document.getElementById('rvFilterStatus');
    const countLabel    = document.getElementById('rvCount');
    const tableEl       = document.getElementById('rvTable');
    const emptyState    = document.getElementById('rvEmptyState');
    const rows          = document.querySelectorAll('.rv-row');

    // ── FILTER LOGIC ────────────────────────────────────────────
    function applyFilter() {
        const keyword = searchInput.value.trim().toLowerCase();
        const status  = filterStatus.value;
        let   visible = 0;

        rows.forEach(row => {
            const matchSearch = !keyword || row.dataset.search.includes(keyword);
            const matchStatus = !status  || row.dataset.status === status;
            const show        = matchSearch && matchStatus;

            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        // Update counter
        countLabel.innerHTML = `Menampilkan <strong>${visible}</strong> reservasi`;

        // Toggle clear button
        clearBtn.style.display = keyword ? 'flex' : 'none';

        // Toggle empty state
        const isEmpty = visible === 0;
        emptyState.style.display     = isEmpty ? 'flex' : 'none';
        tableEl.style.display        = isEmpty ? 'none' : '';
    }

    // ── EVENT LISTENERS ─────────────────────────────────────────
    searchInput?.addEventListener('input', applyFilter);

    filterStatus?.addEventListener('change', applyFilter);

    clearBtn?.addEventListener('click', function () {
        searchInput.value = '';
        applyFilter();
        searchInput.focus();
    });

    // ── KEYBOARD SHORTCUT: Ctrl+F / Cmd+F → fokus search ───────
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            if (searchInput) {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
            }
        }
    });

});