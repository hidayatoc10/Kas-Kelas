@extends('../layouts/sidebar_bendahara')

@section('container')
    <section class="content-header">
        <h1>
            Laporan Pengeluaran
            <small>Rekap Bulanan</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-home"></i> <b>Dashboard</b>
                </a>
            </li>
            <li class="active">Laporan Pengeluaran</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Filter Tanggal</h3>
                    </div>
                    <form action="{{ route('laporan.pengeluaran.tampilkan') }}" method="POST">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="tanggal_awal">Dari Tanggal:</label>
                                <input type="date" name="tanggal_awal" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_akhir">Sampai Tanggal:</label>
                                <input type="date" name="tanggal_akhir" class="form-control" required>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Tampilkan
                            </button>
                        </div>
                    </form>
                </div>

                @isset($transaksis)
                    <div class="alert alert-info">
                        <h4><i class="fa fa-info-circle"></i> Info Laporan</h4>
                        <p><strong>Periode:</strong> {{ date('d M Y', strtotime($tanggal_awal)) }} s/d
                            {{ date('d M Y', strtotime($tanggal_akhir)) }}</p>
                    </div>

                    <div class="mb-3">
                        <a href="javascript:window.print()" class="btn btn-success">
                            <i class="fa fa-print"></i> Cetak Tabel
                        </a>
                    </div>

                    <div class="box box-primary" id="print-area">
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pengeluaran</th>
                                        <th>Jumlah (Rp)</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_pengeluaran = 0; @endphp
                                    @foreach ($transaksis as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->description ?? '-' }}</td>
                                            <td>Rp. {{ number_format($item->jumlah_pengeluaran, 0, ',', '.') }}</td>
                                            <td>{{ $item->created_at->format('d M Y') }}</td>
                                        </tr>
                                        @php $total_pengeluaran += $item->jumlah_pengeluaran; @endphp
                                    @endforeach
                                    <tr>
                                        <th colspan="2" class="text-right">Total Pengeluaran</th>
                                        <th colspan="2">Rp. {{ number_format($total_pengeluaran, 0, ',', '.') }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if (isset($total_pemasukan))
                        <div class="box box-success mt-3">
                            <div class="box-header with-border">
                                <h3 class="box-title">Rekap Saldo</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Total Pemasukan</th>
                                        <td>Rp. {{ number_format($total_pemasukan, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Pengeluaran</th>
                                        <td>Rp. {{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th><strong>Sisa Uang</strong></th>
                                        <td><strong>Rp.
                                                {{ number_format($total_pemasukan - $total_pengeluaran, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning">
                        Silakan pilih tanggal terlebih dahulu untuk menampilkan laporan pengeluaran.
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

            .btn,
            form,
            .breadcrumb,
            .box-footer {
                display: none !important;
            }
        }
    </style>
@endsection
