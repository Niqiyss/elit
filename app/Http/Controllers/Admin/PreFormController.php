<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreForm;
use App\Models\PreSection;
use App\Models\PreCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PreFormController extends Controller
{
    // Show PRE form
    public function index()
    {
        $form = PreForm::with([
            'sections' => function ($query) {
                $query->orderBy('display_order');
            },
            'sections.criteria' => function ($query) {
                $query->orderBy('display_order');
            },
        ])
            ->orderBy('formID')
            ->first();

        return view('admin.pre-form', compact('form'));
    }


    // Create form
    public function storeForm(Request $request)
    {
        $request->validate([
            'form_name' => 'required|string|max:255',
            'instruction' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        if (PreForm::exists()) {
            return redirect()
                ->route('admin.pre.form')
                ->with('error', 'A Pre-Observation form already exists.');
        }

        PreForm::create([
            'form_name' => $request->form_name,
            'instruction' => $request->instruction,
            'status' => $request->status,
            'staffid' => Auth::guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.pre.form')
            ->with('success', 'Pre-Observation form created successfully.');
    }


    // Update form
    public function updateForm(Request $request, $formID)
    {
        $form = PreForm::findOrFail($formID);

        $request->validate([
            'form_name' => 'required|string|max:255',
            'instruction' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $form->update([
            'form_name' => $request->form_name,
            'instruction' => $request->instruction,
            'status' => $request->status,
            'staffid' => Auth::guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.pre.form')
            ->with('success', 'Pre-Observation form updated successfully.');
    }


    // Add section
    public function storeSection(Request $request)
    {
        $request->validate([
            'formID' => 'required|exists:pre_form,formID',
            'section_name' => 'required|string|max:255',
        ]);

        $lastOrder = PreSection::where(
            'formID',
            $request->formID
        )->max('display_order');

        PreSection::create([
            'formID' => $request->formID,
            'section_name' => $request->section_name,
            'display_order' => ($lastOrder ?? 0) + 1,
        ]);

        return redirect()
            ->route('admin.pre.form')
            ->with('success', 'Section added successfully.');
    }


    // Update section
    public function updateSection(Request $request, $sectionID)
    {
        $section = PreSection::findOrFail($sectionID);

        $request->validate([
            'section_name' => 'required|string|max:255',
        ]);

        $section->update([
            'section_name' => $request->section_name,
        ]);

        return redirect()
            ->route('admin.pre.form')
            ->with('success', 'Section updated successfully.');
    }


    // Delete section
    // Delete section only if it has never been used
    public function destroySection($sectionID)
    {
        $section = PreSection::with('criteria')
            ->findOrFail($sectionID);

        $formID = $section->formID;

        $criteriaIDs = $section->criteria
            ->pluck('criteriaID');

        $hasScores = DB::table('pre_score')
            ->whereIn('criteriaID', $criteriaIDs)
            ->exists();

        $hasComments = DB::table('pre_section_comment')
            ->where('sectionID', $sectionID)
            ->exists();

        if ($hasScores || $hasComments) {

            foreach ($section->criteria as $criteria) {
                $criteria->update([
                    'status' => 'Inactive',
                ]);
            }

            return redirect()
                ->route('admin.pre.form')
                ->with(
                    'success',
                    'This section has already been used. Its criteria were set to Inactive instead of being deleted.'
                );
        }

        foreach ($section->criteria as $criteria) {
            $criteria->delete();
        }

        $section->delete();

        $sections = PreSection::where(
            'formID',
            $formID
        )
            ->orderBy('display_order')
            ->get();

        foreach ($sections as $index => $item) {
            $item->display_order = $index + 1;
            $item->save();
        }

        return redirect()
            ->route('admin.pre.form')
            ->with('success', 'Section deleted successfully.');
    }


    // Add criteria
    public function storeCriteria(Request $request)
    {
        $request->validate([
            'sectionID' => 'required|exists:pre_section,sectionID',
            'criteria_label' => 'required|string|max:500',
            'status' => 'required|in:Active,Inactive',
        ]);

        $lastOrder = PreCriteria::where(
            'sectionID',
            $request->sectionID
        )->max('display_order');

        PreCriteria::create([
            'sectionID' => $request->sectionID,
            'criteria_label' => $request->criteria_label,
            'display_order' => ($lastOrder ?? 0) + 1,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.pre.form')
            ->with('success', 'Criteria added successfully.');
    }


    // Update criteria
    public function updateCriteria(Request $request, $criteriaID)
    {
        $criteria = PreCriteria::findOrFail($criteriaID);

        $request->validate([
            'criteria_label' => 'required|string|max:500',
            'status' => 'required|in:Active,Inactive',
        ]);

        $criteria->update([
            'criteria_label' => $request->criteria_label,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.pre.form')
            ->with('success', 'Criteria updated successfully.');
    }


    // Delete or deactivate criteria
    public function destroyCriteria($criteriaID)
    {
        $criteria = PreCriteria::findOrFail($criteriaID);

        $sectionID = $criteria->sectionID;

        $hasBeenUsed = DB::table('pre_score')
            ->where('criteriaID', $criteriaID)
            ->exists();

        if ($hasBeenUsed) {

            $criteria->update([
                'status' => 'Inactive',
            ]);

            return redirect()
                ->route('admin.pre.form')
                ->with(
                    'success',
                    'This criteria has already been used, so it was set to Inactive instead of being deleted.'
                );
        }

        $criteria->delete();

        $criteriaList = PreCriteria::where(
            'sectionID',
            $sectionID
        )
            ->orderBy('display_order')
            ->get();

        foreach ($criteriaList as $index => $item) {
            $item->display_order = $index + 1;
            $item->save();
        }

        return redirect()
            ->route('admin.pre.form')
            ->with('success', 'Criteria deleted successfully.');
    }
}
