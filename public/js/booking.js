/* public/js/booking.js */
(function () {
    'use strict';

    /* ── Elemen ── */
    const bengkelCards   = document.querySelectorAll('.bengkel-card');
    const bengkelIdInput = document.getElementById('bengkelId');
    const bengkelIdForm  = document.getElementById('bengkelIdForm');
    const bengkelList    = document.getElementById('bengkelList');
    const bengkelPreview = document.getElementById('bengkelPreview');
    const btnGanti       = document.getElementById('btnGanti');
    const searchInput    = document.getElementById('searchBengkel');
    const tanggalInput   = document.getElementById('tanggalInput');
    const jamGrid        = document.getElementById('jamGrid');
    const layananCards   = document.querySelectorAll('.layanan-card');
    const estimasiBox    = document.getElementById('estimasiBox');
    const ringkasanEmpty = document.getElementById('ringkasanEmpty');
    const ringkasanDetail= document.getElementById('ringkasanDetail');

    /* Leaflet map */
    let map        = null;
    let mapMarker  = null;
    let selectedBengkelData = {};
    let selectedJam = null;
    let selectedLayanan = null;

    /* ================================================================
       BENGKEL — pilih
    ================================================================ */
    bengkelCards.forEach(card => {
        card.addEventListener('click', function () {
            if (this.dataset.penuh === '1') return;

            bengkelCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            selectedBengkelData = {
                id     : this.dataset.id,
                nama   : this.dataset.nama,
                alamat : this.dataset.alamat,
                foto   : this.dataset.foto,
                rating : parseFloat(this.dataset.rating) || 0,
                lat    : parseFloat(this.dataset.lat) || null,
                lng    : parseFloat(this.dataset.lng) || null,
            };

            bengkelIdInput.value  = selectedBengkelData.id;
            bengkelIdForm.value   = selectedBengkelData.id;

            /* Preview */
            document.getElementById('previewFoto').src             = selectedBengkelData.foto;
            document.getElementById('previewNama').textContent     = selectedBengkelData.nama;
            document.getElementById('previewAlamat').textContent   = selectedBengkelData.alamat;

            let stars = '';
            for (let i = 1; i <= 5; i++) {
                const cls = i <= selectedBengkelData.rating ? 'bi-star-fill star-on' : 'bi-star star-off';
                stars += `<i class="bi ${cls}"></i>`;
            }
            document.getElementById('previewRating').innerHTML =
                stars + ` <small>(${selectedBengkelData.rating.toFixed(1)})</small>`;

            bengkelList.style.display    = 'none';
            bengkelPreview.style.display = 'flex';

            /* Init / update map */
            if (selectedBengkelData.lat && selectedBengkelData.lng) {
                initMap(selectedBengkelData.lat, selectedBengkelData.lng, selectedBengkelData.nama);
            }

            /* Reload slot jika tanggal sudah dipilih */
            if (tanggalInput.value) loadSlotJam();

            updateRingkasan();
        });
    });

    /* Ganti bengkel */
    btnGanti?.addEventListener('click', () => {
        bengkelCards.forEach(c => c.classList.remove('active'));
        bengkelList.style.display    = 'block';
        bengkelPreview.style.display = 'none';
        bengkelIdInput.value         = '';
        bengkelIdForm.value          = '';
        selectedBengkelData          = {};
        updateRingkasan();
    });

    /* Search */
    searchInput?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        bengkelCards.forEach(card => {
            const nama   = card.dataset.nama.toLowerCase();
            const alamat = card.dataset.alamat.toLowerCase();
            card.style.display = (nama.includes(q) || alamat.includes(q)) ? 'flex' : 'none';
        });
    });

    /* ================================================================
       SLOT WAKTU — load dari server saat bengkel + tanggal dipilih
    ================================================================ */
    tanggalInput?.addEventListener('change', () => {
        if (bengkelIdInput.value) loadSlotJam();
        updateRingkasan();
    });

    async function loadSlotJam() {
        const bengkelId = bengkelIdInput.value;
        const tanggal   = tanggalInput.value;
        if (!bengkelId || !tanggal) return;

        /* Reset semua slot */
        document.querySelectorAll('.jam-btn').forEach(btn => {
            btn.classList.remove('penuh', 'selected');
            btn.querySelector('.jam-sub').textContent = '';
        });
        selectedJam = null;

        try {
            const res  = await fetch(`/api/booking/slot?bengkel_id=${bengkelId}&tanggal=${tanggal}`);
            const data = await res.json();
            /*
              Response: { "08:00": 3, "09:00": 5, ... }
              Nilai = jumlah reservasi di slot tersebut
              Kapasitas per hari dibagi rata ke jam, atau gunakan threshold server
            */
            const kapasitasHarian = parseInt(window.kapasitasBengkel?.[bengkelId] ?? 8);
            const jamCount        = 8;
            const kapPerJam       = Math.ceil(kapasitasHarian / jamCount);

            document.querySelectorAll('.jam-btn').forEach(btn => {
                const jam    = btn.dataset.jam;
                const terisi = data[jam] ?? 0;
                const sub    = btn.querySelector('.jam-sub');

                if (terisi >= kapPerJam) {
                    btn.classList.add('penuh');
                    sub.textContent = 'Penuh';
                } else {
                    const sisa = kapPerJam - terisi;
                    sub.textContent = sisa <= 2 ? `Sisa ${sisa}` : '';
                }
            });
        } catch (e) {
            console.warn('Gagal load slot:', e);
        }
    }

    /* Pilih jam */
    document.querySelectorAll('.jam-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            if (this.classList.contains('penuh')) return;
            document.querySelectorAll('.jam-btn').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
            selectedJam = this.dataset.jam;
            document.getElementById('waktuForm').value = selectedJam;
            updateRingkasan();
        });
    });

    /* ================================================================
       LAYANAN — pilih satu + tampilkan estimasi
    ================================================================ */
    layananCards.forEach(card => {
        card.addEventListener('click', function () {
            layananCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');

            selectedLayanan = {
                id     : this.dataset.id,
                nama   : this.dataset.nama,
                durasi : parseInt(this.dataset.durasi) || 0,
                harga  : parseInt(this.dataset.harga)  || 0,
            };

            document.getElementById('layananIdForm').value = selectedLayanan.id;

            /* Update estimasi box */
            document.getElementById('estNama').textContent   = selectedLayanan.nama;
            document.getElementById('estDurasi').textContent = `± ${selectedLayanan.durasi} menit`;
            document.getElementById('estHarga').textContent  =
                'Rp ' + selectedLayanan.harga.toLocaleString('id-ID');
            document.getElementById('estTotal').textContent  =
                'Rp ' + selectedLayanan.harga.toLocaleString('id-ID') + '+';
            estimasiBox.style.display = 'block';

            updateRingkasan();
        });
    });

    /* ================================================================
       FOTO KENDARAAN
    ================================================================ */
    const fotoArea    = document.getElementById('fotoUploadArea');
    const fotoInput   = document.getElementById('fotoKendaraan');
    const fotoPreview = document.getElementById('fotoPreview');
    const fotoImg     = document.getElementById('fotoPreviewImg');
    const btnHapus    = document.getElementById('btnHapusFoto');

    fotoArea?.addEventListener('click', () => fotoInput?.click());
    fotoArea?.addEventListener('dragover', e => {
        e.preventDefault();
        fotoArea.style.borderColor = '#f97316';
    });
    fotoArea?.addEventListener('dragleave', () => {
        fotoArea.style.borderColor = '';
    });
    fotoArea?.addEventListener('drop', e => {
        e.preventDefault();
        fotoArea.style.borderColor = '';
        const file = e.dataTransfer.files[0];
        if (file) handleFoto(file);
    });

    fotoInput?.addEventListener('change', function () {
        if (this.files[0]) handleFoto(this.files[0]);
    });

    btnHapus?.addEventListener('click', () => {
        fotoInput.value      = '';
        fotoImg.src          = '';
        fotoPreview.style.display = 'none';
        fotoArea.style.display   = 'block';
    });

    function handleFoto(file) {
        if (!file.type.startsWith('image/')) return;
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto maksimal 2MB');
            return;
        }
        const reader = new FileReader();
        reader.onload = e => {
            fotoImg.src              = e.target.result;
            fotoPreview.style.display = 'block';
            fotoArea.style.display   = 'none';
        };
        reader.readAsDataURL(file);
    }

    /* ================================================================
       LEAFLET MAP
    ================================================================ */
    function initMap(lat, lng, nama) {
        const mapEl = document.getElementById('map');
        if (!mapEl) return;

        const orangeIcon = L.divIcon({
            className: '',
            html: `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="36" viewBox="0 0 28 36">
                <path d="M14 0C6.3 0 0 6.3 0 14c0 9.8 14 22 14 22S28 23.8 28 14C28 6.3 21.7 0 14 0z" fill="#ff6a00"/>
                <circle cx="14" cy="14" r="6" fill="#fff"/>
            </svg>`,
            iconSize:   [28, 36],
            iconAnchor: [14, 36],
            popupAnchor:[0, -36],
        });

        if (!map) {
            map = L.map('map', { zoomControl: true, scrollWheelZoom: false })
                   .setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                maxZoom: 19,
            }).addTo(map);
        } else {
            map.setView([lat, lng], 15);
        }

        if (mapMarker) mapMarker.remove();
        mapMarker = L.marker([lat, lng], { icon: orangeIcon })
            .addTo(map)
            .bindPopup(`<strong>${nama}</strong>`)
            .openPopup();
    }

    /* ================================================================
       UPDATE RINGKASAN LIVE
    ================================================================ */
    function updateRingkasan() {
        const hasBengkel  = bengkelIdInput.value;
        const hasTanggal  = tanggalInput.value;

        if (!hasBengkel) {
            ringkasanEmpty.style.display   = 'flex';
            ringkasanDetail.style.display  = 'none';
            return;
        }

        ringkasanEmpty.style.display  = 'none';
        ringkasanDetail.style.display = 'block';

        /* Bengkel */
        document.getElementById('rBengkel').textContent = selectedBengkelData.nama || '—';

        /* Kendaraan */
        const merk  = document.querySelector('input[name="merk"]')?.value  || '';
        const tipe  = document.querySelector('input[name="tipe"]')?.value   || '';
        const plat  = document.querySelector('input[name="plat"]')?.value   || '';
        document.getElementById('rKendaraan').textContent =
            merk && tipe ? `${merk} ${tipe} · ${plat}` : '—';

        /* Layanan */
        document.getElementById('rLayanan').textContent =
            selectedLayanan ? selectedLayanan.nama : '—';

        /* Tanggal */
        document.getElementById('rTanggal').textContent = hasTanggal
            ? new Date(hasTanggal).toLocaleDateString('id-ID', {
                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
              })
            : '—';

        /* Waktu */
        document.getElementById('rWaktu').textContent =
            selectedJam ? selectedJam + ' WIB' : '—';

        /* Estimasi di ringkasan */
        const reBox = document.getElementById('ringkasanEstimasi');
        if (selectedLayanan) {
            reBox.style.display = 'block';
            document.getElementById('reDurasi').textContent =
                `± ${selectedLayanan.durasi} menit`;
            document.getElementById('reHarga').textContent =
                'Rp ' + selectedLayanan.harga.toLocaleString('id-ID') + '+';
        } else {
            reBox.style.display = 'none';
        }
    }

    /* Update ringkasan saat isi input kendaraan berubah */
    ['input[name="merk"]', 'input[name="tipe"]', 'input[name="plat"]'].forEach(sel => {
        document.querySelector(sel)?.addEventListener('input', updateRingkasan);
    });

    /* ================================================================
       VALIDASI SUBMIT
    ================================================================ */
    document.getElementById('formBooking')?.addEventListener('submit', function (e) {
        if (!bengkelIdForm.value) {
            e.preventDefault();
            alert('Silakan pilih bengkel terlebih dahulu.');
            document.getElementById('blok-bengkel').scrollIntoView({ behavior: 'smooth' });
            return;
        }
        if (!document.getElementById('layananIdForm').value) {
            e.preventDefault();
            alert('Silakan pilih layanan utama terlebih dahulu.');
            return;
        }
        if (!document.getElementById('waktuForm').value) {
            e.preventDefault();
            alert('Silakan pilih waktu service.');
            return;
        }
    });

})();