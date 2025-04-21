@extends('../layouts/sidebar_bendahara')

@section('container')
    <section class="content-header">
        <h1>Pengeluaran <small>Master Data</small></h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
                    <i class="glyphicon glyphicon-plus"></i> Tambah Pengeluaran
                </button>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pengeluaran</th>
                            <th>Jumlah (Rp)</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengeluaran as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->description ?? '-' }}</td>
                                <td>Rp. {{ number_format($item->jumlah_pengeluaran, 0, ',', '.') }}</td>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td>
                                    <button class="btn btn-success btn-sm" data-toggle="modal"
                                        data-target="#modalEdit{{ $item->id }}">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </button>

                                    <button class="btn btn-danger btn-sm"
                                        onclick="confirmDelete('{{ route('pengeluaranDelete', $item->id) }}', '{{ $item->description }}')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ route('pengeluaranUpdate', $item->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Edit Pengeluaran</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Jumlah Pemasukan Saat Ini</label>
                                                    <input type="text" class="form-control"
                                                        value="Rp. {{ number_format($sisaSaldo, 0, ',', '.') }}" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label>Nama Pengeluaran</label>
                                                    <textarea name="description" class="form-control" rows="3" required>{{ $item->description }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Jumlah (Rp)</label>
                                                    <input type="text" class="form-control formatEdit"
                                                        id="formatEdit{{ $item->id }}"
                                                        value="Rp. {{ number_format($item->jumlah_pengeluaran, 0, ',', '.') }}"
                                                        required>
                                                    <input type="hidden" name="jumlah_pengeluaran"
                                                        id="jumlahEdit{{ $item->id }}"
                                                        value="{{ $item->jumlah_pengeluaran }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="{{ route('pengeluaranAdd') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Pengeluaran</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Jumlah Pemasukan Saat Ini</label>
                            <input type="text" class="form-control"
                                value="Rp. {{ number_format($sisaSaldo, 0, ',', '.') }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>Nama Pengeluaran</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Jumlah (Rp)</label>
                            <input type="text" class="form-control" id="formatTambah" placeholder="Rp. ..." required>
                            <input type="hidden" name="jumlah_pengeluaran" id="jumlahTambah">
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
        const formatTambah = document.getElementById('formatTambah');
        const jumlahTambah = document.getElementById('jumlahTambah');

        if (formatTambah) {
            formatTambah.addEventListener('input', function(e) {
                let input = e.target.value.replace(/[^\d]/g, '');
                if (input) {
                    formatTambah.value = 'Rp. ' + new Intl.NumberFormat('id-ID').format(input);
                    jumlahTambah.value = input;
                } else {
                    formatTambah.value = '';
                    jumlahTambah.value = '';
                }
            });
        }
        document.querySelectorAll('.formatEdit').forEach(function(inputEdit) {
            inputEdit.addEventListener('input', function(e) {
                let input = e.target.value.replace(/[^\d]/g, '');
                let id = this.id.replace('formatEdit', '');
                if (input) {
                    inputEdit.value = 'Rp. ' + new Intl.NumberFormat('id-ID').format(input);
                    document.getElementById('jumlahEdit' + id).value = input;
                } else {
                    inputEdit.value = '';
                    document.getElementById('jumlahEdit' + id).value = '';
                }
            });
        });
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: '{{ session('success') }}',
                confirmButtonText: 'Ok',
            });
        @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonText: 'Ok',
            });
        @endif
        function confirmDelete(url, name) {
            Swal.fire({
                title: 'Peringatan',
                text: `Apakah anda yakin ingin menghapus pengeluaran?`,
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
