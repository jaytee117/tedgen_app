<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataLine;

class DatalineController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(DataLine $dataline)

    {
        return view('layouts.installation.datalines.update', ["dataline" => $dataline]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $valid = $this->validateFields($request);
        DataLine::where('id', $id)->update($valid);
        return redirect()->route('dataline.show', $id)->with('success', 'Dataline Edited!');
    }

    private function validateFields(Request $request)
    {
        $validated = $request->validate([
            'installation_id' => 'required|integer',
            'data_line_type' => 'integer',
            'line_reference' => 'required|string|max:255',
            'x420_line_assignment' => 'integer',
            'xero_account_code' => 'nullable|string|max:255',            
        ]);
        return $validated;
    }
}
