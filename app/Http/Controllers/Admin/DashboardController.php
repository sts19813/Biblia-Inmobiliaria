<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard.index', [
            'usersCount' => User::count(),
            'masterBrokersCount' => User::whereIn('role', ['asesor', 'lider_equipo'])->count(),
            'developersCount' => User::where('role', 'administrador')->count(),
        ]);
    }
}
