@extends('master')

@section('content')
<div class="content-body">
            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/pengguna">Pengguna</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Pengguna</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Pengguna') }}</div>

                <div class="card-body">
                    <form method="POST" action="/pengguna/{{$pengguna->id}}">
                        @csrf
                        @method ('put')
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Nama') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $pengguna->name }}" required autocomplete="name" autofocus>

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
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $pengguna->email }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="departement" class="col-md-4 col-form-label text-md-end">{{ __('Departemen') }}</label>
                            <div class="col-md-6">
                                <select id="departement" name="departement_id" class="form-control @error('departement') is-invalid @enderror" required autocomplete="departement" autofocus>
                                @foreach($departement as $value)
                                    <option value="{{ $value->id }}"{{$pengguna->departement_id == $value->id  ? 'selected' : ''}}>{{ $value->nama}}</option>
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
                                        <option value="Super Admin"{{ $pengguna->jabatan == "Super Admin" ?'selected':'' }}>Super Admin</option>
                                        <option value="Bendahara Yayasan" {{ $pengguna->jabatan == "Bendahara Yayasan" ?'selected':'' }}>Bendahara Yayasan</option>
                                        <option value="Rektor" {{ $pengguna->jabatan == "Rektor" ?'selected':'' }}>Rektor</option> 
                                        <option value="Wakil Rektor II" {{ $pengguna->jabatan == "Wakil Rektor II" ?'selected':'' }}>Wakil Rektor II</option>
                                        <option value="Kabid Keuangan" {{ $pengguna->jabatan == "Kabid Keuangan" ?'selected':'' }}>Kabid Keuangan</option>
                                        <option value="Kabid Perencanaan" {{ $pengguna->jabatan == "Kabid Perencanaan" ?'selected':'' }}>Kabid Perencanaan</option>
                                        <option value="Dekan" {{ $pengguna->jabatan == "Dekan" ?'selected':'' }}>Dekan</option>  
                                        <option value="Bendahara Operasional"{{ $pengguna->jabatan == "Bendahara Operasional" ?'selected':'' }}>Bendahara Operasional</option>     
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
