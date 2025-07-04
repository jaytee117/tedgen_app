<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Installation;

class DashboardController extends Controller
{
    public function index(){

        $installations = Installation::with('site')->orderBy('id', 'asc')->paginate(8);
        return view('dashboard', ['installations' => $installations]);
    }
}
