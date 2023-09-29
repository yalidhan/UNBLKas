<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UpdatePasswordController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function edit()
    {
        return view('auth.gantipassword');
    }

    public function update(Request $request)
    {  
        $request->validate([
            'password_lama' => ['required'],
            'password' => ['required','min:8','confirmed'],
        ]);

        if (Hash::check($request->password_lama, auth()->user()->password)) {
            auth()->user()->update(['password'=>Hash::make($request->password)]);
           return back()->with('message','Password anda berhasil diganti');
        }
        throw ValidationException::withMessages([
            'password_lama' => 'Password Lama Salah',
        ]);
        
    }
}

