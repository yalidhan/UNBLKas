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
                            OR auth()->user()->jabatan=='SPI'
                            OR auth()->user()->departement_id==1
                            )

                            <div class="col-md-3">
                                <form action="report-perencanaan/cetak" method="get">                                                        
                                    <div class="form-group">
                                        <label for="periode" class="col-form-label">Laporan Rekapitulasi Perencanaan:<br>Untuk Minggu</label>
                                            <div class="input-group">
                                                <input id="periode" type="week" name="periode" required class="form-control">
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
                                                <table class="display table-responsive table table-striped table-bordered" id="plannings">
                                                    <thead>
                                                        <tr>
                                                            <th>Untuk Minggu</th>
                                                            <th>Diajukan Tanggal</th>
                                                            <th>Departemen</th>
                                                            <th>Input Oleh</th>
                                                            <th>Tahun Anggaran</th>
                                                            <th>Persetujuan WR 2</th>
                                                            <th>Persetujuan Rektor</th>
                                                            <th>Total</th>
                                                            <th>Status Pembayaran</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($plannings as $value) 
                                                            @if($value->WR_0>0 OR $value->REKTOR_0>0)
                                                        <tr>
                                                            <td>{{$value->for_bulan}}</td>
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
                                                                @php
                                                                    $setujuBayar=DB::select("
                                                                        SELECT sum(nominal_disetujui) AS total_setujubayar
                                                                        FROM planning_details
                                                                        WHERE planning_id=$value->id and status='Paid'");
                                                                @endphp
                                                                <br>Dibayar Rp {{number_format($setujuBayar[0]->total_setujubayar,0,',','.')}}
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-primary">Pending {{$value->STATUS_0}}</span>
                                                                <span class="badge badge-success">Paid {{$value->STATUS_1}}</span>
                                                                <span class="badge badge-danger">Unpaid {{$value->STATUS_2}}</span>
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
                                                        <th><input type="text" placeholder="Search Untuk Minggu"></th>
                                                            <th><input type="text" placeholder="Search Diajukan Tanggal"></th>
                                                            <th><input type="text" placeholder="Search Departemen"></th>
                                                            <th><input type="text" placeholder="Search Input Oleh"></th>
                                                            <th><input type="text" placeholder="Search Tahun Anggaran"></th>
                                                            <th><input type="text" placeholder="Search Persetujuan WR II"></th>
                                                            <th><input type="text" placeholder="Search Persetujuan Rektor"></th>
                                                            <th><input type="text" placeholder="Search Total"></th>
                                                            <th><input type="text" placeholder="Status Pembayaran"></th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="navpills-2" class="tab-pane">
                                            <div class="row align-items-center">
                                                <table class="table-responsive table table-striped table-bordered" id="plannings2">
                                                    <thead>
                                                        <tr>
                                                            <th>Untuk Minggu</th>
                                                            <th>Diajukan Tanggal</th>
                                                            <th>Departemen</th>
                                                            <th>Input Oleh</th>
                                                            <th>Tahun Anggaran</th>
                                                            <th>Persetujuan WR II</th>
                                                            <th>Persetujuan Rektor</th>
                                                            <th>Total</th>
                                                            <th>Status Pembayaran</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($plannings as $value) 
                                                                @if($value->WR_0==0 AND $value->REKTOR_0==0)
                                                        <tr>
                                                            <td>{{$value->for_bulan}}</td>
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
                                                                @php
                                                                    $setujuBayar=DB::select("
                                                                        SELECT sum(nominal_disetujui) AS total_setujubayar
                                                                        FROM planning_details
                                                                        WHERE planning_id=$value->id and status='Paid'");
                                                                @endphp
                                                                <br>Dibayar Rp {{number_format($setujuBayar[0]->total_setujubayar,0,',','.')}}
                                                                
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-primary">Pending {{$value->STATUS_0}}</span>
                                                                <span class="badge badge-success">Paid {{$value->STATUS_1}}</span>
                                                                <span class="badge badge-danger">Unpaid {{$value->STATUS_2}}</span>
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
                                                            <th><input type="text" placeholder="Search Untuk Minggu"></th>
                                                            <th><input type="text" placeholder="Search Diajukan Tanggal"></th>
                                                            <th><input type="text" placeholder="Search Departemen"></th>
                                                            <th><input type="text" placeholder="Search Input Oleh"></th>
                                                            <th><input type="text" placeholder="Search Tahun Anggaran"></th>
                                                            <th><input type="text" placeholder="Search Persetujuan WR II"></th>
                                                            <th><input type="text" placeholder="Search Persetujuan Rektor"></th>
                                                            <th><input type="text" placeholder="Search Total"></th>
                                                            <th><input type="text" placeholder="Status Pembayaran"></th>
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
                                        @if (auth()->user()->jabatan=='Admin Departemen'
                                            OR auth()->user()->jabatan=='Bendahara Operasional'
                                            OR auth()->user()->jabatan=='Dekan'
                                            OR auth()->user()->jabatan=='Kaprodi'   
                                        )
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPerencanaan">Tambah Perencanaan</button>
                                        @else
                                        @endif
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
                                                                <label for="for_bulan" class="col-form-label">Untuk Minggu:</label>
                                                                    <div class="input-group">
                                                                        <input id="for_bulan" required type="week" name="for_bulan" class="form-control">
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
                                        <table class="display table table-striped table-bordered" style="width:100%" id="plannings">
                                            <thead>
                                                <tr>
                                                    <th>Untuk Minggu</th>
                                                    <th>Diajukan Tanggal</th>
                                                    <!-- <th>Departemen</th> -->
                                                    <th>Input Oleh</th>
                                                    <th>Tahun Anggaran</th>
                                                    <th>Disetujui WR II</th>
                                                    <th>Disetujui Rektor</th>
                                                    <th>Total</th>
                                                    <th>Status Pembayaran</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($plannings as $value)
                                                <tr>
                                                    <td>{{$value->for_bulan}}</td>
                                                    <td>{{\Carbon\Carbon::parse($value->created_at)->format('d-F-Y h:i:s')}}</td>
                                                    <!-- <td>{{$value->nama}}</td> -->
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
                                                            @php
                                                                $setujuBayar=DB::select("
                                                                    SELECT sum(nominal_disetujui) AS total_setujubayar
                                                                    FROM planning_details
                                                                    WHERE planning_id=$value->id and status='Paid'");
                                                            @endphp
                                                        <br>Dibayar Rp {{number_format($setujuBayar[0]->total_setujubayar,0,',','.')}}
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-primary">Pending {{$value->STATUS_0}}</span>
                                                        <span class="badge badge-success">Paid {{$value->STATUS_1}}</span>
                                                        <span class="badge badge-danger">Unpaid {{$value->STATUS_2}}</span>
                                                    </td>
                                                    <td><span style="display: flex;"> 
                                                            <a href="{{route('perencanaan.show',$value->id)}}" data-toggle="tooltip" data-placement="top" title="Rincian"><i class="fa fa-eye color-danger"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;                                          
                                                            @if (auth()->user()->id==$value->user_id)
                                                                @if ($value->accounts==0)
                                                                        <form
                                                                            action="{{route('perencanaan.destroy',$value->id)}}"
                                                                            method="POST"> 
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button  type="submit" style="background: none;color: inherit;border: none;padding: 0;font: inherit;cursor: pointer;outline: inherit;" id="submitForm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-close color-danger">1</i></button>
                                                                        </form>
                                                                @else

                                                                @endif
                                                                
                                                                @if ($value->REKTOR_0!=0 && ($value->REKTOR_1==0 && $value->REKTOR_2==0))
                                                                    <form
                                                                        action="{{route('perencanaan.destroy',$value->id)}}"
                                                                        method="POST"> 
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button  type="submit" style="background: none;color: inherit;border: none;padding: 0;font: inherit;cursor: pointer;outline: inherit;" id="submitForm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-close color-danger">2</i></button>
                                                                    </form>
                                                                @else
                                                                @endif
                                                            @else

                                                            @endif         
                                                        </span>                         
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                <th>
                                                            <input type="text" placeholder="Search Untuk Minggu"></th>
                                                            <th><input type="text" placeholder="Search Diajukan Tanggal"></th>
                                                            <!-- <th><input type="text" placeholder="Search Departemen"></th> -->
                                                            <th><input type="text" placeholder="Search Input Oleh"></th>
                                                            <th><input type="text" placeholder="Search Tahun Anggaran"></th>
                                                            <th><input type="text" placeholder="Search Persetujuan WR II"></th>
                                                            <th><input type="text" placeholder="Search Persetujuan Rektor"></th>
                                                            <th><input type="text" placeholder="Search Total"></th>
                                                            <th><input type="text" placeholder="Status Pembayaran"></th>
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
// $('#plannings').dataTable( {
//   "pageLength": 10
// } );
</script>
<script>
$(document).ready(function () {
    // Initialize DataTable
    var table = $('#plannings').DataTable({
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;
                $('input', this.footer()).on('keyup change', function () {
                    if (column.search() !== this.value) {
                        column.search(this.value).draw();
                    }
                });
            });
        }
    });
});
$(document).ready(function () {
    // Initialize DataTable
    var table = $('#plannings2').DataTable({
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;
                $('input', this.footer()).on('keyup change', function () {
                    if (column.search() !== this.value) {
                        column.search(this.value).draw();
                    }
                });
            });
        }
    });
});
</script>
@endpush