<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PdpcForm;
use App\Models\PdpcAspect;
use App\Models\PdpcTums;
use App\Models\PdpcTt;
use App\Models\PdpcTtPoint;
use App\Models\PdpcRubric;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PdpcFormController extends Controller
{
    // Version list
    public function index(): View
    {
        $forms = PdpcForm::with([
            'aspects.tums.tt.points',
            'aspects.tums.rubrics',
        ])
            ->orderByDesc('version_no')
            ->get();

        foreach ($forms as $form) {
            $form->is_used = $this->isFormUsed($form);
            $form->aspect_count = $form->aspects->count();
            $form->tums_count = $form->aspects->sum(fn($aspect) => $aspect->tums->count());

            $form->point_count = $form->aspects->sum(function ($aspect) {
                return $aspect->tums->sum(function ($tums) {
                    return $tums->tt->sum(fn($tt) => $tt->points->count());
                });
            });
        }

        return view('admin.pdpc-form', compact('forms'));
    }

    // Create first form
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'form_name' => ['required', 'string', 'max:255'],
            'instruction' => ['nullable', 'string', 'max:2000'],
        ]);

        if (PdpcForm::exists()) {
            return redirect()->route('admin.pdpc.form')->with('error', 'A PDPC form already exists. Create a new version instead.');
        }

        $form = PdpcForm::create([
            'form_name' => $request->form_name,
            'instruction' => $request->instruction,
            'version_no' => 1,
            'status' => 'Active',
            'staffid' => Auth::guard('admin')->id(),
        ]);

        return redirect()->route('admin.pdpc.form.edit', $form)->with('success', 'PDPC form created. You can now add the form content.');
    }

    // Preview selected version
    public function preview(PdpcForm $pdpcForm): View
    {
        $pdpcForm->load([
            'aspects.tums.tt.points',
            'aspects.tums.rubrics',
        ]);

        return view('admin.pdpc-form-preview', [
            'form' => $pdpcForm,
        ]);
    }

    // Edit selected version
    public function edit(PdpcForm $pdpcForm): View
    {
        $pdpcForm->load([
            'aspects.tums.tt.points',
            'aspects.tums.rubrics',
        ]);

        $formUsed = $this->isFormUsed($pdpcForm);

        $initialAspects = old(
            'aspects',
            $this->formAspects($pdpcForm)
        );

        return view('admin.pdpc-form-edit', [
            'form' => $pdpcForm,
            'formUsed' => $formUsed,
            'initialAspects' => $initialAspects,
        ]);
    }

    // Create new version
    public function createNewVersion(PdpcForm $pdpcForm): RedirectResponse
    {
        $pdpcForm->load([
            'aspects.tums.tt.points',
            'aspects.tums.rubrics',
        ]);

        $newFormID = null;

        DB::transaction(function () use ($pdpcForm, &$newFormID) {

            PdpcForm::where('status', 'Active')->update([
                'status' => 'Inactive',
            ]);

            $nextVersion = (PdpcForm::max('version_no') ?? 0) + 1;

            $newForm = PdpcForm::create([
                'form_name' => $pdpcForm->form_name,
                'instruction' => $pdpcForm->instruction,
                'version_no' => $nextVersion,
                'status' => 'Active',
                'staffid' => Auth::guard('admin')->id(),
            ]);

            $newFormID = $newForm->formID;

            foreach ($pdpcForm->aspects as $oldAspect) {

                $newAspect = PdpcAspect::create([
                    'formID' => $newForm->formID,
                    'aspect_code' => $oldAspect->aspect_code,
                    'aspect_name' => $oldAspect->aspect_name,
                    'display_order' => $oldAspect->display_order,
                ]);

                foreach ($oldAspect->tums as $oldTums) {

                    $newTums = PdpcTums::create([
                        'aspectID' => $newAspect->aspectID,
                        'tums_code' => $oldTums->tums_code,
                        'tums_name' => $oldTums->tums_name,
                        'wajaran' => $oldTums->wajaran,
                        'display_order' => $oldTums->display_order,
                    ]);

                    foreach ($oldTums->tt as $oldTt) {

                        $newTt = PdpcTt::create([
                            'tumsID' => $newTums->tumsID,
                            'display_order' => $oldTt->display_order,
                        ]);

                        foreach ($oldTt->points as $oldPoint) {

                            PdpcTtPoint::create([
                                'ttID' => $newTt->ttID,
                                'point_text' => $oldPoint->point_text,
                                'display_order' => $oldPoint->display_order,
                            ]);
                        }
                    }

                    foreach ($oldTums->rubrics as $oldRubric) {

                        PdpcRubric::create([
                            'tumsID' => $newTums->tumsID,
                            'score' => $oldRubric->score,
                            'description' => $oldRubric->description,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('admin.pdpc.form.edit', $newFormID)
            ->with('success', 'New PDPC form version created successfully.');
    }

    // Update selected version
    public function update(
        Request $request,
        PdpcForm $pdpcForm
    ): RedirectResponse {

        $formUsed = $this->isFormUsed($pdpcForm);

        // Used form: wording only
        if ($formUsed) {

            $validated = $request->validate([
                'form_name' => ['required', 'string', 'max:255'],
                'instruction' => ['nullable', 'string', 'max:2000'],

                'aspects' => ['required', 'array'],
                'aspects.*.aspectID' => ['required', 'integer'],
                'aspects.*.aspect_name' => ['required', 'string', 'max:255'],

                'aspects.*.tums' => ['required', 'array'],
                'aspects.*.tums.*.tumsID' => ['required', 'integer'],
                'aspects.*.tums.*.tums_name' => ['required', 'string', 'max:500'],

                'aspects.*.tums.*.tt' => ['required', 'array'],
                'aspects.*.tums.*.tt.*.ttID' => ['required', 'integer'],
                'aspects.*.tums.*.tt.*.points' => ['required', 'array'],
                'aspects.*.tums.*.tt.*.points.*.pointID' => ['required', 'integer'],
                'aspects.*.tums.*.tt.*.points.*.point_text' => ['required', 'string', 'max:2000'],

                'aspects.*.tums.*.rubrics' => ['required', 'array:0,1,2,3,4'],
                'aspects.*.tums.*.rubrics.*' => ['required', 'string', 'max:2000'],
            ]);

            DB::transaction(function () use ($pdpcForm, $validated) {

                // Update form wording
                $pdpcForm->update([
                    'form_name' => $validated['form_name'],
                    'instruction' => $validated['instruction'] ?? null,
                    'staffid' => Auth::guard('admin')->id(),
                ]);

                foreach ($validated['aspects'] as $aspectData) {

                    // Update Aspect name only
                    $aspect = PdpcAspect::where('aspectID', $aspectData['aspectID'])
                        ->where('formID', $pdpcForm->formID)
                        ->firstOrFail();

                    $aspect->update([
                        'aspect_name' => $aspectData['aspect_name'],
                    ]);

                    foreach ($aspectData['tums'] as $tumsData) {

                        // Update TUMS name only
                        $tums = PdpcTums::where('tumsID', $tumsData['tumsID'])
                            ->where('aspectID', $aspect->aspectID)
                            ->firstOrFail();

                        $tums->update([
                            'tums_name' => $tumsData['tums_name'],
                        ]);

                        foreach ($tumsData['tt'] as $ttData) {

                            // Ensure TT still belongs to this TUMS
                            $tt = PdpcTt::where('ttID', $ttData['ttID'])
                                ->where('tumsID', $tums->tumsID)
                                ->firstOrFail();

                            foreach ($ttData['points'] as $pointData) {

                                // Update TT Point wording only
                                PdpcTtPoint::where('pointID', $pointData['pointID'])
                                    ->where('ttID', $tt->ttID)
                                    ->update([
                                        'point_text' => $pointData['point_text'],
                                    ]);
                            }
                        }

                        // Update RTK wording only
                        foreach ($tumsData['rubrics'] as $score => $description) {

                            PdpcRubric::where('tumsID', $tums->tumsID)
                                ->where('score', (int) $score)
                                ->update([
                                    'description' => trim($description),
                                ]);
                        }
                    }
                }
            });

            return redirect()
                ->route('admin.pdpc.form.edit', $pdpcForm)
                ->with('success', 'Form wording updated successfully.');
        }

        // Unused form: everything can still be changed
        $validated = $request->validate($this->rules());

        DB::transaction(function () use ($pdpcForm, $validated) {

            $pdpcForm->update([
                'form_name' => $validated['form_name'],
                'instruction' => $validated['instruction'] ?? null,
                'staffid' => Auth::guard('admin')->id(),
            ]);

            $this->deleteHierarchy($pdpcForm);

            $this->saveHierarchy(
                $pdpcForm,
                $validated['aspects']
            );
        });

        return redirect()
            ->route('admin.pdpc.form.edit', $pdpcForm)
            ->with('success', 'PDPC form updated successfully.');
    }

    // Delete unused version
    public function destroy(PdpcForm $pdpcForm): RedirectResponse
    {
        if ($this->isFormUsed($pdpcForm)) {
            return redirect()
                ->route('admin.pdpc.form')
                ->with('error', 'This version has already been used and cannot be deleted.');
        }

        $wasActive = $pdpcForm->status === 'Active';

        DB::transaction(function () use ($pdpcForm) {

            $this->deleteHierarchy($pdpcForm);

            DB::table('pdpc_form')
                ->where('formID', $pdpcForm->formID)
                ->delete();
        });

        if ($wasActive) {

            PdpcForm::where('status', 'Active')->update([
                'status' => 'Inactive',
            ]);

            $previousForm = PdpcForm::orderByDesc('version_no')->first();

            if ($previousForm) {
                $previousForm->update([
                    'status' => 'Active',
                ]);
            }
        }

        return redirect()
            ->route('admin.pdpc.form')
            ->with('success', 'PDPC form version deleted successfully.');
    }

    // Save hierarchy
    private function saveHierarchy(
        PdpcForm $form,
        array $aspects
    ): void {

        foreach (array_values($aspects) as $aspectIndex => $aspectData) {

            $aspect = PdpcAspect::create([
                'formID' => $form->formID,
                'aspect_code' => $aspectData['aspect_code'] ?? null,
                'aspect_name' => $aspectData['aspect_name'],
                'display_order' => $aspectIndex + 1,
            ]);

            foreach (array_values($aspectData['tums']) as $tumsIndex => $tumsData) {

                $tums = PdpcTums::create([
                    'aspectID' => $aspect->aspectID,
                    'tums_code' => $tumsData['tums_code'] ?? null,
                    'tums_name' => $tumsData['tums_name'],
                    'wajaran' => $tumsData['wajaran'],
                    'display_order' => $tumsIndex + 1,
                ]);

                foreach (array_values($tumsData['tt']) as $ttIndex => $ttData) {

                    $tt = PdpcTt::create([
                        'tumsID' => $tums->tumsID,
                        'display_order' => $ttIndex + 1,
                    ]);

                    foreach (array_values($ttData['points']) as $pointIndex => $pointData) {

                        PdpcTtPoint::create([
                            'ttID' => $tt->ttID,
                            'point_text' => $pointData['point_text'],
                            'display_order' => $pointIndex + 1,
                        ]);
                    }
                }

                foreach ($tumsData['rubrics'] ?? [] as $score => $description) {

                    if ($description === null || trim($description) === '') {
                        continue;
                    }

                    PdpcRubric::create([
                        'tumsID' => $tums->tumsID,
                        'score' => (int) $score,
                        'description' => trim($description),
                    ]);
                }
            }
        }
    }

    // Delete hierarchy safely
    private function deleteHierarchy(PdpcForm $form): void
    {
        $aspectIDs = DB::table('pdpc_aspect')
            ->where('formID', $form->formID)
            ->pluck('aspectID');

        if ($aspectIDs->isEmpty()) {
            return;
        }

        $tumsIDs = DB::table('pdpc_tums')
            ->whereIn('aspectID', $aspectIDs)
            ->pluck('tumsID');

        if ($tumsIDs->isNotEmpty()) {

            $ttIDs = DB::table('pdpc_tt')
                ->whereIn('tumsID', $tumsIDs)
                ->pluck('ttID');

            if ($ttIDs->isNotEmpty()) {

                $pointIDs = DB::table('pdpc_tt_point')
                    ->whereIn('ttID', $ttIDs)
                    ->pluck('pointID');

                if ($pointIDs->isNotEmpty()) {
                    DB::table('pdpc_score')
                        ->whereIn('pointID', $pointIDs)
                        ->delete();

                    DB::table('pdpc_tt_point')
                        ->whereIn('pointID', $pointIDs)
                        ->delete();
                }

                DB::table('pdpc_tt')
                    ->whereIn('ttID', $ttIDs)
                    ->delete();
            }

            DB::table('pdpc_rubric')
                ->whereIn('tumsID', $tumsIDs)
                ->delete();

            DB::table('pdpc_tums')
                ->whereIn('tumsID', $tumsIDs)
                ->delete();
        }

        DB::table('pdpc_aspect')
            ->whereIn('aspectID', $aspectIDs)
            ->delete();
    }

    // Check version usage
    private function isFormUsed(PdpcForm $form): bool
    {
        return DB::table('pdpc_response')
            ->where('formID', $form->formID)
            ->exists();
    }

    // Create form with hierarchy
    private function createFormWithHierarchy(
        int $version,
        string $formName,
        ?string $instruction,
        array $aspects
    ): PdpcForm {

        PdpcForm::where('status', 'Active')->update([
            'status' => 'Inactive',
        ]);

        $form = PdpcForm::create([
            'form_name' => $formName,
            'instruction' => $instruction,
            'version_no' => $version,
            'status' => 'Active',
            'staffid' => Auth::guard('admin')->id(),
        ]);

        $this->saveHierarchy(
            $form,
            $aspects
        );

        return $form;
    }

    // Validation
    private function rules(): array
    {
        return [
            'form_name' => ['required', 'string', 'max:255'],
            'instruction' => ['nullable', 'string', 'max:2000'],

            'aspects' => ['required', 'array', 'min:1'],
            'aspects.*.aspect_code' => ['nullable', 'string', 'max:30'],
            'aspects.*.aspect_name' => ['required', 'string', 'max:255'],

            'aspects.*.tums' => ['required', 'array', 'min:1'],
            'aspects.*.tums.*.tums_code' => ['nullable', 'string', 'max:30'],
            'aspects.*.tums.*.tums_name' => ['required', 'string', 'max:500'],
            'aspects.*.tums.*.wajaran' => ['required', 'numeric', 'min:0', 'max:100'],

            'aspects.*.tums.*.tt' => ['required', 'array', 'min:1'],
            'aspects.*.tums.*.tt.*.points' => ['required', 'array', 'min:1'],
            'aspects.*.tums.*.tt.*.points.*.point_text' => ['required', 'string', 'max:2000'],

            'aspects.*.tums.*.rubrics' => ['required', 'array:0,1,2,3,4'],
            'aspects.*.tums.*.rubrics.0' => ['required', 'string', 'max:2000'],
            'aspects.*.tums.*.rubrics.1' => ['required', 'string', 'max:2000'],
            'aspects.*.tums.*.rubrics.2' => ['required', 'string', 'max:2000'],
            'aspects.*.tums.*.rubrics.3' => ['required', 'string', 'max:2000'],
            'aspects.*.tums.*.rubrics.4' => ['required', 'string', 'max:2000'],
        ];
    }

    // Convert DB hierarchy for edit
    private function formAspects(PdpcForm $form): array
    {
        return $form->aspects->map(fn($aspect) => [

            'aspectID' => $aspect->aspectID,
            'aspect_code' => $aspect->aspect_code,
            'aspect_name' => $aspect->aspect_name,

            'tums' => $aspect->tums->map(fn($tums) => [

                'tumsID' => $tums->tumsID,
                'tums_code' => $tums->tums_code,
                'tums_name' => $tums->tums_name,
                'wajaran' => $tums->wajaran,

                'tt' => $tums->tt->map(fn($tt) => [

                    'ttID' => $tt->ttID,

                    'points' => $tt->points->map(fn($point) => [
                        'pointID' => $point->pointID,
                        'point_text' => $point->point_text,
                    ])->values()->all(),

                ])->values()->all(),

                'rubrics' => $tums->rubrics
                    ->pluck('description', 'score')
                    ->all(),

            ])->values()->all(),

        ])->values()->all();
    }
}
