<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        /* WATERMARK */
        .watermark {
            position: fixed;
            top: 40%;
            left: 25%;
            font-size: 80px;
            color: #ccc;
            opacity: 0.1;
            transform: rotate(-30deg);
            z-index: -1;
        }

        .header {
            margin-bottom: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        th {
            background: #f97316;
            color: white;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>

<div class="watermark">AUTONEXA</div>

<div class="header">
    <div class="title">Laporan Reservasi Bengkel</div>
    <div>
        Dicetak oleh: <strong>{{ $userDownload }}</strong><br>
        Waktu: {{ $waktuDownload->format('d M Y H:i') }}
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Pelanggan</th>
            <th>Bengkel</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reservasis as $i => $r)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $r->user->name }}</td>
            <td>{{ $r->bengkel->nama }}</td>
            <td>{{ $r->tanggal }}</td>
            <td>{{ $r->status }}</td>
            <td>Rp {{ number_format($r->total_biaya) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    © {{ date('Y') }} Autonexa - Sistem Reservasi Bengkel
</div>

</body>
</html>

{{-- resources/views/admin-pusat/laporan-pdf.blade.php --}}