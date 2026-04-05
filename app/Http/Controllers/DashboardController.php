<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return view('dashboard.ketua');
    }

    public function pendamping()
    {
        return view('dashboard.pendamping');
    }

    public function koordinator()
    {
        return view('dashboard.koordinator');
    }

    public function tim()
    {
        return view('dashboard.tim');
    }

    public function dinas()
    {
        return view('dashboard.dinas');
    }
}