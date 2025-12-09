<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StaffProfile;
use App\Models\ShiftSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{

    /**
     * Display a listing of staff members.
     */
    public function index(Request $request)
    {
        $query = User::with(['staffProfile'])
            ->whereIn('role', ['admin', 'caregiver', 'nurse', 'teacher'])
            ->where('is_active', true);

        // Apply filters
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        $staff = $query->orderBy('name')->paginate(15);

        $roles = ['admin' => 'Administrator', 'caregiver' => 'Caregiver', 'nurse' => 'Nurse', 'teacher' => 'Teacher'];

        return view('staff.index', compact('staff', 'roles'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        $roles = ['admin' => 'Administrator', 'caregiver' => 'Caregiver', 'nurse' => 'Nurse', 'teacher' => 'Teacher'];
        return view('staff.create', compact('roles'));
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,caregiver,nurse,teacher',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'employee_id' => 'required|string|max:50|unique:staff_profiles',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'date_hired' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'qualifications' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => bcrypt($validated['password']),
                'role' => $validated['role'],
                'address' => $validated['address'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'is_active' => true,
            ]);

            // Create staff profile
            StaffProfile::create([
                'user_id' => $user->id,
                'employee_id' => $validated['employee_id'],
                'department' => $validated['department'],
                'position' => $validated['position'],
                'date_hired' => $validated['date_hired'],
                'salary' => $validated['salary'],
                'emergency_contact_name' => $validated['emergency_contact_name'],
                'emergency_contact_phone' => $validated['emergency_contact_phone'],
                'qualifications' => $validated['qualifications'],
                'notes' => $validated['notes'],
            ]);

            DB::commit();

            return redirect()->route('staff.index')->with('success', 'Staff member created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating staff member: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create staff member. Please try again.');
        }
    }

    /**
     * Display the specified staff member.
     */
    public function show(User $staff)
    {
        $staff->load([
            'staffProfile',
            'shiftSchedules' => function ($query) {
                $query->orderBy('shift_date', 'desc')->limit(30);
            },
            'shiftSchedulesScheduled' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(10);
            }
        ]);

        return view('staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(User $staff)
    {
        $staff->load('staffProfile');
        $roles = ['admin' => 'Administrator', 'caregiver' => 'Caregiver', 'nurse' => 'Nurse', 'teacher' => 'Teacher'];
        return view('staff.edit', compact('staff', 'roles'));
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(Request $request, User $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $staff->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,caregiver,nurse,teacher',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'employee_id' => 'required|string|max:50|unique:staff_profiles,employee_id,' . ($staff->staffProfile->id ?? ''),
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'date_hired' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'qualifications' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Update user
            $staff->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'role' => $validated['role'],
                'address' => $validated['address'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'is_active' => $validated['is_active'],
            ]);

            // Update password if provided
            if (!empty($validated['password'])) {
                $staff->update(['password' => bcrypt($validated['password'])]);
            }

            // Update or create staff profile
            if ($staff->staffProfile) {
                $staff->staffProfile->update([
                    'employee_id' => $validated['employee_id'],
                    'department' => $validated['department'],
                    'position' => $validated['position'],
                    'date_hired' => $validated['date_hired'],
                    'salary' => $validated['salary'],
                    'emergency_contact_name' => $validated['emergency_contact_name'],
                    'emergency_contact_phone' => $validated['emergency_contact_phone'],
                    'qualifications' => $validated['qualifications'],
                    'notes' => $validated['notes'],
                ]);
            } else {
                StaffProfile::create([
                    'user_id' => $staff->id,
                    'employee_id' => $validated['employee_id'],
                    'department' => $validated['department'],
                    'position' => $validated['position'],
                    'date_hired' => $validated['date_hired'],
                    'salary' => $validated['salary'],
                    'emergency_contact_name' => $validated['emergency_contact_name'],
                    'emergency_contact_phone' => $validated['emergency_contact_phone'],
                    'qualifications' => $validated['qualifications'],
                    'notes' => $validated['notes'],
                ]);
            }

            DB::commit();

            return redirect()->route('staff.index')->with('success', 'Staff member updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating staff member: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update staff member. Please try again.');
        }
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(User $staff)
    {
        try {
            DB::beginTransaction();

            // Soft delete - deactivate user instead of deleting
            $staff->update(['is_active' => false]);

            DB::commit();

            return redirect()->route('staff.index')->with('success', 'Staff member deactivated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deactivating staff member: ' . $e->getMessage());
            return back()->with('error', 'Failed to deactivate staff member. Please try again.');
        }
    }

    /**
     * Schedule a shift for a staff member.
     */
    public function scheduleShift(Request $request, User $staff)
    {
        $user=Auth::user();
        $validated = $request->validate([
            'shift_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'shift_type' => 'required|in:morning,afternoon,night,full_day,part_time',
            'department' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        try {
            ShiftSchedule::create([
                'staff_id' => $staff->id,
                'scheduled_by' => $user->id,
                'shift_date' => $validated['shift_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'shift_type' => $validated['shift_type'],
                'department' => $validated['department'],
                'notes' => $validated['notes'],
                'status' => 'scheduled',
            ]);

            return back()->with('success', 'Shift scheduled successfully!');
        } catch (\Exception $e) {
            Log::error('Error scheduling shift: ' . $e->getMessage());
            return back()->with('error', 'Failed to schedule shift. Please try again.');
        }
    }

    /**
     * Show shifts for a specific staff member.
     */
    public function showShifts(Request $request, User $staff)
    {
        $query = $staff->shiftSchedules()
            ->with(['staff', 'scheduledBy'])
            ->orderBy('shift_date', 'desc');

        if ($request->has('month') && $request->month) {
            $query->whereMonth('shift_date', $request->month);
        }

        if ($request->has('year') && $request->year) {
            $query->whereYear('shift_date', $request->year);
        }

        $shifts = $query->paginate(20);

        return view('staff.shifts', compact('staff', 'shifts'));
    }

    /**
     * Update shift status.
     */
    public function updateShiftStatus(Request $request, ShiftSchedule $shift)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,in_progress,completed,absent,cancelled',
            'notes' => 'nullable|string',
        ]);

        try {
            $shift->update([
                'status' => $validated['status'],
                'notes' => $validated['notes'],
            ]);

            return back()->with('success', 'Shift status updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating shift status: ' . $e->getMessage());
            return back()->with('error', 'Failed to update shift status. Please try again.');
        }
    }
}