<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use App\Models\Departement;
use App\Models\Budget;
use Illuminate\Http\Request;

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
            or auth()->user()->jabatan=="Super Admin"){
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
    public function show(Planning $planning)
    {
        //
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
    public function update(Request $request, Planning $planning)
    {
        //
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
