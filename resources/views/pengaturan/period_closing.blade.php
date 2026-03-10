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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Pengaturan</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->
            <!-- Container -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <center>
                                        <h1 class="card-title">Tutup Buku Periode</h1>
                                        </center>
                                        @php
                                            $currentMonth = now()->month;
                                            $currentYear = now()->year;
                                            $isCurrentPeriodClosed = $ClosedPeriodsGrouped
                                                ->flatten()
                                                ->where('month', $currentMonth)
                                                ->where('year', $currentYear)
                                                ->isNotEmpty();
                                        @endphp
                                        
                                        <div class="alert {{ $isCurrentPeriodClosed ? 'alert-danger' : 'alert-success' }}">
                                            <strong>Periode Terbuka Saat Ini:</strong>
                                            {{ \Carbon\Carbon::now()->format('F Y') }}
                                            <br>
                                            <strong>Status:</strong>
                                            @if($isCurrentPeriodClosed)
                                                <span class="badge badge-danger">🔒 Ditutup</span>
                                            @else
                                                <span class="badge badge-success">🔓 Terbuka</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="mt-0">Periode yang Sudah Ditutup</h5>

                                    @if($ClosedPeriodsGrouped->isEmpty())
                                        <div class="text-muted">
                                            Belum ada periode yang ditutup
                                        </div>
                                    @else

                                    <div id="closedPeriodAccordion">

                                        @foreach($ClosedPeriodsGrouped as $year => $periods)

                                            <div class="card mb-2">

                                                <!-- YEAR HEADER -->
                                                <div class="card-header p-2">
                                                    <a class="d-flex justify-content-between align-items-center text-dark font-weight-bold"
                                                    data-toggle="collapse"
                                                    href="#year{{ $year }}"
                                                    role="button"
                                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}">

                                                        <span>
                                                            <i class="fa fa-calendar"></i>
                                                            {{ $year }}
                                                        </span>

                                                        <i class="fa fa-chevron-right toggle-chevron"></i>

                                                    </a>

                                                </div>

                                                <!-- MONTH LIST -->
                                                <div id="year{{ $year }}" class="collapse {{ $loop->first ? 'show' : '' }}">
                                                    <ul class="list-group list-group-flush">

                                                        @foreach($periods as $p)
                                                            <li class="list-group-item d-flex justify-content-between">

                                                                {{ \DateTime::createFromFormat('!m', $p->month)?->format('F') ?? $p->month }}
                                                                    @if($p->reopen_expires_at && now()->lt($p->reopen_expires_at))

                                                                        <span class="badge badge-success" style="line-height:1.5!important" title="Dibuka sementara oleh SPI">
                                                                            🔓 Open until {{ \Carbon\Carbon::parse($p->reopen_expires_at)->format('d M Y H:i') }}
                                                                        </span>

                                                                    @else

                                                                        <span class="badge badge-danger" style="line-height:1.5!important">
                                                                            🔒 Closed
                                                                        </span>

                                                                    @endif

                                                            </li>
                                                        @endforeach

                                                    </ul>
                                                </div>

                                            </div>

                                        @endforeach

                                    </div>

                                    @endif

                                </div>
                            </div>

                                <div class="card">
                                    <div class="card-body">
                                        <form id="closingForm" action="{{ route('period.close') }}" method="POST">
                                        @csrf

                                        @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif

                                        <h5 class="mt-0">Tutup Buku Periode</h5>

                                        <div class="row align-items-end">

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Tahun</label>
                                                    <select name="year" class="form-control" required>
                                                        <option value="" disabled selected>Pilih Tahun</option>
                                                        @for ($y = now()->year; $y >= 2024; $y--)
                                                            <option value="{{ $y }}">{{ $y }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Bulan</label>
                                                    @php
                                                    $currentYear = now()->year;
                                                    $currentMonth = now()->month;
                                                    @endphp

                                                    <select name="month" class="form-control" required>
                                                        <option value="" disabled selected>Pilih Bulan</option>

                                                        @for ($m = 1; $m <= 12; $m++)
                                                            <option value="{{ $m }}"
                                                                data-month="{{ $m }}">
                                                                {{ \DateTime::createFromFormat('!m', $m)->format('F') }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="alert alert-info mt-4">
                                                    <strong>Info:</strong><br>
                                                    Setelah ditutup, transaksi pada periode ini tidak dapat diubah.
                                                </div>
                                            </div>

                                        </div>

                                        <hr>

                                        <div class="alert alert-warning">
                                            <i class="fa fa-exclamation-triangle"></i>
                                            <strong>Perhatian!</strong><br>
                                            Tutup buku akan mengunci semua transaksi pada bulan tersebut. User yang memiliki akses untuk menutup buku dan mengajukan request buka buku adalah Bendahara Operasional Rektorat & Yayasan,
                                             untuk membuka kembali diperlukan persetujuan SPI.
                                        </div>
                                        @if (auth()->user()->jabatan == 'Bendahara Operasional'
                                            && in_array(auth()->user()->departement_id, [6, 1]))
                                        <div class="form-check mb-3">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="confirmClosing"
                                                name="confirm_closing"
                                                style="border: 2px solid #050404; width: 20px; height: 20px;"
                                                required>

                                            <label class="form-check-label" for="confirmClosing">
                                                Saya yakin ingin menutup periode ini
                                            </label>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-danger btn-lg">
                                                <i class="fa fa-lock"></i> Tutup Buku
                                            </button>
                                        </div>
                                        @else
                                            <div class="alert alert-danger">
                                                <i class="fa fa-exclamation-circle"></i>
                                                Maaf, Anda tidak memiliki akses untuk menutup periode ini.
                                        @endif
                                        </form>
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
@push('pengaturan-script')
<script>
    document.getElementById("closingForm").addEventListener("submit", function(e) {

        e.preventDefault();

        const monthSelect = document.querySelector('[name="month"]');
        const yearSelect  = document.querySelector('[name="year"]');

        const monthText = monthSelect.options[monthSelect.selectedIndex]?.text;
        const yearText  = yearSelect.value;

        if (!monthText || !yearText) {
            Swal.fire({
                icon: "warning",
                title: "Periode belum dipilih",
                text: "Silakan pilih bulan dan tahun terlebih dahulu"
            });
            return;
        }

        Swal.fire({
            title: "Tutup Buku?",
            html: `
                Anda akan menutup periode:<br>
                <strong>${monthText} ${yearText}</strong><br><br>
                Setelah ditutup:<br>
                ❌ Tidak bisa create/edit/delete transaksi<br>
                🔓 Reopen hanya melalui persetujuan SPI
            `,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, Tutup Buku",
            cancelButtonText: "Batal",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit();
            }
        });

    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const yearSelect  = document.querySelector('[name="year"]');
        const monthSelect = document.querySelector('[name="month"]');

        function updateMonthOptions() {

            const selectedYear = parseInt(yearSelect.value);
            const currentYear  = new Date().getFullYear();
            const currentMonth = new Date().getMonth() + 1;

            monthSelect.querySelectorAll("option").forEach(option => {

                const month = parseInt(option.value);

                if (!month) return; // skip placeholder

                // Disable future months
                if (selectedYear > currentYear ||
                (selectedYear === currentYear && month > currentMonth)) {

                    option.disabled = true;

                    // Reset selection if needed
                    if (option.selected) monthSelect.value = "";
                } else {
                    option.disabled = false;
                }
            });
        }

        yearSelect.addEventListener("change", updateMonthOptions);

    });
</script>
<script>
$(document).ready(function () {

    $('.collapse').each(function () {

        var collapseId = $(this).attr('id');
        var toggle = $('[href="#' + collapseId + '"]');
        var icon = toggle.find('.toggle-chevron');

        // Initial state
        if ($(this).hasClass('show')) {
            icon.removeClass('fa-chevron-right')
                .addClass('fa-chevron-down');
        }

        // When opened
        $(this).on('show.bs.collapse', function () {
            icon.removeClass('fa-chevron-right')
                .addClass('fa-chevron-down');
        });

        // When closed
        $(this).on('hide.bs.collapse', function () {
            icon.removeClass('fa-chevron-down')
                .addClass('fa-chevron-right');
        });

    });

});
</script>
@endpush


