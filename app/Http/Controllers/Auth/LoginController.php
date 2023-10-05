<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function login(Request $request)
    {        
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $userStatus = Auth::User()->status;
            if($userStatus=='1') {
                return redirect()->route('home');
            }else{
                Auth::logout();
                Session::flush();
                return redirect()->route('login')->withInput()->with('message','Akun anda Tidak Aktif. Silahkan kontak admin');
            }
        }
        else {

            return redirect()->route('login')->withInput()->with('message','Email atau password salah. Silahkan coba lagi.');
        }
    }
}
