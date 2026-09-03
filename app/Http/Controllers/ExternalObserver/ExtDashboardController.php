<?php

namespace App\Http\Controllers\ExternalObserver;

use App\Http\Controllers\Controller;
use App\Models\ExternalObserver;
use App\Models\PdpcResponse;
use App\Models\PostResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExtDashboardController extends Controller
{
    // Show external observer dashboard.
    public function index(Request $request)
    {
        // Get logged-in teacher.
        $teacher = Auth::guard('teacher')->user();

        abort_if(
            !$teacher,
            403,
            'Unauthorized access.'
        );

        // Get external observer profile.
        $externalObserver = ExternalObserver::where(
            'teacherID',
            $teacher->teacherID
        )->firstOrFail();


        // Get dashboard filter values.
        $search = trim(
            (string) $request->input(
                'search',
                ''
            )
        );

        $month = $request->input(
            'month',
            'all'
        );

        $year = $request->input(
            'year',
            'all'
        );

        $level = $request->input(
            'level',
            'all'
        );


        // Get teacher assignments.
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
                'observer_assignment.external_observer_id',
                $externalObserver->external_observer_id
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


        // Attach current evaluation information.
        foreach ($assignments as $assignment) {

            $gnId = $assignment->gn_id;


            // Get current observer PDPC draft.
            $pdpcDraft = PdpcResponse::where(
                'gn_id',
                $gnId
            )
                ->where(
                    'observation_stage',
                    'EXTERNAL'
                )
                ->where(
                    'external_observer_id',
                    $externalObserver->external_observer_id
                )
                ->where(
                    'status',
                    'Draft'
                )
                ->latest('responseID')
                ->first();


            // Get latest submitted PDPC globally for teacher.
            $latestPdpc = PdpcResponse::where(
                'gn_id',
                $gnId
            )
                ->where(
                    'observation_stage',
                    'EXTERNAL'
                )
                ->where(
                    'status',
                    'Submitted'
                )
                ->orderByDesc('attempt_no')
                ->orderByDesc('responseID')
                ->first();


            // Determine PDPC form status.
            if ($pdpcDraft) {

                $assignment->pdpc_status =
                    'Draft';
            } elseif ($latestPdpc) {

                $assignment->pdpc_status =
                    'Completed';
            } else {

                $assignment->pdpc_status =
                    'Pending';
            }


            // Get current observer latest feedback.
            $feedbackResponse = PostResponse::where(
                'gn_id',
                $gnId
            )
                ->where(
                    'observation_stage',
                    'EXTERNAL'
                )
                ->where(
                    'external_observer_id',
                    $externalObserver->external_observer_id
                )
                ->latest('responseID')
                ->first();


            // Determine feedback status.
            $assignment->feedback_status =
                $this->getStatus(
                    $feedbackResponse
                );


            // Determine latest result.
            $assignment->result = null;

            if ($latestPdpc) {

                $assignment->result =
                    $latestPdpc->result
                    ??
                    (
                        (float) $latestPdpc->percentage >= 85
                        ? 'PASS'
                        : 'REPEAT'
                    );
            }


            // Determine repeat requirement.
            $assignment->is_repeat =
                $latestPdpc
                &&
                $assignment->result === 'REPEAT';


            // Calculate completed forms.
            $assignment->completed_count =
                (
                    $assignment->pdpc_status
                    === 'Completed'
                    ? 1
                    : 0
                )
                +
                (
                    $assignment->feedback_status
                    === 'Completed'
                    ? 1
                    : 0
                );


            // External stage requires two forms.
            $assignment->total_forms = 2;


            // Check whether any current form is draft.
            $assignment->has_draft =
                $assignment->pdpc_status === 'Draft'
                ||
                $assignment->feedback_status === 'Draft';


            // Determine completed evaluation.
            $assignment->is_completed =
                $assignment->completed_count === 2
                &&
                $latestPdpc
                &&
                $assignment->result === 'PASS';


            // Calculate submission progress.
            $assignment->progress =
                round(
                    (
                        $assignment->completed_count
                        /
                        $assignment->total_forms
                    )
                        * 100
                );
        }


        // Count assigned teachers.
        $totalAssigned =
            $assignments->count();


        // Count completed evaluations.
        $completedCount =
            $assignments
            ->filter(
                fn($assignment) =>
                $assignment->is_completed
            )
            ->count();


        // Count repeat evaluations.
        $repeatCount =
            $assignments
            ->filter(
                fn($assignment) =>
                $assignment->is_repeat
            )
            ->count();


        // Count ongoing evaluations.
        $ongoingCount =
            $assignments
            ->filter(
                fn($assignment) =>
                !$assignment->is_completed
                    &&
                    !$assignment->is_repeat
            )
            ->count();


        // Get five latest assigned evaluations.
        $recentEvaluations =
            $assignments
            ->take(5);


        // Get available PDPC result years.
        $years = DB::table('pdpc_response')
            ->where(
                'external_observer_id',
                $externalObserver->external_observer_id
            )
            ->where(
                'observation_stage',
                'EXTERNAL'
            )
            ->where(
                'status',
                'Submitted'
            )
            ->whereNotNull(
                'observation_date'
            )
            ->selectRaw(
                'YEAR(observation_date) AS year'
            )
            ->pluck('year')
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();


        // Build External PDPC result query.
        $pdpcResultQuery =
            DB::table('pdpc_response')
            ->join(
                'guru_new',
                'pdpc_response.gn_id',
                '=',
                'guru_new.gn_id'
            )
            ->where(
                'pdpc_response.external_observer_id',
                $externalObserver->external_observer_id
            )
            ->where(
                'pdpc_response.observation_stage',
                'EXTERNAL'
            )
            ->where(
                'pdpc_response.status',
                'Submitted'
            );


        // Apply teacher search.
        if ($search !== '') {

            $pdpcResultQuery
                ->where(
                    'guru_new.gn_name',
                    'like',
                    "%{$search}%"
                );
        }


        // Apply month filter.
        if ($month !== 'all') {

            $pdpcResultQuery
                ->whereMonth(
                    'pdpc_response.observation_date',
                    (int) $month
                );
        }


        // Apply year filter.
        if ($year !== 'all') {

            $pdpcResultQuery
                ->whereYear(
                    'pdpc_response.observation_date',
                    (int) $year
                );
        }


        // Apply achievement level filter.
        if ($level !== 'all') {

            $pdpcResultQuery
                ->where(
                    'pdpc_response.achievement_level',
                    $level
                );
        }


        // Get paginated submitted PDPC results.
        $pdpcResults =
            $pdpcResultQuery
            ->select(
                'pdpc_response.responseID',
                'pdpc_response.attempt_no',
                'pdpc_response.observation_date',
                'pdpc_response.class_name',
                'pdpc_response.subject_name',
                'pdpc_response.percentage',
                'pdpc_response.achievement_level',
                'pdpc_response.result',
                'guru_new.gn_id',
                'guru_new.gn_name'
            )
            ->orderByDesc(
                'pdpc_response.observation_date'
            )
            ->orderByDesc(
                'pdpc_response.responseID'
            )
            ->paginate(
                10,
                ['*'],
                'result_page'
            )
            ->withQueryString();


        // Return dashboard.
        return view(
            'external.dashboard',
            compact(
                'totalAssigned',
                'ongoingCount',
                'completedCount',
                'repeatCount',
                'recentEvaluations',
                'pdpcResults',
                'years',
                'search',
                'month',
                'year',
                'level'
            )
        );
    }


    // Convert response into dashboard status.
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
