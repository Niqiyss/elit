<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreForm;
use App\Models\PreSection;
use App\Models\PreCriteria;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PreFormController extends Controller
{
    // Version list
    public function index()
    {
        $forms = PreForm::with([
            'sections' => fn($query) => $query->orderBy('display_order'),
            'sections.criteria' => fn($query) => $query->orderBy('display_order'),
        ])->orderByDesc('version')->get();

        foreach ($forms as $form) {
            $form->is_used = $this->isFormUsed($form);
            $form->section_count = $form->sections->count();
            $form->criteria_count = $form->sections->sum(fn($section) => $section->criteria->count());
            $form->max_mark = $form->criteria_count * $form->max_score;
        }

        return view('admin.pre-form', compact('forms'));
    }

    // Preview selected version
    public function preview(PreForm $preForm)
    {
        $preForm->load([
            'sections' => fn($query) => $query->orderBy('display_order'),
            'sections.criteria' => fn($query) => $query->orderBy('display_order'),
        ]);

        $criteriaCount = $preForm->sections->sum(fn($section) => $section->criteria->count());
        $maximumScore = $criteriaCount * $preForm->max_score;

        return view('admin.pre-form-preview', [
            'form' => $preForm,
            'maximumScore' => $maximumScore,
        ]);
    }

    // Edit selected version
    public function edit(PreForm $preForm)
    {
        $preForm->load([
            'sections' => fn($query) => $query->orderBy('display_order'),
            'sections.criteria' => fn($query) => $query->orderBy('display_order'),
        ]);

        $formUsed = $this->isFormUsed($preForm);
        $sectionCount = $preForm->sections->count();
        $criteriaCount = $preForm->sections->sum(fn($section) => $section->criteria->count());
        $maxTotal = $criteriaCount * $preForm->max_score;

        return view('admin.pre-form-edit', compact(
            'preForm',
            'formUsed',
            'sectionCount',
            'criteriaCount',
            'maxTotal'
        ))->with('form', $preForm);
    }

    // Create first form
    public function storeForm(Request $request): RedirectResponse
    {
        $request->validate([
            'form_name' => 'required|string|max:255',
            'instruction' => 'nullable|string',
            'min_score' => 'required|integer|min:0',
            'max_score' => 'required|integer|gt:min_score',
        ]);

        if (PreForm::exists()) {
            return redirect()->route('admin.pre.form')->with('error', 'A Pre-Observation form already exists. Create a new version instead.');
        }

        $form = PreForm::create([
            'form_name' => $request->form_name,
            'version' => 1,
            'instruction' => $request->instruction,
            'min_score' => $request->min_score,
            'max_score' => $request->max_score,
            'status' => 'Active',
            'staffid' => Auth::guard('admin')->id(),
        ]);

        return redirect()->route('admin.pre.form.edit', $form)->with('success', 'Pre-Observation form created successfully.');
    }

    // Create new version
    public function createNewVersion(PreForm $preForm): RedirectResponse
    {
        $preForm->load([
            'sections' => fn($query) => $query->orderBy('display_order'),
            'sections.criteria' => fn($query) => $query->orderBy('display_order'),
        ]);

        $newFormID = null;

        DB::transaction(function () use ($preForm, &$newFormID) {
            PreForm::where('status', 'Active')->update(['status' => 'Inactive']);

            $nextVersion = (PreForm::max('version') ?? 0) + 1;

            $newForm = PreForm::create([
                'form_name' => $preForm->form_name,
                'version' => $nextVersion,
                'instruction' => $preForm->instruction,
                'min_score' => $preForm->min_score,
                'max_score' => $preForm->max_score,
                'status' => 'Active',
                'staffid' => Auth::guard('admin')->id(),
            ]);

            $newFormID = $newForm->formID;

            foreach ($preForm->sections as $oldSection) {
                $newSection = PreSection::create([
                    'formID' => $newForm->formID,
                    'section_name' => $oldSection->section_name,
                    'display_order' => $oldSection->display_order,
                ]);

                foreach ($oldSection->criteria as $oldCriteria) {
                    PreCriteria::create([
                        'sectionID' => $newSection->sectionID,
                        'criteria_label' => $oldCriteria->criteria_label,
                        'display_order' => $oldCriteria->display_order,
                    ]);
                }
            }
        });

        return redirect()->route('admin.pre.form.edit', $newFormID)->with('success', 'New Pre-Observation form version created successfully.');
    }

    // Update form information
    public function updateForm(Request $request, PreForm $preForm): RedirectResponse
    {
        $formUsed = $this->isFormUsed($preForm);

        if ($formUsed) {
            $request->validate([
                'form_name' => 'required|string|max:255',
                'instruction' => 'nullable|string',
            ]);

            $preForm->update([
                'form_name' => $request->form_name,
                'instruction' => $request->instruction,
                'staffid' => Auth::guard('admin')->id(),
            ]);

            return redirect()->route('admin.pre.form.edit', $preForm)->with('success', 'Form information updated successfully.');
        }

        $request->validate([
            'form_name' => 'required|string|max:255',
            'instruction' => 'nullable|string',
            'min_score' => 'required|integer|min:0',
            'max_score' => 'required|integer|gt:min_score',
        ]);

        $preForm->update([
            'form_name' => $request->form_name,
            'instruction' => $request->instruction,
            'min_score' => $request->min_score,
            'max_score' => $request->max_score,
            'staffid' => Auth::guard('admin')->id(),
        ]);

        return redirect()->route('admin.pre.form.edit', $preForm)->with('success', 'Pre-Observation form updated successfully.');
    }

    // Delete unused version
    public function destroyForm(PreForm $preForm): RedirectResponse
    {
        if ($this->isFormUsed($preForm)) {
            return redirect()->route('admin.pre.form')->with('error', 'This version has already been used and cannot be deleted.');
        }

        $wasActive = $preForm->status === 'Active';

        DB::transaction(function () use ($preForm) {

            $sectionIDs = DB::table('pre_section')
                ->where('formID', $preForm->formID)
                ->pluck('sectionID');

            if ($sectionIDs->isNotEmpty()) {

                $criteriaIDs = DB::table('pre_criteria')
                    ->whereIn('sectionID', $sectionIDs)
                    ->pluck('criteriaID');

                if ($criteriaIDs->isNotEmpty()) {
                    DB::table('pre_score')->whereIn('criteriaID', $criteriaIDs)->delete();
                    DB::table('pre_criteria')->whereIn('criteriaID', $criteriaIDs)->delete();
                }

                DB::table('pre_section_comment')->whereIn('sectionID', $sectionIDs)->delete();
                DB::table('pre_section')->whereIn('sectionID', $sectionIDs)->delete();
            }

            DB::table('pre_form')->where('formID', $preForm->formID)->delete();
        });

        if ($wasActive) {
            DB::table('pre_form')->where('status', 'Active')->update(['status' => 'Inactive']);

            $previousForm = PreForm::orderByDesc('version')->first();

            if ($previousForm) {
                $previousForm->update(['status' => 'Active']);
            }
        }

        return redirect()->route('admin.pre.form')->with('success', 'Form version deleted successfully.');
    }

    // Add section
    public function storeSection(Request $request): RedirectResponse
    {
        $request->validate([
            'formID' => 'required|exists:pre_form,formID',
            'section_name' => 'required|string|max:255',
        ]);

        $form = PreForm::findOrFail($request->formID);

        $this->ensureStructureEditable($form);

        $lastOrder = PreSection::where('formID', $form->formID)->max('display_order');

        PreSection::create([
            'formID' => $form->formID,
            'section_name' => $request->section_name,
            'display_order' => ($lastOrder ?? 0) + 1,
        ]);

        return redirect()->route('admin.pre.form.edit', $form)->with('success', 'Section added successfully.');
    }

    // Update section
    public function updateSection(Request $request, $sectionID): RedirectResponse
    {
        $section = PreSection::findOrFail($sectionID);
        $form = PreForm::findOrFail($section->formID);

        $request->validate([
            'section_name' => 'required|string|max:255',
        ]);

        $section->update([
            'section_name' => $request->section_name,
        ]);

        return redirect()->route('admin.pre.form.edit', $form)->with('success', 'Section updated successfully.');
    }

    // Delete section
    public function destroySection($sectionID): RedirectResponse
    {
        $section = PreSection::findOrFail($sectionID);
        $form = PreForm::findOrFail($section->formID);

        $this->ensureStructureEditable($form);

        DB::transaction(function () use ($section) {

            $criteriaIDs = DB::table('pre_criteria')
                ->where('sectionID', $section->sectionID)
                ->pluck('criteriaID');

            if ($criteriaIDs->isNotEmpty()) {

                $hasScores = DB::table('pre_score')
                    ->whereIn('criteriaID', $criteriaIDs)
                    ->exists();

                if ($hasScores) {
                    throw new \RuntimeException('This section contains observation scores and cannot be deleted.');
                }

                DB::table('pre_criteria')
                    ->where('sectionID', $section->sectionID)
                    ->delete();
            }

            DB::table('pre_section_comment')
                ->where('sectionID', $section->sectionID)
                ->delete();

            DB::table('pre_section')
                ->where('sectionID', $section->sectionID)
                ->delete();
        });

        $sections = PreSection::where('formID', $form->formID)
            ->orderBy('display_order')
            ->get();

        foreach ($sections as $index => $item) {
            $item->update(['display_order' => $index + 1]);
        }

        return redirect()
            ->route('admin.pre.form.edit', $form)
            ->with('success', 'Section deleted successfully.');
    }

    // Add criteria
    public function storeCriteria(Request $request): RedirectResponse
    {
        $request->validate([
            'sectionID' => 'required|exists:pre_section,sectionID',
            'criteria_label' => 'required|string|max:500',
        ]);

        $section = PreSection::findOrFail($request->sectionID);
        $form = PreForm::findOrFail($section->formID);

        $this->ensureStructureEditable($form);

        $lastOrder = PreCriteria::where('sectionID', $section->sectionID)->max('display_order');

        PreCriteria::create([
            'sectionID' => $section->sectionID,
            'criteria_label' => $request->criteria_label,
            'display_order' => ($lastOrder ?? 0) + 1,
        ]);

        return redirect()->route('admin.pre.form.edit', $form)->with('success', 'Criteria added successfully.');
    }

    // Update criteria
    public function updateCriteria(Request $request, $criteriaID): RedirectResponse
    {
        $criteria = PreCriteria::findOrFail($criteriaID);
        $section = PreSection::findOrFail($criteria->sectionID);
        $form = PreForm::findOrFail($section->formID);

        $request->validate([
            'criteria_label' => 'required|string|max:500',
        ]);

        $criteria->update([
            'criteria_label' => $request->criteria_label,
        ]);

        return redirect()->route('admin.pre.form.edit', $form)->with('success', 'Criteria updated successfully.');
    }

    // Delete criteria
    public function destroyCriteria($criteriaID): RedirectResponse
    {
        $criteria = PreCriteria::findOrFail($criteriaID);
        $section = PreSection::findOrFail($criteria->sectionID);
        $form = PreForm::findOrFail($section->formID);

        $this->ensureStructureEditable($form);

        $criteria->delete();

        $criteriaList = PreCriteria::where('sectionID', $section->sectionID)->orderBy('display_order')->get();

        foreach ($criteriaList as $index => $item) {
            $item->update(['display_order' => $index + 1]);
        }

        return redirect()->route('admin.pre.form.edit', $form)->with('success', 'Criteria deleted successfully.');
    }

    private function isFormUsed(PreForm $form): bool
    {
        return DB::table('pre_response')->where('formID', $form->formID)->exists();
    }

    private function ensureStructureEditable(PreForm $form): void
    {
        abort_if(
            $this->isFormUsed($form),
            403,
            'This form version has already been used. Create a new version to make structural changes.'
        );
    }
}
