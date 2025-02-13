@extends('master')

@section('content')
@push('fixed_header_perencanaan')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
@endpush
@push('rincian_planning-style')
    .details-control {
            cursor: pointer;
            text-align: center;
            width: 30px;
        }
    .hidden-row {
            display: none;
        }
    .arrow-icon {
            font-size: 18px;
            transition: transform 0.2s;
        }
    .shown .arrow-icon {
            transform: rotate(90deg); /* Rotate when expanded */
        } 
       
@foreach ($showDetailPlanning as $value)
    #jumlah{{$value->id}}, #labelJumlah{{$value->id}}{
        display: none;
    }

    #setuju{{$value->id}}:checked ~ #jumlah{{$value->id}} {
        display: flex;
    }
    #setuju{{$value->id}}:checked ~ #labelJumlah{{$value->id}} {
        display: flex;
    }

@endforeach
@endpush
<div class="content-body">
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/perencanaan">Perencanaan</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Rincian Perencanaan</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h5 class="card-title">Rincian Perencanaan</h5></div>
                        <div class="card text-left" style="font-weight:bold;font-size:120%;">
                        <div class="card-header">
                            Data Perencanaan
                        </div>
                    <div class="card-body">
                        <div class="table-responsive"> 
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>Departemen</td>
                                                <td>&nbsp;:</td>
                                                <td style="white-space: nowrap;">&nbsp;{{$showPlanning[0]->departement}}</td>
                                            </tr>
                                            <tr>
                                                <td>Diajukan Pada</td>
                                                <td>&nbsp;:</td>
                                                <td style="white-space: nowrap;">&nbsp;{{\Carbon\Carbon::parse($showPlanning[0]->created_at)->format('d-F-Y h:i:s')}}</td>
                                            </tr>
                                            <tr>
                                                <td>Untuk Minggu</td>
                                                <td>&nbsp;:</td>
                                                <td style="white-space: nowrap;">&nbsp;{{$showPlanning[0]->for_bulan}}</td>
                                            </tr>
                                            <tr>
                                                <td style="white-space: nowrap;">Total Perencanaan</td>
                                                <td>&nbsp;:</td>
                                                <td style="white-space: nowrap;">&nbsp;Rp {{number_format($showPlanning[0]->total_perencanaan,0,',','.')}}</td>
                                            </tr>
                                            <tr>
                                                <td style="white-space: nowrap;">Total Disetujui</td>
                                                <td>&nbsp;:</td>
                                                <td style="white-space: nowrap;">&nbsp;Rp {{number_format($showPlanning[0]->total_disetujui,0,',','.')}}</td>
                                            </tr>
                                            <tr>
                                                <td style="white-space: nowrap;">Total Setuju Bayar</td>
                                                <td>&nbsp;:</td>
                                                <td style="white-space: nowrap;">&nbsp;Rp {{number_format($setujuBayar[0]->total_setujubayar,0,',','.')}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                        </div>
                        <div class="col-md-3">
                                <form action="/report-perencanaan/cetak" method="get">                                                        
                                    <br>
                                    <input id="periode" type="hidden" value="{{$showPlanning[0]->for_bulan}}" name="periode" required class="form-control">
                                    <input type="hidden" name="departement" value="{{$showPlanning[0]->departement_id}}">
                                    <input type="hidden" name="p_id" value="{{$showPlanning[0]->id}}">
                                    <button type="submit" class="btn mb-1 btn-success">Cetak Perencanaan<span class="btn-icon-right"><i class="fa fa-filter"></i></span>
                                    </button>
                                </form>
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                            @if (auth()->user()->id==$showPlanning[0]->user_id)
                            <button type="button" class="btn mb-1 btn-rounded btn-warning" data-toggle="modal" data-target="#edit"><span class="btn-icon-left" style="background: #ffbe88;"><i class="fa fa-edit color-warning"></i></span>Edit Data</button>
                                <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('perencanaan.update',$showPlanning[0]->id)}}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                            <label for="for_bulan_edit" class="col-form-label">Untuk Minggu:</label>
                                                            <input value="{{$showPlanning[0]->for_bulan}}" required value="{{ old('for_bulan_edit')}}" name="for_bulan_edit" type="week" class="form-control" id="for_bulan_edit">
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
                                @else

                                @endif
                    </div>                         
                </div>
                </div>
                    @if($errors->first())
                        <div class="alert alert-danger" role="alert">
                        {{$errors->first()}}
                        </div>
                        @else
                    @endif
                </div>
                <div class="table-responsive"> 
            <table id="r_plannings2" class="table table-striped table-bordered zero-configuration" style="width:100%;">
                <thead>
                    <tr>
                        <th  ></th>
                        <th class="hidden-row"  >Jenis</th>
                        @if ($showPlanning[0]->departement_id==6)
                        <th  >Kelompok Rektorat</th>
                        @else
                        @endif
                        <th  >Kegiatan</th>
                        <th  >Keterangan Anggaran Tahun Berjalan</th>
                        <th class="hidden-row"  >Penanggung Jawab Kegiatan</th>
                        <th  >Jumlah Anggaran</th>
                        <th class="hidden-row"  >Satuan Ukur Kinerja Kegiatan</th>
                        <th class="hidden-row"  >Target Kinerja(Target Output)</th>
                        <th class="hidden-row"  >Capaian Kinerja(Realisasi Output)</th>
                        <th class="hidden-row"  >Target Waktu Pelaksanaan</th>
                        <th class="hidden-row"  >Capaian Target Waktu Penyelesaian</th>
                        <th>Jml. Anggaran Disetujui</th>
                        <th>Disetujui WR II</th>
                        <th class="hidden-row"  >Catatan WR II</th>
                        <th>Disetujui Rektor</th>
                        <th>Status Pembayaran</th>
                        <th class="hidden-row">Catatan Rektor</th>
                        <th class="hidden-row">Catatan Yayasan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($showDetailPlanning as $value)
                    <tr>
                        <td class="details-control"><span class="arrow-icon">▶</span></td>
                        <td class="hidden-row">RKA</td>
                        @if ($showPlanning[0]->departement_id==6)
                        <td>{{$value->group_rektorat}}</td>
                        @else
                        @endif
                        <td>{{$value->nama}}</td>
                        <td>
                            @php
                            $budget_id=$showPlanning[0]->budget_id;
                            $account_id=$value->account_id;
                            $departement_id=$showPlanning[0]->departement_id;
                            $year=Str::substr($showPlanning[0]->for_bulan,0,4);
                                $keterangan=DB::select(
                                    "SELECT * FROM budget_details WHERE budget_id=$budget_id and account_id=$account_id"
                                );
                                $realisasi=DB::select(
                                    "   SELECT t.id,t.tanggal,d.dk, d.account_id,sum(d.nominal) AS total,
                                            a.id,a.no,a.kelompok 
                                        FROM transactions t 
                                        LEFT JOIN transaction_details d 
                                            ON t.id = d.transaction_id 
                                        LEFT JOIN accounts a 
                                            ON d.account_id = a.id 
                                        WHERE departement_id=$departement_id AND tanggal BETWEEN '$year-01-01' and '$year-12-01' AND account_id=$account_id AND dk=2    
                                    "
                                );
                                $realisasiDebit=DB::select(
                                    "   SELECT t.id,t.tanggal,d.dk, d.account_id,sum(d.nominal) AS total,
                                            a.id,a.no,a.kelompok 
                                        FROM transactions t 
                                        LEFT JOIN transaction_details d 
                                            ON t.id = d.transaction_id 
                                        LEFT JOIN accounts a 
                                            ON d.account_id = a.id 
                                        WHERE departement_id=$departement_id AND tanggal BETWEEN '$year-01-01' and '$year-12-01' AND account_id=$account_id AND dk=1    
                                    "
                                );
                                if (empty($realisasiDebit)) {
                                    $realisasiDebit = 0;
                                } else {
                                    // Extract the total from the result
                                    $realisasiDebit = $realisasiDebit[0]->total;
                                }
                                $realisasiReal=$realisasi[0]->total-$realisasiDebit;


                            @endphp
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#keterangan{{$value->id}}">
                            Keterangan 
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="keterangan{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="keterangan{{$value->id}}Title" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="keterangan{{$value->id}}Title">Keterangan Anggaran</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        {!!$keterangan[0]->keterangan!!}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                    </div>
                                </div>
                            </div>    

                        </td>
                        <td class="hidden-row">{{$value->pj}}</td>
                        <td style="white-space: nowrap;">Pengajuan <u>Rp {{number_format($value->nominal,0,',','.')}}</u>
                            <span>
                                <br>Anggaran {{$year}} Rp {{number_format($keterangan[0]->nominal,0,',','.')}}
                                <br>Realisasi Rp {{number_format($realisasiReal,0,',','.')}} 
                                    (<?php if($keterangan[0]->nominal!=0){echo number_format(($realisasiReal/$keterangan[0]->nominal)*100, 2, '.', ',');}else{echo"0";}?> %)
                            </span>
                        </td>
                        <td class="hidden-row">
                            @if (!empty($value->satuan_ukur_kinerja))
                                <p>Judul File : {{$value->judul_file}}</p>
                                <button type="button" class="btn btn-primary"                            
                                onclick="window.open('{{$value->satuan_ukur_kinerja}}', 
                                'newwindow', 
                                'width=680,height=780'); 
                                return false;">                                
                                Link Dokumen Satuan Ukur Kinerja Kegiatan
                                </button>
                            @else
                            @endif
                        </td>
                        <td class="hidden-row">{{$value->target_kinerja}}</td>
                        <td class="hidden-row">{{$value->capaian_kinerja}}</td>
                        <td class="hidden-row">{{$value->waktu_pelaksanaan}}</td>
                        <td class="hidden-row">{{$value->capaian_target_waktu}}</td>
                        <td style="white-space: nowrap;"> Rp {{number_format($value->nominal_disetujui,0,',','.')}}</td>
                        <td>
                            @if($value->approved_by_wr2==0)
                                <a href="#" class="badge badge-primary">Sedang Ditinjau</a>
                            @elseif($value->approved_by_wr2==1)
                                <a href="#" class="badge badge-success">Disetujui</a>
                            @elseif($value->approved_by_wr2==2)
                                <a href="#" class="badge badge-danger">Ditolak</a>   
                            @elseif($value->approved_by_wr2==3)
                                <a href="#" class="badge badge-warning">Revisi</a>                                  
                            @endif 
                        </td>
                        <td class="hidden-row">{{$value->note_wr2}}</td>
                        <td>
                            @if($value->approved_by_rektor==0)
                                <a href="#" class="badge badge-primary">Sedang Ditinjau</a>
                            @elseif($value->approved_by_rektor==1)
                                <a href="#" class="badge badge-success">Disetujui</a>
                            @elseif($value->approved_by_rektor==2)
                                <a href="#" class="badge badge-danger">Ditolak</a>
                            @elseif($value->approved_by_rektor==3)
                                <a href="#" class="badge badge-warning">Revisi</a>      
                            @endif 
                        </td>
                        <td class="hidden-row">{{$value->note_rektor}}</td>
                        <td class="hidden-row">{{$value->note}}</td>
                        <td>
                        @if($value->status=="Pending")
                                <a href="#" class="badge badge-primary">Pending
                                    </a>
                                    <br>Updated at
                                    <br>{{\Carbon\Carbon::parse($value->updated_at)->format('d-M-Y H:i:s')}}
                            @elseif($value->status=="Paid")
                                <a href="#" class="badge badge-success">Paid
                                    </a>
                                    <br>Updated at
                                    <br>{{\Carbon\Carbon::parse($value->updated_at)->format('d-M-Y H:i:s')}}
                            @elseif($value->status=="Unpaid")
                                <a href="#" class="badge badge-danger">Unpaid
                                    </a>
                                    <br>Updated at  
                                    <br>{{\Carbon\Carbon::parse($value->updated_at)->format('d-M-Y H:i:s')}}
                            @endif 
                        <td><span style="display: flex;">
                            @if (auth()->user()->departement_id==1)
                            <div class="bootstrap-modal">
                                        <button type="button" class="btn mb-1 btn-rounded btn-light" data-toggle="modal" data-target="#editStatus{{$value->id}}"><i class="fa fa-money fa-2x" style="color:green;" data-toggle="tooltip" data-placement="top" title="Ubah Status Pembayaran"></i></button>
                                        <div class="modal fade" id="editStatus{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="editStatusModalLabel{{$value->id}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editStatusModalLabel{{$value->id}}">Ubah Status Pembayaran </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('updateRincianP',$value->id)}}" method="POST">
                                                        @csrf
                                                        @method('put')
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <span>
                                                                        Mata Anggaran <b>"{{$value->nama}}"</b>
                                                                        <br>Anggaran {{$year}} Rp {{number_format($keterangan[0]->nominal,0,',','.')}}
                                                                        <br>Realisasi Rp {{number_format($realisasi[0]->total,0,',','.')}} (<?php if($keterangan[0]->nominal!=0){echo number_format(($realisasi[0]->total/$keterangan[0]->nominal)*100, 2, '.', ',');}else{echo"0";}?> %)
                                                                        <br>Pengajuan Rp {{number_format($value->nominal,0,',','.')}}
                                                                        <br>Disetujui WRII&Rektor Rp {{number_format($value->nominal_disetujui,0,',','.')}}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                            <label class="col-form-label">Status Pembayaran:</label><br>
                                                                <!-- <label class="radio-inline mr-3"> -->
                                                                    <input  type="radio" value="Pending" name="status" required {{$value->status == "Pending" ?'checked':''}}> Pending</label>
                                                                <!-- <label class="radio-inline mr-3"> -->
                                                                    <input  type="radio" value="Paid" name="status" {{$value->status == "Paid" ?'checked':''}}> Paid</label>
                                                                    <input  type="radio" value="Unpaid" name="status" {{$value->status == "Unpaid" ?'checked':''}}> Unpaid</label>
                                                                <!-- </div> -->
                                                           <!-- <div class="form-group"> -->
                                                                <br>
                                                                <label class="col-form-label">Catatan Yayasan:</label>
                                                                <input value="{{$value->note}}" name="note" type="text" maxlength="255" class="form-control" style="padding:0.35px 0.35px 0.35px 5px !important" >
                                                            </div>                                                          
                                                            <!-- <div class="form-group"> -->
                                                            <!-- </div>  -->
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

                                <!-- <form method="POST" action="{{route('updateRincianP',$value->id)}}"> 
                                    @csrf
                                    @method('PUT')
                                    <button  type="submit" style="background: none;color: green;border: none;padding: 0;font: inherit;cursor: pointer;outline: inherit;" id="status_bayar" data-toggle="tooltip" data-placement="top" title="Ubah Status Pembayaran"><i class="fa fa-money fa-2x"></i></button>
                                </form> -->
                            @endif
                            @if (auth()->user()->id==$showPlanning[0]->user_id )
                                <!-- <a href="#" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil color-muted m-r-5"></i></a>&nbsp;&nbsp;&nbsp;&nbsp; -->
                                @if ($value->approved_by_wr2==0 ||$value->approved_by_wr2==3 ||$value->approved_by_rektor==3)
                                <button type="button" style="background: none;color: inherit;border: none;padding: 0;font: inherit;cursor: pointer;outline: inherit;" data-toggle="modal" data-target="#editRincian{{$value->id}}"><i class="fa fa-pencil color-muted m-r-5" data-toggle="tooltip" data-placement="top" title="Edit Rincian"></i></button>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <div class="modal fade bd-example-modal-lg" id="editRincian{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="editRincianModalLabel{{$value->id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editRincianModalLabel{{$value->id}}">Edit Data Rincian</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('updateRincianP',$value->id)}}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                        <div class="form-group">
                                                            <label for="akun_rincian_edit{{$value->id}}" class="col-form-label">Akun</label>
                                                            <select id="akun_rincian_edit{{$value->id}}" data-width="100%" name="akun_rincian_edit" class="form-control" required>
                                                                @foreach($accountList as $accountvalue)
                                                                    <option value="{{ $accountvalue->account_id }}"{{$value->account_id == $accountvalue->account_id  ? 'selected' : ''}}>({{$accountvalue->tipe}} || {{ $accountvalue->kelompok}}) {{ $accountvalue->nama}}</option>
                                                                    <!-- <option value="{{ $accountvalue->id }}">{{$accountvalue->tipe}} || {{ $accountvalue->nama}}</option> -->
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="jumlah_anggaran_tambah_rincian_edit{{$value->id}}" class="col-form-label">Nominal :</label>
                                                            <input style="padding:0px 0px 0px 5px;" required name="jumlah_anggaran_tambah_rincian_edit" value="{{$value->nominal}}" maxlength="14" type="text" class="form-control" id="jumlah_anggaran_tambah_rincian_edit{{$value->id}}" placeholder="Rp">
                                                        </div>
                                                        @if ($showPlanning[0]->departement_id==6)
                                                        <div class="form-group">
                                                            <label for="group_rektorat" class="col-form-label">{{ __('Group Rektorat:') }}</label>
                                                                <select style="padding:0px 0px 0px 5px;" id="group_rektorat" name="group_rektorat" class="form-control @error('group_rektorat') is-invalid @enderror" required autocomplete="group_rektorat" autofocus>
                                                                <option value="" selected disabled hidden>Pilih Group Rektorat</option>
                                                                    <option value="Wakil Rektor I" {{ $value->group_rektorat == "Wakil Rektor I" ?'selected':'' }}>Wakil Rektor I</option>
                                                                    <option value="Wakil Rektor II" {{ $value->group_rektorat == "Wakil Rektor II" ?'selected':'' }}>Wakil Rektor II</option>
                                                                    <option value="Wakil Rektor III" {{ $value->group_rektorat == "Wakil Rektor III" ?'selected':'' }}>Wakil Rektor III</option>
                                                                    <option value="Laboratorium" {{ $value->group_rektorat == "Laboratorium" ?'selected':'' }}>Laboratorium</option>
                                                                    <option value="Perpustakaan" {{ $value->group_rektorat == "Perpustakaan" ?'selected':'' }}>Perpustakaan</option>
                                                                    <option value="LPPM" {{ $value->group_rektorat == "LPPM" ?'selected':'' }}>LPPM</option>
                                                                    <option value="LPMI" {{ $value->group_rektorat == "LPMI" ?'selected':'' }}>LPMI</option>
                                                                </select>                                             
                                                        </div>
                                                        @else
                                                        @endif
                                                        <div class="form-group">
                                                            <label for="pj" class="col-form-label">Penanggungjawab Kegiatan :</label>
                                                            <input style="padding:0px 0px 0px 5px;" required name="pj" type="text" value="{{$value->pj}}" maxlength="255" class="form-control" id="pj" placeholder="Penanggungjawab">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="satuan_ukur_kinerja" class="col-form-label">Satuan Ukur Kinerja :</label>
                                                            <textarea style="padding:0px 0px 0px 5px;" name="satuan_ukur_kinerja" rows="2" cols="50" maxlength="255" class="form-control" id="satuan_ukur_kinerja" placeholder="Link Google Drive">{{$value->satuan_ukur_kinerja}}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="judul_file" class="col-form-label">Judul File :</label>
                                                            <input style="padding:0px 0px 0px 5px;" name="judul_file" type="text" value="{{$value->judul_file}}" maxlength="255" class="form-control" id="pj" placeholder="Judul File">
                                                        </div>   
                                                        <div class="form-group">
                                                            <label for="target_kinerja" class="col-form-label">Target Kinerja(Target Output) :</label>
                                                            <textarea style="padding:0px 0px 0px 5px;" name="target_kinerja" rows="2" cols="50" maxlength="255" class="form-control" id="target_kinerja" placeholder="Target Output">{{$value->target_kinerja}}</textarea>
                                                        </div>             
                                                        <div class="form-group">
                                                            <label for="capaian_kinerja" class="col-form-label">Capaian Kinerja(Realisasi Output) :</label>
                                                            <textarea style="padding:0px 0px 0px 5px;" name="capaian_kinerja" rows="2" cols="50" maxlength="255" class="form-control" id="capaian_kinerja" placeholder="Realisasi Output">{{$value->capaian_kinerja}}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="target_waktu_pelaksanaan" class="col-form-label">Target Waktu Pelaksanaan:</label>
                                                            <textarea style="padding:0px 0px 0px 5px;" name="target_waktu_pelaksanaan" rows="2" cols="50" maxlength="255" class="form-control" id="target_waktu_pelaksanaan" placeholder="Waktu Pelaksanaan">{{$value->waktu_pelaksanaan}}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="capaian_target_waktu_penyelesaian" class="col-form-label">Capaian Target Waktu Penyelesaian:</label>
                                                            <textarea style="padding:0px 0px 0px 5px;" name="capaian_target_waktu_penyelesaian" rows="2" cols="50" maxlength="255" class="form-control" id="capaian_target_waktu_penyelesaian" placeholder="Target Waktu Penyelesaian">{{$value->capaian_target_waktu}}</textarea>
                                                        </div>    
                                                        <input type="hidden" name="planning_id" value="{{$showPlanning[0]->id}}">                                    
                                                        <input type="hidden" name="current_account" value="{{$value->account_id}}">
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                    <form method="POST" action="{{route('destroyRincianP',$value->id)}}"> 
                                    @csrf
                                    @method('DELETE')
                                    <button  type="submit" style="background: none;color: inherit;border: none;padding: 0;font: inherit;cursor: pointer;outline: inherit;" id="submitForm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-close color-danger"></i></button>
                                </form>                               
                                @else
                                @endif   

                            @else
                            @endif         
                            </span>
                            @if (auth()->user()->jabatan=="Wakil Rektor II" && $value->approved_by_rektor==0)
                            <div class="bootstrap-modal">
                                        <button type="button" class="btn mb-1 btn-rounded btn-warning" data-toggle="modal" data-target="#editPersetujuanWRII{{$value->id}}">Persetujuan WR II</button>
                                        <div class="modal fade" id="editPersetujuanWRII{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="editPersetujuanWRIIModalLabel{{$value->id}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editPersetujuanWRIIModalLabel{{$value->id}}">Persetujuan Wakil Rektor II</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('updateRincianP',$value->id)}}" method="POST">
                                                        @csrf
                                                        @method('put')
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <span>
                                                                        Mata Anggaran <b>"{{$value->nama}}"</b>
                                                                        <br>Anggaran {{$year}} Rp {{number_format($keterangan[0]->nominal,0,',','.')}}
                                                                        <br>Realisasi Rp {{number_format($realisasi[0]->total,0,',','.')}} (<?php if($keterangan[0]->nominal!=0){echo number_format(($realisasi[0]->total/$keterangan[0]->nominal)*100, 2, '.', ',');}else{echo"0";}?> %)
                                                                        <br>Pengajuan Rp {{number_format($value->nominal,0,',','.')}}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                            <label class="col-form-label">Persetujuan:</label><br>
                                                                <!-- <label class="radio-inline mr-3"> -->
                                                                    <input id="setuju{{$value->id}}" type="radio" value="1" name="persetujuan" required> Setuju</label>
                                                                <!-- <label class="radio-inline mr-3"> -->
                                                                    <input id="tidak" type="radio" value="2" name="persetujuan"> Tidak Setuju</label>
                                                                    <input type="radio" value="3" name="persetujuan"> Revisi</label>
                                                                <!-- </div> -->
                                                           <!-- <div class="form-group"> -->
                                                                <br>
                                                                <label id="labelJumlah{{$value->id}}" class="col-form-label">Jumlah Disetujui:</label>
                                                                <input required value="{{$value->nominal}}" id="jumlah{{$value->id}}" maxlength="14" name="jumlah" type="text" class="form-control" style="padding:0.35px 0.35px 0.35px 5px !important" >
                                                                <label class="col-form-label">Catatan WR II:</label>
                                                                <input value="" name="catatan_wrii" type="text" maxlength="255" class="form-control" style="padding:0.35px 0.35px 0.35px 5px !important" >
                                                            </div>                                                            
                                                            <!-- <div class="form-group"> -->
                                                            <!-- </div>  -->
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
                            @elseif (auth()->user()->jabatan=="Wakil Rektor II" && $value->approved_by_rektor==3)
                                <div class="bootstrap-modal">
                                        <button type="button" class="btn mb-1 btn-rounded btn-warning" data-toggle="modal" data-target="#editPersetujuanWRII{{$value->id}}">Persetujuan WR II</button>
                                        <div class="modal fade" id="editPersetujuanWRII{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="editPersetujuanWRIIModalLabel{{$value->id}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editPersetujuanWRIIModalLabel{{$value->id}}">Persetujuan Wakil Rektor II</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('updateRincianP',$value->id)}}" method="POST">
                                                        @csrf
                                                        @method('put')
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <span>
                                                                        Mata Anggaran <b>"{{$value->nama}}"</b>
                                                                        <br>Anggaran {{$year}} Rp {{number_format($keterangan[0]->nominal,0,',','.')}}
                                                                        <br>Realisasi Rp {{number_format($realisasi[0]->total,0,',','.')}} (<?php if($keterangan[0]->nominal!=0){echo number_format(($realisasi[0]->total/$keterangan[0]->nominal)*100, 2, '.', ',');}else{echo"0";}?> %)
                                                                        <br>Pengajuan Rp {{number_format($value->nominal,0,',','.')}}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                            <label class="col-form-label">Persetujuan:</label><br>
                                                                <!-- <label class="radio-inline mr-3"> -->
                                                                    <input id="setuju{{$value->id}}" type="radio" value="1" name="persetujuan" required> Setuju</label>
                                                                <!-- <label class="radio-inline mr-3"> -->
                                                                    <input id="tidak" type="radio" value="2" name="persetujuan"> Tidak Setuju</label>
                                                                    <input type="radio" value="3" name="persetujuan"> Revisi</label>
                                                                <!-- </div> -->
                                                           <!-- <div class="form-group"> -->
                                                                <br>
                                                                <label id="labelJumlah{{$value->id}}" class="col-form-label">Jumlah Disetujui:</label>
                                                                <input required value="{{$value->nominal}}" id="jumlah{{$value->id}}" maxlength="14" name="jumlah" type="text" class="form-control" style="padding:0.35px 0.35px 0.35px 5px !important" >
                                                                <label class="col-form-label">Catatan WR II:</label>
                                                                <input value="" name="catatan_wrii" type="text" maxlength="255" class="form-control" style="padding:0.35px 0.35px 0.35px 5px !important" >
                                                            </div>                                                            
                                                            <!-- <div class="form-group"> -->
                                                            <!-- </div>  -->
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
                            @endif
                            @if (auth()->user()->jabatan=="Rektor" AND $value->approved_by_wr2 !=0 AND $value->approved_by_wr2 !=3)
                                <div class="bootstrap-modal">
                                        <button type="button" class="btn mb-1 btn-rounded btn-warning" data-toggle="modal" data-target="#editPersetujuanRektor{{$value->id}}">Persetujuan Rektor</button>
                                        <div class="modal fade" id="editPersetujuanRektor{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="editPersetujuanRektorModalLabel{{$value->id}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editPersetujuanRektorModalLabel{{$value->id}}">Persetujuan Rektor</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('updateRincianP',$value->id)}}" method="POST">
                                                        @csrf
                                                        @method('put')
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <span>
                                                                        Mata Anggaran <b>"{{$value->nama}}"</b>
                                                                        <br>Anggaran {{$year}} Rp {{number_format($keterangan[0]->nominal,0,',','.')}}
                                                                        <br>Realisasi Rp {{number_format($realisasi[0]->total,0,',','.')}} (<?php if($keterangan[0]->nominal!=0){echo number_format(($realisasi[0]->total/$keterangan[0]->nominal)*100, 2, '.', ',');}else{echo"0";}?> %)
                                                                        <br>Pengajuan Rp {{number_format($value->nominal,0,',','.')}}
                                                                        <br>Disetujui WRII Rp {{number_format($value->nominal_disetujui,0,',','.')}}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                            <label class="col-form-label">Persetujuan:</label><br>
                                                                <!-- <label class="radio-inline mr-3"> -->
                                                                    <input  type="radio" value="1" name="persetujuan_rektor" required> Setuju</label>
                                                                <!-- <label class="radio-inline mr-3"> -->
                                                                    <input  type="radio" value="2" name="persetujuan_rektor"> Tidak Setuju</label>
                                                                    <input  type="radio" value="3" name="persetujuan_rektor"> Revisi</label>
                                                                <!-- </div> -->
                                                           <!-- <div class="form-group"> -->
                                                                <br>
                                                                <label class="col-form-label">Catatan Rektor:</label>
                                                                <input value="" name="catatan_rektor" type="text" maxlength="255" class="form-control" style="padding:0.35px 0.35px 0.35px 5px !important" >
                                                            </div>      
                                                            <input type="hidden" value="{{$value->nominal_disetujui}}" name="jumlah">
                                                            <input type="hidden" value="{{$value->nominal}}" name="jumlah_awal">                                                       
                                                            <!-- <div class="form-group"> -->
                                                            <!-- </div>  -->
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
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <center>
        <?php
        $planning_id=$showPlanning[0]->id;
            $acc_count=DB::select(
                "SELECT count(case when pd.approved_by_wr2=0 THEN 0 END) AS WR_0,
                        count(case when pd.approved_by_wr2=1 THEN 1 END) AS WR_1,
                        count(case when pd.approved_by_wr2=2 THEN 2 END) AS WR_2,
                        count(case when pd.approved_by_wr2=3 THEN 3 END) AS WR_3,
                        count(case when pd.approved_by_rektor=0 THEN 0 END) AS R_0,
                        count(case when pd.approved_by_rektor=1 THEN 1 END) AS R_1,
                        count(case when pd.approved_by_rektor=2 THEN 2 END) AS R_2,
                        count(case when pd.approved_by_rektor=3 THEN 3 END) AS R_3
                FROM planning_details pd
                WHERE planning_id = $planning_id");
        ?>
        @if (auth()->user()->jabatan=="Wakil Rektor II" && $acc_count[0]->R_1==0 && $acc_count[0]->R_2==0 && $acc_count[0]->R_3==0 )
            <br>
            <form action="{{route('perencanaan.update',$showPlanning[0]->id)}}" method="POST">
            @csrf
            @method('put')
                    <input type="hidden" name="pjb" value="wr2">
                    <button type="submit" id="setujui_ditinjau" class="btn btn-primary">Setujui Semua "Sedang Ditinjau"</button>
            </form>
        @endif
        @if (auth()->user()->jabatan=="Rektor" AND $value->approved_by_wr2 !=0 AND $value->approved_by_rektor ==0)
            <br>
            <form action="{{route('perencanaan.update',$showPlanning[0]->id)}}" method="POST">
            @csrf
            @method('put')
                    <input type="hidden" name="pjb" value="rektor">
                    <button type="submit" id="sesuai_wr2" class="btn btn-primary">Ubah Semua "Sedang Ditinjau" Sesuai Dengan WR II</button>
            </form>
        @endif
        <br>
        <a href="{{ route('perencanaan.index') }}"><button type="button" class="btn mb-1 btn-success">Kembali<span class="btn-icon-right"><i class="fa fa-chevron-circle-left"></i></button></a>

        @if (auth()->user()->id==$showPlanning[0]->user_id)
        @if ($acc_count[0]->WR_0==0 && $acc_count[0]->WR_1==0 && $acc_count[0]->WR_2==0)
        <button type="button" class="btn mb-1 btn-primary" data-toggle="modal" data-target="#tambah_rincian">Tambah Rincian<span class="btn-icon-right"><i class="fa fa-cart-plus"></i></button>
        </center>
        <div class="modal fade bd-example-modal-lg" id="tambah_rincian" tabindex="-1" role="dialog" aria-labelledby="tambah_rincianModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="tambah_rincianModalLabel">Tambah Rincian</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                    <div class="modal-body">
                                        <form action="{{route('storeRincianP')}}" method="POST" id="forms">
                                            @csrf
                                            <div class="form-group">
                                                <label for="akun_rincian" class="col-form-label">Akun :</label>
                                                <select id="akun_rincian" data-width="100%" name="akun_rincian" class="form-control" required>
                                                    <option value="" selected disabled hidden>Pilih Akun</option>
                                                    @foreach($accountList as $accountvalue)
                                                    <option value="{{ $accountvalue->account_id }}">({{$accountvalue->tipe}} || {{ $accountvalue->kelompok}}) {{ $accountvalue->nama}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="jumlah_anggaran_tambah_rincian" class="col-form-label">Jumlah Anggaran :</label>
                                                <input  required name="jumlah_anggaran_tambah_rincian" type="text" maxlength="14" class="form-control" id="jumlah_anggaran_tambah_rincian" placeholder="Rp">
                                            </div>
                                            @if ($showPlanning[0]->departement_id==6)
                                            <div class="form-group">
                                                <label for="group_rektorat" class="col-form-label">{{ __('Group Rektorat:') }}</label>
                                                    <select id="group_rektorat" name="group_rektorat" class="form-control @error('group_rektorat') is-invalid @enderror" required autocomplete="group_rektorat" autofocus>
                                                    <option value="" selected disabled hidden>Pilih Group Rektorat</option>
                                                        <option value="Wakil Rektor I">Wakil Rektor I</option>
                                                        <option value="Wakil Rektor II">Wakil Rektor II</option>
                                                        <option value="Wakil Rektor III">Wakil Rektor III</option>
                                                        <option value="Laboratorium">Laboratorium</option>
                                                        <option value="Perpustakaan">Perpustakaan</option>
                                                        <option value="LPPM">LPPM</option>
                                                        <option value="LPMI">LPMI</option>
                                                    </select>                                             
                                            </div>
                                            @else
                                            @endif
                                            <div class="form-group">
                                                <label for="pj" class="col-form-label">Penanggungjawab Kegiatan :</label>
                                                <input  required name="pj" type="text" maxlength="255" class="form-control" id="pj" placeholder="Penanggungjawab">
                                            </div>
                                            <div class="form-group">
                                                <label for="satuan_ukur_kinerja" class="col-form-label">Satuan Ukur Kinerja :</label>
                                                <textarea name="satuan_ukur_kinerja" rows="2" cols="50" maxlength="255" class="form-control" id="satuan_ukur_kinerja" placeholder="Link Google Drive"></textarea>
                                                <label for="judul_file" class="col-form-label">Judul File Satuan Ukur Kinerja :</label>
                                                <input name="judul_file" type="text" maxlength="255" class="form-control" id="judul_file" placeholder="Judul File">                                                
                                            </div>
                                            <div class="form-group">
                                                <label for="target_kinerja" class="col-form-label">Target Kinerja(Target Output) :</label>
                                                <textarea name="target_kinerja" rows="2" cols="50" maxlength="255" class="form-control" id="target_kinerja" placeholder="Target Output"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="capaian_kinerja" class="col-form-label">Capaian Kinerja(Realisasi Output) :</label>
                                                <textarea name="capaian_kinerja" rows="2" cols="50" maxlength="255" class="form-control" id="capaian_kinerja" placeholder="Realisasi Output"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="target_waktu_pelaksanaan" class="col-form-label">Target Waktu Pelaksanaan:</label>
                                                <textarea name="target_waktu_pelaksanaan" rows="2" cols="50" maxlength="255" class="form-control" id="target_waktu_pelaksanaan" placeholder="Waktu Pelaksanaan"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="capaian_target_waktu_penyelesaian" class="col-form-label">Capaian Target Waktu Penyelesaian:</label>
                                                <textarea name="capaian_target_waktu_penyelesaian" rows="2" cols="50" maxlength="255" class="form-control" id="capaian_target_waktu_penyelesaian" placeholder="Target Waktu Penyelesaian"></textarea>
                                            </div>


                                            <input type="hidden" name="planning_id" value="{{$showPlanning[0]->id}}">

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
                @else
                @endif   
            </div>
        </div>
        @else
        @endif
        <!-- @if ($acc_count[0]->WR_0==0 && $acc_count[0]->WR_1==0 && $acc_count[0]->WR_2==0 && $acc_count[0]->WR_3>0)
            <center>
            <button type="button" class="btn mb-1 btn-primary" data-toggle="modal" data-target="#tambah_rincian">Tambah Rincian<span class="btn-icon-right"><i class="fa fa-cart-plus"></i></button>
            </center>
            <div class="modal fade bd-example-modal-lg" id="tambah_rincian" tabindex="-1" role="dialog" aria-labelledby="tambah_rincianModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="tambah_rincianModalLabel">Tambah Rincian</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                        <div class="modal-body">
                                            <form action="{{route('storeRincianP')}}" method="POST" id="forms">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="akun_rincian" class="col-form-label">Akun :</label>
                                                    <select id="akun_rincian" data-width="100%" name="akun_rincian" class="form-control" required>
                                                        <option value="" selected disabled hidden>Pilih Akun</option>
                                                        @foreach($accountList as $accountvalue)
                                                        <option value="{{ $accountvalue->account_id }}">({{$accountvalue->tipe}} || {{ $accountvalue->kelompok}}) {{ $accountvalue->nama}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="jumlah_anggaran_tambah_rincian" class="col-form-label">Jumlah Anggaran :</label>
                                                    <input  required name="jumlah_anggaran_tambah_rincian" type="text" maxlength="14" class="form-control" id="jumlah_anggaran_tambah_rincian" placeholder="Rp">
                                                </div>
                                                @if ($showPlanning[0]->departement_id==6)
                                                <div class="form-group">
                                                    <label for="group_rektorat" class="col-form-label">{{ __('Group Rektorat:') }}</label>
                                                        <select id="group_rektorat" name="group_rektorat" class="form-control @error('group_rektorat') is-invalid @enderror" required autocomplete="group_rektorat" autofocus>
                                                        <option value="" selected disabled hidden>Pilih Group Rektorat</option>
                                                            <option value="Wakil Rektor I">Wakil Rektor I</option>
                                                            <option value="Wakil Rektor II">Wakil Rektor II</option>
                                                            <option value="Wakil Rektor III">Wakil Rektor III</option>
                                                            <option value="Laboratorium">Laboratorium</option>
                                                            <option value="Perpustakaan">Perpustakaan</option>
                                                            <option value="LPPM">LPPM</option>
                                                            <option value="LPMI">LPMI</option>
                                                        </select>                                             
                                                </div>
                                                @else
                                                @endif
                                                <div class="form-group">
                                                    <label for="pj" class="col-form-label">Penanggungjawab Kegiatan :</label>
                                                    <input  required name="pj" type="text" maxlength="255" class="form-control" id="pj" placeholder="Penanggungjawab">
                                                </div>
                                                <div class="form-group">
                                                    <label for="satuan_ukur_kinerja" class="col-form-label">Satuan Ukur Kinerja :</label>
                                                    <textarea name="satuan_ukur_kinerja" rows="2" cols="50" maxlength="255" class="form-control" id="satuan_ukur_kinerja" placeholder="Link Google Drive"></textarea>
                                                    <label for="judul_file" class="col-form-label">Judul File Satuan Ukur Kinerja :</label>
                                                    <input name="judul_file" type="text" maxlength="255" class="form-control" id="judul_file" placeholder="Judul File">                                                
                                                </div>
                                                <div class="form-group">
                                                    <label for="target_kinerja" class="col-form-label">Target Kinerja(Target Output) :</label>
                                                    <textarea name="target_kinerja" rows="2" cols="50" maxlength="255" class="form-control" id="target_kinerja" placeholder="Target Output"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="capaian_kinerja" class="col-form-label">Capaian Kinerja(Realisasi Output) :</label>
                                                    <textarea name="capaian_kinerja" rows="2" cols="50" maxlength="255" class="form-control" id="capaian_kinerja" placeholder="Realisasi Output"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="target_waktu_pelaksanaan" class="col-form-label">Target Waktu Pelaksanaan:</label>
                                                    <textarea name="target_waktu_pelaksanaan" rows="2" cols="50" maxlength="255" class="form-control" id="target_waktu_pelaksanaan" placeholder="Waktu Pelaksanaan"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="capaian_target_waktu_penyelesaian" class="col-form-label">Capaian Target Waktu Penyelesaian:</label>
                                                    <textarea name="capaian_target_waktu_penyelesaian" rows="2" cols="50" maxlength="255" class="form-control" id="capaian_target_waktu_penyelesaian" placeholder="Target Waktu Penyelesaian"></textarea>
                                                </div>


                                                <input type="hidden" name="planning_id" value="{{$showPlanning[0]->id}}">

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
        @else
        @endif -->
            
</div>
</div>
@endsection
@push('nominal-mask')
    $('#jumlah_anggaran_tambah_rincian').mask('#.##0', {reverse: true});
    @foreach ($showDetailPlanning as $valueMask)
    $('#jumlah_anggaran_tambah_rincian_edit{{$valueMask->id}}').mask('#.##0', {reverse: true});
    $('#jumlah{{$valueMask->id}}').mask('#.##0', {reverse: true});
    @endforeach
@endpush

@push('detail_budget-script')
    <script>    
        $(document).ready(function() {
            $('.akun_rincian').select2();
        });
        $('#akun_rincian').select2({
        dropdownParent: $('#tambah_rincian')
        });
    </script>
    @foreach ($showDetailPlanning as $valueDropdown)
    <script>    
        $(document).ready(function() {
            $('.akun_rincian_edit{{$valueDropdown->id}}').select2();
        });
        $('#akun_rincian_edit{{$valueDropdown->id}}').select2({
        dropdownParent: $('#editRincian{{$valueDropdown->id}}')
            });
    </script>
    @endforeach  
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
    </script>
        <script type="text/javascript">
        $(document).on('click', '#setujui_ditinjau', function(e){
        e.preventDefault();
        var form = $(this).parents('form');
        Swal.fire({
                title: "Anda Yakin Untuk Menyetujui Semua Yang Masih Ditinjau?",
                text: "Anggaran yang disetujui akan sama dengan yang diajukan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Setujui!"
        }).then((result) => {
            if (result.value) {
                form.submit();
            }
        });
        });
    </script>
        </script>
        <script type="text/javascript">
        $(document).on('click', '#status_bayar', function(e){
        e.preventDefault();
        var form = $(this).parents('form');
        Swal.fire({
                title: "Konfirmasi",
                text: "Ubah Status Pembayaran",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Setujui!"
        }).then((result) => {
            if (result.value) {
                form.submit();
            }
        });
        });
    </script>
        </script>
        <script type="text/javascript">
        $(document).on('click', '#sesuai_wr2', function(e){
        e.preventDefault();
        var form = $(this).parents('form');
        Swal.fire({
                title: "Anda Yakin Untuk Menyetujui Semua Sesuai Dengan WR II?",
                text: "Anggaran yang disetujui akan sama dengan yang dikoreksi WR II!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Setujui!"
        }).then((result) => {
            if (result.value) {
                form.submit();
            }
        });
        });
    </script>
@endpush

@push('child-row-datatables')
<script>
$(document).ready(function() {
    var table = $('#r_plannings2').DataTable();

    // Add event listener for opening and closing details
    $('#r_plannings2 tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var arrowIcon = $(this).find('.arrow-icon');

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
            arrowIcon.text('▶'); // Change icon to point right
        } else {
            // Open this row
            row.child(format(row.data())).show();
            tr.addClass('shown');
            arrowIcon.text('▼'); // Change icon to point down
        }
    });
    @if ($showPlanning[0]->departement_id==6)
    // Formatting function for row details
    function format(data) {
        return '<div><h3>Rincian Tabel</h3><table><tr><td>Jenis :</td><td>'+ data[1] +'</td></tr>'+
        '<tr><td>Penanggungjawab Kegiatan:</td><td>'+ data[5] +'</td></tr>'+
        '<tr><td>Satuan Ukur Kinerja Kegiatan:</td><td>'+ data[7] +'</td></tr>' +
        '<tr><td>Target Kinerja(Target Output):</td><td>'+ data[8] +'</td></tr>'+
        '<tr><td>Capaian Kinerja(Realisasi Output):</td><td>'+ data[9] +'</td></tr>'+
        '<tr><td>Target Waktu Pelaksanaan:</td><td>'+ data[10] +'</td></tr>'+
        '<tr><td>Capaian Target Waktu Penyelesaian:</td><td>'+ data[11] +'</td></tr>'+
        '<tr><td>Catatan WR II:</td><td>'+ data[14] +'</td></tr>'+
        '<tr><td>Catatan Rektor:</td><td>'+ data[16] +'</td></tr>'+
        '<tr><td>Catatan Yayasan:</td><td>'+ data[17] +'</td></tr>'+
        '</table></div>';
    }                        
    @else
    // Formatting function for row details
    function format(data) {
        return '<div><h3>Rincian Tabel</h3><table><tr><td>Jenis :</td><td>'+ data[1] +'</td></tr>'+
        '<tr><td>Penanggungjawab Kegiatan:</td><td>'+ data[4] +'</td></tr>'+
        '<tr><td>Satuan Ukur Kinerja Kegiatan:</td><td>'+ data[6] +'</td></tr>' +
        '<tr><td>Target Kinerja(Target Output):</td><td>'+ data[7] +'</td></tr>'+
        '<tr><td>Capaian Kinerja(Realisasi Output):</td><td>'+ data[8] +'</td></tr>'+
        '<tr><td>Target Waktu Pelaksanaan:</td><td>'+ data[9] +'</td></tr>'+
        '<tr><td>Capaian Target Waktu Penyelesaian:</td><td>'+ data[10] +'</td></tr>'+
        '<tr><td>Catatan WR II:</td><td>'+ data[13] +'</td></tr>'+
        '<tr><td>Catatan Rektor:</td><td>'+ data[15] +'</td></tr>'+
        '<tr><td>Catatan Yayasan:</td><td>'+ data[16] +'</td></tr>'+
        '</table></div>';
    }
    @endif

});
</script>
@endpush
