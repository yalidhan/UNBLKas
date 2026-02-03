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

    .audit-item {
    background: #f8f9fa;
    background-color:#4385f5!important;
    color:#fff;
    border-radius: 6px;
    padding: 10px;
    margin-bottom: 10px;
    }

    .audit-actions .btn {
    width: 32px;
    height: 32px;
    padding: 0;
    line-height: 32px;
    text-align: center;
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
                                <h3 class="card-title">Audit Transaksi</h3>   
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
                                                    <select id="filter_departemen" name="departement" class="form-select form-select-sm">
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
                                                <td>{{$trans->keterangan}}<span style="font-size:11px;color: #8898aa !important;"><br>input by {{$trans->user->name}}</span></td>
                                                <td>{{\Carbon\Carbon::parse($trans->tanggal)->format('d/m/Y')}}</td>
                                                <td>Rp {{number_format($trans->transaction_details_sum_nominal,0,',','.')}}</td>
                                                <td>
                                                    <span class="badge badge-{{ 
                                                        $trans->audit_status === 'verified' ? 'success' :
                                                        ($trans->audit_status === 'rejected' ? 'danger' :
                                                        ($trans->audit_status === 'under_review' ? 'primary' : 'warning'))
                                                    }}">
                                                        {{ ucfirst(str_replace('_',' ',$trans->audit_status ?? 'pending review')) }}
                                                    </span>
                                                <td>
                                                    <!-- Button trigger modal -->
                                                    <button class="btn btn-primary btn-audit"
                                                        type="button"
                                                        class="btn btn-primary btn-audit"
                                                        data-toggle="modal"
                                                        data-target="#staticBackdrop"

                                                        data-id="{{ $trans->id }}"
                                                        data-no_spb="{{ $trans->no_spb }}"
                                                        data-tanggal="{{ \Carbon\Carbon::parse($trans->tanggal)->format('d/m/Y') }}"
                                                        data-jumlah="Rp {{ number_format($trans->transaction_details_sum_nominal,0,',','.') }}"
                                                        data-status="{{ $trans->audit_status ?? 'Pending Review' }}"
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
                                        <div class="modal-header justify-content-center" style="background-color:rgb(117, 113, 249);">
                                            <h4 class="modal-title font-weight-bold text-uppercase" id="modalStatus" style="color:#fff;">
                                            Pending Review
                                            </h4>
                                            <button type="button" class="close position-absolute" style="right: 15px"
                                                    data-dismiss="modal">
                                            <span>&times;</span>
                                            </button>
                                        </div>

                                        <!-- BODY -->
                                        <div class="modal-body">

                                            <!-- ACTION BUTTONS -->
                                            <div class="text-center mb-4">
                                            <button class="btn btn-success px-4 mr-2">
                                                ✓ Verifikasi
                                            </button>
                                            <button class="btn btn-danger px-4">
                                                ✕ Tolak
                                            </button>
                                            </div>

                                            <!-- DETAIL TRANSAKSI -->
                                            <div class="card border-start border-success shadow-sm" style="background-color:#4385f5!important;">
                                                <div class="card-body" style="color:#fff;">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <h5 class="font-weight-bold">Detail Transaksi</h5>
                                                            <div>No SPB : <strong id="modalNoSpb"></strong></div>
                                                            <div>Tanggal : <strong id="modalTanggal"></strong></div>
                                                            <div>Jumlah : <strong id="modalJumlah"></strong></div>
                                                            <i class="fa fa-paperclip mr-1"></i> Lampiran
                                                                <button class="btn btn-outline-success btn-sm btn-block mt-2" style="color:#fff;">
                                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Lampiran (Pembayaran Gaji Pokok.pdf)
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
                                            <h6 class="font-weight-bold">Riwayat Catatan Audit</h6>
                                            </div>

                                            <div class="audit-item d-flex justify-content-between align-items-center">

                                                <div class="audit-content pr-2">
                                                    <strong>(28 Juli 2025, 15.00 WITA, Muhammad Ali Amd. Ak.)</strong><br>
                                                    Bukti transaksi sudah sesuai
                                                </div>
                                                <div class="audit-actions d-flex">
                                                    <button class="btn btn-sm btn-outline-warning audit-btn mr-1"
                                                                data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="Edit Catatan">
                                                        
                                                        ✏️
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger audit-btn"
                                                                data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="Hapus Catatan">
                                                        🗑️
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="audit-item d-flex justify-content-between align-items-center">
                                                <div class="audit-content pr-2">
                                                    <strong>(28 Juli 2025, 15.00 WITA, Muhammad Ali Amd. Ak.)</strong><br>
                                                    Bukti transaksi sudah sesuai
                                                </div>
                                                <div class="audit-actions d-flex">
                                                    <button class="btn btn-sm btn-outline-warning audit-btn mr-1"
                                                                data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="Edit Catatan">
                                                        
                                                        ✏️
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger audit-btn"
                                                                data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="Hapus Catatan">
                                                        🗑️
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="audit-item d-flex justify-content-between align-items-start">
                                                <div class="audit-content pr-2">
                                                    <strong>(28 Juli 2025, 15.00 WITA, Muhammad Ali Amd. Ak.)</strong><br>
                                                    Bukti transaksi sudah sesuai
                                                </div>
                                                <div class="audit-actions text-right">
                                                <div class="audit-actions d-flex">
                                                    <button class="btn btn-sm btn-outline-warning audit-btn mr-1"
                                                                data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="Edit Catatan">
                                                        ✏️
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger audit-btn"
                                                                data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="Hapus Catatan">
                                                        🗑️
                                                    </button>
                                                </div>
                                            </div>

                                                </div>
                                                <!-- ADD NOTE -->
                                                <div class="input-group mt-3">
                                                <input type="text" class="form-control" placeholder="Tambah Catatan">
                                                <div class="input-group-append">
                                                    <button class="btn btn-success">
                                                    ➕ Tambah
                                                    </button>
                                                </div>
                                                </div>

                                            </div>
                                        

                                        <!-- FOOTER -->
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal">Close</button>
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
            theme: 'bootstrap-5',
            placeholder: 'Pilih Departemen',
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
<script>
    $('#staticBackdrop').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);

        // get data from button
        const id      = button.data('id');
        const noSpb   = button.data('no_spb');
        const tanggal = button.data('tanggal');
        const jumlah  = button.data('jumlah');
        const status  = button.data('status');

        // fill modal
        $('#modalTransactionId').val(id);
        $('#modalNoSpb').text(noSpb);
        $('#modalTanggal').text(tanggal);
        $('#modalJumlah').text(jumlah);
        $('#modalStatus').text(status);

        // optional: change badge color based on status
        // Verified / Rejected / Pending Review
    });
</script>
<script
  src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.11/dist/dotlottie-wc.js"
  type="module"
></script>
@endpush