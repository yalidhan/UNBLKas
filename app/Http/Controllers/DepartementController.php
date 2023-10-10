<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departement;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $departement=Departement::get();
        return view('\departement/departement',['daftardepartement'=>$departement]);
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
            'nama'=>'required',
        ]);
        Departement::create([
            'nama'=>$request->nama,
        ]);
        return redirect()->route('departement.index')->with ('message','Berhasil menambahkan data Departemen');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, Departement $departement)
    {
        //
        $request->validate([
            'nama' => 'required',
        ]);
        $departement->update([
            'nama' => $request->nama
        ]);

        return redirect()->route('departement.index')->with('message', 'Berhasil Merubah Data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function status($status){
        $departement=Departement::find($status);  
        // dd($departement); 
        if($departement){
            if($departement->status){
            $departement->status = 0;
        }
        else{
            $departement->status = 1;
        }
        $departement->save();
        }
        return back();
    }
}
