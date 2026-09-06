<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrincipalDashboardController extends Controller
{
    // Show principal dashboard
    public function index(Request $request)
    {
        $principal = Auth::guard('principal')->user();

        $school = DB::table('school')
            ->where('schoolID', $principal->schoolID)
            ->first();

        $search = trim((string) $request->input('search', ''));
        $month = $request->input('month', 'all');
        $year = $request->input('year', 'all');
        $status = $request->input('status', 'all');

        // Get active new teachers
        $teachers = DB::table('guru_new')
            ->where('schoolID', $principal->schoolID)
            ->where('current_status', 'Active')
            ->orderBy('gn_name')
            ->get();

        // evaluation progress
        foreach ($teachers as $teacher) {
            $gnId = $teacher->gn_id;

            $pre = DB::table('pre_response')
                ->where('gn_id', $gnId)
                ->where('observation_stage', 'PRE')
                ->orderByDesc('responseID')
                ->first();

            $externalPdpc = DB::table('pdpc_response')
                ->where('gn_id', $gnId)
                ->where('observation_stage', 'EXTERNAL')
                ->orderByDesc('responseID')
                ->first();

            $externalFeedback = DB::table('post_response')
                ->where('gn_id', $gnId)
                ->where('observation_stage', 'EXTERNAL')
                ->orderByDesc('responseID')
                ->first();

            $postPdpc = DB::table('pdpc_response')
                ->where('gn_id', $gnId)
                ->where('observation_stage', 'POST')
                ->orderByDesc('responseID')
                ->first();

            $postFeedback = DB::table('post_response')
                ->where('gn_id', $gnId)
                ->where('observation_stage', 'POST')
                ->orderByDesc('responseID')
                ->first();

            $teacher->pre_completed =
                $pre && $pre->status === 'Submitted' ? 1 : 0;

            $teacher->pre_total = 1;

            $teacher->external_completed =
                ($externalPdpc && $externalPdpc->status === 'Submitted' ? 1 : 0)
                +
                ($externalFeedback && $externalFeedback->status === 'Submitted' ? 1 : 0);

            $teacher->external_total = 2;

            $teacher->post_completed =
                ($postPdpc && $postPdpc->status === 'Submitted' ? 1 : 0)
                +
                ($postFeedback && $postFeedback->status === 'Submitted' ? 1 : 0);

            $teacher->post_total = 2;

            $preStatus =
                $teacher->pre_completed === 1
                ? 'Completed'
                : 'In Progress';

            $externalStatus =
                $teacher->external_completed === 2
                ? 'Completed'
                : 'In Progress';

            $postStatus =
                $teacher->post_completed === 2
                ? 'Completed'
                : 'In Progress';

            if (
                $externalPdpc
                && $externalPdpc->status === 'Submitted'
                && $externalPdpc->percentage !== null
                && (float) $externalPdpc->percentage < 85
            ) {
                $externalStatus = 'Repeat Required';
            }

            $hasStartedEvaluation =
                $pre
                || $externalPdpc
                || $externalFeedback
                || $postPdpc
                || $postFeedback;

            if (
                $preStatus === 'Completed'
                && $externalStatus === 'Completed'
                && $postStatus === 'Completed'
            ) {
                $evaluationStatus = 'Completed';
            } elseif ($externalStatus === 'Repeat Required') {
                $evaluationStatus = 'Repeat Required';
            } elseif ($hasStartedEvaluation) {
                $evaluationStatus = 'In Progress';
            } else {
                $evaluationStatus = null;
            }

            $teacher->evaluation_status = $evaluationStatus;

            $dates = collect([
                $pre->observation_date ?? null,
                $externalPdpc->observation_date ?? null,
                $externalFeedback->observation_date ?? null,
                $postPdpc->observation_date ?? null,
                $postFeedback->observation_date ?? null,
            ])->filter();

            $teacher->last_evaluation_date =
                $dates->isNotEmpty()
                ? $dates->max()
                : null;
        }

        // Build summary
        $summary = [
            'total' => $teachers->count(),
            'completed' => $teachers->where('evaluation_status', 'Completed')->count(),
            'in_progress' => $teachers->where('evaluation_status', 'In Progress')->count(),
            'repeat_required' => $teachers->where('evaluation_status', 'Repeat Required')->count(),
        ];

        // Prepare evaluation report without performance filters
        $reportCollection = $teachers->values();

        $reportPage = (int) $request->get('report_page', 1);
        $reportPerPage = 10;

        $reportTeachers = new \Illuminate\Pagination\LengthAwarePaginator(
            $reportCollection->forPage($reportPage, $reportPerPage)->values(),
            $reportCollection->count(),
            $reportPerPage,
            $reportPage,
            [
                'path' => $request->url(),
                'pageName' => 'report_page',
                'query' => $request->except('report_page'),
            ]
        );

        // Get available years
        $years = collect()
            ->merge(
                DB::table('pre_response')
                    ->where('status', 'Submitted')
                    ->whereNotNull('observation_date')
                    ->selectRaw('YEAR(observation_date) as year')
                    ->pluck('year')
            )
            ->merge(
                DB::table('pdpc_response')
                    ->where('status', 'Submitted')
                    ->whereNotNull('observation_date')
                    ->selectRaw('YEAR(observation_date) as year')
                    ->pluck('year')
            )
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        // Use selected year or current year for PDPC chart
        $chartYear =
            $year !== 'all'
            ? (int) $year
            : (int) now()->year;

        // Build filtered PRE query
        $preQuery = DB::table('pre_response')
            ->join(
                'guru_new',
                'pre_response.gn_id',
                '=',
                'guru_new.gn_id'
            )
            ->where(
                'guru_new.schoolID',
                $principal->schoolID
            )
            ->where(
                'guru_new.current_status',
                'Active'
            )
            ->where(
                'pre_response.observation_stage',
                'PRE'
            )
            ->where(
                'pre_response.status',
                'Submitted'
            );

        if ($search !== '') {
            $preQuery->where(
                'guru_new.gn_name',
                'like',
                '%' . $search . '%'
            );
        }

        if ($month !== 'all') {
            $preQuery->whereMonth(
                'pre_response.observation_date',
                (int) $month
            );
        }

        if ($year !== 'all') {
            $preQuery->whereYear(
                'pre_response.observation_date',
                (int) $year
            );
        }

        // Apply status filter to PRE chart
        if ($status === 'Completed') {
            $preQuery->whereNotNull('pre_response.percentage');
        } elseif ($status === 'Repeat Required') {
            $preQuery->whereRaw('1 = 0');
        } elseif ($status === 'In Progress') {
            $preQuery->whereRaw('1 = 0');
        }

        $preResults = $preQuery
            ->select(
                'pre_response.gn_id',
                'pre_response.percentage',
                'pre_response.achievement_level',
                'pre_response.observation_date'
            )
            ->get();

        $prePerformance = [
            'labels' => [
                'Weak (0-39%)',
                'Satisfactory (40-59%)',
                'Good (60-79%)',
                'Very Good (80-89%)',
                'Excellent (90-100%)',
            ],
            'values' => [0, 0, 0, 0, 0],
            'total' => 0,
        ];

        foreach ($preResults as $result) {
            if ($result->percentage === null) {
                continue;
            }

            $percentage = (float) $result->percentage;

            if ($percentage >= 90) {
                $prePerformance['values'][4]++;
            } elseif ($percentage >= 80) {
                $prePerformance['values'][3]++;
            } elseif ($percentage >= 60) {
                $prePerformance['values'][2]++;
            } elseif ($percentage >= 40) {
                $prePerformance['values'][1]++;
            } else {
                $prePerformance['values'][0]++;
            }

            $prePerformance['total']++;
        }

        // Build filtered PDPC query
        $pdpcQuery = DB::table('pdpc_response')
            ->join(
                'guru_new',
                'pdpc_response.gn_id',
                '=',
                'guru_new.gn_id'
            )
            ->where(
                'guru_new.schoolID',
                $principal->schoolID
            )
            ->where(
                'guru_new.current_status',
                'Active'
            )
            ->where(
                'pdpc_response.status',
                'Submitted'
            )
            ->whereIn(
                'pdpc_response.observation_stage',
                ['EXTERNAL', 'POST']
            );

        if ($search !== '') {
            $pdpcQuery->where(
                'guru_new.gn_name',
                'like',
                '%' . $search . '%'
            );
        }

        if ($month !== 'all') {
            $pdpcQuery->whereMonth(
                'pdpc_response.observation_date',
                (int) $month
            );
        }

        if ($year !== 'all') {
            $pdpcQuery->whereYear(
                'pdpc_response.observation_date',
                (int) $year
            );
        } else {
            $pdpcQuery->whereYear(
                'pdpc_response.observation_date',
                $chartYear
            );
        }

        if ($status === 'Completed') {
            $pdpcQuery->whereNotNull('pdpc_response.percentage');
        } elseif ($status === 'Repeat Required') {
            $pdpcQuery
                ->where(
                    'pdpc_response.observation_stage',
                    'EXTERNAL'
                )
                ->where(
                    'pdpc_response.percentage',
                    '<',
                    85
                );
        } elseif ($status === 'In Progress') {
            $pdpcQuery->whereRaw('1 = 0');
        }

        $pdpcResults = $pdpcQuery
            ->select(
                'pdpc_response.gn_id',
                'pdpc_response.observation_stage',
                'pdpc_response.percentage',
                'pdpc_response.observation_date'
            )
            ->get();

        $pdpcTrend = [
            'labels' => [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec',
            ],
            'external' => array_fill(0, 12, null),
            'post' => array_fill(0, 12, null),
            'external_count' => array_fill(0, 12, 0),
            'post_count' => array_fill(0, 12, 0),
        ];

        // Calculate monthly PDPC averages
        for ($monthNumber = 1; $monthNumber <= 12; $monthNumber++) {
            $externalMonthly = $pdpcResults
                ->filter(function ($result) use ($monthNumber) {
                    return $result->observation_stage === 'EXTERNAL'
                        && $result->observation_date
                        && Carbon::parse($result->observation_date)->month === $monthNumber
                        && $result->percentage !== null;
                });

            $postMonthly = $pdpcResults
                ->filter(function ($result) use ($monthNumber) {
                    return $result->observation_stage === 'POST'
                        && $result->observation_date
                        && Carbon::parse($result->observation_date)->month === $monthNumber
                        && $result->percentage !== null;
                });

            if ($externalMonthly->isNotEmpty()) {
                $pdpcTrend['external'][$monthNumber - 1] =
                    round(
                        $externalMonthly->avg(
                            fn($result) => (float) $result->percentage
                        ),
                        2
                    );

                $pdpcTrend['external_count'][$monthNumber - 1] =
                    $externalMonthly
                    ->pluck('gn_id')
                    ->unique()
                    ->count();
            }

            if ($postMonthly->isNotEmpty()) {
                $pdpcTrend['post'][$monthNumber - 1] =
                    round(
                        $postMonthly->avg(
                            fn($result) => (float) $result->percentage
                        ),
                        2
                    );

                $pdpcTrend['post_count'][$monthNumber - 1] =
                    $postMonthly
                    ->pluck('gn_id')
                    ->unique()
                    ->count();
            }
        }

        $pdpcTrendHasData =
            collect($pdpcTrend['external'])->filter(fn($value) => $value !== null)->isNotEmpty()
            ||
            collect($pdpcTrend['post'])->filter(fn($value) => $value !== null)->isNotEmpty();

        return view(
            'principal.dashboard',
            compact(
                'principal',
                'school',
                'summary',
                'reportTeachers',
                'search',
                'month',
                'year',
                'status',
                'years',
                'chartYear',
                'prePerformance',
                'pdpcTrend',
                'pdpcTrendHasData'
            )
        );
    }
}
