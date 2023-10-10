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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Pengguna</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            @if(session()->has('message'))
                                <div class="alert alert-success">
                                    <b>{{ session()->get('message') }}</b>
                                </div>
                            @endif
                            <center>
                                <h4 class="card-title">Daftar Pengguna</h4>
                                <a href="{{route('register')}}"><button type="button" class="btn mb-1 btn-rounded btn-primary">Tambah Pengguna</button></a>
                            </center>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Derpartemen</th>
                                                <th>Jabatan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengguna as $value)
                                            <tr>
                                                <td>{{$value->name}}</td>
                                                <td>{{$value->email}}</td>
                                                <td>{{$value->departement->nama}}</td>
                                                <td>{{$value->jabatan}}</td>
                                                <td>
                                                    <a href="/statuspengguna/{{$value->id}}" class="btn btn-sm btn-{{$value->status ? 'success' : 'danger'}}">
                                                      {{$value->status?'Aktif':'Non Aktif'}}
                                                    </a>
                                                </td>
                                                <td><a href="/pengguna/{{$value->id}}/edit" class="btn mb-1 btn-rounded btn-warning" role="button">Edit</a></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                            <th>Nama</th>
                                                <th>Email</th>
                                                <th>Derpartemen</th>
                                                <th>Jabatan</th>
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
