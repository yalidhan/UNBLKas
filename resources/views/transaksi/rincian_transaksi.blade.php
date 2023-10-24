@extends('master')

@section('content')
<div class="content-body">
            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/transaksi">Transaksi</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Rincian Transaksi</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h5 class="card-title">Rincian Transaksi</h5></div>
                <div class="card-body">
                    <div class="card text-left" style="font-weight:bold;font-size:120%;">
                        <div class="card-header">
                            Data Transaksi
                        </div>
                        <div class="card-body">
                        <div class="table-responsive"> 
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>Departemen</td>
                                                <td>&nbsp;:</td>
                                                <td>&nbsp;Rektorat</td>
                                            </tr>
                                            <tr>
                                                <td>No. SPB</td>
                                                <td>&nbsp;:</td>
                                                <td>&nbsp;011/REKTORAT/2023</td>
                                            </tr>
                                            <tr>
                                                <td>Keterangan</td>
                                                <td>&nbsp;:</td>
                                                <td>&nbsp;Pembelian ATK</td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal</td>
                                                <td>&nbsp;:</td>
                                                <td>&nbsp;10/06/2023</td>
                                            </tr>
                                            <tr>
                                                <td>Total Transaksi</td>
                                                <td>&nbsp;:</td>
                                                <td>&nbsp;Rp 150.000</td>
                                            </tr>
                                        </tbody>
                                    </table>
                        </div>
                        <div class="card-footer text-muted">
                        <button type="button" class="btn mb-1 btn-primary" data-toggle="modal" data-target="#tambah_rincian">Tambah Rincian<span class="btn-icon-right"><i class="fa fa-cart-plus"></i></button>
                            <div class="modal fade bd-example-modal-lg" id="tambah_rincian" tabindex="-1" role="dialog" aria-labelledby="tambah_rincianModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="tambah_rincianModalLabel">Tambah Rincian</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                            <div class="modal-body">
                                                <form action="#" method="POST" id="forms">
                                                @csrf
                                                    <div class="form-group">
                                                        <label for="akun_rincian" class="col-form-label">Akun</label>
                                                        <select id="akun_rincian" data-width="100%" name="akun_rincian" class="form-control" required>
                                                            <option value="" selected disabled hidden>Pilih Akun</option>
                                                            <option value="01">Beban ATK</option>
                                                            <option value="02">Beban Cetak</option>
                                                            <option value="03">Beban Perawatan Gedung</option>
                                                            <option value="04">Gaji Pokok</option>
                                                            <option value="05">Tunjangan Transport</option>
                                                            <option value="06">Konsumsi Pegawai</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="nominal_tambah_rincian" class="col-form-label">Nominal :</label>
                                                        <input required name="nominal_tambah_rincian" type="text" class="form-control" id="nominal_tambah_rincian" placeholder="Rp">
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
                    </div>
                </div>
                <div class="table-responsive"> 
                    <table class="table table-bordered table-striped verticle-middle">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Akun</th>
                                <th scope="col">Nominal</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Beban Cetak</td>
                                <td>Rp 100.000</td>
                                <td><span><a href="#" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil color-muted m-r-5"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-close color-danger"></i></a></span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Beban ATK</td>
                                <td>Rp 50.000</td>
                                <td><span><a href="#" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil color-muted m-r-5"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-close color-danger"></i></a></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <center><a href="/transaksi" class="btn btn-success">Kembali</a></center>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
