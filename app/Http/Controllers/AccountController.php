<?php

namespace App\Http\Controllers;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AccountController extends Controller
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
        if (auth()->user()->departement_id !=1){
            return redirect('/');
        }

        $account=Account::get();
        return view('\account/account',['accountlist'=>$account]);
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
            'kode_akun'=>'required',
        ]);
        $kode_akun=$request->kode_akun;
        if($kode_akun=='01'){
            $tipe_akun='Harta';
        }
        elseif ($kode_akun=='02'){
            $tipe_akun='Utang';
        }
        elseif ($kode_akun=='03'){
            $tipe_akun='Modal';
        }
        elseif ($kode_akun=='04'){
            $tipe_akun='Pendapatan';
        }
        elseif ($kode_akun=='05'){
            $tipe_akun='Beban';
        }
        else{
            $tipe_akun='Tidak Terdaftar';
        }

        $last_data=DB::table('accounts')
                ->where('no','like',"$kode_akun%")
                ->orderBy('created_at','desc')
                ->first();
        if($last_data){
            $no_urut_str=substr($last_data->no,3);
            $no_urut_int=(int)$no_urut_str+1;
            $no_urut_str_pad=str_pad($no_urut_int,2,"0",STR_PAD_LEFT);
            $no_akun="$kode_akun.$no_urut_str_pad";
            // dd($no_akun);
        }
        else{
            $no_akun="$kode_akun.01";
            // dd("$no_urut");
        };
        
        Account::create([
        'nama'=>$request->nama,
        'no'=>$no_akun,
        'tipe'=>$tipe_akun,
        'kelompok'=>$request->kelompok,
        ]);
        return redirect()->route('akun.index')->with ('message','Berhasil menambahkan data Akun');
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
        if (auth()->user()->departement_id !=1){
            return redirect('/');
        }
        $akun=Account::find($id);
        // dd($departement);
        return view('/account/account_edit',compact('akun'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $akun)
    {
        //
        $request->validate([
            'nama' => 'required',
        ]);
        $akun->update([
            'nama' => $request->nama
        ]);

        return redirect()->route('akun.index')->with('message', 'Berhasil Merubah Data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function status($status){
        $account=account::find($status);  
        // dd($account); 
        if($account){
            if($account->status){
            $account->status = 0;
        }
        else{
            $account->status = 1;
        }
        $account->save();
        }
        return back();
    }
}
