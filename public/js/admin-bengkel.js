/* public/js/admin-bengkel.js */
(function () {
    'use strict';

    /* ── Live search & filter (client-side untuk UX cepat) ── */
    const searchInput  = document.getElementById('searchInput');
    const filterStatus = document.getElementById('filterStatus');
    const filterKota   = document.getElementById('filterKota');
    const grid         = document.getElementById('bengkelGrid');

    function filterCards() {
        const q  = (searchInput?.value  || '').toLowerCase();
        const st = filterStatus?.value  || '';
        const kt = filterKota?.value    || '';

        document.querySelectorAll('.bengkel-card').forEach(card => {
            const name   = (card.querySelector('.card-name')?.textContent || '').toLowerCase();
            const addr   = (card.querySelector('.card-addr')?.textContent || '').toLowerCase();
            const status = card.dataset.status || '';
            const kota   = card.dataset.kota   || '';

            const matchQ  = !q  || name.includes(q) || addr.includes(q);
            const matchSt = !st || status === st;
            const matchKt = !kt || kota === kt;

            card.style.display = (matchQ && matchSt && matchKt) ? '' : 'none';
        });

        /* Tampilkan empty state jika semua card tersembunyi */
        const visible = document.querySelectorAll('.bengkel-card[style=""]').length +
                        document.querySelectorAll('.bengkel-card:not([style])').length;
        const existing = document.getElementById('emptyFilter');
        if (visible === 0 && !existing) {
            const div = document.createElement('div');
            div.id = 'emptyFilter';
            div.className = 'empty-state';
            div.innerHTML = `<i class="bi bi-search empty-icon"></i>
                <p class="empty-title">Tidak ada hasil ditemukan</p>
                <p class="empty-sub">Coba ubah kata kunci atau filter</p>`;
            grid?.appendChild(div);
        } else if (visible > 0 && existing) {
            existing.remove();
        }
    }

    searchInput?.addEventListener('input',  filterCards);
    filterStatus?.addEventListener('change', filterCards);
    filterKota?.addEventListener('change',   filterCards);

    /* ── View Toggle: Grid ↔ List ── */
    document.getElementById('btnGrid')?.addEventListener('click', function () {
        grid?.classList.remove('list-view');
        this.classList.add('active');
        document.getElementById('btnList')?.classList.remove('active');
    });

    document.getElementById('btnList')?.addEventListener('click', function () {
        grid?.classList.add('list-view');
        this.classList.add('active');
        document.getElementById('btnGrid')?.classList.remove('active');
    });

})();

/* ================================================================
   TAMBAH BENGKEL — Append ke admin-bengkel.js
================================================================ */

