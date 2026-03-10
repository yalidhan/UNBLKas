@extends('master')
@push('histrory-open-period')

#requestTable thead .filter-row th {
    padding: 4px;
}

#requestTable thead .column-search {
    width: 100%;
    height: 28px;
    font-size: 12px;
}
@endpush
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
                        <h3 class="card-title">Riwayat Permintaan Buka Periode</h3>
                        </center>
                        @if($requests->isEmpty())
                        <div class="text-muted">
                            Belum ada permintaan
                        </div>
                        @else

                        <table id="requestTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Request By</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th>Durasi</th>
                                    <th>Dibuka Sampai</th>
                                    <th>Alasan</th>
                                    <th>Keterangan SPI</th>
                                    <th>Tanggal Request</th>
                                </tr>

                                <!-- FILTER ROW -->
                                <tr class="filter-row">
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Requester"></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Periode"></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Status"></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Durasi"></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Dibuka"></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Alasan"></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search SPI"></th>
                                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Tanggal"></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($requests as $r)
                                <tr>
                                    <td>{{ $r->requester->name }}</td>

                                    <td>
                                        {{ \DateTime::createFromFormat('!m', $r->month)->format('F') }}
                                        {{ $r->year }}
                                    </td>

                                    <td>
                                        @if($r->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($r->status == 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>

                                    <td>{{ $r->requested_duration_hours }} Jam</td>

                                    <td>
                                        @if($r->open_until)
                                            {{ \Carbon\Carbon::parse($r->open_until)->format('d M Y H:i') }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td>{{ $r->reason }}</td>

                                    <td>
                                        @if($r->status == 'rejected')
                                            {{ $r->rejection_reason }}
                                        @elseif($r->status == 'approved')
                                            Disetujui oleh {{ $r->approver->name ?? '-' }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td>{{ $r->created_at->format('d M Y H:i') }}</td>
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
    document.addEventListener("DOMContentLoaded", function () {

        const table = document.getElementById("requestTable");
        const inputs = table.querySelectorAll(".column-search");
        const rows = table.querySelectorAll("tbody tr");

        inputs.forEach((input, colIndex) => {
            input.addEventListener("keyup", function () {

                rows.forEach(row => {
                    const cell = row.children[colIndex];
                    const cellText = cell.textContent.toLowerCase();
                    const searchValue = input.value.toLowerCase();

                    if (cellText.includes(searchValue)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        });

    });
</script>
@endpush


