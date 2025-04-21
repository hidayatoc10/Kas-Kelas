@extends('../layouts/sidebar_bendahara')

@section('container')
    <section class="content-header">
        <h1>
            Transaksi
            <small>Pemasukan</small>
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
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4>
                <i class="icon fa fa-info"></i> Detail Bulan Pembayaran : {{ $bulan->nama_bulan }} {{ $bulan->tahun }}
            </h4>
            <h3>
                Rp. {{ number_format($bulan->pembayaran_seminggu, 0, ',', '.') }} / minggu
            </h3>
        </div>

        <div class="box box-primary">
            <div class="box-header">
                <a href="{{ route('pemasukan') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Back</a>
                <a href="{{ route('siswaAdd') }}" class="btn btn-primary">
                    <i class="glyphicon glyphicon-plus"></i> Tambah Siswa</a>
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
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Minggu 1</th>
                                <th>Minggu 2</th>
                                <th>Minggu 3</th>
                                <th>Minggu 4</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->siswa->nis }}</td>
                                    <td>{{ $item->siswa->nama }}</td>
                                    @for ($i = 1; $i <= 4; $i++)
                                        @php
                                            $field = "minggu_$i";
                                            $status = $item->$field;
                                            $btnClass = $status === 'LUNAS' ? 'btn-success' : 'btn-danger';
                                            $label = $status === 'LUNAS' ? '✔ Sudah bayar' : '✖ Belum bayar';
                                        @endphp
                                        <td>
                                            <button class="btn btn-sm {{ $btnClass }} btn-update-minggu"
                                                data-id="{{ $item->id }}" data-minggu="{{ $field }}"
                                                {{ $status === 'LUNAS' ? 'disabled' : '' }}>
                                                {{ $label }}
                                            </button>
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    @parent
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example1').DataTable();
            $(document).on('click', '.btn-update-minggu', function() {
                let id = $(this).data('id');
                let minggu = $(this).data('minggu');

                Swal.fire({
                    title: 'Apakah anda yakin siswa ini sudah bayar kas?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post("{{ route('catat.kas.update') }}", {
                            _token: '{{ csrf_token() }}',
                            id: id,
                            minggu: minggu
                        }, function(res) {
                            location.reload();
                        });
                    }
                });
            });
        });
    </script>
@endsection
