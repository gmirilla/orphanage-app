<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ChildController extends Controller
{
   public function index(Request $request)
    {
        $query = Child::with(['admittedBy', 'currentRoomAssignment.roomAllocation.facility']);

        // Apply filters
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->has('admission_date_from')) {
            $query->whereDate('admission_date', '>=', $request->admission_date_from);
        }

        if ($request->has('admission_date_to')) {
            $query->whereDate('admission_date', '<=', $request->admission_date_to);
        }

        if ($request->has('status') && $request->status === 'active') {
            $query->where('is_active', true);
        }

        $children = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('children.index', compact('children'));
    }

    public function show(Child $child)
    {
        $child->load([
            'admittedBy',
            'educationHistories',
            'talentsInterests.recordedBy',
            'milestones.recordedBy',
            'currentRoomAssignment.roomAllocation.facility',
            'documents.uploadedBy'
        ]);

        return view('children.show', compact('child'));
    }

    public function create()
    {
        $staff = \App\Models\User::whereIn('role', ['admin', 'caregiver'])->get();
        return view('children.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $user=Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today',
            'background_summary' => 'required|string',
            'admission_date' => 'required|date|after:date_of_birth',
            'admission_source' => 'required|string',
            'guardianship_status' => 'nullable|string',
            'guardian_info' => 'nullable|string',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'height_cm' => 'nullable|numeric|min:0|max:300',
            'weight_kg' => 'nullable|numeric|min:0|max:200',
            'special_needs' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'admitted_by' => 'required|exists:users,id'
        ]);

        $data = $request->all();

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $filename = 'child_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('public/profile-photos', $filename);
            $data['profile_photo'] = str_replace('public/', '', $path);
        }

        $child = Child::create($data);

        // Create admission log
        $child->admissionLog()->create([
            'intake_record' => $request->background_summary,
            'source_details' => $request->admission_source,
            'supporting_documents' => [],
            'medical_history' => [],
            'social_history' => [],
            'processed_by' => $user->id
        ]);

        // Create initial milestone
        $child->recordMilestone('admission', 'Child Admitted', 
            "Child admitted on {$request->admission_date} from {$request->admission_source}");

        return redirect()->route('children.show', $child)
            ->with('success', 'Child profile created successfully.');
    }

    public function edit(Child $child)
    {
        $child->load('admissionLog');
        $staff = \App\Models\User::whereIn('role', ['admin', 'caregiver'])->get();
        return view('children.edit', compact('child', 'staff'));
    }

    public function update(Request $request, Child $child)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today',
            'background_summary' => 'required|string',
            'admission_date' => 'required|date|after:date_of_birth',
            'admission_source' => 'required|string',
            'guardianship_status' => 'nullable|string',
            'guardian_info' => 'nullable|string',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'height_cm' => 'nullable|numeric|min:0|max:300',
            'weight_kg' => 'nullable|numeric|min:0|max:200',
            'special_needs' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'admitted_by' => 'required|exists:users,id',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo
            if ($child->profile_photo) {
                Storage::delete('public/' . $child->profile_photo);
            }

            $photo = $request->file('profile_photo');
            $filename = 'child_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('public/profile-photos', $filename);
            $data['profile_photo'] = str_replace('public/', '', $path);
        }

        $child->update($data);

        return redirect()->route('children.show', $child)
            ->with('success', 'Child profile updated successfully.');
    }

    public function destroy(Child $child)
    {
        // Delete profile photo
        if ($child->profile_photo) {
            Storage::delete('public/' . $child->profile_photo);
        }

        $child->delete();

        return redirect()->route('children.index')
            ->with('success', 'Child profile deleted successfully.');
    }

    public function profile(Child $child)
    {
        $child->load([
            'admittedBy',
            'educationHistories',
            'talentsInterests.recordedBy',
            'milestones.recordedBy',
            'currentRoomAssignment.roomAllocation.facility',
            'documents.uploadedBy'
        ]);

        return view('children.profile', compact('child'));
    }

    public function assignTalent(Request $request, Child $child)
    {
        $request->validate([
            'category' => 'required|in:art,music,sports,academics,technical,social',
            'talent_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|in:beginner,intermediate,advanced'
        ]);

        $child->assignTalent(
            $request->category,
            $request->talent_name,
            $request->description,
            $request->level
        );

        return response()->json(['success' => 'Talent assigned successfully.']);
    }

    public function recordMilestone(Request $request, Child $child)
    {
        $request->validate([
            'type' => 'required|in:growth,achievement,medical,behavioral',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date_recorded' => 'required|date',
            'data' => 'nullable|array'
        ]);

        $child->recordMilestone(
            $request->type,
            $request->title,
            $request->description,
            $request->data
        );

        return response()->json(['success' => 'Milestone recorded successfully.']);
    }

    public function updateMeasurements(Request $request, Child $child)
    {
        $request->validate([
            'height_cm' => 'required|numeric|min:0|max:300',
            'weight_kg' => 'required|numeric|min:0|max:200'
        ]);

        $child->updatePhysicalMeasurements($request->height_cm, $request->weight_kg);

        return response()->json(['success' => 'Measurements updated successfully.']);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $children = Child::active()
            ->where('name', 'like', '%' . $query . '%')
            ->select('id', 'name', 'date_of_birth', 'gender')
            ->limit(10)
            ->get();

        return response()->json($children);
    }

    public function exportProfile(Child $child)
    {
        // Generate PDF profile
        $child->load([
            'admittedBy',
            'educationHistories',
            'talentsInterests.recordedBy',
            'milestones.recordedBy',
            'currentRoomAssignment.roomAllocation.facility'
        ]);

        //$pdf = \PDF::loadView('children.profile-pdf', compact('child'));
        $filename = "child_profile_{$child->id}_{$child->name}.pdf";
        
       // return $pdf->download($filename);
    }

    public function getStatistics()
    {
        $stats = [
            'total_children' => Child::active()->count(),
            'children_by_gender' => Child::active()
                ->selectRaw('gender, COUNT(*) as count')
                ->groupBy('gender')
                ->pluck('count', 'gender'),
            'children_by_age_group' => Child::active()
                ->selectRaw('
                    CASE 
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 0 AND 5 THEN "0-5 years"
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 6 AND 10 THEN "6-10 years"
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 11 AND 15 THEN "11-15 years"
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 16 AND 18 THEN "16-18 years"
                        ELSE "18+ years"
                    END as age_group,
                    COUNT(*) as count
                ')
                ->groupBy('age_group')
                ->pluck('count', 'age_group'),
            'recent_admissions' => Child::where('admission_date', '>=', now()->subMonths(6))->count(),
        ];

        return response()->json($stats);
    }
}
