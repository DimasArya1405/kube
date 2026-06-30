<?php

namespace App\Http\Controllers;

class TemplateLaporanController extends Controller
{
    public function index()
    {
        return view('admin.template_laporan.index');
    }
}