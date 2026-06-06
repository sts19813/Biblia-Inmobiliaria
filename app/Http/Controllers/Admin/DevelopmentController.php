<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DevelopmentController extends Controller
{
    public function index()
    {
        return view('admin.developments.index');
    }
}
