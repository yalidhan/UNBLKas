<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PeriodClosing;

class PeriodClosingController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function periodClosingPage()
    {
        $ClosedPeriods = PeriodClosing::where('is_closed', true)->get();   
        $ClosedPeriodsGrouped = $ClosedPeriods
        ->sortByDesc('year')
        ->groupBy('year');
        return view('pengaturan/period_closing', compact('ClosedPeriods', 'ClosedPeriodsGrouped'));
    }
    public function close(Request $request)
    {
        // Permission check
        if (auth()->user()->jabatan != 'Bendahara Operasional'
            || !in_array(auth()->user()->departement_id, [6, 1])) {

            return back()->withErrors([
                'message' => 'Anda tidak memiliki izin untuk menutup periode'
            ]);
        }
        $periodDate = \Carbon\Carbon::create($request->year, $request->month);

        if ($periodDate->isFuture()) {
            return back()->withErrors([
                'message' => 'Tidak dapat menutup periode di masa depan'
            ]);
        }
        PeriodClosing::updateOrCreate(
            [
                'year'  => $request->year,
                'month' => $request->month
            ],
            [
                'is_closed' => true,
                'closed_by' => auth()->id(),
                'closed_at' => now(),
                'reopened_at' => null,
                'reopen_expires_at' => null
            ]
        );

        return back()->with('message', 'Periode berhasil ditutup');
    }
}
