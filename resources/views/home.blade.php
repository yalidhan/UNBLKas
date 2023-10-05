@extends('master')

@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">

            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            {{ __('You are logged in!') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-sm-4">
                        <div class="card gradient-1">
                            <div class="card-body">
                                <h3 class="card-title text-white">Periode Berjalan</h3>
                                <div class="d-inline-block">
                                    <h2 class="text-white">JULI 2023</h2>
                                    <!-- <p class="text-white mb-0">Jan - March 2019</p> -->
                                </div>
                                <span class="float-right display-5 opacity-5"><i class="fa fa-calendar-check-o"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4">
                        <div class="card gradient-2">
                            <div class="card-body">
                                <h3 class="card-title text-white">Anggaran UNBL</h3>
                                <div class="d-inline-block">
                                    <h2 class="text-white">Rp 1.700.000.000</h2>
                                    <!-- <p class="text-white mb-0">Jan - March 2019</p> -->
                                </div>
                                <span class="float-right display-5 opacity-5"><i class="fa fa-money"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4">
                        <div class="card gradient-3">
                            <div class="card-body">
                                <h3 class="card-title text-white">Realisasi Anggaran</h3>
                                <div class="d-inline-block">
                                    <h2 class="text-white">Rp 850.000.000</h2>
                                    <!-- <p class="text-white mb-0">Jan - March 2019</p> -->
                                </div>
                                <span class="float-right display-5 opacity-5"><i class="fa fa-balance-scale"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                            <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <center><h3>Progress Realisasi Anggaran UNBL</h3>
                                    <div class="progress" style="height: 9px">
                                        <div class="progress-bar bg-success" style="width: 50%;" role="progressbar">50%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <center><h3>Informasi Saldo</h3>
                    <h3>Fakultas Farmasi</h3>
                    <h4>Periode</h4>
                    <h4>Juli 2023</h4>
                <div class="row">
                    <div class="col-lg-4 col-sm-4">
                        <div class="card">
                            <div class="stat-widget-one">
                                <div class="stat-content">
                                    <div class="stat-text">Penerimaan</div>
                                    <div class="stat-digit gradient-3-text">Rp 2.000.000</div>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar gradient-3" style="width: 100%;" role="progressbar"><span class="sr-only">100% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-4">
                        <div class="card">
                            <div class="stat-widget-one">
                                <div class="stat-content">
                                    <div class="stat-text">Pengeluaran</div>
                                    <div class="stat-digit gradient-4-text">Rp 500.000</div>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar gradient-4" style="width: 25%;" role="progressbar"><span class="sr-only">25% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-4">
                        <div class="card">
                            <div class="stat-widget-one">
                                <div class="stat-content">
                                    <div class="stat-text">Saldo</div>
                                    <div class="stat-digit gradient-4-text">Rp 1.500.000</div>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar gradient-4" style="width: 75%;" role="progressbar"><span class="sr-only">75% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- #/ container -->
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
@endsection
