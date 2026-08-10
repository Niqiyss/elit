<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;

class PrincipalDashboardController extends Controller
{
    public function index()
    {
        return view('principal.dashboard');
    }
}