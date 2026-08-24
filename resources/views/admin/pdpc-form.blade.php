<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('admin')->user()->staffname }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">

            {{-- Header --}}
            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <h1 class="text-3xl font-extrabold text-white">
                        Manage External Observation Form
                    </h1>

                    <p class="text-violet-200 mt-2">
                        {{ $editingForm
                            ? 'Update Aspect, TUMS, Tahap Tindakan and Rubric'
                            : 'Manage form' }}
                    </p>

                    {{-- Content Guide --}}
                    <div class="mt-5 pt-4 border-t border-white/10">

                        <p class="text-sm font-semibold text-violet-200 mb-3">
                            Content Guide
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-sm">

                            <div class="rounded-xl bg-white/10 px-4 py-3">
                                <p class="font-bold text-white">
                                    1. Aspect
                                </p>

                                <p class="text-violet-200 mt-1">
                                    Add the main observation aspect
                                </p>
                            </div>

                            <div class="rounded-xl bg-white/10 px-4 py-3">
                                <p class="font-bold text-white">
                                    2. TUMS
                                </p>

                                <p class="text-violet-200 mt-1">
                                    Add TUMS and its weight
                                </p>
                            </div>

                            <div class="rounded-xl bg-white/10 px-4 py-3">
                                <p class="font-bold text-white">
                                    3. Tahap Tindakan
                                </p>

                                <p class="text-violet-200 mt-1">
                                    Add one or more TT points
                                </p>
                            </div>

                            <div class="rounded-xl bg-white/10 px-4 py-3">
                                <p class="font-bold text-white">
                                    4. RTK Rubric
                                </p>

                                <p class="text-violet-200 mt-1">
                                    Add one RTK 0 to RTK 4 rubric for each TUMS
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Success --}}
            @if(session('success'))

                <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800">

                    <p class="font-bold">
                        Form saved successfully
                    </p>

                    <p class="mt-1 text-sm">
                        {{ session('success') }}

                        @if(session('created_form_id'))
                            Form ID: #{{ session('created_form_id') }}
                        @endif
                    </p>

                </div>

            @endif

            {{-- Error --}}
            @if(session('error'))

                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
                    {{ session('error') }}
                </div>

            @endif

            {{-- Validation --}}
            @if($errors->any())

                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">

                    <p class="font-bold">
                        Form cannot be saved. Please correct the following:
                    </p>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif

            <div class="grid grid-cols-1 2xl:grid-cols-3 gap-8 items-start">

                {{-- Builder --}}
                <div
                    class="2xl:col-span-2"
                    x-data="pdpcFormBuilder({{ $editingForm ? 'true' : 'false' }})">

                    <form
                        method="POST"
                        action="{{ $editingForm
                            ? route('admin.pdpc.form.update', $editingForm)
                            : route('admin.pdpc.form.store') }}"
                        class="space-y-8">

                        @csrf

                        @if($editingForm)
                            @method('PUT')
                        @endif

                        {{-- Form Information --}}
                        <section class="bg-white rounded-3xl shadow-lg overflow-hidden">

                            <div class="px-7 py-6 border-b border-gray-100">

                                <h2 class="text-xl font-bold text-gray-800">
                                    Form Information
                                </h2>

                                <p class="text-sm text-gray-400 mt-1">
                                    Manage form name and instruction
                                </p>

                            </div>

                            <div class="p-7">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                    <div>

                                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                                            Form Name
                                        </label>

                                        <input
                                            type="text"
                                            name="form_name"
                                            required
                                            value="{{ old(
                                                'form_name',
                                                $editingForm?->form_name
                                                ?? 'PDPC Observation Form'
                                            ) }}"
                                            class="w-full rounded-xl border-slate-300 focus:border-purple-500 focus:ring-purple-500">

                                    </div>

                                    <div>

                                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                                            Version
                                        </label>

                                        <input
                                            type="text"
                                            value="{{ $editingForm?->version_no ?? 'New' }}"
                                            disabled
                                            class="w-full rounded-xl border-slate-300 bg-slate-100 text-slate-500">

                                    </div>

                                    <div class="md:col-span-2">

                                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                                            Instruction
                                        </label>

                                        <textarea
                                            name="instruction"
                                            rows="3"
                                            placeholder="Enter form instruction..."
                                            class="w-full rounded-xl border-slate-300 focus:border-purple-500 focus:ring-purple-500">{{ old(
                                                'instruction',
                                                $editingForm?->instruction
                                            ) }}</textarea>

                                    </div>

                                </div>

                            </div>

                        </section>

                        {{-- Aspects --}}
                        <template
                            x-for="(aspect, aspectIndex) in aspects"
                            :key="aspect._key">

                            <section class="bg-white rounded-3xl shadow-lg overflow-hidden">

                                {{-- Aspect Header --}}
                                <div class="bg-blue-900 px-7 py-6 text-white flex flex-col gap-5 md:flex-row md:items-end md:justify-between">

                                    <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">

                                        <div>

                                            <label class="block text-xs font-bold uppercase tracking-wider text-white mb-2">
                                                Aspect Code
                                            </label>

                                            <input
                                                type="text"
                                                x-model="aspect.aspect_code"
                                                :name="`aspects[${aspectIndex}][aspect_code]`"
                                                placeholder="Example 4.1"
                                                class="w-full rounded-xl border-blue-500 bg-white text-black focus:border-blue-300 focus:ring-blue-300">

                                        </div>

                                        <div class="md:col-span-3">

                                            <label class="block text-xs font-bold uppercase tracking-wider text-white mb-2">
                                                Aspect
                                            </label>

                                            <input
                                                type="text"
                                                x-model="aspect.aspect_name"
                                                required
                                                :name="`aspects[${aspectIndex}][aspect_name]`"
                                                placeholder="Example: Guru Sebagai Perancang"
                                                class="w-full rounded-xl border-blue-500 bg-white text-gray-800 font-normal focus:border-blue-300 focus:ring-blue-300">

                                        </div>

                                    </div>

                                    <button
                                        type="button"
                                        x-show="aspects.length > 1"
                                        @click="removeAspect(aspectIndex)"
                                        class="shrink-0 px-4 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold transition">

                                        Remove Aspect

                                    </button>

                                </div>

                                {{-- TUMS --}}
                                <div class="p-6 md:p-8 space-y-6 bg-blue-50/40">

                                    <template
                                        x-for="(tum, tumIndex) in aspect.tums"
                                        :key="tum._key">

                                        <div class="rounded-2xl border border-blue-200 bg-white overflow-hidden">

                                            {{-- TUMS Header --}}
                                            <div class="p-5 bg-blue-50 border-b border-blue-100">

                                                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">

                                                    <div>

                                                        <label class="block text-xs font-bold uppercase tracking-wider text-blue-700 mb-2">
                                                            TUMS Code
                                                        </label>

                                                        <input
                                                            type="text"
                                                            x-model="tum.tums_code"
                                                            :name="`aspects[${aspectIndex}][tums][${tumIndex}][tums_code]`"
                                                            placeholder="Example: 4.1.1"
                                                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                                                    </div>

                                                    <div class="md:col-span-3">

                                                        <label class="block text-xs font-bold uppercase tracking-wider text-blue-700 mb-2">
                                                            TUMS
                                                        </label>

                                                        <input
                                                            type="text"
                                                            x-model="tum.tums_name"
                                                            required
                                                            :name="`aspects[${aspectIndex}][tums][${tumIndex}][tums_name]`"
                                                            placeholder="Contoh: Guru merancang pelaksanaan PdPc"
                                                            class="w-full rounded-xl border-gray-300 font-normal focus:border-blue-500 focus:ring-blue-500">

                                                    </div>

                                                    <div>

                                                        <label class="block text-xs font-bold uppercase tracking-wider text-blue-700 mb-2">
                                                            Wajaran
                                                        </label>

                                                        <input
                                                            type="number"
                                                            x-model="tum.wajaran"
                                                            required
                                                            min="0"
                                                            max="100"
                                                            step="0.01"
                                                            :name="`aspects[${aspectIndex}][tums][${tumIndex}][wajaran]`"
                                                            placeholder="10"
                                                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                                                    </div>

                                                    <button
                                                        type="button"
                                                        x-show="aspect.tums.length > 1"
                                                        @click="removeTum(aspectIndex, tumIndex)"
                                                        class="px-4 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-700 text-sm font-semibold">

                                                        Remove TUMS

                                                    </button>

                                                </div>

                                            </div>

                                            {{-- TT --}}
                                            <div class="p-5 space-y-4">

                                                <template
                                                    x-for="(tt, ttIndex) in tum.tt"
                                                    :key="tt._key">

                                                    <div class="rounded-xl bg-slate-50 p-4 border border-slate-200">

                                                        <div class="flex items-center justify-between gap-4 pb-3">

                                                            <p class="text-xs font-bold uppercase tracking-wider text-blue-700">
                                                                Tahap Tindakan (TT)
                                                            </p>

                                                            <button
                                                                type="button"
                                                                x-show="tum.tt.length > 1"
                                                                @click="removeTt(aspectIndex, tumIndex, ttIndex)"
                                                                class="w-10 h-10 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 font-bold">

                                                                &times;

                                                            </button>

                                                        </div>

                                                        {{-- TT Points --}}
                                                        <div class="space-y-3">

                                                            <template
                                                                x-for="(point, pointIndex) in tt.points"
                                                                :key="point._key">

                                                                <div class="grid grid-cols-1 sm:grid-cols-[1fr_2.5rem] gap-3 items-start">

                                                                    <textarea
                                                                        x-model="point.point_text"
                                                                        required
                                                                        rows="2"
                                                                        :name="`aspects[${aspectIndex}][tums][${tumIndex}][tt][${ttIndex}][points][${pointIndex}][point_text]`"
                                                                        placeholder="Enter Tahap Tindakan point"
                                                                        class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>

                                                                    <button
                                                                        type="button"
                                                                        x-show="tt.points.length > 1"
                                                                        @click="removePoint(aspectIndex, tumIndex, ttIndex, pointIndex)"
                                                                        class="h-10 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 font-bold">

                                                                        &times;

                                                                    </button>

                                                                </div>

                                                            </template>

                                                        </div>

                                                        <button
                                                            type="button"
                                                            @click="addPoint(aspectIndex, tumIndex, ttIndex)"
                                                            class="mt-3 px-4 py-2 rounded-xl bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm font-semibold">

                                                            + Add TT Point

                                                        </button>

                                                    </div>

                                                </template>

                                                <button
                                                    type="button"
                                                    @click="addTt(aspectIndex, tumIndex)"
                                                    class="px-4 py-2 rounded-xl bg-violet-100 hover:bg-violet-200 text-violet-700 text-sm font-semibold">

                                                    + Add Tahap Tindakan

                                                </button>

                                                {{-- RTK Rubric --}}
                                                <details
                                                    class="rounded-xl border border-violet-200 bg-violet-50/60"
                                                    open>

                                                    <summary
                                                        class="cursor-pointer list-none px-4 py-3 font-bold text-sm text-violet-900 flex items-center justify-between gap-3">

                                                        <span>
                                                            RUBRIK TAHAP KUALITI (RTK)
                                                        </span>

                                                        <span class="text-xs font-semibold text-violet-700">
                                                            RTK 0 - RTK 4
                                                        </span>

                                                    </summary>

                                                    <div class="border-t border-violet-200 p-4 space-y-3">

                                                        <template
                                                            x-for="score in [4, 3, 2, 1, 0]"
                                                            :key="score">

                                                            <div class="grid grid-cols-[4.2rem_1fr] gap-3 items-start">

                                                                <div
                                                                    class="h-8 px-2 rounded-lg bg-violet-600 text-white flex items-center justify-center text-xs font-bold"
                                                                    x-text="`RTK ${score}`">
                                                                </div>

                                                                <textarea
                                                                    x-model="tum.rubrics[score]"
                                                                    rows="4"
                                                                    :name="`aspects[${aspectIndex}][tums][${tumIndex}][rubrics][${score}]`"
                                                                    :placeholder="`Enter rubric for RTK ${score}`"
                                                                    class="w-full rounded-xl text-sm border-violet-200 focus:border-violet-500 focus:ring-violet-500"></textarea>

                                                            </div>

                                                        </template>

                                                    </div>

                                                </details>

                                            </div>

                                        </div>

                                    </template>

                                    <button
                                        type="button"
                                        @click="addTum(aspectIndex)"
                                        class="px-4 py-2.5 rounded-xl bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm font-semibold">

                                        + Add TUMS

                                    </button>

                                </div>

                            </section>

                        </template>

                        {{-- Sticky Action Bar --}}
                        <div class="sticky bottom-4 z-40 mt-8">

                            <div class="bg-white/95 backdrop-blur-sm border border-slate-200 shadow-xl rounded-2xl px-6 py-4">

                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                                    <button
                                        type="button"
                                        @click="addAspect()"
                                        class="px-5 py-2.5 rounded-xl bg-violet-100 hover:bg-violet-200 text-violet-800 font-semibold text-sm transition">

                                        + Add Aspect

                                    </button>

                                    <div class="flex items-center gap-3 flex-shrink-0">

                                        <a
                                            href="{{ route('admin.manage.form') }}"
                                            class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-900 font-semibold text-sm rounded-xl transition">

                                            Back

                                        </a>

                                        @if($editingForm)

                                            <a
                                                href="{{ route(
                                                    'admin.pdpc.form.show',
                                                    $editingForm
                                                ) }}"
                                                class="px-5 py-2.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-sm rounded-xl transition">

                                                Cancel

                                            </a>

                                        @endif

                                        <button
                                            type="submit"
                                            @if($editingForm)
                                                :disabled="!hasChanges"
                                                :class="hasChanges
                                                    ? 'bg-blue-700 hover:bg-blue-800 text-white'
                                                    : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                                                class="px-6 py-2.5 rounded-xl font-semibold text-sm shadow-sm transition"
                                            @else
                                                class="px-6 py-2.5 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-semibold text-sm shadow-sm transition"
                                            @endif>

                                            {{ $editingForm
                                                ? 'Save Changes'
                                                : 'Save' }}

                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

                {{-- Saved Forms --}}
                <aside class="2xl:sticky 2xl:top-0 space-y-5">

                    <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                        <div class="px-6 py-5 border-b border-gray-100">

                            <h2 class="text-lg font-bold text-gray-800">
                                Saved Forms
                            </h2>

                            <p class="text-sm text-slate-400 mt-1">
                                PDPC forms created by Admin
                            </p>

                        </div>

                        <div class="p-5 space-y-4">

                            @forelse($forms as $form)

                                @php($isSelected = $editingForm?->formID === $form->formID)

                                <article
                                    @class([
                                        'rounded-2xl border p-4 transition',
                                        'border-blue-500 bg-blue-50 ring-2 ring-blue-200' => $isSelected,
                                        'border-gray-200 bg-white' => !$isSelected,
                                    ])>

                                    <div class="flex items-start justify-between gap-3">

                                        <div>

                                            <h3 class="font-bold text-gray-800">
                                                {{ $form->form_name }}
                                            </h3>

                                            <p class="mt-1 text-xs text-gray-500">
                                                Version {{ $form->version_no }}
                                            </p>

                                        </div>

                                        <span
                                            @class([
                                                'rounded-full px-3 py-1.5 text-xs font-bold',
                                                'bg-blue-600 text-white' => $isSelected,
                                                'bg-emerald-800 text-white' =>
                                                    !$isSelected &&
                                                    $form->status === 'Active',
                                                'bg-slate-100 text-slate-600' =>
                                                    !$isSelected &&
                                                    $form->status !== 'Active',
                                            ])>

                                            {{ $isSelected
                                                ? 'Editing'
                                                : $form->status }}

                                        </span>

                                    </div>

                                    <p class="mt-3 text-xs text-slate-500">

                                        {{ $form->aspects->count() }}
                                        Aspect

                                        &middot;

                                        {{
                                            $form->aspects->sum(
                                                fn($aspect) =>
                                                    $aspect->tums->count()
                                            )
                                        }}
                                        TUMS

                                    </p>

                                    <div class="mt-4 grid grid-cols-2 gap-2">

                                        <a
                                            href="{{ route(
                                                'admin.pdpc.form.show',
                                                $form
                                            ) }}"
                                            class="rounded-xl bg-sky-100 px-3 py-2 text-center text-sm font-bold text-sky-800 hover:bg-sky-200">

                                            View

                                        </a>

                                        <a
                                            href="{{ route(
                                                'admin.pdpc.form.edit',
                                                $form
                                            ) }}"
                                            class="rounded-xl bg-blue-100 px-3 py-2 text-center text-sm font-bold text-blue-800 hover:bg-blue-200">

                                            Edit

                                        </a>

                                    </div>

                                </article>

                            @empty

                                <div class="text-center py-10 text-slate-400">
                                    <p>No PDPC forms created yet</p>
                                </div>

                            @endforelse

                        </div>

                    </div>

                </aside>

            </div>

        </div>

    </div>

    {{-- Initial Data --}}
    <script type="application/json" id="pdpc-initial-aspects">@json($initialAspects)</script>

    {{-- Alpine --}}
    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.data('pdpcFormBuilder', (isEditing) => ({

                aspects: [],
                nextKey: 0,
                isEditing,
                originalData: '',

                init() {
                    const initial = JSON.parse(
                        document.getElementById(
                            'pdpc-initial-aspects'
                        ).textContent
                    );

                    this.aspects = initial.map(
                        aspect =>
                        this.normaliseAspect(aspect)
                    );

                    this.originalData =
                        this.formData();
                },

                get hasChanges() {
                    return !this.isEditing ||
                        this.formData() !==
                        this.originalData;
                },

                formData() {
                    return JSON.stringify(
                        this.aspects.map(({
                            _key,
                            ...aspect
                        }) => ({
                            ...aspect,

                            tums: aspect.tums.map(({
                                _key,
                                ...tum
                            }) => ({
                                ...tum,

                                tt: tum.tt.map(({
                                    _key,
                                    ...tt
                                }) => ({
                                    ...tt,

                                    points: tt.points.map(({
                                        _key,
                                        ...point
                                    }) => point)
                                }))
                            }))
                        }))
                    );
                },

                key() {
                    return ++this.nextKey;
                },

                normaliseAspect(aspect = {}) {

                    const tums =
                        aspect.tums?.length
                            ? aspect.tums
                            : [{}];

                    return {
                        _key: this.key(),

                        aspect_code:
                            aspect.aspect_code ?? '',

                        aspect_name:
                            aspect.aspect_name ?? '',

                        tums:
                            tums.map(
                                tum =>
                                this.normaliseTum(tum)
                            )
                    };
                },

                normaliseTum(tum = {}) {

                    const tt =
                        tum.tt?.length
                            ? tum.tt
                            : [{}];

                    return {
                        _key: this.key(),

                        tums_code:
                            tum.tums_code ?? '',

                        tums_name:
                            tum.tums_name ?? '',

                        wajaran:
                            tum.wajaran ?? '',

                        rubrics: {
                            0: '',
                            1: '',
                            2: '',
                            3: '',
                            4: '',
                            ...(tum.rubrics ?? {})
                        },

                        tt:
                            tt.map(
                                item =>
                                this.normaliseTt(item)
                            )
                    };
                },

                normaliseTt(tt = {}) {

                    const points =
                        tt.points?.length
                            ? tt.points
                            : [{}];

                    return {
                        _key: this.key(),

                        points:
                            points.map(
                                point =>
                                this.normalisePoint(point)
                            )
                    };
                },

                normalisePoint(point = {}) {
                    return {
                        _key: this.key(),

                        point_text:
                            point.point_text ?? ''
                    };
                },

                addAspect() {
                    this.aspects.push(
                        this.normaliseAspect()
                    );
                },

                removeAspect(i) {
                    this.aspects.splice(i, 1);
                },

                addTum(i) {
                    this.aspects[i]
                        .tums
                        .push(
                            this.normaliseTum()
                        );
                },

                removeTum(i, j) {
                    this.aspects[i]
                        .tums
                        .splice(j, 1);
                },

                addTt(i, j) {
                    this.aspects[i]
                        .tums[j]
                        .tt
                        .push(
                            this.normaliseTt()
                        );
                },

                removeTt(i, j, k) {
                    this.aspects[i]
                        .tums[j]
                        .tt
                        .splice(k, 1);
                },

                addPoint(i, j, k) {
                    this.aspects[i]
                        .tums[j]
                        .tt[k]
                        .points
                        .push(
                            this.normalisePoint()
                        );
                },

                removePoint(i, j, k, l) {
                    this.aspects[i]
                        .tums[j]
                        .tt[k]
                        .points
                        .splice(l, 1);
                }

            }));

        });
    </script>

</x-app-layout>