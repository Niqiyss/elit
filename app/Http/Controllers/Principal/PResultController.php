<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\GuruNew;
use App\Models\PreForm;
use App\Models\PreResponse;
use App\Models\PreScore;
use App\Models\PreSectionComment;
use App\Models\PdpcForm;
use App\Models\PdpcResponse;
use App\Models\PdpcScore;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PResultController extends Controller
{
    // Show evaluation result list
    public function index(Request $request)
    {
        $principal = Auth::guard('principal')->user();

        abort_if(!$principal, 403, 'Unauthorized access.');

        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status', 'all');
        $perPage = 10;


        // Get active GN under principal school only
        $teacherCollection = DB::table('guru_new')
            ->where('schoolID', $principal->schoolID)
            ->where('current_status', 'Active')
            ->when($search !== '', function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('gn_name', 'like', "%{$search}%")
                        ->orWhere('gn_id', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('gn_name')
            ->get();


        // Attach evaluation progress
        $teacherCollection = $teacherCollection->map(function ($teacher) {

            // PRE
            $pre = DB::table('pre_response')
                ->where('gn_id', $teacher->gn_id)
                ->where('observation_stage', 'PRE')
                ->where('status', 'Submitted')
                ->orderByDesc('responseID')
                ->first();


            // All EXTERNAL PDPC attempts
            $externalAttempts = DB::table('pdpc_response')
                ->where('gn_id', $teacher->gn_id)
                ->where('observation_stage', 'EXTERNAL')
                ->where('status', 'Submitted')
                ->orderByDesc('attempt_no')
                ->orderByDesc('responseID')
                ->get();


            // Latest EXTERNAL PDPC
            $externalPdpc = $externalAttempts->first();


            // Latest EXTERNAL Feedback
            $externalFeedback = null;

            if ($externalPdpc) {

                $externalFeedback = DB::table('post_response')
                    ->where('gn_id', $teacher->gn_id)
                    ->where('observation_stage', 'EXTERNAL')
                    ->where('attempt_no', $externalPdpc->attempt_no)
                    ->where('status', 'Submitted')
                    ->orderByDesc('responseID')
                    ->first();
            }


            // POST PDPC
            $postPdpc = DB::table('pdpc_response')
                ->where('gn_id', $teacher->gn_id)
                ->where('observation_stage', 'POST')
                ->where('status', 'Submitted')
                ->orderByDesc('responseID')
                ->first();


            // POST Feedback
            $postFeedback = DB::table('post_response')
                ->where('gn_id', $teacher->gn_id)
                ->where('observation_stage', 'POST')
                ->where('status', 'Submitted')
                ->orderByDesc('responseID')
                ->first();


            // PRE progress
            $preForms = collect([
                'pre' => $pre,
            ]);

            $preCompleted = $preForms->filter()->count();
            $preTotal = $preForms->count();

            $preProgress = $preTotal > 0
                ? round(($preCompleted / $preTotal) * 100)
                : 0;


            // EXTERNAL progress
            $externalForms = collect([
                'pdpc' => $externalPdpc,
                'feedback' => $externalFeedback,
            ]);

            $externalCompleted = $externalForms->filter()->count();
            $externalTotal = $externalForms->count();

            $externalProgress = $externalTotal > 0
                ? round(($externalCompleted / $externalTotal) * 100)
                : 0;


            // POST progress
            $postForms = collect([
                'pdpc' => $postPdpc,
                'feedback' => $postFeedback,
            ]);

            $postCompleted = $postForms->filter()->count();
            $postTotal = $postForms->count();

            $postProgress = $postTotal > 0
                ? round(($postCompleted / $postTotal) * 100)
                : 0;


            // PRE status
            $preStatus = $pre
                ? 'Completed'
                : 'Pending';


            // EXTERNAL status
            if (!$externalPdpc) {

                $externalStatus = 'Pending';
            } elseif ($externalPdpc->result === 'REPEAT') {

                $externalStatus = 'Repeat Required';
            } elseif (
                $externalPdpc->result === 'PASS'
                && $externalFeedback
            ) {

                $externalStatus = 'Completed';
            } else {

                $externalStatus = 'In Progress';
            }


            // POST status
            if ($postPdpc && $postFeedback) {

                $postStatus = 'Completed';
            } elseif ($postPdpc || $postFeedback) {

                $postStatus = 'In Progress';
            } else {

                $postStatus = 'Pending';
            }


            // Overall evaluation status
            if (
                $preStatus === 'Completed'
                && $externalStatus === 'Completed'
                && $postStatus === 'Completed'
            ) {

                $evaluationStatus = 'Completed';
            } elseif ($externalStatus === 'Repeat Required') {

                $evaluationStatus = 'Repeat Required';
            } elseif (
                $preStatus === 'Pending'
                && $externalStatus === 'Pending'
                && $postStatus === 'Pending'
            ) {

                $evaluationStatus = 'Pending';
            } else {

                $evaluationStatus = 'In Progress';
            }


            // Attach stage progress
            $teacher->pre_completed = $preCompleted;
            $teacher->pre_total = $preTotal;
            $teacher->pre_progress = $preProgress;

            $teacher->external_completed = $externalCompleted;
            $teacher->external_total = $externalTotal;
            $teacher->external_progress = $externalProgress;

            $teacher->post_completed = $postCompleted;
            $teacher->post_total = $postTotal;
            $teacher->post_progress = $postProgress;

            $teacher->evaluation_status = $evaluationStatus;

            return $teacher;
        });


        // Total active teachers
        $totalTeachers = $teacherCollection->count();

        // Status filter
        if ($status !== 'all') {

            $teacherCollection = $teacherCollection
                ->filter(function ($teacher) use ($status) {

                    return $teacher->evaluation_status === $status;
                })
                ->values();
        }


        // Pagination
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $currentItems = $teacherCollection
            ->slice(
                ($currentPage - 1) * $perPage,
                $perPage
            )
            ->values();


        $teachers = new LengthAwarePaginator(
            $currentItems,
            $teacherCollection->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );


        return view('principal.listresult', compact(
            'teachers',
            'totalTeachers',
            'search',
            'status'
        ));
    }


    // Show selected GN evaluation result
    public function show($gn_id)
    {
        $principal = Auth::guard('principal')->user();

        abort_if(!$principal, 403, 'Unauthorized access.');


        // GN must belong to principal school
        $guru = DB::table('guru_new')
            ->where('gn_id', $gn_id)
            ->where('schoolID', $principal->schoolID)
            ->first();

        abort_if(!$guru, 404, 'Teacher not found.');


        // PRE
        $pre = DB::table('pre_response')
            ->leftJoin(
                'pre_form',
                'pre_response.formID',
                '=',
                'pre_form.formID'
            )
            ->where('pre_response.gn_id', $gn_id)
            ->where('pre_response.observation_stage', 'PRE')
            ->where('pre_response.status', 'Submitted')
            ->select(
                'pre_response.*',
                'pre_form.form_name'
            )
            ->orderByDesc('pre_response.responseID')
            ->first();


        // POST PDPC
        $postPdpc = DB::table('pdpc_response')
            ->leftJoin(
                'pdpc_form',
                'pdpc_response.formID',
                '=',
                'pdpc_form.formID'
            )
            ->where('pdpc_response.gn_id', $gn_id)
            ->where('pdpc_response.observation_stage', 'POST')
            ->where('pdpc_response.status', 'Submitted')
            ->select(
                'pdpc_response.*',
                'pdpc_form.form_name'
            )
            ->orderByDesc('pdpc_response.responseID')
            ->first();


        // POST Feedback
        $postFeedback = DB::table('post_response')
            ->leftJoin(
                'post_form',
                'post_response.formID',
                '=',
                'post_form.formID'
            )
            ->where('post_response.gn_id', $gn_id)
            ->where('post_response.observation_stage', 'POST')
            ->where('post_response.status', 'Submitted')
            ->select(
                'post_response.*',
                'post_form.form_name'
            )
            ->orderByDesc('post_response.responseID')
            ->first();


        // All EXTERNAL PDPC attempts
        $externalAttempts = DB::table('pdpc_response')
            ->leftJoin(
                'pdpc_form',
                'pdpc_response.formID',
                '=',
                'pdpc_form.formID'
            )
            ->where('pdpc_response.gn_id', $gn_id)
            ->where('pdpc_response.observation_stage', 'EXTERNAL')
            ->where('pdpc_response.status', 'Submitted')
            ->select(
                'pdpc_response.*',
                'pdpc_form.form_name'
            )
            ->orderByDesc('pdpc_response.attempt_no')
            ->orderByDesc('pdpc_response.responseID')
            ->get();


        // Latest EXTERNAL PDPC
        $externalPdpc = $externalAttempts->first();


        // Feedback for latest EXTERNAL attempt
        $externalFeedback = null;

        if ($externalPdpc) {

            $externalFeedback = DB::table('post_response')
                ->leftJoin(
                    'post_form',
                    'post_response.formID',
                    '=',
                    'post_form.formID'
                )
                ->where('post_response.gn_id', $gn_id)
                ->where('post_response.observation_stage', 'EXTERNAL')
                ->where(
                    'post_response.attempt_no',
                    $externalPdpc->attempt_no
                )
                ->where('post_response.status', 'Submitted')
                ->select(
                    'post_response.*',
                    'post_form.form_name'
                )
                ->orderByDesc('post_response.responseID')
                ->first();
        }


        // Previous EXTERNAL attempts
        $externalHistory = $externalAttempts
            ->skip(1)
            ->values();


        /*
    |--------------------------------------------------------------------------
    | Stage completion
    |--------------------------------------------------------------------------
    */

        // PRE = 1 form
        $preCompleted = $pre ? 1 : 0;
        $preTotal = 1;


        // EXTERNAL = PDPC + Feedback
        $externalCompleted = collect([
            $externalPdpc,
            $externalFeedback,
        ])->filter()->count();

        $externalTotal = 2;


        // POST = PDPC + Feedback
        $postCompleted = collect([
            $postPdpc,
            $postFeedback,
        ])->filter()->count();

        $postTotal = 2;


        /*
    |--------------------------------------------------------------------------
    | Overall progress
    |--------------------------------------------------------------------------
    */
        $completedForms =
            $preCompleted
            + $externalCompleted
            + $postCompleted;

        $totalForms =
            $preTotal
            + $externalTotal
            + $postTotal;

        $overallProgress = $totalForms > 0
            ? round(($completedForms / $totalForms) * 100)
            : 0;


        /*
    |--------------------------------------------------------------------------
    | Overall Evaluation Status
    |--------------------------------------------------------------------------
    */
        if (
            $preCompleted === $preTotal
            &&
            $externalCompleted === $externalTotal
            &&
            $postCompleted === $postTotal
            &&
            $externalPdpc
            &&
            $externalPdpc->result === 'PASS'
        ) {

            $evaluationStatus = 'Completed';
        } elseif (
            $externalPdpc
            &&
            $externalPdpc->result === 'REPEAT'
        ) {

            $evaluationStatus = 'Repeat Required';
        } elseif ($completedForms === 0) {

            $evaluationStatus = 'Pending';
        } else {

            $evaluationStatus = 'In Progress';
        }


        return view('principal.viewresult', compact(
            'guru',
            'pre',
            'postPdpc',
            'postFeedback',
            'externalPdpc',
            'externalFeedback',
            'externalHistory',
            'preCompleted',
            'preTotal',
            'externalCompleted',
            'externalTotal',
            'postCompleted',
            'postTotal',
            'completedForms',
            'totalForms',
            'overallProgress',
            'evaluationStatus'
        ));
    }


    // View PRE result
    public function pre($responseID)
    {
        $principal = Auth::guard('principal')->user();

        abort_if(!$principal, 403, 'Unauthorized access.');


        // Get submitted PRE response
        $response = PreResponse::with([
            'observer.teacher',
        ])
            ->where('responseID', $responseID)
            ->where('observation_stage', 'PRE')
            ->where('status', 'Submitted')
            ->firstOrFail();


        // Teacher must belong to principal school
        $guru = GuruNew::with('school')
            ->where('gn_id', $response->gn_id)
            ->where('schoolID', $principal->schoolID)
            ->firstOrFail();


        // Exact PRE form version used
        $form = PreForm::where(
            'formID',
            $response->formID
        )
            ->with([
                'sections' =>
                fn($query) =>
                $query->orderBy('display_order'),

                'sections.criteria' =>
                fn($query) =>
                $query->orderBy('display_order'),
            ])
            ->firstOrFail();


        // Saved scores
        $scores = PreScore::where(
            'responseID',
            $response->responseID
        )
            ->pluck(
                'score',
                'criteriaID'
            );


        // Saved section comments
        $sectionComments = PreSectionComment::where(
            'responseID',
            $response->responseID
        )
            ->pluck(
                'comment',
                'sectionID'
            );


        return view(
            'principal.result-pre',
            compact(
                'guru',
                'form',
                'response',
                'scores',
                'sectionComments'
            )
        );
    }


    // View PDPC result
    public function pdpc($responseID)
    {
        $principal = Auth::guard('principal')->user();

        abort_if(!$principal, 403, 'Unauthorized access.');


        // Get submitted PDPC response
        $response = PdpcResponse::with([
            'observer.teacher',
            'externalObserver.teacher',
        ])
            ->where('responseID', $responseID)
            ->whereIn(
                'observation_stage',
                [
                    'POST',
                    'EXTERNAL',
                ]
            )
            ->where('status', 'Submitted')
            ->firstOrFail();


        // Teacher must belong to principal school
        $guru = GuruNew::with('school')
            ->where('gn_id', $response->gn_id)
            ->where('schoolID', $principal->schoolID)
            ->firstOrFail();


        // Exact PDPC form version used
        $form = PdpcForm::with([
            'aspects.tums.tt.points',
            'aspects.tums.rubrics',
        ])
            ->where(
                'formID',
                $response->formID
            )
            ->firstOrFail();


        // Saved scores
        $scores = PdpcScore::where(
            'responseID',
            $response->responseID
        )
            ->pluck(
                'score',
                'pointID'
            );


        // Build saved TUMS display
        $tumsResults = $this->calculateStoredTumsResults(
            $form,
            $scores
        );


        return view(
            'principal.result-pdpc',
            compact(
                'guru',
                'form',
                'response',
                'scores',
                'tumsResults'
            )
        );
    }


    // View Feedback result
    public function post($responseID)
    {
        $principal = Auth::guard('principal')->user();

        abort_if(!$principal, 403, 'Unauthorized access.');


        // Get submitted Feedback response
        $response = DB::table('post_response')
            ->where('responseID', $responseID)
            ->whereIn(
                'observation_stage',
                [
                    'POST',
                    'EXTERNAL',
                ]
            )
            ->where('status', 'Submitted')
            ->first();


        abort_if(
            !$response,
            404,
            'Feedback result not found.'
        );


        // Teacher must belong to principal school
        $guru = $this->getGuruNewForPrincipal(
            $response->gn_id,
            $principal->schoolID
        );


        // Exact Feedback form version used
        $form = $this->getPostForm(
            $response->formID
        );


        // Evaluator information
        $this->attachFeedbackEvaluator(
            $response
        );


        // Saved answers
        $existingAnswers = $this->getPostAnswers(
            $response->responseID,
            $form
        );


        return view(
            'principal.result-post',
            compact(
                'guru',
                'form',
                'response',
                'existingAnswers'
            )
        );
    }


    // Build PDPC TUMS display from saved scores
    private function calculateStoredTumsResults(
        $form,
        $scores
    ): array {
        $results = [];


        foreach ($form->aspects as $aspect) {

            foreach ($aspect->tums as $tums) {

                $points = $tums->tt
                    ->flatMap(
                        fn($tt) =>
                        $tt->points
                    );


                $totalPoints = $points->count();

                $actionCount = 0;
                $qualityTotal = 0;


                foreach ($points as $point) {

                    $score = (int) (
                        $scores[$point->pointID]
                        ?? 0
                    );


                    $qualityTotal += $score;


                    if ($score > 0) {
                        $actionCount++;
                    }
                }


                $actionScore =
                    $totalPoints > 0
                    ? round(
                        (
                            $actionCount /
                            $totalPoints
                        ) * 4
                    )
                    : 0;


                $qualityMean =
                    $totalPoints > 0
                    ? round(
                        $qualityTotal /
                            $totalPoints,
                        2
                    )
                    : 0;


                $actionPercentage = round(
                    (
                        $actionScore /
                        4
                    ) * 100,
                    2
                );


                $qualityPercentage = round(
                    (
                        $qualityMean /
                        4
                    ) * 100,
                    2
                );


                $tumsPercentage = round(
                    (
                        $actionPercentage
                        * 0.25
                    )
                        +
                        (
                            $qualityPercentage
                            * 0.75
                        ),
                    2
                );


                $weight = (float) $tums->wajaran;


                $weightedScore = round(
                    (
                        $tumsPercentage
                        * $weight
                    ) / 100,
                    2
                );


                $results[$tums->tumsID] = [

                    'total_points' =>
                    $totalPoints,

                    'action_count' =>
                    $actionCount,

                    'quality_total' =>
                    $qualityTotal,

                    'action_score' =>
                    $actionScore,

                    'quality_mean' =>
                    $qualityMean,

                    'action_percentage' =>
                    $actionPercentage,

                    'quality_percentage' =>
                    $qualityPercentage,

                    'tums_percentage' =>
                    $tumsPercentage,

                    'wajaran' =>
                    $weight,

                    'weighted_score' =>
                    $weightedScore,
                ];
            }
        }


        return $results;
    }


    // Get exact Feedback form version
    private function getPostForm($formID)
    {
        $form = DB::table('post_form')
            ->where('formID', $formID)
            ->first();


        abort_if(
            !$form,
            404,
            'Feedback form not found.'
        );


        $sections = DB::table('post_section')
            ->where('formID', $form->formID)
            ->orderBy('display_order')
            ->get();


        foreach ($sections as $section) {

            // Get every field from saved form version
            $fields = DB::table('post_field')
                ->where(
                    'sectionID',
                    $section->sectionID
                )
                ->orderBy('display_order')
                ->get();


            foreach ($fields as $field) {

                $field->options = DB::table(
                    'post_field_option'
                )
                    ->where(
                        'fieldID',
                        $field->fieldID
                    )
                    ->orderBy('display_order')
                    ->get();
            }


            $section->fields = $fields;
        }


        $form->sections = $sections;


        return $form;
    }


    // Get saved Feedback answers
    private function getPostAnswers(
        $responseID,
        $form
    ): array {
        $answers = DB::table('post_answer')
            ->where(
                'responseID',
                $responseID
            )
            ->get();


        $fields = $form->sections
            ->flatMap(
                fn($section) =>
                $section->fields
            );


        $existingAnswers = [];


        foreach ($answers as $answer) {

            $field = $fields->firstWhere(
                'fieldID',
                $answer->fieldID
            );


            if (!$field) {
                continue;
            }


            if (
                $field->field_type ===
                'checkbox'
            ) {

                $existingAnswers[$answer->fieldID] = json_decode(
                    $answer->answer_value,
                    true
                ) ?? [];
            } else {

                $existingAnswers[$answer->fieldID] =
                    $answer->answer_value;
            }
        }


        return $existingAnswers;
    }


    // Get GN under principal school using DB
    private function getGuruNewForPrincipal(
        $gnId,
        $schoolID
    ) {
        $guru = DB::table('guru_new')
            ->where('gn_id', $gnId)
            ->where('schoolID', $schoolID)
            ->first();


        abort_if(
            !$guru,
            404,
            'Teacher not found.'
        );


        $guru->school =
            $guru->schoolID
            ? DB::table('school')
            ->where(
                'schoolID',
                $guru->schoolID
            )
            ->first()
            : null;


        return $guru;
    }


    // Attach Feedback evaluator information
    private function attachFeedbackEvaluator(
        $response
    ): void {
        $response->evaluator_name = '-';


        $response->evaluator_role =
            $response->observation_stage ===
            'EXTERNAL'
            ? 'External Observer'
            : 'Observer';


        // Observer
        if (
            $response->observation_stage ===
            'POST'
            &&
            $response->observer_id
        ) {

            $observer = DB::table('observer')
                ->where(
                    'observer_id',
                    $response->observer_id
                )
                ->first();


            if ($observer) {

                $teacher = DB::table('teacher')
                    ->where(
                        'teacherID',
                        $observer->teacherID
                    )
                    ->first();


                $response->evaluator_name =
                    $teacher->teacher_name
                    ?? '-';
            }


            return;
        }


        // External Observer
        if (
            $response->observation_stage ===
            'EXTERNAL'
            &&
            $response->external_observer_id
        ) {

            $externalObserver =
                DB::table(
                    'external_observer'
                )
                ->where(
                    'external_observer_id',
                    $response->external_observer_id
                )
                ->first();


            if ($externalObserver) {

                $teacher = DB::table('teacher')
                    ->where(
                        'teacherID',
                        $externalObserver->teacherID
                    )
                    ->first();


                $response->evaluator_name =
                    $teacher->teacher_name
                    ?? '-';
            }
        }
    }
}
