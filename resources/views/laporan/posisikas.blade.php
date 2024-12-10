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
                                <h4 class="card-title">Laporan Posisi Kas</h4>
                                </br>
                                        <form action="{{route('posisikasCetak')}}" target="_blank" method="GET">
                                                <label for="sampai">Periode </label>
                                                <input required id="periode" name="periode" type="month" class="form-control" placeholder="Periode">
                                            </div>
                                        </br><button type="submit" class="btn mb-1 btn-primary">Lihat Laporan<span class="btn-icon-right"><i class="fa fa-filter"></i></span>
                                            </button>
                                        </form>
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


