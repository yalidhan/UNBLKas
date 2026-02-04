@extends('master')

@section('content')
<div class="content-body">
            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/transaksi">Transaksi</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Transaksi Transfer</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Transfer') }}</div>
                <div class="card-body">
                    <form id="formTF" method="POST" action="{{route('updateTransfer',$showTransaction[0]->no_trf)}}">
                        @csrf
                        @method ('put')
                        <input type="hidden" name="kode" value="edit">
                        <div class="form-group">
                            <label for="no_spb_transfer" class="col-form-label">Departement Awal</label>
                            <input value="{{$showTransaction[0]->departement->nama}}" readonly type="text" class="form-control" id="departement_awal" placeholder="No.Urut/Departemen/UNBL/Tahun">
                        </div>                        
                        <div class="form-group">
                            <label for="akun_kas_awal" class="col-form-label">Kas Departemen Awal:</label>
                            <select @if (auth()->user()->id==$showTransaction[0]->user_id) @else disabled @endif id="akun_kas_awal" data-width="100%" name="akun_kas_awal" class="form-control" required>
                            <option value="" selected disabled hidden >Pilih Akun Kas</option>
                                @foreach($accountlistHarta as $accountvalue)
                                    <option value="{{ $accountvalue->id }}"{{$showDetailTransaction1[0]->account_id == $accountvalue->id  ? 'selected' : ''}}>{{$accountvalue->tipe}} || {{ $accountvalue->nama}}</option>
                                @endforeach
                            </select>
                        </div>                                                                        
                        <div class="form-group">
                            <label for="akun_kas_tujuan" class="col-form-label">Kas Departemen Tujuan:</label>
                            <select @if (auth()->user()->id==$showTransaction[0]->user_id) @else disabled @endif id="akun_kas_tujuan" data-width="100%" name="akun_kas_tujuan" class="form-control" required>
                            <option value="" selected disabled hidden>Pilih Akun Kas</option>
                                @foreach($accountlistHarta as $accountvalue)
                                    <option value="{{ $accountvalue->id }}"{{$showDetailTransaction2[0]->account_id == $accountvalue->id  ? 'selected' : ''}}>{{$accountvalue->tipe}} || {{ $accountvalue->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="departement_tujuan" class="col-form-label">Departemen Tujuan:</label>
                            <select @if (auth()->user()->id==$showTransaction[0]->user_id) @else disabled @endif id="departement_tujuan" data-width="100%" name="departement_tujuan" class="form-control" required>
                                <option value="" selected disabled hidden>Pilih Departement Tujuan</option>
                                @foreach($listDepartement as $departementvalue)
                                    <option value="{{ $departementvalue->id }}"{{$showTransaction[1]->departement_id == $departementvalue->id  ? 'selected' : ''}}>{{ $departementvalue->nama}}</option>
                                @endforeach
                            </select>
                        </div>                                                                        
                            <div class="form-group">
                            <label for="tgl_transfer" class="col-form-label">Tanggal:</label>
                            <div class="input-group">
                                <input @if (auth()->user()->id==$showTransaction[0]->user_id) @else disabled @endif id="tgl_transfer" value="{{$showTransaction[0]->tanggal}}" required type="date" name="tgl_transfer" class="form-control" placeholder="mm/dd/yyyy">
                            </div>
                        </div>
                    <div class="form-group">
                        <label for="no_spb_transfer" class="col-form-label">No.SPB:</label>
                        <input @if (auth()->user()->id==$showTransaction[0]->user_id) @else disabled @endif required name="no_spb_transfer" value="{{$showTransaction[0]->no_spb}}" type="text" class="form-control" id="no_spb_transfer" maxlength="35" placeholder="No.Urut/Departemen/UNBL/Tahun">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Keterangan</span>
                        </div>
                        <textarea @if (auth()->user()->id==$showTransaction[0]->user_id) @else disabled @endif name="keterangan_transfer" rows="2" cols="30" maxlength="255" class="form-control" maxlength="100" aria-label="With textarea">{{$showTransaction[0]->keterangan}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="nominal_transfer" class="col-form-label">Nominal Transfer:</label>
                        <input @if (auth()->user()->id==$showTransaction[0]->user_id) @else disabled @endif required name="nominal_transfer" value="{{$showDetailTransaction1[0]->nominal}}" type="text" class="form-control" id="nominal_transfer" maxlength="14" placeholder="Rp">
                    </div>
                    </div>
                    <input type="text" hidden value="{{$showTransaction[0]->id}}" name="id_1">
                    <input type="text" hidden value="{{$showTransaction[1]->id}}" name="id_2">
                    <div class="modal-footer">
                                <a href="{{ route('transaksi.index') }}"><button type="button" class="btn btn-secondary">Kembali</button></a>
                                @if (auth()->user()->id==$showTransaction[0]->user_id)<button disabled type="submit" class="btn btn-primary" id="submitForm">Simpan
                                </button>
                    </form>
                                 @else  @endif                              
                                @if (auth()->user()->jabatan="Bendahara Operasional")
                                <button type="button" class="btn mb-1 btn-rounded btn-primary" data-toggle="modal" data-target="#cetakSPB"><span class="btn-icon-left" style="background:rgb(82, 83, 85);"><i class="fa fa-print"></i></span>Cetak SPB</button>
                                <div class="modal fade" id="cetakSPB" tabindex="-2" role="dialog" aria-labelledby="cetakSPBModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Cetak SPB</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('updateTransfer',$showTransaction[0]->id)}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" hidden value="{{$showTransaction[0]->id}}" name="id_1">
                                        <input type="text" hidden value="{{$showTransaction[1]->id}}" name="id_2">
                                        <input type="hidden" name="kode" value="spb">
                                        <div class="form-group">
                                            <label for="kepada" class="col-form-label">Kepada :</label>
                                            <input name="kepada" value="{{$showTransaction[0]->kepada}}" maxlength="255" type="text" class="form-control" id="kepada" placeholder="Penerima Dana">
                                        </div>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Catatan Perpajakan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            </div>
                                            <textarea name="ctt_pajak" rows="2" cols="30" maxlength="255" class="form-control" aria-label="With textarea">{{$showTransaction[0]->ctt_pajak}}</textarea>
                                        </div>
                                        </br>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Catatan Perbendaharaan</span>
                                            </div>
                                            <textarea name="ctt_bendahara" rows="2" cols="30" maxlength="255" class="form-control" aria-label="With textarea">{{$showTransaction[0]->ctt_bendahara}}</textarea>
                                        </div>
                                    </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan&Cetak</button>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                </div>   
                                @else
                                @endif   
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('edit_transfer-script')
    <script>    
        $(document).ready(function() {
            $('.akun_kas_tujuan').select2();
        });
        $('#akun_kas_tujuan').select2({

    });
    </script>
        <script>    
        $(document).ready(function() {
            $('.akun_kas_awal').select2();
        });
        $('#akun_kas_awal').select2({

    });
    </script>
    <script>    
        $(document).ready(function() {
            $('.departement_tujuan').select2();
        });
        $('#departement_tujuan').select2({

    });
    </script>
    <script type="text/javascript">
        $('#submitForm').on('click', function(e){
        e.preventDefault();
        var form = $(this).parents('form');
        Swal.fire({
        title: "Simpan perubahan data?",
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "Simpan",
        denyButtonText: `Tidak`
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                document.getElementById("formTF").submit();
            } else if (result.isDenied) {
                Swal.fire("Perubahan data tidak disimpan", "", "info");
            }
        });
    });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#formTF').on('input change',function(){
                $('#submitForm').attr('disabled', false);
            });
        });
    </script>
@endpush