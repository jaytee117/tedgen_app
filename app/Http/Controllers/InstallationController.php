<?php

namespace App\Http\Controllers;

use App\Models\Installation;
use Illuminate\Http\Request;
use App\Models\Site;
use App\Actions\InstallationAction;

class InstallationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $installations = Installation::with('site')->orderBy('created_at', 'desc')->paginate(10);
        return view('layouts.installation.index', ['installations' => $installations]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Site $site)
    {
        return view('layouts.installation.create', ['site' => $site]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valid = $this->validateFields($request);
        $validated = InstallationAction::storeNewRates($valid);
        $installation = Installation::create($validated);
        InstallationAction::createDataLines($installation);
        InstallationAction::createLastCounts($installation);
        return redirect()->route('installation.show', ['installation' => $installation])->with('success', 'Installation Created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Installation $installation)

    {
        $installation->load('datalines'); //to load in any relations    
        return view('layouts.installation.create', ["installation" => $installation]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $valid = $this->validateFields($request);
        $validated = InstallationAction::storeNewRates($valid);
        Installation::where('id', $id)->update($validated);
        return redirect()->route('installation.show', $id)->with('success', 'Installation Edited!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Installation $installation)
    {
        $inst = $installation;
        $installation->delete();
        return redirect()->route('site.show', $inst->site_id)->with('success', 'Installation Deleted!');
    }

    private function validateFields(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|integer',
            'site_id' => 'required|integer',
            'asset_id' => 'required|string|max:255',
            'machine_status' => 'required|integer',
            'machine_type' => 'required|integer',
            'logger_type' => 'required|integer',
            'ip_address' => 'nullable|string|max:255',
            'xero_id' => 'nullable|string|max:255',
            'elec_day_rate' => 'nullable|string|max:255',
            'elec_night_rate' => 'nullable|string|max:255',
            'gas_rate' => 'nullable|string|max:255',
            'elec_ccl_rate' => 'nullable|string|max:255',
            'gas_ccl_rate' => 'nullable|string|max:255',
            'elec_ccl_discount' => 'nullable|string|max:255',
            'gas_ccl_discount' => 'nullable|string|max:255',
            'boiler_efficiency' => 'nullable|string|max:255',
            'tedgen_discount' => 'nullable|string|max:255',
            'calorific_value' => 'nullable|string|max:255',
            'conversion_factor' => 'nullable|string|max:255',
            'elec_carbon_rate' => 'nullable|string|max:255',
            'gas_carbon_rate' => 'nullable|string|max:255',
            'tedgen_elec_day' => 'nullable|string|max:255',
            'tedgen_elec_night' => 'nullable|string|max:255',
            'tedgen_gas_heating' => 'nullable|string|max:255',
        ]);
        return $validated;
    }
}
