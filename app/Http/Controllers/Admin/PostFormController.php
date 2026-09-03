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
    // Show version list
    public function index()
    {
        $forms = PostForm::with([
            'sections' => fn($query) => $query->orderBy('display_order'),
            'sections.fields' => fn($query) => $query->orderBy('display_order'),
            'sections.fields.options' => fn($query) => $query->orderBy('display_order'),
        ])->orderByDesc('version')->get();

        foreach ($forms as $form) {
            $form->is_used = $this->isFormUsed($form);
            $form->section_count = $form->sections->count();
            $form->field_count = $form->sections->sum(fn($section) => $section->fields->count());
        }

        return view('admin.post-form', compact('forms'));
    }

    // Preview one version
    public function show(PostForm $postForm)
    {
        $postForm->load([
            'sections' => fn($query) => $query->orderBy('display_order'),
            'sections.fields' => fn($query) => $query->orderBy('display_order'),
            'sections.fields.options' => fn($query) => $query->orderBy('display_order'),
        ]);

        return view('admin.post-form-preview', [
            'form' => $postForm,
        ]);
    }

    // Edit/manage one version
    public function edit(PostForm $postForm)
    {
        $postForm->load([
            'sections' => fn($query) => $query->orderBy('display_order'),
            'sections.fields' => fn($query) => $query->orderBy('display_order'),
            'sections.fields.options' => fn($query) => $query->orderBy('display_order'),
        ]);

        $formUsed = $this->isFormUsed($postForm);

        return view('admin.post-form-edit', [
            'form' => $postForm,
            'formUsed' => $formUsed,
        ]);
    }

    // Create first version
    public function storeForm(Request $request)
    {
        $request->validate([
            'form_name' => 'required|string|max:255',
            'instruction' => 'nullable|string',
        ]);

        if (PostForm::exists()) {
            return redirect()
                ->route('admin.post.form')
                ->with('error', 'A form already exists. Create a new version instead.');
        }

        $form = PostForm::create([
            'form_name' => $request->form_name,
            'version' => 1,
            'instruction' => $request->instruction,
            'status' => 'Active',
            'staffid' => Auth::guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.post.form.edit', $form)
            ->with('success', 'Feedback Observation form created successfully.');
    }

    // Create new version
    public function createNewVersion(PostForm $postForm)
    {
        $postForm->load([
            'sections.fields.options',
        ]);

        $newFormID = null;

        DB::transaction(function () use ($postForm, &$newFormID) {

            PostForm::where('status', 'Active')->update([
                'status' => 'Inactive',
            ]);

            $nextVersion = (PostForm::max('version') ?? 0) + 1;

            $newForm = PostForm::create([
                'form_name' => $postForm->form_name,
                'version' => $nextVersion,
                'instruction' => $postForm->instruction,
                'status' => 'Active',
                'staffid' => Auth::guard('admin')->id(),
            ]);

            $newFormID = $newForm->formID;

            foreach ($postForm->sections as $oldSection) {

                $newSection = PostSection::create([
                    'formID' => $newForm->formID,
                    'section_name' => $oldSection->section_name,
                    'display_order' => $oldSection->display_order,
                ]);

                foreach ($oldSection->fields as $oldField) {

                    $newField = PostField::create([
                        'sectionID' => $newSection->sectionID,
                        'field_label' => $oldField->field_label,
                        'field_type' => $oldField->field_type,
                        'display_order' => $oldField->display_order,
                        'is_required' => $oldField->is_required,
                    ]);

                    foreach ($oldField->options as $oldOption) {
                        PostFieldOption::create([
                            'fieldID' => $newField->fieldID,
                            'option_label' => $oldOption->option_label,
                            'display_order' => $oldOption->display_order,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('admin.post.form.edit', $newFormID)
            ->with('success', 'New Feedback Observation form version created successfully.');
    }

    // Update form information
    public function updateForm(Request $request, PostForm $postForm)
    {
        $request->validate([
            'form_name' => 'required|string|max:255',
            'instruction' => 'nullable|string',
        ]);

        $postForm->update([
            'form_name' => $request->form_name,
            'instruction' => $request->instruction,
            'staffid' => Auth::guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.post.form.edit', $postForm)
            ->with('success', 'Form information updated successfully.');
    }

    // Delete unused version
    public function destroyForm(PostForm $postForm)
    {
        if ($this->isFormUsed($postForm)) {
            return redirect()
                ->route('admin.post.form')
                ->with('error', 'This version has already been used and cannot be deleted.');
        }

        $wasActive = $postForm->status === 'Active';

        $postForm->load([
            'sections.fields.options',
        ]);

        DB::transaction(function () use ($postForm) {

            foreach ($postForm->sections as $section) {

                foreach ($section->fields as $field) {

                    PostFieldOption::where('fieldID', $field->fieldID)->delete();

                    $field->delete();
                }

                $section->delete();
            }

            $postForm->delete();
        });

        if ($wasActive) {

            PostForm::where('status', 'Active')->update([
                'status' => 'Inactive',
            ]);

            $previousForm = PostForm::orderByDesc('version')->first();

            if ($previousForm) {
                $previousForm->update([
                    'status' => 'Active',
                ]);
            }
        }

        return redirect()
            ->route('admin.post.form')
            ->with('success', 'Form version deleted successfully.');
    }

    // Add section
    public function storeSection(Request $request)
    {
        $request->validate([
            'formID' => 'required|exists:post_form,formID',
            'section_name' => 'required|string|max:255',
        ]);

        $form = PostForm::findOrFail($request->formID);

        $this->ensureStructureEditable($form);

        $lastOrder = PostSection::where('formID', $form->formID)
            ->max('display_order');

        PostSection::create([
            'formID' => $form->formID,
            'section_name' => $request->section_name,
            'display_order' => ($lastOrder ?? 0) + 1,
        ]);

        return redirect()
            ->route('admin.post.form.edit', $form)
            ->with('success', 'Section added successfully.');
    }

    // Update section wording
    public function updateSection(Request $request, $sectionID)
    {
        $section = PostSection::findOrFail($sectionID);
        $form = PostForm::findOrFail($section->formID);

        $request->validate([
            'section_name' => 'required|string|max:255',
        ]);

        $section->update([
            'section_name' => $request->section_name,
        ]);

        return redirect()
            ->route('admin.post.form.edit', $form)
            ->with('success', 'Section updated successfully.');
    }

    // Delete section
    public function destroySection($sectionID)
    {
        $section = PostSection::with('fields.options')
            ->findOrFail($sectionID);

        $form = PostForm::findOrFail($section->formID);

        $this->ensureStructureEditable($form);

        DB::transaction(function () use ($section) {

            foreach ($section->fields as $field) {

                PostFieldOption::where('fieldID', $field->fieldID)->delete();

                $field->delete();
            }

            $section->delete();
        });

        $sections = PostSection::where('formID', $form->formID)
            ->orderBy('display_order')
            ->get();

        foreach ($sections as $index => $item) {
            $item->update([
                'display_order' => $index + 1,
            ]);
        }

        return redirect()
            ->route('admin.post.form.edit', $form)
            ->with('success', 'Section deleted successfully.');
    }

    // Add field
    public function storeField(Request $request)
    {
        $request->validate([
            'sectionID' => 'required|exists:post_section,sectionID',
            'field_label' => 'required|string|max:500',
            'field_type' => 'required|in:display,text,textarea,checkbox,radio',
            'is_required' => 'required|boolean',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:255',
        ]);

        $section = PostSection::findOrFail($request->sectionID);
        $form = PostForm::findOrFail($section->formID);

        $this->ensureStructureEditable($form);

        $lastOrder = PostField::where('sectionID', $section->sectionID)
            ->max('display_order');

        $field = PostField::create([
            'sectionID' => $section->sectionID,
            'field_label' => $request->field_label,
            'field_type' => $request->field_type,
            'display_order' => ($lastOrder ?? 0) + 1,
            'is_required' => $request->field_type === 'display'
                ? 0
                : $request->is_required,
        ]);

        $this->saveOptions($request, $field);

        return redirect()
            ->route('admin.post.form.edit', $form)
            ->with('success', 'Field added successfully.');
    }

    // Update field
    public function updateField(Request $request, $fieldID)
    {
        $field = PostField::findOrFail($fieldID);
        $section = PostSection::findOrFail($field->sectionID);
        $form = PostForm::findOrFail($section->formID);

        $formUsed = $this->isFormUsed($form);

        if ($formUsed) {

            $request->validate([
                'field_label' => 'required|string|max:500',
            ]);

            $field->update([
                'field_label' => $request->field_label,
            ]);
        } else {

            $request->validate([
                'field_label' => 'required|string|max:500',
                'field_type' => 'required|in:display,text,textarea,checkbox,radio',
                'is_required' => 'required|boolean',
                'options' => 'nullable|array',
                'options.*' => 'nullable|string|max:255',
            ]);

            $field->update([
                'field_label' => $request->field_label,
                'field_type' => $request->field_type,
                'is_required' => $request->field_type === 'display'
                    ? 0
                    : $request->is_required,
            ]);

            PostFieldOption::where('fieldID', $field->fieldID)->delete();

            $this->saveOptions($request, $field);
        }

        return redirect()
            ->route('admin.post.form.edit', $form)
            ->with('success', 'Field updated successfully.');
    }

    // Delete field
    public function destroyField($fieldID)
    {
        $field = PostField::findOrFail($fieldID);
        $section = PostSection::findOrFail($field->sectionID);
        $form = PostForm::findOrFail($section->formID);

        $this->ensureStructureEditable($form);

        $sectionID = $field->sectionID;

        PostFieldOption::where('fieldID', $field->fieldID)->delete();

        $field->delete();

        $fields = PostField::where('sectionID', $sectionID)
            ->orderBy('display_order')
            ->get();

        foreach ($fields as $index => $item) {
            $item->update([
                'display_order' => $index + 1,
            ]);
        }

        return redirect()
            ->route('admin.post.form.edit', $form)
            ->with('success', 'Field deleted successfully.');
    }

    // Save checkbox/radio options
    private function saveOptions(Request $request, PostField $field)
    {
        if (!in_array($request->field_type, ['checkbox', 'radio'])) {
            return;
        }

        if (!$request->has('options')) {
            return;
        }

        foreach ($request->options as $index => $option) {

            if (is_null($option) || trim($option) === '') {
                continue;
            }

            PostFieldOption::create([
                'fieldID' => $field->fieldID,
                'option_label' => trim($option),
                'display_order' => $index + 1,
            ]);
        }
    }

    // Check whether version has been used
    private function isFormUsed(PostForm $form): bool
    {
        return DB::table('post_response')
            ->where('formID', $form->formID)
            ->exists();
    }

    // Block structural changes for used version
    private function ensureStructureEditable(PostForm $form): void
    {
        abort_if(
            $this->isFormUsed($form),
            403,
            'This form version has already been used. Create a new version to make structural changes.'
        );
    }
}
