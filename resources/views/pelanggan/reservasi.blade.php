{{-- views/pelanggan/reservasi.blade.php --}}

@extends('layout.app')
@section('title', 'Booking Service')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/reservasi.css') }}">
@endpush

@section('content')

{{-- ===== PAGE HERO HEADER ===== --}}
<div class="reservasi-hero">
    <div class="hero-text">
        <div class="hero-eyebrow">Booking Service</div>
        <h1 class="hero-title">Reservasi <span>Sekarang!</span></h1>
        <p class="hero-subtitle">Pilih bengkel terpercaya dan tentukan jadwal servis kendaraanmu</p>
    </div>
    <a href="{{ route('pelanggan.riwayat') }}" class="btn-riwayat">
        <span class="btn-riwayat-dot"></span>
        Reservasi Saya
        {{-- Opsional: tampilkan jumlah reservasi aktif --}}
        @if(isset($totalReservasi) && $totalReservasi > 0)
            <span class="btn-riwayat-badge">{{ $totalReservasi }}</span>
        @endif
    </a>
</div>

<div class="reservasi-wrapper">
    <div class="reservasi-left">
        {{-- ===== BLOK 1: PILIH BENGKEL ===== --}}
        <div class="reservasi-block" id="blok-bengkel">
            <div class="block-header">
                <span class="block-number">1</span>
                <h5 class="block-title">Pilih Bengkel</h5>
            </div>
            <div class="block-body">

                {{-- Search + Tombol Terdekat --}}
                <div class="search-row">
                    <div class="search-input-wrap">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" id="searchBengkel" class="search-input" placeholder="Cari bengkel...">
                    </div>
                    <button type="button" class="btn-terdekat" id="btnTerdekat">
                        <i class="bi bi-geo-alt-fill"></i>
                        Bengkel Terdekat
                    </button>
                </div>

                {{-- Preview Bengkel Terpilih --}}
                <div class="bengkel-preview" id="bengkelPreview" style="display: none;">
                    <img id="previewFoto" src="" alt="" class="preview-foto">
                    <div class="preview-info">
                        <div class="preview-nama" id="previewNama"></div>
                        <div class="preview-alamat" id="previewAlamat"></div>
                        <div class="preview-rating" id="previewRating"></div>
                    </div>
                    <button type="button" class="btn-ganti" id="btnGanti">
                        <i class="bi bi-arrow-repeat"></i> Ganti
                    </button>
                </div>

                {{-- Daftar Bengkel --}}
                <div class="bengkel-list" id="bengkelList">
                    @foreach($bengkels as $bengkel)
                    <div class="bengkel-card"
                        data-id="{{ $bengkel->id }}"
                        data-nama="{{ $bengkel->nama }}"
                        data-alamat="{{ $bengkel->alamat }}"
                        data-foto="{{ $bengkel->foto ? asset('storage/'.$bengkel->foto) : asset('img/bengkel-default.jpg') }}"
                        data-rating="{{ $bengkel->reviews_avg_rating ?? 0 }}">
                        <img src="{{ $bengkel->foto ? asset('storage/'.$bengkel->foto) : asset('img/bengkel-default.jpg') }}"
                            class="bengkel-thumb" alt="{{ $bengkel->nama }}">
                        <div class="bengkel-info">
                            <div class="bengkel-nama">{{ $bengkel->nama }}</div>
                            <div class="bengkel-alamat">{{ $bengkel->alamat }}</div>
                            <div class="bengkel-stars mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= ($bengkel->reviews_avg_rating ?? 0) ? '-fill star-on' : ' star-off' }}"></i>
                                @endfor
                                <small class="rating-val">({{ number_format($bengkel->reviews_avg_rating ?? 0, 1) }})</small>
                            </div>
                        </div>
                        <i class="bi bi-check-circle-fill check-icon"></i>
                    </div>
                    @endforeach
                </div>

                <input type="hidden" name="bengkel_id" id="bengkelId">
                @error('bengkel_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- ===== BLOK 2: FORM RESERVASI ===== --}}
        <div class="reservasi-block" id="blok-form">
            <div class="block-header">
                <span class="block-number">2</span>
                <h5 class="block-title">Detail Reservasi</h5>
            </div>
            <div class="block-body">
                <form action="{{ route('pelanggan.booking.store') }}" method="POST" id="formBooking">
                    @csrf
                    <input type="hidden" name="bengkel_id" id="bengkelIdForm">

                    <div class="form-row-2">
                        <div class="form-field">
                            <label class="field-label">
                                <i class="bi bi-calendar3"></i> Tanggal
                            </label>
                            <input type="date" name="tanggal" class="field-input"
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('tanggal') }}" required>
                            @error('tanggal')<div class="field-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-field">
                            <label class="field-label">
                                <i class="bi bi-clock"></i> Waktu
                            </label>
                            <select name="waktu" class="field-input" required>
                                <option value="">-- Pilih Jam --</option>
                                @foreach(['08:00','09:00','10:00','11:00','13:00','14:00','15:00','16:00'] as $jam)
                                    <option value="{{ $jam }}" {{ old('waktu') == $jam ? 'selected' : '' }}>
                                        {{ $jam }} WIB
                                    </option>
                                @endforeach
                            </select>
                            @error('waktu')<div class="field-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-field">
                        <label class="field-label">
                            <i class="bi bi-chat-left-text"></i> Keluhan Kendaraan
                        </label>
                        <textarea name="keluhan" class="field-input field-textarea"
                                rows="4"
                                placeholder="Ceritakan keluhan kendaraan Anda secara detail..." required>{{ old('keluhan') }}</textarea>
                        @error('keluhan')<div class="field-error">{{ $message }}</div>@enderror
                    </div>    

                    <div class="form-field">
                        <label class="field-label">
                            <i class="bi bi-wrench"></i> Pilih Layanan
                        </label>

                        <div class="layanan-list">
                            <label class="checkbox-item">
                                <input type="checkbox" name="layanan[]" value="ganti oli">
                                Ganti Oli
                            </label>

                            <label class="checkbox-item">
                                <input type="checkbox" name="layanan[]" value="tune up">
                                Tune Up
                            </label>

                            <label class="checkbox-item">
                                <input type="checkbox" name="layanan[]" value="ban">
                                Ganti Ban
                            </label>

                            <label class="checkbox-item">
                                <input type="checkbox" name="layanan[]" value="kelistrikan">
                                Kelistrikan
                            </label>

                            <label class="checkbox-item">
                                <input type="checkbox" name="layanan[]" value="injeksi">
                                Servis Injeksi
                            </label>
                        </div>
                    </div>                    

                    <button type="submit" class="btn-reservasi" id="btnReservasi">
                        <i class="bi bi-check2-circle"></i>
                        Reservasi Sekarang
                    </button>
                </form>
            </div>
        </div>        
    </div>

    <div class="reservasi-right">
        {{-- ===== BLOK 3: RINGKASAN ===== --}}
        <div class="reservasi-block" id="blok-ringkasan">
            <div class="block-header">
                <span class="block-number">3</span>
                <h5 class="block-title">Ringkasan Reservasi</h5>
            </div>
            <div class="block-body">
                <div id="ringkasanEmpty" class="ringkasan-empty">
                    <i class="bi bi-clipboard2-pulse"></i>
                    <p>Isi form di atas untuk melihat ringkasan</p>
                </div>
                <div id="ringkasanDetail" style="display: none;">
                    <div class="ringkasan-row">
                        <span class="ringkasan-label"><i class="bi bi-shop"></i> Bengkel</span>
                        <span class="ringkasan-val" id="rBengkel">—</span>
                    </div>
                    <div class="ringkasan-row">
                        <span class="ringkasan-label"><i class="bi bi-calendar3"></i> Tanggal</span>
                        <span class="ringkasan-val" id="rTanggal">—</span>
                    </div>
                    <div class="ringkasan-row">
                        <span class="ringkasan-label"><i class="bi bi-clock"></i> Waktu</span>
                        <span class="ringkasan-val" id="rWaktu">—</span>
                    </div>
                    <div class="ringkasan-row">
                        <span class="ringkasan-label"><i class="bi bi-info-circle"></i> Status</span>
                        <span class="ringkasan-val">
                            <span class="status-badge status-pending">Menunggu Konfirmasi</span>
                        </span>
                    </div>
                </div>

                {{-- Mini Map --}}
                <div id="map" class="mini-map"></div>
            </div>
        </div>
    </div>

