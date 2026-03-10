<?php

namespace App\Http\Controllers;

use App\Models\PeriodClosing;
use App\Models\PeriodOpenRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PeriodOpenRequestController extends Controller
{
    //
    public function periodOpenPage()
    {
        $ClosedPeriods = PeriodClosing::where('is_closed', true)
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        // Ambil pending requests terbaru
        $PendingRequests = PeriodOpenRequest::with('requester')
            ->where('status', 'pending')
            ->get()
            ->keyBy(fn($r) => $r->year . '-' . $r->month);

        return view('pengaturan.period_opening', compact(
            'ClosedPeriods',
            'PendingRequests'
        ));
    }
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function requestOpen(Request $request)
    {
        // ===============================
        // VALIDATION
        // ===============================
        $request->validate([
            'period_id' => 'required|exists:period_closings,id',
            'duration'  => 'required|integer|min:1|max:168', // up to 7 days
            'reason'    => 'required|string|max:500',
            'confirm_request' => 'accepted',
        ]);
        if (auth()->user()->jabatan != 'Bendahara Operasional'
            || !in_array(auth()->user()->departement_id, [6, 1])) {

            return back()->withErrors([
                'message' => 'Maaf, Anda tidak memiliki akses untuk mengirim permintaan buka periode.'
            ]);
        }
        $period = PeriodClosing::findOrFail($request->period_id);

        // ===============================
        // BUSINESS RULE CHECKS
        // ===============================

        // Must be closed
        if (!$period->is_closed) {
            return back()->withErrors([
                'message' => 'Periode belum ditutup'
            ]);
        }

        // Already temporarily open
        if ($period->reopen_expires_at &&
            now()->lt($period->reopen_expires_at)) {

            return back()->withErrors([
                'message' => 'Periode sedang dibuka sementara'
            ]);
        }

        // Existing pending request
        $pending = PeriodOpenRequest::where('year', $period->year)
            ->where('month', $period->month)
            ->where('status', 'pending')
            ->exists();

        if ($pending) {
            return back()->withErrors([
                'message' => 'Sudah ada permintaan pending untuk periode ini'
            ]);
        }

        // ===============================
        // CREATE REQUEST
        // ===============================

        PeriodOpenRequest::create([
            'year' => $period->year,
            'month' => $period->month,
            'requested_by' => auth()->id(),
            'reason' => $request->reason,
            'requested_duration_hours' => $request->duration,
            'status' => 'pending'
        ]);

        return back()->with('message',
            'Permintaan pembukaan periode berhasil dikirim ke SPI');
    }

    public function index()
        {
            $requests = PeriodOpenRequest::with('requester')
                ->where('status', 'pending')
                ->orderByDesc('created_at')
                ->get();

            return view('pengaturan.period_opening_requests', compact('requests'));
        }

    public function approve($id)
    {
        $req = PeriodOpenRequest::findOrFail($id);
        if ($req->reopen_expires_at &&
            now()->lt($req->reopen_expires_at)) {
            return back()->withErrors([
                'message' => 'Periode sedang dibuka sementara'
            ]);
        }
        if ($req->status !== 'pending') {
            return back()->withErrors([
                'message' => 'Permintaan sudah diproses'
            ]);
        }

        // cari periode
        $period = PeriodClosing::where('year', $req->year)
            ->where('month', $req->month)
            ->firstOrFail();

        // hitung waktu buka
        $expires = now()->addHours($req->requested_duration_hours);

        // update period
        $period->reopened_at = now();
        $period->reopen_expires_at = $expires;
        $period->save();

        // update request
        $req->status = 'approved';
        $req->approved_by = Auth::id();
        $req->approved_at = now();
        $req->open_until = $expires;
        $req->save();

        return back()->with('message',
            'Periode berhasil dibuka sementara');
    }
    
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $req = PeriodOpenRequest::findOrFail($id);

        if ($req->status !== 'pending') {
            return back()->withErrors([
                'message' => 'Permintaan sudah diproses'
            ]);
        }

        $req->status = 'rejected';
        $req->approved_by = Auth::id();
        $req->approved_at = now();
        $req->rejection_reason = $request->rejection_reason;
        $req->save();

        return back()->with('message',
            'Permintaan berhasil ditolak');
    }

    public function myRequests()
    {
        $requests = PeriodOpenRequest::with('approver')
            ->orderByDesc('created_at')
            ->get();

        return view('pengaturan.period_opening_history', compact('requests'));
    }

    public function markRead($id)
    {
        $req = PeriodOpenRequest::where('requested_by', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $req->is_read = true;
        $req->save();

        return response()->json(['success' => true]);
    }
}
