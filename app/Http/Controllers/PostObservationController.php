<?php

namespace App\Http\Controllers;

use App\Models\PostForm;
use App\Models\PostResponse;
use App\Models\PostAnswer;
use App\Models\Observer;
use App\Models\ExternalObserver;
use App\Models\GuruNew;
use App\Models\AuditObservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostObservationController extends Controller
{
    // Show new feedback form
    public function create(Request $request, $gn_id)
    {
        $teacherID = Auth::guard('teacher')->id();

        $observer = Observer::where(
            'teacherID',
            $teacherID
        )->first();

        $externalObserver = ExternalObserver::where(
            'teacherID',
            $teacherID
        )->first();


        // Determine role and stage
        if ($request->routeIs('observer.post.create')) {

            abort_if(
                !$observer,
                403,
                'You are not registered as an observer.'
            );

            $role = 'observer';
            $stage = 'POST';
        } else {

            abort_if(
                !$externalObserver,
                403,
                'You are not registered as an external observer.'
            );

            $role = 'external';
            $stage = 'EXTERNAL';
        }


        // Get teacher
        $guru = GuruNew::with('school')
            ->where(
                'gn_id',
                $gn_id
            )
            ->firstOrFail();


        // Check assignment
        if ($role === 'observer') {

            $assigned = DB::table('observer_assignment')
                ->where(
                    'gn_id',
                    $gn_id
                )
                ->where(
                    'observer_id',
                    $observer->observer_id
                )
                ->exists();
        } else {

            $assigned = DB::table('observer_assignment')
                ->where(
                    'gn_id',
                    $gn_id
                )
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


        // Check existing response
        if ($role === 'observer') {

            $existingResponse = PostResponse::where(
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

            $existingResponse = PostResponse::where(
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


        // Redirect draft to edit page
        if (
            $existingResponse &&
            $existingResponse->status === 'Draft'
        ) {
            return redirect()->route(
                $role === 'observer'
                    ? 'observer.post.edit'
                    : 'external.post.edit',
                $existingResponse->responseID
            );
        }


        // Prevent duplicate submitted response
        if (
            $existingResponse &&
            $existingResponse->status === 'Submitted'
        ) {
            return redirect()
                ->route(
                    $role === 'observer'
                        ? 'observer.manage'
                        : 'external.manage',
                    $gn_id
                )
                ->with(
                    'error',
                    'This feedback observation has already been submitted.'
                );
        }


        // Get active form
        $form = $this->getActiveForm();


        return view(
            'post-observation.form',
            compact(
                'form',
                'guru',
                'gn_id',
                'role',
                'stage'
            )
        );
    }


    // Store new feedback response
    public function store(Request $request, $gn_id)
    {
        $teacherID = Auth::guard('teacher')->id();

        $observer = Observer::where(
            'teacherID',
            $teacherID
        )->first();

        $externalObserver = ExternalObserver::where(
            'teacherID',
            $teacherID
        )->first();


        // Determine role and stage
        if ($request->routeIs('observer.post.store')) {

            abort_if(
                !$observer,
                403,
                'You are not registered as an observer.'
            );

            $role = 'observer';
            $stage = 'POST';
        } else {

            abort_if(
                !$externalObserver,
                403,
                'You are not registered as an external observer.'
            );

            $role = 'external';
            $stage = 'EXTERNAL';
        }


        // Make sure teacher exists
        GuruNew::where(
            'gn_id',
            $gn_id
        )->firstOrFail();


        // Check assignment
        if ($role === 'observer') {

            $assigned = DB::table('observer_assignment')
                ->where(
                    'gn_id',
                    $gn_id
                )
                ->where(
                    'observer_id',
                    $observer->observer_id
                )
                ->exists();
        } else {

            $assigned = DB::table('observer_assignment')
                ->where(
                    'gn_id',
                    $gn_id
                )
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


        // Get active form
        $form = $this->getActiveForm();


        // Validate form
        $this->validateForm(
            $request,
            $form
        );


        DB::transaction(function () use (
            $request,
            $gn_id,
            $form,
            $observer,
            $externalObserver,
            $role,
            $stage
        ) {

            // Prevent duplicate response
            if ($role === 'observer') {

                $existingResponse = PostResponse::where(
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

                $existingResponse = PostResponse::where(
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


            abort_if(
                $existingResponse,
                409,
                'A feedback response already exists for this teacher.'
            );


            // Create response
            $response = new PostResponse();

            $response->gn_id =
                $gn_id;

            $response->formID =
                $form->formID;

            $response->observation_stage =
                $stage;

            $response->observer_id =
                $role === 'observer'
                ? $observer->observer_id
                : null;

            $response->external_observer_id =
                $role === 'external'
                ? $externalObserver->external_observer_id
                : null;

            $response->class_name =
                $request->class_name;

            $response->subject_name =
                $request->subject_name;

            $response->observation_date =
                $request->observation_date;

            $response->observation_time =
                $request->observation_time;

            $response->status =
                $request->submit_action;

            $response->save();


            // Save answers
            $this->saveAnswers(
                $request,
                $form,
                $response
            );

            if ($request->submit_action === 'Submitted') {

                AuditObservation::create([
                    'teacherID' => $role === 'observer'
                        ? $observer->teacherID
                        : $externalObserver->teacherID,

                    'gn_id' => $gn_id,

                    'role' => $role === 'observer'
                        ? 'Observer'
                        : 'External Observer',

                    'stage' => $stage,

                    'form_name' => $form->form_name,

                    'action' => 'Submitted',

                    'audit_date' => now()->toDateString(),

                    'audit_time' => now()->format('H:i:s'),
                ]);
            }
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
                    ? 'Feedback observation submitted successfully.'
                    : 'Draft saved successfully.'
            );
    }


    // Show draft edit form
    public function edit(Request $request, $responseID)
    {
        $teacherID = Auth::guard('teacher')->id();

        $observer = Observer::where(
            'teacherID',
            $teacherID
        )->first();

        $externalObserver = ExternalObserver::where(
            'teacherID',
            $teacherID
        )->first();


        // Determine role and stage
        if ($request->routeIs('observer.post.edit')) {

            abort_if(
                !$observer,
                403,
                'You are not registered as an observer.'
            );

            $role = 'observer';
            $stage = 'POST';
        } else {

            abort_if(
                !$externalObserver,
                403,
                'You are not registered as an external observer.'
            );

            $role = 'external';
            $stage = 'EXTERNAL';
        }


        // Get response
        $response = PostResponse::where(
            'responseID',
            $responseID
        )
            ->where(
                'observation_stage',
                $stage
            )
            ->firstOrFail();


        // Check ownership
        if ($role === 'observer') {

            abort_if(
                $response->observer_id !=
                    $observer->observer_id,
                403,
                'You are not allowed to edit this response.'
            );
        } else {

            abort_if(
                $response->external_observer_id !=
                    $externalObserver->external_observer_id,
                403,
                'You are not allowed to edit this response.'
            );
        }


        // Submitted response cannot be edited
        abort_if(
            $response->status === 'Submitted',
            403,
            'Submitted feedback cannot be edited.'
        );


        // Get teacher
        $guru = GuruNew::with('school')
            ->where(
                'gn_id',
                $response->gn_id
            )
            ->firstOrFail();

        $gn_id =
            $response->gn_id;


        // Get current active fields from form
        $form = $this->getForm(
            $response->formID
        );


        // Get saved answers
        $answers = PostAnswer::where(
            'responseID',
            $response->responseID
        )->get();

        $existingAnswers = [];


        // Prepare saved answers
        foreach ($answers as $answer) {

            $field = $form->sections
                ->flatMap(function ($section) {
                    return $section->fields;
                })
                ->firstWhere(
                    'fieldID',
                    $answer->fieldID
                );


            // Field may have been made inactive by Admin
            if (!$field) {
                continue;
            }


            if ($field->field_type === 'checkbox') {

                $existingAnswers[$answer->fieldID] =
                    json_decode(
                        $answer->answer_value,
                        true
                    ) ?? [];
            } else {

                $existingAnswers[$answer->fieldID] =
                    $answer->answer_value;
            }
        }


        return view(
            'post-observation.edit',
            compact(
                'form',
                'guru',
                'gn_id',
                'role',
                'stage',
                'response',
                'existingAnswers'
            )
        );
    }


    // Update draft response
    public function update(Request $request, $responseID)
    {
        $teacherID = Auth::guard('teacher')->id();

        $observer = Observer::where(
            'teacherID',
            $teacherID
        )->first();

        $externalObserver = ExternalObserver::where(
            'teacherID',
            $teacherID
        )->first();


        // Determine role and stage
        if ($request->routeIs('observer.post.update')) {

            abort_if(
                !$observer,
                403,
                'You are not registered as an observer.'
            );

            $role = 'observer';
            $stage = 'POST';
        } else {

            abort_if(
                !$externalObserver,
                403,
                'You are not registered as an external observer.'
            );

            $role = 'external';
            $stage = 'EXTERNAL';
        }


        // Get response
        $response = PostResponse::where(
            'responseID',
            $responseID
        )
            ->where(
                'observation_stage',
                $stage
            )
            ->firstOrFail();


        // Check ownership
        if ($role === 'observer') {

            abort_if(
                $response->observer_id !=
                    $observer->observer_id,
                403,
                'You are not allowed to edit this response.'
            );
        } else {

            abort_if(
                $response->external_observer_id !=
                    $externalObserver->external_observer_id,
                403,
                'You are not allowed to edit this response.'
            );
        }


        // Submitted response cannot be edited
        abort_if(
            $response->status === 'Submitted',
            403,
            'Submitted feedback cannot be edited.'
        );


        // Get current active fields
        $form = $this->getForm(
            $response->formID
        );


        // Validate form
        $this->validateForm(
            $request,
            $form
        );


        DB::transaction(function () use (
            $request,
            $response,
            $form,
            $observer,
            $externalObserver,
            $role,
            $stage
        ) {

            // Update response
            $response->class_name =
                $request->class_name;

            $response->subject_name =
                $request->subject_name;

            $response->observation_date =
                $request->observation_date;

            $response->observation_time =
                $request->observation_time;

            $response->status =
                $request->submit_action;

            $response->save();


            // Replace active-field answers only
            $activeFieldIDs = $form->sections
                ->flatMap(function ($section) {
                    return $section->fields;
                })
                ->where(
                    'field_type',
                    '!=',
                    'display'
                )
                ->pluck('fieldID');


            // Delete answers only for fields currently editable
            PostAnswer::where(
                'responseID',
                $response->responseID
            )
                ->whereIn(
                    'fieldID',
                    $activeFieldIDs
                )
                ->delete();


            // Save current answers
            $this->saveAnswers(
                $request,
                $form,
                $response
            );

            if ($request->submit_action === 'Submitted') {

                AuditObservation::create([
                    'teacherID' => $role === 'observer'
                        ? $observer->teacherID
                        : $externalObserver->teacherID,

                    'gn_id' => $response->gn_id,

                    'role' => $role === 'observer'
                        ? 'Observer'
                        : 'External Observer',

                    'stage' => $stage,

                    'form_name' => $form->form_name,

                    'action' => 'Submitted',

                    'audit_date' => now()->toDateString(),

                    'audit_time' => now()->format('H:i:s'),
                ]);
            }
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
                    ? 'Feedback observation submitted successfully.'
                    : 'Draft updated successfully.'
            );
    }

    // Show submitted feedback observation
    public function show(Request $request, $responseID)
    {
        $teacherID = Auth::guard('teacher')->id();

        $observer = Observer::where(
            'teacherID',
            $teacherID
        )->first();

        $externalObserver = ExternalObserver::where(
            'teacherID',
            $teacherID
        )->first();


        // Determine role and stage
        if ($request->routeIs('observer.post.view')) {

            abort_if(
                !$observer,
                403,
                'You are not registered as an observer.'
            );

            $role = 'observer';
            $stage = 'POST';
        } else {

            abort_if(
                !$externalObserver,
                403,
                'You are not registered as an external observer.'
            );

            $role = 'external';
            $stage = 'EXTERNAL';
        }


        // Get response
        $response = PostResponse::where(
            'responseID',
            $responseID
        )
            ->where(
                'observation_stage',
                $stage
            )
            ->where(
                'status',
                'Submitted'
            )
            ->firstOrFail();


        // Check ownership
        if ($role === 'observer') {

            abort_if(
                $response->observer_id !=
                    $observer->observer_id,
                403,
                'You are not allowed to view this response.'
            );
        } else {

            abort_if(
                $response->external_observer_id !=
                    $externalObserver->external_observer_id,
                403,
                'You are not allowed to view this response.'
            );
        }


        // Get teacher
        $guru = GuruNew::with('school')
            ->where(
                'gn_id',
                $response->gn_id
            )
            ->firstOrFail();


        // Get form used by this response
        $form = $this->getForm(
            $response->formID
        );


        // Get saved answers
        $answers = PostAnswer::where(
            'responseID',
            $response->responseID
        )->get();


        $existingAnswers = [];


        foreach ($answers as $answer) {

            $field = $form->sections
                ->flatMap(function ($section) {
                    return $section->fields;
                })
                ->firstWhere(
                    'fieldID',
                    $answer->fieldID
                );


            if (!$field) {
                continue;
            }


            if ($field->field_type === 'checkbox') {

                $existingAnswers[$answer->fieldID] =
                    json_decode(
                        $answer->answer_value,
                        true
                    ) ?? [];
            } else {

                $existingAnswers[$answer->fieldID] =
                    $answer->answer_value;
            }
        }


        return view(
            'post-observation.view',
            compact(
                'form',
                'guru',
                'response',
                'role',
                'stage',
                'existingAnswers'
            )
        );
    }


    // Get active POST form
    private function getActiveForm()
    {
        $form = PostForm::where(
            'status',
            'Active'
        )
            ->with([
                'sections' => function ($query) {
                    $query->orderBy(
                        'display_order'
                    );
                },

                'sections.fields' => function ($query) {
                    $query
                        ->where(
                            'status',
                            'Active'
                        )
                        ->orderBy(
                            'display_order'
                        );
                },

                'sections.fields.options' => function ($query) {
                    $query->orderBy(
                        'display_order'
                    );
                },
            ])
            ->orderBy('formID')
            ->first();


        abort_if(
            !$form,
            404,
            'No active post observation form found.'
        );


        return $form;
    }


    // Get form used by existing response
    private function getForm($formID)
    {
        $form = PostForm::where(
            'formID',
            $formID
        )
            ->with([
                'sections' => function ($query) {
                    $query->orderBy(
                        'display_order'
                    );
                },

                'sections.fields' => function ($query) {
                    $query
                        ->where(
                            'status',
                            'Active'
                        )
                        ->orderBy(
                            'display_order'
                        );
                },

                'sections.fields.options' => function ($query) {
                    $query->orderBy(
                        'display_order'
                    );
                },
            ])
            ->first();


        abort_if(
            !$form,
            404,
            'Post observation form not found.'
        );


        return $form;
    }


    // Validate POST response
    private function validateForm(
        Request $request,
        $form
    ) {
        $isSubmit =
            $request->submit_action ===
            'Submitted';


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

            'observation_time' => $isSubmit
                ? [
                    'required',
                    'date_format:H:i',
                ]
                : [
                    'nullable',
                    'date_format:H:i',
                ],

            'submit_action' => [
                'required',
                'in:Draft,Submitted',
            ],
        ];


        foreach ($form->sections as $section) {

            foreach ($section->fields as $field) {

                if (
                    $field->field_type ===
                    'display'
                ) {
                    continue;
                }


                $answerKey =
                    'answers.' .
                    $field->fieldID;


                // Checkbox validation
                if (
                    $field->field_type ===
                    'checkbox'
                ) {

                    $rules[$answerKey] =
                        $isSubmit &&
                        $field->is_required
                        ? [
                            'required',
                            'array',
                            'min:1',
                        ]
                        : [
                            'nullable',
                            'array',
                        ];


                    $rules[$answerKey . '.*'] = [
                        'string',
                        'max:255',
                    ];
                } else {

                    // Text, textarea and radio validation
                    $rules[$answerKey] =
                        $isSubmit &&
                        $field->is_required
                        ? [
                            'required',
                            'string',
                        ]
                        : [
                            'nullable',
                            'string',
                        ];
                }
            }
        }


        $request->validate(
            $rules
        );
    }


    // Save answered fields only
    private function saveAnswers(
        Request $request,
        $form,
        PostResponse $response
    ) {
        foreach ($form->sections as $section) {

            foreach ($section->fields as $field) {

                if (
                    $field->field_type ===
                    'display'
                ) {
                    continue;
                }


                $answerValue =
                    $request->input(
                        'answers.' .
                            $field->fieldID
                    );


                // Save checkbox as JSON
                if (
                    $field->field_type ===
                    'checkbox'
                ) {

                    if (
                        !is_array($answerValue)
                        ||
                        empty($answerValue)
                    ) {
                        continue;
                    }

                    $answerValue =
                        json_encode(
                            $answerValue
                        );
                } else {

                    // Do not save empty answers
                    if (
                        $answerValue === null
                        ||
                        trim(
                            (string) $answerValue
                        ) === ''
                    ) {
                        continue;
                    }
                }


                PostAnswer::create([
                    'responseID' =>
                    $response->responseID,

                    'fieldID' =>
                    $field->fieldID,

                    'answer_value' =>
                    $answerValue,
                ]);
            }
        }
    }
}
