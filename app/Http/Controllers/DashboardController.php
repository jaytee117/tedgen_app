<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    public function index(){
        Permission::create(['name' => 'edit customers']);
        return view('dashboard');
    }
}
