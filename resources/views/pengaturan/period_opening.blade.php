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
             <!-- container -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <center>
                                <h1 class="card-title">Request Buka Buku Periode</h1>
                                </center>
                                @if(session('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                                @endif
                                <form id="requestForm" action="{{ route('period.request') }}" method="POST">
                                @csrf

                                <div class="row mt-4">

                                    <!-- PERIODE -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Periode yang Akan Dibuka</label>

                                            <select id="periodSelect"
                                                    name="period_id"
                                                    class="form-control"
                                                    required>

                                                <option value="" disabled selected>Pilih Periode</option>

                                                @foreach ($ClosedPeriods as $p)

                                                    @php
                                                        $key = $p->year . '-' . $p->month;

                                                        $pending = $PendingRequests[$key] ?? null;

                                                        $isTempOpen = $p->reopen_expires_at &&
                                                                    now()->lt($p->reopen_expires_at);

                                                        $label = \DateTime::createFromFormat('!m', $p->month)->format('F')
                                                                . ' ' . $p->year;

                                                        if ($isTempOpen) {
                                                            $label .= ' — 🔓 Sedang Dibuka';
                                                        } elseif ($pending) {
                                                            $label .= ' — ⏳ Pending (' . $pending->requester->name . ')';
                                                        }
                                                    @endphp

                                                    <option value="{{ $p->id }}"
                                                            @if($isTempOpen || $pending) disabled @endif>

                                                        {{ \DateTime::createFromFormat('!m', $p->month)->format('F') }}
                                                        {{ $p->year }}

                                                        @if($isTempOpen)
                                                            — 🔓 Sedang Dibuka
                                                        @elseif($pending)
                                                            — ⏳ Pending ({{ $pending->requester->name }})
                                                        @endif

                                                    </option>

                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <!-- DURASI -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Durasi Pembukaan</label>
                                            <select name="duration" class="form-control" required>
                                                <option value="" disabled selected>Pilih Durasi</option>
                                                <option value="1">1 Jam</option>
                                                <option value="2">2 Jam</option>
                                                <option value="4">4 Jam</option>
                                                <option value="8">8 Jam</option>
                                                <option value="24">24 Jam</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <!-- ALASAN -->
                                <div class="form-group">
                                    <label>Alasan Permintaan</label>
                                    <textarea name="reason"
                                            rows="4"
                                            maxlength="500"
                                            class="form-control"
                                            placeholder="Jelaskan alasan pembukaan kembali periode..."
                                            required></textarea>
                                </div>

                                <!-- WARNING -->
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    <strong>Perhatian!</strong><br>
                                    Permintaan akan dikirim ke SPI untuk persetujuan.
                                    Periode hanya akan dibuka sementara sesuai durasi yang disetujui.
                                </div>

                                <!-- KONFIRMASI -->
                                <div class="form-check mb-3">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="confirm_request"
                                        id="confirmRequest"
                                        style="border: 2px solid #050404; width: 20px; height: 20px;"
                                        required>

                                    <label class="form-check-label" for="confirmRequest">
                                        Saya memahami konsekuensi pembukaan periode ini
                                    </label>
                                </div>
                                @if (auth()->user()->jabatan == 'Bendahara Operasional'
                                    && in_array(auth()->user()->departement_id, [6, 1]))
                                <!-- BUTTON -->
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fa fa-paper-plane"></i> Kirim Permintaan
                                    </button>
                                </div>
                                @else
                                <div class="alert alert-danger">
                                    <i class="fa fa-exclamation-circle"></i>
                                    Maaf, Anda tidak memiliki akses untuk mengirim permintaan buka periode.
                                @endif
                                </form>
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
    document.getElementById("requestForm").addEventListener("submit", function(e) {

        e.preventDefault();

        const periodSelect = document.getElementById("periodSelect");
        const duration     = document.querySelector('[name="duration"]');

        if (!periodSelect || !duration) return;

        const periodText =
            periodSelect.options[periodSelect.selectedIndex]?.text;

        const durationText =
            duration.options[duration.selectedIndex]?.text;

        if (!periodText || !durationText) {
            Swal.fire({
                icon: "warning",
                title: "Data belum lengkap",
                text: "Silakan pilih periode dan durasi"
            });
            return;
        }

        Swal.fire({
            title: "Kirim Permintaan?",
            html: `
                Periode: <strong>${periodText}</strong><br>
                Durasi: <strong>${durationText}</strong><br><br>
                Permintaan akan dikirim ke SPI.
            `,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#007bff",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, Kirim",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit();
            }
        });

    });
    </script>
@endpush


