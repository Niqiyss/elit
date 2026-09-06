<?php

namespace App\Http\Controllers\NewTeacher;

use App\Http\Controllers\Controller;
use App\Models\PreForm;
use App\Models\PreResponse;
use App\Models\PreScore;
use App\Models\PreSectionComment;
use App\Models\PdpcForm;
use App\Models\PdpcResponse;
use App\Models\PdpcScore;
use App\Models\PostResponse;
use App\Models\GuruNew;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    // Show own ev results
    public function index()
    {
        $guru = Auth::guard('new_teacher')->user();

        abort_if(!$guru, 403, 'Unauthorized access');

        $gnId = $guru->gn_id;

        // Latest PRE
        $pre = PreResponse::with('form')
            ->where('gn_id', $gnId)
            ->where('observation_stage', 'PRE')
            ->where('status', 'Submitted')
            ->latest('responseID')
            ->first();

        // Latest POST PDPC
        $postPdpc = PdpcResponse::with('form')
            ->where('gn_id', $gnId)
            ->where('observation_stage', 'POST')
            ->where('status', 'Submitted')
            ->latest('responseID')
            ->first();

        // Latest POST Feedback
        $postFeedback = PostResponse::with('form')
            ->where('gn_id', $gnId)
            ->where('observation_stage', 'POST')
            ->where('status', 'Submitted')
            ->latest('responseID')
            ->first();

        // Latest EXTERNAL PDPC
        $externalPdpc = PdpcResponse::with('form')
            ->where('gn_id', $gnId)
            ->where('observation_stage', 'EXTERNAL')
            ->where('status', 'Submitted')
            ->orderByDesc('attempt_no')
            ->orderByDesc('responseID')
            ->first();

        // Feedback belong to latest EXTERNAL attempt
        $externalFeedback = $externalPdpc
            ? PostResponse::with('form')
            ->where('gn_id', $gnId)
            ->where('observation_stage', 'EXTERNAL')
            ->where('attempt_no', $externalPdpc->attempt_no)
            ->where('status', 'Submitted')
            ->latest('responseID')
            ->first()
            : null;

        // Previous EXTERNAL PDPC attempts
        $externalHistory = PdpcResponse::with('form')
            ->where('gn_id', $gnId)
            ->where('observation_stage', 'EXTERNAL')
            ->where('status', 'Submitted')
            ->when($externalPdpc, fn($query) => $query->where('attempt_no', '<', $externalPdpc->attempt_no))
            ->orderByDesc('attempt_no')
            ->orderByDesc('responseID')
            ->get();

        return view('newteacher.result', compact(
            'guru',
            'pre',
            'postPdpc',
            'postFeedback',
            'externalPdpc',
            'externalFeedback',
            'externalHistory'
        ));
    }

    // View own PRE result
    public function pre($responseID)
    {
        $authGuru = Auth::guard('new_teacher')->user();

        abort_if(!$authGuru, 403, 'Unauthorized access');

        // Get own PRE result
        $response = PreResponse::with(['observer.teacher'])
            ->where('responseID', $responseID)
            ->where('gn_id', $authGuru->gn_id)
            ->where('observation_stage', 'PRE')
            ->where('status', 'Submitted')
            ->firstOrFail();

        // Get teacher
        $guru = GuruNew::with('school')->where('gn_id', $authGuru->gn_id)->firstOrFail();

        // Get exact PRE form version
        $form = PreForm::where('formID', $response->formID)
            ->with([
                'sections' => fn($query) => $query->orderBy('display_order'),
                'sections.criteria' => fn($query) => $query->orderBy('display_order'),
            ])
            ->firstOrFail();

        // Get saved scores
        $scores = PreScore::where('responseID', $response->responseID)->pluck('score', 'criteriaID');

        // Get saved section comments
        $sectionComments = PreSectionComment::where('responseID', $response->responseID)->pluck('comment', 'sectionID');

        return view('newteacher.result-pre', compact('guru', 'form', 'response', 'scores', 'sectionComments'));
    }

    // View own PDPC result
    public function pdpc($responseID)
    {
        $authGuru = Auth::guard('new_teacher')->user();

        abort_if(!$authGuru, 403, 'Unauthorized access');

        // Get own submitted PDPC response
        $response = PdpcResponse::with(['observer.teacher', 'externalObserver.teacher'])
            ->where('responseID', $responseID)
            ->where('gn_id', $authGuru->gn_id)
            ->whereIn('observation_stage', ['POST', 'EXTERNAL'])
            ->where('status', 'Submitted')
            ->firstOrFail();

        // Get teacher
        $guru = GuruNew::with('school')->where('gn_id', $authGuru->gn_id)->firstOrFail();

        // Get exact PDPC form version used
        $form = PdpcForm::with(['aspects.tums.tt.points', 'aspects.tums.rubrics'])
            ->where('formID', $response->formID)
            ->firstOrFail();

        // Get saved scores
        $scores = PdpcScore::where('responseID', $response->responseID)->pluck('score', 'pointID');

        // Build TUMS display from saved scores
        $tumsResults = $this->calculateStoredTumsResults($form, $scores);

        return view('newteacher.result-pdpc', compact('guru', 'form', 'response', 'scores', 'tumsResults'));
    }

    // View own Feedback result
    public function post($responseID)
    {
        $authGuru = Auth::guard('new_teacher')->user();

        abort_if(!$authGuru, 403, 'Unauthorized access');

        // Get own submitted Feedback response
        $response = DB::table('post_response')
            ->where('responseID', $responseID)
            ->where('gn_id', $authGuru->gn_id)
            ->whereIn('observation_stage', ['POST', 'EXTERNAL'])
            ->where('status', 'Submitted')
            ->first();

        abort_if(!$response, 404, 'Feedback result not found');

        // Get teacher
        $guru = $this->getGuruNew($authGuru->gn_id);

        // Get exact Feedback form version used
        $form = $this->getPostForm($response->formID);

        // Get evaluator information
        $this->attachFeedbackEvaluator($response);

        // Get saved answers
        $existingAnswers = $this->getPostAnswers($response->responseID, $form);

        return view('newteacher.result-post', compact('guru', 'form', 'response', 'existingAnswers'));
    }

    // Build PDPC TUMS display from saved scores
    private function calculateStoredTumsResults($form, $scores): array
    {
        $results = [];

        foreach ($form->aspects as $aspect) {
            foreach ($aspect->tums as $tums) {
                $points = $tums->tt->flatMap(fn($tt) => $tt->points);
                $totalPoints = $points->count();
                $actionCount = 0;
                $qualityTotal = 0;

                foreach ($points as $point) {
                    $score = (int) ($scores[$point->pointID] ?? 0);
                    $qualityTotal += $score;

                    if ($score > 0) {
                        $actionCount++;
                    }
                }

                $actionScore = $totalPoints > 0 ? round(($actionCount / $totalPoints) * 4) : 0;
                $qualityMean = $totalPoints > 0 ? round($qualityTotal / $totalPoints, 2) : 0;
                $actionPercentage = round(($actionScore / 4) * 100, 2);
                $qualityPercentage = round(($qualityMean / 4) * 100, 2);
                $tumsPercentage = round(($actionPercentage * 0.25) + ($qualityPercentage * 0.75), 2);
                $weight = (float) $tums->wajaran;
                $weightedScore = round(($tumsPercentage * $weight) / 100, 2);

                $results[$tums->tumsID] = [
                    'total_points' => $totalPoints,
                    'action_count' => $actionCount,
                    'quality_total' => $qualityTotal,
                    'action_score' => $actionScore,
                    'quality_mean' => $qualityMean,
                    'action_percentage' => $actionPercentage,
                    'quality_percentage' => $qualityPercentage,
                    'tums_percentage' => $tumsPercentage,
                    'wajaran' => $weight,
                    'weighted_score' => $weightedScore,
                ];
            }
        }

        return $results;
    }

    // Get Feedback form using DB
    private function getPostForm($formID)
    {
        $form = DB::table('post_form')->where('formID', $formID)->first();

        abort_if(!$form, 404, 'Feedback form not found');

        $sections = DB::table('post_section')->where('formID', $form->formID)->orderBy('display_order')->get();

        foreach ($sections as $section) {
            // Get every field from exact saved form
            $fields = DB::table('post_field')->where('sectionID', $section->sectionID)->orderBy('display_order')->get();

            foreach ($fields as $field) {
                $field->options = DB::table('post_field_option')->where('fieldID', $field->fieldID)->orderBy('display_order')->get();
            }

            $section->fields = $fields;
        }

        $form->sections = $sections;

        return $form;
    }

    // Get saved Feedback answers
    private function getPostAnswers($responseID, $form): array
    {
        $answers = DB::table('post_answer')->where('responseID', $responseID)->get();
        $fields = $form->sections->flatMap(fn($section) => $section->fields);
        $existingAnswers = [];

        foreach ($answers as $answer) {
            $field = $fields->firstWhere('fieldID', $answer->fieldID);

            if (!$field) {
                continue;
            }

            if ($field->field_type === 'checkbox') {
                $existingAnswers[$answer->fieldID] = json_decode($answer->answer_value, true) ?? [];
            } else {
                $existingAnswers[$answer->fieldID] = $answer->answer_value;
            }
        }

        return $existingAnswers;
    }

    // Get GN and school using DB
    private function getGuruNew($gnId)
    {
        $guru = DB::table('guru_new')->where('gn_id', $gnId)->first();

        abort_if(!$guru, 404, 'Teacher not found.');

        $guru->school = $guru->schoolID
            ? DB::table('school')->where('schoolID', $guru->schoolID)->first()
            : null;

        return $guru;
    }

    // Attach Feedback evaluator
    private function attachFeedbackEvaluator($response): void
    {
        $response->evaluator_name = '-';
        $response->evaluator_role = $response->observation_stage === 'EXTERNAL' ? 'External Observer' : 'Observer';

        // Observer
        if ($response->observation_stage === 'POST' && $response->observer_id) {
            $observer = DB::table('observer')->where('observer_id', $response->observer_id)->first();

            if ($observer) {
                $teacher = DB::table('teacher')->where('teacherID', $observer->teacherID)->first();
                $response->evaluator_name = $teacher->teacher_name ?? '-';
            }

            return;
        }

        // External observer
        if ($response->observation_stage === 'EXTERNAL' && $response->external_observer_id) {
            $externalObserver = DB::table('external_observer')->where('external_observer_id', $response->external_observer_id)->first();

            if ($externalObserver) {
                $teacher = DB::table('teacher')->where('teacherID', $externalObserver->teacherID)->first();
                $response->evaluator_name = $teacher->teacher_name ?? '-';
            }
        }
    }
}
