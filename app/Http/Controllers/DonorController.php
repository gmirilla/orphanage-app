<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DonorController extends Controller
{
    public function index(Request $request)
    {
        $query = Donor::with('managedBy')->withCount('donations');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('donor_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $donors = $query->orderBy('name')->paginate(15);
        $types = ['individual' => 'Individual', 'organization' => 'Organization', 'corporate' => 'Corporate'];

        return view('donors.index', compact('donors', 'types'));
    }

    public function create()
    {
        $types = ['individual' => 'Individual', 'organization' => 'Organization', 'corporate' => 'Corporate'];
        return view('donors.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string',
            'donor_type' => 'required|in:individual,organization,corporate',
            'tax_id'     => 'nullable|string|max:50',
            'preferences'=> 'nullable|string',
            'status'     => 'nullable|in:active,inactive,preferred',
        ]);

        try {
            DB::beginTransaction();

            Donor::create([
                ...$validated,
                'status'     => $validated['status'] ?? 'active',
                'managed_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('donors.index')->with('success', 'Donor created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating donor: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create donor. Please try again.');
        }
    }

    public function show(Donor $donor)
    {
        $donor->load(['managedBy', 'donations' => fn($q) => $q->orderBy('donation_date', 'desc')->limit(10)]);

        $totalDonations   = $donor->donations()->count();
        $totalAmount      = $donor->getTotalDonationsAllTime();
        $thisYearDonations = $donor->getDonationCountThisYear();
        $thisYearAmount   = $donor->getTotalDonationsThisYear();

        return view('donors.show', compact('donor', 'totalDonations', 'totalAmount', 'thisYearDonations', 'thisYearAmount'));
    }

    public function edit(Donor $donor)
    {
        $types = ['individual' => 'Individual', 'organization' => 'Organization', 'corporate' => 'Corporate'];
        return view('donors.edit', compact('donor', 'types'));
    }

    public function update(Request $request, Donor $donor)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string',
            'donor_type' => 'required|in:individual,organization,corporate',
            'tax_id'     => 'nullable|string|max:50',
            'preferences'=> 'nullable|string',
            'status'     => 'required|in:active,inactive,preferred',
        ]);

        try {
            DB::beginTransaction();
            $donor->update($validated);
            DB::commit();

            return redirect()->route('donors.show', $donor)->with('success', 'Donor updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating donor: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update donor. Please try again.');
        }
    }

    public function destroy(Donor $donor)
    {
        try {
            DB::beginTransaction();
            $donor->update(['status' => 'inactive']);
            DB::commit();

            return redirect()->route('donors.index')->with('success', 'Donor deactivated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deactivating donor: ' . $e->getMessage());
            return back()->with('error', 'Failed to deactivate donor. Please try again.');
        }
    }

    public function addDonation(Request $request, Donor $donor)
    {
        $validated = $request->validate([
            'donation_type' => 'required|in:cash,material,service',
            'amount'        => 'nullable|numeric|min:0',
            'description'   => 'nullable|string',
            'donation_date' => 'required|date',
            'status'        => 'nullable|in:pledged,received,cancelled',
            'notes'         => 'nullable|string',
        ]);

        try {
            $receiptNumber = 'RCP-' . strtoupper(uniqid());
            Donation::create([
                'donor_id'      => $donor->id,
                'recorded_by'   => Auth::id(),
                'receipt_number'=> $receiptNumber,
                'status'        => $validated['status'] ?? 'received',
                ...$validated,
            ]);

            return back()->with('success', 'Donation recorded successfully.');
        } catch (\Exception $e) {
            Log::error('Error adding donation: ' . $e->getMessage());
            return back()->with('error', 'Failed to add donation. Please try again.');
        }
    }

    public function donationHistory(Request $request, Donor $donor)
    {
        $query = $donor->donations()->with('recordedBy')->orderBy('donation_date', 'desc');

        if ($request->filled('year')) {
            $query->whereYear('donation_date', $request->year);
        }

        if ($request->filled('type')) {
            $query->where('donation_type', $request->type);
        }

        $donations = $query->paginate(20);

        return view('donors.donation-history', compact('donor', 'donations'));
    }

    public function getStats()
    {
        $stats = [
            'total_donors'       => Donor::count(),
            'active_donors'      => Donor::where('status', 'active')->count(),
            'total_donations'    => Donation::count(),
            'total_amount'       => Donation::where('status', 'received')->sum('amount'),
            'this_year_donations'=> Donation::whereYear('donation_date', now()->year)->count(),
            'this_year_amount'   => Donation::where('status', 'received')->whereYear('donation_date', now()->year)->sum('amount'),
        ];

        return response()->json($stats);
    }

    public function search(Request $request)
    {
        $query = Donor::where('status', '!=', 'inactive');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return response()->json($query->orderBy('name')->limit(10)->get());
    }
}
