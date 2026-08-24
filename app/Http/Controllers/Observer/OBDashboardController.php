<?php

namespace App\Http\Controllers\Observer;

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
    public function index()
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


        return view(
            'observer.dashboard',
            compact(
                'totalAssigned',
                'ongoingCount',
                'draftCount',
                'completedCount',
                'recentEvaluations'
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