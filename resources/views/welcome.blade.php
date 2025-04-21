<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cek Pembayaran Kas</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
        }

        .main-container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .form-box {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            max-width: 500px;
            width: 100%;
            margin: 20px;
        }

        .form-box h2 {
            font-weight: bold;
            color: #333;
        }

        .illustration {
            max-width: 500px;
            margin: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border-radius: 25px;
            padding: 10px 20px;
        }

        .btn-success {
            border-radius: 25px;
            padding: 10px 20px;
        }

        table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        th {
            background-color: #f2f2f2;
        }

        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="container main-container">
        <div class="form-box">
            <h2 class="text-center">🔍 Cek Pembayaran Kas Siswa</h2>
            <form action="{{ route('cek.nis') }}" method="POST" class="mt-4">
                @csrf
                <div class="form-group">
                    <label for="nis">Masukkan NIS:</label>
                    <input type="text" name="nis" id="nis" class="form-control" required placeholder="Contoh: 123456" value="{{ old('nis') }}">
                </div>

                <div class="form-group mt-3">
                    <label for="bulan_id">Pilih Bulan:</label>
                    <select name="bulan_id" id="bulan_id" class="form-control" required>
                        <option value="">-- Pilih Bulan --</option>
                        @foreach ($bulanList as $bulan)
                            <option value="{{ $bulan->id }}" {{ old('bulan_id') == $bulan->id ? 'selected' : '' }}>
                                {{ $bulan->nama_bulan }} {{ $bulan->tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Cek Kas</button>
                    <a href="{{ route('login') }}" class="btn btn-success">Login</a>
                </div>
            </form>

            @if (isset($siswa) && $transaksi)
                <form action="{{ route('cetak.bukti') }}" method="POST" target="_blank" class="mt-3">
                    @csrf
                    <input type="hidden" name="nis" value="{{ $siswa->nis }}">
                    <input type="hidden" name="bulan_id" value="{{ $transaksi->bulan_id }}">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-print"></i> Cetak Bukti Kas
                    </button>
                </form>
            @endif

            @if (session('error'))
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: '{{ session('error') }}'
                    });
                </script>
            @endif

            @if (isset($siswa))
                <div class="mt-4">
                    <h4>📄 Hasil Pengecekan</h4>
                    <table class="table table-bordered mt-2">
                        <tr><th>NIS</th><td>{{ $siswa->nis }}</td></tr>
                        <tr><th>Nama</th><td>{{ $siswa->nama }}</td></tr>
                        @if ($transaksi)
                            <tr><th>Bulan</th><td>{{ $transaksi->bulan->nama_bulan }} {{ $transaksi->bulan->tahun }}</td></tr>
                            <tr><th>Minggu Ke-1</th><td style="color: {{ $transaksi->minggu_1 == 'LUNAS' ? 'green' : 'red' }}">{{ $transaksi->minggu_1 ?? 'BELUM BAYAR' }}</td></tr>
                            <tr><th>Minggu Ke-2</th><td style="color: {{ $transaksi->minggu_2 == 'LUNAS' ? 'green' : 'red' }}">{{ $transaksi->minggu_2 ?? 'BELUM BAYAR' }}</td></tr>
                            <tr><th>Minggu Ke-3</th><td style="color: {{ $transaksi->minggu_3 == 'LUNAS' ? 'green' : 'red' }}">{{ $transaksi->minggu_3 ?? 'BELUM BAYAR' }}</td></tr>
                            <tr><th>Minggu Ke-4</th><td style="color: {{ $transaksi->minggu_4 == 'LUNAS' ? 'green' : 'red' }}">{{ $transaksi->minggu_4 ?? 'BELUM BAYAR' }}</td></tr>
                            <tr><th>Total Bayar</th><td>Rp. {{ number_format($transaksi->jumlah, 0, ',', '.') }}</td></tr>
                        @else
                            <tr><th colspan="2" class="text-center text-danger">Belum Ada Pembayaran Untuk Bulan Ini</th></tr>
                        @endif
                    </table>
                </div>
            @endif
        </div>
    </div>
</body>

</html>
