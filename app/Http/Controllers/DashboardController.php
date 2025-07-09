<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Installation;
use App\Actions\InstallationAction;

class DashboardController extends Controller
{
    public function index(){

        $stats = InstallationAction::getDashboardStats();
        $installations = Installation::with('site')->orderBy('id', 'asc')->paginate(8);
        return view('dashboard', ['installations' => $installations, 'stats' => $stats]);
    }
}
