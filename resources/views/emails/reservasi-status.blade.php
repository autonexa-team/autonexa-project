<x-mail::message>
# 🔧 Update Status Reservasi

Halo **{{ $reservasi->user->name }}**,

@if($reservasi->status == 'dikonfirmasi')
Reservasi Anda di bengkel **{{ $reservasi->bengkel->nama }}** telah berhasil dikonfirmasi.

<x-mail::panel>
✅ DIKONFIRMASI — Silakan datang sesuai jadwal yang telah dipilih.
</x-mail::panel>

@elseif($reservasi->status == 'diproses')
Reservasi Anda saat ini sedang dikerjakan oleh mekanik kami.

<x-mail::panel>
🔧 SEDANG DIPROSES — Mohon tunggu hingga proses service selesai.
</x-mail::panel>

@elseif($reservasi->status == 'selesai')
🎉 Kendaraan Anda telah selesai diservice. Silakan datang ke bengkel untuk mengambil kendaraan Anda.

<x-mail::panel>
✅ SERVICE SELESAI
</x-mail::panel>

@elseif($reservasi->status == 'dibatalkan')
<x-mail::panel>
❌ RESERVASI DIBATALKAN — Mohon hubungi pihak bengkel untuk informasi lebih lanjut.
</x-mail::panel>
@endif

---

### 📋 Detail Reservasi

| Info | Detail |
|------|--------|
| Bengkel | {{ $reservasi->bengkel->nama }} |
| Tanggal | {{ \Carbon\Carbon::parse($reservasi->tanggal)->format('d M Y') }} |
| Jam | {{ $reservasi->waktu }} WIB |
| Kendaraan | {{ $reservasi->kendaraan ?? '-' }} |
| Plat Nomor | {{ $reservasi->plat ?? '-' }} |

---

### 🔧 Layanan & Biaya

**Layanan Utama:** {{ $reservasi->layanan->nama ?? '-' }}

@php
    $hargaLayanan = $reservasi->layanan->harga ?? 0;
    $spareparts   = $reservasi->spareparts ?? collect(); 
    $totalSp      = $spareparts->sum(fn($s) => $s->qty * $s->harga);
    $grandTotal   = $hargaLayanan + $totalSp;
@endphp

@if($spareparts->count() > 0)
**Sparepart yang Digunakan:**

| Nama Sparepart | Qty | Harga Satuan | Subtotal |
|----------------|-----|-------------|---------|
@foreach($spareparts as $sp)
| {{ $sp->nama }} | {{ $sp->qty }} | Rp {{ number_format($sp->harga, 0, ',', '.') }} | Rp {{ number_format($sp->qty * $sp->harga, 0, ',', '.') }} |
@endforeach

@endif

<x-mail::panel>
💰 **Rincian Biaya:**

- Layanan Utama: Rp {{ number_format($hargaLayanan, 0, ',', '.') }}
- Total Sparepart: Rp {{ number_format($totalSp, 0, ',', '.') }}

**Total Tagihan: Rp {{ number_format($grandTotal, 0, ',', '.') }}**
</x-mail::panel>

@if($reservasi->hasil_service && $reservasi->status == 'selesai')
---

### 📝 Catatan dari Bengkel

{{ $reservasi->hasil_service }}

@endif

---

<x-mail::button :url="route('pelanggan.riwayat')">
Lihat Reservasi Saya
</x-mail::button>

Terima kasih telah menggunakan AutoNexa 🏍️

Salam,
**AutoNexa System**
</x-mail::message>