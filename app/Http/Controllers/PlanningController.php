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
    public function __construct()
    {
        $this->middleware('auth');
    }
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
            OR auth()->user()->departement_id==1
            ){
            \DB::statement("SET SQL_MODE=''");
            $plannings=DB::select(
                "SELECT p.id,p.for_bulan, p.created_at, 
                    count(case when pd.approved_by_wr2=1 THEN 1 END) AS WR_1, 
                    count(case when pd.approved_by_wr2=2 THEN 2 END) AS WR_2, 
                    count(case when pd.approved_by_wr2=0 THEN 0 END) AS WR_0,
                    count(case when pd.approved_by_wr2=3 THEN 3 END) AS WR_3,  
                    count(case when pd.approved_by_rektor=1 THEN 1 END) AS REKTOR_1, 
                    count(case when pd.approved_by_rektor=2 THEN 2 END) AS REKTOR_2, 
                    count(case when pd.approved_by_rektor=0 THEN 0 END) AS REKTOR_0,
                    count(case when pd.approved_by_rektor=3 THEN 3 END) AS REKTOR_3,
                    count(case when pd.status='Paid' THEN 1 END) AS STATUS_1, 
                    count(case when pd.status='Unpaid' THEN 2 END) AS STATUS_2, 
                    count(case when pd.status='Pending' THEN 0 END) AS STATUS_0,    
                    sum(pd.nominal) AS nominal, sum(pd.nominal_disetujui) AS nominal_disetujui,
                    d.nama,
                    b.tahun, 
                    u.name, u.id AS user_id 
                    FROM plannings p LEFT JOIN planning_details pd 
                    ON p.id = pd.planning_id 
                    LEFT JOIN departements d 
                    ON p.departement_id = d.id 
                    LEFT JOIN budgets b 
                    ON p.budget_id = b.id 
                    LEFT JOIN users u 
                    ON p.user_id = u.id 
                    GROUP by p.id 
                    ORDER BY p.for_bulan DESC, p.created_at ASC;"
            );
        }else{
            $departement_id=auth()->user()->departement_id;
            \DB::statement("SET SQL_MODE=''");
            $plannings=DB::select(
                "SELECT p.id,p.for_bulan, p.created_at,p.departement_id, 
                    count(case when pd.approved_by_wr2=1 THEN 1 END) AS WR_1, 
                    count(case when pd.approved_by_wr2=2 THEN 2 END) AS WR_2, 
                    count(case when pd.approved_by_wr2=0 THEN 0 END) AS WR_0,
                    count(case when pd.approved_by_wr2=3 THEN 3 END) AS WR_3,  
                    count(case when pd.approved_by_rektor=1 THEN 1 END) AS REKTOR_1, 
                    count(case when pd.approved_by_rektor=2 THEN 2 END) AS REKTOR_2, 
                    count(case when pd.approved_by_rektor=0 THEN 0 END) AS REKTOR_0,
                    count(case when pd.approved_by_rektor=3 THEN 3 END) AS REKTOR_3,
                    count(case when pd.status='Paid' THEN 1 END) AS STATUS_1, 
                    count(case when pd.status='Unpaid' THEN 2 END) AS STATUS_2, 
                    count(case when pd.status='Pending' THEN 0 END) AS STATUS_0,                    
                    sum(pd.nominal) AS nominal, sum(pd.nominal_disetujui) AS nominal_disetujui, 
                    count(pd.account_id) AS accounts,
                    d.nama,
                    b.tahun, 
                    u.name, u.id AS user_id 
                    FROM plannings p LEFT JOIN planning_details pd 
                    ON p.id = pd.planning_id 
                    LEFT JOIN departements d 
                    ON p.departement_id = d.id 
                    LEFT JOIN budgets b 
                    ON p.budget_id = b.id 
                    LEFT JOIN users u 
                    ON p.user_id = u.id
                    WHERE p.departement_id=$departement_id
                    GROUP by p.id 
                    ORDER BY p.for_bulan DESC, p.created_at ASC;"
            );

        }
        // dd($plannings);

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
        // $monthYear=$year.'-'.$month.'-01';
        // dd($month);
        // dd($request->for_bulan,$monthYear);
        $user_id=auth()->user()->id;
        $departement_id=auth()->user()->departement_id;
        $budget_id=Budget::where('tahun','=',$year)->where('departement_id','=',$departement_id)->orderBy('created_at','ASC')->first();
        if(empty($budget_id->id)){
            return redirect()->route('perencanaan.index')->withErrors(['message' => 'Tidak Terdapat Data Anggaran Tahun '.$year]);
        }
        // dd($budget_id->tahun);
        $planningQuery=Planning::create([
            'for_bulan'=>$request->for_bulan,
            'departement_id'=>$departement_id,
            'user_id'=>$user_id,
            'budget_id'=>$budget_id->id,
        ]);
        $planning_id=$planningQuery->id;
        return redirect('perencanaan/'.$planning_id);
        // return redirect()->route('perencanaan.index')->withErrors(['message' => ''])->with('message','Berhasil menambahkan data anggaran');
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
            pd.account_id,sum(pd.nominal) AS total_perencanaan,sum(pd.nominal_disetujui) AS total_disetujui
            FROM plannings p
            LEFT JOIN planning_details pd
            ON p.id = pd.planning_id
            LEFT JOIN departements dp
            ON p.departement_id = dp.id
            WHERE p.id=$id"
        );
        $setujuBayar=DB::select("
        SELECT sum(nominal_disetujui) AS total_setujubayar
        FROM planning_details
        WHERE planning_id=$id and status='Paid'");
        $departement=$showPlanning[0]->departement_id;
        $date=explode('-',$showPlanning[0]->for_bulan);
        // $month=$date[1];
        $tahun_anggaran=$date[0];
        // dd($tahun_anggaran);
        $showDetailPlanning=DB::select(
            "SELECT a.id as account_id,a.nama,
                pd.jenis,pd.id,pd.group_rektorat,pd.pj,pd.nominal,pd.nominal_disetujui,pd.satuan_ukur_kinerja,pd.judul_file,
                pd.target_kinerja,pd.capaian_kinerja,pd.waktu_pelaksanaan,pd.approved_by_wr2,pd.note_wr2,pd.note,pd.status,
                pd.approved_by_rektor,pd.note_rektor,pd.capaian_target_waktu,pd.updated_at
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

        return view('planning/rincian_planning',compact('setujuBayar','showPlanning','showDetailPlanning','accountList'));
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
        if ($request->pjb=="wr2"){
            $planning_detail=Planning_detail::where('planning_id','=',$id)->where('approved_by_wr2','=','0')->get();
            // dd($planning_detail);
            foreach ($planning_detail as $detail){
                $update = Planning_detail::find($detail['id']);
                $update->nominal_disetujui=$detail['nominal'];
                $update->approved_by_wr2=1;
                $update->save();
                
            }
            return redirect::back()->with('message', 'Berhasil Mensetujui Semua Yang Masih Ditinjau');

        }
        elseif($request->pjb=="rektor"){
            $planning_detail=Planning_detail::where('planning_id','=',$id)->where('approved_by_rektor','=','0')
                ->where('approved_by_wr2','!=','0')->get();
            // dd($planning_detail);
            foreach ($planning_detail as $detail){
                $update = Planning_detail::find($detail['id']);
                $update->approved_by_rektor=$detail->approved_by_wr2;
                $update->save();
            }
            return redirect::back()->with('message', 'Berhasil Mensetujui Semua Yang Masih Ditinjau');
        }
        else{
            $request->validate([
                'for_bulan_edit' => 'required'
            ]);
            $user_id=auth()->user()->id;

            $budget = Planning::find($id);
            $budget->for_bulan = $request->for_bulan_edit;
            $budget->update();
            return redirect::back()->with('message', 'Berhasil Mengubah Data Perencanaan');
        }
        
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
            'jenis'=>'required',
            'akun_rincian'=>'required',
            'jumlah_anggaran_tambah_rincian'=>'required',
            'pj'=>'required',
            'target_kinerja'=>'required',
            'capaian_kinerja'=>'required',
            'target_waktu_pelaksanaan'=>'required',
            'capaian_target_waktu_penyelesaian'=>'required',
            'planning_id'=>'required',
        ]);
        // dd($request->akun_rincian);
        $nominal_rincian_int=$request->jumlah_anggaran_tambah_rincian;
        $nominal_rincian_int=str_replace('.','',$nominal_rincian_int);
        $find=Planning_detail::where('planning_id','=',$request->planning_id)->where('account_id','=',$request->akun_rincian)
                                ->where('group_rektorat','=',$request->group_rektorat)->get();
        // dd(!$find->isEmpty());
        // dd($find);
        if (!$find->isEmpty()){
            return redirect::back()->withErrors(['message' => 'Mata akun sudah terdaftar, silahkan cek ulang']);
        }
        Planning_detail::create([
            'planning_id'=>$request->planning_id,
            'account_id'=>$request->akun_rincian,
            'jenis'=>$request->jenis,
            'nominal'=>$nominal_rincian_int,
            'group_rektorat'=>$request->group_rektorat,
            'pj'=>$request->pj,
            'satuan_ukur_kinerja'=>$request->satuan_ukur_kinerja,
            'judul_file'=>$request->judul_file,
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
        if (!empty($request->persetujuan)){
            $request->validate([
                'persetujuan'=>'required',
                'jumlah'=>'required',
            ]);  

        $nominal_rincian_int=$request->jumlah;
        $nominal_rincian_int=str_replace('.','',$nominal_rincian_int);
            if($request->persetujuan==2){
                $nominal_rincian_int=0;      
            }
            if($request->persetujuan==3){
                $nominal_rincian_int=0;      
            }
        $updateRincian = Planning_detail::find($id);
        $updateRincian->approved_by_wr2 = $request->persetujuan;
        $updateRincian->nominal_disetujui = $nominal_rincian_int;
        $updateRincian->note_wr2 = $request->catatan_wrii;
        $updateRincian->update();
        }elseif (!empty($request->persetujuan_rektor)){
            $request->validate([
                'persetujuan_rektor'=>'required',
            ]);
            $nominal_rincian_int=$request->jumlah;
            $nominal_rincian_int=str_replace('.','',$nominal_rincian_int);
                if($request->persetujuan_rektor==2){
                    $nominal_rincian_int=0;
                }
                elseif($request->persetujuan_rektor==1){
                    $nominal_rincian_int=$request->jumlah;
                    if($request->jumlah==0){
                        $nominal_rincian_int=$request->jumlah_awal;
                    }
                } //jika wr2 setuju dan rektor tidak setuju, nominal disetujui==0, tapi jika wr2 setuju & rektor tidak, nominal disetujui kok 0
            $updateRincian = Planning_detail::find($id);
            $updateRincian->approved_by_rektor = $request->persetujuan_rektor;
            $updateRincian->note_rektor = $request->catatan_rektor;
            $updateRincian->nominal_disetujui = $nominal_rincian_int;
            $updateRincian->update();           
        }elseif (!empty($request->status)){
            $request->validate([
                'status'=>'required',
            ]);
            $updateRincian = Planning_detail::find($id);
            $updateRincian->status = $request->status;
            $updateRincian->note = $request->note;
            $updateRincian->update();           
        }else{
            $request->validate([
                'akun_rincian_edit'=>'required',
                'jumlah_anggaran_tambah_rincian_edit'=>'required',
                'pj'=>'required',
                'target_kinerja'=>'required',
                'capaian_kinerja'=>'required',
                'target_waktu_pelaksanaan'=>'required',
                'capaian_target_waktu_penyelesaian'=>'required',
                'jenis'=>'required',
            ]);
            // dd($request->current_account,$request->akun_rincian_edit);
            $nominal_rincian_int=$request->jumlah_anggaran_tambah_rincian_edit;
            $nominal_rincian_int=str_replace('.','',$nominal_rincian_int);
            $find=Planning_detail::where('planning_id','=',$request->planning_id)->where('account_id','=',$request->akun_rincian_edit)
                                    ->where('group_rektorat','=',$request->group_rektorat)->get();
            // dd(!$find->isEmpty());
            if (!$find->isEmpty()){
                if($request->current_account==$request->akun_rincian_edit){
                    $updateRincian = Planning_detail::find($id);
                    $updateRincian->account_id = $request->akun_rincian_edit;
                    $updateRincian->jenis = $request->jenis;
                    $updateRincian->nominal = $nominal_rincian_int;
                    $updateRincian->group_rektorat = $request->group_rektorat;
                    $updateRincian->pj = $request->pj;
                    $updateRincian->satuan_ukur_kinerja = $request->satuan_ukur_kinerja;
                    $updateRincian->judul_file = $request->judul_file;
                    $updateRincian->target_kinerja = $request->target_kinerja;
                    $updateRincian->capaian_kinerja = $request->capaian_kinerja;
                    $updateRincian->waktu_pelaksanaan = $request->target_waktu_pelaksanaan;
                    $updateRincian->capaian_target_waktu = $request->capaian_target_waktu_penyelesaian;
                    $updateRincian->update();
                    return redirect::back()->with('message', 'Berhasil mengubah data rincian');
                }
                return redirect::back()->withErrors(['message' => 'Mata akun sudah terdaftar, silahkan cek ulang']);
            }
            $updateRincian = Planning_detail::find($id);
            $updateRincian->account_id = $request->akun_rincian_edit;
            $updateRincian->jenis = $request->jenis;
            $updateRincian->nominal = $nominal_rincian_int;
            $updateRincian->group_rektorat = $request->group_rektorat;
            $updateRincian->pj = $request->pj;
            $updateRincian->satuan_ukur_kinerja = $request->satuan_ukur_kinerja;
            $updateRincian->judul_file = $request->judul_file;
            $updateRincian->target_kinerja = $request->target_kinerja;
            $updateRincian->capaian_kinerja = $request->capaian_kinerja;
            $updateRincian->waktu_pelaksanaan = $request->target_waktu_pelaksanaan;
            $updateRincian->capaian_target_waktu = $request->capaian_target_waktu_penyelesaian;
            $updateRincian->update();
        }
        return redirect::back()->with('message', 'Berhasil mengubah data rincian');
    }
}
