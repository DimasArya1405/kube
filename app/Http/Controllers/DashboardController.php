<?php

namespace App\Http\Controllers;
use App\Models\User;
class DashboardController extends Controller
{
    public function admin()
    {
        $users = User::all();
        return view('admin.dashboard.index', compact('users'));
    }
    
    public function ketua()
    {
        return view('ketua_kube.dashboard.index');
    }

    public function pendamping()
    {
        return view('pendamping.dashboard.index');
    }

    public function koordinator()
    {
        return view('koordinator.dashboard.index');
    }

    public function kepala_dinas()
    {
        return view('kepala_dinas.dashboard.index');
    }
}