<?php

namespace App\Http\Controllers\Observer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Observer;
use App\Models\PreResponse;
use App\Models\PdpcResponse;
use App\Models\PostResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OBDashboardController extends Controller
{
    // Show observer dashboard
    public function index(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        $observer = Observer::where(
            'teacherID',
            $teacher->teacherID
        )->firstOrFail();


        $assignments = DB::table('observer_assignment')
            ->join(
                'guru_new',
                'observer_assignment.gn_id',
                '=',
                'guru_new.gn_id'
            )
            ->leftJoin(
                'school',
                'guru_new.schoolID',
                '=',
                'school.schoolID'
            )
            ->where(
                'observer_assignment.observer_id',
                $observer->observer_id
            )
            ->select(
                'observer_assignment.*',
                'guru_new.gn_name',
                'school.school_name'
            )
            ->orderByDesc(
                'observer_assignment.assigned_date'
            )
            ->get();


        foreach ($assignments as $assignment) {

            $gnId = $assignment->gn_id;


            $preResponse = PreResponse::where(
                'gn_id',
                $gnId
            )
                ->where(
                    'observation_stage',
                    'PRE'
                )
                ->where(
                    'observer_id',
                    $observer->observer_id
                )
                ->latest('responseID')
                ->first();


            $pdpcResponse = PdpcResponse::where(
                'gn_id',
                $gnId
            )
                ->where(
                    'observation_stage',
                    'POST'
                )
                ->where(
                    'observer_id',
                    $observer->observer_id
                )
                ->latest('responseID')
                ->first();


            $feedbackResponse = PostResponse::where(
                'gn_id',
                $gnId
            )
                ->where(
                    'observation_stage',
                    'POST'
                )
                ->where(
                    'observer_id',
                    $observer->observer_id
                )
                ->latest('responseID')
                ->first();


            $assignment->pre_status =
                $this->getStatus(
                    $preResponse
                );


            $assignment->pdpc_status =
                $this->getStatus(
                    $pdpcResponse
                );


            $assignment->feedback_status =
                $this->getStatus(
                    $feedbackResponse
                );


            $assignment->completed_count =
                ($assignment->pre_status === 'Completed' ? 1 : 0)
                +
                ($assignment->pdpc_status === 'Completed' ? 1 : 0)
                +
                ($assignment->feedback_status === 'Completed' ? 1 : 0);


            $assignment->total_forms = 3;


            $assignment->has_draft =
                $assignment->pre_status === 'Draft'
                ||
                $assignment->pdpc_status === 'Draft'
                ||
                $assignment->feedback_status === 'Draft';


            $assignment->is_completed =
                $assignment->completed_count === 3;


            $assignment->progress =
                round(
                    ($assignment->completed_count / 3) * 100
                );
        }


        $totalAssigned =
            $assignments->count();


        $completedCount =
            $assignments
            ->filter(
                fn($assignment) =>
                $assignment->is_completed
            )
            ->count();


        $ongoingCount =
            $assignments
            ->filter(
                fn($assignment) =>
                !$assignment->is_completed
            )
            ->count();


        $draftCount =
            $assignments
            ->filter(
                fn($assignment) =>
                $assignment->has_draft
            )
            ->count();


        $recentEvaluations =
            $assignments
            ->take(5);

        // PRE result filters
        $preSearch = trim((string) $request->input('pre_search', ''));
        $preMonth = $request->input('pre_month', 'all');
        $preYear = $request->input('pre_year', 'all');
        $preLevel = $request->input('pre_level', 'all');


        // Get available PRE years
        $preYears = DB::table('pre_response')
            ->where('observer_id', $observer->observer_id)
            ->where('observation_stage', 'PRE')
            ->where('status', 'Submitted')
            ->whereNotNull('observation_date')
            ->selectRaw('YEAR(observation_date) AS year')
            ->pluck('year')
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();


        // Build PRE observation result
        $preResultQuery = DB::table('pre_response')
            ->join(
                'guru_new',
                'pre_response.gn_id',
                '=',
                'guru_new.gn_id'
            )
            ->where(
                'pre_response.observer_id',
                $observer->observer_id
            )
            ->where(
                'pre_response.observation_stage',
                'PRE'
            )
            ->where(
                'pre_response.status',
                'Submitted'
            );


        // Search teacher
        if ($preSearch !== '') {

            $preResultQuery->where(
                'guru_new.gn_name',
                'like',
                "%{$preSearch}%"
            );
        }


        // Month filter
        if ($preMonth !== 'all') {

            $preResultQuery->whereMonth(
                'pre_response.observation_date',
                (int) $preMonth
            );
        }


        // Year filter
        if ($preYear !== 'all') {

            $preResultQuery->whereYear(
                'pre_response.observation_date',
                (int) $preYear
            );
        }


        // Achievement level filter
        if ($preLevel !== 'all') {

            $preResultQuery->where(
                'pre_response.achievement_level',
                $preLevel
            );
        }


        // PRE results
        $preResults = $preResultQuery
            ->select(
                'pre_response.responseID',
                'pre_response.observation_date',
                'pre_response.class_name',
                'pre_response.subject_name',
                'pre_response.total_score',
                'pre_response.percentage',
                'pre_response.achievement_level',
                'guru_new.gn_id',
                'guru_new.gn_name'
            )
            ->orderByDesc('pre_response.observation_date')
            ->orderByDesc('pre_response.responseID')
            ->paginate(
                10,
                ['*'],
                'pre_page'
            )
            ->withQueryString();


        return view(
            'observer.dashboard',
            compact(
                'totalAssigned',
                'ongoingCount',
                'draftCount',
                'completedCount',
                'recentEvaluations',
                'preResults',
                'preYears',
                'preSearch',
                'preMonth',
                'preYear',
                'preLevel'
            )
        );
    }


    private function getStatus($response): string
    {
        if (!$response) {
            return 'Pending';
        }

        if ($response->status === 'Draft') {
            return 'Draft';
        }

        if ($response->status === 'Submitted') {
            return 'Completed';
        }

        return 'Pending';
    }
}
