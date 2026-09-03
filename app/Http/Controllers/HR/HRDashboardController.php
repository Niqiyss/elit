<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\GuruNew;

class HRDashboardController extends Controller
{
    public function index()
    {
        // Count all new teachers
        $totalTeachers = GuruNew::count();

        // Count active teachers
        $activeTeachers = GuruNew::where('current_status', 'Active')->count();

        // Count inactive teachers
        $inactiveTeachers = GuruNew::where('current_status', 'Inactive')->count();

        // Count completed teachers
        $completeTeachers = GuruNew::where('current_status', 'Complete')->count();

        // Get latest five registered teachers
        $recentTeachers = GuruNew::with('school')
            ->orderByDesc('appointed_date')
            ->take(5)
            ->get();

        // Show HR dashboard
        return view(
            'hr.dashboard',
            compact(
                'totalTeachers',
                'activeTeachers',
                'inactiveTeachers',
                'completeTeachers',
                'recentTeachers'
            )
        );
    }
}