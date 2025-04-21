@extends('../layouts/sidebar_bendahara')

@section('container')
    <section class="content-header">
        <h1>Edit Siswa</h1>
        <ol class="breadcrumb">
            <li><a href="dashboard"><i class="fa fa-home"></i><b>Kas Kelas</b></a></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Siswa</h3>
                    </div>

                    <form action="{{ route('siswaEdit_post', $siswa->nis) }}" method="post">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $siswa->nama) }}" required>
                                @error('nama')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="nis">NIS</label>
                                <input type="text" name="nis" id="nis" class="form-control" value="{{ old('nis', $siswa->nis) }}" required>
                                @error('nis')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                                    <option value="Laki-Laki" {{ $siswa->jenis_kelamin == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                    <option value="Perempuan" {{ $siswa->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="box-footer">
                                <a href="{{ route('siswa') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                                <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
