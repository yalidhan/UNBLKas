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
        }
        if ($request->input('periode')==''){
            $current=Carbon::now();
            $month=$current->month;
            $year=$current->year;
        }
        $departement_id=auth()->user()->departement_id;
        \DB::statement("SET SQL_MODE=''");
        $transaction=DB::select(
            "SELECT t.id, t.no_spb,t.tanggal, t.keterangan, t.no_trf, t.user_id, 
                d.dk,sum(d.nominal) AS total,
                u.name
            FROM transactions t
            LEFT JOIN transaction_details d
            ON t.id = d.transaction_id
            LEFT JOIN users u
            ON t.user_id = u.id
            WHERE t.departement_id=$departement_id and month (tanggal)=$month
                and year (tanggal)=$year
            GROUP BY 
                transaction_id
            ORDER BY 
                tanggal ASC,
                id ASC"
        );
        $saldoDebit=DB::select(
            "SELECT t.id, t.no_spb,t.tanggal,   
                d.dk,sum(d.nominal) AS total_debit
            FROM transactions t
            LEFT JOIN transaction_details d
            ON t.id = d.transaction_id
            WHERE departement_id=$departement_id and tanggal <'$year-$month-01' and dk=1 GROUP BY departement_id"
        );
        if ($saldoDebit){
            $saldoDebit=$saldoDebit[0]->total_debit;
        }
        else{
            $saldoDebit=0;
        }
        $saldoKredit=DB::select(
            "SELECT t.id, t.no_spb,t.tanggal,   
                d.dk,sum(d.nominal) AS total_kredit
            FROM transactions t
            LEFT JOIN transaction_details d
            ON t.id = d.transaction_id
            WHERE departement_id=$departement_id and tanggal <'$year-$month-01' and dk=2 GROUP BY departement_id"
        );
        if ($saldoKredit){
            $saldoKredit=$saldoKredit[0]->total_kredit;
        }
        else{
            $saldoKredit=0;
        }
        $saldoLastMonth=$saldoDebit-$saldoKredit;

        $departement=Departement::where('status','=','1')->get();
        $accountHarta=Account::where('nama','LIKE','Kas Kecil%')->where('status','=','1')->orderBy('no')->get();
        // $accountPendapatan=Account::where(function($q){$q->where('tipe','=','Pendapatan')->orWhere('nama','LIKE','Kas Bank%')->orWhere('tipe','=','Modal');})->where('status','=','1')->orderBy('no')->get();
        $accountPendapatan=Account::where('status','=','1')->orderBy('no')->get();
        // dd($accountPendapatan);
        return view('transaksi/transaksi',
            ['transactionlist'=>$transaction],
            ['accountlistPendapatan'=>$accountPendapatan])
                ->with ('year',$year)
                ->with('month',$month)
                ->with('accountlistHarta',$accountHarta)
                ->with('listDepartement',$departement)
                ->with('saldoLastMonth',$saldoLastMonth);
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
        if ($request->tp_trx=='pemasukan'){
            $request->validate([
                'tgl_pemasukan'=>'required',
                'kode_pemasukan'=>'required',
                'akun_pendapatan'=>'required',
                'nominal_pemasukan'=>'required',
            ]);
            $nominal_pemasukan_int=$request->nominal_pemasukan;
            $nominal_pemasukan_int=str_replace('.','',$nominal_pemasukan_int);
            $queryTransaction=Transaction::create([
                'tanggal'=>$request->tgl_pemasukan,
                'no_spb'=>$request->kode_pemasukan,
                'keterangan'=>$request->keterangan_pemasukan,
                'departement_id'=>$request->id_departement,
                'user_id'=>$request->user_id,
            ]);
            if($queryTransaction){
                Transaction_detail::create([
                    'transaction_id'=>$queryTransaction->id,
                    'account_id'=>$request->akun_pendapatan,
                    'nominal'=>$nominal_pemasukan_int,
                    'dk'=>$request->dk,
                ]);}
            }else{

            }
        if ($request->tp_trx=='pengeluaran'){
            $request->validate([
                'tgl_pengeluaran'=>'required',
                'no_spb_pengeluaran'=>'required',
                'keterangan_pengeluaran'=>'required',
            ]);
            Transaction::create([
                'tanggal'=>$request->tgl_pengeluaran,
                'no_spb'=>$request->no_spb_pengeluaran,
                'keterangan'=>$request->keterangan_pengeluaran,
                'departement_id'=>$request->id_departement,
                'user_id'=>$request->user_id,
            ]);}
        if($request->tp_trx=='transfer'){
            $request->validate([
                'departement_tujuan'=>'required',
                'tgl_transfer'=>'required',
                'no_spb_transfer'=>'required',
                'akun_kas_awal'=>'required',
                'akun_kas_tujuan'=>'required',
                'nominal_transfer'=>'required',
            ]);
            $nominal_transfer_int=$request->nominal_transfer;
            $nominal_transfer_int=str_replace('.','',$nominal_transfer_int);
            $last_data=DB::table('transactions')
            ->where('no_trf','like',"TF%")
            ->orderBy('created_at','desc')
            ->first();
            if($last_data){
                $no_urut_str=substr($last_data->no_trf,3);
                $no_urut_int=(int)$no_urut_str+1;
                $no_trf="TF.$no_urut_int";
                // dd($no_trf);
            }
            else{
                $no_trf="TF.1";
                // dd("$no_trf");
            };
            $transferQuery=Transaction::create([
                'tanggal'=>$request->tgl_transfer,
                'no_spb'=>$request->no_spb_transfer,
                'keterangan'=>$request->keterangan_transfer,
                'departement_id'=>$request->id_departement_pengirim,
                'user_id'=>$request->user_id,
                'no_trf'=>$no_trf,
            ]);
            if ($transferQuery){
                Transaction_detail::create([
                    'transaction_id'=>$transferQuery->id,
                    'account_id'=>$request->akun_kas_awal,
                    'nominal'=>$nominal_transfer_int,
                    'dk'=>$request->dk_1,
                ]);
                $transferQuery2=Transaction::create([
                    'tanggal'=>$request->tgl_transfer,
                    'no_spb'=>$request->no_spb_transfer,
                    'keterangan'=>$request->keterangan_transfer,
                    'departement_id'=>$request->departement_tujuan,
                    'user_id'=>$request->user_id,
                    'no_trf'=>$no_trf,
                ]);
                    if($transferQuery2){
                        Transaction_detail::create([
                            'transaction_id'=>$transferQuery2->id,
                            'account_id'=>$request->akun_kas_tujuan,
                            'nominal'=>$nominal_transfer_int,
                            'dk'=>$request->dk_2,
                        ]);}
                    }else{

                    }
            }else{

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
            "SELECT t.id, t.no_spb,t.tanggal, t.keterangan,t.user_id,
                dp.id AS departement_id, dp.nama AS departement,sum(d.nominal) AS total
            FROM transactions t
            LEFT JOIN transaction_details d
            ON t.id = d.transaction_id
            LEFT JOIN departements dp
            ON t.departement_id = dp.id
            WHERE transaction_id=$id"
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
        return view('transaksi/rincian_transaksi',compact('showTransaction','showDetailTransaction','accountList'));
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
        //
        $request->validate([
            'tgl_edit' => 'required',
            'no_spb_edit' => 'required',
            'keterangan_edit' => 'required'
        ]);
        $transaction = Transaction::find($id);
        $transaction->tanggal = $request->tgl_edit;
        $transaction->no_spb = $request->no_spb_edit;
        $transaction->keterangan = $request->keterangan_edit;
        $transaction->update();

        return redirect::back()->with('message', 'Berhasil Mengubah Data Transaksi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        // dd(str_contains($id,'TF.'));
        if (str_contains($id,'TF.')){
            Transaction::where('no_trf',$id)->delete();
        }
        else {
            Transaction::where('id',$id)->delete();
        }
        return redirect::back()->with ('message','Berhasil menghapus data Transaksi');
    }

    public function destroyRincian(string $id)
    {
        Transaction_detail::where('id',$id)->delete();
        return redirect::back()->with('message','Berhasil menghapus rincian Transaksi');
    }

    public function storeRincian(Request $request)
    {
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
        return redirect::back()->with ('message','Berhasil menmbah rincian transaksi');
    }
    public function updateRincian(Request $request,$id)
    {
        $request->validate([
            'akun_rincian_edit' => 'required',
            'nominal_tambah_rincian_edit' => 'required'
        ]);
        $nominal_rincian_int=$request->nominal_tambah_rincian_edit;
        $nominal_rincian_int=str_replace('.','',$nominal_rincian_int);

        $updateRincian = Transaction_detail::find($id);
        $updateRincian->account_id = $request->akun_rincian_edit;
        $updateRincian->nominal = $nominal_rincian_int;
        $updateRincian->update();

        return redirect::back()->with('message', 'Berhasil Mengubah Data Rincian');
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
        $request->validate([
            'departement_tujuan'=>'required',
            'tgl_transfer'=>'required',
            'no_spb_transfer'=>'required',
            'akun_kas_awal'=>'required',
            'akun_kas_tujuan'=>'required',
            'nominal_transfer'=>'required',
        ]);
        $nominal_transfer_int=$request->nominal_transfer;
        $nominal_transfer_int=str_replace('.','',$nominal_transfer_int);
        $id_1=$request->id_1;
        $id_2=$request->id_2;

        $updateTransfer1 = Transaction::find($id_1);
            $updateTransfer1->tanggal = $request->tgl_transfer;
            $updateTransfer1->no_spb = $request->no_spb_transfer;
            $updateTransfer1->keterangan = $request->keterangan_transfer;
        $updateTransfer1->update();
        $detailTransfer1id = Transaction_detail::where('transaction_id','=',$id_1)->first();
        $updateDetailTransfer1 = Transaction_detail::find($detailTransfer1id->id);
        $updateDetailTransfer1->account_id = $request->akun_kas_awal;
        $updateDetailTransfer1->nominal = $nominal_transfer_int;
        $updateDetailTransfer1->update();

        $updateTransfer2 = Transaction::find($id_2);
            $updateTransfer2->tanggal = $request->tgl_transfer;
            $updateTransfer2->no_spb = $request->no_spb_transfer;
            $updateTransfer2->keterangan = $request->keterangan_transfer;
            $updateTransfer2->departement_id = $request->departement_tujuan;
        $updateTransfer2->update();

        $detailTransfer2id = Transaction_detail::where('transaction_id','=',$id_2)->first();
        $updateDetailTransfer2 = Transaction_detail::find($detailTransfer2id->id);
        $updateDetailTransfer2->account_id = $request->akun_kas_tujuan;
        $updateDetailTransfer2->nominal = $nominal_transfer_int;
        $updateDetailTransfer2->update();

        return redirect::back()->with('message', 'Berhasil mengubah data Transfer');
    }
    
}
