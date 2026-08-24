<?php

namespace App\Http\Controllers\Observer;

use App\Http\Controllers\Controller;
use App\Models\PreForm;
use App\Models\PreResponse;
use App\Models\PreScore;
use App\Models\PreSectionComment;
use App\Models\Observer;
use App\Models\GuruNew;
use App\Models\AuditObservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PreObservationController extends Controller
{
    // Show new PRE form
    public function create($gn_id)
    {
        $teacherID = Auth::guard('teacher')->id();

        $observer = Observer::where(
            'teacherID',
            $teacherID
        )->first();

        abort_if(
            !$observer,
            403,
            'You are not registered as an observer.'
        );

        $guru = GuruNew::with('school')
            ->where('gn_id', $gn_id)
            ->firstOrFail();

        $assigned = DB::table('observer_assignment')
            ->where('gn_id', $gn_id)
            ->where(
                'observer_id',
                $observer->observer_id
            )
            ->exists();

        abort_if(
            !$assigned,
            403,
            'This teacher is not assigned to you.'
        );

        $existingResponse = PreResponse::where(
            'gn_id',
            $gn_id
        )
            ->where(
                'observer_id',
                $observer->observer_id
            )
            ->where(
                'observation_stage',
                'PRE'
            )
            ->latest('responseID')
            ->first();

        if (
            $existingResponse &&
            $existingResponse->status === 'Draft'
        ) {
            return redirect()->route(
                'observer.pre.edit',
                $existingResponse->responseID
            );
        }

        if (
            $existingResponse &&
            $existingResponse->status === 'Submitted'
        ) {
            return redirect()
                ->route(
                    'observer.manage',
                    $gn_id
                )
                ->with(
                    'error',
                    'This Pre-Observation has already been submitted.'
                );
        }

        $form = $this->getActiveForm();

        return view(
            'pre-observation.form',
            compact(
                'form',
                'guru',
                'gn_id'
            )
        );
    }


    // Store new PRE response
    public function store(
        Request $request,
        $gn_id
    ) {
        $teacherID = Auth::guard('teacher')->id();

        $observer = Observer::where(
            'teacherID',
            $teacherID
        )->first();

        abort_if(
            !$observer,
            403,
            'You are not registered as an observer.'
        );

        GuruNew::where(
            'gn_id',
            $gn_id
        )->firstOrFail();

        $assigned = DB::table('observer_assignment')
            ->where('gn_id', $gn_id)
            ->where(
                'observer_id',
                $observer->observer_id
            )
            ->exists();

        abort_if(
            !$assigned,
            403,
            'This teacher is not assigned to you.'
        );

        $form = $this->getActiveForm();

        $this->validateForm(
            $request,
            $form
        );

        DB::transaction(function () use (
            $request,
            $gn_id,
            $observer,
            $form
        ) {
            $existingResponse = PreResponse::where(
                'gn_id',
                $gn_id
            )
                ->where(
                    'observer_id',
                    $observer->observer_id
                )
                ->where(
                    'observation_stage',
                    'PRE'
                )
                ->first();

            abort_if(
                $existingResponse,
                409,
                'A Pre-Observation response already exists.'
            );

            $result = $this->calculateResult(
                $request,
                $form
            );

            $response = PreResponse::create([
                'formID' => $form->formID,
                'gn_id' => $gn_id,
                'observer_id' => $observer->observer_id,
                'observation_stage' => 'PRE',
                'class_name' => $request->class_name,
                'subject_name' => $request->subject_name,
                'observation_date' => $request->observation_date,
                'total_score' => $result['total_score'],
                'percentage' => $result['percentage'],
                'achievement_level' => $result['achievement_level'],
                'other_comment' => $request->other_comment,
                'status' => $request->submit_action,
            ]);

            $this->saveScores(
                $request,
                $form,
                $response
            );

            $this->saveSectionComments(
                $request,
                $form,
                $response
            );

            if ($request->submit_action === 'Submitted') {

                AuditObservation::create([
                    'teacherID' => $observer->teacherID,
                    'gn_id' => $gn_id,
                    'role' => 'Observer',
                    'stage' => 'PRE',
                    'form_name' => $form->form_name,
                    'action' => 'Submitted',
                    'audit_date' => now()->toDateString(),
                    'audit_time' => now()->format('H:i:s'),
                ]);
            }
        });

        return redirect()
            ->route(
                'observer.manage',
                $gn_id
            )
            ->with(
                'success',
                $request->submit_action === 'Submitted'
                    ? 'Pre-Observation submitted successfully.'
                    : 'Pre-Observation draft saved successfully.'
            );
    }


    // Show PRE draft edit form
    public function edit($responseID)
    {
        $teacherID = Auth::guard('teacher')->id();

        $observer = Observer::where(
            'teacherID',
            $teacherID
        )->first();

        abort_if(
            !$observer,
            403,
            'You are not registered as an observer.'
        );

        $response = PreResponse::where(
            'responseID',
            $responseID
        )
            ->where(
                'observation_stage',
                'PRE'
            )
            ->firstOrFail();

        abort_if(
            $response->observer_id != $observer->observer_id,
            403,
            'You are not allowed to edit this response.'
        );

        abort_if(
            $response->status === 'Submitted',
            403,
            'Submitted Pre-Observation cannot be edited.'
        );

        $assigned = DB::table('observer_assignment')
            ->where(
                'gn_id',
                $response->gn_id
            )
            ->where(
                'observer_id',
                $observer->observer_id
            )
            ->exists();

        abort_if(
            !$assigned,
            403,
            'This teacher is not assigned to you.'
        );

        $guru = GuruNew::with('school')
            ->where(
                'gn_id',
                $response->gn_id
            )
            ->firstOrFail();

        $gn_id = $response->gn_id;

        $form = $this->getForm(
            $response->formID
        );

        $existingScores = PreScore::where(
            'responseID',
            $response->responseID
        )
            ->pluck(
                'score',
                'criteriaID'
            )
            ->toArray();

        $existingComments = PreSectionComment::where(
            'responseID',
            $response->responseID
        )
            ->pluck(
                'comment',
                'sectionID'
            )
            ->toArray();

        return view(
            'pre-observation.edit',
            compact(
                'form',
                'guru',
                'gn_id',
                'response',
                'existingScores',
                'existingComments'
            )
        );
    }


    // Update PRE draft
    public function update(
        Request $request,
        $responseID
    ) {
        $teacherID = Auth::guard('teacher')->id();

        $observer = Observer::where(
            'teacherID',
            $teacherID
        )->first();

        abort_if(
            !$observer,
            403,
            'You are not registered as an observer.'
        );

        $response = PreResponse::where(
            'responseID',
            $responseID
        )
            ->where(
                'observation_stage',
                'PRE'
            )
            ->firstOrFail();

        abort_if(
            $response->observer_id != $observer->observer_id,
            403,
            'You are not allowed to edit this response.'
        );

        abort_if(
            $response->status === 'Submitted',
            403,
            'Submitted Pre-Observation cannot be edited.'
        );

        $assigned = DB::table('observer_assignment')
            ->where(
                'gn_id',
                $response->gn_id
            )
            ->where(
                'observer_id',
                $observer->observer_id
            )
            ->exists();

        abort_if(
            !$assigned,
            403,
            'This teacher is not assigned to you.'
        );

        $form = $this->getForm(
            $response->formID
        );

        $this->validateForm(
            $request,
            $form
        );

        DB::transaction(function () use (
            $request,
            $response,
            $form,
            $observer
        ) {
            $result = $this->calculateResult(
                $request,
                $form
            );

            $response->class_name =
                $request->class_name;

            $response->subject_name =
                $request->subject_name;

            $response->observation_date =
                $request->observation_date;

            $response->other_comment =
                $request->other_comment;

            $response->total_score =
                $result['total_score'];

            $response->percentage =
                $result['percentage'];

            $response->achievement_level =
                $result['achievement_level'];

            $response->status =
                $request->submit_action;

            $response->save();


            // Replace previous scores
            PreScore::where(
                'responseID',
                $response->responseID
            )->delete();

            $this->saveScores(
                $request,
                $form,
                $response
            );


            // Replace previous section comments
            PreSectionComment::where(
                'responseID',
                $response->responseID
            )->delete();

            $this->saveSectionComments(
                $request,
                $form,
                $response
            );
            if ($request->submit_action === 'Submitted') {

                AuditObservation::create([
                    'teacherID' => $observer->teacherID,
                    'gn_id' => $response->gn_id,
                    'role' => 'Observer',
                    'stage' => 'PRE',
                    'form_name' => $form->form_name,
                    'action' => 'Submitted',
                    'audit_date' => now()->toDateString(),
                    'audit_time' => now()->format('H:i:s'),
                ]);
            }
        });

        return redirect()
            ->route(
                'observer.manage',
                $response->gn_id
            )
            ->with(
                'success',
                $request->submit_action === 'Submitted'
                    ? 'Pre-Observation submitted successfully.'
                    : 'Pre-Observation draft updated successfully.'
            );
    }

    // Show submitted PRE observation
    public function show($responseID)
    {
        $teacherID = Auth::guard('teacher')->id();

        $observer = Observer::where(
            'teacherID',
            $teacherID
        )->first();

        abort_if(
            !$observer,
            403,
            'You are not registered as an observer.'
        );

        $response = PreResponse::where(
            'responseID',
            $responseID
        )
            ->where(
                'observation_stage',
                'PRE'
            )
            ->where(
                'status',
                'Submitted'
            )
            ->firstOrFail();

        abort_if(
            $response->observer_id != $observer->observer_id,
            403,
            'You are not allowed to view this response.'
        );

        $guru = GuruNew::with('school')
            ->where(
                'gn_id',
                $response->gn_id
            )
            ->firstOrFail();

        $form = PreForm::where(
            'formID',
            $response->formID
        )
            ->with([
                'sections' => function ($query) {
                    $query->orderBy('display_order');
                },

                'sections.criteria' => function ($query) {
                    $query->orderBy('display_order');
                },
            ])
            ->firstOrFail();

        $scores = PreScore::where(
            'responseID',
            $response->responseID
        )
            ->pluck(
                'score',
                'criteriaID'
            );

        $sectionComments = PreSectionComment::where(
            'responseID',
            $response->responseID
        )
            ->pluck(
                'comment',
                'sectionID'
            );

        return view(
            'pre-observation.view',
            compact(
                'form',
                'guru',
                'response',
                'scores',
                'sectionComments'
            )
        );
    }


    // Get active PRE form
    private function getActiveForm()
    {
        $form = PreForm::where(
            'status',
            'Active'
        )
            ->with([
                'sections' => function ($query) {
                    $query->orderBy(
                        'display_order'
                    );
                },

                'sections.criteria' => function ($query) {
                    $query
                        ->where(
                            'status',
                            'Active'
                        )
                        ->orderBy(
                            'display_order'
                        );
                },
            ])
            ->first();

        abort_if(
            !$form,
            404,
            'No active Pre-Observation form found.'
        );

        return $form;
    }


    // Get form used by existing response
    private function getForm($formID)
    {
        return PreForm::where(
            'formID',
            $formID
        )
            ->with([
                'sections' => function ($query) {
                    $query->orderBy(
                        'display_order'
                    );
                },

                'sections.criteria' => function ($query) {
                    $query
                        ->where(
                            'status',
                            'Active'
                        )
                        ->orderBy(
                            'display_order'
                        );
                },
            ])
            ->firstOrFail();
    }


    // Validate PRE form
    private function validateForm(
        Request $request,
        $form
    ) {
        $isSubmit =
            $request->submit_action === 'Submitted';

        $rules = [
            'class_name' => $isSubmit
                ? [
                    'required',
                    'string',
                    'max:100',
                ]
                : [
                    'nullable',
                    'string',
                    'max:100',
                ],

            'subject_name' => $isSubmit
                ? [
                    'required',
                    'string',
                    'max:100',
                ]
                : [
                    'nullable',
                    'string',
                    'max:100',
                ],

            'observation_date' => $isSubmit
                ? [
                    'required',
                    'date',
                ]
                : [
                    'nullable',
                    'date',
                ],

            'other_comment' => [
                'nullable',
                'string',
            ],

            'submit_action' => [
                'required',
                'in:Draft,Submitted',
            ],
        ];


        foreach ($form->sections as $section) {

            foreach ($section->criteria as $criteria) {

                $key =
                    'scores.' .
                    $criteria->criteriaID;

                $rules[$key] = $isSubmit
                    ? [
                        'required',
                        'integer',
                        'min:1',
                        'max:5',
                    ]
                    : [
                        'nullable',
                        'integer',
                        'min:1',
                        'max:5',
                    ];
            }


            $rules['section_comments.' .
                $section->sectionID] = $isSubmit
                ? [
                    'required',
                    'string',
                ]
                : [
                    'nullable',
                    'string',
                ];
        }


        $request->validate($rules);
    }


    // Calculate PRE result
    private function calculateResult(
        Request $request,
        $form
    ) {
        if (
            $request->submit_action === 'Draft'
        ) {
            return [
                'total_score' => null,
                'percentage' => null,
                'achievement_level' => null,
            ];
        }


        $totalScore = 0;
        $criteriaCount = 0;


        foreach ($form->sections as $section) {

            foreach ($section->criteria as $criteria) {

                $score = (int) $request->input(
                    'scores.' .
                        $criteria->criteriaID
                );

                $totalScore += $score;

                $criteriaCount++;
            }
        }


        $maxScore =
            $criteriaCount * 5;


        $percentage =
            $maxScore > 0
            ? round(
                ($totalScore / $maxScore) * 100,
                2
            )
            : 0;


        if ($percentage < 40) {

            $level = 'Weak';
        } elseif ($percentage < 60) {

            $level = 'Satisfactory';
        } elseif ($percentage < 80) {

            $level = 'Good';
        } elseif ($percentage < 90) {

            $level = 'Very Good';
        } else {

            $level = 'Excellent';
        }


        return [
            'total_score' =>
            $totalScore,

            'percentage' =>
            $percentage,

            'achievement_level' =>
            $level,
        ];
    }


    // Save criteria scores
    private function saveScores(
        Request $request,
        $form,
        PreResponse $response
    ) {
        foreach ($form->sections as $section) {

            foreach ($section->criteria as $criteria) {

                $score =
                    $request->input(
                        'scores.' .
                            $criteria->criteriaID
                    );


                if ($score !== null) {

                    PreScore::create([
                        'responseID' =>
                        $response->responseID,

                        'criteriaID' =>
                        $criteria->criteriaID,

                        'score' =>
                        $score,
                    ]);
                }
            }
        }
    }


    // Save one comment per section
    private function saveSectionComments(
        Request $request,
        $form,
        PreResponse $response
    ) {
        foreach ($form->sections as $section) {

            $comment =
                $request->input(
                    'section_comments.' .
                        $section->sectionID
                );


            if (
                $comment !== null &&
                trim($comment) !== ''
            ) {
                PreSectionComment::create([
                    'responseID' =>
                    $response->responseID,

                    'sectionID' =>
                    $section->sectionID,

                    'comment' =>
                    $comment,
                ]);
            }
        }
    }
}
