<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostForm;
use App\Models\PostSection;
use App\Models\PostField;
use App\Models\PostFieldOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostFormController extends Controller
{
    public function index()
    {
        $form = PostForm::with([
            'sections' => function ($query) {
                $query->orderBy('display_order');
            },

            'sections.fields' => function ($query) {
                $query->orderBy('display_order');
            },

            'sections.fields.options' => function ($query) {
                $query->orderBy('display_order');
            },
        ])
            ->orderBy('formID')
            ->first();

        return view(
            'admin.post-form',
            compact('form')
        );
    }


    public function storeForm(Request $request)
    {
        $request->validate([
            'form_name' => [
                'required',
                'string',
                'max:255',
            ],

            'instruction' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],
        ]);

        PostForm::create([
            'form_name' => $request->form_name,
            'instruction' => $request->instruction,
            'status' => $request->status,
            'staffid' => Auth::guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.post.form')
            ->with(
                'success',
                'Post observation form created successfully'
            );
    }


    public function updateForm(
        Request $request,
        $formID
    ) {
        $form = PostForm::findOrFail($formID);

        $request->validate([
            'form_name' => [
                'required',
                'string',
                'max:255',
            ],

            'instruction' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],
        ]);

        $form->form_name =
            $request->form_name;

        $form->instruction =
            $request->instruction;

        $form->status =
            $request->status;

        $form->staffid =
            Auth::guard('admin')->id();

        $form->save();

        return redirect()
            ->route('admin.post.form')
            ->with(
                'success',
                'Form information updated successfully'
            );
    }


    public function storeSection(Request $request)
    {
        $request->validate([
            'formID' => [
                'required',
                'exists:post_form,formID',
            ],

            'section_name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $lastOrder = PostSection::where(
            'formID',
            $request->formID
        )
            ->max('display_order');

        $nextOrder =
            ($lastOrder ?? 0) + 1;

        PostSection::create([
            'formID' =>
                $request->formID,

            'section_name' =>
                $request->section_name,

            'display_order' =>
                $nextOrder,
        ]);

        return redirect()
            ->route('admin.post.form')
            ->with(
                'success',
                'Section added successfully'
            );
    }


    public function updateSection(
        Request $request,
        $sectionID
    ) {
        $section = PostSection::findOrFail(
            $sectionID
        );

        $request->validate([
            'section_name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $section->section_name =
            $request->section_name;

        $section->save();

        return redirect()
            ->route('admin.post.form')
            ->with(
                'success',
                'Section updated successfully'
            );
    }


    // Delete section only if its fields have never been used
    public function destroySection($sectionID)
    {
        $section = PostSection::with('fields')
            ->findOrFail($sectionID);

        $formID =
            $section->formID;

        $fieldIDs =
            $section->fields
                ->pluck('fieldID');

        $hasAnswers = false;

        if ($fieldIDs->isNotEmpty()) {

            $hasAnswers = DB::table('post_answer')
                ->whereIn(
                    'fieldID',
                    $fieldIDs
                )
                ->exists();
        }


        // If section already has answers, keep history safe
        if ($hasAnswers) {

            foreach ($section->fields as $field) {

                $field->update([
                    'status' => 'Inactive',
                ]);
            }

            return redirect()
                ->route('admin.post.form')
                ->with(
                    'success',
                    'This section has already been used. Its fields were set to Inactive instead of deleting the section.'
                );
        }


        // Delete unused fields and options
        foreach ($section->fields as $field) {

            PostFieldOption::where(
                'fieldID',
                $field->fieldID
            )->delete();

            $field->delete();
        }

        $section->delete();


        // Reorder remaining sections
        $sections = PostSection::where(
            'formID',
            $formID
        )
            ->orderBy('display_order')
            ->get();

        foreach ($sections as $index => $item) {

            $item->display_order =
                $index + 1;

            $item->save();
        }

        return redirect()
            ->route('admin.post.form')
            ->with(
                'success',
                'Section deleted successfully'
            );
    }


    public function reorderSections(Request $request)
    {
        $request->validate([
            'sections' => [
                'required',
                'array',
            ],

            'sections.*' => [
                'required',
                'exists:post_section,sectionID',
            ],
        ]);

        foreach (
            $request->sections
            as $index => $sectionID
        ) {
            PostSection::where(
                'sectionID',
                $sectionID
            )
                ->update([
                    'display_order' =>
                        $index + 1,
                ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }


    public function storeField(Request $request)
    {
        $request->validate([
            'sectionID' => [
                'required',
                'exists:post_section,sectionID',
            ],

            'field_label' => [
                'required',
                'string',
                'max:500',
            ],

            'field_type' => [
                'required',
                'in:display,text,textarea,checkbox,radio',
            ],

            'is_required' => [
                'required',
                'boolean',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],

            'options' => [
                'nullable',
                'array',
            ],

            'options.*' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $lastOrder = PostField::where(
            'sectionID',
            $request->sectionID
        )
            ->max('display_order');

        $nextOrder =
            ($lastOrder ?? 0) + 1;

        $field = PostField::create([
            'sectionID' =>
                $request->sectionID,

            'field_label' =>
                $request->field_label,

            'field_type' =>
                $request->field_type,

            'display_order' =>
                $nextOrder,

            'is_required' =>
                $request->field_type === 'display'
                    ? 0
                    : $request->is_required,

            'status' =>
                $request->status,
        ]);


        // Save checkbox or radio options
        if (
            in_array(
                $request->field_type,
                [
                    'checkbox',
                    'radio',
                ]
            )
            &&
            $request->has('options')
        ) {
            foreach (
                $request->options
                as $index => $option
            ) {
                if (
                    is_null($option)
                    ||
                    trim($option) === ''
                ) {
                    continue;
                }

                PostFieldOption::create([
                    'fieldID' =>
                        $field->fieldID,

                    'option_label' =>
                        trim($option),

                    'display_order' =>
                        $index + 1,
                ]);
            }
        }

        return redirect()
            ->route('admin.post.form')
            ->with(
                'success',
                'Field added successfully'
            );
    }


    public function updateField(
        Request $request,
        $fieldID
    ) {
        $field = PostField::findOrFail(
            $fieldID
        );

        $request->validate([
            'field_label' => [
                'required',
                'string',
                'max:500',
            ],

            'field_type' => [
                'required',
                'in:display,text,textarea,checkbox,radio',
            ],

            'is_required' => [
                'required',
                'boolean',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],

            'options' => [
                'nullable',
                'array',
            ],

            'options.*' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $field->field_label =
            $request->field_label;

        $field->field_type =
            $request->field_type;

        $field->is_required =
            $request->field_type === 'display'
                ? 0
                : $request->is_required;

        $field->status =
            $request->status;

        $field->save();


        // Replace current options
        PostFieldOption::where(
            'fieldID',
            $field->fieldID
        )->delete();


        if (
            in_array(
                $request->field_type,
                [
                    'checkbox',
                    'radio',
                ]
            )
            &&
            $request->has('options')
        ) {
            foreach (
                $request->options
                as $index => $option
            ) {
                if (
                    is_null($option)
                    ||
                    trim($option) === ''
                ) {
                    continue;
                }

                PostFieldOption::create([
                    'fieldID' =>
                        $field->fieldID,

                    'option_label' =>
                        trim($option),

                    'display_order' =>
                        $index + 1,
                ]);
            }
        }

        return redirect()
            ->route('admin.post.form')
            ->with(
                'success',
                'Field updated successfully'
            );
    }


    // Delete unused field or deactivate used field
    public function destroyField($fieldID)
    {
        $field = PostField::findOrFail(
            $fieldID
        );

        $sectionID =
            $field->sectionID;

        $hasBeenUsed = DB::table('post_answer')
            ->where(
                'fieldID',
                $fieldID
            )
            ->exists();


        // Keep historical answers safe
        if ($hasBeenUsed) {

            $field->update([
                'status' => 'Inactive',
            ]);

            return redirect()
                ->route('admin.post.form')
                ->with(
                    'success',
                    'This field has already been used, so it was set to Inactive instead of being deleted.'
                );
        }


        // Delete unused options
        PostFieldOption::where(
            'fieldID',
            $fieldID
        )->delete();


        // Delete unused field
        $field->delete();


        // Reorder remaining fields
        $fields = PostField::where(
            'sectionID',
            $sectionID
        )
            ->orderBy('display_order')
            ->get();

        foreach ($fields as $index => $item) {

            $item->display_order =
                $index + 1;

            $item->save();
        }

        return redirect()
            ->route('admin.post.form')
            ->with(
                'success',
                'Field deleted successfully.'
            );
    }


    public function reorderFields(Request $request)
    {
        $request->validate([
            'fields' => [
                'required',
                'array',
            ],

            'fields.*' => [
                'required',
                'exists:post_field,fieldID',
            ],
        ]);

        foreach (
            $request->fields
            as $index => $fieldID
        ) {
            PostField::where(
                'fieldID',
                $fieldID
            )
                ->update([
                    'display_order' =>
                        $index + 1,
                ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}