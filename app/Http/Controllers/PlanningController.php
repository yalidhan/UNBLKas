<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use App\Models\Departement;
use App\Models\Budget;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Budget_detail;
use App\Models\Perencanaan_detail;
use Illuminate\Support\Facades\DB;
use Redirect;

class PlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (auth()->user()->jabatan=="Bendahara Yayasan" 
            or auth()->user()->jabatan=="Rektor"
            or auth()->user()->jabatan=="Wakil Rektor II"
            or auth()->user()->jabatan=="Super Admin"
            or auth()->user()->jabatan=="Kabid Keuangan"
            or auth()->user()->jabatan=="Kabid Perencanaan"
            ){
            $plannings=Planning::orderby('for_bulan','DESC')->orderBy('created_at','ASC')->get();
        }else{
            $plannings=Planning::where('departement_id','=',auth()->user()->departement_id)->orderBy('created_at','DESC')->get();
        }
        // dd($plannings);
        $departements=Departement::where('status','=','1')->get();
        return view('\planning/planning',compact('plannings'));
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
            'for_bulan'=>'required',
        ]);
        $date=explode('-',$request->for_bulan);
        $month=$date[1];
        $year=$date[0];
        $monthYear=$year.'-'.$month.'-01';
        // dd($monthYear);
        // dd($request->for_bulan,$monthYear);
        $user_id=auth()->user()->id;
        $departement_id=auth()->user()->departement_id;
        $budget_id=Budget::where('tahun','=',$year)->where('departement_id','=',$departement_id)->orderBy('created_at','ASC')->first();
        if(empty($budget_id->id)){
            return redirect()->route('perencanaan.index')->withErrors(['message' => 'Tidak Terdapat Data Anggaran Tahun '.$year]);
        }
        // dd($budget_id->tahun);
        Planning::create([
            'for_bulan'=>$monthYear,
            'departement_id'=>$departement_id,
            'user_id'=>$user_id,
            'budget_id'=>$budget_id->id,
        ]);
        
        return redirect()->route('perencanaan.index')->withErrors(['message' => ''])->with('message','Berhasil menambahkan data anggaran');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        \DB::statement("SET SQL_MODE=''");
        $showPlanning=DB::select(
            "SELECT p.created_at, p.user_id, p.id,p.budget_id, p.for_bulan, 
            dp.id AS departement_id,dp.nama AS departement,
            sum(pd.nominal) AS total_perencanaan
            FROM plannings p
            LEFT JOIN planning_details pd
            ON p.id = pd.planning_id
            LEFT JOIN departements dp
            ON p.departement_id = dp.id
            WHERE planning_id=$id"
        );
        $departement=$showPlanning[0]->departement_id;
        $date=explode('-',$showPlanning[0]->for_bulan);
        $month=$date[1];
        $tahun_anggaran=$date[0];

        $showDetailPlanning=DB::select(
            "SELECT a.id as account_id,a.nama,
                pd.id,pd.group_rektorat,pd.pj,pd.nominal,pd.nominal_disetujui,pd.satuan_ukur_kinerja,
                pd.target_kinerja,pd.capaian_kinerja,pd.waktu_pelaksanaan,pd.approved_by_wr2,pd.note_wr2,
                pd.approved_by_rektor,pd.note_rektor,pd.capaian_target_waktu
            FROM planning_details pd
            LEFT JOIN accounts a
            ON pd.account_id = a.id
            WHERE planning_id = $id;"
        );
        // dd($showDetailPlanning);
        // $accountList=Account::where('status','=','1')->orderBy('no', 'ASC')->get();
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
                departement_id=$departement AND tahun=$tahun_anggaran
            ORDER BY
                account_id ASC;"
        );
        // dd($accountList);

        return view('\planning\rincian_planning',compact('showPlanning','showDetailPlanning','accountList'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Planning $planning)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'for_bulan_edit' => 'required'
        ]);
        $user_id=auth()->user()->id;

        $budget = Planning::find($id);
        $budget->for_bulan = $request->for_bulan_edit.'-1';
        $budget->update();

        return redirect::back()->with('message', 'Berhasil Mengubah Data Perencanaan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $perencanaan)
    {
        //
        // dd($perencanaan);
        Planning::where('id',$perencanaan)->delete();
        return redirect()->route('perencanaan.index')->withErrors(['message' => ''])->with('message','Berhasil menghapus data perencanaan');
    }
}
