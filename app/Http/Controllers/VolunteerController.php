<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VolunteerTask;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class VolunteerController extends Controller
{
  /**
     * Display a listing of volunteers.
     */
    public function index(Request $request)
    {
        $query = User::with(['volunteerProfile'])
            ->where('role', 'volunteer');

        // Apply filters
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $volunteers = $query->orderBy('name')->paginate(15);

        return view('volunteers.index', compact('volunteers'));
    }

    /**
     * Show the form for creating a new volunteer.
     */
    public function create()
    {
        return view('volunteers.create');
    }

    /**
     * Store a newly created volunteer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'volunteer_id' => 'required|string|max:50|unique:volunteers',
            'skills' => 'nullable|string',
            'availability' => 'nullable|in:weekends,weekdays,evenings,flexible',
            'interests' => 'nullable|string',
            'previous_experience' => 'nullable|string',
            'motivation' => 'nullable|string',
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
                'role' => 'volunteer',
                'address' => $validated['address'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'is_active' => true,
            ]);

            // Create volunteer profile
            Volunteer::create([
                'user_id' => $user->id,
                'volunteer_id' => $validated['volunteer_id'],
                'skills' => $validated['skills'],
                'availability' => $validated['availability'],
                'interests' => $validated['interests'],
                'previous_experience' => $validated['previous_experience'],
                'motivation' => $validated['motivation'],
                'notes' => $validated['notes'],
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('volunteers.index')->with('success', 'Volunteer created successfully! They will need to be approved before they can start volunteering.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating volunteer: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create volunteer. Please try again.');
        }
    }

    /**
     * Display the specified volunteer.
     */
    public function show(User $volunteer)
    {
        $volunteer->load([
            'volunteerProfile',
            'volunteerTasks' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(20);
            }
        ]);

        return view('volunteers.show', compact('volunteer'));
    }

    /**
     * Show the form for editing the specified volunteer.
     */
    public function edit(User $volunteer)
    {
        $volunteer->load('volunteerProfile');
        return view('volunteers.edit', compact('volunteer'));
    }

    /**
     * Update the specified volunteer in storage.
     */
    public function update(Request $request, User $volunteer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $volunteer->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'volunteer_id' => 'required|string|max:50|unique:volunteers,volunteer_id,' . ($volunteer->volunteerProfile->id ?? ''),
            'skills' => 'nullable|string',
            'availability' => 'nullable|in:weekends,weekdays,evenings,flexible',
            'interests' => 'nullable|string',
            'previous_experience' => 'nullable|string',
            'motivation' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Update user
            $volunteer->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'is_active' => $validated['is_active'],
            ]);

            // Update password if provided
            if (!empty($validated['password'])) {
                $volunteer->update(['password' => bcrypt($validated['password'])]);
            }

            // Update or create volunteer profile
            if ($volunteer->volunteerProfile) {
                $volunteer->volunteerProfile->update([
                    'volunteer_id' => $validated['volunteer_id'],
                    'skills' => $validated['skills'],
                    'availability' => $validated['availability'],
                    'interests' => $validated['interests'],
                    'previous_experience' => $validated['previous_experience'],
                    'motivation' => $validated['motivation'],
                    'notes' => $validated['notes'],
                ]);
            } else {
                Volunteer::create([
                    'user_id' => $volunteer->id,
                    'volunteer_id' => $validated['volunteer_id'],
                    'skills' => $validated['skills'],
                    'availability' => $validated['availability'],
                    'interests' => $validated['interests'],
                    'previous_experience' => $validated['previous_experience'],
                    'motivation' => $validated['motivation'],
                    'notes' => $validated['notes'],
                    'status' => 'pending',
                ]);
            }

            DB::commit();

            return redirect()->route('volunteers.index')->with('success', 'Volunteer updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating volunteer: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update volunteer. Please try again.');
        }
    }

    /**
     * Remove the specified volunteer from storage.
     */
    public function destroy(User $volunteer)
    {
        try {
            DB::beginTransaction();

            // Soft delete - deactivate user instead of deleting
            $volunteer->update(['is_active' => false]);

            DB::commit();

            return redirect()->route('volunteers.index')->with('success', 'Volunteer deactivated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deactivating volunteer: ' . $e->getMessage());
            return back()->with('error', 'Failed to deactivate volunteer. Please try again.');
        }
    }

    /**
     * Approve a volunteer.
     */
    public function approve(Request $request, User $volunteer)
    {
        try {
            if ($volunteer->volunteerProfile) {
                $volunteer->volunteerProfile->update(['status' => 'approved']);
            }

            return back()->with('success', 'Volunteer approved successfully!');
        } catch (\Exception $e) {
            Log::error('Error approving volunteer: ' . $e->getMessage());
            return back()->with('error', 'Failed to approve volunteer. Please try again.');
        }
    }

    /**
     * Assign a task to a volunteer.
     */
    public function assignTask(Request $request, User $volunteer)
    {
        $user=Auth::user();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        try {
            VolunteerTask::create([
                'volunteer_id' => $volunteer->id,
                'assigned_by' => $user->id,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'due_date' => $validated['due_date'],
                'priority' => $validated['priority'],
                'status' => 'assigned',
            ]);

            return back()->with('success', 'Task assigned to volunteer successfully!');
        } catch (\Exception $e) {
            Log::error('Error assigning task: ' . $e->getMessage());
            return back()->with('error', 'Failed to assign task. Please try again.');
        }
    }

    /**
     * Search volunteers via API.
     */
    public function search(Request $request)
    {
        $query = User::with(['volunteerProfile'])
            ->where('role', 'volunteer')
            ->where('is_active', true);

        if ($request->has('q') && $request->q) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $volunteers = $query->orderBy('name')->limit(10)->get();

        return response()->json($volunteers);
    }
}
