<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ Auth::guard('admin')->user()->staffname }}</h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">

            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl px-8 py-6 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    <div>
                        <h1 class="text-3xl font-extrabold text-white">{{ $form->form_name }}</h1>
                        <p class="text-violet-300 mt-2">Manage form information, sections and criteria</p>
                    </div>

                    <div class="flex items-stretch gap-3">

                        <div class="min-w-[110px] bg-white/10 border border-white/10 rounded-2xl px-5 py-3">
                            <p class="text-xs uppercase tracking-wider text-violet-200 font-semibold">Version</p>
                            <p class="text-xl font-bold text-white mt-1">{{ $form->version }}</p>
                        </div>

                        @if($formUsed)

                        <div class="bg-amber-400/10 border border-amber-300/20 rounded-2xl px-5 py-3 flex items-center gap-3">
                            <div>
                                <p class="text-sm font-bold text-white">Form in use</p>
                                <p class="text-xs text-amber-200 mt-0.5">Only criteria/section name can be change</p>
                            </div>
                        </div>

                        @else

                        <div class="bg-blue-400/10 border border-blue-300/20 rounded-2xl px-5 py-3 flex items-center gap-3">
                            <div>
                                <p class="text-sm font-bold text-white">Current Form</p>
                                <p class="text-xs text-blue-200 mt-0.5">Form content can be change</p>
                            </div>
                        </div>

                        @endif

                    </div>

                </div>

            </div>

            @if(session('success'))
            <div class="mb-6 px-5 py-4 bg-green-100 border border-green-200 text-green-700 rounded-xl">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="mb-6 px-5 py-4 bg-red-100 border border-red-200 text-red-700 rounded-xl">{{ session('error') }}</div>
            @endif

            @if($errors->any())
            <div class="mb-6 px-5 py-4 bg-red-100 border border-red-200 text-red-700 rounded-xl">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">

                <div class="px-6 py-5 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Form Information</h2>
                        <p class="text-sm text-gray-400 mt-1">Basic form details and scoring</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm">
                        <span><span class="text-gray-400">Criteria</span> <strong class="text-gray-800 ml-1">{{ $criteriaCount }}</strong></span>
                        <span><span class="text-gray-400">Score Range</span> <strong class="text-gray-800 ml-1">{{ $form->min_score }} - {{ $form->max_score }}</strong></span>
                        <span><span class="text-gray-400">Maximum Total</span> <strong class="text-amber-600 ml-1">{{ $maxTotal }}</strong></span>
                    </div>

                </div>

                <div class="p-6">

                    <form id="formInformation" method="POST" action="{{ route('admin.pre.form.update', $form->formID) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

                            <div class="lg:col-span-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Form Name</label>
                                <input type="text" name="form_name" value="{{ old('form_name', $form->form_name) }}" required class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div class="lg:col-span-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Instruction</label>
                                <input type="text" name="instruction" value="{{ old('instruction', $form->instruction) }}" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div class="lg:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Minimum Score</label>

                                @if($formUsed)
                                <div class="h-[42px] px-4 flex items-center bg-gray-100 border border-gray-200 rounded-xl text-gray-500">{{ $form->min_score }}</div>
                                @else
                                <input type="number" name="min_score" value="{{ old('min_score', $form->min_score) }}" min="0" required class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                @endif
                            </div>

                            <div class="lg:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Maximum Score</label>

                                @if($formUsed)
                                <div class="h-[42px] px-4 flex items-center bg-gray-100 border border-gray-200 rounded-xl text-gray-500">{{ $form->max_score }}</div>
                                @else
                                <input type="number" name="max_score" value="{{ old('max_score', $form->max_score) }}" min="1" required class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                @endif
                            </div>

                        </div>

                    </form>

                </div>

            </div>


            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-24">

                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">

                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Form Content</h2>
                        <p class="text-sm text-gray-400 mt-1">Manage sections and criteria for this version</p>
                    </div>

                    <p class="text-sm text-gray-600">{{ $sectionCount }} Sections · {{ $criteriaCount }} Criteria</p>

                </div>

                @forelse($form->sections as $section)

                <div class="border-b border-gray-100">


                    <div class="px-6 py-5 bg-blue-50">

                        <div class="flex items-center justify-between gap-4">

                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold">{{ $loop->iteration }}</div>

                                <div>
                                    <p class="font-bold text-gray-800">{{ $section->section_name }}</p>
                                    <p class="text-xs text-gray-400 mt-1">Section</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">

                                <button
                                    type="button"
                                    data-section-id="{{ $section->sectionID }}"
                                    onclick="toggleSectionEdit(this)"
                                    class="px-4 py-2 bg-amber-400 hover:bg-amber-500 text-gray-900 text-sm font-semibold rounded-xl">

                                    Edit

                                </button>

                                @if(!$formUsed)

                                <form method="POST" action="{{ route('admin.pre.section.delete', $section->sectionID) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" onclick="return confirm('Delete this section and all its criteria?')" class="w-10 h-10 inline-flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5" />
                                        </svg>
                                    </button>
                                </form>

                                @endif

                            </div>

                        </div>

                    </div>


                    <div id="section-edit-{{ $section->sectionID }}" class="hidden px-6 py-5 bg-slate-50">

                        <form method="POST" action="{{ route('admin.pre.section.update', $section->sectionID) }}">
                            @csrf
                            @method('PUT')

                            <div class="flex items-end gap-4">

                                <div class="flex-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Section Name</label>
                                    <input type="text" name="section_name" value="{{ $section->section_name }}" required class="w-full rounded-xl border-gray-300">
                                </div>

                                <button type="submit" class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl">Update</button>

                            </div>

                        </form>

                    </div>


                    <div class="divide-y divide-gray-100">

                        @foreach($section->criteria as $criteria)

                        <div class="px-6 py-4">

                            <div class="flex items-center justify-between gap-5">

                                <div class="flex items-center gap-4 flex-1">

                                    <span class="w-8 text-center text-sm text-gray-400">{{ $loop->iteration }}</span>

                                    <p class="text-sm text-gray-800">{{ $criteria->criteria_label }}</p>

                                </div>

                                <div class="flex items-center gap-2">

                                    <button
                                        type="button"
                                        data-criteria-id="{{ $criteria->criteriaID }}"
                                        onclick="toggleCriteriaEdit(this)"
                                        class="px-4 py-2 bg-amber-400 hover:bg-amber-500 text-gray-900 text-sm font-semibold rounded-xl">

                                        Edit

                                    </button>

                                    @if(!$formUsed)

                                    <form method="POST" action="{{ route('admin.pre.criteria.delete', $criteria->criteriaID) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" onclick="return confirm('Delete this criteria?')" class="w-10 h-10 inline-flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-xl">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5" />
                                            </svg>
                                        </button>
                                    </form>

                                    @endif

                                </div>

                            </div>


                            <div id="criteria-edit-{{ $criteria->criteriaID }}" class="hidden mt-4 ml-12 p-4 bg-slate-50 rounded-xl">

                                <form method="POST" action="{{ route('admin.pre.criteria.update', $criteria->criteriaID) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="flex items-end gap-4">

                                        <div class="flex-1">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Criteria</label>
                                            <input type="text" name="criteria_label" value="{{ $criteria->criteria_label }}" required class="w-full rounded-xl border-gray-300">
                                        </div>

                                        <button type="submit" class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl">Update</button>

                                    </div>

                                </form>

                            </div>

                        </div>

                        @endforeach

                    </div>


                    @if(!$formUsed)

                    <div class="px-6 py-4 bg-slate-50">

                        <details>

                            <summary class="cursor-pointer text-blue-700 font-semibold text-sm">+ Add Criteria</summary>

                            <form method="POST" action="{{ route('admin.pre.criteria.store') }}" class="mt-4">
                                @csrf

                                <input type="hidden" name="sectionID" value="{{ $section->sectionID }}">

                                <div class="flex items-end gap-4">

                                    <div class="flex-1">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Criteria</label>
                                        <input type="text" name="criteria_label" required class="w-full rounded-xl border-gray-300">
                                    </div>

                                    <button type="submit" class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl">Add</button>

                                </div>

                            </form>

                        </details>

                    </div>

                    @endif

                </div>

                @empty

                <div class="py-12 text-center text-gray-400">No sections added</div>

                @endforelse


                @if(!$formUsed)

                <div class="px-6 py-5 bg-slate-50">

                    <details>

                        <summary class="cursor-pointer text-blue-700 font-semibold text-sm">+ Add Section</summary>

                        <form method="POST" action="{{ route('admin.pre.section.store') }}" class="mt-4">
                            @csrf

                            <input type="hidden" name="formID" value="{{ $form->formID }}">

                            <div class="flex items-end gap-4">

                                <div class="flex-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Section Name</label>
                                    <input type="text" name="section_name" required class="w-full rounded-xl border-gray-300">
                                </div>

                                <button type="submit" class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl">Add</button>

                            </div>

                        </form>

                    </details>

                </div>

                @endif

            </div>

        </div>

    </div>


    <div class="fixed bottom-4 left-0 right-0 z-40 px-6 pointer-events-none">

        <div class="max-w-7xl mx-auto">

            <div class="bg-white/95 backdrop-blur-md border border-gray-200 shadow-xl rounded-2xl px-6 py-4 pointer-events-auto">

                <div class="flex items-center justify-between">

                    <a href="{{ route('admin.pre.form') }}"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl">
                        Back
                    </a>

                    <div class="flex items-center gap-3">

                        <a href="{{ route('admin.pre.form.preview', $form->formID) }}"
                            class="px-5 py-2.5 bg-sky-100 hover:bg-sky-200 text-sky-700 font-semibold text-sm rounded-xl">
                            Preview
                        </a>

                        <button type="submit"
                            form="formInformation"
                            class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-semibold text-sm rounded-xl">
                            Save
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>
        function toggleSectionEdit(button) {
            const row = document.getElementById('section-edit-' + button.dataset.sectionId);
            if (row) row.classList.toggle('hidden');
        }

        function toggleCriteriaEdit(button) {
            const row = document.getElementById('criteria-edit-' + button.dataset.criteriaId);
            if (row) row.classList.toggle('hidden');
        }
    </script>

</x-app-layout>