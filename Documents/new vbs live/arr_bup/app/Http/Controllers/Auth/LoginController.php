<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class LoginController extends Controller
{
    public function login()
    {
        // dd('asdf');
        if(Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            return view('admin.user.login');
        }
    }

    public function authenticate(Request $request)
{
    $credentials = $request->validate([
        'username' => ['required'],
        'password' => ['required']
    ]);

    try {
        // Manually check if the user exists
        $user = User::where('username', $credentials['username'])->first();

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        // Verify password
        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->with('error', 'Incorrect password.');
        }

        // Manually log in the user
        Auth::login($user);
        $request->session()->regenerate();

        // Store user info in session
        session([
            'user_id' => $user->id,
            'user_full_name' => $user->full_name,
            'user_department' => $user->department,
            'user_role' => $user->role,
            'user_active' => $user->active,
        ]);

        return redirect()->route('dashboard')->with('success', 'Welcome to VBS ' . $user->full_name);

    } catch (\Exception $e) {
        $message = '';

        switch ($e->getCode()) {
            case '08001':
                $message = 'Lost connection to server. Please try again later.';
                break;
            default:
                $message = 'An error occurred: ' . $e->getMessage();
                break;
        }

        return back()->with('error', $message);
    }

    return redirect()
            ->route('login')
            ->with('error', 'Login failed. Please check your credentials.')
            ->withInput($request->only('username'));
}


    public function logout(Request $request)
    {
        Auth::logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/');
    }
}
