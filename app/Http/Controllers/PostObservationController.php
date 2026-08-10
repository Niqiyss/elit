<?php

namespace App\Http\Controllers;

use App\Models\PostForm;
use App\Models\PostResponse;
use App\Models\PostAnswer;
use App\Models\Observer;
use App\Models\ExternalObserver;
use App\Models\GuruNew;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostObservationController extends Controller
{
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

        abort_if(
            !$observer && !$externalObserver,
            403,
            'You are not registered as an observer.'
        );

        $role = $observer
            ? 'observer'
            : 'external';

        $stage = $observer
            ? 'POST'
            : 'EXTERNAL';


        $guru = GuruNew::with('school')
            ->where('gn_id', $gn_id)
            ->first();

        if (!$guru) {
            abort(
                404,
                'New teacher not found. GN ID: ' . $gn_id
            );
        }


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
            ])
            ->orderBy(
                'formID'
            )
            ->first();

        if (!$form) {
            abort(
                404,
                'No active post observation form found.'
            );
        }


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

        abort_if(
            !$observer && !$externalObserver,
            403,
            'You are not registered as an observer.'
        );


        $guru = GuruNew::where(
            'gn_id',
            $gn_id
        )->first();

        if (!$guru) {
            abort(
                404,
                'New teacher not found. GN ID: ' . $gn_id
            );
        }


        $form = PostForm::where(
            'status',
            'Active'
        )
            ->with([
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
            ])
            ->orderBy(
                'formID'
            )
            ->first();

        if (!$form) {
            abort(
                404,
                'No active post observation form found.'
            );
        }


        $rules = [
            'class_name' => [
                'required',
                'string',
                'max:100',
            ],

            'subject_name' => [
                'required',
                'string',
                'max:100',
            ],

            'observation_date' => [
                'required',
                'date',
            ],

            'observation_time' => [
                'required',
            ],

            'submit_action' => [
                'required',
                'in:Draft,Submitted',
            ],
        ];


        foreach ($form->sections as $section) {

            foreach ($section->fields as $field) {

                if (
                    $field->field_type === 'display'
                ) {
                    continue;
                }

                $fieldRules = [];

                if (
                    $request->submit_action === 'Submitted'
                    && $field->is_required
                ) {
                    $fieldRules[] = 'required';
                } else {
                    $fieldRules[] = 'nullable';
                }

                if (
                    $field->field_type === 'number'
                ) {
                    $fieldRules[] = 'numeric';
                }

                if (
                    $field->field_type === 'date'
                ) {
                    $fieldRules[] = 'date';
                }

                $rules[
                    'answers.' . $field->fieldID
                ] = $fieldRules;
            }
        }


        $request->validate(
            $rules
        );


        DB::transaction(function () use (
            $request,
            $gn_id,
            $form,
            $observer,
            $externalObserver
        ) {

            $response = PostResponse::create([
                'observation_stage' =>
                    $observer
                        ? 'POST'
                        : 'EXTERNAL',

                'class_name' =>
                    $request->class_name,

                'subject_name' =>
                    $request->subject_name,

                'observation_date' =>
                    $request->observation_date,

                'observation_time' =>
                    $request->observation_time,

                'status' =>
                    $request->submit_action,

                'formID' =>
                    $form->formID,

                'observer_id' =>
                    $observer
                        ? $observer->observer_id
                        : null,

                'external_observer_id' =>
                    $externalObserver
                        ? $externalObserver->external_observer_id
                        : null,

                'gn_id' =>
                    $gn_id,
            ]);


            foreach ($form->sections as $section) {

                foreach ($section->fields as $field) {

                    if (
                        $field->field_type === 'display'
                    ) {
                        continue;
                    }

                    $answerValue =
                        $request->input(
                            'answers.' .
                            $field->fieldID
                        );

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
        });


        return redirect()
            ->back()
            ->with(
                'success',
                $request->submit_action === 'Submitted'
                    ? 'Post observation submitted successfully.'
                    : 'Draft saved successfully.'
            );
    }
}