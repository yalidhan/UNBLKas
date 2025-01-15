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
                <li class="breadcrumb-item"><a href="/anggaran/">Rincian Anggaran</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Rincian Anggaran</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
            @if($errors->first())
            <div class="alert alert-danger" role="alert">
            {{$errors->first()}}
            </div>
            @else
            @endif
                <form action="{{route('updateRincianA',$budget->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                        <div class="form-group">
                            <label for="akun_rincian_edit" class="col-form-label">Akun</label>
                            <select id="akun_rincian_edit" data-width="100%" name="akun_rincian_edit" class="form-control" required>
                                @foreach($accountList as $accountvalue)
                                    <option value="{{ $accountvalue->id }}"{{$budget->account_id == $accountvalue->id  ? 'selected' : ''}}>({{$accountvalue->tipe}} || {{ $accountvalue->kelompok}}) {{ $accountvalue->nama}}</option>
                                    <!-- <option value="{{ $accountvalue->id }}">{{$accountvalue->tipe}} || {{ $accountvalue->nama}}</option> -->
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nominal_tambah_rincian_edit{{$budget->id}}" class="col-form-label">Nominal :</label>
                            <input style="padding:0px 0px 0px 5px;" required name="nominal_tambah_rincian_edit" value="{{$budget->nominal}}" maxlength="14" type="text" class="form-control" id="nominal_tambah_rincian_edit" placeholder="Rp">
                        </div>
                        <div class="form-group summernote">
                            <label for="summernote{{$budget->id}}" class="col-form-label">Keterangan Rincian :</label>
                            <textarea name="keterangan_rincian_edit"  class="form-control" id="summernote">{!!$budget->keterangan!!}</textarea>
                        </div>
                            <input type="hidden" name="budget_id" value="{{$budget->budget_id}}">
                            <input type="hidden" name="current_account" value="{{$budget->account_id}}">
                        <div class="modal-footer">
                            <a type="button" class="btn btn-secondary" href="/anggaran/{{$budget->budget_id}}">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>  
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('nominal-mask')
    $('#nominal_tambah_rincian').mask('#.##0', {reverse: true});
    $('#nominal_tambah_rincian_edit').mask('#.##0', {reverse: true});
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
    <script>    
        $(document).ready(function() {
            $('.akun_rincian_edit').select2();
        });
        $('#akun_rincian_edit').select2({
            });
    </script>

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
