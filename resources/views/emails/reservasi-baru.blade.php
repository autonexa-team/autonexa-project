<x-mail::message>
# ✅ Reservasi Berhasil Dibuat

Halo **{{ $reservasi->user->name }}**,

Reservasi service Anda berhasil dibuat.

<x-mail::panel>
Nomor Reservasi:
#{{ str_pad($reservasi->id, 6, '0', STR_PAD_LEFT) }}
</x-mail::panel>

### Detail Reservasi

- **Bengkel:** {{ $reservasi->bengkel->nama }}
- **Tanggal:** {{ \Carbon\Carbon::parse($reservasi->tanggal)->format('d M Y') }}
- **Jam:** {{ $reservasi->waktu }}
- **Kendaraan:** {{ $reservasi->kendaraan }}
- **Plat:** {{ $reservasi->plat }}
- **Status:** {{ $reservasi->status_label }}

### Keluhan

{{ $reservasi->keluhan }}

<x-mail::button :url="route('pelanggan.riwayat')">
Lihat Reservasi
</x-mail::button>

Silakan datang sesuai jadwal yang telah dipilih.

Terima kasih,<br>
AutoNexa
</x-mail::message>