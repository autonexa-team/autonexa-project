/**
 * review-edit.js
 * Fitur: toggle edit form, bintang interaktif, char count, konfirmasi hapus
 *
 * Ganti bagian "EDIT REVIEW" di inline script blade dengan:
 *   <script src="{{ asset('js/review-edit.js') }}"></script>
 */

document.addEventListener('DOMContentLoaded', function () {

    // ── ELEMEN ──────────────────────────────────────────────────
    const btnEditReview  = document.getElementById('btnEditReview');
    const btnCancelEdit  = document.getElementById('btnCancelEdit');
    const btnHapusReview = document.getElementById('btnHapusReview');
    const reviewDisplay  = document.getElementById('reviewDisplay');
    const reviewEditForm = document.getElementById('reviewEditForm');
    const formHapus      = document.getElementById('formHapusReview');

    // ── TOGGLE TAMPIL/SEMBUNYIKAN FORM EDIT ─────────────────────
    btnEditReview?.addEventListener('click', () => {
        reviewDisplay.style.display  = 'none';
        reviewEditForm.style.display = 'block';
        // Fokus ke textarea
        document.getElementById('editKomentar')?.focus();
    });

    btnCancelEdit?.addEventListener('click', () => {
        reviewEditForm.style.display = 'none';
        reviewDisplay.style.display  = 'block';
    });

    // ── BINTANG INTERAKTIF ───────────────────────────────────────
    const editStars      = document.querySelectorAll('.re-edit-star');
    const editRatingInput = document.getElementById('editRatingInput');
    const editRatingLbl  = document.getElementById('editRatingLbl');

    const ratingLabels = ['', 'Sangat Buruk', 'Kurang Baik', 'Cukup', 'Baik', 'Sangat Baik'];

    function setStars(val) {
        editStars.forEach((s, i) => {
            s.classList.toggle('re-edit-star--on', i < val);
        });
        if (editRatingInput) editRatingInput.value = val;
        if (editRatingLbl)   editRatingLbl.textContent = val
            ? `${val} / 5 bintang — ${ratingLabels[val]}`
            : '';
    }

    editStars.forEach(star => {
        const val = parseInt(star.dataset.val);

        star.addEventListener('mouseenter', () => setStars(val));

        star.addEventListener('mouseleave', () => {
            // kembali ke nilai tersimpan
            setStars(parseInt(editRatingInput?.value || 0));
        });

        star.addEventListener('click', () => {
            setStars(val);
        });

        // Keyboard support
        star.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                setStars(val);
            }
        });
    });

    // ── CHAR COUNT ───────────────────────────────────────────────
    const editKomentar  = document.getElementById('editKomentar');
    const editCharCount = document.getElementById('editCharCount');
    const MAX_CHAR = 500;

    function updateCharCount() {
        if (!editKomentar || !editCharCount) return;
        const len = editKomentar.value.length;
        editCharCount.textContent = `${len} / ${MAX_CHAR}`;
        editCharCount.classList.remove('re-char-count--warn', 're-char-count--full');
        if (len >= MAX_CHAR)           editCharCount.classList.add('re-char-count--full');
        else if (len >= MAX_CHAR * 0.85) editCharCount.classList.add('re-char-count--warn');
    }

    editKomentar?.addEventListener('input', updateCharCount);
    updateCharCount(); // inisialisasi

    // ── SUBMIT FORM EDIT — loading state ────────────────────────
    const formEditReview = document.getElementById('formEditReview');
    const btnSimpan      = document.getElementById('btnSimpanEdit');

    formEditReview?.addEventListener('submit', function () {
        if (btnSimpan) {
            btnSimpan.innerHTML  = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btnSimpan.disabled   = true;
        }
    });

    // ── KONFIRMASI HAPUS ─────────────────────────────────────────
    // Buat elemen konfirmasi secara dinamis agar tidak perlu HTML tambahan
    if (btnHapusReview && formHapus) {

        let confirmBox = null;

        btnHapusReview.addEventListener('click', function () {
            if (confirmBox) {
                // Sudah tampil → hapus lagi
                confirmBox.remove();
                confirmBox = null;
                return;
            }

            confirmBox = document.createElement('div');
            confirmBox.className = 're-delete-confirm show';
            confirmBox.innerHTML = `
                <strong>Yakin ingin menghapus ulasan ini?</strong><br>
                Tindakan ini tidak dapat dibatalkan.
                <div class="re-delete-confirm-btns">
                    <button type="button" class="btn btn--ghost btn--sm" id="confirmNo">
                        Tidak, Batal
                    </button>
                    <button type="button" class="btn btn--danger btn--sm" id="confirmYes">
                        <i class="fas fa-trash"></i> Ya, Hapus
                    </button>
                </div>`;

            // Sisipkan setelah tombol aksi
            btnHapusReview.closest('.re-actions')?.insertAdjacentElement('afterend', confirmBox);

            document.getElementById('confirmNo')?.addEventListener('click', () => {
                confirmBox.remove();
                confirmBox = null;
            });

            document.getElementById('confirmYes')?.addEventListener('click', () => {
                document.getElementById('confirmYes').innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i> Menghapus...';
                formHapus.submit();
            });
        });
    }

    // ── RESET loading jika halaman di-cache (browser back) ───────
    window.addEventListener('pageshow', function (e) {
        if (e.persisted && btnSimpan) {
            btnSimpan.innerHTML = '<i class="fas fa-floppy-disk"></i> Simpan Perubahan';
            btnSimpan.disabled  = false;
        }
    });

});