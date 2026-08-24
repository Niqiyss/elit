<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PdpcForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PdpcFormController extends Controller
{
    // Show form builder
    public function index(): View
    {
        $forms = PdpcForm::query()
            ->with([
                'aspects.tums.tt.points',
                'aspects.tums.rubrics',
            ])
            ->latest('formID')
            ->get();

        $initialAspects = old('aspects', $this->blankAspects());

        $editingForm = null;

        return view(
            'admin.pdpc-form',
            compact(
                'editingForm',
                'forms',
                'initialAspects'
            )
        );
    }

    // Store new PDPC form
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $form = DB::transaction(function () use ($validated): PdpcForm {

            PdpcForm::where('status', 'Active')->update([
                'status' => 'Inactive',
            ]);

            $nextVersion = (PdpcForm::max('version_no') ?? 0) + 1;

            $form = PdpcForm::create([
                'form_name' => $validated['form_name'],
                'instruction' => $validated['instruction'] ?? null,
                'version_no' => $nextVersion,
                'status' => 'Active',
                'staffid' => Auth::guard('admin')->id(),
            ]);

            $this->saveHierarchy(
                $form,
                $validated['aspects']
            );

            return $form;
        });

        return redirect()
            ->route(
                'admin.pdpc.form.show',
                $form->formID
            )
            ->with(
                'success',
                'PDPC form saved successfully.'
            )
            ->with(
                'created_form_id',
                $form->formID
            );
    }

    // View saved form
    public function show(PdpcForm $pdpcForm): View
    {
        $pdpcForm->load([
            'aspects.tums.tt.points',
            'aspects.tums.rubrics',
        ]);

        $forms = PdpcForm::query()
            ->with([
                'aspects.tums',
            ])
            ->latest('formID')
            ->get();

        return view(
            'admin.pdpc-form-show',
            compact(
                'forms',
                'pdpcForm'
            )
        );
    }

    // Show edit form
    public function edit(PdpcForm $pdpcForm): View
    {
        $pdpcForm->load([
            'aspects.tums.tt.points',
            'aspects.tums.rubrics',
        ]);

        $forms = PdpcForm::query()
            ->with([
                'aspects.tums',
            ])
            ->latest('formID')
            ->get();

        $initialAspects = old(
            'aspects',
            $this->formAspects($pdpcForm)
        );

        $editingForm = $pdpcForm;

        return view(
            'admin.pdpc-form',
            compact(
                'editingForm',
                'forms',
                'initialAspects'
            )
        );
    }

    // Update existing form
    public function update(
        Request $request,
        PdpcForm $pdpcForm
    ): RedirectResponse {

        if ($pdpcForm->responses()->exists()) {
            return redirect()
                ->route(
                    'admin.pdpc.form.show',
                    $pdpcForm->formID
                )
                ->with(
                    'error',
                    'This form cannot be edited because it has already been used for an observation.'
                );
        }

        $validated = $request->validate($this->rules());

        DB::transaction(function () use (
            $pdpcForm,
            $validated
        ): void {

            $pdpcForm->update([
                'form_name' => $validated['form_name'],
                'instruction' => $validated['instruction'] ?? null,
            ]);

            $pdpcForm->aspects()->delete();

            $this->saveHierarchy(
                $pdpcForm,
                $validated['aspects']
            );
        });

        return redirect()
            ->route(
                'admin.pdpc.form.show',
                $pdpcForm->formID
            )
            ->with(
                'success',
                'PDPC form updated successfully.'
            );
    }

    // Delete form
    public function destroy(
        PdpcForm $pdpcForm
    ): RedirectResponse {

        if ($pdpcForm->responses()->exists()) {
            return redirect()
                ->route(
                    'admin.pdpc.form.show',
                    $pdpcForm->formID
                )
                ->with(
                    'error',
                    'This form cannot be deleted because it has already been used for an observation.'
                );
        }

        $pdpcForm->delete();

        return redirect()
            ->route('admin.pdpc.form')
            ->with(
                'success',
                'PDPC form deleted successfully.'
            );
    }

    // Save complete hierarchy
    private function saveHierarchy(
        PdpcForm $form,
        array $aspects
    ): void {

        foreach (
            array_values($aspects)
            as $aspectIndex => $aspectData
        ) {

            $aspect = $form->aspects()->create([
                'aspect_code' => $aspectData['aspect_code'] ?? null,
                'aspect_name' => $aspectData['aspect_name'],
                'display_order' => $aspectIndex + 1,
            ]);

            foreach (
                array_values($aspectData['tums'])
                as $tumsIndex => $tumsData
            ) {

                $tums = $aspect->tums()->create([
                    'tums_code' => $tumsData['tums_code'] ?? null,
                    'tums_name' => $tumsData['tums_name'],
                    'wajaran' => $tumsData['wajaran'],
                    'display_order' => $tumsIndex + 1,
                ]);

                foreach (
                    array_values($tumsData['tt'])
                    as $ttIndex => $ttData
                ) {

                    $tt = $tums->tt()->create([
                        'display_order' => $ttIndex + 1,
                    ]);

                    foreach (
                        array_values($ttData['points'])
                        as $pointIndex => $pointData
                    ) {

                        $tt->points()->create([
                            'point_text' => $pointData['point_text'],
                            'display_order' => $pointIndex + 1,
                        ]);
                    }
                }

                // Save one RTK rubric set for this TUMS
                foreach (
                    $tumsData['rubrics'] ?? []
                    as $score => $description
                ) {

                    if (
                        $description === null ||
                        trim($description) === ''
                    ) {
                        continue;
                    }

                    $tums->rubrics()->create([
                        'score' => (int) $score,
                        'description' => trim($description),
                    ]);
                }
            }
        }
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

    // Initial blank form
    private function blankAspects(): array
    {
        return [[
            'aspect_code' => '4.1',
            'aspect_name' => '',

            'tums' => [[
                'tums_code' => '4.1.1',
                'tums_name' => '',
                'wajaran' => '',

                'tt' => [[
                    'points' => [[
                        'point_text' => '',
                    ]],
                ]],

                'rubrics' => [
                    4 => '',
                    3 => '',
                    2 => '',
                    1 => '',
                    0 => '',
                ],
            ]],
        ]];
    }

    // Convert database hierarchy for edit form
    private function formAspects(
        PdpcForm $form
    ): array {

        return $form
            ->aspects
            ->map(
                fn($aspect) => [

                    'aspect_code' =>
                    $aspect->aspect_code,

                    'aspect_name' =>
                    $aspect->aspect_name,

                    'tums' =>
                    $aspect
                        ->tums
                        ->map(
                            fn($tums) => [

                                'tums_code' =>
                                $tums->tums_code,

                                'tums_name' =>
                                $tums->tums_name,

                                'wajaran' =>
                                $tums->wajaran,

                                'tt' =>
                                $tums
                                    ->tt
                                    ->map(
                                        fn($tt) => [

                                            'points' =>
                                            $tt
                                                ->points
                                                ->map(
                                                    fn($point) => [
                                                        'point_text' =>
                                                        $point->point_text,
                                                    ]
                                                )
                                                ->values()
                                                ->all(),
                                        ]
                                    )
                                    ->values()
                                    ->all(),

                                'rubrics' =>
                                $tums
                                    ->rubrics
                                    ->pluck(
                                        'description',
                                        'score'
                                    )
                                    ->all(),
                            ]
                        )
                        ->values()
                        ->all(),
                ]
            )
            ->values()
            ->all();
    }
}