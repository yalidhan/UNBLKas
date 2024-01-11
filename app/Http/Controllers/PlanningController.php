<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use App\Models\Departement;
use App\Models\Budget;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Budget_detail;
use App\Models\Planning_detail;
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
        return view('planning/planning',compact('plannings'));
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
            pd.account_id,sum(pd.nominal) AS total_perencanaan
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

        return view('planning/rincian_planning',compact('showPlanning','showDetailPlanning','accountList'));
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

    public function storeRincianP(Request $request)
    {
        $request->validate([
            'akun_rincian'=>'required',
            'jumlah_anggaran_tambah_rincian'=>'required',
            'pj'=>'required',
            'satuan_ukur_kinerja'=>'required',
            'target_kinerja'=>'required',
            'capaian_kinerja'=>'required',
            'target_waktu_pelaksanaan'=>'required',
            'capaian_target_waktu_penyelesaian'=>'required',
            'planning_id'=>'required',
        ]);
        // dd($request->akun_rincian);
        $nominal_rincian_int=$request->jumlah_anggaran_tambah_rincian;
        $nominal_rincian_int=str_replace('.','',$nominal_rincian_int);
        $find=Planning_detail::where('planning_id','=',$request->planning_id)->where('account_id','=',$request->akun_rincian)->get();
        // dd(!$find->isEmpty());
        if (!$find->isEmpty()){
            return redirect::back()->withErrors(['message' => 'Mata akun sudah terdaftar, silahkan cek ulang']);
        }
        Planning_detail::create([
            'planning_id'=>$request->planning_id,
            'account_id'=>$request->akun_rincian,
            'nominal'=>$nominal_rincian_int,
            'group_rektorat'=>$request->group_rektorat,
            'pj'=>$request->pj,
            'satuan_ukur_kinerja'=>$request->satuan_ukur_kinerja,
            'target_kinerja'=>$request->target_kinerja,
            'capaian_kinerja'=>$request->capaian_kinerja,
            'waktu_pelaksanaan'=>$request->target_waktu_pelaksanaan,
            'capaian_target_waktu'=>$request->capaian_target_waktu_penyelesaian,
        ]);
        return redirect::back()->with ('message','Berhasil menmbah rincian perencanaan');
    }

    public function destroyRincianP(string $id)
    {
       Planning_detail::where('id',$id)->delete();
        return redirect::back()->withErrors(['message' => ''])->with('message','Berhasil menghapus rincian perencanaan');
    }

    public function updateRincianP(Request $request,$id)
    {
        $request->validate([
            'planning_id'=>$request->planning_id,
            'account_id'=>$request->akun_rincian,
            'nominal'=>$nominal_rincian_int,
            'pj'=>$request->pj,
            'satuan_ukur_kinerja'=>$request->satuan_ukur_kinerja,
            'target_kinerja'=>$request->target_kinerja,
            'capaian_kinerja'=>$request->capaian_kinerja,
            'waktu_pelaksanaan'=>$request->target_waktu_pelaksanaan,
            'capaian_target_waktu'=>$request->capaian_target_waktu_penyelesaian,
        ]);
        // dd($request->current_account,$request->akun_rincian_edit);
        $nominal_rincian_int=$request->nominal_tambah_rincian_edit;
        $nominal_rincian_int=str_replace('.','',$nominal_rincian_int);
        $find=Budget_detail::where('budget_id','=',$request->budget_id)->where('account_id','=',$request->akun_rincian_edit)->get();
        // dd(!$find->isEmpty());
        if (!$find->isEmpty()){
            if($request->current_account==$request->akun_rincian_edit){
                $updateRincian = Budget_detail::find($id);
                $updateRincian->account_id = $request->akun_rincian_edit;
                $updateRincian->nominal = $nominal_rincian_int;
                $updateRincian->keterangan = $request->keterangan_rincian_edit;
                $updateRincian->update();
                return redirect::back()->with('message', 'Berhasil mengubah data rincian');
            }
            return redirect::back()->withErrors(['message' => 'Mata akun sudah terdaftar, silahkan cek ulang']);
        }
        $updateRincian = Budget_detail::find($id);
        $updateRincian->account_id = $request->akun_rincian_edit;
        $updateRincian->nominal = $nominal_rincian_int;
        $updateRincian->keterangan = $request->keterangan_rincian_edit;
        $updateRincian->update();

        return redirect::back()->with('message', 'Berhasil mengubah data rincian');
    }
}
