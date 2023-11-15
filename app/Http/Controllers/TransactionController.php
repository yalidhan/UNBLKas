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

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        //
        if ($request->filled('periode')){
            $date=explode('-',$request->periode);
            $month=$date[1];
            $year=$date[0];
        };
        if ($request->input('periode')==''){
            $current=Carbon::now();
            $month=$current->month;
            $year=$current->year;
        }
        $departement_id=auth()->user()->departement_id;
        \DB::statement("SET SQL_MODE=''");
        $transaction=DB::select(
            "SELECT t.id, t.no_spb,t.tanggal, t.keterangan,   
                d.dk,sum(d.nominal) AS total
            FROM transactions t
            LEFT JOIN transaction_details d
            ON t.id = d.transaction_id
            WHERE departement_id=$departement_id and month (tanggal)=$month
                and year (tanggal)=$year
            GROUP BY 
                transaction_id
            ORDER BY 
                tanggal ASC,
                id ASC"
        );
        // dd($year,$month);
        $account=Account::get()->where('tipe','=','Pendapatan')->sortBy('no');
        return view('\transaksi/transaksi',['transactionlist'=>$transaction],
            ['accountlist'=>$account])->with ('year',$year)->with('month',$month);

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
        
        $request->validate([
            'tgl_pemasukan'=>'required',
            'kode_pemasukan'=>'required',
            'akun_pendapatan'=>'required',
            'nominal_pemasukan'=>'required',
        ]);
        $nominal_pemasukan_int=$request->nominal_pemasukan;
        $nominal_pemasukan_int=str_replace('.','',$nominal_pemasukan_int);
        Transaction::create([
            'tanggal'=>$request->tgl_pemasukan,
            'no_spb'=>$request->kode_pemasukan,
            'keterangan'=>$request->keterangan_pemasukan,
            'departement_id'=>$request->id_departement,
            'user_id'=>$request->user_id,
        ]);
        
        $last_transaction=Transaction::orderBy('id','desc')->first();
        Transaction_detail::create([
            'transaction_id'=>$last_transaction->id,
            'account_id'=>$request->akun_pendapatan,
            'nominal'=>$nominal_pemasukan_int,
            'dk'=>$request->dk,
        ]);
        return redirect::back()->with ('message','Berhasil menambahkan data Transaksi');
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
