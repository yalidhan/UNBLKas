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
                                <h4 class="card-title">Laporan Pertanggungjawaban</h4>
                                </br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="{{route('lpjCetak')}}" target="_blank" method="GET">
                                        <div class="row">
                                            <div class="col">
                                                <label for="dari">Dari Tanggal</label>
                                                <input required id="dari" name="dari" type="date" class="form-control" placeholder="Dari Tanggal">
                                            </div>
                                            <div class="col">
                                                <label for="sampai">Sampai Tanggal</label>
                                                <input required id="sampai" name="sampai" type="date" class="form-control" placeholder="Sampai Tanggal">
                                            </div>
                                            @if (auth()->user()->departement_id==1 
                                                OR auth()->user()->jabatan=="Rektor"
                                                OR auth()->user()->jabatan=="Wakil Rektor II"
                                                OR auth()->user()->jabatan=="Kabid Keuangan"
                                                OR auth()->user()->jabatan=="Kabid Perencanaan"
                                                OR auth()->user()->jabatan=="SPI"
                                                ) 
                                                <div class="col">
                                                    <label for="departement">Departemen Tujuan:</label>
                                                        <select id="departement" data-width="100%" name="departement" class="form-control">
                                                            <option value="" selected disabled hidden>Pilih Departement Tujuan</option>
                                                            @foreach($departement as $departementvalue)
                                                                <option value="{{ $departementvalue->id }}">{{ $departementvalue->nama}}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                            @else

                                            @endif
                                        </div>
                                        </br><button type="submit" class="btn mb-1 btn-primary">Lihat Laporan<span class="btn-icon-right"><i class="fa fa-filter"></i></span>
                                            </button>
                                        </form>
                                    </div>
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
@push('lpjPage-script')
    <script>    
        $(document).ready(function() {
            $('.departement').select2();
        });
        $('#departement').select2({
        });
    </script>
@endpush

