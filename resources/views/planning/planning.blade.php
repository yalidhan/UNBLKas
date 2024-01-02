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
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Perencanaan Seluruh Departemen</h4>
                                    <ul class="nav nav-pills mb-3">
                                        <li class="nav-item"><a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">Ditinjau</a>
                                        </li>
                                        <li class="nav-item"><a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false">Disetujui</a>
                                        </li>
                                        <li class="nav-item"><a href="#navpills-3" class="nav-link" data-toggle="tab" aria-expanded="true">Ditolak</a>
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
                                                            <th>Disetujui WR II</th>
                                                            <th>Disetujui Rektor</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($plannings as $value) 
                                                            @if($value->is_approved_wr2==0 or $value->is_approved_rektor==0)
                                                        <tr>
                                                            <td>{{\Carbon\Carbon::parse($value->for_bulan)->format('F-Y')}}</td>
                                                            <td>{{\Carbon\Carbon::parse($value->created_at)->format('d-F-Y h:i:s')}}</td>
                                                            <td>{{$value->departement->nama}}</td>
                                                            <td>{{$value->user->name}}</td>
                                                            <td>{{$value->budget->tahun}}</td>
                                                            <td>
                                                                @if($value->is_approved_wr2==0)
                                                                    <a href="#" class="badge badge-primary">Sedang Ditinjau</a>
                                                                @elseif($value->is_approved_wr2==1)
                                                                    <a href="#" class="badge badge-success">Disetujui</a>
                                                                @elseif($value->is_approved_wr2==2)
                                                                    <a href="#" class="badge badge-danger">Ditolak</a>   
                                                                @endif 
                                                            </td>
                                                            <td>
                                                                @if($value->is_approved_rektor==0)
                                                                    <a href="#" class="badge badge-primary">Sedang Ditinjau</a>
                                                                @elseif($value->is_approved_rektor==1)
                                                                    <a href="#" class="badge badge-success">Disetujui</a>
                                                                @elseif($value->is_approved_rektor==2)
                                                                    <a href="#" class="badge badge-danger">Ditolak</a>   
                                                                @endif 
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
                                                            <th>Disetujui WR II</th>
                                                            <th>Disetujui Rektor</th>
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
                                                            <th>Disetujui WR II</th>
                                                            <th>Disetujui Rektor</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($plannings as $value) 
                                                            @if(($value->is_approved_wr2==1 and $value->is_approved_rektor==1) or ($value->is_approved_wr2==2 and $value->is_approved_rektor==1) )
                                                        <tr>
                                                            <td>{{\Carbon\Carbon::parse($value->for_bulan)->format('F-Y')}}</td>
                                                            <td>{{\Carbon\Carbon::parse($value->created_at)->format('d-F-Y h:i:s')}}</td>
                                                            <td>{{$value->departement->nama}}</td>
                                                            <td>{{$value->user->name}}</td>
                                                            <td>{{$value->budget->tahun}}</td>
                                                            <td>
                                                                @if($value->is_approved_wr2==0)
                                                                    <a href="#" class="badge badge-primary">Sedang Ditinjau</a>
                                                                @elseif($value->is_approved_wr2==1)
                                                                    <a href="#" class="badge badge-success">Disetujui</a>
                                                                @elseif($value->is_approved_wr2==2)
                                                                    <a href="#" class="badge badge-danger">Ditolak</a>   
                                                                @endif 
                                                            </td>
                                                            <td>
                                                                @if($value->is_approved_rektor==0)
                                                                    <a href="#" class="badge badge-primary">Sedang Ditinjau</a>
                                                                @elseif($value->is_approved_rektor==1)
                                                                    <a href="#" class="badge badge-success">Disetujui</a>
                                                                @elseif($value->is_approved_rektor==2)
                                                                    <a href="#" class="badge badge-danger">Ditolak</a>   
                                                                @endif 
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
                                                            <th>Disetujui WR II</th>
                                                            <th>Disetujui Rektor</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="navpills-3" class="tab-pane">
                                            <div class="row align-items-center">
                                                <table class="table table-striped table-bordered zero-configuration">
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
                                                            @if(($value->is_approved_wr2==2 and $value->is_approved_rektor==2) or ($value->is_approved_wr2==1 and $value->is_approved_rektor==2))
                                                        <tr>
                                                            <td>{{\Carbon\Carbon::parse($value->for_bulan)->format('F-Y')}}</td>
                                                            <td>{{\Carbon\Carbon::parse($value->created_at)->format('d-F-Y h:i:s')}}</td>
                                                            <td>{{$value->departement->nama}}</td>
                                                            <td>{{$value->user->name}}</td>
                                                            <td>{{$value->budget->tahun}}</td>
                                                            <td>
                                                                @if($value->is_approved_wr2==0)
                                                                    <a href="#" class="badge badge-primary">Sedang Ditinjau</a>
                                                                @elseif($value->is_approved_wr2==1)
                                                                    <a href="#" class="badge badge-success">Disetujui</a>
                                                                @elseif($value->is_approved_wr2==2)
                                                                    <a href="#" class="badge badge-danger">Ditolak</a>   
                                                                @endif 
                                                            </td>
                                                            <td>
                                                                @if($value->is_approved_rektor==0)
                                                                    <a href="#" class="badge badge-primary">Sedang Ditinjau</a>
                                                                @elseif($value->is_approved_rektor==1)
                                                                    <a href="#" class="badge badge-success">Disetujui</a>
                                                                @elseif($value->is_approved_rektor==2)
                                                                    <a href="#" class="badge badge-danger">Ditolak</a>   
                                                                @endif 
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
                                                            <th>Disetujui WR II</th>
                                                            <th>Disetujui Rektor</th>
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
                                                    <td>{{$value->departement->nama}}</td>
                                                    <td>{{$value->user->name}}</td>
                                                    <td>{{$value->budget->tahun}}</td>
                                                    <td>
                                                        @if($value->is_approved_wr2==0)
                                                            <a href="#" class="badge badge-primary">Sedang Ditinjau</a>
                                                        @elseif($value->is_approved_wr2==1)
                                                            <a href="#" class="badge badge-success">Disetujui</a>
                                                        @elseif($value->is_approved_wr2==2)
                                                            <a href="#" class="badge badge-danger">Ditolak</a>   
                                                        @endif 
                                                    </td>
                                                    <td>
                                                        @if($value->is_approved_rektor==0)
                                                            <a href="#" class="badge badge-primary">Sedang Ditinjau</a>
                                                        @elseif($value->is_approved_rektor==1)
                                                            <a href="#" class="badge badge-success">Disetujui</a>
                                                        @elseif($value->is_approved_rektor==2)
                                                            <a href="#" class="badge badge-danger">Ditolak</a>   
                                                        @endif 
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