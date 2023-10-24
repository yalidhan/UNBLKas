@extends('master')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Laporan</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Laporan Pertanggungjawaban</h4>
                                </br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form>
                                        <div class="row">
                                            <div class="col">
                                            <label for="dari">Dari Tanggal</label>
                                            <input id="dari" type="date" class="form-control" placeholder="Dari Tanggal">
                                            </div>
                                            <div class="col">
                                            <label for="sampai">Sampai Tanggal</label>
                                            <input id="sampai" type="date" class="form-control" placeholder="Sampai Tanggal">
                                            </div>
                                        </div>
                                        </br><button type="button" class="btn mb-1 btn-primary">Lihat Laporan<span class="btn-icon-right"><i class="fa fa-filter"></i></span>
                                            </button>
                                        </form>
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

