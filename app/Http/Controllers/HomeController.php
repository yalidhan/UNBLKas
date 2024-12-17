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
        $rencana_anggaran=DB::select(
        "SELECT b.id, b.departement_id,b.tahun,
            dp.nama,
            a.id,a.no,a.nama,a.kelompok,
            bd.account_id,bd.budget_id,sum(bd.nominal) as total
            FROM budgets b
            LEFT JOIN departements dp
                ON b.departement_id = dp.id
            LEFT JOIN budget_details bd
                ON b.id = bd.budget_id
            LEFT JOIN accounts a 
            ON bd.account_id = a.id 
            WHERE departement_id=$departement_id AND tahun=$year AND a.kelompok NOT IN('','Transfer Antar Bank')
            GROUP BY b.id"
            );
        $realisasi_anggaran=DB::select(
        "SELECT t.id,t.departement_id,t.tanggal,
                d.transaction_id, d.account_id,sum(d.nominal) as total, 
                a.id,a.no,a.nama,a.kelompok 
        FROM transactions t 
        LEFT JOIN transaction_details d 
            ON t.id = d.transaction_id 
        LEFT JOIN accounts a 
            ON d.account_id = a.id 
        WHERE t.departement_id=$departement_id 
            AND YEAR(tanggal)=$year 
            AND DK=2 
            -- AND a.no Like '02.%'
            AND a.no NOT Like '01.%' 
            -- AND a.no NOT Like '01.%'
            -- AND a.no NOT Like '02.%'   
            -- AND a.no NOT Like '03.%'  
            AND a.kelompok NOT IN('','Transfer Antar Bank')
        GROUP BY departement_id
        ");
        $realisasi_anggaranDebit=DB::select(
            "SELECT t.id,t.departement_id,t.tanggal,
                    d.transaction_id,d.account_id,sum(d.nominal) as total, 
                    a.id,a.no,a.nama,a.kelompok 
            FROM transactions t 
            LEFT JOIN transaction_details d 
                ON t.id = d.transaction_id 
            LEFT JOIN accounts a 
                ON d.account_id = a.id 
            WHERE t.departement_id=$departement_id AND YEAR(tanggal)=$year 
                AND DK=1 
                -- AND a.no Like '02.%'
                -- AND a.no NOT Like '04.%'  
                AND a.no NOT Like '01.%'  
                -- AND a.no NOT Like '03.%'  
                AND a.kelompok NOT IN('','Transfer Antar Bank')
            GROUP BY departement_id
            ");
            // dd($realisasi_anggaran,$realisasi_anggaranDebit);
            if(empty($realisasi_anggaranDebit)){
                $realisasi_anggaranDebit=0;
            }else{
                $realisasi_anggaranDebit=$realisasi_anggaranDebit[0]->total;
            }
            if(empty($realisasi_anggaran)){
                $realisasi_anggaran=0;
            }else{
                $realisasi_anggaran=$realisasi_anggaran[0]->total;
            }
        $realisasi_anggaranReal=$realisasi_anggaran-$realisasi_anggaranDebit;
        
        if (empty($rencana_anggaran) or $rencana_anggaran[0]->total==0){
            $rencana_anggaran=0;
        }
        // dd($realisasi_anggaran,$rencana_anggaran);
        if($realisasi_anggaran==0 or $rencana_anggaran==0){
            $persentaseRealisasi=0;
        }else{
            $persentaseRealisasi=$realisasi_anggaranReal/($rencana_anggaran[0]->total)*100;
        }
        

        return view('home',
            ['transactionlist'=>$transaction])
                ->with('saldoDebitList',$saldoDebit)
                ->with('saldoKreditList',$saldoKredit)
                ->with('saldoLastMonth',$saldoLastMonth)
                ->with('persentasePengeluaran',$persentasePengeluaran)
                ->with('rencana_anggaran',$rencana_anggaran)
                ->with('realisasi_anggaran',$realisasi_anggaranReal)
                ->with('persentaseRealisasi',$persentaseRealisasi)
                ->with('persentaseSaldo',$persentaseSaldo);
    }
}
