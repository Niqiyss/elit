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
            'You are not registered as an observer.'
        );

        $isObserver = !is_null($observer);
        $isExternal = !is_null($externalObserver);

        $guruNew = GuruNew::with('school')
            ->where('gn_id', $gn_id)
            ->firstOrFail();

        // Check assignment
        if ($isObserver) {

            $assigned = DB::table('observer_assignment')
                ->where('gn_id', $gn_id)
                ->where(
                    'observer_id',
                    $observer->observer_id
                )
                ->exists();
        } else {

            $assigned = DB::table('observer_assignment')
                ->where('gn_id', $gn_id)
                ->where(
                    'external_observer_id',
                    $externalObserver->external_observer_id
                )
                ->exists();
        }

        abort_if(
            !$assigned,
            403,
            'This teacher is not assigned to you.'
        );


        // Get active form names
        $preForm = PreForm::where(
            'status',
            'Active'
        )
            ->orderBy('formID')
            ->first();

        $postForm = PostForm::where(
            'status',
            'Active'
        )
            ->orderBy('formID')
            ->first();


        // Get PRE response for Observer only
        $preResponse = null;

        if ($isObserver) {

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
        }


        // Get POST / Feedback response
        if ($isObserver) {

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
        } else {

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
                ->latest('responseID')
                ->first();
        }

        // Get PDPC form and responses
        $pdpcForm = PdpcForm::where(
            'status',
            'Active'
        )
            ->latest('formID')
            ->first();

        $pdpcPostResponse = null;
        $latestExternalResponse = null;

        if ($isObserver) {

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
        }

        if ($isExternal) {

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
                ->latest('attempt_no')
                ->first();
        }


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
                'latestExternalResponse'
            )
        );

    }
}