(function () {
    'use strict';

    /* Hanya jalankan di halaman tambah bengkel */
    if (!document.getElementById('formTambahBengkel')) return;

    /* ── Elemen ── */
    const btnGeocode    = document.getElementById('btnGeocode');
    const alamatInput   = document.getElementById('alamat');
    const latInput      = document.getElementById('latitude');
    const lngInput      = document.getElementById('longitude');
    const kotaInput     = document.getElementById('kota');
    const geoLoading    = document.getElementById('geoLoading');
    const geoSuccess    = document.getElementById('geoSuccess');
    const geoError      = document.getElementById('geoError');
    const mapEmptyState = document.getElementById('mapEmptyState');
    const leafletMap    = document.getElementById('leafletMap');
    const fotoInput     = document.getElementById('fotoInput');
    const uploadArea    = document.getElementById('uploadArea');
    const photoPreview  = document.getElementById('photoPreview');
    const previewImg    = document.getElementById('previewImg');
    const photoFilename = document.getElementById('photoFilename');
    const btnRemove     = document.getElementById('btnRemovePhoto');
    const toggleTrack   = document.getElementById('toggleTrack');
    const toggleLabel   = document.getElementById('toggleLabel');
    const statusInput   = document.getElementById('statusInput');

    let leafletInstance = null;
    let markerInstance  = null;

    /* ================================================================
       STATUS TOGGLE
    ================================================================ */
    document.getElementById('toggleWrap')?.addEventListener('click', function () {
        const isOn = toggleTrack.classList.toggle('on');
        toggleLabel.textContent = isOn ? 'Aktif' : 'Nonaktif';
        toggleLabel.className   = isOn ? 'toggle-lbl' : 'toggle-lbl off';
        statusInput.value       = isOn ? 'aktif' : 'nonaktif';
    });

    /* ================================================================
       GEOCODING via Nominatim (OpenStreetMap — gratis, tanpa API key)
    ================================================================ */
    function hideGeoStates() {
        geoLoading.style.display = 'none';
        geoSuccess.style.display = 'none';
        geoError.style.display   = 'none';
    }

    btnGeocode?.addEventListener('click', async function () {
        const query = alamatInput?.value.trim();
        if (!query) { alamatInput?.focus(); return; }

        hideGeoStates();
        geoLoading.style.display = 'flex';
        btnGeocode.disabled = true;

        try {
            const res  = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(query)}`,
                { headers: { 'Accept-Language': 'id' } }
            );
            const data = await res.json();

            if (!data.length) throw new Error('not_found');

            const { lat, lon, display_name } = data[0];

            /* Update inputs */
            latInput.value  = parseFloat(lat).toFixed(6);
            lngInput.value  = parseFloat(lon).toFixed(6);

            /* Ekstrak kota dari display_name */
            const parts = display_name.split(',');
            kotaInput.value = parts.length >= 3 ? parts[parts.length - 3].trim() : parts[0].trim();

            /* Tampilkan peta */
            showMap(parseFloat(lat), parseFloat(lon));

            geoLoading.style.display = 'none';
            geoSuccess.style.display = 'flex';
            document.getElementById('geoSuccessText').textContent =
                `Ditemukan: ${parts.slice(0, 2).join(', ')}`;

        } catch (e) {
            geoLoading.style.display = 'none';
            geoError.style.display   = 'flex';
        } finally {
            btnGeocode.disabled = false;
        }
    });

    /* ================================================================
       LEAFLET MAP
    ================================================================ */
    function showMap(lat, lng) {
        mapEmptyState.style.display = 'none';
        leafletMap.style.display    = 'block';

        if (!leafletInstance) {
            /* Inisialisasi Leaflet pertama kali */
            leafletInstance = L.map('leafletMap').setView([lat, lng], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(leafletInstance);

            /* Custom marker orange */
            const orangeIcon = L.divIcon({
                className: '',
                html: `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="36" viewBox="0 0 28 36">
                    <path d="M14 0C6.3 0 0 6.3 0 14c0 9.8 14 22 14 22s14-12.2 14-22C28 6.3 21.7 0 14 0z" fill="#ff6a00"/>
                    <circle cx="14" cy="14" r="6" fill="white"/>
                </svg>`,
                iconSize:   [28, 36],
                iconAnchor: [14, 36],
            });

            markerInstance = L.marker([lat, lng], {
                icon: orangeIcon,
                draggable: true,
            }).addTo(leafletInstance);

            /* Update koordinat saat marker digeser */
            markerInstance.on('dragend', function () {
                const pos = this.getLatLng();
                latInput.value = pos.lat.toFixed(6);
                lngInput.value = pos.lng.toFixed(6);
            });

        } else {
            /* Update posisi jika map sudah ada */
            leafletInstance.setView([lat, lng], 16);
            markerInstance.setLatLng([lat, lng]);
        }
    }

    /* Sinkron: jika lat/lng diubah manual, geser marker */
    [latInput, lngInput].forEach(input => {
        input?.addEventListener('change', function () {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            if (!isNaN(lat) && !isNaN(lng) && leafletInstance) {
                leafletInstance.setView([lat, lng], 16);
                markerInstance.setLatLng([lat, lng]);
            }
        });

        /* Jadikan input bisa diedit meski readonly */
        input?.addEventListener('click', function () {
            this.removeAttribute('readonly');
        });
    });

    /* ================================================================
       FOTO UPLOAD
    ================================================================ */
    fotoInput?.addEventListener('change', function () {
        handlePhotoFile(this.files[0]);
    });

    window.handleDrop = function (e) {
        e.preventDefault();
        uploadArea?.classList.remove('upload-drag');
        const file = e.dataTransfer.files[0];
        if (!file || !file.type.startsWith('image/')) return;

        /* Assign ke input */
        const dt = new DataTransfer();
        dt.items.add(file);
        fotoInput.files = dt.files;
        handlePhotoFile(file);
    };

    function handlePhotoFile(file) {
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file melebihi 2 MB. Pilih foto yang lebih kecil.');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src         = e.target.result;
            uploadArea.style.display  = 'none';
            photoPreview.style.display = 'block';
            photoFilename.style.display = 'block';
            photoFilename.textContent  =
                `${file.name} · ${(file.size / 1024).toFixed(0)} KB`;
        };
        reader.readAsDataURL(file);
    }

    btnRemove?.addEventListener('click', function () {
        fotoInput.value            = '';
        previewImg.src             = '';
        photoPreview.style.display  = 'none';
        uploadArea.style.display    = 'block';
        photoFilename.style.display = 'none';
    });

    /* ================================================================
       CLIENT-SIDE VALIDATION
    ================================================================ */
    document.getElementById('formTambahBengkel')?.addEventListener('submit', function (e) {
        let valid = true;
        const required = ['nama', 'admin_id', 'alamat'];

        required.forEach(name => {
            const el = this.elements[name];
            if (!el || !el.value.trim()) {
                el?.classList.add('finput-error');
                valid = false;
            } else {
                el?.classList.remove('finput-error');
            }
        });

        if (!valid) {
            e.preventDefault();
            this.querySelector('.finput-error')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

})();

// halaman detail bengkel 
/* ================================================================
   DETAIL BENGKEL — Append ke admin-bengkel.js
================================================================ */

(function () {
    'use strict';

    /* Hanya jalankan di halaman detail (ada #detailMap) */
    const mapEl = document.getElementById('detailMap');
    if (!mapEl || typeof L === 'undefined') return;

    const lat  = window.bengkelLat  || -6.2088;
    const lng  = window.bengkelLng  || 106.8456;
    const nama = window.bengkelNama || 'Bengkel';

    /* Init Leaflet read-only (tidak bisa di-drag) */
    const map = L.map('detailMap', {
        center: [lat, lng],
        zoom: 16,
        zoomControl: true,
        scrollWheelZoom: false,
        dragging: true,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    /* Custom orange marker */
    const orangeIcon = L.divIcon({
        className: '',
        html: `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="36" viewBox="0 0 28 36">
            <path d="M14 0C6.3 0 0 6.3 0 14c0 9.8 14 22 14 22s14-12.2 14-22C28 6.3 21.7 0 14 0z" fill="#ff6a00"/>
            <circle cx="14" cy="14" r="6" fill="white"/>
        </svg>`,
        iconSize:   [28, 36],
        iconAnchor: [14, 36],
        popupAnchor:[0, -36],
    });

    L.marker([lat, lng], { icon: orangeIcon, draggable: false })
     .addTo(map)
     .bindPopup(`<strong>${nama}</strong>`, { closeButton: false })
     .openPopup();

})();

/* ================================================================
// edit bengkel js
/* ================================================================
   EDIT BENGKEL — Append ke admin-bengkel.js
================================================================ */

(function () {
    'use strict';

    /* Hanya jalankan di halaman edit (ada formEditBengkel) */
    if (!document.getElementById('formEditBengkel')) return;

    const data = window.editBengkelData || {};

    /* ── Elemen (sama dengan tambah, cuma formnya beda) ── */
    const btnGeocode    = document.getElementById('btnGeocode');
    const alamatInput   = document.getElementById('alamat');
    const latInput      = document.getElementById('latitude');
    const lngInput      = document.getElementById('longitude');
    const kotaInput     = document.getElementById('kota');
    const geoLoading    = document.getElementById('geoLoading');
    const geoSuccess    = document.getElementById('geoSuccess');
    const geoError      = document.getElementById('geoError');
    const mapEmptyState = document.getElementById('mapEmptyState');
    const leafletMapEl  = document.getElementById('leafletMap');
    const fotoInput     = document.getElementById('fotoInput');
    const uploadArea    = document.getElementById('uploadArea');
    const photoPreview  = document.getElementById('photoPreview');
    const previewImg    = document.getElementById('previewImg');
    const photoFilename = document.getElementById('photoFilename');
    const btnRemove     = document.getElementById('btnRemovePhoto');
    const fotoExisting  = document.getElementById('fotoExisting');
    const btnHapusFoto  = document.getElementById('btnHapusFoto');
    const hapusFotoInput= document.getElementById('hapusFotoInput');
    const toggleTrack   = document.getElementById('toggleTrack');
    const toggleLabel   = document.getElementById('toggleLabel');
    const statusInput   = document.getElementById('statusInput');

    let leafletInstance = null;
    let markerInstance  = null;

    /* ================================================================
       STATUS TOGGLE — pre-fill dari data bengkel
    ================================================================ */
    document.getElementById('toggleWrap')?.addEventListener('click', function () {
        const isOn = toggleTrack.classList.toggle('on');
        toggleLabel.textContent  = isOn ? 'Aktif' : 'Nonaktif';
        toggleLabel.className    = isOn ? 'toggle-lbl' : 'toggle-lbl off';
        statusInput.value        = isOn ? 'aktif' : 'nonaktif';
    });

    /* ================================================================
       MAP — langsung tampil jika sudah ada koordinat
    ================================================================ */
    function initMap(lat, lng) {
        if (!leafletMapEl || typeof L === 'undefined') return;

        mapEmptyState.style.display  = 'none';
        leafletMapEl.style.display   = 'block';

        if (!leafletInstance) {
            leafletInstance = L.map('leafletMap').setView([lat, lng], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(leafletInstance);

            const orangeIcon = L.divIcon({
                className: '',
                html: `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="36" viewBox="0 0 28 36">
                    <path d="M14 0C6.3 0 0 6.3 0 14c0 9.8 14 22 14 22s14-12.2 14-22C28 6.3 21.7 0 14 0z" fill="#ff6a00"/>
                    <circle cx="14" cy="14" r="6" fill="white"/>
                </svg>`,
                iconSize:   [28, 36],
                iconAnchor: [14, 36],
            });

            markerInstance = L.marker([lat, lng], {
                icon: orangeIcon,
                draggable: true,
            }).addTo(leafletInstance);

            markerInstance.on('dragend', function () {
                const pos = this.getLatLng();
                latInput.value = pos.lat.toFixed(6);
                lngInput.value = pos.lng.toFixed(6);
            });

        } else {
            leafletInstance.setView([lat, lng], 16);
            markerInstance.setLatLng([lat, lng]);
        }
    }

    /* Auto-init map kalau bengkel sudah punya koordinat */
    if (data.lat && data.lng) {
        /* Tunggu DOM siap baru init Leaflet */
        setTimeout(() => initMap(data.lat, data.lng), 100);
    }

    /* ================================================================
       GEOCODING
    ================================================================ */
    function hideGeoStates() {
        geoLoading.style.display = 'none';
        geoSuccess.style.display = 'none';
        geoError.style.display   = 'none';
    }

    btnGeocode?.addEventListener('click', async function () {
        const query = alamatInput?.value.trim();
        if (!query) { alamatInput?.focus(); return; }

        hideGeoStates();
        geoLoading.style.display = 'flex';
        btnGeocode.disabled = true;

        try {
            const res  = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(query)}`,
                { headers: { 'Accept-Language': 'id' } }
            );
            const result = await res.json();
            if (!result.length) throw new Error('not_found');

            const { lat, lon, display_name } = result[0];
            latInput.value = parseFloat(lat).toFixed(6);
            lngInput.value = parseFloat(lon).toFixed(6);

            const parts = display_name.split(',');
            kotaInput.value = parts.length >= 3
                ? parts[parts.length - 3].trim()
                : parts[0].trim();

            initMap(parseFloat(lat), parseFloat(lon));

            geoLoading.style.display = 'none';
            geoSuccess.style.display = 'flex';
            document.getElementById('geoSuccessText').textContent =
                `Ditemukan: ${parts.slice(0, 2).join(', ')}`;

        } catch {
            geoLoading.style.display = 'none';
            geoError.style.display   = 'flex';
        } finally {
            btnGeocode.disabled = false;
        }
    });

    /* Edit lat/lng manual → geser marker */
    [latInput, lngInput].forEach(input => {
        input?.addEventListener('click', function () {
            this.removeAttribute('readonly');
        });
        input?.addEventListener('change', function () {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            if (!isNaN(lat) && !isNaN(lng) && leafletInstance) {
                leafletInstance.setView([lat, lng], 16);
                markerInstance.setLatLng([lat, lng]);
            }
        });
    });

    /* ================================================================
       FOTO — handle existing + upload baru + hapus
    ================================================================ */

    /* Tombol hapus foto existing */
    btnHapusFoto?.addEventListener('click', function () {
        if (!confirm('Hapus foto bengkel ini?')) return;
        fotoExisting.style.display = 'none';
        uploadArea.style.display   = 'block';
        hapusFotoInput.value       = '1';
    });

    /* Upload foto baru */
    fotoInput?.addEventListener('change', function () {
        handlePhotoFile(this.files[0]);
    });

    window.handleDrop = function (e) {
        e.preventDefault();
        uploadArea?.classList.remove('upload-drag');
        const file = e.dataTransfer.files[0];
        if (!file || !file.type.startsWith('image/')) return;
        const dt = new DataTransfer();
        dt.items.add(file);
        fotoInput.files = dt.files;
        handlePhotoFile(file);
    };

    function handlePhotoFile(file) {
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file melebihi 2 MB. Pilih foto yang lebih kecil.');
            return;
        }
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src              = e.target.result;
            uploadArea.style.display    = 'none';
            if (fotoExisting) fotoExisting.style.display = 'none';
            photoPreview.style.display  = 'block';
            photoFilename.style.display = 'block';
            photoFilename.textContent   =
                `${file.name} · ${(file.size / 1024).toFixed(0)} KB`;
            /* Reset flag hapus kalau upload foto baru */
            if (hapusFotoInput) hapusFotoInput.value = '0';
        };
        reader.readAsDataURL(file);
    }

    /* Batal foto baru → kembali ke existing atau upload area */
    btnRemove?.addEventListener('click', function () {
        fotoInput.value             = '';
        previewImg.src              = '';
        photoPreview.style.display  = 'none';
        photoFilename.style.display = 'none';

        /* Kalau bengkel sudah punya foto & belum dihapus → tampilkan lagi */
        if (data.hasFoto && hapusFotoInput?.value !== '1') {
            fotoExisting.style.display = 'block';
        } else {
            uploadArea.style.display = 'block';
        }
    });

    /* ================================================================
       CLIENT-SIDE VALIDATION
    ================================================================ */
    document.getElementById('formEditBengkel')?.addEventListener('submit', function (e) {
        let valid = true;
        ['nama', 'admin_id', 'alamat', 'kapasitas'].forEach(name => {
            const el = this.elements[name];
            if (!el || !el.value.toString().trim()) {
                el?.classList.add('finput-error');
                valid = false;
            } else {
                el?.classList.remove('finput-error');
            }
        });
        if (!valid) {
            e.preventDefault();
            this.querySelector('.finput-error')
                ?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

})();