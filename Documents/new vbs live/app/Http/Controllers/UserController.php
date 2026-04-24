<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
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
        $department = $request->department;
        $role = $request->role;
        $email = $request->email;
        $username = $request->username;
        $password = $request->password;
        $approver_id = $request->approver_id;
        
        $validator = Validator::make(
            [   // Values to validate
                'full_name' => $full_name,
                'department' => $department,
                'role' => $role,
                'email' => $email,
                'username' => $username,
                'password' => $password,
                'approver_id'=>$approver_id,
            ],
            [   // Validators
                'full_name' => 'required|string',
                'department' => 'required',
                'role' => 'required',
                'email' => 'nullable|email',
                'username' => 'required',
                'password' => 'required',
                'approver_id' => 'required',
            ],
            [   // Possible in the future, make custom rule for Tagalog alphabet that includes Ññ
                'full_name.required' => "Full name is required",
                'full_name.string' => "Full name must be a string",
                'department.required' => "Department is required",
                'role.required' => "Role is required",
                'email.email' => " Email must be in email format",
                'username.required' => "Username is required",
                'password.required' => "Password is required",
                'approver_id.required' => "approver is required",
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
            'active' => 1, // Default keep make user active after creating
            'approver_id'
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
    public function list(Request $request)
    {
        if ($request->ajax()) {
        $users = User::select([
            'id',
            'full_name', // change to fullname if needed
            'department',
            'username',
            'email',
            'active',
        ])->get();

        $data = $users->map(function ($row) {
            $editUrl = route('user.edit', $row->id);

            $toggleText = $row->active == 1 ? 'Inactive' : 'Activate';
            $toggleClass = $row->active == 1 ? 'btn-danger' : 'btn-success';

            return [
                'id' => $row->id,
                'full_name' => $row->full_name,
                'department' => $row->department,
                'username' => $row->username,
                'email' => $row->email,
                'status' => $row->active == 1
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>',
                'action' => '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-primary me-1">Edit</a>
                    <button type="button"
                            class="btn btn-sm ' . $toggleClass . ' toggle-active"
                            data-id="' . $row->id . '">
                        ' . $toggleText . '
                    </button>
                ',
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }

    return view('admin.user.users-list');
    }

    public function toggleActive($id)
    {
        $user = User::findOrFail($id);
        $user->active = $user->active == 1 ? 0 : 1;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully.'
        ]);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
        {
            $user = User::findOrFail($id);

            $selectedUsers = DB::connection('sqlsrv4')
                ->table('users')
                ->select('id', 'name', 'designation')
                ->whereIn('id', array_filter([
                    $user->approver_id,
                ]))
                ->get()
                ->keyBy('id');

            $approver = $selectedUsers->get($user->approver_id);


            return view('admin.user.edit-user', compact(
                'user',
                'approver',

            ));
        }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $data = [
        'full_name' => $request->full_name,
        'dept' => $request->department,
        'username' => $request->username,
        'email' => $request->email,
        'approver_id' => $request->approver_id,

    ];

    if (!empty($request->password)) {
        $data['password'] = bcrypt($request->password);
    }

    $user->update($data);

    return redirect()->route('user.list')->with('success', 'User updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
