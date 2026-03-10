@extends('master')
@section('content')
@push('audit-style')
    /* Row search */
    #audit thead .filter-row th {
        padding: 4px 6px;
    }

    /* Input kecil & rapi */
    #audit thead .column-search {
        width: 100%;
        height: 28px;
        padding: 2px 6px;
        font-size: 12px;
        border-radius: 4px;
        border: 1px solid #ced4da;
    }

    #audit thead .column-search:focus {
        outline: none;
        box-shadow: none;
        border-color: #80bdff;
    }
    .card .form-label {
        margin-bottom: 4px;
        color: #555;
    }

    .card input[type="month"],
    .card select {
        height: 32px;
        font-size: 13px;
    }

    .card .btn-sm {
        height: 32px;
    }
    <!-- Modal css -->
    body.modal-open {
    overflow: hidden;
    }

    .modal-body {
    max-height: 70vh;
    overflow-y: auto;
    }
    
    .audit-modal {
    <!-- background-color:rgb(117, 113, 249); -->
     <!-- background-color: #fff;     -->
    }

    .audit-btn {
    width: 32px;
    height: 32px;
    padding: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    line-height: 1; /* kill baseline offset */
    }


    .modal-header {
    border-bottom: 2px solid #333;
    }

    .modal-body {
    font-size: 0.95rem;
    }
    <!-- Responsive Table -->
    /* Prevent wrapping */
    .table-audit {
        white-space: nowrap;
    }

    /* Sticky Status Audit (2nd last column) */
    .table-audit th:nth-last-child(2),
    .table-audit td:nth-last-child(2) {
        position: sticky;
        right: 120px; /* must match Aksi width */
        background: #fff;
        z-index: 2;
        min-width: 150px;
        text-align: center;
    }

    /* Sticky Aksi (last column) */
    .table-audit th:last-child,
    .table-audit td:last-child {
        position: sticky;
        right: 0;
        background: #fff;
        z-index: 3;
        min-width: 120px;
        text-align: center;
    }

    /* Header layering */
    .table-audit thead th:nth-last-child(2) {
        z-index: 4;
        <!-- background: #f8f9fa; -->
    }
    .table-audit thead th:last-child {
        z-index: 5;
        <!-- background: #f8f9fa; -->
    }

    /* Small screen optimization */
    @media (max-width: 768px) {
        .table-audit th,
        .table-audit td {
            padding: 0.45rem;
            font-size: 0.85rem;
        }

        .btn-audit {
            padding: 0.25rem 0.6rem;
            font-size: 0.75rem;
        }
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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Audit Transaksi</a></li>
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
                                <h1 class="card-title">Audit Transaksi</h1>   
                            </center>
                            <!-- Card Filter -->
                            <div class="col-md-12 mb-3">
                                <div class="card border-start border-success shadow-sm" style="background-color:rgb(117, 113, 249);">
                                    <div class="card-body" style="color:#fff;">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fa fa-filter text-success me-2"></i>
                                            &nbsp;
                                            <h5 class="mb-0 fw-bold text-uppercase" style="color:#fff;">Filter Data</h5>
                                        </div>
                                        <form method="GET" action="{{ route('transaction_audits.index') }}">
                                        <div class="row g-3 align-items-end">
                                            <!-- Periode -->
                                            
                                                <div class="col-md-4" >
                                                    <label class="form-label fw-semibold small" style="color:#fff;">Periode</label>
                                                    <input type="month" name="periode" id="filter_periode" class="form-control form-control-sm">
                                                </div>

                                                <!-- Departemen -->
                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold small" style="color:#fff;">Departemen</label>
                                                    <select id="filter_departemen" name="departement" class="form-control form-control-sm">
                                                        <option value="">Pilih Departement</option>
                                                        @foreach($departement as $dept)
                                                            <option value="{{ $dept->id }}">{{ $dept->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Buttons -->
                                                <div class="col-md-4 d-flex gap-2" >
                                                    <button style="color:#fff;"id="btnFilter" type="submit" class="btn btn-success btn-sm px-4">
                                                        <i class="fa fa-search me-1"></i> Terapkan
                                                    </button>
                                                    &nbsp;
                                                    <button style="color:#fff;" id="btnReset" class="btn btn-warning btn-sm px-4">
                                                        <i class="fa fa-refresh me-1"></i> Reset
                                                    </button>
                                                </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <center>
                                <h4>
                                    Daftar Transaksi Periode 
                                    {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}
                                    - Pada Departemen 
                                    {{ $departementName->nama ?? 'Semua Departemen' }}
                                </h4>
                            </center>
                                <div class="table-responsive table-audit-wrapper">
                                    <table id="audit" class="table align-items-center mb-0 table-audit">
                                        <thead>
                                            <tr>
                                                <th>No.SPB</th>
                                                <th>Keterangan</th>
                                                <th>Tanggal SPB</th>
                                                <th>Jumlah</th>
                                                <th>Status Audit</th>
                                                <th>Aksi</th>
                                            </tr>
                                            <tr class="filter-row">
                                                <th><input type="text" class="column-search" placeholder="Cari..." /></th>
                                                <th><input type="text" class="column-search" placeholder="Cari..." /></th>
                                                <th><input type="text" class="column-search" placeholder="Cari..." /></th>
                                                <th><input type="text" class="column-search" placeholder="Cari..." /></th>
                                                <th><input type="text" class="column-search" placeholder="Cari..." /></th>
                                                <th style="width:110px"></th> <!-- Aksi: no search -->
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($transaction as $trans)
                                            <tr>
                                                <td>{{$trans->no_spb}}</td>
                                                <td>{{$trans->keterangan}}<span style="font-size:11px;color: #8898aa !important;"><br>input by {{$trans->user->name}}</span>
                                                <br>
                                                @if($trans->status_transaksi=='on_progress')
                                                       <span class="badge badge-warning" style="font-size:11px;color: #000 !important;">Status Transaksi : Dalam Penyelesaian  </span><br>
                                                   @else
                                                       <span class="badge badge-success" style="font-size:11px;color: #000 !important;">Status Transaksi : Selesai  </span><br>
                                                   @endif
                                                   @if($trans->bukti_file_path=='')
                                                       <span class="badge badge-warning" style="font-size:11px;color: #000 !important;">Status Bukti : Belum Upload Bukti  </span><br>
                                                   @else
                                                       <span class="badge badge-success" style="font-size:11px;color: #000 !important;">Status Bukti : Sudah Upload Bukti  </span><br>
                                                   @endif
                                                </td>
                                                <td>{{\Carbon\Carbon::parse($trans->tanggal)->format('d/m/Y')}}</td>
                                                <td>Rp {{number_format($trans->transaction_details_sum_nominal,0,',','.')}}</td>
                                                <td>
                                                @php
                                                $status = $trans->audit?->status ?? 'pending_review';
                                                @endphp

                                                <span id="auditStatusBadge-{{ $trans->id }}"
                                                    class="badge audit-status-badge
                                                    badge-{{
                                                        $status === 'verified' ? 'success' :
                                                        ($status === 'rejected' ? 'danger' :
                                                        ($status === 'under_review' ? 'primary' : 'warning'))
                                                    }}">
                                                    {{ ucfirst(str_replace('_',' ',$status)) }}
                                                </span>
                                                </td>
                                                <td>
                                                    <!-- Button trigger modal -->
                                                    <button class="btn btn-primary btn-audit"
                                                        type="button"
                                                        class="btn btn-primary btn-audit"
                                                        data-toggle="modal"
                                                        data-target="#staticBackdrop"

                                                        data-id="{{ $trans->id }}"
                                                        data-bukti_file_name="{{ $trans->bukti_file_name }}"
                                                        data-bukti_file_path="{{ $trans->bukti_file_path }}"
                                                        data-no_spb="{{ $trans->no_spb }}"
                                                        data-tanggal="{{ \Carbon\Carbon::parse($trans->tanggal)->format('d/m/Y') }}"
                                                        data-jumlah="Rp {{ number_format($trans->transaction_details_sum_nominal,0,',','.') }}"
                                                        data-audit_status="{{ $trans->audit?->status}}"
                                                        data-audit_id="{{ $trans->audit?->id ?? '' }}"
                                                        data-auditor_name="{{ $trans->audit?->auditor->name ?? '' }}"
                                                        data-audited_at="{{ $trans->audit?->audited_at ? \Carbon\Carbon::parse($trans->audit?->audited_at)->format('d/m/Y H:i') : '' }}"
                                                        data-tooltip="tooltip"
                                                        data-placement="top"
                                                        title="Audit">
                                                        
                                                         <i class="fa fa-clipboard"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
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
                                        <div class="text-center mb-4">
                                            <form id="auditForm"
                                                action="{{ route('transaction_audits.store') }}"
                                                method="POST">
                                                @csrf

                                                <input type="hidden" name="transaction_id" id="modalTransactionId">
                                                <input type="hidden" id="modalAuditId">
                                                <input type="hidden" name="status" id="audit_status">
                                                <button type="button"
                                                        id="btnVerify"
                                                        class="btn btn-success"
                                                        onclick="confirmAudit('verified')">
                                                    ✓ Verifikasi
                                                </button>

                                                <button type="button"
                                                        id="btnReject"
                                                        class="btn btn-danger"
                                                        onclick="confirmAudit('rejected')">
                                                    ✕ Tolak
                                                </button>
                                            </form>
                                        </div>
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


                                                <div id="auditNotesContainer">
                                                    <div class="text-center text-muted">Memuat catatan...</div>
                                                </div>
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
                                            
                                            </div>
                                        

                                        <!-- FOOTER -->
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal">Close</button>
                                        </div>

                                        </div>
                                    </div>
                                </div>

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
                                                        style="width:100%; height:100%; display:none;">
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
@push ('audit-script')

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
<!-- <script>
    $('#anggaran').DataTable({
    order: [[0, 'desc']]
});
</script> -->
<script>
    $(document).ready(function () {

        var table = $('#audit').DataTable({
            order: [[2, 'asc']],
            columnDefs: [
                { targets: 5, orderable: false, searchable: false } // kolom Aksi
            ]
        });

        // Apply search per column (thead)
        $('#audit thead tr.filter-row th').each(function (index) {
            $('input', this).on('keyup change clear', function () {
                if (table.column(index).search() !== this.value) {
                    table.column(index).search(this.value).draw();
                }
            });
        });

    });
</script>
<script>
    $(document).ready(function () {
        $('#filter_departemen').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Departemen 🔽',
            allowClear: true,
            width: '100%'
        });
    });
</script>
<script>
    $(document).ready(function () {

        var table = $('#audit').DataTable();

        $('#btnFilter').on('click', function () {
            let periode = $('#filter_periode').val();
            let departemen = $('#filter_departemen').val();

            // contoh: kolom 0 = periode, kolom 1 = departemen
            table.column(0).search(periode);
            table.column(1).search(departemen).draw();
        });

        $('#btnReset').on('click', function () {
            $('#filter_periode').val('');
            $('#filter_departemen').val(null).trigger('change');

            table.search('').columns().search('').draw();
        });

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