<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostObservationController extends Controller
{
    // Show new feedback form
    public function create(Request $request, $gn_id)
    {
        $teacherID = Auth::guard('teacher')->id();

        $observer = DB::table('observer')->where('teacherID', $teacherID)->first();
        $externalObserver = DB::table('external_observer')->where('teacherID', $teacherID)->first();

        // Determine role and stage
        if ($request->routeIs('observer.post.create')) {
            abort_if(!$observer, 403, 'You are not registered as an observer');

            $role = 'observer';
            $stage = 'POST';
            $attemptNo = 1;
        } else {
            abort_if(!$externalObserver, 403, 'You are not registered as an external observer');

            $role = 'external';
            $stage = 'EXTERNAL';
            $attemptNo = $this->getCurrentExternalAttemptNo($gn_id);
        }

        // Get teacher
        $guru = $this->getGuruNew($gn_id);

        // Check assignment
        $assigned = $role === 'observer'
            ? DB::table('observer_assignment')
            ->where('gn_id', $gn_id)
            ->where('observer_id', $observer->observer_id)
            ->exists()
            : DB::table('observer_assignment')
            ->where('gn_id', $gn_id)
            ->where('external_observer_id', $externalObserver->external_observer_id)
            ->exists();

        abort_if(!$assigned, 403, 'This teacher is not assigned to you');

        // Get current attempt response
        $existingQuery = DB::table('post_response')
            ->where('gn_id', $gn_id)
            ->where('observation_stage', $stage)
            ->where('attempt_no', $attemptNo);

        if ($role === 'observer') {
            $existingQuery->where('observer_id', $observer->observer_id);
        } else {
            $existingQuery->where('external_observer_id', $externalObserver->external_observer_id);
        }

        $existingResponse = $existingQuery->orderByDesc('responseID')->first();

        // Redirect draft
        if ($existingResponse && $existingResponse->status === 'Draft') {
            return redirect()->route(
                $role === 'observer'
                    ? 'observer.post.edit'
                    : 'external.post.edit',
                $existingResponse->responseID
            );
        }

        // Prevent duplicate submitted feedback
        if ($existingResponse && $existingResponse->status === 'Submitted') {
            return redirect()
                ->route(
                    $role === 'observer'
                        ? 'observer.manage'
                        : 'external.manage',
                    $gn_id
                )
                ->with(
                    'error',
                    'Feedback for this evaluation attempt has already been submitted'
                );
        }

        // Get active form
        $form = $this->getActiveForm();

        return view('post-observation.form', compact(
            'form',
            'guru',
            'gn_id',
            'role',
            'stage',
            'attemptNo'
        ));
    }


    // Store new feedback response
    public function store(Request $request, $gn_id)
    {
        $teacherID = Auth::guard('teacher')->id();

        $observer = DB::table('observer')->where('teacherID', $teacherID)->first();
        $externalObserver = DB::table('external_observer')->where('teacherID', $teacherID)->first();

        // Determine role and stage
        if ($request->routeIs('observer.post.store')) {
            abort_if(!$observer, 403, 'You are not registered as an observer');

            $role = 'observer';
            $stage = 'POST';
            $attemptNo = 1;
        } else {
            abort_if(!$externalObserver, 403, 'You are not registered as an external observer');

            $role = 'external';
            $stage = 'EXTERNAL';
            $attemptNo = $this->getCurrentExternalAttemptNo($gn_id);
        }

        // Make sure teacher exists
        abort_if(
            !DB::table('guru_new')
                ->where('gn_id', $gn_id)
                ->exists(),
            404,
            'Teacher not found.'
        );

        // Check assignment
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

        // Get exact form version opened by evaluator
        $request->validate([
            'formID' => ['required', 'exists:post_form,formID'],
        ]);

        $form = $this->getForm($request->formID);

        // Validate response
        $this->validateForm($request, $form);

        DB::transaction(function () use (
            $request,
            $gn_id,
            $form,
            $observer,
            $externalObserver,
            $role,
            $stage,
            $attemptNo
        ) {
            // Prevent duplicate attempt
            $existingResponse = DB::table('post_response')
                ->where('gn_id', $gn_id)
                ->where('observation_stage', $stage)
                ->where('attempt_no', $attemptNo)
                ->exists();

            abort_if(
                $existingResponse,
                409,
                'A feedback response already exists for this evaluation'
            );

            // Create response
            $responseID = DB::table('post_response')->insertGetId([
                'gn_id' => $gn_id,
                'formID' => $form->formID,
                'observation_stage' => $stage,
                'attempt_no' => $attemptNo,
                'observer_id' => $role === 'observer'
                    ? $observer->observer_id
                    : null,
                'external_observer_id' => $role === 'external'
                    ? $externalObserver->external_observer_id
                    : null,
                'class_name' => $request->class_name,
                'subject_name' => $request->subject_name,
                'observation_date' => $request->observation_date,
                'observation_time' => $request->observation_time,
                'status' => $request->submit_action,
            ]);

            // Save answers
            $this->saveAnswers(
                $request,
                $form,
                $responseID
            );

            // Save audit
            if ($request->submit_action === 'Submitted') {
                DB::table('audit_observation')->insert([
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
                    ? 'Form submitted successfully'
                    : 'Draft saved successfully'
            );
    }


    // Show draft edit form
    public function edit(Request $request, $responseID)
    {
        $teacherID = Auth::guard('teacher')->id();

        $observer = DB::table('observer')->where('teacherID', $teacherID)->first();
        $externalObserver = DB::table('external_observer')->where('teacherID', $teacherID)->first();

        // Determine role and stage
        if ($request->routeIs('observer.post.edit')) {
            abort_if(!$observer, 403, 'You are not registered as an observer.');

            $role = 'observer';
            $stage = 'POST';
        } else {
            abort_if(!$externalObserver, 403, 'You are not registered as an external observer');

            $role = 'external';
            $stage = 'EXTERNAL';
        }

        // Get response
        $response = DB::table('post_response')
            ->where('responseID', $responseID)
            ->where('observation_stage', $stage)
            ->first();

        abort_if(
            !$response,
            404,
            'Feedback response not found'
        );

        // Check ownership
        if ($role === 'observer') {
            abort_if(
                $response->observer_id != $observer->observer_id,
                403,
                'You are not allowed to edit this response'
            );
        } else {
            abort_if(
                $response->external_observer_id != $externalObserver->external_observer_id,
                403,
                'You are not allowed to edit this response'
            );
        }

        // Submitted cannot be edited
        abort_if(
            $response->status === 'Submitted',
            403,
            'Submitted feedback cannot be edited'
        );

        // Get teacher
        $guru = $this->getGuruNew($response->gn_id);

        $gn_id = $response->gn_id;

        // Get exact form version used
        $form = $this->getForm($response->formID);

        // Get saved answers
        $existingAnswers = $this->getExistingAnswers(
            $response->responseID,
            $form
        );

        return view('post-observation.edit', compact(
            'form',
            'guru',
            'gn_id',
            'role',
            'stage',
            'response',
            'existingAnswers'
        ));
    }


    // Update draft response
    public function update(Request $request, $responseID)
    {
        $teacherID = Auth::guard('teacher')->id();

        $observer = DB::table('observer')->where('teacherID', $teacherID)->first();
        $externalObserver = DB::table('external_observer')->where('teacherID', $teacherID)->first();

        // Determine role and stage
        if ($request->routeIs('observer.post.update')) {
            abort_if(!$observer, 403, 'You are not registered as an observer');

            $role = 'observer';
            $stage = 'POST';
        } else {
            abort_if(!$externalObserver, 403, 'You are not registered as an external observer');

            $role = 'external';
            $stage = 'EXTERNAL';
        }

        // Get response
        $response = DB::table('post_response')
            ->where('responseID', $responseID)
            ->where('observation_stage', $stage)
            ->first();

        abort_if(
            !$response,
            404,
            'Feedback response not found'
        );

        // Check ownership
        if ($role === 'observer') {
            abort_if(
                $response->observer_id != $observer->observer_id,
                403,
                'You are not allowed to edit this response'
            );
        } else {
            abort_if(
                $response->external_observer_id != $externalObserver->external_observer_id,
                403,
                'You are not allowed to edit this response'
            );
        }

        // Submitted cannot be edited
        abort_if(
            $response->status === 'Submitted',
            403,
            'Submitted feedback cannot be edited'
        );

        // Get exact form version used
        $form = $this->getForm($response->formID);

        // Validate response
        $this->validateForm($request, $form);

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
            DB::table('post_response')
                ->where('responseID', $response->responseID)
                ->update([
                    'class_name' => $request->class_name,
                    'subject_name' => $request->subject_name,
                    'observation_date' => $request->observation_date,
                    'observation_time' => $request->observation_time,
                    'status' => $request->submit_action,
                ]);

            // Get editable field IDs
            $fieldIDs = $form->sections
                ->flatMap(fn($section) => $section->fields)
                ->where('field_type', '!=', 'display')
                ->pluck('fieldID');

            // Replace saved answers
            if ($fieldIDs->isNotEmpty()) {
                DB::table('post_answer')
                    ->where('responseID', $response->responseID)
                    ->whereIn('fieldID', $fieldIDs)
                    ->delete();
            }

            // Save current answers
            $this->saveAnswers(
                $request,
                $form,
                $response->responseID
            );

            // Save audit
            if ($request->submit_action === 'Submitted') {
                DB::table('audit_observation')->insert([
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
                    ? 'Form submitted successfully'
                    : 'Draft updated successfully'
            );
    }


    // Show submitted feedback
    public function show(Request $request, $responseID)
    {
        $teacherID = Auth::guard('teacher')->id();

        $observer = DB::table('observer')->where('teacherID', $teacherID)->first();
        $externalObserver = DB::table('external_observer')->where('teacherID', $teacherID)->first();

        // Determine role and stage
        if ($request->routeIs('observer.post.view')) {
            abort_if(!$observer, 403, 'You are not registered as an observer');

            $role = 'observer';
            $stage = 'POST';
        } else {
            abort_if(!$externalObserver, 403, 'You are not registered as an external observer');

            $role = 'external';
            $stage = 'EXTERNAL';
        }

        // Get submitted response
        $response = DB::table('post_response')
            ->where('responseID', $responseID)
            ->where('observation_stage', $stage)
            ->where('status', 'Submitted')
            ->first();

        abort_if(!$response, 404, 'Submitted feedback response not found');

        // Check ownership
        if ($role === 'observer') {
            abort_if(
                $response->observer_id != $observer->observer_id,
                403,
                'You are not allowed to view this response'
            );
        } else {
            abort_if(
                $response->external_observer_id != $externalObserver->external_observer_id,
                403,
                'You are not allowed to view this response'
            );
        }

        // Get teacher
        $guru = $this->getGuruNew($response->gn_id);

        // Get exact form version used
        $form = $this->getForm($response->formID);

        // Get saved answers
        $existingAnswers = $this->getExistingAnswers(
            $response->responseID,
            $form
        );

        // Get evaluator name from database
        if ($role === 'observer') {
            $evaluatorName = DB::table('observer')
                ->join('teacher', 'observer.teacherID', '=', 'teacher.teacherID')
                ->where('observer.observer_id', $response->observer_id)
                ->value('teacher.teacher_name');
        } else {
            $evaluatorName = DB::table('external_observer')
                ->join('teacher', 'external_observer.teacherID', '=', 'teacher.teacherID')
                ->where('external_observer.external_observer_id', $response->external_observer_id)
                ->value('teacher.teacher_name');
        }

        return view('post-observation.view', compact(
            'form',
            'guru',
            'response',
            'role',
            'stage',
            'existingAnswers',
            'evaluatorName'
        ));
    }


    // Get current EXTERNAL attempt
    private function getCurrentExternalAttemptNo($gn_id): int
    {
        $latestPdpcAttempt = DB::table('pdpc_response')
            ->where('gn_id', $gn_id)
            ->where('observation_stage', 'EXTERNAL')
            ->max('attempt_no');

        $latestFeedbackAttempt = DB::table('post_response')
            ->where('gn_id', $gn_id)
            ->where('observation_stage', 'EXTERNAL')
            ->max('attempt_no');

        return max(
            (int) ($latestPdpcAttempt ?? 1),
            (int) ($latestFeedbackAttempt ?? 1)
        );
    }


    // Get active POST form
    private function getActiveForm()
    {
        $form = DB::table('post_form')
            ->where('status', 'Active')
            ->orderByDesc('formID')
            ->first();

        abort_if(
            !$form,
            404,
            'No active post observation form found'
        );

        return $this->attachFormStructure($form);
    }


    // Get exact form version used
    private function getForm($formID)
    {
        $form = DB::table('post_form')
            ->where('formID', $formID)
            ->first();

        abort_if(
            !$form,
            404,
            'Post observation form not found'
        );

        return $this->attachFormStructure($form);
    }


    // Attach sections, fields and options
    private function attachFormStructure($form)
    {
        $sections = DB::table('post_section')
            ->where('formID', $form->formID)
            ->orderBy('display_order')
            ->get();

        foreach ($sections as $section) {
            $fields = DB::table('post_field')
                ->where('sectionID', $section->sectionID)
                ->orderBy('display_order')
                ->get();

            foreach ($fields as $field) {
                $field->options = DB::table('post_field_option')
                    ->where('fieldID', $field->fieldID)
                    ->orderBy('display_order')
                    ->get();
            }

            $section->fields = $fields;
        }

        $form->sections = $sections;

        return $form;
    }


    // Get GN with school
    private function getGuruNew($gn_id)
    {
        $guru = DB::table('guru_new')
            ->where('gn_id', $gn_id)
            ->first();

        abort_if(
            !$guru,
            404,
            'Teacher not found.'
        );

        $guru->school = $guru->schoolID
            ? DB::table('school')
            ->where('schoolID', $guru->schoolID)
            ->first()
            : null;

        return $guru;
    }


    // Get saved answers
    private function getExistingAnswers($responseID, $form): array
    {
        $answers = DB::table('post_answer')
            ->where('responseID', $responseID)
            ->get();

        $fields = $form->sections
            ->flatMap(fn($section) => $section->fields);

        $existingAnswers = [];

        foreach ($answers as $answer) {
            $field = $fields->firstWhere(
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

        return $existingAnswers;
    }


    // Validate POST response
    private function validateForm(Request $request, $form)
    {
        $isSubmit = $request->submit_action === 'Submitted';

        $rules = [
            'class_name' => $isSubmit
                ? ['required', 'string', 'max:100']
                : ['nullable', 'string', 'max:100'],

            'subject_name' => $isSubmit
                ? ['required', 'string', 'max:100']
                : ['nullable', 'string', 'max:100'],

            'observation_date' => $isSubmit
                ? ['required', 'date']
                : ['nullable', 'date'],

            'observation_time' => $isSubmit
                ? ['required', 'date_format:H:i']
                : ['nullable', 'date_format:H:i'],

            'submit_action' => [
                'required',
                'in:Draft,Submitted'
            ],
        ];

        foreach ($form->sections as $section) {
            foreach ($section->fields as $field) {
                if ($field->field_type === 'display') {
                    continue;
                }

                $answerKey =
                    'answers.' . $field->fieldID;

                // Checkbox
                if ($field->field_type === 'checkbox') {
                    $rules[$answerKey] =
                        $isSubmit && $field->is_required
                        ? ['required', 'array', 'min:1']
                        : ['nullable', 'array'];

                    $rules[$answerKey . '.*'] = [
                        'string',
                        'max:255'
                    ];
                } else {
                    // Text, textarea and radio
                    $rules[$answerKey] =
                        $isSubmit && $field->is_required
                        ? ['required', 'string']
                        : ['nullable', 'string'];
                }
            }
        }

        $request->validate($rules);
    }


    // Save answered fields
    private function saveAnswers(
        Request $request,
        $form,
        $responseID
    ) {
        foreach ($form->sections as $section) {
            foreach ($section->fields as $field) {
                if ($field->field_type === 'display') {
                    continue;
                }

                $answerValue = $request->input(
                    'answers.' . $field->fieldID
                );

                // Save checkbox as JSON
                if ($field->field_type === 'checkbox') {
                    if (
                        !is_array($answerValue) ||
                        empty($answerValue)
                    ) {
                        continue;
                    }

                    $answerValue =
                        json_encode($answerValue);
                } else {
                    // Skip empty answer
                    if (
                        $answerValue === null ||
                        trim((string) $answerValue) === ''
                    ) {
                        continue;
                    }
                }

                DB::table('post_answer')->insert([
                    'responseID' => $responseID,
                    'fieldID' => $field->fieldID,
                    'answer_value' => $answerValue,
                ]);
            }
        }
    }
}
