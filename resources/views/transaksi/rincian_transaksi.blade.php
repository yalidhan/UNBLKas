@extends('master')

@section('content')
<div class="content-body">
            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/transaksi">Transaksi</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Rincian Transaksi</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h5 class="card-title">Rincian Transaksi</h5></div>
                <div class="card-body">
                    <div class="card text-left" style="font-weight:bold;font-size:120%;">
                        <div class="card-header">
                            Data Transaksi
                        </div>
                        <div class="card-body">
                        <div class="table-responsive"> 
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>Departemen</td>
                                                <td>&nbsp;:</td>
                                                <td>&nbsp;{{$showTransaction[0]->departement}}</td>
                                            </tr>
                                            <tr>
                                                <td>No. SPB</td>
                                                <td>&nbsp;:</td>
                                                <td>&nbsp;{{$showTransaction[0]->no_spb}}</td>
                                            </tr>
                                            <tr>
                                                <td>Keterangan</td>
                                                <td>&nbsp;:</td>
                                                <td>&nbsp;{{$showTransaction[0]->keterangan}}</td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal</td>
                                                <td>&nbsp;:</td>
                                                <td>&nbsp;{{\Carbon\Carbon::parse($showTransaction[0]->tanggal)->format('d/m/Y')}}</td>
                                            </tr>
                                            <tr>
                                                <td>Total Transaksi</td>
                                                <td>&nbsp;:</td>
                                                <td>&nbsp;Rp {{number_format($showTransaction[0]->total,0,',','.')}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                        </div>
                        <div class="card-footer text-muted">
                            <button type="button" class="btn mb-1 btn-rounded btn-warning" data-toggle="modal" data-target="#edit"><span class="btn-icon-left" style="background: #ffbe88;"><i class="fa fa-edit color-warning"></i></span>Edit Data</button>
                                <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('transaksi.update',$showTransaction[0]->id)}}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                        <label for="tgl_edit" class="col-form-label">Tanggal:</label>
                                                        <div class="input-group">
                                                            <input required type="date" value="{{$showTransaction[0]->tanggal}}" name="tgl_edit" class="form-control" placeholder="mm/dd/yyyy">
                                                        </div>
                                                    </div>
                                                <div class="form-group">
                                                    <label for="no_spb_edit" class="col-form-label">No.SPB:</label>
                                                    <input required name="no_spb_edit" value="{{$showTransaction[0]->no_spb}}" type="text" class="form-control" id="no_spb_edit" placeholder="No.Urut/Departemen/UNBL/Tahun">
                                                </div>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Keterangan</span>
                                                    </div>
                                                    <textarea required name="keterangan_edit" rows="2" cols="30" maxlength="75" class="form-control" aria-label="With textarea">{{$showTransaction[0]->keterangan}}</textarea>
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
                        </div>
                    </div>
                </div>
                <div class="table-responsive"> 
                    <table class="table table-bordered table-striped verticle-middle">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Akun</th>
                                <th scope="col">Nominal</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1 ; @endphp
                            @foreach ($showDetailTransaction as $value)
                            <tr>
                                <td>{{$no++}}</td>
                                <td>{{$value->tipe}} || {{$value->nama}}</td>
                                <td> Rp {{number_format($value->nominal,0,',','.')}}</td>
                                <td><span><a href="#" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil color-muted m-r-5"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-close color-danger"></i></a></span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <center>
                    <a href="{{ URL::previous() }}"><button type="button" class="btn mb-1 btn-success">Kembali<span class="btn-icon-right"><i class="fa fa-chevron-circle-left"></i></button></a>
                    <button type="button" class="btn mb-1 btn-primary" data-toggle="modal" data-target="#tambah_rincian">Tambah Rincian<span class="btn-icon-right"><i class="fa fa-cart-plus"></i></button>
                </center>
                    <div class="modal fade bd-example-modal-lg" id="tambah_rincian" tabindex="-1" role="dialog" aria-labelledby="tambah_rincianModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="tambah_rincianModalLabel">Tambah Rincian</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                            <div class="modal-body">
                                                <form action="#" method="POST" id="forms">
                                                @csrf
                                                    <div class="form-group">
                                                        <label for="akun_rincian" class="col-form-label">Akun</label>
                                                        <select id="akun_rincian" data-width="100%" name="akun_rincian" class="form-control" required>
                                                            <option value="" selected disabled hidden>Pilih Akun</option>
                                                            @foreach($accountList as $accountvalue)
                                                            <option value="{{ $accountvalue->id }}">{{$accountvalue->tipe}} || {{ $accountvalue->nama}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="nominal_tambah_rincian" class="col-form-label">Nominal :</label>
                                                        <input required name="nominal_tambah_rincian" type="text" class="form-control" id="nominal_tambah_rincian" placeholder="Rp">
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
            </div>
        </div>
    </div>
</div>
</div>
@endsection
