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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Departemen</a></li>
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
                                <h4 class="card-title">Daftar Departemen</h4>   
                            
                                <div class="bootstrap-modal">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addDepartemen">Tambah Departemen</button>
                            </center>
                                    <div class="modal fade" id="addDepartemen" tabindex="-1" role="dialog" aria-labelledby="adddepartemenModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="adddepartemenModalLabel">Tambah Departemen</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="/departement" method="POST">
                                                    @csrf
                                                        <div class="form-group">
                                                            <label for="nama" class="col-form-label">Nama Departemen:</label>
                                                            <input required value="{{ old('nama')}}" name="nama" type="text" class="form-control" id="nama">
                                                        </div>
                                                        <div class="form-group">
                                                        <label for="pusat" class="col-md-4 col-form-label text-md-end">{{ __('Pusat') }}</label>
                                                                <select id="pusat" name="pusat" class="form-control @error('pusat') is-invalid @enderror" required autocomplete="pusat" autofocus>
                                                                    <option value="" selected disabled hidden>Pilih Pusat</option>
                                                                    <option value="Rektorat">Rektorat</option>
                                                                    <option value="Fakultas Farmasi">Fakultas Farmasi</option>
                                                                    <option value="Fakultas Ilmu Kesehatan & Sains Teknologi">Fakultas Ilmu Kesehatan & Sains Teknologi</option> 
                                                                    <option value="Fakultas Ilmu Sosial & Humaniora">Fakultas Ilmu Sosial & Humaniora</option>
                                                                    <option value="Yayasan Borneo Lestari">Yayasan Borneo Lestari</option>  
                                                                </select>
                                                                @error('pusat')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
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
                                                <th>Nama</th>
                                                <th>Pusat</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($daftardepartement as $departement)
                                            <tr>
                                                <td>{{$departement->nama}}</td>
                                                <td>{{$departement->pusat}}</td>
                                                <td>
                                                    <a href="/departementstat/{{$departement->id}}" class="btn btn-sm btn-{{$departement->status ? 'success' : 'danger'}}">
                                                      {{$departement->status?'Aktif':'Non Aktif'}}
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="bootstrap-modal">
                                                        <button type="button" class="btn mb-1 btn-rounded btn-warning" data-toggle="modal" data-target="#editDepartemen{{$departement->id}}">Edit</button>
                                                        <div class="modal fade" id="editDepartemen{{$departement->id}}" tabindex="-1" role="dialog" aria-labelledby="editdepartemenModalLabel{{$departement->id}}" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="editdepartemenModalLabel{{$departement->id}}">Edit Departemen</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="/departement/{{$departement->id}}" method="POST">
                                                                        @csrf
                                                                        @method('put')
                                                                            <div class="form-group">
                                                                                <label for="nama" class="col-form-label">Nama Departemen:</label>
                                                                                <input required value="{{$departement->nama}}" name="nama" type="text" class="form-control" id="nama" style="padding:0.35px 0.35px 0.35px 5px !important" >
                                                                            </div>
                                                                            <div class="form-group">
                                                                            <label for="pusat" class="col-form-label">{{ __('Pusat') }}</label>
                                                                                    <select id="pusat" data-width="100%" name="pusat" class="form-control @error('pusat') is-invalid @enderror" required autocomplete="pusat" autofocus style="padding:0.35px 0.35px 0.35px 5px !important">
                                                                                        <option value="" selected disabled hidden>Pilih Pusat</option>
                                                                                        <option value="Rektorat"{{ $departement->pusat == "Rektorat" ?'selected':'' }}>Rektorat</option>
                                                                                        <option value="Fakultas Farmasi"{{ $departement->pusat == "Fakultas Farmasi" ?'selected':'' }}>Fakultas Farmasi</option>
                                                                                        <option value="Fakultas Ilmu Kesehatan & Sains Teknologi" {{ $departement->pusat == "Fakultas Ilmu Kesehatan & Sains Teknologi" ?'selected':'' }}>Fakultas Ilmu Kesehatan & Sains Teknologi</option> 
                                                                                        <option value="Fakultas Ilmu Sosial & Humaniora" {{ $departement->pusat == "Fakultas Ilmu Sosial & Humaniora" ?'selected':'' }}>Fakultas Ilmu Sosial & Humaniora</option>
                                                                                        <option value="Yayasan Borneo Lestari" {{ $departement->pusat == "Yayasan Borneo Lestari" ?'selected':'' }}>Yayasan Borneo Lestari</option>  
                                                                                    </select>
                                                                                    @error('pusat')
                                                                                        <span class="invalid-feedback" role="alert">
                                                                                            <strong>{{ $message }}</strong>
                                                                                        </span>
                                                                                    @enderror
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
                                                </td>
                                                <!-- <td><a href="/departement/{{$departement->id}}/edit" class="btn mb-1 btn-rounded btn-warning" role="button">Edit</a></td> -->
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                            <th>Nama</th>
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
