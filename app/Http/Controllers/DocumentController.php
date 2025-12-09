<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Models\Donor;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
 
    /**
     * Display a listing of donors.
     */
    public function index(Request $request)
    {
        $query = Donor::with(['managedBy'])
            ->withCount('donations');

        // Apply filters
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('organization', 'like', "%{$search}%");
            });
        }

        if ($request->has('type') && $request->type) {
            $query->where('donor_type', $request->type);
        }

        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $donors = $query->orderBy('name')->paginate(15);

        $types = ['individual' => 'Individual', 'organization' => 'Organization', 'corporate' => 'Corporate'];

        return view('donors.index', compact('donors', 'types'));
    }

    /**
     * Show the form for creating a new donor.
     */
    public function create()
    {
        $types = ['individual' => 'Individual', 'organization' => 'Organization', 'corporate' => 'Corporate'];
        return view('donors.create', compact('types'));
    }

    /**
     * Store a newly created donor in storage.
     */
    public function store(Request $request)
    {
        $user=Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'donor_type' => 'required|in:individual,organization,corporate',
            'organization' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:50',
            'preferred_contact_method' => 'nullable|in:email,phone,mail,all',
            'donation_frequency' => 'nullable|in:one_time,monthly,quarterly,annually,as_needed',
            'interests' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            Donor::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'donor_type' => $validated['donor_type'],
                'organization' => $validated['organization'],
                'tax_id' => $validated['tax_id'],
                'preferred_contact_method' => $validated['preferred_contact_method'],
                'donation_frequency' => $validated['donation_frequency'],
                'interests' => $validated['interests'],
                'notes' => $validated['notes'],
                'managed_by' => $user->id,
                'is_active' => true,
                'created_date' => now(),
            ]);

            DB::commit();

            return redirect()->route('donors.index')->with('success', 'Donor created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating donor: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create donor. Please try again.');
        }
    }

    /**
     * Display the specified donor.
     */
    public function show(Donor $donor)
    {
        $donor->load([
            'donations' => function ($query) {
                $query->orderBy('donation_date', 'desc')->limit(10);
            },
            'managedBy'
        ]);

        // Calculate statistics
        $totalDonations = $donor->donations->count();
        $totalAmount = $donor->donations->sum('amount');
        $thisYearDonations = $donor->donations->where('donation_date', '>=', now()->startOfYear())->count();
        $thisYearAmount = $donor->donations->where('donation_date', '>=', now()->startOfYear())->sum('amount');

        return view('donors.show', compact('donor', 'totalDonations', 'totalAmount', 'thisYearDonations', 'thisYearAmount'));
    }

    /**
     * Show the form for editing the specified donor.
     */
    public function edit(Donor $donor)
    {
        $types = ['individual' => 'Individual', 'organization' => 'Organization', 'corporate' => 'Corporate'];
        return view('donors.edit', compact('donor', 'types'));
    }

    /**
     * Update the specified donor in storage.
     */
    public function update(Request $request, Donor $donor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'donor_type' => 'required|in:individual,organization,corporate',
            'organization' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:50',
            'preferred_contact_method' => 'nullable|in:email,phone,mail,all',
            'donation_frequency' => 'nullable|in:one_time,monthly,quarterly,annually,as_needed',
            'interests' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            $donor->update($validated);

            DB::commit();

            return redirect()->route('donors.index')->with('success', 'Donor updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating donor: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update donor. Please try again.');
        }
    }

    /**
     * Remove the specified donor from storage.
     */
    public function destroy(Donor $donor)
    {
        try {
            DB::beginTransaction();

            // Soft delete - deactivate instead of deleting
            $donor->update(['is_active' => false]);

            DB::commit();

            return redirect()->route('donors.index')->with('success', 'Donor deactivated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deactivating donor: ' . $e->getMessage());
            return back()->with('error', 'Failed to deactivate donor. Please try again.');
        }
    }

    /**
     * Add a donation to a donor.
     */
    public function addDonation(Request $request, Donor $donor)
    {
        $user=Auth::user();
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'donation_date' => 'required|date',
            'donation_type' => 'required|in:cash,check,credit_card,bank_transfer,goods,other',
            'description' => 'nullable|string',
            'receipt_number' => 'nullable|string|max:100',
            'campaign' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            Donation::create([
                'donor_id' => $donor->id,
                'recorded_by' => $user->id,
                'amount' => $validated['amount'],
                'donation_date' => $validated['donation_date'],
                'donation_type' => $validated['donation_type'],
                'description' => $validated['description'],
                'receipt_number' => $validated['receipt_number'],
                'campaign' => $validated['campaign'],
                'notes' => $validated['notes'],
            ]);

            return back()->with('success', 'Donation recorded successfully!');
        } catch (\Exception $e) {
            Log::error('Error adding donation: ' . $e->getMessage());
            return back()->with('error', 'Failed to add donation. Please try again.');
        }
    }

    /**
     * Show donation history for a donor.
     */
    public function donationHistory(Request $request, Donor $donor)
    {
        $query = $donor->donations()
            ->with(['recordedBy'])
            ->orderBy('donation_date', 'desc');

        if ($request->has('year') && $request->year) {
            $query->whereYear('donation_date', $request->year);
        }

        if ($request->has('type') && $request->type) {
            $query->where('donation_type', $request->type);
        }

        $donations = $query->paginate(20);

        return view('donors.donation-history', compact('donor', 'donations'));
    }

    /**
     * Get donor statistics for API.
     */
    public function getStats()
    {
        $stats = [
            'total_donors' => Donor::count(),
            'active_donors' => Donor::where('is_active', true)->count(),
            'total_donations' => Donation::count(),
            'total_amount' => Donation::sum('amount'),
            'this_year_donations' => Donation::where('donation_date', '>=', now()->startOfYear())->count(),
            'this_year_amount' => Donation::where('donation_date', '>=', now()->startOfYear())->sum('amount'),
        ];

        return response()->json($stats);
    }

    /**
     * Search donors via API.
     */
    public function search(Request $request)
    {
        $query = Donor::where('is_active', true);

        if ($request->has('q') && $request->q) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('organization', 'like', "%{$search}%");
            });
        }

        $donors = $query->orderBy('name')->limit(10)->get();

        return response()->json($donors);
    }
}
