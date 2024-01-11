<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Departement;
use App\Models\Budget;
use App\Models\Account;
use App\Models\Budget_detail;
use Illuminate\Support\Facades\DB;
use Redirect;

class BudgetController extends Controller
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
        if (auth()->user()->departement_id==1 ){
            $budgets=Budget::all();
        }elseif(auth()->user()->departement_id==6){
            $budgets=Budget::where('departement_id','!=','1')->get();
        }
        else{
            $budgets=Budget::where('departement_id','=',auth()->user()->departement_id)->get();
        }
        
        $departements=Departement::where('status','=','1')->get();
        return view('budget/budget',compact('budgets','departements'));
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
            'tahun_anggaran'=>'required|numeric',
        ]);
        $user_id=auth()->user()->id;
        if ($request->departement==""){
            $departement_id=auth()->user()->departement_id;
        }else{
            $departement_id=$request->departement;
        }
        Budget::create([
            'tahun'=>$request->tahun_anggaran,
            'departement_id'=>$departement_id,
            'user_id'=>$user_id,
        ]);
        
        return redirect()->route('anggaran.index')->withErrors(['message' => ''])->with('message','Berhasil menambahkan data anggaran');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        \DB::statement("SET SQL_MODE=''");
        $showBudget=DB::select(
            "SELECT b.id, b.tahun, b.departement_id, b.user_id,
                dp.nama AS departement,sum(bd.nominal) AS total_anggaran
            FROM budgets b
            LEFT JOIN budget_details bd
            ON b.id = bd.budget_id
            LEFT JOIN departements dp
            ON b.departement_id = dp.id
            WHERE budget_id=$id"
        );
        // dd($showBudget);
        // $showBudget=Budget::find($id);
        // dd($showBudget);
        $showDetailBudget=DB::select(
            "SELECT bd.nominal, bd.id, bd.keterangan, a.nama,a.id as account_id, a.tipe, a.no, a.kelompok
            FROM budget_details bd
            LEFT JOIN accounts a
            ON bd.account_id = a.id
            WHERE budget_id = $id;"
        );
        // dd($showDetailBudget);
        $accountList=Account::where('status','=','1')->orderBy('no', 'ASC')->get();
        // dd($accountList);
        $departements=Departement::where('status','=','1')->get();
        return view('budget/rincian_budget',compact('showBudget','showDetailBudget','accountList','departements'));
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

        $request->validate([
            'tahun_anggaran_edit' => 'required|numeric'
        ]);
        $user_id=auth()->user()->id;
        if ($request->departement_edit==""){
            $departement_id=auth()->user()->departement_id;
        }else{
            $departement_id=$request->departement_edit;
        }
        
        $budget = Budget::find($id);
        $budget->tahun = $request->tahun_anggaran_edit;
        $budget->departement_id =$departement_id;
        $budget->update();

        return redirect::back()->with('message', 'Berhasil Mengubah Data Transaksi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Budget::where('id',$id)->delete();
        return redirect()->route('anggaran.index')->withErrors(['message' => ''])->with('message','Berhasil menghapus data anggaran');
    }

    public function storeRincianA(Request $request)
    {
        $request->validate([
            'akun_rincian'=>'required',
            'nominal_tambah_rincian'=>'required',
            'budget_id'=>'required',
        ]);
        // dd($request->akun_rincian);
        $nominal_rincian_int=$request->nominal_tambah_rincian;
        $nominal_rincian_int=str_replace('.','',$nominal_rincian_int);
        $find=Budget_detail::where('budget_id','=',$request->budget_id)->where('account_id','=',$request->akun_rincian)->get();
        // dd(!$find->isEmpty());
        if (!$find->isEmpty()){
            return redirect::back()->withErrors(['message' => 'Mata akun sudah terdaftar, silahkan cek ulang']);
        }
        Budget_detail::create([
            'budget_id'=>$request->budget_id,
            'account_id'=>$request->akun_rincian,
            'nominal'=>$nominal_rincian_int,
            'keterangan'=>$request->keterangan_rincian,
        ]);
        return redirect::back()->with ('message','Berhasil menmbah rincian budget');
    }

    public function destroyRincianA(string $id)
    {
       Budget_detail::where('id',$id)->delete();
        return redirect::back()->withErrors(['message' => ''])->with('message','Berhasil menghapus rincian anggaran');
    }

    public function updateRincianA(Request $request,$id)
    {
        $request->validate([
            'akun_rincian_edit' => 'required',
            'nominal_tambah_rincian_edit' => 'required'
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
