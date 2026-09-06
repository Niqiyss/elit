<?php

namespace App\Http\Controllers\Observer;

use App\Http\Controllers\Controller;
use App\Models\Observer;
use App\Models\ExternalObserver;
use App\Models\PreResponse;
use App\Models\PdpcResponse;
use App\Models\PostResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ListEvaluateController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        $observer = Observer::where(
            'teacherID',
            $teacher->teacherID
        )->first();

        $externalObserver = ExternalObserver::where(
            'teacherID',
            $teacher->teacherID
        )->first();

        abort_if(
            !$observer && !$externalObserver,
            403,
            'You are not registered as an observer'
        );

        $isObserver = !is_null($observer);

        $status = $request->get(
            'status',
            'active'
        );

        if (
            $isObserver &&
            $status === 'repeat'
        ) {
            $status = 'active';
        }

        $listRoute = $isObserver
            ? 'observer.list.evaluate'
            : 'external.list.evaluate';


        // Get assignments
        $query = DB::table('observer_assignment')
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
            ->select(
                'observer_assignment.*',
                'guru_new.gn_name',
                'school.school_name'
            );

        if ($isObserver) {
            $query->where(
                'observer_assignment.observer_id',
                $observer->observer_id
            );
        } else {
            $query->where(
                'observer_assignment.external_observer_id',
                $externalObserver->external_observer_id
            );
        }

        $allAssignments = $query
            ->orderByDesc('observer_assignment.assigned_date')
            ->get();


        // Check form status
        foreach ($allAssignments as $assignment) {

            $gnId = $assignment->gn_id;


            // Normal ob
            if ($isObserver) {

                $preResponse = PreResponse::where('gn_id', $gnId)
                    ->where('observation_stage', 'PRE')
                    ->where('observer_id', $observer->observer_id)
                    ->latest('responseID')
                    ->first();

                $assignment->pre_status =
                    $this->getFormStatus($preResponse);


                $pdpcResponse = PdpcResponse::where('gn_id', $gnId)
                    ->where('observation_stage', 'POST')
                    ->where('observer_id', $observer->observer_id)
                    ->latest('responseID')
                    ->first();

                $assignment->pdpc_status =
                    $this->getFormStatus($pdpcResponse);


                $feedbackResponse = PostResponse::where('gn_id', $gnId)
                    ->where('observation_stage', 'POST')
                    ->where('observer_id', $observer->observer_id)
                    ->latest('responseID')
                    ->first();

                $assignment->feedback_status =
                    $this->getFormStatus($feedbackResponse);


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

                $assignment->is_repeat = false;
                $assignment->attempt_no = null;

                $assignment->is_fully_completed =
                    $assignment->completed_count ===
                    $assignment->total_forms;

                continue;
            }


            // Get latest External PDPC by current external ob
            $currentOwnPdpc = PdpcResponse::where('gn_id', $gnId)
                ->where('observation_stage', 'EXTERNAL')
                ->where(
                    'external_observer_id',
                    $externalObserver->external_observer_id
                )
                ->orderByDesc('attempt_no')
                ->orderByDesc('responseID')
                ->first();


            // Get latest External Feedback by current external ob
            $currentOwnFeedback = PostResponse::where('gn_id', $gnId)
                ->where('observation_stage', 'EXTERNAL')
                ->where(
                    'external_observer_id',
                    $externalObserver->external_observer_id
                )
                ->orderByDesc('attempt_no')
                ->orderByDesc('responseID')
                ->first();


            // Get latest submitted External PDPC across all external ob
            $latestPdpcSubmitted = PdpcResponse::where('gn_id', $gnId)
                ->where('observation_stage', 'EXTERNAL')
                ->where('status', 'Submitted')
                ->orderByDesc('attempt_no')
                ->orderByDesc('responseID')
                ->first();


            // Keep current observer attempt if they already started one
            if ($currentOwnPdpc || $currentOwnFeedback) {

                $currentAttemptNo = max(
                    $currentOwnPdpc?->attempt_no ?? 0,
                    $currentOwnFeedback?->attempt_no ?? 0
                );
            } else {

                // New external observer continues after latest submitted attempt
                $currentAttemptNo =
                    ($latestPdpcSubmitted?->attempt_no ?? 0) + 1;
            }


            $assignment->attempt_no =
                $currentAttemptNo;


            // Get PDPC for current attempt
            $currentPdpcResponse = PdpcResponse::where('gn_id', $gnId)
                ->where('observation_stage', 'EXTERNAL')
                ->where(
                    'external_observer_id',
                    $externalObserver->external_observer_id
                )
                ->where(
                    'attempt_no',
                    $currentAttemptNo
                )
                ->latest('responseID')
                ->first();


            // Get Feedback for current attempt
            $currentFeedbackResponse = PostResponse::where('gn_id', $gnId)
                ->where('observation_stage', 'EXTERNAL')
                ->where(
                    'external_observer_id',
                    $externalObserver->external_observer_id
                )
                ->where(
                    'attempt_no',
                    $currentAttemptNo
                )
                ->latest('responseID')
                ->first();


            // PDPC status
            $assignment->pdpc_status =
                $this->getFormStatus(
                    $currentPdpcResponse
                );


            // Feedback status
            $assignment->feedback_status =
                $this->getFormStatus(
                    $currentFeedbackResponse
                );


            // Latest PDPC result
            $assignment->external_result =
                $latestPdpcSubmitted?->result;


            // Repeat required
            $assignment->is_repeat =
                $latestPdpcSubmitted
                &&
                $latestPdpcSubmitted->result === 'REPEAT';


            // Completion count
            $assignment->completed_count =
                ($assignment->pdpc_status === 'Completed' ? 1 : 0)
                +
                ($assignment->feedback_status === 'Completed' ? 1 : 0);

            $assignment->total_forms = 2;


            // Draft exists
            $assignment->has_draft =
                $assignment->pdpc_status === 'Draft'
                ||
                $assignment->feedback_status === 'Draft';


            // Fully completed
            $assignment->is_fully_completed =
                $currentPdpcResponse
                &&
                $currentPdpcResponse->status === 'Submitted'
                &&
                $currentPdpcResponse->result === 'PASS'
                &&
                $currentFeedbackResponse
                &&
                $currentFeedbackResponse->status === 'Submitted';
        }


        // Filter
        if ($status === 'completed') {

            $filtered = $allAssignments
                ->filter(
                    fn($assignment) =>
                    $assignment->is_fully_completed
                )
                ->values();
        } elseif (
            !$isObserver &&
            $status === 'repeat'
        ) {

            $filtered = $allAssignments
                ->filter(
                    fn($assignment) =>
                    $assignment->is_repeat
                )
                ->values();
        } else {

            $filtered = $allAssignments
                ->filter(
                    function ($assignment) use ($isObserver) {

                        if (!$isObserver) {
                            return
                                !$assignment->is_fully_completed
                                &&
                                !$assignment->is_repeat;
                        }

                        return
                            !$assignment->is_fully_completed;
                    }
                )
                ->values();
        }


        // Pagination
        $page = $request->get('page', 1);

        $perPage = 10;

        $assignments = new LengthAwarePaginator(
            $filtered
                ->forPage(
                    $page,
                    $perPage
                )
                ->values(),

            $filtered->count(),

            $perPage,

            $page,

            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );


        return view(
            'observer.list-evaluate',
            compact(
                'assignments',
                'status',
                'isObserver',
                'listRoute'
            )
        );
    }


    // Convert response to status
    private function getFormStatus($response): string
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
