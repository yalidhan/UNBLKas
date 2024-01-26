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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Perencanaan</a></li>
                    </ol>
                </div>
            </div>

            <!-- row -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        @if(auth()->user()->jabatan=='Bendahara Yayasan' 
                            OR auth()->user()->jabatan=='Wakil Rektor II' 
                            OR auth()->user()->jabatan=='Rektor'
                            OR auth()->user()->jabatan=='Super Admin'
                            OR auth()->user()->jabatan=='Kabid Keuangan'
                            OR auth()->user()->jabatan=='Kabid Perencanaan'
                            )

                            <div class="col-md-3">
                                <form action="report-perencanaan/cetak" method="get">                                                        
                                    <div class="form-group">
                                        <label for="periode" class="col-form-label">Laporan Rekapitulasi Perencanaan:<br>Untuk Bulan</label>
                                            <div class="input-group">
                                                <input id="periode" type="month" name="periode" required class="form-control">
                                            </div>   
                                        </br><button type="submit" class="btn mb-1 btn-success">Cetak<span class="btn-icon-right"><i class="fa fa-filter"></i></span>
                                    </button>
                                    </div>
                                </form>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Perencanaan Seluruh Departemen</h4>
                                    <ul class="nav nav-pills mb-3">
                                        <li class="nav-item"><a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">Sedang Ditinjau</a>
                                        </li>
                                        <li class="nav-item"><a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false">Selesai</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content br-n pn">
                                        <div id="navpills-1" class="tab-pane active">
                                            <div class="row align-items-center">
                                                <table class="table table-striped table-bordered zero-configuration" id="plannings">
                                                    <thead>
                                                        <tr>
                                                            <th>Untuk Bulan</th>
                                                            <th>Diajukan Tanggal</th>
                                                            <th>Departemen</th>
                                                            <th>Input Oleh</th>
                                                            <th>Tahun Anggaran</th>
                                                            <th>Persetujuan WR 2</th>
                                                            <th>Persetujuan Rektor</th>
                                                            <th>Total</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($plannings as $value) 
                                                            @if($value->WR_0>0 OR $value->REKTOR_0>0)
                                                        <tr>
                                                            <td>{{\Carbon\Carbon::parse($value->for_bulan)->format('F-Y')}}</td>
                                                            <td>{{\Carbon\Carbon::parse($value->created_at)->format('d-F-Y h:i:s')}}</td>
                                                            <td>{{$value->nama}}</td>
                                                            <td>{{$value->name}}</td>
                                                            <td>{{$value->tahun}}</td>
                                                            <td>
                                                                <span class="badge badge-primary">Sedang Ditinjau {{$value->WR_0}}</span>
                                                                <span class="badge badge-success">Disetujui {{$value->WR_1}}</span>
                                                                <span class="badge badge-danger">Ditolak {{$value->WR_2}}</span>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-primary">Sedang Ditinjau {{$value->REKTOR_0}}</span>
                                                                <span class="badge badge-success">Disetujui {{$value->REKTOR_1}}</span>
                                                                <span class="badge badge-danger">Ditolak {{$value->REKTOR_2}}</span>
                                                            </td>
                                                            <td style="white-space: nowrap;">Pengajuan Rp {{number_format($value->nominal,0,',','.')}}
                                                                <br>Disetujui Rp {{number_format($value->nominal_disetujui,0,',','.')}}
                                                            </td>
                                                            <td><span style="display: flex;"> 
                                                                    <a href="{{route('perencanaan.show',$value->id)}}" data-toggle="tooltip" data-placement="top" title="Rincian"><i class="fa fa-eye color-danger"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;                                          
                                                                    @if (auth()->user()->id==$value->user_id)
                                                                        <form
                                                                            action="{{route('perencanaan.destroy',$value->id)}}"
                                                                            method="POST"> 
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button  type="submit" style="background: none;color: inherit;border: none;padding: 0;font: inherit;cursor: pointer;outline: inherit;" id="submitForm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-close color-danger"></i></button>
                                                                        </form>   
                                                                    @else

                                                                    @endif         
                                                                </span>                         
                                                            </td>
                                                        </tr>
                                                        @else

                                                        @endif
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Untuk Bulan</th>
                                                            <th>Diajukan Tanggal</th>
                                                            <th>Departemen</th>
                                                            <th>Input Oleh</th>
                                                            <th>Tahun Anggaran</th>
                                                            <th>Persetujuan WR II</th>
                                                            <th>Persetujuan Rektor</th>
                                                            <th>Total</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="navpills-2" class="tab-pane">
                                            <div class="row align-items-center">
                                                <table class="table table-striped table-bordered zero-configuration">
                                                    <thead>
                                                        <tr>
                                                            <th>Untuk Bulan</th>
                                                            <th>Diajukan Tanggal</th>
                                                            <th>Departemen</th>
                                                            <th>Input Oleh</th>
                                                            <th>Tahun Anggaran</th>
                                                            <th>Persetujuan WR II</th>
                                                            <th>Persetujuan Rektor</th>
                                                            <th>Total</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($plannings as $value) 
                                                            @if($value->WR_0==0 AND $value->REKTOR_0==0)
                                                        <tr>
                                                            <td>{{\Carbon\Carbon::parse($value->for_bulan)->format('F-Y')}}</td>
                                                            <td>{{\Carbon\Carbon::parse($value->created_at)->format('d-F-Y h:i:s')}}</td>
                                                            <td>{{$value->nama}}</td>
                                                            <td>{{$value->name}}</td>
                                                            <td>{{$value->tahun}}</td>
                                                            <td>
                                                                <span class="badge badge-primary">Sedang Ditinjau {{$value->WR_0}}</span>
                                                                <span class="badge badge-success">Disetujui {{$value->WR_1}}</span>
                                                                <span class="badge badge-danger">Ditolak {{$value->WR_2}}</span>
                                                            </td>
                                                            <td>
                                                            <span class="badge badge-primary">Sedang Ditinjau {{$value->REKTOR_0}}</span>
                                                                <span class="badge badge-success">Disetujui {{$value->REKTOR_1}}</span>
                                                                <span class="badge badge-danger">Ditolak {{$value->REKTOR_2}}</span>
                                                            </td>
                                                            <td style="white-space: nowrap;">Pengajuan Rp {{number_format($value->nominal,0,',','.')}}
                                                                <br>Disetujui Rp {{number_format($value->nominal_disetujui,0,',','.')}}
                                                            </td>
                                                            <td><span style="display: flex;"> 
                                                                    <a href="{{route('perencanaan.show',$value->id)}}" data-toggle="tooltip" data-placement="top" title="Rincian"><i class="fa fa-eye color-danger"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;                                          
                                                                    @if (auth()->user()->id==$value->user_id)
                                                                        <form
                                                                            action="{{route('perencanaan.destroy',$value->id)}}"
                                                                            method="POST"> 
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button  type="submit" style="background: none;color: inherit;border: none;padding: 0;font: inherit;cursor: pointer;outline: inherit;" id="submitForm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-close color-danger"></i></button>
                                                                        </form>   
                                                                    @else

                                                                    @endif         
                                                                </span>                         
                                                            </td>
                                                        </tr>
                                                        @else

                                                        @endif
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Untuk Bulan</th>
                                                            <th>Diajukan Tanggal</th>
                                                            <th>Departemen</th>
                                                            <th>Input Oleh</th>
                                                            <th>Tahun Anggaran</th>
                                                            <th>Persetujuan WR II</th>
                                                            <th>Persetujuan Rektor</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>   
                        @else
                            <div class="card">
                                <div class="card-body">
                                <center>
                                    <h4 class="card-title">Daftar Perencanaan</h4>   
                                    
                                    <h4></h4>
                                    
                                    <div class="bootstrap-modal">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPerencanaan">Tambah Perencanaan</button>
                                </center>
                                        <div class="modal fade" id="addPerencanaan" tabindex="-1" role="dialog" aria-labelledby="addPerencanaanModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addPerencanaanModalLabel">Tambah Perencanaan</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="/perencanaan" method="POST">
                                                        @csrf
                                                            <div class="form-group">
                                                                <label for="for_bulan" class="col-form-label">Untuk Bulan:</label>
                                                                    <div class="input-group">
                                                                        <input id="for_bulan" required type="month" name="for_bulan" class="form-control">
                                                                    </div>   
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
                                    @if($errors->first())
                                        <div class="alert alert-danger" role="alert">
                                    {{$errors->first()}}
                                    </div>
                                    @else
                                    @endif
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration" id="plannings">
                                            <thead>
                                                <tr>
                                                    <th>Untuk Bulan</th>
                                                    <th>Diajukan Tanggal</th>
                                                    <th>Departemen</th>
                                                    <th>Input Oleh</th>
                                                    <th>Tahun Anggaran</th>
                                                    <th>Disetujui WR II</th>
                                                    <th>Disetujui Rektor</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($plannings as $value)
                                                <tr>
                                                    <td>{{\Carbon\Carbon::parse($value->for_bulan)->format('F-Y')}}</td>
                                                    <td>{{\Carbon\Carbon::parse($value->created_at)->format('d-F-Y h:i:s')}}</td>
                                                    <td>{{$value->nama}}</td>
                                                    <td>{{$value->name}}</td>
                                                    <td>{{$value->tahun}}</td>
                                                    <td>
                                                        <span class="badge badge-primary">Sedang Ditinjau {{$value->WR_0}}</span>
                                                        <span class="badge badge-success">Disetujui {{$value->WR_1}}</span>
                                                        <span class="badge badge-danger">Ditolak {{$value->WR_2}}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-primary">Sedang Ditinjau {{$value->REKTOR_0}}</span>
                                                        <span class="badge badge-success">Disetujui {{$value->REKTOR_1}}</span>
                                                        <span class="badge badge-danger">Ditolak {{$value->REKTOR_2}}</span>
                                                    </td>
                                                    <td style="white-space: nowrap;">Pengajuan Rp {{number_format($value->nominal,0,',','.')}}
                                                        <br>Disetujui Rp {{number_format($value->nominal_disetujui,0,',','.')}}
                                                    </td>
                                                    <td><span style="display: flex;"> 
                                                            <a href="{{route('perencanaan.show',$value->id)}}" data-toggle="tooltip" data-placement="top" title="Rincian"><i class="fa fa-eye color-danger"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;                                          
                                                            @if (auth()->user()->id==$value->user_id)
                                                                <form
                                                                    action="{{route('perencanaan.destroy',$value->id)}}"
                                                                    method="POST"> 
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button  type="submit" style="background: none;color: inherit;border: none;padding: 0;font: inherit;cursor: pointer;outline: inherit;" id="submitForm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-close color-danger"></i></button>
                                                                </form>   
                                                            @else

                                                            @endif         
                                                        </span>                         
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Untuk Bulan</th>
                                                    <th>Diajukan Tanggal</th>
                                                    <th>Departemen</th>
                                                    <th>Input Oleh</th>
                                                    <th>Tahun Anggaran</th>
                                                    <th>Disetujui WR II</th>
                                                    <th>Disetujui Rektor</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- #/ container -->
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
@endsection
@push ('budget-script')

<script type="text/javascript">
        $(document).on('click', '#submitForm', function(e){
        e.preventDefault();
        var form = $(this).parents('form');
        Swal.fire({
                title: "Anda Yakin Untuk Menghapus Data Ini?",
                text: "Data Yang Telah Dihapus Tidak Dapat Dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!"
        }).then((result) => {
            if (result.value) {
                form.submit();
            }
        });
    });
$('#plannings').dataTable( {
  "pageLength": 25
} );
</script>

@endpush