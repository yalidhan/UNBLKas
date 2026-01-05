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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Log Transaksi Per Mata Akun</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <!-- @if(session()->has('message'))
                                <div class="alert alert-success">
                                    <b>{{ session()->get('message') }}</b>
                                </div>
                            @endif -->
                            <center>
                                <h4 class="card-title">Log Transaksi Per Mata Akun</h4>                 
                            <h3 align="center">"
                                    {{$departement->nama}}
                                "</h3>
                            <h4 align="center">Sampai Dengan {{\Carbon\Carbon::parse($sd)->format('d F Y')}}</h4>  
                            </center>
                            
                            <div class="row g-3 mb-4">
                                
                                <!-- Card Pengeluaran -->
                                <div class="col-md-6">
                                    <div class="card border-start border-danger border-4 shadow-sm">
                                        <div class="card-body">
                                        <center>
                                            <div class="h1 text-muted small uppercase fw-bold">Total Pengeluaran</div>
                                            <div class="h3 mb-0 fw-bold text-danger">
                                                Rp {{ number_format($totalPengeluaran[0]->total_pengeluaran, 0, ',', '.') }},-
                                            </div>
                                        </div>
                                        </center>
                                    </div>
                                </div>

                                <!-- Card Pengembalian -->
                                <div class="col-md-6">
                                    <div class="card border-start border-success border-4 shadow-sm">
                                        <div class="card-body">
                                        <center>
                                            <div class="h1 text-muted small uppercase fw-bold">Total Pengembalian</div>
                                            <div class="h3 mb-0 fw-bold text-success">
                                                Rp {{ number_format($totalPengembalian[0]->total_pengembalian, 0, ',', '.') }},-
                                            </div>
                                        </div>
                                        </center>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Kalkulasi -->
                            <div class="col-md-12">
                                <div class="card border-start border-success shadow-sm">
                                            <div class="card-body">
                                        <center>
                                            <div class="h1 text-muted small uppercase fw-bold">Kalkulasi(Pengeluaran-Pengembalian)</div>
                                            <div class="h3 mb-0 fw-bold text-warning">
                                                Rp {{ number_format(($totalPengeluaran[0]->total_pengeluaran-$totalPengembalian[0]->total_pengembalian), 0, ',', '.') }},-
                                            </div>
                                        </div>
                                        </center>
                                    </div>
                                </div>
                            </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>No.SPB</th>
                                                <th>Keterangan</th>
                                                <th>Tanggal</th>
                                                <th>Nama Akun</th>
                                                <th>Transaksi</th>
                                                <th>Nominal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($logtransaksi as $transaksi)
                                            <tr>
                                                <td>{{$transaksi->no_spb}}</td>
                                                <td>{{$transaksi->keterangan}}</td>
                                                <td>{{\Carbon\Carbon::parse($transaksi->tanggal)->format('d F Y')}}</td>
                                                <td>{{$transaksi->nama}}</td>
                                                <td>{{$transaksi->dk ==2 ? 'Pengeluaran' : 'Pengembalian'}}</td>
                                                <td>Rp {{number_format(($transaksi->nominal),0,',','.')}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No.SPB</th>
                                                <th>Keterangan</th>
                                                <th>Tanggal</th>
                                                <th>Nama Akun</th>
                                                <th>Nominal</th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
