<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\GuruNew;
use App\Models\School;
use Illuminate\Http\Request;

class HRDashboardController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | DASHBOARD STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalTeachers = GuruNew::count();

        $activeTeachers = GuruNew::where(
            'current_status',
            'Active'
        )->count();

        $inactiveTeachers = GuruNew::where(
            'current_status',
            'Inactive'
        )->count();

        $completeTeachers = GuruNew::where(
            'current_status',
            'Complete'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | FILTER VALUES
        |--------------------------------------------------------------------------
        */

        $search = $request->search;
        $schoolID = $request->schoolID;
        $status = $request->status;
        $appointedDate = $request->appointed_date;


        /*
        |--------------------------------------------------------------------------
        | TEACHER LIST
        |--------------------------------------------------------------------------
        */

        $recentTeachers = GuruNew::with('school')

            // SEARCH
            ->when($search, function ($query, $search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'gn_name',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'gn_id',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'email',
                        'like',
                        '%' . $search . '%'
                    );

                });

            })


            // SCHOOL
            ->when($schoolID, function ($query, $schoolID) {

                $query->where(
                    'schoolID',
                    $schoolID
                );

            })


            // STATUS
            ->when($status, function ($query, $status) {

                $query->where(
                    'current_status',
                    $status
                );

            })


            // APPOINTED DATE
            ->when($appointedDate, function ($query, $appointedDate) {

                $query->whereDate(
                    'appointed_date',
                    $appointedDate
                );

            })


            ->orderBy(
                'appointed_date',
                'desc'
            )

            ->paginate(5)

            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | SCHOOL FILTER
        |--------------------------------------------------------------------------
        */

        $schools = School::orderBy(
            'school_name'
        )->get();


        return view(
            'hr.dashboard',
            compact(
                'totalTeachers',
                'activeTeachers',
                'inactiveTeachers',
                'completeTeachers',
                'recentTeachers',
                'schools'
            )
        );
    }
}