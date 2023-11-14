@extends('master')
@section('content')
@php use Carbon\Carbon; @endphp
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Transaksi</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <h4 class="card-title">Buku Kas</h4>
                                <div class="row">
                                    <div class="col-md-3">
                                        <form action="#">
                                            <div class="form-group">
                                                <label for="periode" class="col-form-label">Periode:</label>
                                                    <div class="input-group">
                                                        <input id="periode" type="month" name="periode" class="form-control">
                                                    </div>   
                                                </br><button type="button" class="btn mb-1 btn-success">Filter<span class="btn-icon-right"><i class="fa fa-filter"></i></span>
                                            </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-9 align-self-center">
                                            <!-- <button type="button" class="btn mb-1 btn-primary">pemasukan<span class="btn-icon-right"><i class="fa fa-money"></i></span></button> -->
                                            <!-- <div class="bootstrap-modal"> -->
                                                <button type="button" class="btn mb-1 btn-primary" data-toggle="modal" data-target="#pemasukan">Pemasukan<span class="btn-icon-right"><i class="fa fa-money"></i></button>
                                                <div class="modal fade bd-example-modal-lg" id="pemasukan" tabindex="-1" role="dialog" aria-labelledby="pemasukanModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="pemasukanModalLabel">Pemasukan</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="/transaksi" method="POST">
                                                                @csrf
                                                                        <div class="form-group">
                                                                            <label for="tgl_pemasukan" class="col-form-label">Tanggal:</label>
                                                                            <div class="input-group">
                                                                                <input id="tgl_pemasukan" required name="tgl_pemasukan" type="date" class="form-control" placeholder="mm/dd/yyyy">
                                                                            </div>
                                                                        </div>
                                                                    <div class="form-group">
                                                                        <label for="kode_pemasukan" class="col-form-label">Kode Pemasukan:</label>
                                                                        <input name="kode_pemasukan" type="text" class="form-control" id="kode_pemasukan" placeholder="No Cek/No Ref/No Record/Kode Transaksi">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="akun_pendapatan" class="col-form-label">Akun Pendapatan</label>
                                                                        <select id="akun_pendapatan" data-width="100%" name="akun_pendapatan" class="form-control" required>
                                                                            <option value="" selected disabled hidden>Pilih Akun</option>
                                                                            @foreach($accountlist as $accountvalue)
                                                                                <option value="{{ $accountvalue->id }}">{{$accountvalue->tipe}} || {{ $accountvalue->nama}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">Keterangan</span>
                                                                        </div>
                                                                        <textarea name="keterangan_pemasukan" rows="2" cols="30" maxlength="75" class="form-control" aria-label="With textarea"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="nominal_pemasukan" class="col-form-label">Nominal Pemasukan:</label>
                                                                        <input required name="nominal_pemasukan" type="text" class="form-control" id="nominal_pemasukan" placeholder="Rp">
                                                                    </div>
                                                                    <input type="hidden" value="{{auth()->user()->departement_id}}" name="id_departement">
                                                                    <input type="hidden" value="{{auth()->user()->id}}" name="user_id">
                                                                    <input type="hidden" value="1" name="dk">
                                                            </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                                    </div>
                                                                </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <!-- </div> -->
                                            <!-- <button type="button" class="btn mb-1 btn-info">Pengeluaran<span class="btn-icon-right"><i class="fa fa-shopping-cart"></i></span></button> -->
                                            <!-- <div class="bootstrap-modal"> -->
                                                <button type="button" class="btn mb-1 btn-danger" data-toggle="modal" data-target="#pengeluaran">Pengeluaran<span class="btn-icon-right"><i class="fa fa-shopping-cart"></i></button>
                                                    <div class="modal fade" id="pengeluaran" tabindex="-1" role="dialog" aria-labelledby="pengeluaranModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="pengeluaranModalLabel">Pengeluaran</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="/pengeluaran" method="POST">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                            <label for="tgl_pengeluaran" class="col-form-label">Tanggal:</label>
                                                                            <div class="input-group">
                                                                                <input required type="date" name="tgl_pengeluaran" class="form-control" placeholder="mm/dd/yyyy">
                                                                            </div>
                                                                        </div>
                                                                    <div class="form-group">
                                                                        <label for="no_spb_transfer" class="col-form-label">No.SPB:</label>
                                                                        <input required name="no_spb_transfer" type="text" class="form-control" id="no_spb_transfer" placeholder="No.Urut/Departemen/UNBL/Tahun">
                                                                    </div>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">Keterangan</span>
                                                                        </div>
                                                                        <textarea required name="keterangan_pengeluaran" rows="2" cols="30" maxlength="75" class="form-control" aria-label="With textarea"></textarea>
                                                                    </div>
                                                                </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                                        </div>
                                                                    </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <!-- </div> -->
                                            <!-- <button type="button" class="btn mb-1 btn-secondary">Transfer<span class="btn-icon-right"><i class="fa fa-send"></i></span></button> -->
                                            <!-- <div class="bootstrap-modal"> -->
                                                <button type="button" class="btn mb-1 btn-warning" data-toggle="modal" data-target="#transfer">Transfer<span class="btn-icon-right"><i class="fa fa-send"></i></button>
                                                        <div class="modal fade bd-example-modal-lg" id="transfer" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="transferModalLabel">Transfer</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <!-- <div class="card border-dark">
                                                                            <div class="card-body text-dark">
                                                                                <h5 class="card-title">Informasi Saldo</h5>
                                                                                <p class="card-text">User : User Admin Fakultas</p>
                                                                                <p class="card-text">Departemen : Fakultas Farmasi  </p>
                                                                                <p class="card-text">Saldo : Rp 50.000.000 </p>
                                                                            </div>
                                                                        </div> -->
                                                                        <form action="#" method="POST">
                                                                        @csrf
                                                                        <div class="form-group">
                                                                            <label for="akun_kas" class="col-form-label">Kas Departemen Tujuan:</label>
                                                                            <select id="akun_kas" data-width="100%" name="akun_kas" class="form-control" required>
                                                                                <option value="" selected disabled hidden>Pilih Akun</option>
                                                                                <option value="01">Kas Yayasan</option>
                                                                                <option value="02">Kas Rektorat</option>
                                                                                <option value="03">Kas Fakultas Farmasi</option>
                                                                                <option value="04">Kas Fakultas Humaniora</option>
                                                                                <option value="05">Kas Fakultas Kesehatan</option>
                                                                                <option value="06">Kas Prodi D3 Farmasi</option>
                                                                            </select>
                                                                        </div>
                                                                            <div class="form-group">
                                                                            <label for="tgl_transfer" class="col-form-label">Tanggal:</label>
                                                                            <div class="input-group">
                                                                                <input id="tgl_transfer" required type="date" name="tgl_pengeluaran" class="form-control" placeholder="mm/dd/yyyy">
                                                                            </div>
                                                                        </div>
                                                                    <div class="form-group">
                                                                        <label for="no_spb_transfer" class="col-form-label">No.SPB:</label>
                                                                        <input required name="no_spb_transfer" type="text" class="form-control" id="no_spb_transfer" placeholder="No.Urut/Departemen/UNBL/Tahun">
                                                                    </div>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">Keterangan</span>
                                                                        </div>
                                                                        <textarea required name="keterangan_transfer" rows="2" cols="30" maxlength="75" class="form-control" aria-label="With textarea"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="nominal_transfer" class="col-form-label">Nominal Transfer:</label>
                                                                        <input required name="nominal_transfer" type="text" class="form-control" id="nominal_transfer" placeholder="Rp">
                                                                    </div>
                                                                    </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                                            </div>
                                                                        </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                            <!-- </div> -->                                    
                                    </div>
                                </div>
                            </br>
                                <center><h4>Kas {{auth()->user()->departement->nama}} Periode {{Carbon::now()->isoFormat('MMMM Y')}}  </h4></center>
                                <div class="table-responsive"> 
                                    <table class="table table-bordered table-striped verticle-middle">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">No.SPB</th>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">Keterangan</th>
                                                <th scope="col">Debet</th>
                                                <th scope="col">Kredit</th>
                                                <th scope="col">Saldo</th>
                                                <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $saldo = 15000000 ; @endphp
                                            <tr>
                                                <td colspan="6"><b>Saldo Awal</b></td>
                                                <td style="text-align:left;white-space: nowrap;" ><b>Rp {{number_format($saldo,0,',','.')}}</b></td>
                                            </tr>
                                            @php $no = 1 ; @endphp
                                            @foreach ($transactionlist as $transaction)
                                            <tr>
                                                <td>{{$no++}}</td>
                                                <td>{{$transaction->no_spb}}</td>
                                                <td>{{\Carbon\Carbon::parse($transaction->tanggal)->format('d/m/Y')}}</td>
                                                <td>{{$transaction->keterangan}}</td>
                                                @if ($transaction->dk==1)
                                                    <td style="white-space: nowrap;"> Rp {{number_format($transaction->total,0,',','.'),
                                                        $saldo=$saldo+$transaction->total}}</td>
                                                @else 
                                                    <td style="white-space: nowrap;">Rp 0</td>
                                                @endif
                                                @if ($transaction->dk==2)
                                                    <td style="white-space: nowrap;"> Rp {{number_format($transaction->total,0,',','.'),
                                                        $saldo=$saldo-$transaction->total}}</td>
                                                @else 
                                                    <td style="white-space: nowrap;">Rp 0</td>
                                                @endif
                                                <td style="white-space: nowrap;">Rp {{number_format($saldo,0,',','.')}}</td>
                                                <td><span><a href="/rincian_transaksi" data-toggle="tooltip" data-placement="top" title="Rincian">
                                                    <i class="fa fa-eye color-danger"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i class="fa fa-pencil color-muted m-r-5"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" data-toggle="tooltip" data-placement="top" title="Hapus">
                                                    <i class="fa fa-close color-danger"></i></a></span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
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

