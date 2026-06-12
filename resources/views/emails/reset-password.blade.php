<x-mail::message>

<div style="text-align:center;">

    # Reset Password AutoNexa

    Halo **{{ $userName }}**,

    Kami menerima permintaan reset password untuk akun:

    <strong>{{ $userEmail }}</strong>

<br>

<x-mail::button :url="$resetUrl">
    Reset Password
</x-mail::button>

<br>

    Link reset password berlaku selama
    <strong>{{ $expireMin }} menit</strong>.

<br>

    Jika kamu tidak meminta reset password,
    abaikan email ini.

<br><br>

Terima kasih,<br>
<strong>AutoNexa</strong>

</div>

</x-mail::message>