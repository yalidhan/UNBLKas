@extends('master')

@section('content')
<div class="content-body">
            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/akun">Akun</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Akun</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Akun') }}</div>

                <div class="card-body">
                    <form method="POST" action="/akun/{{$akun->id}}">
                        @csrf
                        @method ('put')
                        <div class="row mb-3">
                            <label for="no" class="col-md-4 col-form-label text-md-end">{{ __('No Akun') }}</label>

                            <div class="col-md-6">
                                <p><b>{{$akun->no}}</b></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="tipe" class="col-md-4 col-form-label text-md-end">{{ __('Tipe Akun') }}</label>

                            <div class="col-md-6">
                                <p><b>{{$akun->tipe}}</b></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nama" class="col-md-4 col-form-label text-md-end">{{ __('Nama') }}</label>

                            <div class="col-md-6">
                                <input id="nama" type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{$akun->nama}}" required autocomplete="nama" autofocus>

                                @error('nama')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="kelompok" class="col-md-4 col-form-label text-md-end">{{ __('Kelompok') }}</label>

                            <div class="col-md-6">
                                <input id="kelompok" type="text" class="form-control @error('kelompok') is-invalid @enderror" name="kelompok" value="{{$akun->kelompok}}" required autocomplete="kelompok" autofocus>

                                @error('kelompok')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Simpan') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
