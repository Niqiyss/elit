<?php

namespace App\Http\Controllers;

use App\Models\PdpcForm;
use App\Models\PdpcResponse;
use App\Models\PdpcScore;
use App\Models\Observer;
use App\Models\ExternalObserver;
use App\Models\GuruNew;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PdpcObservationController extends Controller
{
    // Create observation
    public function create(Request $request, $gn_id)
    {
        $teacherID = Auth::guard('teacher')->id();

        $observer = Observer::where('teacherID', $teacherID)->first();

        $externalObserver = ExternalObserver::where('teacherID', $teacherID)->first();

        if ($request->routeIs('observer.pdpc.create')) {
            abort_if(!$observer, 403, 'You are not registered as an observer.');

            $role = 'observer';
            $stage = 'POST';
        } else {
            abort_if(!$externalObserver, 403, 'You are not registered as an external observer.');

            $role = 'external';
            $stage = 'EXTERNAL';
        }

        $guru = GuruNew::with('school')
            ->where('gn_id', $gn_id)
            ->firstOrFail();

        $assigned = $role === 'observer'
            ? DB::table('observer_assignment')
            ->where('gn_id', $gn_id)
            ->where('observer_id', $observer->observer_id)
            ->exists()
            : DB::table('observer_assignment')
            ->where('gn_id', $gn_id)
            ->where('external_observer_id', $externalObserver->external_observer_id)
            ->exists();

        abort_if(!$assigned, 403, 'This teacher is not assigned to you.');

        // Get existing draft
        $draftQuery = PdpcResponse::where('gn_id', $gn_id)
            ->where('observation_stage', $stage)
            ->where('status', 'Draft');

        if ($role === 'observer') {
            $draftQuery->where('observer_id', $observer->observer_id);
        } else {
            $draftQuery->where('external_observer_id', $externalObserver->external_observer_id);
        }

        $draft = $draftQuery->latest('responseID')->first();

        if ($draft) {
            return redirect()->route(
                $role === 'observer'
                    ? 'observer.pdpc.edit'
                    : 'external.pdpc.edit',
                $draft->responseID
            );
        }

        // Prevent new EXTERNAL after PASS
        if ($stage === 'EXTERNAL') {
            $latestSubmitted = PdpcResponse::where('gn_id', $gn_id)
                ->where('observation_stage', 'EXTERNAL')
                ->where('status', 'Submitted')
                ->orderByDesc('attempt_no')
                ->orderByDesc('responseID')
                ->first();

            if ($latestSubmitted && $latestSubmitted->result === 'PASS') {
                return redirect()
                    ->route('external.manage', $gn_id)
                    ->with('error', 'The External Observation has already passed.');
            }
        }

        // Prevent duplicate POST PDPC
        if ($stage === 'POST') {
            $submitted = PdpcResponse::where('gn_id', $gn_id)
                ->where('observation_stage', 'POST')
                ->where('status', 'Submitted')
                ->exists();

            if ($submitted) {
                return redirect()
                    ->route('observer.manage', $gn_id)
                    ->with('error', 'The Post Observation has already been submitted.');
            }
        }

        $form = $this->getActiveForm();

        return view('pdpc-observation.form', compact(
            'form',
            'guru',
            'gn_id',
            'role',
            'stage'
        ));
    }


    // Store observation
    public function store(Request $request, $gn_id)
    {
        $teacherID = Auth::guard('teacher')->id();

        $observer = Observer::where('teacherID', $teacherID)->first();

        $externalObserver = ExternalObserver::where('teacherID', $teacherID)->first();

        if ($request->routeIs('observer.pdpc.store')) {
            abort_if(!$observer, 403, 'You are not registered as an observer.');

            $role = 'observer';
            $stage = 'POST';
        } else {
            abort_if(!$externalObserver, 403, 'You are not registered as an external observer.');

            $role = 'external';
            $stage = 'EXTERNAL';
        }

        GuruNew::where('gn_id', $gn_id)->firstOrFail();

        $assigned = $role === 'observer'
            ? DB::table('observer_assignment')
            ->where('gn_id', $gn_id)
            ->where('observer_id', $observer->observer_id)
            ->exists()
            : DB::table('observer_assignment')
            ->where('gn_id', $gn_id)
            ->where('external_observer_id', $externalObserver->external_observer_id)
            ->exists();

        abort_if(!$assigned, 403, 'This teacher is not assigned to you.');

        $form = $this->getActiveForm();

        $this->validateObservation($request, $form);

        DB::transaction(function () use (
            $request,
            $gn_id,
            $form,
            $observer,
            $externalObserver,
            $role,
            $stage
        ) {
            $attemptNo = (PdpcResponse::where('gn_id', $gn_id)
                ->where('observation_stage', $stage)
                ->max('attempt_no') ?? 0) + 1;

            $calculation = $this->calculateResult($request, $form);

            $response = PdpcResponse::create([
                'formID' => $form->formID,
                'gn_id' => $gn_id,
                'observer_id' => $role === 'observer' ? $observer->observer_id : null,
                'external_observer_id' => $role === 'external' ? $externalObserver->external_observer_id : null,
                'observation_stage' => $stage,
                'attempt_no' => $attemptNo,
                'class_name' => $request->class_name,
                'subject_name' => $request->subject_name,
                'observation_date' => $request->observation_date,
                'observation_time' => $request->observation_time,
                'total_score' => $calculation['total_score'],
                'percentage' => $calculation['percentage'],
                'achievement_level' => $calculation['achievement_level'],
                'result' => $stage === 'EXTERNAL' ? $calculation['result'] : null,
                'status' => $request->submit_action,
            ]);

            $this->saveScores($request, $form, $response);
        });

        return redirect()
            ->route(
                $role === 'observer'
                    ? 'observer.manage'
                    : 'external.manage',
                $gn_id
            )
            ->with(
                'success',
                $request->submit_action === 'Submitted'
                    ? 'PDPC observation submitted successfully.'
                    : 'PDPC observation draft saved successfully.'
            );
    }


    // Edit draft
    public function edit(Request $request, $responseID)
    {
        [
            $role,
            $stage,
            $observer,
            $externalObserver
        ] = $this->resolveRole($request);

        $response = PdpcResponse::where('responseID', $responseID)
            ->where('observation_stage', $stage)
            ->firstOrFail();

        abort_if(
            $response->status === 'Submitted',
            403,
            'Submitted observations cannot be edited.'
        );

        $this->checkOwnership(
            $response,
            $role,
            $observer,
            $externalObserver
        );

        $guru = GuruNew::with('school')
            ->where('gn_id', $response->gn_id)
            ->firstOrFail();

        $gn_id = $response->gn_id;

        $form = $this->getForm($response->formID);

        $existingScores = PdpcScore::where('responseID', $response->responseID)
            ->pluck('score', 'pointID')
            ->toArray();

        return view('pdpc-observation.edit', compact(
            'form',
            'guru',
            'gn_id',
            'role',
            'stage',
            'response',
            'existingScores'
        ));
    }


    // Update draft
    public function update(Request $request, $responseID)
    {
        [
            $role,
            $stage,
            $observer,
            $externalObserver
        ] = $this->resolveRole($request);

        $response = PdpcResponse::where('responseID', $responseID)
            ->where('observation_stage', $stage)
            ->firstOrFail();

        abort_if(
            $response->status === 'Submitted',
            403,
            'Submitted observations cannot be edited.'
        );

        $this->checkOwnership(
            $response,
            $role,
            $observer,
            $externalObserver
        );

        $form = $this->getForm($response->formID);

        $this->validateObservation($request, $form);

        DB::transaction(function () use (
            $request,
            $response,
            $form,
            $stage
        ) {
            $calculation = $this->calculateResult($request, $form);

            $response->update([
                'class_name' => $request->class_name,
                'subject_name' => $request->subject_name,
                'observation_date' => $request->observation_date,
                'observation_time' => $request->observation_time,
                'total_score' => $calculation['total_score'],
                'percentage' => $calculation['percentage'],
                'achievement_level' => $calculation['achievement_level'],
                'result' => $stage === 'EXTERNAL' ? $calculation['result'] : null,
                'status' => $request->submit_action,
            ]);

            PdpcScore::where('responseID', $response->responseID)->delete();

            $this->saveScores($request, $form, $response);
        });

        return redirect()
            ->route(
                $role === 'observer'
                    ? 'observer.manage'
                    : 'external.manage',
                $response->gn_id
            )
            ->with(
                'success',
                $request->submit_action === 'Submitted'
                    ? 'PDPC observation submitted successfully.'
                    : 'PDPC observation draft updated successfully.'
            );
    }


    // View submitted observation
    public function show(Request $request, $responseID)
    {
        [
            $role,
            $stage,
            $observer,
            $externalObserver
        ] = $this->resolveRole($request);

        $response = PdpcResponse::where('responseID', $responseID)
            ->where('observation_stage', $stage)
            ->where('status', 'Submitted')
            ->firstOrFail();

        $this->checkOwnership(
            $response,
            $role,
            $observer,
            $externalObserver
        );

        $guru = GuruNew::with('school')
            ->where('gn_id', $response->gn_id)
            ->firstOrFail();

        $form = $this->getForm($response->formID);

        $scores = PdpcScore::where('responseID', $response->responseID)
            ->pluck('score', 'pointID');

        $tumsResults = $this->calculateStoredTumsResults(
            $form,
            $scores
        );

        return view('pdpc-observation.view', compact(
            'form',
            'guru',
            'response',
            'role',
            'stage',
            'scores',
            'tumsResults'
        ));
    }


    // Get active form
    private function getActiveForm()
    {
        $form = PdpcForm::where('status', 'Active')
            ->with([
                'aspects.tums.tt.points',
                'aspects.tums.rubrics',
            ])
            ->latest('formID')
            ->first();

        abort_if(!$form, 404, 'No active PDPC form found.');

        return $form;
    }


    // Get response form
    private function getForm($formID)
    {
        return PdpcForm::with([
            'aspects.tums.tt.points',
            'aspects.tums.rubrics',
        ])
            ->where('formID', $formID)
            ->firstOrFail();
    }


    // Validate observation
    private function validateObservation(Request $request, $form)
    {
        $isSubmit = $request->submit_action === 'Submitted';

        $rules = [
            'class_name' => $isSubmit ? 'required|string|max:100' : 'nullable|string|max:100',
            'subject_name' => $isSubmit ? 'required|string|max:100' : 'nullable|string|max:100',
            'observation_date' => $isSubmit ? 'required|date' : 'nullable|date',
            'observation_time' => $isSubmit ? 'required|date_format:H:i' : 'nullable|date_format:H:i',
            'submit_action' => 'required|in:Draft,Submitted',
        ];

        foreach ($form->aspects as $aspect) {
            foreach ($aspect->tums as $tums) {
                foreach ($tums->tt as $tt) {
                    foreach ($tt->points as $point) {
                        $key = 'scores.' . $point->pointID;

                        $rules[$key] = $isSubmit
                            ? 'required|integer|min:0|max:4'
                            : 'nullable|integer|min:0|max:4';
                    }
                }
            }
        }

        $request->validate($rules);
    }


    // Calculate final observation result
    private function calculateResult(Request $request, $form)
    {
        if ($request->submit_action === 'Draft') {
            return [
                'total_score' => null,
                'percentage' => null,
                'achievement_level' => null,
                'result' => null,
                'tums_results' => [],
            ];
        }

        $totalQualityScore = 0;
        $overallTotal = 0;
        $tumsResults = [];

        foreach ($form->aspects as $aspect) {
            foreach ($aspect->tums as $tums) {
                $points = $tums->tt->flatMap(fn($tt) => $tt->points);

                $totalPoints = $points->count();
                $actionCount = 0;
                $qualityTotal = 0;

                foreach ($points as $point) {
                    $score = (int) $request->input('scores.' . $point->pointID);

                    $qualityTotal += $score;

                    if ($score > 0) {
                        $actionCount++;
                    }
                }

                $actionScore = $totalPoints > 0
                    ? round(($actionCount / $totalPoints) * 4)
                    : 0;

                $qualityMean = $totalPoints > 0
                    ? round($qualityTotal / $totalPoints, 2)
                    : 0;

                $actionPercentage = round(($actionScore / 4) * 100, 2);

                $qualityPercentage = round(($qualityMean / 4) * 100, 2);

                $tumsPercentage = round(
                    ($actionPercentage * 0.25)
                        +
                        ($qualityPercentage * 0.75),
                    2
                );

                $weight = (float) $tums->wajaran;

                $weightedScore = round(
                    ($tumsPercentage * $weight) / 100,
                    2
                );

                $overallTotal += $weightedScore;

                $totalQualityScore += $qualityTotal;

                $tumsResults[$tums->tumsID] = [
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

        $overallTotal = round($overallTotal, 2);

        // Determine achievement level
        $achievementLevel = match (true) {
            $overallTotal >= 90 => 'Excellent',
            $overallTotal >= 80 => 'Good',
            $overallTotal >= 50 => 'Satisfactory',
            $overallTotal >= 20 => 'Weak',
            default => 'Very Weak',
        };

        // Determine PASS or REPEAT
        $result = $overallTotal >= 85
            ? 'PASS'
            : 'REPEAT';

        return [
            'total_score' => $totalQualityScore,
            'percentage' => $overallTotal,
            'achievement_level' => $achievementLevel,
            'result' => $result,
            'tums_results' => $tumsResults,
        ];
    }


    // Calculate TUMS result from saved scores
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

                $actionScore = $totalPoints > 0
                    ? round(($actionCount / $totalPoints) * 4)
                    : 0;

                $qualityMean = $totalPoints > 0
                    ? round($qualityTotal / $totalPoints, 2)
                    : 0;

                $actionPercentage = round(($actionScore / 4) * 100, 2);

                $qualityPercentage = round(($qualityMean / 4) * 100, 2);

                $tumsPercentage = round(
                    ($actionPercentage * 0.25)
                        +
                        ($qualityPercentage * 0.75),
                    2
                );

                $weight = (float) $tums->wajaran;

                $weightedScore = round(
                    ($tumsPercentage * $weight) / 100,
                    2
                );

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


    // Save individual scores
    private function saveScores(Request $request, $form, PdpcResponse $response)
    {
        foreach ($form->aspects as $aspect) {
            foreach ($aspect->tums as $tums) {
                foreach ($tums->tt as $tt) {
                    foreach ($tt->points as $point) {
                        $score = $request->input('scores.' . $point->pointID);

                        if ($score === null || $score === '') {
                            continue;
                        }

                        PdpcScore::create([
                            'responseID' => $response->responseID,
                            'pointID' => $point->pointID,
                            'score' => (int) $score,
                        ]);
                    }
                }
            }
        }
    }


    // Resolve evaluator role
    private function resolveRole(Request $request)
    {
        $teacherID = Auth::guard('teacher')->id();

        $observer = Observer::where('teacherID', $teacherID)->first();

        $externalObserver = ExternalObserver::where('teacherID', $teacherID)->first();

        if ($request->routeIs('observer.pdpc.*')) {
            abort_if(!$observer, 403, 'You are not registered as an observer.');

            return [
                'observer',
                'POST',
                $observer,
                $externalObserver,
            ];
        }

        abort_if(
            !$externalObserver,
            403,
            'You are not registered as an external observer.'
        );

        return [
            'external',
            'EXTERNAL',
            $observer,
            $externalObserver,
        ];
    }


    // Check response ownership
    private function checkOwnership(
        PdpcResponse $response,
        $role,
        $observer,
        $externalObserver
    ) {
        if ($role === 'observer') {
            abort_if(
                $response->observer_id != $observer->observer_id,
                403,
                'You are not allowed to access this observation.'
            );
        } else {
            abort_if(
                $response->external_observer_id != $externalObserver->external_observer_id,
                403,
                'You are not allowed to access this observation.'
            );
        }
    }
}
