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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Tambah Pengguna</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <center><h3><div class="card-header">{{ __('Tambah Pengguna') }}</div></h3></center>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Nama') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Alamat Email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Konfirmasi Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-3">
                        <label for="departement" class="col-md-4 col-form-label text-md-end">{{ __('Departemen') }}</label>
                            <div class="col-md-6">
                                <select id="departement" name="departement_id" class="form-control @error('departement') is-invalid @enderror" required autocomplete="departement" autofocus>
                                <option value="" selected disabled hidden>Pilih Departemen</option>
                                @foreach($departement as $value)
                                    <option value="{{ $value->id }}">{{ $value->nama}}</option>
                                @endforeach
                                </select>
                
                                @error('departement')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="jabatan" class="col-md-4 col-form-label text-md-end">{{ __('Jabatan') }}</label>
                            <div class="col-md-6">
                                    <select id="jabatan" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" required autocomplete="jabatan" autofocus>
                                        <option value="" selected disabled hidden>Pilih Jabatan</option>
                                        <option value="Super Admin">Super Admin</option>
                                        <option value="Bendahara Yayasan">Bendahara Yayasan</option>
                                        <option value="Rektor">Rektor</option> 
                                        <option value="Wakil Rektor II">Wakil Rektor II</option>
                                        <option value="Kabid Keuangan">Kabid Keuangan</option>
                                        <option value="Kabid Perencanaan">Kabid Perencanaan</option>
                                        <option value="Dekan">Dekan</option>
                                        <option value="Kaprodi">Kaprodi</option>
                                        <option value="Bendahara Operasional">Bendahara Operasional</option>
                                        <option value="Admin Departemen">Admin Departemen</option>
                                        <option value="SPI">SPI</option>     
                                    </select>
                                @error('jabatan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
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
