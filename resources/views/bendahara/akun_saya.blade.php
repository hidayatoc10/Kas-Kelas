@extends('../layouts/sidebar_bendahara')

@section('container')
    <section class="content-header">
        <h1>
            Akun {{ auth()->user()->nama }}
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
                        <h3 class="box-title">Ubah Profile {{ auth()->user()->nama }}</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                    </div>
                    @if ($errors->any())
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="alert alert-danger alert-dismissible">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('updateAccount_post') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="old_password">Password Lama</label>
                                <input type="password" class="form-control @error('old_password') is-invalid @enderror"
                                    name="old_password" id="old_password" placeholder="Password Lama" required>
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                @error('old_password')
                                    <div class="invalid-feedback" style="color:red;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group has-feedback">
                                <label for="new_password">Password Baru</label>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                    name="new_password" id="new_password" placeholder="Password Baru" required>
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                @error('new_password')
                                    <div class="invalid-feedback" style="color:red;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group has-feedback">
                                <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                                <input type="password"
                                    class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                    name="new_password_confirmation" id="new_password_confirmation"
                                    placeholder="Konfirmasi Password Baru" required>
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                @error('new_password_confirmation')
                                    <div class="invalid-feedback" style="color:red;">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn btn-success" id="btnUbah">
                                Ubah
                            </button>
                        </div>
                    </form>
                </div>
    </section>
@endsection
@section('scripts')
    @parent
    <script>
        document.getElementById('btnUbah').addEventListener('click', function() {
            const form = this.closest('form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            Swal.fire({
                title: 'Peringatan',
                text: "Apakah Anda yakin ingin mengubah akun ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, ubah!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
