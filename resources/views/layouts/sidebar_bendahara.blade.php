<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>TABUNGAN SISWA</title>
    <link rel="icon" href="{{ asset('dist/img/julas.png') }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/skins/_all-skins.min.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
</head>


<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown messages-menu">
                            <a class="dropdown-toggle" data-toggle="dropdown">
                                <span>
                                    <b>
                                        {{ Auth::user()->nama }}
                                    </b>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="main-sidebar">
            <section class="sidebar">
                </<b>
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="{{ asset('dist/img/julas.png') }}" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>
                            {{ Auth::user()->nama }}
                        </p>
                        <span class="label label-success">
                            Administrator
                        </span>
                    </div>
                </div>
                </br>
                <ul class="sidebar-menu">
                    <li class="header">NAVIGASI UTAMA</li>

                    <li class="treeview">
                        <a href="{{ route('dashboard') }}">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                            <span class="pull-right-container">
                            </span>
                        </a>
                    </li>

                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-refresh"></i>
                            <span>Transaksi</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">

                            <li>
                                <a href="{{ route('pemasukan') }}">
                                    <i class="fa fa-arrow-circle-o-down"></i>Pemasukan</a>
                            </li>
                            <li>
                                <a href="{{ route('pengeluaran') }}">
                                    <i class="fa fa-arrow-circle-o-up"></i>Pengeluaran</a>
                            </li>
                        </ul>
                    </li>

                    <li class="treeview">
                        <a href="{{ route('siswa') }}">
                            <i class="fa fa-users"></i>
                            <span>Siswa</span>
                            <span class="pull-right-container">
                            </span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="{{ route('laporan.pengeluaran') }}">
                            <i class="fa fa-book"></i>
                            <span>Laporan Pengeluaran</span>
                            <span class="pull-right-container">
                            </span>
                        </a>
                    </li>

                    <li class="treeview">
                        <a href="{{ route('laporan') }}">
                            <i class="fa fa-file"></i>
                            <span>Laporan Pemasukan</span>
                            <span class="pull-right-container">
                            </span>
                        </a>
                    </li>

                    <li class="header">SETTING</li>

                    <li class="treeview">
                        <a href="{{ route('updateAccount') }}">
                            <i class="fa fa-user"></i>
                            <span>Akun Saya</span>
                            <span class="pull-right-container">
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="#" id="btnLogout">
                            <i class="fa fa-sign-out"></i>
                            <span>Logout</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>
            </section>
        </aside>
        <div class="content-wrapper">
            <section class="content">
                @yield('container')
                @yield('scripts')
            </section>
        </div>
        <!-- JS -->
        <script src="{{ asset('plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
        <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
        <script src="{{ asset('dist/js/app.min.js') }}"></script>
        <script src="{{ asset('dist/js/demo.js') }}"></script>

        <script>
            $(function() {
                $("#example1").DataTable();
                $('#example2').DataTable({
                    "paging": true,
                    "lengthChange": false,
                    "searching": false,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#btnLogout').click(function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Keluar dari aplikasi?',
                        text: "Anda akan logout dari sistem.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Logout!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ url('logout') }}";
                        }
                    });
                });
            });
        </script>
        <script>
            $(function() {
                $(".select2").select2();
            });
        </script>

</body>

</html>
