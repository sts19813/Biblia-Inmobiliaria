<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DeveloperProfileController extends Controller
{
    public function __invoke()
    {
        return view('admin.developer-profile.index');
    }
}
