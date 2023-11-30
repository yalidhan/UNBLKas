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

    public function lpjPage()
    {
        $departement=Departement::where('status','=','1')->get();
    return view('\laporan/pertanggungjawaban')->with('departement',$departement);
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
                transaction_id
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
                d.dk,sum(d.nominal) AS total_debit
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
        return view('\laporan/cetak-pertanggungjawaban')
                ->with('transactionList',$transaction)
                ->with('departementNama',$departementNama)
                ->with('periode',$periode)
                ->with('saldoLastMonth',$saldoLastMonth);
    }
}
