@extends('master')
@section('content')
@php use Carbon\Carbon; @endphp
@push('transaksi-style')
    /* Original header stays in layout */
    .ledger-table thead {
        background: #f8f9fa;
    }

    /* Sticky cloned header */
    .sticky-header {
        position: fixed;
        top: 0;
        z-index: 1000;
        background: #f8f9fa;
        box-shadow: 0 2px 8px rgba(0,0,0,.08);
        display: none;
    }

    .sticky-header th {
        box-sizing: border-box;
    }
    .ledger-table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .ledger-table thead.is-sticky {
        position: fixed;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 8px rgba(0,0,0,.08);
    }
    .ledger-table thead th {
        position: sticky;
        top: 0;
        z-index: 5;
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    .ledger-table th,
    .ledger-table td {
        padding: 0.45rem 0.65rem;
        font-size: 0.95rem;
        vertical-align: middle;
    }
    /* Saldo column */
    .ledger-table th:nth-child(7),
    .ledger-table td:nth-child(7) {
        font-weight: 700;
        background: linear-gradient(
            to left,
            rgba(45,206,137,0.10),
            rgba(45,206,137,0.03)
        );
        width: 130px;
    }
    /* Numbers right-aligned */
    .ledger-table th:nth-child(5),
    .ledger-table th:nth-child(6),
    .ledger-table th:nth-child(7),
    .ledger-table td:nth-child(5),
    .ledger-table td:nth-child(6),
    .ledger-table td:nth-child(7) {
        text-align: right;
        white-space: nowrap;
    }

    /* Description left aligned */
    .ledger-table th:nth-child(4),
    .ledger-table td:nth-child(4) {
        text-align: left;
    }

    @media print {
    .ledger-wrapper {
         overflow-x: auto;
    }
    }
    
    /* Sticky last column (Aksi) */
    .ledger-table th:last-child,
    .ledger-table td:last-child {
        position: sticky;
        right: 0;
        z-index: 6;
        background: #fff;
        width: 90px;
        min-width: 90px;
    }

    /* Make sure header stays above body */
    .ledger-table thead th:last-child {
        z-index: 7;
        background: #f8f9fa;
    }

    /* Optional shadow so user sees it's fixed */
    .ledger-table th:last-child::before,
    .ledger-table td:last-child::before {
        content: "";
        position: absolute;
        left: -6px;
        top: 0;
        height: 100%;
        width: 6px;
        background: linear-gradient(to left, rgba(0,0,0,0.15), transparent);
    }
    /* Saldo sticky */
    .ledger-table th:nth-last-child(2),
    .ledger-table td:nth-last-child(2) {
        position: sticky;
        right: 90px;
        background: #fff;
        z-index: 5;
    }
    .ledger-table {
    table-layout: auto;
    }
    .ledger-table {
    table-layout: fixed;
    width: 100%;
    }

    /* No */
.ledger-table th:nth-child(1),
.ledger-table td:nth-child(1) {
    width: 50px;
}

/* No.SPB */
.ledger-table th:nth-child(2),
.ledger-table td:nth-child(2) {
    width: 130px;
}

/* Tanggal */
.ledger-table th:nth-child(3),
.ledger-table td:nth-child(3) {
    width: 100px;
}

/* Debet */
.ledger-table th:nth-child(5),
.ledger-table td:nth-child(5) {
    width: 120px;
}

/* Kredit */
.ledger-table th:nth-child(6),
.ledger-table td:nth-child(6) {
    width: 120px;
}
.ledger-table th:nth-child(4),
.ledger-table td:nth-child(4) {
    word-break: break-word;
}
    /* ===============================
   CHAT / NOTE UI
=============================== */

/* Container spacing */
#auditNotesContainer {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

/* Wrapper controls alignment */
.note-wrapper {
    display: flex;
    width: 100%;
}

.owner-note-wrapper {
    justify-content: flex-end;
}

.other-note-wrapper {
    justify-content: flex-start;
}

/* Chat bubble base */
.audit-item {
    max-width: 65%;
    padding: 14px 16px;
    border-radius: 18px;
    position: relative;
    font-size: 14px;
    line-height: 1.4;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.15s ease;
}

/* Slight hover lift */
.audit-item:hover {
    transform: translateY(-2px);
}

/* OTHER USER (left side) */
.other-bubble {
    background: linear-gradient(135deg, #6f6bf2, #8c88ff);
    color: #fff;
    border-top-left-radius: 4px;
}

/* CURRENT USER (right side) */
.owner-bubble {
    background: #e9f3ff;
    color: #000;
    border-top-right-radius: 4px;
    border-left: 4px solid #0d6efd;
}

/* Metadata line */
.audit-item strong {
    font-size: 12px;
    opacity: 0.85;
    display: block;
    margin-bottom: 4px;
}

/* Footer row */
.message-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 8px;
}

/* LEFT SIDE BUTTONS */
.message-actions-inline {
    display: flex;
    gap: 6px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

/* Show buttons only when hovering bubble */
.audit-item:hover .message-actions-inline {
    opacity: 1;
}

/* STATUS (always visible) */
.message-status {
    font-size: 11px;
    opacity: 0.7;
}

/* Clean icon buttons */
.action-btn {
    background: rgba(255,255,255,0.85);
    border: none;
    padding: 4px 6px;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    transition: background 0.2s ease;
}

.action-btn:hover {
    background: rgba(255,255,255,1);
}
.message-actions-inline {
    display: flex;
    gap: 6px;
    opacity: 0;
    transform: translateY(4px);
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.audit-item:hover .message-actions-inline {
    opacity: 1;
    transform: translateY(0);
}
/* ===============================
   FLOATING ACTION BUTTONS
=============================== */

/* Hidden by default */
.message-actions {
    position: absolute;
    top: 8px;
    right: 10px;
    display: flex;
    gap: 6px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

/* Show on hover */
.audit-item:hover .message-actions {
    opacity: 1;
}

/* Minimal action buttons */
.action-btn {
    background: rgba(255,255,255,0.85);
    border: none;
    padding: 4px 6px;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    transition: background 0.2s ease;
}

.action-btn:hover {
    background: rgba(255,255,255,1);
}
/* Message read status */
.message-status {
    font-size: 11px;
    margin-top: 6px;
    text-align: right;
    opacity: 0.7;
}
.audit-status-under-review {
    color: #ffc107;
}
.modal-header {
    transition: background-color 0.5s ease;
}
.audit-status-badge{
    transition: all .5s ease;
}
@endpush
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Transaksi</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <h4 class="card-title">Buku Kas</h4>
                                <div class="card mb-3">
                                    <div class="card-body" style="background: linear-gradient(135deg, #f0f4ff, #d9e2ff);">
                                        <h4 class="card-title">Filter Transaksi</h4>
                                        <form method="GET" action="{{ url('transaksi') }}">
                                            <div class="row align-items-end">

                                                <!-- YEAR -->
                                                <div class="col-md-2">
                                                    <label>Tahun</label>
                                                    <select name="tahun" class="form-control">
                                                        <option value="">Semua</option>
                                                        @for($y = date('Y'); $y >= 2020; $y--)
                                                            <option value="{{ $y }}" {{ request('tahun')==$y ? 'selected' : '' }}>
                                                                {{ $y }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                <!-- STATUS TRANSAKSI -->
                                                <div class="col-md-3">
                                                    <label>Status Transaksi</label>
                                                    <select name="status_transaksi" class="form-control">
                                                        <option value="">Semua</option>
                                                        <option value="selesai" {{ request('status_transaksi')=='selesai' ? 'selected' : '' }}>Selesai</option>
                                                        <option value="on_progress" {{ request('status_transaksi')=='on_progress' ? 'selected' : '' }}>On Progress</option>
                                                    </select>
                                                </div>

                                                <!-- STATUS BUKTI -->
                                                <div class="col-md-3">
                                                    <label>Status Bukti</label>
                                                    <select name="status_bukti" class="form-control">
                                                        <option value="">Semua</option>
                                                        <option value="uploaded" {{ request('status_bukti')=='uploaded' ? 'selected' : '' }}>Sudah Upload</option>
                                                        <option value="not_uploaded" {{ request('status_bukti')=='not_uploaded' ? 'selected' : '' }}>Belum Upload</option>
                                                    </select>
                                                </div>

                                                <!-- STATUS AUDIT -->
                                                <div class="col-md-2">
                                                    <label>Status Audit</label>
                                                        <select name="status_audit" class="form-control">
                                                            <option value="">Semua</option>

                                                            <option value="verified" {{ request('status_audit')=='verified' ? 'selected' : '' }}>
                                                                Sudah Diaudit
                                                            </option>

                                                            <option value="pending_review" {{ request('status_audit')=='pending_review' ? 'selected' : '' }}>
                                                                Menunggu Audit
                                                            </option>

                                                            <option value="under_review" {{ request('status_audit')=='under_review' ? 'selected' : '' }}>
                                                                Dalam Proses Audit
                                                            </option>

                                                            <option value="rejected" {{ request('status_audit')=='rejected' ? 'selected' : '' }}>
                                                                Ditolak
                                                            </option>
                                                        </select>
                                                </div>

                                                <!-- BUTTON -->
                                                <div class="col-md-2">
                                                    <button type="submit" class="btn btn-success btn-block mb-1">
                                                        <i class="fa fa-filter"></i> Filter
                                                    </button>

                                                    <a href="{{ url('transaksi') }}" class="btn btn-secondary btn-block">
                                                        Reset
                                                    </a>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <form action="transaksi" method="get">    
                                                           
                                            <div class="form-group">
                                                <label for="periode" class="col-form-label">Periode:</label>
                                                    <div class="input-group">
                                                        <input id="periode" type="month" name="periode" class="form-control">
                                                    </div>   
                                                </br><button type="submit" class="btn mb-1 btn-success">Filter<span class="btn-icon-right"><i class="fa fa-filter"></i></span>
                                            </button>
                                            </div>
                                        </form>
                                    </div>
                                    @if (auth()->user()->jabatan=="Bendahara Operasional")
                                    <div class="col-md-9 align-self-center">
                                            <!-- <button type="button" class="btn mb-1 btn-primary">pemasukan<span class="btn-icon-right"><i class="fa fa-money"></i></span></button> -->
                                            <!-- <div class="bootstrap-modal"> -->
                                                <!-- if (auth()->user()->departement_id==1) -->
                                                    <button type="button" class="btn mb-1 btn-primary" data-toggle="modal" data-target="#pemasukan">Pemasukan<span class="btn-icon-right"><i class="fa fa-money"></i></button>
                                                <!-- else
                                                
                                                endif -->
                                                <div class="modal fade bd-example-modal-lg" id="pemasukan" tabindex="-1" role="dialog" aria-labelledby="pemasukanModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="pemasukanModalLabel">Pemasukan</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="/transaksi" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                        <div class="form-group">
                                                                            <label for="tgl_pemasukan" class="col-form-label">Tanggal:</label>
                                                                            <div class="input-group">
                                                                                <input id="tgl_pemasukan" required name="tgl_pemasukan" type="date" class="form-control" placeholder="mm/dd/yyyy">
                                                                            </div>
                                                                        </div>
                                                                    <div class="form-group">
                                                                        <label for="kode_pemasukan" class="col-form-label">Kode Pemasukan:</label>
                                                                        <input maxlength="25" required name="kode_pemasukan" type="text" class="form-control" id="kode_pemasukan" placeholder="No Cek/No Ref/No Record/Kode Transaksi">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="akun_pendapatan" class="col-form-label">Akun Pendapatan/Pengembalian</label>
                                                                        <select id="akun_pendapatan" data-width="100%" name="akun_pendapatan" class="form-control" required>
                                                                            <option value="" selected disabled hidden>Pilih Akun</option>
                                                                            @foreach($accountlistPendapatan as $accountvalue)
                                                                                <option value="{{ $accountvalue->id }}">{{$accountvalue->tipe}} || {{ $accountvalue->nama}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">Keterangan</span>
                                                                        </div>
                                                                        <textarea name="keterangan_pemasukan" rows="2" cols="30" maxlength="255" class="form-control" aria-label="With textarea"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="nominal_pemasukan" class="col-form-label">Nominal Pemasukan:</label>
                                                                        <input required name="nominal_pemasukan" type="text" maxlength="14" class="form-control" id="nominal_pemasukan" placeholder="Rp">
                                                                    </div>
                                                                    <!-- Bukti Transaksi (PDF only, max 5MB) -->
                                                                    <div class="form-group mt-3">
                                                                        <label for="bukti_transaksi">Bukti Transaksi (PDF, max 5MB)</label>
                                                                        <div class="drop-area border rounded p-3 text-center" style="cursor:pointer;">
                                                                            <p class="mb-2">Drag & drop PDF di sini atau klik</p>

                                                                            <input type="file"
                                                                                name="bukti_transaksi"
                                                                                class="bukti-input"
                                                                                accept="application/pdf"
                                                                                style="display:none;">

                                                                            <span class="file-name text-muted">Belum ada file</span>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Status Transaksi -->
                                                                    <div class="form-group mt-3">
                                                                        <label>Status Transaksi</label>

                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="status_transaksi" id="status_selesai" value="selesai" required>
                                                                            <label class="form-check-label" for="status_selesai">Selesai</label>
                                                                        </div>

                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="status_transaksi" id="status_progress" value="on_progress">
                                                                            <label class="form-check-label" for="status_progress" checked>On Progress</label>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" value="{{auth()->user()->departement_id}}" name="id_departement">
                                                                    <input type="hidden" value="{{auth()->user()->id}}" name="user_id">
                                                                    <input type="hidden" value="1" name="dk">
                                                                    <input type="hidden" value="pemasukan" name="tp_trx">
                                                            </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                                    </div>
                                                                </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <!-- </div> -->
                                            <!-- <button type="button" class="btn mb-1 btn-info">Pengeluaran<span class="btn-icon-right"><i class="fa fa-shopping-cart"></i></span></button> -->
                                            <!-- <div class="bootstrap-modal"> -->
                                                <button type="button" class="btn mb-1 btn-danger" data-toggle="modal" data-target="#pengeluaran">Pengeluaran<span class="btn-icon-right"><i class="fa fa-shopping-cart"></i></button>
                                                    <div class="modal fade" id="pengeluaran" tabindex="-1" role="dialog" aria-labelledby="pengeluaranModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="pengeluaranModalLabel">Pengeluaran</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="/transaksi" method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                            <label for="tgl_pengeluaran" class="col-form-label">Tanggal:</label>
                                                                            <div class="input-group">
                                                                                <input required type="date" name="tgl_pengeluaran" class="form-control" placeholder="mm/dd/yyyy">
                                                                            </div>
                                                                        </div>
                                                                    <div class="form-group">
                                                                        <label for="no_spb_pengeluaran" class="col-form-label">No.SPB:</label>
                                                                        <input required name="no_spb_pengeluaran" maxlength="35" type="text" class="form-control" id="no_spb_pengeluaran" placeholder="No.Urut/Departemen/UNBL/Tahun">
                                                                    </div>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">Keterangan</span>
                                                                        </div>
                                                                        <textarea required name="keterangan_pengeluaran" rows="2" cols="30" maxlength="255" class="form-control" aria-label="With textarea"></textarea>
                                                                    </div>
                                                                    <!-- Bukti Transaksi (PDF only, max 5MB) -->
                                                                    <div class="form-group mt-3">
                                                                        <label for="bukti_transaksi">Bukti Transaksi (PDF, max 5MB)</label>
                                                                        <div class="drop-area border rounded p-3 text-center" style="cursor:pointer;">
                                                                            <p class="mb-2">Drag & drop PDF di sini atau klik</p>

                                                                            <input type="file"
                                                                                name="bukti_transaksi"
                                                                                class="bukti-input"
                                                                                accept="application/pdf"
                                                                                style="display:none;">

                                                                            <span class="file-name text-muted">Belum ada file</span>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Status Transaksi -->
                                                                    <div class="form-group mt-3">
                                                                        <label>Status Transaksi</label>

                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="status_transaksi" id="status_selesai" value="selesai" required>
                                                                            <label class="form-check-label" for="status_selesai">Selesai</label>
                                                                        </div>

                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="status_transaksi" id="status_progress" value="on_progress">
                                                                            <label class="form-check-label" for="status_progress" checked>On Progress</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                                        </div>
                                                                        <input type="hidden" value="{{auth()->user()->departement_id}}" name="id_departement">
                                                                        <input type="hidden" value="{{auth()->user()->id}}" name="user_id">
                                                                        <input type="hidden" value="pengeluaran" name="tp_trx">
                                                                    </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <!-- </div> -->
                                            <!-- <button type="button" class="btn mb-1 btn-secondary">Transfer<span class="btn-icon-right"><i class="fa fa-send"></i></span></button> -->
                                            <!-- <div class="bootstrap-modal"> -->
                                                <button type="button" class="btn mb-1 btn-warning" data-toggle="modal" data-target="#transfer">Transfer<span class="btn-icon-right"><i class="fa fa-send"></i></button>
                                                        <div class="modal fade bd-example-modal-lg" id="transfer" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="transferModalLabel">Transfer</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <!-- <div class="card border-dark">
                                                                            <div class="card-body text-dark">
                                                                                <h5 class="card-title">Informasi Saldo</h5>
                                                                                <p class="card-text">User : User Admin Fakultas</p>
                                                                                <p class="card-text">Departemen : Fakultas Farmasi  </p>
                                                                                <p class="card-text">Saldo : Rp 50.000.000 </p>
                                                                            </div>
                                                                        </div> -->
                                                                        <form action="transaksi" method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <div class="form-group">
                                                                            <label for="akun_kas_awal" class="col-form-label">Kas Departemen Awal:</label>
                                                                            <select id="akun_kas_awal" data-width="100%" name="akun_kas_awal" class="form-control" required>
                                                                            <option value="" selected disabled hidden>Pilih Akun Kas</option>
                                                                                @foreach($accountlistHarta as $accountvalue)
                                                                                    <option value="{{ $accountvalue->id }}">{{$accountvalue->tipe}} || {{ $accountvalue->nama}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>                                                                        
                                                                        <div class="form-group">
                                                                            <label for="akun_kas_tujuan" class="col-form-label">Kas Departemen Tujuan:</label>
                                                                            <select id="akun_kas_tujuan" data-width="100%" name="akun_kas_tujuan" class="form-control" required>
                                                                            <option value="" selected disabled hidden>Pilih Akun Kas</option>
                                                                                @foreach($accountlistHarta as $accountvalue)
                                                                                    <option value="{{ $accountvalue->id }}">{{$accountvalue->tipe}} || {{ $accountvalue->nama}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="departement_tujuan" class="col-form-label">Departemen Tujuan:</label>
                                                                            <select id="departement_tujuan" data-width="100%" name="departement_tujuan" class="form-control" required>
                                                                                <option value="" selected disabled hidden>Pilih Departement Tujuan</option>
                                                                                @foreach($listDepartement as $departementvalue)
                                                                                    <option value="{{ $departementvalue->id }}">{{ $departementvalue->nama}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>                                                                        
                                                                            <div class="form-group">
                                                                            <label for="tgl_transfer" class="col-form-label">Tanggal:</label>
                                                                            <div class="input-group">
                                                                                <input id="tgl_transfer" required type="date" name="tgl_transfer" class="form-control" placeholder="mm/dd/yyyy">
                                                                            </div>
                                                                        </div>
                                                                    <div class="form-group">
                                                                        <label for="no_spb_transfer" class="col-form-label">No.SPB:</label>
                                                                        <input required name="no_spb_transfer" maxlength="35" type="text" class="form-control" id="no_spb_transfer" placeholder="No.Urut/Departemen/UNBL/Tahun">
                                                                    </div>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">Keterangan</span>
                                                                        </div>
                                                                        <textarea name="keterangan_transfer" rows="2" cols="30" maxlength="255" class="form-control" aria-label="With textarea"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="nominal_transfer" class="col-form-label">Nominal Transfer:</label>
                                                                        <input required name="nominal_transfer" maxlength="14" type="text" class="form-control" id="nominal_transfer" placeholder="Rp">
                                                                    </div>
                                                                    <!-- Bukti Transaksi (PDF only, max 5MB) -->
                                                                    <div class="form-group mt-3">
                                                                        <label for="bukti_transaksi">Bukti Transaksi (PDF, max 5MB)</label>
                                                                        <div class="drop-area border rounded p-3 text-center" style="cursor:pointer;">
                                                                            <p class="mb-2">Drag & drop PDF di sini atau klik</p>

                                                                            <input type="file"
                                                                                name="bukti_transaksi"
                                                                                class="bukti-input"
                                                                                accept="application/pdf"
                                                                                style="display:none;">

                                                                            <span class="file-name text-muted">Belum ada file</span>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Status Transaksi -->
                                                                    <div class="form-group mt-3">
                                                                        <label>Status Transaksi</label>

                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="status_transaksi" id="status_selesai" value="selesai" required>
                                                                            <label class="form-check-label" for="status_selesai">Selesai</label>
                                                                        </div>

                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="status_transaksi" id="status_progress" value="on_progress">
                                                                            <label class="form-check-label" for="status_progress" checked>On Progress</label>
                                                                        </div>
                                                                    </div>
                                                                    </div>
                                                                        <input type="hidden" value="{{auth()->user()->departement_id}}" name="id_departement_pengirim">
                                                                        <input type="hidden" value="{{auth()->user()->id}}" name="user_id">
                                                                        <input type="hidden" value="transfer" name="tp_trx">
                                                                        <input type="hidden" value="2" name="dk_1">
                                                                        <input type="hidden" value="1" name="dk_2">
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                                            </div>
                                                                        </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                            <!-- </div> -->                                    
                                    </div>
                                    @else
                                    @endif
                                </div>
                                @if($errors->first())
                                    <div class="alert alert-danger" role="alert">
                                    {{$errors->first()}}
                                    </div>
                                    @else
                                @endif
                            </br>
                                <center>
                                <h4>
                                Kas {{ auth()->user()->departement->nama }} Periode
                                @if($month)
                                    {{ \Carbon\Carbon::createFromDate($year,$month,1)->format('F Y') }}
                                @else
                                    Tahun {{ $year }}
                                @endif
                                </h4>
                                </center>
                                {{-- ========================= --}}
                                {{-- PERIOD STATUS ALERT --}}
                                {{-- ========================= --}}

                                @if($periodStatus == 'closed')

                                    <div class="alert alert-danger">
                                        <i class="fa fa-lock"></i>
                                        <strong>Periode Ditutup</strong><br>
                                        Transaksi pada periode ini tidak dapat dibuat, diubah, atau dihapus.
                                    </div>

                                @elseif($periodStatus == 'temporary_open')
                                    <div class="alert alert-success">
                                        <i class="fa fa-unlock"></i>
                                        <strong>Periode Dibuka Sementara</strong><br>
                                        Berlaku sampai:
                                        <strong>{{ \Carbon\Carbon::parse($reopenExpires)->format('d M Y H:i') }}</strong>
                                    </div>
                                @else

                                    <div class="alert alert-info">
                                        <i class="fa fa-check-circle"></i>
                                        <strong>Periode Aktif</strong><br>
                                        Periode ini masih aktif dan dapat digunakan.
                                    </div>

                                @endif
                                @if($advancedFilter)
                                <div class="alert alert-info">
                                <i class="fa fa-exclamation-triangle"></i> Menampilkan hasil filter. Saldo tidak ditampilkan pada mode filter.
                                </div>
                                @endif
                                <div class="table-responsive table-transaksi ledger-wrapper"> 
                                    <table class="table table-bordered verticle-middle ledger-table" style="color: #222222;font-size: 1rem;">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">No.SPB</th>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">Keterangan</th>
                                                <th scope="col">Debet</th>
                                                <th scope="col">Kredit</th>
                                                @if(!$advancedFilter)
                                                <th scope="col">Saldo</th>
                                                @endif
                                                <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php 
                                                $saldo = 0 ;
                                                $saldo=$saldo+$saldoLastMonth;
                                            @endphp
                                            @if(!$advancedFilter)
                                            <tr>
                                                <td colspan="6"><b>Saldo Awal</b></td>
                                                <td colspan="2"><b>Rp {{ number_format($saldo,0,',','.') }}</b></td>
                                            </tr>
                                            @endif
                                            @php $no = 1 ; @endphp
                                            @foreach ($transactionlist as $transaction)
                                            <tr>
                                                <td>{{$no++}}</td>
                                                <td>{{$transaction->no_spb}}</td>
                                                <td>{{\Carbon\Carbon::parse($transaction->tanggal)->format('d/m/Y')}}</td>
                                                <td>{{$transaction->keterangan}} <span style="font-size:11px;color: #8898aa !important;"><br>input by {{$transaction->name}}</span><br>
                                                   @if($transaction->status_transaksi=='on_progress')
                                                       <span class="badge badge-warning" style="font-size:11px;color: #000 !important;">Status Transaksi : Dalam Penyelesaian  </span><br>
                                                   @else
                                                       <span class="badge badge-success" style="font-size:11px;color: #000 !important;">Status Transaksi : Selesai  </span><br>
                                                   @endif
                                                   @if($transaction->bukti_file_path=='')
                                                       <span class="badge badge-warning" style="font-size:11px;color: #000 !important;">Status Bukti : Belum Upload Bukti  </span><br>
                                                   @else
                                                       <span class="badge badge-success" style="font-size:11px;color: #000 !important;">Status Bukti : Sudah Upload Bukti  </span><br>
                                                   @endif
                                                   @if (($transaction->audit?->status ?? 'pending') == 'verified')
                                                       <span class="badge badge-success" style="font-size:11px;color: #000 !important;">Status Audit : Sudah DiAudit  </span><br>
                                                    @elseif (($transaction->audit?->status ?? 'pending') == 'pending_review' || is_null($transaction->audit))
                                                        <span class="badge badge-warning" style="font-size:11px;color: #000 !important;">Status Audit : Menunggu Audit  </span><br>
                                                    @elseif (($transaction->audit?->status ?? 'pending') == 'rejected')
                                                        <span class="badge badge-danger" style="font-size:11px;color: #000 !important;">Status Audit : Ditolak  </span><br>
                                                    @elseif (($transaction->audit?->status ?? 'pending') == 'under_review')
                                                        <span class="badge badge-info" style="font-size:11px;color: #000 !important;">Status Audit : Dalam Proses Audit  </span><br>
                                                    @endif
                                                    <br>
                                                
                                                    <button class="btn btn-info btn-audit-preview"
                                                            type="button"
                                                            data-toggle="modal"
                                                            data-target="#staticBackdrop"

                                                            data-id="{{ $transaction->id }}"
                                                            data-no_spb="{{ $transaction->no_spb }}"
                                                            data-tanggal="{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d/m/Y') }}"
                                                            data-jumlah="Rp {{ number_format($transaction->total,0,',','.') }}"

                                                            data-bukti_file_name="{{ basename($transaction->bukti_file_path) }}"
                                                            data-bukti_file_path="{{ $transaction->bukti_file_path }}"

                                                            data-audit_id="{{ $transaction->audit?->id }}"
                                                            data-audit_status="{{ $transaction->audit?->status ?? 'pending' }}"
                                                            data-auditor_name="{{ $transaction->audit?->auditor->name ?? '-' }}"
                                                            data-audited_at="{{ $transaction->audit?->audited_at 
                                                                ? \Carbon\Carbon::parse($transaction->audit->audited_at)->format('d/m/Y H:i') 
                                                                : '-' }}">

                                                        <i class="fa fa-eye"></i> Lihat Audit 
                                                            @if($transaction->audit?->unread_notes > 0)
                                                            <span id="auditUnreadBadge-{{ $transaction->id }}"
                                                                class="badge badge-danger audit-unread-badge"
                                                                data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="Ada {{ $transaction->audit->unread_notes }} catatan audit yang belum dibaca">
                                                                {{ $transaction->audit->unread_notes }}
                                                            </span>
                                                            @endif
                                                    </button>
                                                
                                                    
                                                </td>
                                                @if ($transaction->dk==1)
                                                    <td style="white-space: nowrap;"> Rp {{number_format($transaction->total,0,',','.'),
                                                        $saldo=$saldo+$transaction->total}}</td>
                                                @else 
                                                    <td style="white-space: nowrap;">Rp 0</td>
                                                @endif
                                                @if ($transaction->dk==2)
                                                    <td style="white-space: nowrap;"> Rp {{number_format($transaction->total,0,',','.'),
                                                        $saldo=$saldo-$transaction->total}}</td>
                                                @else 
                                                    <td style="white-space: nowrap;">Rp 0</td>
                                                @endif
                                                @if(!$advancedFilter)
                                                <td style="white-space: nowrap;">Rp {{number_format($saldo,0,',','.')}}</td>
                                                @endif
                                                <td><span style="display: flex;">
                                                        @if ($transaction->no_trf=="")
                                                            <a href="{{route('transaksi.show',$transaction->id)}}" data-toggle="tooltip" data-placement="top" title="Rincian"><i class="fa fa-eye color-danger"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                        @else
                                                            <a href="{{route('showTransfer',$transaction->no_trf)}}" data-toggle="tooltip" data-placement="top" title="Rincian"><i class="fa fa-eye color-danger"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                        @endif
                                                        @if (auth()->user()->id==$transaction->user_id)
                                                        <form
                                                            @if ($transaction->no_trf=="")
                                                                action="{{route('transaksi.destroy',$transaction->id)}}"
                                                            @else
                                                                action="{{route('transaksi.destroy',"$transaction->no_trf")}}"
                                                            @endif
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
                                    </table>
                                    <!-- Modal Audit &-->
                                    <div class="modal fade" id="staticBackdrop"
                                        data-backdrop="static"
                                        data-keyboard="false"
                                        tabindex="-1">

                                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                            <div class="modal-content audit-modal">

                                        <!-- HEADER -->
                                        <div class="modal-header justify-content-center" id="auditHeader">
                                            <h4 class="modal-title font-weight-bold text-uppercase" id="modalStatus">
                                                Pending Review
                                            </h4>

                                            <button type="button"
                                                    class="close position-absolute"
                                                    style="right: 15px"
                                                    data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>

                                            <!-- BODY -->
                                            <div class="modal-body">

                                            <!-- ACTION BUTTONS -->
                                            <div id="auditVerifiedCard"
                                                class="card d-none"
                                                style="background-color:rgb(117, 113, 249);">
                                                <div class="card-body" style="border-color:#fff;color:#fff;">
                                                    <blockquote class="blockquote mb-0">
                                                        <p>Transaksi Ini Telah DiAudit ✔</p>
                                                        <footer class="blockquote-footer" style="color:#000;">
                                                            Auditor - <span id="modalAuditorName"></span> 
                                                            (Tanggal <cite id="modalAuditedAt" title="Source Title"></cite> WITA)
                                                        </footer>
                                                    </blockquote>
                                                </div>
                                            </div>
                                                <!-- DETAIL TRANSAKSI -->
                                                <div class="card border-start border-success shadow-sm" style="background-color:rgb(117, 113, 249);">
                                                    <div class="card-body" style="color:#fff;">
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12">
                                                                <h5 class="font-weight-bold">Detail Transaksi</h5>
                                                                <div>No SPB : <strong id="modalNoSpb"></strong></div>
                                                                <div>Tanggal : <strong id="modalTanggal"></strong></div>
                                                                <div>Jumlah : <strong id="modalJumlah"></strong></div>
                                                                <i class="fa fa-paperclip mr-1"></i> Lampiran

                                                                <!-- <button type="button"
                                                                        class="btn btn-outline-success btn-sm btn-block mt-2 btn-preview-pdf"
                                                                        data-toggle="modal"
                                                                        data-target="#pdfPreviewModal"
                                                                        style="color:#fff;">
                                                                    <i class="fa fa-file-pdf-o"></i>
                                                                    Lampiran
                                                                </button> -->
                                                                <button type="button"
                                                                        id="btnPreviewPdf"
                                                                        class="btn btn-outline-secondary btn-sm btn-block mt-2"
                                                                        disabled>
                                                                    <i class="fa fa-ban"></i>
                                                                    Tidak Ada File Bukti Terupload
                                                                </button>
                                                                <input type="hidden" id="modalTransactionId">
                                                            </div>
                                                            <div class="col-md-6 col-sm-12">
                                                                <dotlottie-wc
                                                                src="https://lottie.host/bfba7991-310b-4c42-8b76-76abc7d8d8ea/FYS6PEdcLd.lottie"
                                                            
                                                                autoplay
                                                                loop
                                                                ></dotlottie-wc>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>
                                                <!-- RIWAYAT AUDIT -->
                                                <div class="mb-2">
                                                        <h5 class="font-weight-bold">Riwayat Catatan Audit</h5>
                                                </div>
                                                <div id="auditNotesContainer">
                                                        <div class="text-center text-muted">Memuat catatan...</div>
                                                </div>
                                                    <!-- ADD NOTE -->
                                                    <div class="input-group mt-3">
                                                        <input type="text"
                                                            id="auditNoteInput"
                                                            class="form-control"
                                                            placeholder="Tambah Catatan">

                                                        <div class="input-group-append">
                                                            <button class="btn btn-success" id="saveNoteBtn">
                                                                ➕ Tambah
                                                            </button>
                                                            &nbsp;
                                                            <button class="btn btn-secondary d-none" id="cancelEditBtn">
                                                                ❌ Batal
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" id="editingNoteId">
                                            

                                            <!-- FOOTER -->
                                            <div class="modal-footer">
                                                <button class="btn btn-primary" data-dismiss="modal">Close</button>
                                            </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal Audit-->
                                </div>
                                    <!-- PDF modal preview -->
                                    <div class="modal fade" id="pdfPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                            <div class="modal-content">

                                                <!-- Header -->
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="fa fa-file-pdf-o text-danger"></i> Preview Lampiran
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>

                                                <!-- Body -->
                                                <div class="modal-body p-0 position-relative" style="height:80vh;">

                                                    <!-- Loading -->
                                                    <!-- <div id="pdfLoading"
                                                        class="d-flex justify-content-center align-items-center"
                                                        style="height:100%;">
                                                        <div class="text-center">
                                                            <div class="spinner-border text-success mb-2"></div>
                                                            <div>Memuat dokumen...</div>
                                                        </div>
                                                    </div> -->

                                                    <!-- Iframe -->
                                                    <iframe id="pdfIframe"
                                                            src=""
                                                            frameborder="0"
                                                            style="width:100%; height:100%;">
                                                    </iframe>
                                                </div>

                                                <!-- Footer -->
                                                <div class="modal-footer">
                                                    <a href="#" target="_blank" id="openNewTab" class="btn btn-secondary btn-sm">
                                                        <i class="fa fa-external-link"></i> Buka Tab Baru
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                                                        Tutup
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                <!-- Pdf Modal Preview -->
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
@push ('transaksi-script')
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
        <script>    
        $(document).ready(function() {
            $('.akun_pendapatan').select2();
        });
        $('#akun_pendapatan').select2({
        dropdownParent: $('#pemasukan')
    });
    </script>
    <script>    
        $(document).ready(function() {
            $('.akun_kas_tujuan').select2();
        });
        $('#akun_kas_tujuan').select2({
        dropdownParent: $('#transfer')
    });
    </script>
        <script>    
        $(document).ready(function() {
            $('.akun_kas_awal').select2();
        });
        $('#akun_kas_awal').select2({
        dropdownParent: $('#transfer')
    });
    </script>
    <script>    
        $(document).ready(function() {
            $('.departement_tujuan').select2();
        });
        $('#departement_tujuan').select2({
        dropdownParent: $('#transfer')
    });
    </script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.querySelector('.ledger-table');
        const originalThead = table.querySelector('thead');

        const stickyThead = originalThead.cloneNode(true);
        stickyThead.classList.add('sticky-header');
        document.body.appendChild(stickyThead);

        function syncWidths() {
            const originalThs = originalThead.querySelectorAll('th');
            const stickyThs = stickyThead.querySelectorAll('th');

            stickyThead.style.width = table.offsetWidth + 'px';

            originalThs.forEach((th, i) => {
                const width = th.getBoundingClientRect().width;
                stickyThs[i].style.width = width + 'px';
            });
        }

        window.addEventListener('scroll', () => {
            const rect = table.getBoundingClientRect();
            const theadHeight = originalThead.offsetHeight;

            if (rect.top <= 0 && rect.bottom > theadHeight) {
                stickyThead.style.display = 'table';
                stickyThead.style.left = rect.left + 'px';
                syncWidths();
            } else {
                stickyThead.style.display = 'none';
            }
        });

        window.addEventListener('resize', syncWidths);
    });
</script>
<script>
    document.querySelectorAll(".drop-area").forEach(dropArea => {

        const fileInput = dropArea.querySelector(".bukti-input");
        const fileName  = dropArea.querySelector(".file-name");

        // Klik area → buka file chooser
        dropArea.addEventListener("click", () => fileInput.click());

        // Pilih file manual
        fileInput.addEventListener("change", handleFile);

        // Drag over
        dropArea.addEventListener("dragover", e => {
            e.preventDefault();
            dropArea.classList.add("bg-light");
        });

        // Drag leave
        dropArea.addEventListener("dragleave", () => {
            dropArea.classList.remove("bg-light");
        });

        // Drop file
        dropArea.addEventListener("drop", e => {
            e.preventDefault();
            dropArea.classList.remove("bg-light");

            fileInput.files = e.dataTransfer.files;
            handleFile();
        });

        function handleFile() {

            const file = fileInput.files[0];
            if (!file) return;

            // PDF only
            if (file.type !== "application/pdf") {
                alert("Hanya file PDF!");
                fileInput.value = "";
                fileName.textContent = "Belum ada file";
                return;
            }

            // Max 5MB
            if (file.size > 5 * 1024 * 1024) {
                alert("Ukuran maksimal 5MB!");
                fileInput.value = "";
                fileName.textContent = "Belum ada file";
                return;
            }

            fileName.textContent = file.name;
        }

    });
</script>
<script
  src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.11/dist/dotlottie-wc.js"
  type="module"
></script>
<script>
    const previewPdfBaseUrl = "{{ url('/transactions') }}";
    $('#pdfPreviewModal').on('show.bs.modal', function () {

        // 1️⃣ Get transaction ID (from hidden input or wherever you store it)
        const transactionId = $('#modalTransactionId').val();

        if (!transactionId) {
            alert('Transaction ID not found');
            return;
        }

        // 2️⃣ Cache buster (THIS IS WHAT YOU ASKED ABOUT)
        const cacheBuster = new Date().getTime();

        // 3️⃣ Build fresh PDF URL
        const pdfUrl = `${previewPdfBaseUrl}/${transactionId}/bukti/preview?v=${cacheBuster}`;

        // 4️⃣ Set iframe + new tab link
        $('#pdfIframe').attr('src', pdfUrl);
        $('#openNewTab').attr('href', pdfUrl);

        // 5️⃣ Loading state
        $('#pdfLoading').show();
        $('#pdfIframe').hide();
    });

    // When PDF finishes loading
    $('#pdfIframe').on('load', function () {
        $('#pdfLoading').hide();
        $(this).show();
    });

    // Cleanup (IMPORTANT for cache issues)
    $('#pdfPreviewModal').on('hidden.bs.modal', function () {
        $('#pdfIframe').attr('src', 'about:blank');
    });
</script>
<script>

    /* ===============================
    |  AUDIT CONFIRMATION
    =============================== */
    function confirmAudit(status) {

        const currentStatus = $('#audit_status').val();
        const transactionId = $('#modalTransactionId').val();

        if (!transactionId) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Transaction ID tidak ditemukan.'
            });
            return;
        }

        if (currentStatus === status) {
            Swal.fire({
                icon: 'info',
                title: 'Tidak Ada Perubahan',
                text: 'Status audit sudah sama.'
            });
            return;
        }

        const config = {
            verified: {
                title: 'Verifikasi Transaksi?',
                text: 'Transaksi akan ditandai sebagai TERVERIFIKASI',
                icon: 'question',
                confirmButtonText: 'Ya, Verifikasi',
                confirmButtonColor: '#28a745'
            },
            rejected: {
                title: 'Tolak Transaksi?',
                text: 'Transaksi akan DITOLAK dan tidak dapat dikembalikan',
                icon: 'warning',
                confirmButtonText: 'Ya, Tolak',
                confirmButtonColor: '#dc3545'
            }
        };

        Swal.fire({
            title: config[status].title,
            text: config[status].text,
            icon: config[status].icon,
            showCancelButton: true,
            confirmButtonText: config[status].confirmButtonText,
            cancelButtonText: 'Batal',
            confirmButtonColor: config[status].confirmButtonColor,
            reverseButtons: true
        }).then((result) => {

            if (!result.isConfirmed) return;

            // update hidden input
            $('#audit_status').val(status);

            // update badge BEFORE reload
            updateAuditBadge(transactionId, status);

            // update modal header
            updateAuditHeader(status);

            // disable buttons
            $('#btnVerify, #btnReject').prop('disabled', true);

            // submit form
            $('#auditForm').submit();
        });
    }


    /* ===============================
    |  MODAL INITIALIZATION
    =============================== */
    function updateUnreadBadge(transactionId, unreadCount) {

        const badgeId = `#auditUnreadBadge-${transactionId}`;
        let badge = $(badgeId);

        if (unreadCount > 0) {

            if (!badge.length) {

                const newBadge = `
                    <span id="auditUnreadBadge-${transactionId}"
                        class="badge badge-danger audit-unread-badge">
                        ${unreadCount}
                    </span>`;

                $(`button[data-id="${transactionId}"]`).append(newBadge);

            } else {

                badge.text(unreadCount);

            }

        } else {

            badge.remove();

        }
    }
    function updateAuditBadge(transactionId, status) {

        const badge = $(`#auditStatusBadge-${transactionId}`);

        if (!badge.length) return;

        badge.removeClass('badge-success badge-danger badge-primary badge-warning');

        let color = 'warning';
        let label = 'Pending Review';

        if (status === 'under_review') {
            color = 'primary';
            label = 'Under Review';
        }

        if (status === 'verified') {
            color = 'success';
            label = 'Verified';
        }

        if (status === 'rejected') {
            color = 'danger';
            label = 'Rejected';
        }

        badge
            .addClass(`badge-${color}`)
            .text(label);
    }
    function updateAuditHeader(status) {

        const header = $('#auditHeader');
        const title = $('#modalStatus');

        header.removeClass('bg-success bg-danger bg-info bg-warning');
        title.removeClass('text-white text-dark');

        switch (status) {

            case 'verified':
                header.addClass('bg-success');
                title.text('Verified').addClass('text-white');
                break;

            case 'rejected':
                header.addClass('bg-danger');
                title.text('Rejected').addClass('text-white');
                break;

            case 'under_review':
                header.addClass('bg-info');
                title.text('Under Review').addClass('text-white');
                break;

            default:
                header.addClass('bg-warning');
                title.text('Pending Review').addClass('text-dark');
        }
    }
    $('#staticBackdrop').on('show.bs.modal', function (event) {

        const button = event.relatedTarget ? $(event.relatedTarget) : null;
        if (!button) return;

        const id          = button.data('id');
        const noSpb       = button.data('no_spb');
        const tanggal     = button.data('tanggal');
        const jumlah      = button.data('jumlah');
        const auditorName = button.data('auditor_name');
        const auditedAt   = button.data('audited_at');
        const auditId     = button.data('audit_id');
        const auditStatus = button.data('audit_status');

        const fileName = button.data('bukti_file_name');
        const filePath = button.data('bukti_file_path');
        const previewBtn = $('#btnPreviewPdf');
        updateAuditHeader(auditStatus);
        // Fill modal
        $('#modalTransactionId').val(id);
        $('#modalNoSpb').text(noSpb);
        $('#modalTanggal').text(tanggal);
        $('#modalJumlah').text(jumlah);
        $('#modalAuditorName').text(auditorName);
        $('#modalAuditedAt').text(auditedAt);
        $('#modalAuditId').val(auditId);
        $('#audit_status').val(auditStatus);
        // ===============================
        // AUDIT STATUS HEADER
        // ===============================

        const statusMap = {
            under_review: 'Under Review ⏳',
            verified: 'Verified ✔',
            rejected: 'Rejected ❌'
        };

        $('#modalStatus').text(statusMap[auditStatus] || 'Pending Review ⚠');
        // Verified card
        if (auditStatus === 'verified') {
            $('#auditVerifiedCard').removeClass('d-none');
        } else {
            $('#auditVerifiedCard').addClass('d-none');
        }

        updateAuditButtons(auditStatus);

        // File preview
        if (filePath) {
            previewBtn
                .prop('disabled', false)
                .removeClass('btn-outline-secondary')
                .addClass('btn-outline-success')
                .html(`<i class="fa fa-file-pdf-o"></i> ${fileName}`);
        } else {
            previewBtn
                .prop('disabled', true)
                .removeClass('btn-outline-success')
                .addClass('btn-outline-secondary')
                .html(`<i class="fa fa-ban"></i> Tidak Ada File Bukti Terupload`);
        }

        /* ===============================
        |  LOAD AUDIT NOTES
        =============================== */

        const container = $('#auditNotesContainer');

        if (!auditId) {
            container.html('<div class="text-muted text-center">Belum ada catatan.</div>');
            return;
        }

        container.empty().html('<div class="text-center text-muted">Memuat catatan...</div>');

        fetch(`/audit-notes/${auditId}`)
            .then(res => res.json())
            .then(data => {

                if (!data.length) {
                    container.html('<div class="text-muted text-center">Belum ada catatan.</div>');
                    return;
                }

                let html = '';
                data.forEach(note => {
                    html += renderNoteHTML(note);
                });

                container.html(html);
                // mark as read visually
                updateUnreadBadge(id, 0);
            })
            .catch(() => {
                container.html('<div class="text-danger text-center">Gagal memuat catatan.</div>');
            });
    });


    /* ===============================
    |  SAVE NOTE (CREATE / UPDATE)
    =============================== */
    $('#saveNoteBtn').on('click', function () {

        const noteText = $('#auditNoteInput').val().trim();
        const auditId = $('#modalAuditId').val();
        const editingId = $('#editingNoteId').val();
        const transactionId = $('#modalTransactionId').val();

        if (!noteText) {
            Swal.fire('Error', 'Catatan tidak boleh kosong.', 'error');
            return;
        }

        let url = '/audit-notes';
        let method = 'POST';

        if (editingId) {
            url = `/audit-notes/${editingId}`;
            method = 'PUT';
        }

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                audit_id: auditId,
                transaction_id: transactionId,
                note: noteText
            })
        })
        .then(res => res.json())
        .then(note => {
            $('#modalAuditId').val(note.audit_id);
            // 
            updateUnreadBadge(transactionId, 0);
            // 🔹 Update modal status if audit created
            // If audit record was just created
            if (!editingId && note.audit_id) {

            updateAuditHeader('under_review');

            $('#audit_status').val('under_review');

            updateAuditBadge(transactionId, 'under_review');

                
            }

            if (editingId) {
                $(`#note-${note.id}`).replaceWith(renderNoteHTML(note));
            } else {
                $('#auditNotesContainer')
                .append(renderNoteHTML(note))
                .children()
                .last()
                .hide()
                .fadeIn(200);
            $('[data-toggle="tooltip"]').tooltip();
            $('#auditNotesContainer').animate({
                scrollTop: $('#auditNotesContainer')[0].scrollHeight
            }, 300);    
            }
                                        
            // $('#auditNoteInput').val('');
            // $('#editingNoteId').val('');
            // $('#saveNoteBtn').html('➕ Tambah');
            resetEditMode();
        });
    });


    /* ===============================
    |  EDIT NOTE
    =============================== */
    $(document).on('click', '.edit-note-btn', function () {

        const noteId = $(this).data('id');
        const noteText = $(`#note-${noteId}`)
            .find('.note-text')
            .text()
            .trim();

        $('#auditNoteInput').val(noteText);
        $('#editingNoteId').val(noteId);

        $('#saveNoteBtn')
            .removeClass('btn-success')
            .addClass('btn-primary')
            .html('💾 Simpan');

        $('#cancelEditBtn').removeClass('d-none');

        $('#auditNoteInput').focus();
    });
    $('#cancelEditBtn').on('click', function () {
        resetEditMode();
    });
    // Reset edit mode function
        function resetEditMode() {

            $('#auditNoteInput').val('');
            $('#editingNoteId').val('');

            $('#saveNoteBtn')
                .removeClass('btn-primary')
                .addClass('btn-success')
                .html('➕ Tambah');

            $('#cancelEditBtn').addClass('d-none');
        }
        $('#auditNoteInput').on('keydown', function(e) {
            if (e.key === 'Escape') {
                resetEditMode();
            }
        });
        /* ===============================
        |  DELETE NOTE
        =============================== */
            $(document).on('click', '.delete-note-btn', function () {

                const noteId = $(this).data('id');

                Swal.fire({
                    title: 'Hapus Catatan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus'
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    fetch(`/audit-notes/${noteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => {

                        // 🔐 CHECK UNAUTHORIZED HERE
                        if (res.status === 403) {
                            Swal.fire('Error', 'Anda tidak memiliki izin.', 'error');
                            return null; // stop chain
                        }

                        return res.json();
                    })
                    .then(data => {

                        if (!data) return; // if 403 happened

                        // ✅ Remove note normally
                        $(`#note-${noteId}`).fadeOut(200, function () {
                            $(this).remove();
                        });
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
                    });
                });
            });


        /* ===============================
        |  BUTTON STATE CONTROL
        =============================== */
        function updateAuditButtons(status) {

            $('#btnVerify, #btnReject').prop('disabled', false);

            if (status === 'verified') {
                $('#btnVerify').prop('disabled', true);
            }

            if (status === 'rejected') {
                $('#btnReject').prop('disabled', true);
            }
        }


        /* ===============================
        |  RENDER NOTE TEMPLATE
        =============================== */
    function renderNoteHTML(note) {

        const isOwner = note.is_owner === true;

        const editedLabel =
            note.created_at !== note.updated_at
            ? '<span class="text-warning ml-1">(Edited)</span>'
            : '';

        const wrapperClass = isOwner ? 'owner-note-wrapper' : 'other-note-wrapper';
        const bubbleClass = isOwner ? 'owner-bubble' : 'other-bubble';

        const actionButtons = isOwner
            ? `
                <div class="message-actions-inline">
                    <button class="action-btn edit-note-btn"
                            data-id="${note.id}"
                            data-toggle="tooltip"
                            title="Edit">
                        ✏️
                    </button>
                    <button class="action-btn delete-note-btn"
                            data-id="${note.id}"
                            data-toggle="tooltip"
                            title="Delete">
                        🗑️
                    </button>
                </div>
            `
            : '';

        const readStatus = isOwner
            ? `<div class="message-status">
                    ${note.read_at ? 'Sudah Dibaca ✓✓' : 'Terkirim ✓'}
            </div>`
            : '';

        return `
        <div class="note-wrapper ${wrapperClass}" id="note-${note.id}">
            <div class="audit-item ${bubbleClass}">
                <strong>
                    (${note.note_at}, ${note.notetaker})
                    ${editedLabel}
                </strong>

                <div class="note-text mt-1">${note.note}</div>

                <div class="message-footer">
                    ${actionButtons}
                    ${readStatus}
                </div>
            </div>
        </div>
        `;
    }



</script>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif
<script>
    $(document).ready(function () {

        $('#btnPreviewPdf').on('click', function (e) {
            e.preventDefault();

            if ($(this).prop('disabled')) return;

            // 🔒 Bootstrap 4 rule: close first modal
            $('#staticBackdrop').modal('hide');

            $('#staticBackdrop').one('hidden.bs.modal', function () {
                $('#pdfPreviewModal').modal('show');
            });
        });

    });
</script>
<script>
    $(document).ready(function () {

        $('#pdfPreviewModal').on('hidden.bs.modal', function () {
            $('#staticBackdrop').modal('show');
        });

    });
</script>
@endpush

