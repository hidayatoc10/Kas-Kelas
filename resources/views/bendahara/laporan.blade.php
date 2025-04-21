@extends('../layouts/sidebar_bendahara')

@section('container')
    <section class="content-header">
        <h1>
            Laporan Pemasukan
            <small>Bulanan</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-home"></i> <b>Dashboard</b>
                </a>
            </li>
            <li class="active">Laporan Pemasukan</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pilih Bulan Pembayaran</h3>
                    </div>
                    <form action="{{ route('laporan.pemasukan.tampilkan') }}" method="POST">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="bulan_id">Bulan Pembayaran</label>
                                <select name="bulan_id" id="bulan_id" class="form-control" required>
                                    <option value="">-- Pilih Bulan --</option>
                                    @foreach ($bulans as $bulan)
                                        <option value="{{ $bulan->id }}"
                                            {{ isset($bulan_terpilih) && $bulan_terpilih->id == $bulan->id ? 'selected' : '' }}>
                                            {{ $bulan->nama_bulan }} | {{ $bulan->tahun }} | Rp.
                                            {{ number_format($bulan->pembayaran_seminggu, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tampilkan</button>
                        </div>
                    </form>
                </div>

                @if (isset($transaksis))
                    <div class="alert alert-info">
                        <h4><i class="fa fa-info-circle"></i> Info Laporan</h4>
                        <p><strong>Bulan:</strong> {{ $bulan_terpilih->nama_bulan }}</p>
                        <p><strong>Tahun:</strong> {{ $bulan_terpilih->tahun }}</p>
                        <p><strong>Pembayaran per Minggu:</strong> Rp.
                            {{ number_format($bulan_terpilih->pembayaran_seminggu, 0, ',', '.') }}</p>
                    </div>
                    <div class="mb-3">
                        <a href="javascript:window.print()" class="btn btn-success"><i class="fa fa-print"></i> Cetak
                            Tabel</a>
                    </div>
                    <div class="box box-primary" id="print-area">
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Minggu Ke-1</th>
                                        <th>Minggu Ke-2</th>
                                        <th>Minggu Ke-3</th>
                                        <th>Minggu Ke-4</th>
                                        <th>Total (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                        $total_semua = 0;
                                    @endphp
                                    @foreach ($transaksis as $transaksi)
                                        @php
                                            $jumlah_minggu = 0;
                                            for ($i = 1; $i <= 4; $i++) {
                                                if ($transaksi->{'minggu_' . $i} == 'LUNAS') {
                                                    $jumlah_minggu += $bulan_terpilih->pembayaran_seminggu;
                                                }
                                            }
                                            $total_semua += $jumlah_minggu;
                                        @endphp
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $transaksi->siswa->nama }}</td>
                                            @for ($i = 1; $i <= 4; $i++)
                                                <td
                                                    style="color: {{ $transaksi->{'minggu_' . $i} == 'LUNAS' ? 'green' : 'red' }}">
                                                    {{ $transaksi->{'minggu_' . $i} == 'LUNAS' ? number_format($bulan_terpilih->pembayaran_seminggu, 0, ',', '.') : 'BELUM BAYAR' }}
                                                </td>
                                            @endfor
                                            <td><strong>Rp. {{ number_format($jumlah_minggu, 0, ',', '.') }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-right">Total Keseluruhan</th>
                                        <th>Rp. {{ number_format($total_semua, 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        Silakan pilih Bulan terlebih dahulu untuk menampilkan laporan pemasukan.
                    </div>
                @endisset
        </div>
    </div>
</section>
@endsection

@section('scripts')
@parent
<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            confirmButtonText: 'Ok',
        });
    @endif
</script>
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #print-area,
        #print-area * {
            visibility: visible;
        }

        #print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>
@endsection
