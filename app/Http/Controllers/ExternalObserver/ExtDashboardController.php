<?php

namespace App\Http\Controllers\ExternalObserver;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExtDashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();

        return view('external.dashboard', compact('teacher'));
    }
}