@extends('../layouts/sidebar_bendahara')

@section('container')
    <section class="content-header">
        <h1>
            Tambah Siswa
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
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tambah Siswa</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('siswaAdd_post') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    name="nama" id="nama" placeholder="Nama" required>
                                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                @error('nama')
                                    <div class="invalid-feedback" style="color:red;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group has-feedback">
                                <label for="nis">NIS</label>
                                <input type="number" class="form-control @error('nis') is-invalid @enderror" name="nis"
                                    id="nis" placeholder="NIS" maxlength="10" required>
                                <span class="glyphicon glyphicon-file form-control-feedback"></span>
                                @error('nis')
                                    <div class="invalid-feedback" style="color:red;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group has-feedback">
                                <b>Jenis Kelamin:</b>
                                <select name="jenis_kelamin" class="form-control">
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback" style="color:red;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="box-footer">
                                <a href="{{ route('siswa') }}" class="btn btn-default">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </form>
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
    </script>
@endsection
