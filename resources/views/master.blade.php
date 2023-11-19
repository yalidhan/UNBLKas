<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Website Buku Kas UNBL</title>
    <!-- Favicon icon -->
    <link rel="icon" type="/assets/image/png" sizes="16x16" href="/assets/images/unbl.png">
    <!-- Custom Stylesheet -->
    <link href="/assets/plugins/tables/css/datatable/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/select2.min.css" rel="stylesheet">
    <link href="/assets/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/plugins/sweetalert/css/sweetalert.css" rel="stylesheet">
    <!-- <link href="/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet"> -->

</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    
    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <div class="brand-logo">
                <a href="/home">
                    <b class="logo-abbr"><img src="/assets/images/unbl.png" alt=""> </b>
                    <span class="logo-compact"><img src="/assets/images/unbl.png" alt=""></span>
                    <span class="brand-title">
                        <img src="/assets/images/unbl-text.png" alt="">
                    </span>
                </a>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">    
            <div class="header-content clearfix">
                
                <div class="nav-control">
                    <div class="hamburger">
                        <span class="toggle-icon"><i class="icon-menu"></i></span>
                    </div>
                </div>
                <div class="header-left">
                    
                </div>
                <div class="header-right">
                    <ul class="clearfix">
                        <li class="icons dropdown">
                            <div class="user-img c-pointer position-relative"   data-toggle="dropdown">
                                <span class="activity active"></span>
                                <img src="/assets/images/user/form-user.png" height="40" width="40" alt="">
                            </div>
                            <div class="drop-down dropdown-profile   dropdown-menu">
                                <div class="dropdown-content-body">
                                    <ul>
                                        <li>
                                            <b>{{auth::user()->name}}</b>
                                        </li>
                                        <li>
                                            <a href="{{route('updatepassword')}}"><i class="icon-key"></i> <span>Ganti Password</span></a>
                                        </li>
                                        <li>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();">
                                                    <i class="fa fa-sign-out"></i> <span>Logout</span>
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                    @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="nk-sidebar">           
            <div class="nk-nav-scroll">
                <ul class="metismenu" id="menu">
                    <li>
                        <a href="{{route('home')}}" aria-expanded="false">
                            <i class="icon-speedometer menu-icon"></i><span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-label">Data Master</li>
                    <li class="mega-menu mega-menu-sm">
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-globe-alt menu-icon"></i><span class="nav-text">Data</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{route('pengguna.index')}}">Pengguna</a></li>
                            <li><a href="{{route('departement.index')}}">Departemen</a></li>
                            <li><a href="{{route('akun.index')}}">Akun</a></li>
                            <li><a href="#">Anggaran</a></li>
                        </ul>
                    <li class="nav-label">Transaksi</li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-note menu-icon"></i><span class="nav-text">Transaksi</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="/transaksi">Buku Kas</a></li>
                        </ul>
                    </li>
                    <li class="nav-label">Laporan</li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-notebook menu-icon"></i><span class="nav-text">Laporan</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="/pertanggungjawaban">Lembar Pertanggungjawaban</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->
        @yield('content')
        
        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Universitas Borneo Lestari <b>2023</b></p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="/assets/plugins/common/common.min.js"></script>
    <script src="/assets/js/custom.min.js"></script>
    <script src="/assets/js/settings.js"></script>
    <script src="/assets/js/gleek.js"></script>
    <script src="/assets/js/select2.min.js"></script>
    <script src="/assets/js/styleSwitcher.js"></script>

    <script src="/assets/plugins/tables/js/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/tables/js/datatable/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/tables/js/datatable-init/datatable-basic.min.js"></script>
    <script src="/assets/plugins/sweetalert/js/sweetalert.min.js"></script>
    <script src="/assets/plugins/sweetalert/js/sweetalert.init.js"></script>
    <!-- <script src="/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script> -->
    @if(session()->has('message'))
        <script>
            swal("Sukses!", "{{ session()->get('message') }}", "success");
        </script>
    <!-- <div class="alert alert-success">
        <b>{{ session()->get('message') }}</b>
    </div> -->
    @endif
    <script src="/assets/js/jquery.mask.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#nominal_pemasukan').mask('#.##0', {reverse: true});
            $('#nominal_tambah_rincian').mask('#.##0', {reverse: true});
            $('#nominal_transfer').mask('#.##0', {reverse: true});
        })
    </script>
    <script>    
        $(document).ready(function() {
            $('.akun_rincian').select2();
        });
        $('#akun_rincian').select2({
        dropdownParent: $('#tambah_rincian')
    });
    </script>
    <script>    
        $(document).ready(function() {
            $('.akun_pendapatan').select2();
        });
        $('#akun_pendapatan').select2({
        dropdownParent: $('#pemasukan')
    });
    </script>
    <script>    
        $(document).ready(function() {
            $('.akun_kas_tujuan').select2();
        });
        $('#akun_kas_tujuan').select2({
        dropdownParent: $('#transfer')
    });
    </script>
        <script>    
        $(document).ready(function() {
            $('.akun_kas_awal').select2();
        });
        $('#akun_kas_awal').select2({
        dropdownParent: $('#transfer')
    });
    </script>
    <script>    
        $(document).ready(function() {
            $('.departement_tujuan').select2();
        });
        $('#departement_tujuan').select2({
        dropdownParent: $('#transfer')
    });
    </script>

</body>

</html>