<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $staffs = Staff::all();

        return view('Staff.liststaff', compact('staffs'));
    }

    public function registernew()
    {
        //

        return view('Staff.newstaff');
    }

        public function viewstaff(Request $request)
    {
        //
        $staff=Staff::find($request->id);

        return view('Staff.viewstaff', compact('staff'));
    }

    public function savestaff(Request $request)
    {
        // Validate user input
        $request->validate([
            'fname' => 'required|string',
            'lname' => 'required|string',
            'email' => 'required|email|unique:users,email',
            // Add other rules as needed
        ]);

        // Create user account
        $name = $request->fname . ' ';
        if (!empty($request->mname)) {
            $name .= $request->mname . ' ';
        }
        $name .= $request->lname;
        $name = trim($name);

        $email = $request->email;
        $password = Hash::make('password123');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        // Build staff input
        $staffData = $request->all();
        $staffData['userid'] = $user->id;
        $staffData['staffnotes'] = $staffData['staffnotes'] ?? null; // Ensure nullable field is handled

        //profilepicture do not override if empty
        if (!empty($request->profilepicture)) {
            # code...
            $request->validate(['profilepicture' => 'image|max:4096',]);
            $filename = uniqid() . '.' . $request->file('profilepicture')->extension();
            $fpath = $request->file('profilepicture')->storeAs('profilepictures/staff', $filename, 'public');
            $staffData['profilephoto'] = $fpath;
        }

        $staff = Staff::create($staffData);


        return redirect()->route('list_staff');
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
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        //
    }
}
