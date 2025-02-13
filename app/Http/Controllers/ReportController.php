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

class ReportController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function realisasiPage()
    {
        // $tahun=Carbon::now()->format('Y');
        // $departement_id=auth()->user()->departement_id;
        if(auth()->user()->departement_id==1){
            $departement=Departement::where('status','=','1')->get();
        }
        elseif(auth()->user()->jabatan=="Dekan"){
            $dp_id=auth()->user()->departement_id;
            $pusat=Departement::where('id',$dp_id)->value('pusat');
            // $departement=DB::select("
            // SELECT * FROM departements WHERE status= 1 and pusat='$pusat'");
            // dd($pusat);
            $departement = Departement::where([
                ['status', '1'],
                ['pusat', $pusat],
            ])->get();
        }else{
            $departement=Departement::where('status','=','1')->whereNotIn('id',[1,18,19,20,21])->get();  
            // $departement=Departement::where('status','=','1')->where('id','!=','1')->where('id','!=','18')->get();
        }
        // $account=Account::where('kelompok','!=','""')->groupby('kelompok')->get();
        \DB::statement("SET SQL_MODE=''");
        // if($departement_id==1){
            $account=DB::select(
                "SELECT * FROM accounts WHERE kelompok !='' GROUP BY kelompok"
            );
        // }else{
        //     $account=DB::select(
        //         "SELECT a.kelompok FROM budgets b 
        //             LEFT JOIN departements dp ON b.departement_id = dp.id 
        //             LEFT JOIN budget_details bd ON b.id = bd.budget_id 
        //             LEFT JOIN accounts a ON bd.account_id = a.id 
        //             WHERE departement_id=$departement_id AND tahun=$tahun 
        //             GROUP BY kelompok;"
        //     );
        // }

        
        // dd($account);
        return view('laporan/realisasianggaran')
            ->with('account',$account)
            ->with('departement',$departement);
    }

    public function realisasiCetak(Request $request)
    {
        if ($request->departement==""){
            $d_id=auth()->user()->departement_id;
            $departement_id="departement_id=$d_id";
            $departement=Departement::find(auth()->user()->departement_id);
        }elseif($request->departement==0){
            $departement_id="departement_id NOT IN (1,18,19,20,21)";//Ini Perlu ditambah jika ada departement di luar univ
            $departement=array(
                "nama"=>"Universitas Borneo Lestari",
                "id"=>"0"
            );
        }elseif($request->departement==1){
            $departement_id="departement_id IN (1,19,20,21)";//Ini Perlu ditambah jika ada tambahan yayasan
            $departement=array(
                "nama"=>"Yayasan Borneo Lestari",
                "id"=>"1"
            );
        }
        else{
            $departement_id="departement_id=$request->departement";
            $departement=Departement::find($request->departement);
        }
        // dd($departement['nama']);
        if ($request->kelompok_anggaran==""){
            $kelompok="NOT IN('','Transfer Antar Bank')";
        }else{
            $kelompok="='$request->kelompok_anggaran'";
        }

        $tahun=Carbon::parse($request->sampai)->format('Y');
        $sd=Carbon::parse($request->sampai)->format('F Y');
        $sd2=$request->sampai;
        // dd($sd2,$sd);
        // dd($departement);
        \DB::statement("SET SQL_MODE=''");

        $kelompok=DB::select(
            "SELECT b.id, b.departement_id,b.tahun,
            dp.nama,
            bd.budget_id,bd.id,bd.account_id,sum(bd.nominal) as total,
            a.kelompok
            FROM budgets b
            LEFT JOIN departements dp
                ON b.departement_id = dp.id
            LEFT JOIN budget_details bd
                ON b.id = bd.budget_id
            LEFT JOIN accounts a
                ON bd.account_id = a.id
            WHERE $departement_id AND tahun=$tahun AND kelompok $kelompok
            GROUP BY kelompok
            ORDER BY account_id ASC"
        );
        // dd($kelompok);

        return view('laporan/cetak-realisasianggaran')
                    ->with('kelompok',$kelompok)
                    ->with('sd',$sd)
                    ->with('sd2',$sd2)
                    ->with('tahun',$tahun)
                    ->with('departement',$departement);
    }

    public function logTransaksi(Request $request)
    {
        $tahun=$request->thn;
        $sd=$request->sd;
        $account_id=$request->akn;
        $departement_id=$request->dp;
        $departement=Departement::find($request->dp);
        // dd($departement->nama);
        $logtransaksi=DB::select(
            "SELECT t.keterangan,t.no_spb,t.id,t.tanggal,d.dk, d.account_id,d.nominal,
                                a.id,a.no,a.nama,dp.nama as departemen 
            FROM transactions t 
            LEFT JOIN transaction_details d 
                                ON t.id = d.transaction_id 
            LEFT JOIN accounts a 
                                ON d.account_id = a.id 
            LEFT JOIN departements dp
                                ON t.departement_id=dp.id
            WHERE t.departement_id=$departement_id AND t.tanggal BETWEEN '$tahun-01-01' and '$sd' AND d.account_id=$account_id AND dk=2            
        ");
        // dd($logtransaksi);
        return view('laporan/logtransaksi')
                    ->with('logtransaksi',$logtransaksi)
                    ->with('sd',$sd)
                    ->with('departement',$departement);
    }


    public function lpjPage()
    {   
        if(auth()->user()->departement_id==1){
            $departement=Departement::where('status','=','1')->get();
        }
        elseif(auth()->user()->jabatan=="Dekan"){
            $dp_id=auth()->user()->departement_id;
            $pusat=Departement::where('id',$dp_id)->value('pusat');
            // $departement=DB::select("
            // SELECT * FROM departements WHERE status= 1 and pusat='$pusat'");
            // dd($pusat);
            $departement = Departement::where([
                ['status', '1'],
                ['pusat', $pusat],
            ])->get();
        }
        else{  
            $departement=Departement::where('status','=','1')->whereNotIn('id',[1,18,19,20,21])->get();
            // $departement=Departement::where('status','=','1')->where('id','!=','1')->where('id','!=','18')->get();
        }
        
        return view('laporan/pertanggungjawaban')->with('departement',$departement);
    }

    public function lpjCetak(Request $request)
    {
        if ($request->departement==""){
            $departement_id=auth()->user()->departement_id;
        }else{
            $departement_id=$request->departement;
        }
        \DB::statement("SET SQL_MODE=''");
        $transaction=DB::select(
            "SELECT t.id,t.departement_id, t.no_spb,t.tanggal, t.keterangan, t.no_trf, t.user_id, dp.nama, 
                d.dk,sum(d.nominal) AS total,
                u.name
            FROM transactions t
            LEFT JOIN transaction_details d
            ON t.id = d.transaction_id
            LEFT JOIN users u
            ON t.user_id = u.id
            LEFT JOIN departements dp
            ON t.departement_id = dp.id
            WHERE t.departement_id=$departement_id and tanggal BETWEEN '$request->dari' and '$request->sampai'
            GROUP BY 
                d.transaction_id, t.id
            ORDER BY 
                tanggal ASC,
                id ASC"
        );

        $periode = array(
            "dari"=>$request->dari,
            "sampai"=>$request->sampai
        );
        $saldoDebitLastMonth=DB::select(
            "SELECT t.id,t.departement_id, t.no_spb,t.tanggal,   
                d.dk,sum(d.nominal) AS total_debit
            FROM transactions t
            LEFT JOIN transaction_details d
            ON t.id = d.transaction_id
            WHERE departement_id=$departement_id and tanggal <'$request->dari' and dk=1 GROUP BY departement_id"
        );
        if ($saldoDebitLastMonth){
            $saldoDebitLastMonth=$saldoDebitLastMonth[0]->total_debit;
        }
        else{
            $saldoDebitLastMonth=0;
        }
        $saldoKreditLastMonth=DB::select(
            "SELECT t.id,t.departement_id, t.no_spb,t.tanggal,   
                d.dk,sum(d.nominal) AS total_kredit
            FROM transactions t
            LEFT JOIN transaction_details d
            ON t.id = d.transaction_id
            WHERE departement_id=$departement_id and tanggal <'$request->dari' and dk=2 GROUP BY departement_id"
        );

        if ($saldoKreditLastMonth){
            $saldoKreditLastMonth=$saldoKreditLastMonth[0]->total_kredit;
        }
        else{
            $saldoKreditLastMonth=0;
        }
        $saldoLastMonth=$saldoDebitLastMonth-$saldoKreditLastMonth;
        $departementNama=Departement::find($departement_id);
        // dd($periode);
        return view('laporan/cetak-pertanggungjawaban')
                ->with('transactionList',$transaction)
                ->with('departementNama',$departementNama)
                ->with('periode',$periode)
                ->with('departement_id',$departement_id)
                ->with('saldoLastMonth',$saldoLastMonth);
    }

    public function perencanaanCetak(Request $request)
    {
        $date=explode('-',$request->periode);
        $month=$date[1];
        $year=$date[0];
        $departement_id=$request->departement;
        $p_id=$request->p_id;
        // dd($date,$month,$year);
        if (!empty($request->departement)){
            $d_query="AND departement_id=$departement_id" ;
        }
        else{
            $d_query="";
        }

        \DB::statement("SET SQL_MODE=''");
        $pusat=DB::select(
            "SELECT p.for_bulan,
                    d.pusat
            FROM plannings p
            LEFT JOIN departements d
                ON p.departement_id = d.id
            WHERE p.for_bulan='$year-$month' $d_query 
            GROUP BY d.pusat"
        );
        return view('laporan/cetak-perencanaan')
                ->with('month',$month)
                ->with('year',$year)
                ->with('pusat',$pusat)
                ->with('departement_id',$departement_id)
                ->with('p_id',$p_id)
                ;
    }
    public function posisikasPage()
    {   
       return view('laporan/posisikas');
    }

    public function posisikasCetak(Request $request)
    {
        $tahun=Carbon::parse($request->periode)->format('Y');
        $sd=Carbon::parse($request->periode)->format('F Y');
        $sd2=$request->periode;
        $lastdateperiode=carbon::create($sd2)->endOfMonth()->toDateString();

        $departement=Departement::where('status','=','1')->get();      

        return view('laporan/cetak-posisikas')
        ->with('sd',$sd)
        ->with('sd2',$sd2)
        ->with('tahun',$tahun)
        ->with('lastdateperiode',$lastdateperiode)
        ->with('departements',$departement);
    }

}
