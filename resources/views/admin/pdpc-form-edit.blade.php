<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('admin')->user()->staffname }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">

            {{-- HEADER --}}
            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl px-8 py-6 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    <div>

                        <h1 class="text-3xl font-extrabold text-white">
                            {{ $form->form_name }}
                        </h1>

                        <p class="text-violet-300 mt-2">
                            Manage Aspect, TUMS, Tahap Tindakan and Rubric
                        </p>

                    </div>

                    <div class="flex items-stretch gap-3">

                        {{-- Version --}}
                        <div class="min-w-[110px] bg-white/10 border border-white/10 rounded-2xl px-5 py-3">

                            <p class="text-xs uppercase tracking-wider text-violet-200 font-semibold">
                                Version
                            </p>

                            <p class="text-xl font-bold text-white mt-1">
                                {{ $form->version_no }}
                            </p>

                        </div>


                        {{-- Usage Notice --}}
                        @if($formUsed)

                        <div class="bg-amber-400/10 border border-amber-300/20 rounded-2xl px-5 py-3 flex items-center gap-3">

                            <div class="w-9 h-9 rounded-xl bg-amber-300/20 flex items-center justify-center">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-amber-200"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />

                                </svg>

                            </div>

                            <div>

                                <p class="text-sm font-bold text-white">
                                    Form in use
                                </p>

                                <p class="text-xs text-amber-200 mt-0.5">
                                    Only wording can be edited
                                </p>

                            </div>

                        </div>

                        @else

                        <div class="bg-blue-400/10 border border-blue-300/20 rounded-2xl px-5 py-3 flex items-center gap-3">

                            <div class="w-9 h-9 rounded-xl bg-blue-300/20 flex items-center justify-center">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-blue-200"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 13l4 4L19 7" />

                                </svg>

                            </div>

                            <div>

                                <p class="text-sm font-bold text-white">
                                    Current / Not Yet Used
                                </p>

                                <p class="text-xs text-blue-200 mt-0.5">
                                    Form content can be changed
                                </p>

                            </div>

                        </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- SUCCESS --}}
            @if(session('success'))

            <div class="mb-6 px-5 py-4 bg-green-100 border border-green-200 text-green-700 rounded-xl">
                {{ session('success') }}
            </div>

            @endif


            {{-- ERROR --}}
            @if(session('error'))

            <div class="mb-6 px-5 py-4 bg-red-100 border border-red-200 text-red-700 rounded-xl">
                {{ session('error') }}
            </div>

            @endif


            {{-- VALIDATION --}}
            @if($errors->any())

            <div class="mb-6 px-5 py-4 bg-red-100 border border-red-200 text-red-700 rounded-xl">

                <p class="font-semibold mb-2">
                    Form cannot be saved. Please correct the following:
                </p>

                <ul class="list-disc list-inside text-sm space-y-1">

                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

            @endif


            {{-- FORM BUILDER --}}
            <div x-data="pdpcFormBuilder()">

                <form
                    id="pdpcFormBuilder"
                    method="POST"
                    action="{{ route('admin.pdpc.form.update', $form) }}"
                    class="space-y-8 mb-24">

                    @csrf
                    @method('PUT')


                    {{-- FORM INFORMATION --}}
                    <section class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">

                        <div class="px-6 py-5 border-b border-gray-100">

                            <h2 class="text-lg font-bold text-gray-900">
                                Form Information
                            </h2>

                            <p class="text-sm text-gray-400 mt-1">
                                Update form name and instruction
                            </p>

                        </div>


                        <div class="p-6">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                {{-- Form Name --}}
                                <div>

                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Form Name
                                    </label>

                                    <input
                                        type="text"
                                        name="form_name"
                                        value="{{ old('form_name', $form->form_name) }}"
                                        required
                                        class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                                </div>


                                {{-- Instruction --}}
                                <div>

                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Instruction
                                    </label>

                                    <input
                                        type="text"
                                        name="instruction"
                                        value="{{ old('instruction', $form->instruction) }}"
                                        class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                                </div>

                            </div>

                        </div>

                    </section>


                    {{-- CONTENT GUIDE --}}
                    <section class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">

                        <div class="px-6 py-5 border-b border-gray-100">

                            <div class="flex items-center justify-between gap-4">

                                <h2 class="text-lg font-bold text-gray-900">
                                    Content Guide
                                </h2>


                                @if($formUsed)

                                <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">
                                    Structure Locked
                                </span>

                                @endif

                            </div>

                        </div>


                        <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-4">

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 px-4 py-4">

                                <p class="font-bold text-gray-900">
                                    1. Aspect
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    Main aspect
                                </p>

                            </div>


                            <div class="rounded-2xl bg-slate-50 border border-slate-200 px-4 py-4">

                                <p class="font-bold text-gray-900">
                                    2. TUMS
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    TUMS description and wajaran
                                </p>

                            </div>


                            <div class="rounded-2xl bg-slate-50 border border-slate-200 px-4 py-4">

                                <p class="font-bold text-gray-900">
                                    3. Tahap Tindakan (TT)
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    One or more TT points
                                </p>

                            </div>


                            <div class="rounded-2xl bg-slate-50 border border-slate-200 px-4 py-4">

                                <p class="font-bold text-gray-900">
                                    4. RTK Rubric
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    Rubric points RTK 0 - RTK 4
                                </p>

                            </div>

                        </div>

                    </section>


                    {{-- ASPECTS --}}
                    <template x-for="(aspect, aspectIndex) in aspects" :key="aspect._key">

                        <section class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">


                            {{-- Hidden Aspect ID --}}
                            <input
                                type="hidden"
                                :name="`aspects[${aspectIndex}][aspectID]`"
                                :value="aspect.aspectID">


                            {{-- Aspect Header --}}
                            <div class="bg-blue-900 px-6 py-5">

                                <div class="flex flex-col md:flex-row md:items-end gap-4">

                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 flex-1">


                                        {{-- Aspect Code --}}
                                        <div>

                                            <label class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-2">
                                                Aspect Code
                                            </label>


                                            @if($formUsed)

                                            <input
                                                type="text"
                                                x-model="aspect.aspect_code"
                                                disabled
                                                class="w-full rounded-xl border-blue-700 bg-blue-800 text-blue-100 cursor-not-allowed">

                                            @else

                                            <input
                                                type="text"
                                                x-model="aspect.aspect_code"
                                                :name="`aspects[${aspectIndex}][aspect_code]`"
                                                placeholder="Example: 4.1"
                                                class="w-full rounded-xl border-blue-400 bg-white text-gray-900">

                                            @endif

                                        </div>


                                        {{-- Aspect Name --}}
                                        <div class="md:col-span-3">

                                            <label class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-2">
                                                Aspect
                                            </label>

                                            <input
                                                type="text"
                                                x-model="aspect.aspect_name"
                                                :name="`aspects[${aspectIndex}][aspect_name]`"
                                                required
                                                placeholder="Enter aspect"
                                                class="w-full rounded-xl border-blue-400 bg-white text-gray-900">

                                        </div>

                                    </div>


                                    {{-- Delete Aspect --}}
                                    @if(!$formUsed)

                                    <button
                                        type="button"
                                        x-show="aspects.length > 1"
                                        @click="removeAspect(aspectIndex)"
                                        title="Delete Aspect"
                                        class="w-10 h-10 flex-shrink-0 inline-flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-xl transition">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5" />

                                        </svg>

                                    </button>

                                    @endif

                                </div>

                            </div>


                            {{-- TUMS --}}
                            <div class="p-6 bg-blue-50/40 space-y-6">

                                <template x-for="(tum, tumIndex) in aspect.tums" :key="tum._key">

                                    <div class="rounded-2xl bg-white border border-blue-200 overflow-hidden">


                                        {{-- Hidden TUMS ID --}}
                                        <input
                                            type="hidden"
                                            :name="`aspects[${aspectIndex}][tums][${tumIndex}][tumsID]`"
                                            :value="tum.tumsID">


                                        {{-- TUMS HEADER --}}
                                        <div class="p-5 bg-blue-50 border-b border-blue-100">

                                            <div class="grid grid-cols-1 lg:grid-cols-[150px_1fr_140px_42px] gap-4 items-end">


                                                {{-- TUMS Code --}}
                                                <div>

                                                    <label class="block text-xs font-bold uppercase tracking-wider text-blue-700 mb-2">
                                                        TUMS Code
                                                    </label>


                                                    @if($formUsed)

                                                    <input
                                                        type="text"
                                                        x-model="tum.tums_code"
                                                        disabled
                                                        class="w-full rounded-xl border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed">

                                                    @else

                                                    <input
                                                        type="text"
                                                        x-model="tum.tums_code"
                                                        :name="`aspects[${aspectIndex}][tums][${tumIndex}][tums_code]`"
                                                        placeholder="4.1.1"
                                                        class="w-full rounded-xl border-gray-300">

                                                    @endif

                                                </div>


                                                {{-- TUMS Name --}}
                                                <div>

                                                    <label class="block text-xs font-bold uppercase tracking-wider text-blue-700 mb-2">
                                                        TUMS
                                                    </label>

                                                    <input
                                                        type="text"
                                                        x-model="tum.tums_name"
                                                        :name="`aspects[${aspectIndex}][tums][${tumIndex}][tums_name]`"
                                                        required
                                                        placeholder="Enter TUMS"
                                                        class="w-full rounded-xl border-gray-300">

                                                </div>


                                                {{-- Wajaran --}}
                                                <div>

                                                    <label class="block text-xs font-bold uppercase tracking-wider text-blue-700 mb-2">
                                                        Wajaran
                                                    </label>


                                                    @if($formUsed)

                                                    <input
                                                        type="number"
                                                        x-model="tum.wajaran"
                                                        disabled
                                                        class="w-full rounded-xl border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed">

                                                    @else

                                                    <input
                                                        type="number"
                                                        x-model="tum.wajaran"
                                                        :name="`aspects[${aspectIndex}][tums][${tumIndex}][wajaran]`"
                                                        required
                                                        min="0"
                                                        max="100"
                                                        step="0.01"
                                                        placeholder="10"
                                                        class="w-full rounded-xl border-gray-300">

                                                    @endif

                                                </div>


                                                {{-- Delete TUMS --}}
                                                <div>

                                                    @if(!$formUsed)

                                                    <button
                                                        type="button"
                                                        x-show="aspect.tums.length > 1"
                                                        @click="removeTum(aspectIndex, tumIndex)"
                                                        title="Delete TUMS"
                                                        class="w-10 h-10 inline-flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-xl">

                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="w-5 h-5"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            stroke-width="2">

                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5" />

                                                        </svg>

                                                    </button>

                                                    @endif

                                                </div>

                                            </div>

                                        </div>


                                        {{-- TT CONTENT --}}
                                        <div class="p-5 space-y-5">

                                            <template x-for="(tt, ttIndex) in tum.tt" :key="tt._key">

                                                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">


                                                    {{-- Hidden TT ID --}}
                                                    <input
                                                        type="hidden"
                                                        :name="`aspects[${aspectIndex}][tums][${tumIndex}][tt][${ttIndex}][ttID]`"
                                                        :value="tt.ttID">


                                                    <div class="flex items-center justify-between gap-4 mb-4">

                                                        <div>

                                                            <p class="text-sm font-bold text-gray-800">
                                                                Tahap Tindakan (TT)
                                                            </p>

                                                            <p class="text-xs text-gray-400 mt-1">
                                                                Add one or more action points
                                                            </p>

                                                        </div>


                                                        {{-- Delete TT --}}
                                                        @if(!$formUsed)

                                                        <button
                                                            type="button"
                                                            x-show="tum.tt.length > 1"
                                                            @click="removeTt(aspectIndex, tumIndex, ttIndex)"
                                                            title="Delete Tahap Tindakan"
                                                            class="w-9 h-9 inline-flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-xl">

                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="w-4 h-4"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor"
                                                                stroke-width="2">

                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5" />

                                                            </svg>

                                                        </button>

                                                        @endif

                                                    </div>


                                                    {{-- POINTS --}}
                                                    <div class="space-y-3">

                                                        <template x-for="(point, pointIndex) in tt.points" :key="point._key">

                                                            <div class="flex items-start gap-3">


                                                                {{-- Hidden Point ID --}}
                                                                <input
                                                                    type="hidden"
                                                                    :name="`aspects[${aspectIndex}][tums][${tumIndex}][tt][${ttIndex}][points][${pointIndex}][pointID]`"
                                                                    :value="point.pointID">


                                                                {{-- Point Number --}}
                                                                <div
                                                                    class="w-8 h-10 flex items-center justify-center text-sm font-semibold text-gray-400"
                                                                    x-text="pointIndex + 1">
                                                                </div>


                                                                {{-- Point Text --}}
                                                                <textarea
                                                                    x-model="point.point_text"
                                                                    :name="`aspects[${aspectIndex}][tums][${tumIndex}][tt][${ttIndex}][points][${pointIndex}][point_text]`"
                                                                    required
                                                                    rows="2"
                                                                    placeholder="Enter Tahap Tindakan point"
                                                                    class="flex-1 rounded-xl border-gray-300"></textarea>


                                                                {{-- Delete Point --}}
                                                                @if(!$formUsed)

                                                                <button
                                                                    type="button"
                                                                    x-show="tt.points.length > 1"
                                                                    @click="removePoint(aspectIndex, tumIndex, ttIndex, pointIndex)"
                                                                    title="Delete Point"
                                                                    class="w-10 h-10 inline-flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-xl">

                                                                    <svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        class="w-4 h-4"
                                                                        fill="none"
                                                                        viewBox="0 0 24 24"
                                                                        stroke="currentColor"
                                                                        stroke-width="2">

                                                                        <path
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5" />

                                                                    </svg>

                                                                </button>

                                                                @endif

                                                            </div>

                                                        </template>

                                                    </div>


                                                    {{-- Add Point --}}
                                                    @if(!$formUsed)

                                                    <button
                                                        type="button"
                                                        @click="addPoint(aspectIndex, tumIndex, ttIndex)"
                                                        class="mt-4 px-4 py-2 rounded-xl bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-semibold">

                                                        + Add TT Point

                                                    </button>

                                                    @endif

                                                </div>

                                            </template>


                                            {{-- Add TT --}}
                                            @if(!$formUsed)

                                            <button
                                                type="button"
                                                @click="addTt(aspectIndex, tumIndex)"
                                                class="px-4 py-2 rounded-xl bg-violet-100 hover:bg-violet-200 text-violet-700 text-sm font-semibold">

                                                + Add Tahap Tindakan

                                            </button>

                                            @endif


                                            {{-- RTK RUBRIC --}}
                                            <div class="rounded-2xl border border-violet-200 overflow-hidden">

                                                <div class="px-5 py-4 bg-violet-50 border-b border-violet-200">

                                                    <div class="flex items-center justify-between gap-4">

                                                        <div>

                                                            <p class="font-bold text-violet-900">
                                                                Rubrik Tahap Kualiti (RTK)
                                                            </p>

                                                            <p class="text-xs text-violet-600 mt-1">

                                                                @if($formUsed)
                                                                Only rubric wording can be edited
                                                                @else
                                                                Define the rubric for each score
                                                                @endif

                                                            </p>

                                                        </div>

                                                        <span class="text-xs font-semibold text-violet-700">
                                                            RTK 0 - RTK 4
                                                        </span>

                                                    </div>

                                                </div>


                                                <div class="p-5 space-y-3 bg-white">

                                                    <template x-for="score in [4, 3, 2, 1, 0]" :key="score">

                                                        <div class="grid grid-cols-[70px_1fr] gap-3 items-start">

                                                            {{-- Fixed RTK Score --}}
                                                            <div
                                                                class="h-10 rounded-xl bg-violet-100 text-violet-700 flex items-center justify-center text-xs font-bold"
                                                                x-text="`RTK ${score}`">
                                                            </div>


                                                            {{-- Editable RTK Description --}}
                                                            <textarea
                                                                x-model="tum.rubrics[score]"
                                                                :name="`aspects[${aspectIndex}][tums][${tumIndex}][rubrics][${score}]`"
                                                                rows="3"
                                                                required
                                                                :placeholder="`Enter rubric for RTK ${score}`"
                                                                class="w-full rounded-xl border-violet-200 focus:border-violet-500 focus:ring-violet-500"></textarea>

                                                        </div>

                                                    </template>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </template>


                                {{-- ADD TUMS --}}
                                @if(!$formUsed)

                                <button
                                    type="button"
                                    @click="addTum(aspectIndex)"
                                    class="px-4 py-2.5 rounded-xl bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm font-semibold">

                                    + Add TUMS

                                </button>

                                @endif

                            </div>

                        </section>

                    </template>


                    {{-- ADD ASPECT --}}
                    @if(!$formUsed)

                    <section class="bg-white rounded-3xl shadow-sm border border-gray-200 p-6">

                        <button
                            type="button"
                            @click="addAspect()"
                            class="px-5 py-2.5 bg-violet-100 hover:bg-violet-200 text-violet-700 text-sm font-semibold rounded-xl">

                            + Add Aspect

                        </button>

                    </section>

                    @endif

                </form>

            </div>

        </div>

    </div>


    {{-- STICKY ACTION --}}
    <div class="fixed bottom-4 left-0 right-0 z-40 px-6 pointer-events-none">

        <div class="max-w-7xl mx-auto">

            <div class="bg-white/95 backdrop-blur-md border border-gray-200 shadow-xl rounded-2xl px-6 py-4 pointer-events-auto">

                <div class="flex items-center justify-between gap-3">

                    <a
                        href="{{ route('admin.pdpc.form') }}"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition">

                        Back

                    </a>


                    <div class="flex items-center gap-3">

                        <a
                            href="{{ route('admin.pdpc.form.preview', $form) }}"
                            class="px-5 py-2.5 bg-sky-100 hover:bg-sky-200 text-sky-700 font-semibold text-sm rounded-xl transition">

                            Preview

                        </a>


                        <button
                            type="submit"
                            form="pdpcFormBuilder"
                            class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-semibold text-sm rounded-xl transition">

                            Save

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Initial Builder Data --}}
    <script type="application/json" id="pdpc-initial-aspects">
        @json($initialAspects)
    </script>


    {{-- Alpine --}}
    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.data('pdpcFormBuilder', () => ({

                aspects: [],
                nextKey: 0,

                init() {

                    const initial = JSON.parse(
                        document.getElementById('pdpc-initial-aspects').textContent
                    );

                    this.aspects = initial.map(
                        aspect => this.normaliseAspect(aspect)
                    );
                },

                key() {
                    return ++this.nextKey;
                },

                normaliseAspect(aspect = {}) {

                    const tums = aspect.tums?.length
                        ? aspect.tums
                        : [{}];

                    return {
                        _key: this.key(),

                        aspectID: aspect.aspectID ?? '',

                        aspect_code:
                            aspect.aspect_code ?? '',

                        aspect_name:
                            aspect.aspect_name ?? '',

                        tums: tums.map(
                            tum => this.normaliseTum(tum)
                        )
                    };
                },

                normaliseTum(tum = {}) {

                    const tt = tum.tt?.length
                        ? tum.tt
                        : [{}];

                    return {
                        _key: this.key(),

                        tumsID:
                            tum.tumsID ?? '',

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

                        tt: tt.map(
                            item => this.normaliseTt(item)
                        )
                    };
                },

                normaliseTt(tt = {}) {

                    const points = tt.points?.length
                        ? tt.points
                        : [{}];

                    return {
                        _key: this.key(),

                        ttID:
                            tt.ttID ?? '',

                        points: points.map(
                            point => this.normalisePoint(point)
                        )
                    };
                },

                normalisePoint(point = {}) {

                    return {
                        _key: this.key(),

                        pointID:
                            point.pointID ?? '',

                        point_text:
                            point.point_text ?? ''
                    };
                },

                addAspect() {
                    this.aspects.push(
                        this.normaliseAspect()
                    );
                },

                removeAspect(index) {
                    this.aspects.splice(index, 1);
                },

                addTum(aspectIndex) {
                    this.aspects[aspectIndex]
                        .tums
                        .push(
                            this.normaliseTum()
                        );
                },

                removeTum(aspectIndex, tumIndex) {
                    this.aspects[aspectIndex]
                        .tums
                        .splice(tumIndex, 1);
                },

                addTt(aspectIndex, tumIndex) {
                    this.aspects[aspectIndex]
                        .tums[tumIndex]
                        .tt
                        .push(
                            this.normaliseTt()
                        );
                },

                removeTt(aspectIndex, tumIndex, ttIndex) {
                    this.aspects[aspectIndex]
                        .tums[tumIndex]
                        .tt
                        .splice(ttIndex, 1);
                },

                addPoint(aspectIndex, tumIndex, ttIndex) {
                    this.aspects[aspectIndex]
                        .tums[tumIndex]
                        .tt[ttIndex]
                        .points
                        .push(
                            this.normalisePoint()
                        );
                },

                removePoint(aspectIndex, tumIndex, ttIndex, pointIndex) {
                    this.aspects[aspectIndex]
                        .tums[tumIndex]
                        .tt[ttIndex]
                        .points
                        .splice(pointIndex, 1);
                }

            }));

        });
    </script>

</x-app-layout>