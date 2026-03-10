<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditNote;
use App\Models\User;
use App\Models\TransactionAudit;
class AuditNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getByAudit($auditId)
    {
        AuditNote::withoutTimestamps(function () use ($auditId) {
            AuditNote::where('audit_id', $auditId)
                ->where('notetaker_id', '!=', auth()->id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        });
        try {
            $notes = AuditNote::with('notetaker')
                ->where('audit_id', $auditId)
                ->oldest()
                ->get()
                ->map(function ($note) {

                    $date = $note->note_at ?? $note->created_at;

                    return [
                        'id' => $note->id,
                        'note' => $note->note,
                        'notetaker' => optional($note->notetaker)->name ?? 'Unknown',
                        'note_at' => $date ? \Carbon\Carbon::parse($date)->format('d F Y, H.i') . ' WITA' : '-',
                        'created_at' => $note->created_at,
                        'updated_at' => $note->updated_at,
                        'read_at' => $note->read_at,
                        'is_owner' => $note->notetaker_id === auth()->id(),
                        ];
                });

            return response()->json($notes);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $request->validate([
        'audit_id' => 'required|exists:transaction_audits,id',
        'note' => 'required|string'
        ]);

        $note = AuditNote::create([
            'audit_id' => $request->audit_id,
            'notetaker_id' => auth()->id(),
            'note' => $request->note,
            'note_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'audit_id' => 'required|exists:transaction_audits,id',
        //     'note' => 'required|string'
        // ]);

        // $note = AuditNote::create([
        //     'audit_id' => $request->audit_id,
        //     'notetaker_id' => auth()->id(),
        //     'note' => $request->note,
        //     'note_at' => now(),
        // ]);

        // $note->load('notetaker');

        // return response()->json([
        //     'id' => $note->id,
        //     'note' => $note->note,
        //     'notetaker' => $note->notetaker->name,
        //     'note_at' => $note->note_at->format('d F Y, H.i') . ' WITA',
        //     'created_at' => $note->created_at,
        //     'updated_at' => $note->updated_at,
        //     'read_at' => $note->read_at,
        //     'is_owner' => true // ✅ VERY IMPORTANT
        // ]);

        $request->validate([
            'audit_id' => 'nullable',
            'transaction_id' => 'required',
            'note' => 'required|string'
        ]);

        // Find existing audit
        $audit = TransactionAudit::where('transaction_id', $request->transaction_id)->first();

        // If audit doesn't exist, create it automatically
        if (!$audit) {

            $audit = TransactionAudit::create([
                'transaction_id' => $request->transaction_id,
                'auditor_id' => auth()->id(),
                'status' => 'under_review',
                'audited_at' => null
            ]);
        }

        // Create note
        $note = AuditNote::create([
            'audit_id' => $audit->id,
            'notetaker_id' => auth()->id(),
            'note' => $request->note,
            'note_at' => now()
        ]);

        $note->load('notetaker');
        // dd($audit->status);
        return response()->json([
            'id' => $note->id,
            'note' => $note->note,
            'notetaker' => $note->notetaker->name,
            'note_at' => $note->note_at->format('d F Y, H.i') . ' WITA',
            'created_at' => $note->created_at,
            'updated_at' => $note->updated_at,
            'read_at' => $note->read_at,
            'is_owner' => true,
            'audit_id' => $audit->id,
            'audit_status' => $audit->status
        ]);
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
    public function update(Request $request, AuditNote $note)
    {
        if ($note->notetaker_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'note' => 'required|string'
        ]);

        $note->update([
            'note' => $request->note
        ]);

        $note->load('notetaker');

        return response()->json([
            'id' => $note->id,
            'note' => $note->note,
            'notetaker' => $note->notetaker->name,
            'note_at' => $note->note_at->format('d F Y, H.i') . ' WITA',
            'created_at' => $note->created_at,
            'updated_at' => $note->updated_at,
            'read_at' => $note->read_at,
            'is_owner' => true
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AuditNote $note)
    {
        if ($note->notetaker_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $note->delete();

        return response()->json(['success' => true]);
    }
}
