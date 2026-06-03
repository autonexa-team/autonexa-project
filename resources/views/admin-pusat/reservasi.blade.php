{{-- resources/views/admin-pusat/reservasi.blade.php --}}
@extends('layout.admin')
@section('title', 'Manajemen Reservasi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-reservasi.css') }}">
@endpush

@section('content')

@php
$statusMap = [
    'pending' => [
        'label' => 'Menunggu',
        'class' => 'badge-pending'
    ],

    'dikonfirmasi' => [
        'label' => 'Dikonfirmasi',
        'class' => 'badge-confirmed'
    ],

    'diproses' => [
        'label' => 'Dikerjakan',
        'class' => 'badge-progress'
    ],

    'selesai' => [
        'label' => 'Selesai',
        'class' => 'badge-done'
    ],

    'dibatalkan' => [
        'label' => 'Dibatalkan',
        'class' => 'badge-cancel'
    ],
];

$total = $reservasi->total();

$pending = $reservasi
    ->where('status','pending')
    ->count();

$confirmed = $reservasi
    ->where('status','dikonfirmasi')
    ->count();

$inProgress = $reservasi
    ->where('status','diproses')
    ->count();

$done = $reservasi
    ->where('status','selesai')
    ->count();

$cancelled = $reservasi
    ->where('status','dibatalkan')
    ->count();
@endphp

{{-- ── HEADER ──────────────────────────────────────────────────── --}}
<div class="rv-header">
    <div class="rv-header-left">
        <h1 class="rv-title">
            @if($bengkel) Reservasi — {{ $bengkel->nama }}
            @else Manajemen Reservasi
            @endif
        </h1>
        <p class="rv-sub">
            @if($bengkel) Daftar reservasi untuk bengkel {{ $bengkel->nama }}
            @else Seluruh data reservasi dari semua jaringan bengkel
            @endif
        </p>
    </div>
    <div class="rv-header-actions">
        @if($bengkel)
            <a href="{{ route('admin-pusat.bengkel.show', $bengkel->id) }}" class="rv-btn-back">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        @endif
    </div>
</div>

{{-- ── STAT STRIP ───────────────────────────────────────────────── --}}
<div class="rv-stat-strip">
    <div class="rv-stat-item rv-stat-all">
        <div class="rv-stat-icon si-blue"><i class="bi bi-calendar3"></i></div>
        <div>
            <div class="rv-stat-num">{{ $total }}</div>
            <div class="rv-stat-lbl">Total</div>
        </div>
    </div>
    <div class="rv-stat-div"></div>
    <div class="rv-stat-item">
        <div class="rv-stat-icon si-amber"><i class="bi bi-hourglass-split"></i></div>
        <div>
            <div class="rv-stat-num">{{ $pending }}</div>
            <div class="rv-stat-lbl">Menunggu</div>
        </div>
    </div>
    <div class="rv-stat-div"></div>
    <div class="rv-stat-item">
        <div class="rv-stat-icon si-indigo"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="rv-stat-num">{{ $confirmed }}</div>
            <div class="rv-stat-lbl">Dikonfirmasi</div>
        </div>
    </div>
    <div class="rv-stat-div"></div>
    <div class="rv-stat-item">
        <div class="rv-stat-icon si-orange"><i class="bi bi-wrench-adjustable"></i></div>
        <div>
            <div class="rv-stat-num">{{ $inProgress }}</div>
            <div class="rv-stat-lbl">Dikerjakan</div>
        </div>
    </div>
    <div class="rv-stat-div"></div>
    <div class="rv-stat-item">
        <div class="rv-stat-icon si-green"><i class="bi bi-patch-check"></i></div>
        <div>
            <div class="rv-stat-num">{{ $done }}</div>
            <div class="rv-stat-lbl">Selesai</div>
        </div>
    </div>
    <div class="rv-stat-div"></div>
    <div class="rv-stat-item">
        <div class="rv-stat-icon si-red"><i class="bi bi-x-circle"></i></div>
        <div>
            <div class="rv-stat-num">{{ $cancelled }}</div>
            <div class="rv-stat-lbl">Dibatalkan</div>
        </div>
    </div>
</div>

