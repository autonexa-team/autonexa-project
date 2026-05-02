{{-- resources/views/mekanik/service/detail.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Detail Service')
@section('page-title', 'Detail Service')

@section('content')
<div class="row g-4">

    {{-- Info Reservasi --}}
    <div class="col-lg-4">
        <div class="card-dash">
            <div class="card-dash-header">
                <h6><i class="bi bi-info-circle me-2"></i>Info Kendaraan</h6>
            </div>
            <div class="card-body">
                <dl class="detail-list">
                    <dt>Pelanggan</dt>
                    <dd>{{ $reservasi->user->name }}</dd>
                    <dt>Telepon</dt>
                    <dd>{{ $reservasi->user->phone ?? '-' }}</dd>
                    <dt>Keluhan</dt>
                    <dd>{{ $reservasi->keluhan }}</dd>
                    <dt>Tanggal</dt>
                    <dd>{{ $reservasi->tanggal->format('d M Y') }}</dd>
                    <dt>Waktu</dt>
                    <dd>{{ $reservasi->waktu }} WIB</dd>
                    <dt>Status</dt>
                    <dd>@include('partials.status-badge', ['status' => $reservasi->status])</dd>
                </dl>
            </div>
        </div>
    </div>

    {{-- Form Hasil Service & Sparepart --}}
    <div class="col-lg-8">
        <div class="card-dash">
            <div class="card-dash-header">
                <h6><i class="bi bi-tools me-2"></i>Input Hasil Service & Sparepart</h6>
            </div>
            <div class="card-body">

                {{-- Tambah Sparepart --}}
                <h6 class="mb-3">Sparepart Digunakan</h6>
                <form method="POST" action="{{ route('mekanik.sparepart.store', $reservasi->id) }}" class="mb-4" id="formSparepart">
                    @csrf
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Nama Sparepart</label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama sparepart" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Qty</label>
                            <input type="number" name="qty" class="form-control" min="1" value="1" id="inputQty">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Harga / Pcs</label>
                            <input type="number" name="harga" class="form-control" placeholder="0" id="inputHarga">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Total</label>
                            <input type="text" class="form-control bg-light" id="previewTotal" readonly value="Rp 0">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>Tambah Sparepart
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Tabel Sparepart --}}
                @if($reservasi->spareparts->count())
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr><th>Nama</th><th>Qty</th><th>Harga</th><th>Total</th><th></th></tr>
                        </thead>
                        <tbody>
                            @foreach($reservasi->spareparts as $sp)
                            <tr>
                                <td>{{ $sp->nama }}</td>
                                <td>{{ $sp->qty }}</td>
                                <td>Rp {{ number_format($sp->harga, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($sp->total, 0, ',', '.') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('mekanik.sparepart.destroy', $sp->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold table-light">
                                <td colspan="3" class="text-end">Total Keseluruhan</td>
                                <td colspan="2">Rp {{ number_format($reservasi->spareparts->sum('total'), 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif

                {{-- Form Selesai Service --}}
                <form method="POST" action="{{ route('mekanik.service.selesai', $reservasi->id) }}" id="formSelesai">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Hasil Service / Catatan Mekanik</label>
                        <textarea name="hasil_service" class="form-control" rows="4"
                                  placeholder="Tuliskan hasil pemeriksaan dan perbaikan yang telah dilakukan..."
                                  required>{{ old('hasil_service', $reservasi->hasil_service) }}</textarea>
                        @error('hasil_service')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Total Biaya (Rp)</label>
                        <input type="number" name="total_biaya" class="form-control"
                               value="{{ old('total_biaya', $reservasi->spareparts->sum('total')) }}"
                               placeholder="0" required>
                    </div>

                    @if($reservasi->status === 'diproses')
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-secondary"
                                onclick="document.getElementById('formSelesai').submit()">
                            <i class="bi bi-save me-1"></i>Simpan Draft
                        </button>
                        <button type="submit" name="action" value="tandai_selesai"
                                class="btn btn-success"
                                onclick="return confirm('Tandai service ini sebagai selesai?')">
                            <i class="bi bi-check2-all me-2"></i>Tandai Sudah Selesai
                        </button>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function hitungTotal() {
    const qty = parseInt(document.getElementById('inputQty').value) || 0;
    const harga = parseInt(document.getElementById('inputHarga').value) || 0;
    const total = qty * harga;
    document.getElementById('previewTotal').value = 'Rp ' + total.toLocaleString('id-ID');
}
document.getElementById('inputQty').addEventListener('input', hitungTotal);
document.getElementById('inputHarga').addEventListener('input', hitungTotal);
</script>
@endpush