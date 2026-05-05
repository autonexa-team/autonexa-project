/**
 * admin-sparepart-form.js
 * Script untuk halaman Form Tambah / Edit Sparepart
 * Letakkan di: public/js/admin-sparepart-form.js
 */

document.addEventListener('DOMContentLoaded', function () {

    // ═══════════════════════════════════════════════
    //  ELEMEN
    // ═══════════════════════════════════════════════

    const form          = document.getElementById('formTambahSparepart');
    const btnSimpan     = document.getElementById('btnSimpan');
    const btnText       = btnSimpan?.querySelector('.spf-btn-text');
    const btnLoading    = btnSimpan?.querySelector('.spf-btn-loading');

    const namaInput     = document.getElementById('nama');
    const hargaDisplay  = document.getElementById('hargaDisplay');   // input tampilan (Rp formatted)
    const hargaHidden   = document.getElementById('harga');           // hidden field → dikirim ke server
    const hargaPreview  = document.getElementById('hargaPreview');

    const deskripsiInput = document.getElementById('deskripsi');
    const charCount      = document.getElementById('charCount');

    const MAX_DESC = 300;


    // ═══════════════════════════════════════════════
    //  HARGA — auto format ribuan
    // ═══════════════════════════════════════════════

    /**
     * Ubah angka mentah menjadi format ribuan: 85000 → "85.000"
     * @param {string|number} val
     * @returns {string}
     */
    function formatRibuan(val) {
        const angka = String(val).replace(/\D/g, '');
        if (!angka) return '';
        return parseInt(angka, 10).toLocaleString('id-ID');
    }

    /**
     * Hapus semua karakter non-digit: "85.000" → "85000"
     * @param {string} val
     * @returns {string}
     */
    function stripFormat(val) {
        return val.replace(/\D/g, '');
    }

    if (hargaDisplay) {
        // Inisialisasi jika ada nilai old() dari Blade
        if (hargaDisplay.value) {
            const raw = stripFormat(hargaDisplay.value);
            hargaDisplay.value = formatRibuan(raw);
            hargaHidden.value  = raw;
        }

        hargaDisplay.addEventListener('input', function () {
            const raw       = stripFormat(this.value);
            this.value      = formatRibuan(raw);       // tampilkan terformat
            hargaHidden.value = raw;                   // simpan angka mentah

            // Preview teks di bawah input
            if (raw) {
                const nominal = parseInt(raw, 10).toLocaleString('id-ID');
                hargaPreview.innerHTML = `Terbaca: <strong>Rp ${nominal}</strong>`;
            } else {
                hargaPreview.textContent = 'Masukkan angka, titik akan ditambahkan otomatis';
                hargaPreview.innerHTML   = 'Masukkan angka, titik akan ditambahkan otomatis';
            }

            // Hapus visual error saat user mulai mengetik
            clearError(hargaDisplay);
        });

        // Jaga posisi kursor agar tidak loncat saat format
        hargaDisplay.addEventListener('keydown', function (e) {
            // Izinkan: angka, backspace, delete, arrow, tab, home, end
            const allowed = [
                'Backspace','Delete','ArrowLeft','ArrowRight',
                'ArrowUp','ArrowDown','Tab','Home','End'
            ];
            const isDigit = /^[0-9]$/.test(e.key);
            if (!isDigit && !allowed.includes(e.key) && !e.ctrlKey && !e.metaKey) {
                e.preventDefault();
            }
        });
    }


    // ═══════════════════════════════════════════════
    //  CHAR COUNT — deskripsi
    // ═══════════════════════════════════════════════

    function updateCharCount() {
        if (!deskripsiInput || !charCount) return;
        const len = deskripsiInput.value.length;
        charCount.textContent = `${len} / ${MAX_DESC}`;

        charCount.classList.remove('near-limit', 'at-limit');
        if (len >= MAX_DESC) {
            charCount.classList.add('at-limit');
        } else if (len >= MAX_DESC * 0.85) {
            charCount.classList.add('near-limit');
        }
    }

    deskripsiInput?.addEventListener('input', function () {
        updateCharCount();
        clearError(this);
    });

    // Inisialisasi dari old()
    updateCharCount();


    // ═══════════════════════════════════════════════
    //  CLEAR ERROR saat user mulai mengetik
    // ═══════════════════════════════════════════════

    function clearError(inputEl) {
        inputEl.classList.remove('input-error');
        const group = inputEl.closest('.sp-form-group');
        if (!group) return;
        group.classList.remove('has-error');
        const errMsg = group.querySelector('.spf-error-msg');
        if (errMsg) errMsg.remove();
    }

    namaInput?.addEventListener('input', function () {
        clearError(this);
    });


    // ═══════════════════════════════════════════════
    //  CLIENT-SIDE VALIDATION sebelum submit
    // ═══════════════════════════════════════════════

    function showError(inputEl, msg) {
        const group = inputEl.closest('.sp-form-group');
        if (!group) return;

        inputEl.classList.add('input-error');
        group.classList.add('has-error');

        // Hapus error lama jika ada
        const existing = group.querySelector('.spf-error-msg');
        if (existing) existing.remove();

        const span = document.createElement('span');
        span.className = 'spf-error-msg';
        span.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${msg}`;

        // Sisipkan setelah input / prefix-wrap
        const prefixWrap = group.querySelector('.sp-input-prefix-wrap');
        const target     = prefixWrap || inputEl;
        target.insertAdjacentElement('afterend', span);

        // Scroll ke error pertama
        if (!document.querySelector('.input-error:first-of-type') ||
            group === document.querySelector('.has-error')) {
            inputEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            inputEl.focus();
        }
    }

    function validateForm() {
        let valid = true;

        // Nama
        if (!namaInput?.value.trim()) {
            showError(namaInput, 'Nama sparepart wajib diisi');
            valid = false;
        } else if (namaInput.value.trim().length < 3) {
            showError(namaInput, 'Nama sparepart minimal 3 karakter');
            valid = false;
        }

        // Harga
        const hargaRaw = parseInt(hargaHidden?.value || '0', 10);
        if (!hargaRaw || hargaRaw <= 0) {
            showError(hargaDisplay, 'Harga wajib diisi dan harus lebih dari 0');
            valid = false;
        }

        return valid;
    }


    // ═══════════════════════════════════════════════
    //  SUBMIT — loading state & disable button
    // ═══════════════════════════════════════════════

    form?.addEventListener('submit', function (e) {
        e.preventDefault();

        // Validasi client-side
        if (!validateForm()) return;

        // Pastikan hidden field terisi sebelum submit
        // (jika user paste tanpa trigger input event)
        if (hargaDisplay && hargaHidden) {
            hargaHidden.value = stripFormat(hargaDisplay.value);
        }

        // Loading state
        setLoadingState(true);

        // Submit form native
        this.submit();
    });

    function setLoadingState(loading) {
        if (!btnSimpan) return;

        btnSimpan.disabled = loading;

        if (btnText)    btnText.style.display    = loading ? 'none' : '';
        if (btnLoading) btnLoading.style.display = loading ? 'inline-flex' : 'none';
    }


    // ═══════════════════════════════════════════════
    //  RESET loading state jika halaman di-cache
    //  (browser back/forward navigation)
    // ═══════════════════════════════════════════════

    window.addEventListener('pageshow', function (e) {
        if (e.persisted) setLoadingState(false);
    });

});