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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Akun</a></li>
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
                                <h4 class="card-title">Daftar Akun</h4>   
                            
                                <div class="bootstrap-modal">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAccount">Tambah Akun</button>
                            </center>
                                    <div class="modal fade" id="addAccount" tabindex="-1" role="dialog" aria-labelledby="addAccountModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addAccountModalLabel">Tambah Akun</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="/akun" method="POST">
                                                    @csrf
                                                        
                                                            <div class="form-group">
                                                            <label for="kode_akun" class="col-form-label">{{ __('Tipe Akun:') }}</label>
                                                                <select id="kode_akun" name="kode_akun" class="form-control @error('kode_akun') is-invalid @enderror" required autocomplete="kode_akun" autofocus>
                                                                <option value="" selected disabled hidden>Pilih Tipe Akun</option>
                                                                    <option value="01">Harta</option>
                                                                    <option value="02">Utang</option>
                                                                    <option value="03">Modal</option>
                                                                    <option value="04">Pendapatan</option>
                                                                    <option value="05">Beban</option>
                                                                </select>
                                                
                                                            </div>
                                                        <div class="form-group">
                                                            <label for="nama" class="col-form-label">Nama Akun:</label>
                                                            <input required value="{{ old('nama')}}" name="nama" type="text" class="form-control" id="nama">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="kelompok" class="col-form-label">Kelompok:</label>
                                                            <input required value="{{ old('kelompok')}}" name="kelompok" type="text" class="form-control" id="kelompok">
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
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>No Akun</th>
                                                <th>Tipe Akun</th>
                                                <th>Kelompok</th>
                                                <th>Nama Akun</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($accountlist as $account)
                                            <tr>
                                                <td>{{$account->no}}</td>
                                                <td>{{$account->tipe}}</td>
                                                <td>{{$account->kelompok}}</td>
                                                <td>{{$account->nama}}</td>
                                                <td>
                                                    <a href="/akunstat/{{$account->id}}" class="btn btn-sm btn-{{$account->status ? 'success' : 'danger'}}">
                                                      {{$account->status?'Aktif':'Non Aktif'}}
                                                    </a>
                                                </td>
                                                <td><a href="/akun/{{$account->id}}/edit" class="btn mb-1 btn-rounded btn-warning" role="button">Edit</a></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No Akun</th>
                                                <th>Tipe Akun</th>
                                                <th>Kelompok</th>
                                                <th>Nama Akun</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
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
