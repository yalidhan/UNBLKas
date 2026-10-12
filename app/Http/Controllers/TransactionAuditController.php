<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionAudit;
use Illuminate\Support\Facades\DB;
use Redirect;
use App\Models\AuditNote;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Departement;
use App\Models\Transaction;

class TransactionAuditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if (!in_array(auth()->user()->jabatan, ["SPI","Super Admin"])){
            return redirect('/');
        }
        if ($request->filled('periode')){
            $date=explode('-',$request->periode);
            $month=$date[1];
            $year=$date[0];
        }
        if ($request->input('periode')==''){
            $current=Carbon::now();
            $month=$current->month;
            $year=$current->year;
        }
        // if ($request->filled('departement')){
        //     $selectedDepartement = $request->input('departement');
        //     $transaction = Transaction::whereMonth('tanggal', $month)
        //                     ->whereYear('tanggal', $year)
        //                     ->where('departement_id', $selectedDepartement)
        //                     ->withSum('transaction_details', 'nominal')
        //                     ->with('audit')
        //                     ->orderBy('tanggal','asc')->orderBy('id','asc')->get();
        //     $departementName = Departement::find($selectedDepartement);
        // }else{
        //     $transaction = Transaction::whereMonth('tanggal', $month)
        //                     ->whereYear('tanggal', $year)
        //                     ->withSum('transaction_details', 'nominal')
        //                     ->with('audit')
        //                     ->orderBy('tanggal','asc')->orderBy('id','asc')->get();
        //                     $departementName = null;
        // }
        $query = Transaction::whereMonth('tanggal', $month)
        ->whereYear('tanggal', $year)
        ->whereNotIn('departement_id',[1,18,19,20,21])
        ->withSum('transaction_details', 'nominal')
        ->with('audit');

        if ($request->filled('departement')) {
            $query->where('departement_id', $request->departement);
            $departementName = Departement::find($request->departement);
        } else {
            $departementName = null;
        }

        $transaction = $query->orderBy('tanggal','asc')
                            ->orderBy('id','asc')
                            ->get();
        // dd($transaction[0]->audit);
        $departement = Departement::where('status','1')->whereNotIn('id',[1,18,19,20,21])->get();
        return view('audit/audit', compact('transaction',
                                            'month',
                                            'year',
                                            'departement',
                                            'departementName',
                                            ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    //     // dd($request->all());
    //     $request->validate([
    //     'transaction_id' => 'required|exists:transactions,id',
    //     'status' => 'required|in:verified,rejected',
    //     ]);

    //     TransactionAudit::updateOrCreate(
    //         ['transaction_id' => $request->transaction_id],
    //         [
    //             'status'     => $request->status,
    //             'auditor_id' => auth()->id(),
    //             'audited_at' => now(),
    //         ]
    //     );

    //     return redirect()
    //         ->back()
    //         ->with('success', 'Audit berhasil diproses.');
    // }
    
public function store(Request $request)
{
    $validated = $request->validate([
        'transaction_id' => 'required|exists:transactions,id',
        'status' => 'required|in:verified,rejected',
    ]);

    $audit = TransactionAudit::updateOrCreate(
        ['transaction_id' => $validated['transaction_id']],
        [
            'status'     => $validated['status'],
            'auditor_id' => auth()->id(),
            'audited_at' => now(),
        ]
    );

    // Return JSON for AJAX requests so the page does not reload.
    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Audit berhasil diproses.',
            'audit_status' => $audit->status,
            'auditor_name' => auth()->user()->name,
            'audited_at' => $audit->audited_at
                ? Carbon::parse($audit->audited_at)->format('d/m/Y H:i')
                : null,
        ]);
    }

    // Preserve the existing behavior for normal form submissions.
    return redirect()
        ->back()
        ->with('success', 'Audit berhasil diproses.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
