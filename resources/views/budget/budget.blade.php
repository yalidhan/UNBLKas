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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Anggaran</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <center>
                                <h4 class="card-title">Daftar Anggaran</h4>   
                                
                                <h4></h4>
                                
                                <div class="bootstrap-modal">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAnggaran">Tambah Anggaran</button>
                            </center>
                                    <div class="modal fade" id="addAnggaran" tabindex="-1" role="dialog" aria-labelledby="addAnggaranModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addAnggaranModalLabel">Tambah Anggaran</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="/anggaran" method="POST">
                                                    @csrf
                                                        <div class="form-group">
                                                            <label for="tahun_anggaran" class="col-form-label">Tahun Anggaran:</label>
                                                            <input onkeypress='validate(event)'required value="{{ old('tahun_anggaran')}}" name="tahun_anggaran" type="text" maxlength="4" placeholder="Cth. 2023" class="form-control" id="tahun_anggaran">
                                                        </div>
                                                        @if (auth()->user()->departement_id==1)
                                                        <div class="form-group">
                                                            <label for="departement" class="col-form-label">Departemen:</label>
                                                            <select id="departement" data-width="100%" name="departement" class="form-control" required>
                                                                <option value="" selected disabled hidden>Pilih Departement Tujuan</option>
                                                                @foreach($departements as $departementvalue)
                                                                    <option value="{{ $departementvalue->id }}">{{ $departementvalue->nama}}</option>
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
                                </div>
                                @if($errors->first())
                                    <div class="alert alert-danger" role="alert">
                                {{$errors->first()}}
                                </div>
                                @else
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Tahun Anggaran</th>
                                                <th>Departemen</th>
                                                <th>Input Oleh</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($budgets as $value)
                                            <tr>
                                                <td>{{$value->tahun}}</td>
                                                <td>{{$value->departement->nama}}</td>
                                                <td>{{$value->user->name}}</td>
                                                <td><span style="display: flex;"> 
                                                        <a href="{{route('anggaran.show',$value->id)}}" data-toggle="tooltip" data-placement="top" title="Rincian"><i class="fa fa-eye color-danger"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;                                          
                                                        @if (auth()->user()->id==$value->user_id)
                                                            <form
                                                                action="{{route('anggaran.destroy',$value->id)}}"
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
                                                <th>Tahun Anggaran</th>
                                                <th>Departemen</th>
                                                <th>Input Oleh</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
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

function validate(evt) {
  var theEvent = evt || window.event;

    // Handle paste
    if (theEvent.type === 'paste') {
        key = event.clipboardData.getData('text/plain');
    } else {
    // Handle key press
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
    }
    var regex = /[0-9]|\./;
    if( !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
    }
    }
</script>

<script>    
    $(document).ready(function() {
        $('.departement').select2();
    });
    $('#departement').select2({
    dropdownParent: $('#addAnggaran')
    });
</script>
@endpush