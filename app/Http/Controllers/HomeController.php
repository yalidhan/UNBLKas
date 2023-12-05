<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Transaction_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Redirect;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
            $current=Carbon::now();
            $month=$current->month;
            $year=$current->year;
            $first_day=Carbon::parse($year.'-'.$month)->startOfMonth()->format('Y-n-d');
            $last_day=Carbon::parse($year.'-'.$month)->lastOfMonth()->format('Y-n-d');

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
            "SELECT t.id,t.departement_id, t.no_spb,t.tanggal,   
                d.dk,sum(d.nominal) AS total_debit
            FROM transactions t
            LEFT JOIN transaction_details d
            ON t.id = d.transaction_id
            WHERE departement_id=$departement_id and tanggal BETWEEN '$first_day' and '$last_day' and dk=1 GROUP BY departement_id"
        );
        if ($saldoDebit){
            $saldoDebit=$saldoDebit[0]->total_debit;
        }
        else{
            $saldoDebit=0;
        }
        $saldoKredit=DB::select(
            "SELECT t.id,t.departement_id, t.no_spb,t.tanggal,   
                d.dk,sum(d.nominal) AS total_kredit
            FROM transactions t
            LEFT JOIN transaction_details d
            ON t.id = d.transaction_id
            WHERE departement_id=$departement_id and tanggal BETWEEN '$first_day' and '$last_day' and dk=2 GROUP BY departement_id"
        );
        if ($saldoKredit){
            $saldoKredit=$saldoKredit[0]->total_kredit;
        }
        else{
            $saldoKredit=0;
        }
        $saldoDebitLastMonth=DB::select(
            "SELECT t.id,t.departement_id, t.no_spb,t.tanggal,   
                d.dk,sum(d.nominal) AS total_debit
            FROM transactions t
            LEFT JOIN transaction_details d
            ON t.id = d.transaction_id
            WHERE departement_id=$departement_id and tanggal <'$year-$month-01' and dk=1 GROUP BY departement_id"
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
            WHERE departement_id=$departement_id and tanggal <'$year-$month-01' and dk=2 GROUP BY departement_id"
        );
        // dd($saldoKreditLastMonth);
        if ($saldoKreditLastMonth){
            $saldoKreditLastMonth=$saldoKreditLastMonth[0]->total_kredit;
        }
        else{
            $saldoKreditLastMonth=0;
        }
        $saldoLastMonth=$saldoDebitLastMonth-$saldoKreditLastMonth;
       
        if($saldoDebit==0 and $saldoLastMonth==0){
            $persentasePengeluaran=0;
        }else{
            $persentasePengeluaran=$saldoKredit/($saldoDebit+$saldoLastMonth)*100;
        }
        if($saldoDebit==0 and $saldoLastMonth==0){
            $persentaseSaldo=0;
        }else{
            $persentaseSaldo=($saldoDebit+$saldoLastMonth-$saldoKredit)/($saldoDebit+$saldoLastMonth)*100;
        }
        // dd($persentaseSaldo);
        // dd($persentasePengeluaran,$persentaseSaldo);
        return view('home',
            ['transactionlist'=>$transaction])
                ->with('saldoDebitList',$saldoDebit)
                ->with('saldoKreditList',$saldoKredit)
                ->with('saldoLastMonth',$saldoLastMonth)
                ->with('persentasePengeluaran',$persentasePengeluaran)
                ->with('persentaseSaldo',$persentaseSaldo);
    }
}
