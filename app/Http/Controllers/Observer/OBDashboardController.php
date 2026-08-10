<?php

namespace App\Http\Controllers\Observer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OBDashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();

        return view('observer.dashboard', compact('teacher'));
    }
}