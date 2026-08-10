<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostForm;
use App\Models\PostSection;
use App\Models\PostField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostFormController extends Controller
{
    /* DISPLAY MANAGE POST FORM PAGE */
    public function index()
    {
        $form = PostForm::with([
            'sections' => function ($query) {
                $query->orderBy('display_order');
            },
            'sections.fields' => function ($query) {
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


    /* CREATE FORM */
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


    /* UPDATE FORM INFORMATION */
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


        $form->form_name = $request->form_name;
        $form->instruction = $request->instruction;
        $form->status = $request->status;

        // Admin who last managed the form
        $form->staffid = Auth::guard('admin')->id();

        $form->save();


        return redirect()
            ->route('admin.post.form')
            ->with(
                'success',
                'Form information updated successfully'
            );
    }


    /* ADD SECTION */
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

            'display_order' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);


        PostSection::create([
            'formID' => $request->formID,
            'section_name' => $request->section_name,
            'display_order' => $request->display_order,
        ]);


        return redirect()
            ->route('admin.post.form')
            ->with(
                'success',
                'Section added successfully'
            );
    }


    /* UPDATE SECTION */
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

            'display_order' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);


        $section->section_name =
            $request->section_name;

        $section->display_order =
            $request->display_order;

        $section->save();


        return redirect()
            ->route('admin.post.form')
            ->with(
                'success',
                'Section updated successfully'
            );
    }


    /* DELETE SECTION */
    public function destroySection($sectionID)
    {
        $section = PostSection::findOrFail(
            $sectionID
        );

        $section->delete();


        return redirect()
            ->route('admin.post.form')
            ->with(
                'success',
                'Section deleted successfully'
            );
    }


    /* ADD FIELD */
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
                'in:display,text,textarea,number,date,time',
            ],

            'display_order' => [
                'required',
                'integer',
                'min:1',
            ],

            'is_required' => [
                'required',
                'boolean',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],
        ]);

        PostField::create([
            'sectionID' => $request->sectionID,
            'field_label' => $request->field_label,
            'field_type' => $request->field_type,
            'display_order' => $request->display_order,
            'is_required' => $request->field_type === 'display'
                ? 0
                : $request->is_required,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.post.form')
            ->with(
                'success',
                'Field added successfully'
            );
    }


    /* UPDATE FIELD */
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
                'in:display,text,textarea,number,date,time',
            ],

            'display_order' => [
                'required',
                'integer',
                'min:1',
            ],

            'is_required' => [
                'required',
                'boolean',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],
        ]);

        $field->field_label =
            $request->field_label;

        $field->field_type =
            $request->field_type;

        $field->display_order =
            $request->display_order;

        $field->is_required =
            $request->field_type === 'display'
            ? 0
            : $request->is_required;

        $field->status =
            $request->status;

        $field->save();

        return redirect()
            ->route('admin.post.form')
            ->with(
                'success',
                'Field updated successfully'
            );
    }


    /* DELETE FIELD */
    public function destroyField($fieldID)
    {
        $field = PostField::findOrFail(
            $fieldID
        );

        $field->delete();


        return redirect()
            ->route('admin.post.form')
            ->with(
                'success',
                'Field deleted successfully'
            );
    }
}
