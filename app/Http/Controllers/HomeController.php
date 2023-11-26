<?php

namespace App\Http\Controllers;

use App\Models\TimeSheet;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function login()
    {
        return view('auth.login');
    }

    public function loginsubmit(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        $active_user = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'active' => 1
        ]);
        $inactive_user = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'active' => 0
        ]);

        if ($active_user) {
            Toastr::success('Login success', 'Success');
            return redirect()->route('home');
        }
        elseif ($inactive_user) {
            Auth::logout();
            Toastr::error('User is not active', 'Failed');
            return redirect()->route('login.form');
        }
        else {
            Toastr::error('Incorrect email or password', 'Failed');
            return redirect()->route('login.form');
        }
    }

    public function logout()
    {
        Auth::logout();
        Toastr::success('Logout Successfull', 'Success');
        return redirect()->route('login.form');
    }

    public function index()
    {
        if (Auth::user()->role == 'admin') {
        $users = User::where('role','supervisor')->latest()->take(5)->get();
        return view('index', compact('users'));
        }
        elseif (Auth::user()->role == 'supervisor') {
            $entries = TimeSheet::where('entered_by',Auth::id())->take(5)->get();
            return view('index', compact('entries'));
        }
    }

    public function password()
    {
        return view('auth.password');
    }

    public function password_update(Request $request, $id)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
        ]);

        $user = User::where('id',$id)->first();
        $user->password = Hash::make($request->password);
        $result = $user->save();

        if ($result) {
            Toastr::success('success', 'password changed');
            return redirect()->route('home');
        }
        else {
            Toastr::error('failed', 'password change failed');
            return redirect()->route('home');
        }
    }

}
