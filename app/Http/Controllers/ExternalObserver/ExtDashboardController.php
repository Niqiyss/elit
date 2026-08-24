<?php

namespace App\Http\Controllers\ExternalObserver;

use App\Http\Controllers\Controller;
use App\Models\ExternalObserver;
use App\Models\PdpcResponse;
use App\Models\PostResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExtDashboardController extends Controller
{
    // Show external observer dashboard
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();

        $externalObserver = ExternalObserver::where(
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


        foreach ($assignments as $assignment) {

            $gnId = $assignment->gn_id;


            /*
            |--------------------------------------------------------------------------
            | PDPC Draft
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | Latest Submitted PDPC
            |--------------------------------------------------------------------------
            |
            | Use the latest submitted attempt for this teacher.
            | Do not restrict by current external observer because
            | repeat observation can be assigned to another observer.
            |
            */

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


            /*
            |--------------------------------------------------------------------------
            | PDPC Form Status
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | Feedback Form
            |--------------------------------------------------------------------------
            */

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


            $assignment->feedback_status =
                $this->getStatus(
                    $feedbackResponse
                );


            /*
            |--------------------------------------------------------------------------
            | PASS / REPEAT
            |--------------------------------------------------------------------------
            |
            | 85.00 and above = PASS
            | Below 85.00 = REPEAT
            |
            */

            $assignment->result = null;


            if ($latestPdpc) {

                $assignment->result =
                    (float) $latestPdpc->percentage >= 85
                        ? 'PASS'
                        : 'REPEAT';
            }


            $assignment->is_repeat =
                $latestPdpc
                &&
                (float) $latestPdpc->percentage < 85;


            /*
            |--------------------------------------------------------------------------
            | Form Completion
            |--------------------------------------------------------------------------
            |
            | External Observer has 2 forms:
            |
            | 1. PDPC Observation Form
            | 2. Feedback Observation Form
            |
            */

            $assignment->completed_count =
                ($assignment->pdpc_status === 'Completed' ? 1 : 0)
                +
                ($assignment->feedback_status === 'Completed' ? 1 : 0);


            $assignment->total_forms = 2;


            $assignment->has_draft =
                $assignment->pdpc_status === 'Draft'
                ||
                $assignment->feedback_status === 'Draft';


            /*
            |--------------------------------------------------------------------------
            | Fully Completed
            |--------------------------------------------------------------------------
            |
            | Fully completed means:
            |
            | - Both forms submitted
            | - Latest PDPC percentage >= 85
            |
            */

            $assignment->is_completed =
                $assignment->completed_count === 2
                &&
                $latestPdpc
                &&
                (float) $latestPdpc->percentage >= 85;


            /*
            |--------------------------------------------------------------------------
            | Progress
            |--------------------------------------------------------------------------
            |
            | Progress only means form submission progress.
            |
            | 0/2 = 0%
            | 1/2 = 50%
            | 2/2 = 100%
            |
            | 100% does NOT automatically mean PASS.
            |
            */

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


        /*
        |--------------------------------------------------------------------------
        | Dashboard Cards
        |--------------------------------------------------------------------------
        */

        $totalAssigned =
            $assignments->count();


        $completedCount =
            $assignments
                ->filter(
                    fn($assignment) =>
                    $assignment->is_completed
                )
                ->count();


        $repeatCount =
            $assignments
                ->filter(
                    fn($assignment) =>
                    $assignment->is_repeat
                )
                ->count();


        $ongoingCount =
            $assignments
                ->filter(
                    fn($assignment) =>
                    !$assignment->is_completed
                    &&
                    !$assignment->is_repeat
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | Recent Evaluations
        |--------------------------------------------------------------------------
        */

        $recentEvaluations =
            $assignments
                ->take(5);


        return view(
            'external.dashboard',
            compact(
                'totalAssigned',
                'ongoingCount',
                'completedCount',
                'repeatCount',
                'recentEvaluations'
            )
        );
    }


    // Convert form response into dashboard status
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