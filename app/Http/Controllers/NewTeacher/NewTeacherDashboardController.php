<?php

namespace App\Http\Controllers\NewTeacher;

use App\Http\Controllers\Controller;

class NewTeacherDashboardController extends Controller
{
    public function index()
    {
        return view('newteacher.dashboard');
    }
}