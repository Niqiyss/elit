<?php

namespace App\Http\Controllers\ExternalObserver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExtDashboardController extends Controller
{
    // Show external observer dashboard
    public function index(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        abort_if(!$teacher, 403, 'Unauthorized access');

        $externalObserver = DB::table('external_observer')
            ->where('teacherID', $teacher->teacherID)
            ->first();

        abort_if(!$externalObserver, 404);

        $search = trim((string) $request->input('search', ''));
        $month = $request->input('month', 'all');
        $year = $request->input('year', 'all');
        $level = $request->input('level', 'all');

        // Get assigned teachers
        $assignments = DB::table('observer_assignment')
            ->join('guru_new', 'observer_assignment.gn_id', '=', 'guru_new.gn_id')
            ->leftJoin('school', 'guru_new.schoolID', '=', 'school.schoolID')
            ->where('observer_assignment.external_observer_id', $externalObserver->external_observer_id)
            ->select('observer_assignment.*', 'guru_new.gn_name', 'school.school_name')
            ->orderByDesc('observer_assignment.assigned_date')
            ->get();

        // Attach current evaluation information
        foreach ($assignments as $assignment) {
            $gnId = $assignment->gn_id;

            $pdpcDraft = DB::table('pdpc_response')
                ->where('gn_id', $gnId)
                ->where('observation_stage', 'EXTERNAL')
                ->where('external_observer_id', $externalObserver->external_observer_id)
                ->where('status', 'Draft')
                ->orderByDesc('responseID')
                ->first();

            $latestPdpc = DB::table('pdpc_response')
                ->where('gn_id', $gnId)
                ->where('observation_stage', 'EXTERNAL')
                ->where('status', 'Submitted')
                ->orderByDesc('attempt_no')
                ->orderByDesc('responseID')
                ->first();

            if ($pdpcDraft) {
                $assignment->pdpc_status = 'Draft';
            } elseif ($latestPdpc) {
                $assignment->pdpc_status = 'Completed';
            } else {
                $assignment->pdpc_status = 'Pending';
            }

            $feedbackResponse = DB::table('post_response')
                ->where('gn_id', $gnId)
                ->where('observation_stage', 'EXTERNAL')
                ->where('external_observer_id', $externalObserver->external_observer_id)
                ->orderByDesc('responseID')
                ->first();

            $assignment->feedback_status = $this->getStatus($feedbackResponse);
            $assignment->result = null;

            if ($latestPdpc) {
                $assignment->result = $latestPdpc->result ?? ((float) $latestPdpc->percentage >= 85 ? 'PASS' : 'REPEAT');
            }

            $assignment->is_repeat = $latestPdpc && $assignment->result === 'REPEAT';

            $assignment->completed_count =
                ($assignment->pdpc_status === 'Completed' ? 1 : 0) +
                ($assignment->feedback_status === 'Completed' ? 1 : 0);

            $assignment->total_forms = 2;

            $assignment->has_draft =
                $assignment->pdpc_status === 'Draft' ||
                $assignment->feedback_status === 'Draft';

            $assignment->is_completed =
                $assignment->completed_count === 2 &&
                $latestPdpc &&
                $assignment->result === 'PASS';

            $assignment->progress = round(($assignment->completed_count / $assignment->total_forms) * 100);
        }

        $totalAssigned = $assignments->count();
        $completedCount = $assignments->filter(fn($assignment) => $assignment->is_completed)->count();
        $repeatCount = $assignments->filter(fn($assignment) => $assignment->is_repeat)->count();
        $ongoingCount = $assignments->filter(fn($assignment) => !$assignment->is_completed && !$assignment->is_repeat)->count();
        $recentEvaluations = $assignments->take(5);

        // Get available PDPC result years
        $years = DB::table('pdpc_response')
            ->where('external_observer_id', $externalObserver->external_observer_id)
            ->where('observation_stage', 'EXTERNAL')
            ->where('status', 'Submitted')
            ->whereNotNull('observation_date')
            ->selectRaw('YEAR(observation_date) AS year')
            ->pluck('year')
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        // Build External PDPC result query
        $pdpcResultQuery = DB::table('pdpc_response')
            ->join('guru_new', 'pdpc_response.gn_id', '=', 'guru_new.gn_id')
            ->where('pdpc_response.external_observer_id', $externalObserver->external_observer_id)
            ->where('pdpc_response.observation_stage', 'EXTERNAL')
            ->where('pdpc_response.status', 'Submitted');

        if ($search !== '') {
            $pdpcResultQuery->where('guru_new.gn_name', 'like', "%{$search}%");
        }

        if ($month !== 'all') {
            $pdpcResultQuery->whereMonth('pdpc_response.observation_date', (int) $month);
        }

        if ($year !== 'all') {
            $pdpcResultQuery->whereYear('pdpc_response.observation_date', (int) $year);
        }

        if ($level !== 'all') {
            $pdpcResultQuery->where('pdpc_response.achievement_level', $level);
        }

        // Get paginated submitted PDPC results
        $pdpcResults = $pdpcResultQuery
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
            ->orderByDesc('pdpc_response.observation_date')
            ->orderByDesc('pdpc_response.responseID')
            ->paginate(10, ['*'], 'result_page')
            ->withQueryString();

        return view('external.dashboard', compact(
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
        ));
    }

    // Convert response into dashboard status
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