</div>
@endsection

{{-- Scriptnya harus dipindahin --}}

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/booking.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bengkelCards = document.querySelectorAll('.bengkel-card');
    const bengkelIdInput = document.getElementById('bengkelId');
    const bengkelIdForm  = document.getElementById('bengkelIdForm');
    const bengkelList    = document.getElementById('bengkelList');
    const bengkelPreview = document.getElementById('bengkelPreview');
    const btnGanti       = document.getElementById('btnGanti');
    const searchInput    = document.getElementById('searchBengkel');

    // Ringkasan live update
    const rBengkel  = document.getElementById('rBengkel');
    const rTanggal  = document.getElementById('rTanggal');
    const rWaktu    = document.getElementById('rWaktu');
    const ringkasanEmpty  = document.getElementById('ringkasanEmpty');
    const ringkasanDetail = document.getElementById('ringkasanDetail');

    function updateRingkasan() {
        const hasBengkel = bengkelIdInput.value;
        const tanggalEl  = document.querySelector('input[name="tanggal"]');
        const waktuEl    = document.querySelector('select[name="waktu"]');

        if (hasBengkel) {
            ringkasanEmpty.style.display  = 'none';
            ringkasanDetail.style.display = 'block';

            rBengkel.textContent = document.getElementById('previewNama').textContent || '—';
            rTanggal.textContent = tanggalEl.value
                ? new Date(tanggalEl.value).toLocaleDateString('id-ID', {weekday:'long', day:'numeric', month:'long', year:'numeric'})
                : '—';
            rWaktu.textContent = waktuEl.value ? waktuEl.value + ' WIB' : '—';
        } else {
            ringkasanEmpty.style.display  = 'flex';
            ringkasanDetail.style.display = 'none';
        }
    }

    // Pilih bengkel
    bengkelCards.forEach(card => {
        card.addEventListener('click', function () {
            bengkelCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            const id     = this.dataset.id;
            const nama   = this.dataset.nama;
            const alamat = this.dataset.alamat;
            const foto   = this.dataset.foto;
            const rating = parseFloat(this.dataset.rating) || 0;

            bengkelIdInput.value = id;
            bengkelIdForm.value  = id;

            // Tampilkan preview
            document.getElementById('previewFoto').src    = foto;
            document.getElementById('previewNama').textContent   = nama;
            document.getElementById('previewAlamat').textContent = alamat;

            let stars = '';
            for (let i = 1; i <= 5; i++) {
                stars += `<i class="bi bi-star${i <= rating ? '-fill star-on' : ' star-off'}"></i>`;
            }
            document.getElementById('previewRating').innerHTML = stars + ` <small>(${rating.toFixed(1)})</small>`;

            bengkelList.style.display    = 'none';
            bengkelPreview.style.display = 'flex';
            updateRingkasan();
        });
    });

    // Ganti bengkel
    btnGanti.addEventListener('click', function () {
        bengkelList.style.display    = 'block';
        bengkelPreview.style.display = 'none';
        bengkelIdInput.value         = '';
        bengkelIdForm.value          = '';
        bengkelCards.forEach(c => c.classList.remove('active'));
        updateRingkasan();
    });

    // Search
    searchInput.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        bengkelCards.forEach(card => {
            const nama   = card.dataset.nama.toLowerCase();
            const alamat = card.dataset.alamat.toLowerCase();
            card.style.display = (nama.includes(q) || alamat.includes(q)) ? 'flex' : 'none';
        });
    });

    // Live update ringkasan saat tanggal/waktu berubah
    document.querySelector('input[name="tanggal"]').addEventListener('change', updateRingkasan);
    document.querySelector('select[name="waktu"]').addEventListener('change', updateRingkasan);
});
</script>
@endpush