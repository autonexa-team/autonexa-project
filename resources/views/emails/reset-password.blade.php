<x-mail::message>
# Reset Password AutoNexa

Halo {{ $userName }},

Kami menerima permintaan reset password untuk akun:

<strong>{{ $userEmail }}</strong>

<x-mail::button :url="$resetUrl">
Reset Password
</x-mail::button>

Link reset password berlaku selama {{ $expireMin }} menit.

Jika kamu tidak meminta reset password, abaikan email ini.

Terima kasih,<br>
AutoNexa
</x-mail::message>