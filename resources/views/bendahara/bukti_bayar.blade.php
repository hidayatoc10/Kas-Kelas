<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran Kas</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <style>
        @media print {
            .no-print { display: none; }
        }
        .bukti-box {
            border: 1px solid #333;
            padding: 20px;
            margin-top: 30px;
        }
        .text-header {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 20px;
        }
        .ref {
            text-align: center;
            font-size: 13px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="bukti-box">
            <div class="text-header">
                Bukti Pembayaran Kas Siswa
            </div>
            <hr>
            <table class="table table-bordered">
                <tr>
                    <th>NIS</th>
                    <td>{{ $siswa->nis }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $siswa->nama }}</td>
                </tr>
                <tr>
                    <th>Bulan</th>
                    <td>{{ $transaksi->bulan->nama_bulan }} {{ $transaksi->bulan->tahun }}</td>
                </tr>
                <tr>
                    <th>Status Minggu</th>
                    <td>
                        <ul style="list-style: none; padding-left: 0;">
                            @for ($i = 1; $i <= 4; $i++)
                                <li>
                                    Minggu ke-{{ $i }}:
                                    <span style="color: {{ $transaksi->{'minggu_' . $i} == 'LUNAS' ? 'green' : 'red' }}">
                                        {{ $transaksi->{'minggu_' . $i} }}
                                    </span>
                                </li>
                            @endfor
                        </ul>
                    </td>
                </tr>
                <tr>
                    <th>Total Bayar</th>
                    <td><strong>Rp. {{ number_format($transaksi->jumlah, 0, ',', '.') }}</strong></td>
                </tr>
            </table>

            <div class="ref">
                No. Referensi: {{ $ref_number }}
            </div>
        </div>
    </div>
</body>
</html>
