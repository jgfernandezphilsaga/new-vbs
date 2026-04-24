<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\RequestHeader;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create-user');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $previous_id = User::max('id');
        $id = $previous_id ? $previous_id + 1 : 1;

        // dd($request);
        $full_name = $request->full_name;
        $department = $request->dept;
        $role = $request->role;
        $email = $request->email;
        $username = $request->username;
        $password = $request->password;
        
        $validator = Validator::make(
            [   // Values to validate
                'full_name' => $full_name,
                'department' => $department,
                'role' => $role,
                'email' => $email,
                'username' => $username,
                'password' => $password,
            ],
            [   // Validators
                'full_name' => 'required|string',
                'department' => 'required',
                'role' => 'required',
                'email' => 'nullable|email',
                'username' => 'required',
                'password' => 'required',
            ],
            [   // Possible in the future, make custom rule for Tagalog alphabet that includes Ññ
                'full_name.required' => "Full name is required",
                'full_name.string' => "Full name must be a string",
                'department.required' => "Department is required",
                'role.required' => "Role is required",
                'email.email' => " Email must be in email format",
                'username.required' => "Username is required",
                'password.required' => "Password is required",
            ]
        );

        // If Validation fails, send to check_fail function to handle error message content
        if($validator->fails())
        {
            $errors = $validator->errors();
            $message = $errors->all();
            $messageString = implode(' ', $message);
            
            return back()->with('error', $messageString)->withInput();
        }

        // $full_name = $last_name . ', ' . $first_name;

        // if($middle_name !== null)
        // {
        //     $full_name .= ' ' . $middle_name; // substr($middle_name, 0, 1); if only middle initial
        // }

        $full_name = strtoupper($full_name);

        User::create([
            'full_name' => $full_name,
            'department' => $department,
            'role' => $role,
            'email' => $email ?? null,
            'username' => $username,
            'password' => Hash::make($password),
            'active' => 1 // Default keep make user active after creating
        ]);

        return redirect()->route('dashboard')->with('success', 'Successfully created new user!');
    }

    public function fullname($id) 
    {
        $fullname = User::where('id', $id)->pluck('full_name')->first();

        return response()->json([
            'fullname' => $fullname
        ]);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