{{-- ── TABLE CARD ───────────────────────────────────────────────── --}}
<div class="rv-card">

    {{-- Toolbar --}}
    <div class="rv-toolbar">
        <div class="rv-search-wrap">
            <i class="bi bi-search rv-search-icon"></i>
            <input
                type="text"
                id="rvSearch"
                class="rv-search"
                placeholder="Cari pelanggan, bengkel, atau layanan..."
                autocomplete="off"
            >
            <button class="rv-search-clear" id="rvClearSearch" style="display:none;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="rv-toolbar-right">
            {{-- Filter status --}}
            <select class="rv-select" id="rvFilterStatus">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu</option>
                <option value="dikonfirmasi">Dikonfirmasi</option>
                <option value="diproses">Dikerjakan</option>
                <option value="selesai">Selesai</option>
                <option value="dibatalkan">Dibatalkan</option>
            </select>

            <span class="rv-count-label" id="rvCount">
                Menampilkan <strong>{{ $total }}</strong> reservasi
            </span>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="rv-table" id="rvTable">
            <thead>
                <tr>
                    <th style="width:56px;">ID</th>
                    <th>Pelanggan</th>
                    <th>Bengkel</th>
                    <th>Keluhan / Layanan</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th style="width:80px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="rvBody">
                @forelse($reservasi as $res)
                @php
                    [$stLabel, $stClass] = array_values($statusMap[$res->status] ?? ['Pending','badge-pending']);
                    $initials = strtoupper(substr($res->user->name ?? 'U', 0, 1))
                              . strtoupper(substr(explode(' ', $res->user->name ?? 'U ')[1] ?? '', 0, 1));
                @endphp
                <tr class="rv-row"
                    data-search="{{ strtolower($res->user->name . ' ' . $res->bengkel->nama . ' ' . $res->keluhan) }}"
                    data-status="{{ $res->status }}">

                    {{-- ID --}}
                    <td>
                        <span class="rv-id">#{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </td>

                    {{-- Pelanggan --}}
                    <td>
                        <div class="rv-td-user">
                            <div class="rv-avatar">{{ $initials }}</div>
                            <span class="rv-td-name">{{ $res->user->name ?? '-' }}</span>
                        </div>
                    </td>

                    {{-- Bengkel --}}
                    <td>
                        <div class="rv-td-bengkel">
                            <i class="bi bi-shop rv-bengkel-icon"></i>
                            {{ $res->bengkel->nama ?? '-' }}
                        </div>
                    </td>

                    {{-- Keluhan --}}
                    <td>
                        <span class="rv-td-keluhan">{{ $res->keluhan ?? '-' }}</span>
                    </td>

                    {{-- Tanggal --}}
                    <td class="rv-td-date">
                        {{ \Carbon\Carbon::parse($res->tanggal)->translatedFormat('d M Y') }}
                    </td>

                    {{-- Waktu --}}
                    <td>
                        <span class="rv-td-waktu">
                            <i class="bi bi-clock"></i>
                            {{ $res->waktu ?? '-' }}
                        </span>
                    </td>

                    {{-- Total --}}
                    <td>
                        @if(($res->total_biaya ?? 0) > 0)
                            <span class="rv-td-nominal">
                                Rp {{ number_format($res->total_biaya, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="rv-td-muted">—</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td>
                        <span class="status-badge {{ $stClass }}">{{ $stLabel }}</span>
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div class="rv-td-aksi">
                            <a href="{{ route('admin-pusat.reservasi.show', $res->id) }}"
                               class="rv-btn-aksi rv-btn-view" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="rv-empty-cell">
                        <div class="rv-empty">
                            <div class="rv-empty-icon"><i class="bi bi-calendar-x"></i></div>
                            <div class="rv-empty-title">Belum ada reservasi</div>
                            <div class="rv-empty-sub">Data reservasi akan muncul di sini</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- JS empty state --}}
        <div class="rv-empty" id="rvEmptyState" style="display:none; padding:48px 0;">
            <div class="rv-empty-icon"><i class="bi bi-search"></i></div>
            <div class="rv-empty-title">Tidak ditemukan</div>
            <div class="rv-empty-sub">Coba ubah kata kunci atau filter</div>
        </div>
    </div>

    {{-- Pagination --}}
    @if(method_exists($reservasi, 'links'))
    <div class="rv-pagination">
        {{ $reservasi->links() }}
    </div>
    @endif

</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/admin-reservasi.js') }}"></script>
@endpush