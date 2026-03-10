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
                        <center>
                        <h3 class="card-title">Persetujuan Pembukaan Periode</h3>
                        </center>
                        @if(session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                        @endif

                        @if($requests->isEmpty())
                        <div class="text-muted">
                            Tidak ada permintaan pending
                        </div>
                        @else

                        <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Periode</th>
                            <th>Pemohon</th>
                            <th>Durasi</th>
                            <th>Alasan</th>
                            <th>Tanggal Request</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($requests as $r)
                        <tr>

                        <td>
                            {{ \DateTime::createFromFormat('!m', $r->month)->format('F') }}
                            {{ $r->year }}
                        </td>

                        <td>{{ $r->requester->name }}</td>

                        <td>{{ $r->requested_duration_hours }} Jam</td>

                        <td>{{ $r->reason }}</td>

                        <td>{{ $r->created_at->format('d M Y H:i') }}</td>

                        <td class="text-center">

                        <form action="{{ route('period.requests.approve',$r->id) }}"
                            method="POST"
                            style="display:inline"
                            class="approve-form">

                            @csrf

                            <button type="button"
                                    class="btn btn-success btn-sm approve-btn"
                                    data-period="{{ \DateTime::createFromFormat('!m', $r->month)->format('F') }} {{ $r->year }}"
                                    data-duration="{{ $r->requested_duration_hours }}">
                                Approve
                            </button>

                        </form>

                            <!-- REJECT -->
                            <button class="btn btn-danger btn-sm"
                                    onclick="rejectRequest({{ $r->id }})">
                                Reject
                            </button>

                        </td>

                        </tr>
                        @endforeach
                        </tbody>
                        </table>

                        @endif

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
    function rejectRequest(id)
    {
        Swal.fire({
            title: 'Tolak Permintaan?',
            input: 'textarea',
            inputLabel: 'Alasan penolakan',
            inputPlaceholder: 'Masukkan alasan...',
            showCancelButton: true,
            confirmButtonText: 'Tolak',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) {
                    return 'Alasan wajib diisi!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/period/requests/${id}/reject`;

                form.innerHTML = `
                    @csrf
                    <input type="hidden"
                        name="rejection_reason"
                        value="${result.value}">
                `;

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
<script>
document.querySelectorAll('.approve-btn').forEach(button => {

    button.addEventListener('click', function() {

        const form = this.closest('form');
        const period = this.dataset.period;
        const duration = this.dataset.duration;

        Swal.fire({
            title: "Approve Permintaan?",
            html: `
                Periode:<br>
                <strong>${period}</strong><br><br>

                Durasi Pembukaan:<br>
                <strong>${duration} Jam</strong><br><br>

                Periode akan dibuka sementara sesuai durasi.
            `,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, Approve",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

    });

});
</script>
@endpush


