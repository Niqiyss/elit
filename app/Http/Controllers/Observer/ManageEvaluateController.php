<?php

namespace App\Http\Controllers\Observer;

use App\Http\Controllers\Controller;
use App\Models\Observer;
use App\Models\ExternalObserver;
use App\Models\GuruNew;
use App\Models\PostResponse;
use App\Models\PreResponse;
use App\Models\PreForm;
use App\Models\PostForm;
use App\Models\PdpcForm;
use App\Models\PdpcResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageEvaluateController extends Controller
{
    public function index($gn_id)
    {
        // Get logged-in teacher.
        $teacher = Auth::guard('teacher')->user();

        abort_if(
            !$teacher,
            403,
            'Unauthorized access.'
        );

        // Get observer record.
        $observer = Observer::where(
            'teacherID',
            $teacher->teacherID
        )->first();

        // Get external observer record.
        $externalObserver = ExternalObserver::where(
            'teacherID',
            $teacher->teacherID
        )->first();

        // Make sure teacher is registered as an evaluator.
        abort_if(
            !$observer && !$externalObserver,
            403,
            'You are not registered as an observer.'
        );

        // Determine evaluator role.
        $isObserver = !is_null($observer);
        $isExternal = !is_null($externalObserver);

        // Get selected new teacher.
        $guruNew = GuruNew::with('school')
            ->where('gn_id', $gn_id)
            ->firstOrFail();

        // Check normal observer assignment.
        if ($isObserver) {

            $assigned = DB::table('observer_assignment')
                ->where('gn_id', $gn_id)
                ->where(
                    'observer_id',
                    $observer->observer_id
                )
                ->exists();
        } else {

            // Check external observer assignment.
            $assigned = DB::table('observer_assignment')
                ->where('gn_id', $gn_id)
                ->where(
                    'external_observer_id',
                    $externalObserver->external_observer_id
                )
                ->exists();
        }

        // Prevent access to unassigned teacher.
        abort_if(
            !$assigned,
            403,
            'This teacher is not assigned to you.'
        );

        // Get latest active PRE form.
        $preForm = PreForm::where(
            'status',
            'Active'
        )
            ->latest('formID')
            ->first();

        // Get latest active Feedback form.
        $postForm = PostForm::where(
            'status',
            'Active'
        )
            ->latest('formID')
            ->first();

        // Get latest active PDPC form.
        $pdpcForm = PdpcForm::where(
            'status',
            'Active'
        )
            ->latest('formID')
            ->first();

        // Prepare default response variables.
        $preResponse = null;
        $pdpcPostResponse = null;
        $latestExternalResponse = null;
        $latestExternalSubmitted = null;
        $feedbackResponse = null;
        $externalAttemptNo = null;
        $externalHistory = collect();

        // Load normal observer evaluation records.
        if ($isObserver) {

            // Get latest PRE response by current observer.
            $preResponse = PreResponse::where(
                'gn_id',
                $gn_id
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

            // Get latest POST PDPC response by current observer.
            $pdpcPostResponse = PdpcResponse::where(
                'gn_id',
                $gn_id
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

            // Get latest POST Feedback response by current observer.
            $feedbackResponse = PostResponse::where(
                'gn_id',
                $gn_id
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
        }

        // Load external observer evaluation records.
        if ($isExternal && !$isObserver) {

            // Get latest submitted External PDPC attempt across all external observers.
            $latestExternalSubmitted = PdpcResponse::where(
                'gn_id',
                $gn_id
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

            // Get latest External PDPC record belonging to current external observer.
            $currentOwnPdpc = PdpcResponse::where(
                'gn_id',
                $gn_id
            )
                ->where(
                    'observation_stage',
                    'EXTERNAL'
                )
                ->where(
                    'external_observer_id',
                    $externalObserver->external_observer_id
                )
                ->orderByDesc('attempt_no')
                ->orderByDesc('responseID')
                ->first();

            // Get latest External Feedback record belonging to current external observer.
            $currentOwnFeedback = PostResponse::where(
                'gn_id',
                $gn_id
            )
                ->where(
                    'observation_stage',
                    'EXTERNAL'
                )
                ->where(
                    'external_observer_id',
                    $externalObserver->external_observer_id
                )
                ->orderByDesc('attempt_no')
                ->orderByDesc('responseID')
                ->first();

            // Keep current observer attempt if an evaluation has already been started.
            if ($currentOwnPdpc || $currentOwnFeedback) {

                $externalAttemptNo = max(
                    $currentOwnPdpc?->attempt_no ?? 0,
                    $currentOwnFeedback?->attempt_no ?? 0
                );
            } else {

                // Continue after latest submitted External attempt.
                $externalAttemptNo =
                    ($latestExternalSubmitted?->attempt_no ?? 0) + 1;
            }

            // Get current External PDPC response for calculated attempt.
            $latestExternalResponse = PdpcResponse::where(
                'gn_id',
                $gn_id
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
                    'attempt_no',
                    $externalAttemptNo
                )
                ->latest('responseID')
                ->first();

            // Get current External Feedback response for calculated attempt.
            $feedbackResponse = PostResponse::where(
                'gn_id',
                $gn_id
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
                    'attempt_no',
                    $externalAttemptNo
                )
                ->latest('responseID')
                ->first();

            // Get previous submitted External attempts only.
            $externalHistory = DB::table('pdpc_response')
                ->leftJoin(
                    'external_observer',
                    'pdpc_response.external_observer_id',
                    '=',
                    'external_observer.external_observer_id'
                )
                ->leftJoin(
                    'teacher',
                    'external_observer.teacherID',
                    '=',
                    'teacher.teacherID'
                )
                ->where(
                    'pdpc_response.gn_id',
                    $gn_id
                )
                ->where(
                    'pdpc_response.observation_stage',
                    'EXTERNAL'
                )
                ->where(
                    'pdpc_response.status',
                    'Submitted'
                )
                ->where(
                    'pdpc_response.attempt_no',
                    '<',
                    $externalAttemptNo
                )
                ->select(
                    'pdpc_response.responseID',
                    'pdpc_response.attempt_no',
                    'pdpc_response.observation_date',
                    'pdpc_response.percentage',
                    'pdpc_response.achievement_level',
                    'pdpc_response.result',
                    'pdpc_response.external_observer_id',
                    'teacher.teacher_name as evaluator_name'
                )
                ->orderByDesc(
                    'pdpc_response.attempt_no'
                )
                ->orderByDesc(
                    'pdpc_response.responseID'
                )
                ->get();
        }

        // Return evaluation management page.
        return view(
            'observer.manage',
            compact(
                'guruNew',
                'isObserver',
                'isExternal',
                'preResponse',
                'feedbackResponse',
                'preForm',
                'postForm',
                'pdpcForm',
                'pdpcPostResponse',
                'latestExternalResponse',
                'latestExternalSubmitted',
                'externalAttemptNo',
                'externalHistory'
            )
        );
    }
}
