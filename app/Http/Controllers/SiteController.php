<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;

class SiteController extends Controller
{
    public function index(){
        $sites = Site::with('account')->orderBy('created_at', 'desc')->paginate(10);
        return view('layouts.site.index',['sites' => $sites]);
    }

    public function create(Customer $customer){
        return view('layouts.site.create', ["customer" => $customer]);
    }

    public function update(Request $request, string $id){
        $validated = $request->validate([
            'account_id' => 'required|integer',
            'site_name' => 'required|string|max:255',
            'site_img' => 'nullable|image',
            'site_telephone' => 'required|string|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'postcode' => 'required|string|max:255',
            'lat' => 'nullable|string|max:255',
            'lng' => 'nullable|string|max:255',
            'current_temp' => 'nullable|string|max:255',
            'weather_icon' => 'nullable|string|max:255',
        ]);

        if($request->hasFile('site_img')) {
            $imagePath = $request->file('site_img')->store('upload', 'public');
            $validated['site_img'] = $imagePath;
        }
        Site::where('id',$id)->update($validated);      
        return redirect()->route('site.index')->with('success', 'Site Edited!');
    }

    public function show(Site $site){
        //route -> /ninjas/{$id}
        //$customer->load('site'); //to load in any relations

        //show/edit uses the same form for create
        return view('layouts.site.create', ["site" => $site]);
    }
    
    public function store(Request $request){
         $validated = $request->validate([
            'account_id' => 'required|integer',
            'site_name' => 'required|string|max:255',
            'site_img' => 'nullable|image',
            'site_telephone' => 'required|string|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'postcode' => 'required|string|max:255',
            'lat' => 'nullable|string|max:255',
            'lng' => 'nullable|string|max:255',
            'current_temp' => 'nullable|string|max:255',
            'weather_icon' => 'nullable|string|max:255',
        ]);
        if($request->hasFile('site_img')) {
            $imagePath = $request->file('site_img')->store('upload', 'public');
            $validated['site_img'] = $imagePath;
        }
        Site::create($validated);      
        return redirect()->route('site.index')->with('success', 'Site Created!');
    }

    
}
