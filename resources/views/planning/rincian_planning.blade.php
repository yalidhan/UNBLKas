@extends('master')

@section('content')
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
                                                <td>Dijukan Pada</td>
                                                <td>&nbsp;:</td>
                                                <td style="white-space: nowrap;">&nbsp;{{\Carbon\Carbon::parse($showPlanning[0]->created_at)->format('d-F-Y h:i:s')}}</td>
                                            </tr>
                                            <tr>
                                                <td>Untuk Bulan</td>
                                                <td>&nbsp;:</td>
                                                <td style="white-space: nowrap;">&nbsp;{{\Carbon\Carbon::parse($showPlanning[0]->for_bulan)->format('F-Y')}}</td>
                                            </tr>
                                            <tr>
                                                <td style="white-space: nowrap;">Total Perencanaan</td>
                                                <td>&nbsp;:</td>
                                                <td style="white-space: nowrap;">&nbsp;Rp {{number_format($showPlanning[0]->total_perencanaan,0,',','.')}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
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
                                                            <label for="for_bulan_edit" class="col-form-label">Untuk Bulan:</label>
                                                            <input value="{{\Carbon\Carbon::parse($showPlanning[0]->for_bulan)->format('Y-m')}}" required value="{{ old('for_bulan_edit')}}" name="for_bulan_edit" type="month" class="form-control" id="for_bulan_edit">
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
                <div class="table-responsive"> 
            <table id="r_plannings" class="table table-striped table-bordered zero-configuration" style="border:3px;">
                <thead>
                    <tr>
                        <th scope="col">Jenis</th>
                        @if ($showPlanning[0]->departement_id==6)
                        <th scope="col">Kelompok Rektorat</th>
                        @else
                        @endif
                        <th scope="col">Kegiatan</th>
                        <th scope="col">Keterangan Anggaran</th>
                        <th scope="col">Penanggung Jawab Kegiatan</th>
                        <th scope="col">Jumlah Anggaran</th>
                        <th scope="col">Satuan Ukur Kinerja Kegiatan</th>
                        <th scope="col">Target Kinerja(Target Output)</th>
                        <th scope="col">Capaian Kinerja(Realisasi Output)</th>
                        <th scope="col">Target Waktu Pelaksanaan</th>
                        <th scope="col">Capaian Target Waktu Penyelesaian</th>
                        <th scope="col">Jml. Anggaran Disetujui</th>
                        <th scope="col">Disetujui WR II</th>
                        <th scope="col">Catatan WR II</th>
                        <th scope="col">Disetujui Rektor</th>
                        <th scope="col">Catatan Rektor</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($showDetailPlanning as $value)
                    <tr>
                        <td>RKA</td>
                        @if ($showPlanning[0]->departement_id==6)
                        <td>{{$value->group_rektorat}}</td>
                        @else
                        @endif
                        <td>{{$value->nama}}</td>
                        <td>
                            @php
                            $budget_id=$showPlanning[0]->budget_id;
                            $account_id=$value->id;
                                $keterangan=DB::select(
                                    "SELECT * FROM budget_details WHERE budget_id=$budget_id and account_id=$account_id"
                                ); 
                            @endphp
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#keterangan{{$value->id}}">
                            Lihat Keterangan 
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
                        <td>{{$value->pj}}</td>
                        <td style="white-space: nowrap;"> Rp {{number_format($value->nominal,0,',','.')}}</td>
                        <td><button type="button" class="btn btn-primary"                            
                            onclick="window.open('{{$value->satuan_ukur_kinerja}}', 
                            'newwindow', 
                            'width=680,height=780'); 
                            return false;">                                
                            Link Dokumen Satuan Ukur Kinerja Kegiatan
                            </button>

                        </td>
                        <td>{{$value->target_kinerja}}</td>
                        <td>{{$value->capaian_kinerja}}</td>
                        <td>{{$value->waktu_pelaksanaan}}</td>
                        <td>{{$value->capaian_target_waktu}}</td>
                        <td style="white-space: nowrap;"> Rp {{number_format($value->nominal_disetujui,0,',','.')}}</td>
                        <td>
                            @if($value->approved_by_wr2==0)
                                <a href="#" class="badge badge-primary">Sedang Ditinjau</a>
                            @elseif($value->approved_by_wr2==1)
                                <a href="#" class="badge badge-success">Disetujui</a>
                            @elseif($value->approved_by_wr2==2)
                                <a href="#" class="badge badge-danger">Ditolak</a>   
                            @endif 
                        </td>
                        <td>{{$value->note_wr2}}</td>
                        <td>
                            @if($value->approved_by_rektor==0)
                                <a href="#" class="badge badge-primary">Sedang Ditinjau</a>
                            @elseif($value->approved_by_rektor==1)
                                <a href="#" class="badge badge-success">Disetujui</a>
                            @elseif($value->approved_by_rektor==2)
                                <a href="#" class="badge badge-danger">Ditolak</a>   
                            @endif 
                        </td>
                        <td>{{$value->note_rektor}}</td>
                        <td><span style="display: flex;">
                            @if (auth()->user()->id==$showPlanning[0]->user_id)
                                <!-- <a href="#" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil color-muted m-r-5"></i></a>&nbsp;&nbsp;&nbsp;&nbsp; -->
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
                                                                    <option value="{{ $accountvalue->id }}"{{$value->account_id == $accountvalue->id  ? 'selected' : ''}}>({{$accountvalue->tipe}} || {{ $accountvalue->kelompok}}) {{ $accountvalue->nama}}</option>
                                                                    <!-- <option value="{{ $accountvalue->id }}">{{$accountvalue->tipe}} || {{ $accountvalue->nama}}</option> -->
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nominal_tambah_rincian_edit{{$value->id}}" class="col-form-label">Nominal :</label>
                                                            <input style="padding:0px 0px 0px 5px;" required name="nominal_tambah_rincian_edit" value="{{$value->nominal}}" maxlength="14" type="text" class="form-control" id="nominal_tambah_rincian_edit{{$value->id}}" placeholder="Rp">
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
                                <form method="POST" action="{{route('destroyRincianP',$value->id)}}"> 
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
            </table>
        </div>
        <center>
        <a href="{{ route('perencanaan.index') }}"><button type="button" class="btn mb-1 btn-success">Kembali<span class="btn-icon-right"><i class="fa fa-chevron-circle-left"></i></button></a>
        @if (auth()->user()->id==$showPlanning[0]->user_id)
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
                                                <input  required name="pj" type="text" maxlength="25" class="form-control" id="pj" placeholder="Penanggungjawab">
                                            </div>
                                            <div class="form-group">
                                                <label for="satuan_ukur_kinerja" class="col-form-label">Satuan Ukur Kinerja :</label>
                                                <textarea name="satuan_ukur_kinerja" rows="2" cols="50" maxlength="250" class="form-control" id="satuan_ukur_kinerja" placeholder="Link Google Drive"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="target_kinerja" class="col-form-label">Target Kinerja(Target Output) :</label>
                                                <textarea name="target_kinerja" rows="2" cols="50" maxlength="100" class="form-control" id="target_kinerja" placeholder="Target Output"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="capaian_kinerja" class="col-form-label">Capaian Kinerja(Realisasi Output) :</label>
                                                <textarea name="capaian_kinerja" rows="2" cols="50" maxlength="100" class="form-control" id="capaian_kinerja" placeholder="Realisasi Output"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="target_waktu_pelaksanaan" class="col-form-label">Target Waktu Pelaksanaan:</label>
                                                <textarea name="target_waktu_pelaksanaan" rows="2" cols="50" maxlength="25" class="form-control" id="target_waktu_pelaksanaan" placeholder="Waktu Pelaksanaan"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="capaian_target_waktu_penyelesaian" class="col-form-label">Capaian Target Waktu Penyelesaian:</label>
                                                <textarea name="capaian_target_waktu_penyelesaian" rows="2" cols="50" maxlength="25" class="form-control" id="capaian_target_waktu_penyelesaian" placeholder="Target Waktu Penyelesaian"></textarea>
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
            
        </div>
        @if($errors->first())
            <div class="alert alert-danger" role="alert">
            {{$errors->first()}}
            </div>
            @else
        @endif

    </div>
</div>
</div>
@endsection
@push('nominal-mask')
    $('#jumlah_anggaran_tambah_rincian').mask('#.##0', {reverse: true});
    @foreach ($showDetailPlanning as $valueMask)
    $('#jumlah_anggaran_tambah_rincian_edit{{$valueMask->id}}').mask('#.##0', {reverse: true});
    @endforeach
@endpush

@push('detail_budget-script')
    <script>    
        $('#r_plannings').dataTable( {
        "pageLength": 25
        } );
    </script>
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
@endpush
