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
                    <!-- <div class="col-12">
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
                    </div> -->
                </div>
                <div class="row">
                    <div class="col-lg-4 col-sm-4">
                        <div class="card gradient-1">
                            <div class="card-body">
                                <h3 class="card-title text-white">Periode Berjalan</h3>
                                <div class="d-inline-block">
                                    <h2 class="text-white">{{\Carbon\Carbon::now()->format(' F Y')}}</h2>
                                    <!-- <p class="text-white mb-0">Jan - March 2019</p> -->
                                </div>
                                <span class="float-right display-5 opacity-5"><i class="fa fa-calendar-check-o"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4">
                        <div class="card gradient-2">
                            <div class="card-body">
                                <h3 class="card-title text-white">Anggaran {{auth()->user()->departement->nama}}</h3>
                                <div class="d-inline-block">
                                    <h2 class="text-white">Rp @if ($rencana_anggaran!=0)
                                        {{number_format($rencana_anggaran[0]->total,0,',','.')}}
                                        @else
                                            0
                                        @endif
                                    </h2>
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
                                    <h2 class="text-white">Rp @if ($realisasi_anggaran!=0)
                                        {{number_format($realisasi_anggaran[0]->total,0,',','.')}}
                                        @else
                                            0
                                        @endif
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
                                    <center><h3>Progress Realisasi Anggaran {{auth()->user()->departement->nama}}</h3>
                                    <div class="progress" style="height: 9px">
                                        <div class="progress-bar bg-success" style="color:#000000;width:{{number_format($persentaseRealisasi, 2, '.', ',')}}%;" role="progressbar">{{number_format($persentaseRealisasi, 2, '.', ',')}}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <center><h3>Informasi Saldo</h3>
                    <h3>{{auth()->user()->departement->nama}}</h3>
                    <h4>Periode</h4>
                    <h4>Periode {{\Carbon\Carbon::now()->format('F Y')}}</h4>
                <div class="row">
                    <div class="col-lg-4 col-sm-4">
                        <div class="card">
                            <div class="stat-widget-one">
                                <div class="stat-content">
                                    <div class="stat-text">Saldo Bulan Lalu+Penerimaan</div>
                                    <div class="stat-digit gradient-6-text">Rp {{number_format($saldoLastMonth+$saldoDebitList,0,',','.')}}</div>
                                </div>
                                <div class="progress mb-3" style="height: 13px">
                                    <div class="progress-bar bg-success active progress-bar-striped" style="width: 100%;" role="progressbar"><span class="sr-only">100% Complete</span>
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
                                    <div class="stat-digit gradient-4-text">Rp {{number_format($saldoKreditList,0,',','.')}}</div>
                                </div>
                                <div class="progress mb-3" style="height: 13px">
                                    <div class="progress-bar bg-warning active progress-bar-striped" style="color:#000000;width:{{number_format($persentasePengeluaran, 2, '.', ',')}}%;" role="progressbar">{{number_format($persentasePengeluaran, 2, '.', ',')}}%
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
                                    <div class="stat-digit gradient-4-text">Rp {{number_format($saldoLastMonth+$saldoDebitList-$saldoKreditList,0,',','.')}}</div>
                                </div>
                                <div class="progress mb-3" style="height: 13px">
                                    <div class="progress-bar bg-danger active progress-bar-striped" style="color:#000000;width:{{number_format($persentaseSaldo, 2, '.', ',')}}%;" role="progressbar">{{number_format($persentaseSaldo, 2, '.', ',')}}%
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
