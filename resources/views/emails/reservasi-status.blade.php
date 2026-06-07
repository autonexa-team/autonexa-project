<x-mail::message>
# 🔧 Update Status Reservasi

Halo **{{ $reservasi->user->name }}**,

@if($reservasi->status == 'dikonfirmasi')

Reservasi Anda di bengkel:

**{{ $reservasi->bengkel->nama }}**

telah berhasil dikonfirmasi.

<x-mail::panel>
✅ DIKONFIRMASI
</x-mail::panel>

Silakan datang sesuai jadwal yang telah dipilih.

@elseif($reservasi->status == 'diproses')

Reservasi Anda saat ini sedang dikerjakan oleh mekanik.

<x-mail::panel>
🔧 SEDANG DIPROSES
</x-mail::panel>

Mohon tunggu hingga proses service selesai.

@elseif($reservasi->status == 'selesai')

🎉 Kendaraan Anda telah selesai diservice.

<x-mail::panel>
✅ SERVICE SELESAI
</x-mail::panel>

Silakan datang ke bengkel untuk mengambil kendaraan Anda.

@if($reservasi->hasil_service)
### Hasil Service

{{ $reservasi->hasil_service }}
@endif

@elseif($reservasi->status == 'dibatalkan')

<x-mail::panel>
❌ RESERVASI DIBATALKAN
</x-mail::panel>

Mohon hubungi pihak bengkel untuk informasi lebih lanjut.

@endif

---

### Detail Reservasi

- Bengkel: {{ $reservasi->bengkel->nama }}
- Tanggal: {{ \Carbon\Carbon::parse($reservasi->tanggal)->format('d M Y') }}
- Jam: {{ $reservasi->waktu }}
- Kendaraan: {{ $reservasi->kendaraan }}
- Plat Nomor: {{ $reservasi->plat }}

<x-mail::button :url="route('pelanggan.riwayat')">
Lihat Reservasi Saya
</x-mail::button>

Terima kasih telah menggunakan AutoNexa 🏍️

Salam,<br>
AutoNexa System
</x-mail::message>