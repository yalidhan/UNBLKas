@extends('master')
@push('rincian_budget-style')
    <style>
        .summernote .table td{
        border-color: #000000;
        }
        .summernote .table-bordered {
        border: 1px solid #000000;
        }
        .note-editable ul li{
        list-style: disc !important;
        list-style-position: inside !important;
        }
        .note-editable ol li {
        list-style: decimal !important;
        list-style-position: inside !important;
        }
    </style>
@endpush
@section('content')
<div class="content-body">
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/anggaran">Budget</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Rincian Anggaran</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h5 class="card-title">Rincian Anggaran</h5></div>
                        <div class="card text-left" style="font-weight:bold;font-size:120%;">
                        <div class="card-header">
                            Data Anggaran
                        </div>
                    <div class="card-body">
                        <div class="table-responsive"> 
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>Departemen</td>
                                                <td>&nbsp;:</td>
                                                <td style="white-space: nowrap;">&nbsp;{{$showBudget[0]->departement}}</td>
                                            </tr>
                                            <tr>
                                                <td>Tahun Anggaran</td>
                                                <td>&nbsp;:</td>
                                                <td style="white-space: nowrap;">&nbsp;{{$showBudget[0]->tahun}}</td>
                                            </tr>
                                            <tr>
                                                <td style="white-space: nowrap;">Total Anggaran</td>
                                                <td>&nbsp;:</td>
                                                <td style="white-space: nowrap;">&nbsp;Rp {{number_format($showBudget[0]->total_anggaran,0,',','.')}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                            @if (auth()->user()->id==$showBudget[0]->user_id)
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
                                                <form action="{{route('anggaran.update',$showBudget[0]->id)}}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                            <label for="tahun_anggaran_edit" class="col-form-label">Tahun Anggaran:</label>
                                                            <input value="{{$showBudget[0]->tahun}}" onkeypress='validate(event)' required value="{{ old('tahun_anggaran_edit')}}" name="tahun_anggaran_edit" type="text" maxlength="4" placeholder="Cth. 2023" class="form-control" id="tahun_anggaran_edit">
                                                </div>
                                                @if (auth()->user()->departement_id==1)
                                                <div class="form-group">
                                                        <label for="departement_edit" class="col-form-label">Departemen:</label>
                                                        <select id="departement_edit" data-width="100%" name="departement_edit" class="form-control" required>
                                                            <option value="" selected disabled hidden>Pilih Departemen</option>
                                                            @foreach($departements as $departementvalue)
                                                                <option value="{{ $departementvalue->id }}"{{$showBudget[0]->departement_id == $departementvalue->id  ? 'selected' : ''}}>{{ $departementvalue->nama}}</option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                                @else
                                                
                                                @endif
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
        </div>
        @if($errors->first())
            <div class="alert alert-danger" role="alert">
            {{$errors->first()}}
            </div>
            @else
        @endif
        <div class="table-responsive"> 
            <table class="table table-striped table-bordered zero-configuration" style="border:3px;">
                <thead>
                    <tr>
                        <th scope="col">Kode Akun</th>
                        <th scope="col">Akun</th>
                        <th scope="col">Kelompok</th>
                        <th scope="col">Total Nominal</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($showDetailBudget as $value)
                    <tr>
                        <td>{{$value->no}}</td>
                        <td>{{$value->tipe}} || {{$value->nama}}</td>
                        <td>{{$value->kelompok}}</td>
                        <td style="white-space: nowrap;"> Rp {{number_format($value->nominal,0,',','.')}}</td>
                        <td>
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
                                <div class="modal-body summernote">
                                    {!!$value->keterangan!!}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                                </div>
                            </div>
                            </div>                           
                        </td>
                        <td><span style="display: flex;">
                            @if (auth()->user()->id==$showBudget[0]->user_id)
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
                                                    <form action="{{route('updateRincianA',$value->id)}}" method="POST">
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
                                                        <div class="form-group summernote">
                                                            <label for="summernote{{$value->id}}" class="col-form-label">Keterangan Rincian :</label>
                                                            <textarea  required name="keterangan_rincian_edit"  class="form-control" id="summernote{{$value->id}}">{!!$value->keterangan!!}</textarea>
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
                                <form method="POST" action="{{route('destroyRincianA',$value->id)}}"> 
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
        <a href="{{ route('anggaran.index') }}"><button type="button" class="btn mb-1 btn-success">Kembali<span class="btn-icon-right"><i class="fa fa-chevron-circle-left"></i></button></a>
        @if (auth()->user()->id==$showBudget[0]->user_id)
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
                                        <form action="{{route('storeRincianA')}}" method="POST" id="forms">
                                            @csrf
                                            <div class="form-group">
                                                <label for="akun_rincian" class="col-form-label">Akun :</label>
                                                <select id="akun_rincian" data-width="100%" name="akun_rincian" class="form-control" required>
                                                    <option value="" selected disabled hidden>Pilih Akun</option>
                                                    @foreach($accountList as $accountvalue)
                                                    <option value="{{ $accountvalue->id }}">({{$accountvalue->tipe}} || {{ $accountvalue->kelompok}}) {{ $accountvalue->nama}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="nominal_tambah_rincian" class="col-form-label">Total Nominal :</label>
                                                <input  required name="nominal_tambah_rincian" type="text" maxlength="14" class="form-control" id="nominal_tambah_rincian" placeholder="Rp">
                                            </div>
                                            <input type="hidden" name="budget_id" value="{{$showBudget[0]->id}}">
                                            <div class="form-group summernote">
                                                <label for="summernote" class="col-form-label">Keterangan Rincian :</label>
                                                <textarea name="keterangan_rincian"  class="form-control" id="summernote"></textarea>
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
                @else
                @endif   
            </div>
        </div>
    </div>
</div>
@endsection
@push('nominal-mask')
    $('#nominal_tambah_rincian').mask('#.##0', {reverse: true});
    @foreach ($showDetailBudget as $valueMask)
    $('#nominal_tambah_rincian_edit{{$valueMask->id}}').mask('#.##0', {reverse: true});
    @endforeach
@endpush

@push('detail_budget-script')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
            $('#summernote').summernote({
            shortcuts:false,
            // tableClassName: 'table table-striped',
            disableDragAndDrop: true,
            placeholder: 'Buat detil dari total nominal anggaran di sini, bisa menggunakan tabel',
            tabsize: 2,
            height: 100,
            toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['view', ['fullscreen']],
            ],
        });
    </script>
    @foreach ($showDetailBudget as $valueSummernote)
    <script>
            $('#summernote{{$valueSummernote->id}}').summernote({
            shortcuts:false,
            // tableClassName: 'table table-striped',
            disableDragAndDrop: true,
            placeholder: 'Buat detil dari total nominal anggaran di sini, bisa menggunakan tabel',
            tabsize: 2,
            height: 100,
            toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['view', ['fullscreen']],
            ],
        });
    </script>
    @endforeach
    <script>    
        $(document).ready(function() {
            $('.departement_edit').select2();
        });
        $('#departement_edit').select2({
        dropdownParent: $('#edit')
        });
    </script>
    <script>    
        $(document).ready(function() {
            $('.akun_rincian').select2();
        });
        $('#akun_rincian').select2({
        dropdownParent: $('#tambah_rincian')
        });
    </script>
    @foreach ($showDetailBudget as $valueDropdown)
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
