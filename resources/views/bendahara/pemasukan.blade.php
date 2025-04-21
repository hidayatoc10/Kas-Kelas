@extends('../layouts/sidebar_bendahara')

@section('container')
    <section class="content-header">
        <h1>
            Master Data
            <small>Siswa</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="dashboard">
                    <i class="fa fa-home"></i>
                    <b>Kas Kelas</b>
                </a>
            </li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="mb-3">
                    <a href="" data-toggle="modal" data-target="#tambahBulanModal" title="Bulan"
                        class="btn btn-primary">
                        <i class="glyphicon glyphicon-plus"></i> Bulan
                    </a>
                </div>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove">
                        <i class="fa fa-remove"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Pembayaran per minggu</th>
                                <th>Total Uang kas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalPerMinggu = 0;
                                $totalKas = 0;
                            @endphp
                            @foreach ($bulanList as $bulan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bulan->nama_bulan }}</td>
                                    <td>{{ $bulan->tahun }}</td>
                                    <td>Rp. {{ number_format($bulan->pembayaran_seminggu, 0, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($bulan->total ?? 0, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('catat.kas', $bulan->id) }}" title="Lihat Detail" class="btn btn-success">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-delete"
                                            data-id="{{ $bulan->id }}" title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @php
                                    $totalPerMinggu += $bulan->pembayaran_seminggu;
                                    $totalKas += $bulan->total ?? 0;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold; background-color: #f2f2f2;">
                                <td colspan="3" class="text-right">Total:</td>
                                <td>Rp. {{ number_format($totalPerMinggu, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($totalKas, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="tambahBulanModal" tabindex="-1" role="dialog" aria-labelledby="tambahBulanLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('AddBulan') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Bulan Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Bulan</label>
                            <select name="nama_bulan" class="form-control" required>
                                <option value="">-- Pilih Bulan --</option>
                                @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $bulan)
                                    <option value="{{ $bulan }}">{{ $bulan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tahun</label>
                            <select name="tahun" class="form-control" required>
                                <option value="">-- Pilih Tahun --</option>
                                @for ($tahun = date('Y'); $tahun >= date('Y') - 50; $tahun--)
                                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pembayaran / Minggu</label>
                            <input type="text" id="formatPembayaran" class="form-control"
                                placeholder="Contoh: Rp. 10.000" required>
                            <input type="hidden" name="pembayaran_seminggu" id="pembayaran_seminggu" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        const formatPembayaran = document.getElementById('formatPembayaran');
        const pembayaranHidden = document.getElementById('pembayaran_seminggu');

        formatPembayaran.addEventListener('input', function(e) {
            let input = e.target.value.replace(/[^\d]/g, '');
            if (input) {
                const formatted = new Intl.NumberFormat('id-ID').format(input);
                formatPembayaran.value = 'Rp. ' + formatted;
                pembayaranHidden.value = input;
            } else {
                formatPembayaran.value = '';
                pembayaranHidden.value = '';
            }
        });
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonText: 'Ok',
            });
        @endif
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data bulan akan dihapus secara permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/hapus_bulan/${id}`;
                    }
                });
            });
        });
    </script>
@endsection
