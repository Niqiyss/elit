<?php

namespace App\Http\Controllers\NewTeacher;

use App\Http\Controllers\Controller;
use App\Models\PreResponse;
use App\Models\PdpcResponse;
use App\Models\PostResponse;
use Illuminate\Support\Facades\Auth;

class NewTeacherDashboardController extends Controller
{
    // Show GN dashboard
    public function index()
    {
        $guru = Auth::guard('new_teacher')->user();

        abort_if(
            !$guru,
            403,
            'Unauthorized access.'
        );

        $gnId = $guru->gn_id;

        // Get submitted PRE
        $pre = PreResponse::with('form')
            ->where('gn_id', $gnId)
            ->where('observation_stage', 'PRE')
            ->where('status', 'Submitted')
            ->latest('responseID')
            ->first();

        // Get all submitted EXTERNAL PDPC attempts
        $externalAttempts = PdpcResponse::with('form')
            ->where('gn_id', $gnId)
            ->where('observation_stage', 'EXTERNAL')
            ->where('status', 'Submitted')
            ->orderByDesc('attempt_no')
            ->orderByDesc('responseID')
            ->get();

        // Get latest EXTERNAL PDPC attempt
        $externalPdpc = $externalAttempts->first();

        // Get all submitted EXTERNAL feedback
        $externalFeedbacks = PostResponse::with('form')
            ->where('gn_id', $gnId)
            ->where('observation_stage', 'EXTERNAL')
            ->where('status', 'Submitted')
            ->orderByDesc('attempt_no')
            ->orderByDesc('responseID')
            ->get();

        // Get feedback for latest EXTERNAL attempt
        $externalFeedback = $externalPdpc
            ? $externalFeedbacks->firstWhere(
                'attempt_no',
                $externalPdpc->attempt_no
            )
            : null;

        // Get submitted POST PDPC
        $postPdpc = PdpcResponse::with('form')
            ->where('gn_id', $gnId)
            ->where('observation_stage', 'POST')
            ->where('status', 'Submitted')
            ->latest('responseID')
            ->first();

        // Get submitted POST feedback
        $postFeedback = PostResponse::with('form')
            ->where('gn_id', $gnId)
            ->where('observation_stage', 'POST')
            ->where('status', 'Submitted')
            ->latest('responseID')
            ->first();

        // Set PRE status
        $preStatus = $pre
            ? 'completed'
            : 'pending';

        // Set EXTERNAL status
        if (!$externalPdpc) {

            $externalStatus = 'pending';
        } elseif ($externalPdpc->result === 'REPEAT') {

            $externalStatus = 'repeat';
        } elseif (
            $externalPdpc->result === 'PASS'
            && $externalFeedback
        ) {

            $externalStatus = 'completed';
        } else {

            $externalStatus = 'in_progress';
        }

        // Set POST status
        if ($postPdpc && $postFeedback) {

            $postStatus = 'completed';
        } elseif ($postPdpc || $postFeedback) {

            $postStatus = 'in_progress';
        } else {

            $postStatus = 'pending';
        }

        // Count current required submitted forms
        $submittedForms =
            ($pre ? 1 : 0)
            +
            ($externalPdpc ? 1 : 0)
            +
            ($externalFeedback ? 1 : 0)
            +
            ($postPdpc ? 1 : 0)
            +
            ($postFeedback ? 1 : 0);

        $totalForms = 5;

        // Calculate submitted form percentage
        $submittedPercentage = round(
            ($submittedForms / $totalForms) * 100
        );

        return view(
            'newteacher.dashboard',
            compact(
                'guru',
                'pre',
                'externalAttempts',
                'externalPdpc',
                'externalFeedback',
                'postPdpc',
                'postFeedback',
                'preStatus',
                'externalStatus',
                'postStatus',
                'submittedForms',
                'totalForms',
                'submittedPercentage'
            )
        );
    }
}
