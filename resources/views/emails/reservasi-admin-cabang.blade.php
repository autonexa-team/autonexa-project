<x-mail::message>
# 📢 Reservasi Baru Masuk

Ada pelanggan yang melakukan reservasi service.

### Informasi Pelanggan

- Nama: {{ $reservasi->user->name }}
- Email: {{ $reservasi->user->email }}

### Detail Reservasi

- Tanggal: {{ $reservasi->tanggal }}
- Jam: {{ $reservasi->waktu }}
- Kendaraan: {{ $reservasi->kendaraan }}
- Plat: {{ $reservasi->plat }}

### Keluhan

{{ $reservasi->keluhan }}

<x-mail::panel>
Segera lakukan konfirmasi reservasi melalui dashboard AutoNexa.
</x-mail::panel>

Terima kasih,<br>
AutoNexa System
</x-mail::message>