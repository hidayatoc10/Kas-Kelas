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
                    <a href="{{ route('siswaAdd') }}" title="Tambah Data" class="btn btn-primary">
                        <i class="glyphicon glyphicon-plus"></i> Tambah Data
                    </a>
                    <form id="importForm" action="{{ route('siswaImport') }}" method="POST" enctype="multipart/form-data"
                        style="display: inline;">
                        @csrf
                        <label class="btn btn-success" title="Import Excel" style="margin-bottom: 0;">
                            <i class="glyphicon glyphicon-upload"></i> Import Excel
                            <input type="file" name="excel_file" accept=".xlsx" style="display: none;"
                                onchange="document.getElementById('importForm').submit();">
                        </label>
                    </form>
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
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($siswa as $siswa)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $siswa->nis }}
                                    </td>
                                    <td>
                                        {{ $siswa->nama }}
                                    </td>
                                    <td>
                                        {{ $siswa->jenis_kelamin }}
                                    </td>
                                    <td>
                                        <a href="{{ route('siswaEdit', $siswa->nis) }}" title="Ubah"
                                            class="btn btn-success">
                                            <i class="glyphicon glyphicon-edit"></i>
                                        </a>
                                        <button class="btn btn-danger"
                                            onclick="confirmDelete('{{ route('siswaDelete', $siswa->nis) }}', '{{ $siswa->nama }}')">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </button>
                                    </td>
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
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonText: 'Ok',
            });
        @endif

        function confirmDelete(url, name) {
            Swal.fire({
                title: 'Peringatan',
                text: `Apakah kamu yakin ingin mengahpus siswa ${name}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    </script>
@endsection
