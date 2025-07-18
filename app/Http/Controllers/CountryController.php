<?php

namespace App\Http\Controllers;

use App\Imports\CountryImport;
use App\Models\country;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $countries=country::all();
        return view('configuration.importcountries', compact('countries'));
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
    public function show(country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, country $country)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(country $country)
    {
        //
    }

    /**Import thelist of Countries */
    public function importcountries(Request $request)    
    {
       // dd($request);
    $request->validate([
        'countrylist' => 'required|mimes:xlsx,xls'
    ]);

    Excel::import(new CountryImport, $request->file('countrylist'));

    return back()->with('success', 'Excel file imported successfully.');
    }

}
