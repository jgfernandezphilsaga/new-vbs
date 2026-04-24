<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view('admin.user.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials))
        {
            //dd('test');
            $request->session()->regenerate();

            $user = Auth::user();

            session([
                'user_id' => $user->id,
                'user_full_name' => $user->full_name,
                'user_department' => $user->department,
                'user_role' => $user->role,
                'user_active' => $user->active,
            ]);
            if(session('user_role') === 'dept_secretary')
            {
                return redirect()->route('dashboard');
            }
            elseif(session('user_role') === 'gsd_dispatcher')
            {
                return redirect()->route('dispatch');
            }
            
        }
        // dd('error');
        return redirect()
                ->route('login')
                ->with('error', 'The credentials do not match our records!')
                ->onlyInput('username');
    }
}
