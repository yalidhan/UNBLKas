<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Departement;


class PenggunaController extends Controller
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
        //
        $pengguna=User::all();
        return view('pengguna',compact('pengguna'));
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
        $departement=Departement::all();
        $pengguna=User::find($id);
        // dd($departement);
        return view('pengguna_edit',compact('pengguna','departement'));
        
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $pengguna)
    {
        //
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'jabatan' => 'required',
            'departement_id' => 'required'
        ]);
        
        $pengguna->update([
            'name' => $request->name,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'departement_id' => $request->departement_id
        ]);
        return redirect()->route('pengguna.index')->with('message', 'Berhasil Merubah Data');
    }

    public function status($statuspengguna){
        $user=User::find($statuspengguna);
        // dd($user->status);
        if($user){
            if($user->status){
            $user->status = 0;
        }
        else{
            $user->status = 1;
        }
        $user->save();
        }
        return back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
