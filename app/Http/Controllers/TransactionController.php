<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\Transaction_detail;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Redirect;
use Carbon\Carbon;
use App\Models\Departement;
use App\Models\User;
use App\Models\Budget;
use App\Models\Budget_detail;
use App\Models\PeriodClosing;
Use App\Models\AuditNote;
Use App\Models\TransactionAudit;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function previewBukti(Transaction $transaction)
    {
        if (!$transaction->bukti_file_path ||
            !Storage::disk('public')->exists($transaction->bukti_file_path)) {
            abort(404);
        }

        return response()->file(
            storage_path('app/public/' . $transaction->bukti_file_path),
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$transaction->bukti_file_name.'"',
                'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'              => 'no-cache',
                'Expires'             => '0',
            ]
        );
    }
    public function index(Request $request)
    {        
        // =============================
        // FILTER PERIODE
        // =============================
        $departement_id=auth()->user()->departement_id;
        if ($request->filled('periode')) {

            [$year, $month] = explode('-', $request->periode);

        } elseif ($request->filled('tahun')) {

            $year = $request->tahun;
            $month = null;

        } else {

            $current = Carbon::now();
            $year  = $current->year;
            $month = $current->month;

        }
        // Add on
            $where = "t.departement_id = $departement_id";

            if ($month) {
                $where .= " AND MONTH(t.tanggal) = $month";
            }

            if ($year) {
                $where .= " AND YEAR(t.tanggal) = $year";
            }

            if ($request->status_transaksi) {
                $status = $request->status_transaksi;
                $where .= " AND t.status_transaksi = '$status'";
            }

            if ($request->status_bukti) {

                if ($request->status_bukti == 'uploaded') {
                    $where .= " AND t.bukti_file_path IS NOT NULL";
                }

                if ($request->status_bukti == 'not_uploaded') {
                    $where .= " AND t.bukti_file_path IS NULL";
                }
            }
        // Add on end
        $advancedFilter =
            $request->filled('tahun') ||
            $request->filled('status_transaksi') ||
            $request->filled('status_bukti') ||
            $request->filled('status_audit');
        \DB::statement("SET SQL_MODE=''");
        // $transaction=DB::select(
        //     "SELECT t.id, t.no_spb,t.tanggal, t.keterangan, t.no_trf, t.user_id,t.status_transaksi, 
        //         t.bukti_file_path, d.dk,sum(d.nominal) AS total,
        //         u.name
        //     FROM transactions t
        //     LEFT JOIN transaction_details d
        //     ON t.id = d.transaction_id
        //     LEFT JOIN users u
        //     ON t.user_id = u.id
        //     WHERE t.departement_id=$departement_id and month (tanggal)=$month
        //         and year (tanggal)=$year
        //     GROUP BY 
        //         d.transaction_id,t.id
        //     ORDER BY 
        //         tanggal ASC,
        //         id ASC"
        // );
        $transaction = DB::select("
            SELECT 
                t.id,
                t.no_spb,
                t.tanggal,
                t.keterangan,
                t.no_trf,
                t.user_id,
                t.status_transaksi,
                t.bukti_file_path,
                d.dk,
                SUM(d.nominal) AS total,
                u.name
            FROM transactions t
            LEFT JOIN transaction_details d
                ON t.id = d.transaction_id
            LEFT JOIN users u
                ON t.user_id = u.id
            WHERE $where
            GROUP BY d.transaction_id, t.id
            ORDER BY tanggal ASC, id ASC
        ");

        $transactionIds = collect($transaction)->pluck('id')->toArray();

        $audits = TransactionAudit::with('auditor')
            ->whereIn('transaction_id', $transactionIds)
            ->get()
            ->keyBy('transaction_id');

        if ($request->status_audit) {

            $filtered = [];

            foreach ($transaction as $t) {

                $audit = $audits[$t->id] ?? null;
                $status = $audit->status ?? 'pending_review';

                if ($status == $request->status_audit) {
                    $filtered[] = $t;
                }
            }

            $transaction = $filtered;
        }
        $transactionIds = collect($transaction)->pluck('id')->toArray();

        $unreadCounts = DB::table('audit_notes')
        ->select('audit_id', DB::raw('count(*) as total'))
        ->whereNull('read_at')
        ->where('notetaker_id', '!=', auth()->id())
        ->groupBy('audit_id')
        ->pluck('total', 'audit_id');
        
        foreach ($transaction as $t) {

            $audit = $audits[$t->id] ?? null;

            if ($audit) {
                $audit->unread_notes = $unreadCounts[$audit->id] ?? 0;
            }

            $t->audit = $audit;
        }
        

        // $saldoDebit=DB::select(
        //     "SELECT t.id, t.no_spb,t.tanggal,   
        //         d.dk,sum(d.nominal) AS total_debit
        //     FROM transactions t
        //     LEFT JOIN transaction_details d
        //     ON t.id = d.transaction_id
        //     WHERE departement_id=$departement_id and tanggal <'$year-$month-01' and dk=1 GROUP BY departement_id"
        // );
        // if ($saldoDebit){
        //     $saldoDebit=$saldoDebit[0]->total_debit;
        // }
        // else{
        //     $saldoDebit=0;
        // }
        // $saldoKredit=DB::select(
        //     "SELECT t.id, t.no_spb,t.tanggal,   
        //         d.dk,sum(d.nominal) AS total_kredit
        //     FROM transactions t
        //     LEFT JOIN transaction_details d
        //     ON t.id = d.transaction_id
        //     WHERE departement_id=$departement_id and tanggal <'$year-$month-01' and dk=2 GROUP BY departement_id"
        // );
        // if ($saldoKredit){
        //     $saldoKredit=$saldoKredit[0]->total_kredit;
        // }
        // else{
        //     $saldoKredit=0;
        // }
        // $saldoLastMonth=$saldoDebit-$saldoKredit;
        if (!$advancedFilter) {

            $saldoDebit = DB::select("
                SELECT SUM(d.nominal) AS total_debit
                FROM transactions t
                LEFT JOIN transaction_details d
                ON t.id = d.transaction_id
                WHERE departement_id = $departement_id
                AND tanggal < '$year-$month-01'
                AND dk = 1
            ");

            $saldoDebit = $saldoDebit ? $saldoDebit[0]->total_debit : 0;

            $saldoKredit = DB::select("
                SELECT SUM(d.nominal) AS total_kredit
                FROM transactions t
                LEFT JOIN transaction_details d
                ON t.id = d.transaction_id
                WHERE departement_id = $departement_id
                AND tanggal < '$year-$month-01'
                AND dk = 2
            ");

            $saldoKredit = $saldoKredit ? $saldoKredit[0]->total_kredit : 0;

            $saldoLastMonth = $saldoDebit - $saldoKredit;

        } else {

            $saldoLastMonth = null;

        }

        $departement=Departement::where('status','=','1')->get();
        $accountHarta=Account::where('nama','LIKE','Kas%')->where('status','=','1')->orderBy('no')->get();
        // $accountPendapatan=Account::where(function($q){$q->where('tipe','=','Pendapatan')->orWhere('nama','LIKE','Kas Bank%')->orWhere('tipe','=','Modal');})->where('status','=','1')->orderBy('no')->get();
        $accountPendapatan=Account::where('status','=','1')->orderBy('no')->get();
        // dd($accountPendapatan);
        // =============================
        // CHECK PERIOD STATUS
        // =============================

        $period = PeriodClosing::where('year', $year)
            ->where('month', $month)
            ->first();

        $periodStatus = 'open'; // default

        if ($period && $period->is_closed) {

            // If temporarily reopened
            if ($period->reopen_expires_at &&
                now()->lt($period->reopen_expires_at)) {

                $periodStatus = 'temporary_open';

            } else {

                $periodStatus = 'closed';

            }
        }
        $reopenExpires = null;

        if ($period && $period->reopen_expires_at) {
            $reopenExpires = $period->reopen_expires_at;
        }
        //
        return view('transaksi/transaksi',
            [
                'transactionlist' => $transaction,
                'accountlistPendapatan' => $accountPendapatan,
                'periodStatus' => $periodStatus
            ])
            ->with('year', $year)
            ->with('month', $month)
            ->with('accountlistHarta', $accountHarta)
            ->with('listDepartement', $departement)
            ->with('saldoLastMonth', $saldoLastMonth)
            ->with('reopenExpires', $reopenExpires)
            ->with('advancedFilter', $advancedFilter);
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
    public function store(Request $request)
    {
        //
        if ($request->tp_trx == 'pemasukan') {
            if (isPeriodLocked($request->tgl_pemasukan)) {
            return back()->withErrors([
                'message' => 'Periode transaksi '.date('F Y', strtotime($request->tgl_pemasukan)).' sudah ditutup'
            ]);
            }

            $request->validate([
                'tgl_pemasukan'      => 'required',
                'kode_pemasukan'     => 'required',
                'akun_pendapatan'    => 'required',
                'nominal_pemasukan'  => 'required',
                'bukti_transaksi'    => 'nullable|file|mimes:pdf|max:5120',
                'status_transaksi'   => 'required|in:selesai,on_progress',
            ]);

            $fileName = null;
            $filePath = null;

            // ===== Upload File =====
            if ($request->hasFile('bukti_transaksi')) {

                $file = $request->file('bukti_transaksi');

                $fileName = time().'_'.$file->getClientOriginalName();

                $filePath = $file->storeAs(
                    'bukti-transaksi',
                    $fileName,
                    'public'
                );
            }

            // Bersihkan nominal (hapus titik pemisah ribuan)
            $nominal = str_replace('.', '', $request->nominal_pemasukan);

            DB::beginTransaction();

            try {

                // ===== Simpan Header =====
                $queryTransaction = Transaction::create([
                    'tanggal'          => $request->tgl_pemasukan,
                    'no_spb'           => $request->kode_pemasukan,
                    'keterangan'       => $request->keterangan_pemasukan,
                    'departement_id'   => $request->id_departement,
                    'user_id'          => $request->user_id,
                    'bukti_file_name'  => $fileName,
                    'bukti_file_path'  => $filePath,
                    'status_transaksi' => $request->status_transaksi,
                ]);

                // ===== Simpan Detail =====
                Transaction_detail::create([
                    'transaction_id' => $queryTransaction->id,
                    'account_id'     => $request->akun_pendapatan,
                    'nominal'        => $nominal,
                    'dk'             => $request->dk,
                ]);

                DB::commit();

                return redirect('transaksi/'.$queryTransaction->id)
                    ->with('message', 'Pemasukan berhasil disimpan');

            } catch (\Exception $e) {

                DB::rollBack();

                // ===== Hapus file jika gagal =====
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }

                return redirect()->back()
                    ->withErrors(['message' => 'Gagal menyimpan transaksi'])
                    ->withInput();
            }
        }
        if ($request->tp_trx=='pengeluaran'){
                if (isPeriodLocked($request->tgl_pengeluaran)) {
                return back()->withErrors([
                    'message' => 'Periode transaksi '.date('F Y', strtotime($request->tgl_pengeluaran)).' sudah ditutup'
                ]);
                }
                $request->validate([
                    'tgl_pengeluaran'        => 'required',
                    'no_spb_pengeluaran'     => 'required',
                    'keterangan_pengeluaran' => 'required',
                    'bukti_transaksi'        => 'file|mimes:pdf|max:5120',
                    'status_transaksi'       => 'required|in:selesai,on_progress',
                ]);

                $find = Transaction::where('no_spb', '=', $request->no_spb_pengeluaran)->get();

                if (!$find->isEmpty()) {

                    return redirect()->back()
                        ->withErrors(['message' => 'NO. SPB sudah terdaftar, silahkan cek ulang']);

                } else {

                    $fileName = null;
                    $filePath = null;

                    // ===== Upload File =====
                    if ($request->hasFile('bukti_transaksi')) {

                        $file = $request->file('bukti_transaksi');

                        $fileName = time() . '_' . $file->getClientOriginalName();

                        $filePath = $file->storeAs(
                            'bukti-transaksi',
                            $fileName,
                            'public'
                        );
                    }

                    try {

                        // ===== Simpan ke DB =====
                        $pengeluaranQuery = Transaction::create([
                            'tanggal'          => $request->tgl_pengeluaran,
                            'no_spb'           => $request->no_spb_pengeluaran,
                            'keterangan'       => $request->keterangan_pengeluaran,
                            'departement_id'   => $request->id_departement,
                            'user_id'          => $request->user_id,
                            'bukti_file_name'  => $fileName,
                            'bukti_file_path'  => $filePath,
                            'status_transaksi' => $request->status_transaksi,
                        ]);

                        return redirect('transaksi/' . $pengeluaranQuery->id);

                    } catch (\Exception $e) {

                        // ===== Hapus file jika gagal =====
                        if ($filePath && Storage::disk('public')->exists($filePath)) {
                            Storage::disk('public')->delete($filePath);
                        }

                        return redirect()->back()
                            ->withErrors(['message' => 'Gagal menyimpan transaksi'])
                            ->withInput();
                    }
                }
            }
        if ($request->tp_trx == 'transfer') {
            if (isPeriodLocked($request->tgl_transfer)) {
            return back()->withErrors([
                'message' => 'Periode transaksi bulan '.date('F Y', strtotime($request->tgl_transfer)).' sudah ditutup'
            ]);
            }
            $request->validate([
                'departement_tujuan'  => 'required',
                'tgl_transfer'        => 'required',
                'no_spb_transfer'     => 'required',
                'akun_kas_awal'       => 'required',
                'akun_kas_tujuan'     => 'required',
                'nominal_transfer'    => 'required',
                'bukti_transaksi'     => 'nullable|file|mimes:pdf|max:5120',
                'status_transaksi'    => 'required|in:selesai,on_progress',
            ]);

            // ===== Upload File =====
            $fileName = null;
            $filePath = null;

            if ($request->hasFile('bukti_transaksi')) {

                $file = $request->file('bukti_transaksi');

                $fileName = time().'_'.$file->getClientOriginalName();

                $filePath = $file->storeAs(
                    'bukti-transaksi',
                    $fileName,
                    'public'
                );
            }

            // Bersihkan nominal
            $nominal = str_replace('.', '', $request->nominal_transfer);

            // ===== Generate No Transfer =====
            $last_data = DB::table('transactions')
                ->where('no_trf', 'like', 'TF%')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($last_data) {
                $no_urut = (int) substr($last_data->no_trf, 3) + 1;
                $no_trf  = "TF.$no_urut";
            } else {
                $no_trf = "TF.1";
            }

            DB::beginTransaction();

            try {

                // ===============================
                // 1️⃣ TRANSAKSI PENGIRIM
                // ===============================
                $transferOut = Transaction::create([
                    'tanggal'          => $request->tgl_transfer,
                    'no_spb'           => $request->no_spb_transfer,
                    'keterangan'       => $request->keterangan_transfer,
                    'departement_id'   => $request->id_departement_pengirim,
                    'user_id'          => $request->user_id,
                    'no_trf'           => $no_trf,
                    'bukti_file_name'  => $fileName,
                    'bukti_file_path'  => $filePath,
                    'status_transaksi' => $request->status_transaksi,
                ]);

                Transaction_detail::create([
                    'transaction_id' => $transferOut->id,
                    'account_id'     => $request->akun_kas_awal,
                    'nominal'        => $nominal,
                    'dk'             => $request->dk_1,
                ]);

                // ===============================
                // 2️⃣ TRANSAKSI PENERIMA
                // ===============================
                $transferIn = Transaction::create([
                    'tanggal'          => $request->tgl_transfer,
                    'no_spb'           => $request->no_spb_transfer,
                    'keterangan'       => $request->keterangan_transfer,
                    'departement_id'   => $request->departement_tujuan,
                    'user_id'          => $request->user_id,
                    'no_trf'           => $no_trf,
                    'bukti_file_name'  => $fileName,
                    'bukti_file_path'  => $filePath,
                    'status_transaksi' => $request->status_transaksi,
                ]);

                Transaction_detail::create([
                    'transaction_id' => $transferIn->id,
                    'account_id'     => $request->akun_kas_tujuan,
                    'nominal'        => $nominal,
                    'dk'             => $request->dk_2,
                ]);

                DB::commit();

                return redirect()->back()
                    ->with('message', 'Transfer berhasil disimpan');

            } catch (\Exception $e) {

                DB::rollBack();

                // ===== Hapus file jika gagal =====
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }

                return redirect()->back()
                    ->withErrors(['message' => 'Gagal menyimpan transfer'])
                    ->withInput();
            }
        }
        return redirect::back()->with ('message','Berhasil menambahkan data Transaksi');
        
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        \DB::statement("SET SQL_MODE=''");
        $showTransaction=DB::select(
            "SELECT t.id, t.no_spb,t.tanggal, t.keterangan,t.user_id,t.kepada,t.ctt_pajak,t.ctt_bendahara,
                t.bukti_file_name, t.bukti_file_path,t.status_transaksi,
                dp.id AS departement_id, dp.nama AS departement,sum(d.nominal) AS total
            FROM transactions t
            LEFT JOIN transaction_details d
            ON t.id = d.transaction_id
            LEFT JOIN departements dp
            ON t.departement_id = dp.id
            WHERE t.id=$id"
        );
        $departement_id=$showTransaction[0]->departement_id;
        $tahun=Carbon::parse($showTransaction[0]->tanggal)->format('Y');
        $showDetailTransaction=DB::select(
            "SELECT dt.nominal, a.nama, a.no, a.id as account_id, a.tipe, a.kelompok, dt.id, dt.dk
            FROM transaction_details dt
            LEFT JOIN accounts a
            ON dt.account_id = a.id
            WHERE transaction_id = $id;"
        );
        if ($showDetailTransaction){
            $dk=$showDetailTransaction[0]->dk;
        }
        else{
            $dk=2;
        }
        
        if ($dk==1){
            // $accountList=Account::where('tipe','=','Pendapatan')->where('status','=','1')->orderBy('no', 'ASC')->get();
            $accountList=Account::where('status','=','1')->orderBy('no', 'ASC')->get();

        }
        
        else{
            // $accountList=Account::where('tipe','!=','Pendapatan')->where('status','=','1')->orderBy('no', 'ASC')->get();
            $accountList=DB::select(
                "SELECT b.id,b.departement_id,b.tahun,
                        bd.budget_id,bd.id,bd.account_id,
                        a.nama,a.tipe,a.kelompok
                FROM budgets b
                LEFT JOIN budget_details bd
                    ON bd.budget_id = b.id
                LEFT JOIN accounts a
                    ON a.id = bd.account_id
                WHERE 
                    departement_id=$departement_id AND tahun=$tahun
                ORDER BY
                    account_id ASC;"
            );
            // dd($accountList);
        }
        return view('transaksi/rincian_transaksi',compact('departement_id','showTransaction','showDetailTransaction','accountList'));
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
    public function update(Request $request, $id)
    {
        // dd($request->kode);
        if ($request->kode=="spb"){
            $transaction = Transaction::find($id);
            $transaction->kepada = $request->kepada;
            $transaction->ctt_pajak = $request->ctt_pajak;
            $transaction->ctt_bendahara = $request->ctt_bendahara;
            $transaction->update();
            // $showTransaction=Transaction::where('id','=',$id)->get();
            \DB::statement("SET SQL_MODE=''");
            $showTransaction=DB::select(
                "SELECT t.no_trf,t.id,t.departement_id, t.no_spb,t.tanggal, 
                    t.keterangan, t.no_trf, t.user_id,t.kepada,t.ctt_pajak,t.ctt_bendahara,sum(d.nominal) AS total,d.nominal,
                    a.nama as akun,
                    dp.nama, 
                    d.nominal,
                    u.name,u.nik
                FROM transactions t
                LEFT JOIN transaction_details d
                ON t.id = d.transaction_id
                LEFT JOIN accounts a
                ON d.account_id = a.id
                LEFT JOIN users u
                ON t.user_id = u.id
                LEFT JOIN departements dp
                ON t.departement_id = dp.id
                WHERE t.id=$id
                GROUP BY d.transaction_id, t.id"
            );
    
            // dd($showTransaction);
            return view('laporan/cetak-SPB',compact('showTransaction'));
        }
        elseif($request->kode=="edit"){
            if (isPeriodLocked($request->tgl_edit)) {
            return back()->withErrors([
                'message' => 'Periode transaksi '.date('F Y', strtotime($request->tgl_edit)).' sudah ditutup'
            ]);
            }
            $request->validate([
                'tgl_edit'        => 'required',
                'no_spb_edit'     => 'required',
                'keterangan_edit' => 'required',
                'bukti_transaksi' => 'nullable|file|mimes:pdf|max:5120',
                'status_transaksi'=> 'required|in:selesai,on_progress',
            ]);

            $transaction = Transaction::findOrFail($id);

            $oldPath = $transaction->bukti_file_path;

            $newFileName = $transaction->bukti_file_name;
            $newFilePath = $transaction->bukti_file_path;

            // ===== HAPUS BUKTI TANPA UPLOAD =====
            if ($request->hapus_bukti && !$request->hasFile('bukti_transaksi')) {

                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }

                $newFileName = null;
                $newFilePath = null;
            }

            // ===== UPLOAD FILE BARU =====
            if ($request->hasFile('bukti_transaksi')) {

                $file = $request->file('bukti_transaksi');

                $newFileName = time().'_'.$file->getClientOriginalName();

                $newFilePath = $file->storeAs(
                    'bukti-transaksi',
                    $newFileName,
                    'public'
                );
            }

            try {

                $transaction->tanggal          = $request->tgl_edit;
                $transaction->no_spb           = $request->no_spb_edit;
                $transaction->keterangan       = $request->keterangan_edit;
                $transaction->status_transaksi = $request->status_transaksi;
                $transaction->bukti_file_name  = $newFileName;
                $transaction->bukti_file_path  = $newFilePath;

                $transaction->update();

                // Hapus file lama jika diganti
                if ($request->hasFile('bukti_transaksi') &&
                    $oldPath &&
                    Storage::disk('public')->exists($oldPath)) {

                    Storage::disk('public')->delete($oldPath);
                }

                return back()->with('message', 'Berhasil Mengubah Data Transaksi');

            } catch (\Exception $e) {

                // Hapus file baru jika gagal
                if ($request->hasFile('bukti_transaksi') &&
                    Storage::disk('public')->exists($newFilePath)) {

                    Storage::disk('public')->delete($newFilePath);
                }

                return back()->withErrors(['message' => 'Gagal Mengubah Data']);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {

            // =====================================
            // 🔵 KASUS TRANSFER (TF.x)
            // =====================================
            if (str_contains($id, 'TF.')) {

                $transactions = Transaction::where('no_trf', $id)->get();

                if ($transactions->isEmpty()) {
                    return back()->withErrors(['message' => 'Data tidak ditemukan']);
                }

                // 🔒 PERIOD CHECK (DB DATE)
                foreach ($transactions as $trx) {
                    if (isPeriodLocked($trx->tanggal)) {
                        return back()->withErrors([
                            'message' => 'Periode transaksi '.date('F Y', strtotime($trx->tanggal)).' sudah ditutup'
                        ]);
                    }
                }
                // Ambil path file (shared)
                $filePath = $transactions->first()->bukti_file_path;

                // Hapus detail semua transaksi
                foreach ($transactions as $trx) {
                    Transaction_detail::where('transaction_id', $trx->id)->delete();
                }

                // Hapus transaksi
                Transaction::where('no_trf', $id)->delete();

                // Hapus file jika tidak dipakai transaksi lain
                if ($filePath) {

                    $count = Transaction::where('bukti_file_path', $filePath)->count();

                    if ($count == 0 && Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                }

            }
            // =====================================
            // 🟢 TRANSAKSI BIASA
            // =====================================
            else {

                $trx = Transaction::findOrFail($id);
                
                // 🔒 PERIOD CHECK (DB DATE)
                if (isPeriodLocked($trx->tanggal)) {
                    return back()->withErrors([
                        'message' => 'Periode transaksi '.date('F Y', strtotime($trx->tanggal)).' sudah ditutup'
                    ]);
                }

                $filePath = $trx->bukti_file_path;

                // Hapus detail
                Transaction_detail::where('transaction_id', $id)->delete();

                // Hapus transaksi
                $trx->delete();

                // Hapus file jika tidak dipakai transaksi lain
                if ($filePath) {

                    $count = Transaction::where('bukti_file_path', $filePath)->count();

                    if ($count == 0 && Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                }
            }

            DB::commit();

            return back()->with('message', 'Berhasil menghapus data Transaksi');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors([
                'message' => 'Gagal menghapus data Transaksi'
            ]);
        }
    }

    public function destroyRincian(string $id)
    {
        // 🔒 PERIOD CHECK (DB DATE)
        $trx = Transaction_detail::find($id)->transaction;
            if (isPeriodLocked($trx->tanggal)) {
                return back()->withErrors([
                    'message' => 'Periode transaksi '.date('F Y', strtotime($trx->tanggal)).' sudah ditutup'
                ]);
        }
        Transaction_detail::where('id',$id)->delete();
        return redirect::back()->with('message','Berhasil menghapus rincian Transaksi');
    }

    public function storeRincian(Request $request)
    {
        $tgl_transaksi=Transaction::find($request->transaction_id)->tanggal;
        if (isPeriodLocked($tgl_transaksi)) {
        return back()->withErrors([
            'message' => 'Periode transaksi '.date('F Y', strtotime($tgl_transaksi)).' sudah ditutup'
        ]);
        }
        $request->validate([
            'akun_rincian'=>'required',
            'nominal_tambah_rincian'=>'required',
            'transaction_id'=>'required',
        ]);
        $nominal_rincian_int=$request->nominal_tambah_rincian;
        $nominal_rincian_int=str_replace('.','',$nominal_rincian_int);
        
        $akun_tipe=Account::find($request->akun_rincian);
        $akun_tipe=$akun_tipe->tipe;
        if ($akun_tipe=="Pendapatan"){
            $dk=1;
        }
        else{
            $dk=2;
        }
        Transaction_detail::create([
            'transaction_id'=>$request->transaction_id,
            'account_id'=>$request->akun_rincian,
            'nominal'=>$nominal_rincian_int,
            'dk'=>$dk,
        ]);
        $date=$tgl_transaksi;
        $paguAnggaranQuery=Budget::where('departement_id','=',$request->departement_id)
            ->where('tahun','=',Carbon::parse($date)->format('Y'))
            ->whereHas('budget_details', function ($query) use ($request) {
                $query->where('account_id', $request->akun_rincian);
            })
            ->first();
        
        if (!$paguAnggaranQuery) {
            return redirect()->back()->with('warning', 'Data anggaran tidak ditemukan untuk tahun ini');
        }
        
        $paguAnggaran=$paguAnggaranQuery->budget_details->where('account_id','=',$request->akun_rincian)->first()->nominal;
        $realisasi=Transaction::where('departement_id','=',$request->departement_id)
            ->whereYear('tanggal','=',Carbon::parse($date)->format('Y'))
            ->whereHas('transaction_details', function ($query) use ($request) {
                $query->where('account_id', $request->akun_rincian);
            })
            ->get()
            ->sum(function($transaction) use ($request) {
                return $transaction->transaction_details->where('account_id', $request->akun_rincian)->sum('nominal');
            });
        $rpPaguAnggaran = "Rp " . number_format($paguAnggaran, 0, ',', '.');
        $rpRealisasi = "Rp " . number_format($realisasi, 0, ',', '.');
        $departementName = Departement::find($request->departement_id)->nama;
        $namaAkun=Account::find($request->akun_rincian)->nama;
        if ($realisasi > $paguAnggaran){        
            return redirect()->back()->with([
                'warning' => 'Pengeluaran Sudah Melebihi Pagu Anggaran!',
                'namaAkun' => $namaAkun,
                'namaDepartement' => $departementName,
                'rpPaguAnggaran' => $rpPaguAnggaran,
                'rpRealisasi' => $rpRealisasi,
                'tahun' => Carbon::parse($date)->format('Y'),
            ]);
        }

        return redirect::back()->with ('message','Berhasil menambah rincian transaksi');
    }

    public function updateRincian(Request $request,$id)
    {
        $id_transaksi=Transaction_detail::find($id)->transaction_id;
        $tgl_transaksi=Transaction::find($id_transaksi)->tanggal;
        if (isPeriodLocked($tgl_transaksi)) {
        return back()->withErrors([
            'message' => 'Periode transaksi '.date('F Y', strtotime($tgl_transaksi)).' sudah ditutup'
        ]);
        }
        $request->validate([
            'akun_rincian_edit' => 'required',
            'nominal_tambah_rincian_edit' => 'required'
        ]);

        $nominal_rincian_int = str_replace('.', '', $request->nominal_tambah_rincian_edit);

        $updateRincian = Transaction_detail::find($id);

        // UPDATE DATA
        $updateRincian->account_id = $request->akun_rincian_edit;
        $updateRincian->nominal = $nominal_rincian_int;
        $updateRincian->update();

        if ($updateRincian->dk == 2) {
        $date = $tgl_transaksi;

        $paguAnggaranQuery = Budget::where('departement_id','=',$request->departement_id)
            ->where('tahun','=',Carbon::parse($date)->format('Y'))
            ->whereHas('budget_details', function ($query) use ($request) {
                $query->where('account_id', $request->akun_rincian_edit);
            })
            ->first();

        if (!$paguAnggaranQuery) {
            return redirect()->back()->with('warning', 'Data anggaran tidak ditemukan untuk tahun ini');
        }

        $paguAnggaran = $paguAnggaranQuery->budget_details
            ->where('account_id','=',$request->akun_rincian_edit)
            ->first()
            ->nominal;

        // ⭐ THIS ALREADY RETURNS CORRECT TOTAL
        $realisasi = Transaction::where('departement_id','=',$request->departement_id)
            ->whereYear('tanggal','=',Carbon::parse($date)->format('Y'))
            ->whereHas('transaction_details', function ($query) use ($request) {
                $query->where('account_id', $request->akun_rincian_edit);
            })
            ->get()
            ->sum(function($transaction) use ($request) {
                return $transaction->transaction_details
                    ->where('account_id', $request->akun_rincian_edit)
                    ->sum('nominal');
            });

        $rpPaguAnggaran = "Rp " . number_format($paguAnggaran, 0, ',', '.');
        $rpRealisasi = "Rp " . number_format($realisasi, 0, ',', '.');
        $departementName = Departement::find($request->departement_id)->nama;
        $namaAkun=Account::find($request->akun_rincian_edit)->nama;

        if ($realisasi > $paguAnggaran){        
            return redirect()->back()->with([
                'warning' => 'Pengeluaran Sudah Melebihi Pagu Anggaran!',
                'namaAkun' => $namaAkun,
                'namaDepartement' => $departementName,
                'rpPaguAnggaran' => $rpPaguAnggaran,
                'rpRealisasi' => $rpRealisasi,
                'tahun' => Carbon::parse($date)->format('Y'),
                'message' => 'Berhasil Mengubah Data Rincian'
            ]);
        }
        }

        return redirect()->back()->with('message', 'Berhasil Mengubah Data Rincian');
    }


    public function showTransfer(string $id)
    {   
        $showTransaction=Transaction::where('no_trf','=',$id)->get();
        $idDetail1=$showTransaction[0]->id;
        $idDetail2=$showTransaction[1]->id;
        $showDetailTransaction1=Transaction_detail::where('transaction_id','=',$idDetail1)->get();
        $showDetailTransaction2=Transaction_detail::where('transaction_id','=',$idDetail2)->get();

        $departement=Departement::where('status','=','1')->get();
        $accountHarta=Account::where('nama','LIKE','Kas%')->where('status','=','1')->orderBy('no')->get();
        return view('/transaksi/edit_transfer')
            ->with('accountlistHarta',$accountHarta)
            ->with('listDepartement',$departement)
            ->with('showTransaction',$showTransaction)
            ->with('showDetailTransaction1',$showDetailTransaction1)
            ->with('showDetailTransaction2',$showDetailTransaction2);
    }

    public function updateTransfer(Request $request, $id)
    {
        // dd($request->kepada);
        if ($request->kode=="spb"){
            // dd($request->id_1);
            $transaction = Transaction::find($request->id_1);
            $transaction->kepada = $request->kepada;
            $transaction->ctt_pajak = $request->ctt_pajak;
            $transaction->ctt_bendahara = $request->ctt_bendahara;
            $transaction->update();
            $transaction = Transaction::find($request->id_2);
            $transaction->kepada = $request->kepada;
            $transaction->ctt_pajak = $request->ctt_pajak;
            $transaction->ctt_bendahara = $request->ctt_bendahara;
            $transaction->update();
            // $showTransaction=Transaction::where('id','=',$id)->get();
            \DB::statement("SET SQL_MODE=''");
            $showTransaction=DB::select(
                "SELECT t.no_trf,t.id,t.departement_id, t.no_spb,t.tanggal, 
                    t.keterangan, t.no_trf, t.user_id,t.kepada,t.ctt_pajak,t.ctt_bendahara,sum(d.nominal) AS total,d.nominal,
                    a.nama as akun,
                    dp.nama, 
                    d.nominal,
                    u.name,u.nik
                FROM transactions t
                LEFT JOIN transaction_details d
                ON t.id = d.transaction_id
                LEFT JOIN accounts a
                ON d.account_id = a.id
                LEFT JOIN users u
                ON t.user_id = u.id
                LEFT JOIN departements dp
                ON t.departement_id = dp.id
                WHERE t.id=$request->id_1
                GROUP BY d.transaction_id, t.id"
            );
    
            // dd($showTransaction);
            return view('laporan/cetak-SPB',compact('showTransaction'));
        }

        if (isPeriodLocked($request->tgl_transfer)) {
        return back()->withErrors([
            'message' => 'Periode transaksi '.date('F Y', strtotime($request->tgl_transfer)).' sudah ditutup'
        ]);
        }
        $request->validate([
            'departement_tujuan' => 'required',
            'tgl_transfer'       => 'required',
            'no_spb_transfer'    => 'required',
            'akun_kas_awal'      => 'required',
            'akun_kas_tujuan'    => 'required',
            'nominal_transfer'   => 'required',
            'bukti_transaksi'    => 'nullable|file|mimes:pdf|max:5120',
            'status_transaksi'   => 'required|in:selesai,on_progress',
        ]);

        $nominal = str_replace('.', '', $request->nominal_transfer);

        $id_1 = $request->id_1; // pengirim
        $id_2 = $request->id_2; // penerima

        $trx1 = Transaction::findOrFail($id_1);
        $trx2 = Transaction::findOrFail($id_2);

        // ===== FILE LAMA =====
        $oldPath = $trx1->bukti_file_path;

        $newFileName = $trx1->bukti_file_name;
        $newFilePath = $trx1->bukti_file_path;

        // ===============================
        // HAPUS BUKTI TANPA UPLOAD BARU
        // ===============================
        if ($request->hapus_bukti && !$request->hasFile('bukti_transaksi')) {

            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $newFileName = null;
            $newFilePath = null;
        }

        // ===============================
        // UPLOAD FILE BARU
        // ===============================
        if ($request->hasFile('bukti_transaksi')) {

            $file = $request->file('bukti_transaksi');

            $newFileName = time().'_'.$file->getClientOriginalName();

            $newFilePath = $file->storeAs(
                'bukti-transaksi',
                $newFileName,
                'public'
            );
        }

        DB::beginTransaction();

        try {

            // ====================================
            // UPDATE TRANSAKSI PENGIRIM
            // ====================================
            $trx1->tanggal          = $request->tgl_transfer;
            $trx1->no_spb           = $request->no_spb_transfer;
            $trx1->keterangan       = $request->keterangan_transfer;
            $trx1->bukti_file_name  = $newFileName;
            $trx1->bukti_file_path  = $newFilePath;
            $trx1->status_transaksi = $request->status_transaksi;
            $trx1->update();

            $detail1 = Transaction_detail::where('transaction_id', $id_1)->firstOrFail();

            $detail1->account_id = $request->akun_kas_awal;
            $detail1->nominal    = $nominal;
            $detail1->update();

            // ====================================
            // UPDATE TRANSAKSI PENERIMA
            // ====================================
            $trx2->tanggal          = $request->tgl_transfer;
            $trx2->no_spb           = $request->no_spb_transfer;
            $trx2->keterangan       = $request->keterangan_transfer;
            $trx2->departement_id   = $request->departement_tujuan;
            $trx2->bukti_file_name  = $newFileName;
            $trx2->bukti_file_path  = $newFilePath;
            $trx2->status_transaksi = $request->status_transaksi;
            $trx2->update();

            $detail2 = Transaction_detail::where('transaction_id', $id_2)->firstOrFail();

            $detail2->account_id = $request->akun_kas_tujuan;
            $detail2->nominal    = $nominal;
            $detail2->update();

            // ====================================
            // HAPUS FILE LAMA JIKA DIGANTI
            // ====================================
            if ($request->hasFile('bukti_transaksi') &&
                $oldPath &&
                Storage::disk('public')->exists($oldPath)) {

                Storage::disk('public')->delete($oldPath);
            }

            DB::commit();

            return back()->with('message', 'Berhasil mengubah data Transfer');

        } catch (\Exception $e) {

            DB::rollBack();

            // Hapus file baru jika gagal
            if ($request->hasFile('bukti_transaksi') &&
                $newFilePath &&
                Storage::disk('public')->exists($newFilePath)) {

                Storage::disk('public')->delete($newFilePath);
            }

            return back()->withErrors([
                'message' => 'Gagal mengubah data Transfer'
            ]);
        }
    }
    
}
