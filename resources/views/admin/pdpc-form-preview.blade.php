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

                        @if($form->instruction)
                        <p class="text-violet-300 mt-2">
                            {{ $form->instruction }}
                        </p>
                        @endif

                    </div>

                    <div class="flex items-stretch gap-3">

                        {{-- VERSION --}}
                        <div class="min-w-[110px] bg-white/10 border border-white/10 rounded-2xl px-5 py-3">

                            <p class="text-xs uppercase tracking-wider text-violet-200 font-semibold">
                                Version
                            </p>

                            <p class="text-xl font-bold text-white mt-1">
                                {{ $form->version_no }}
                            </p>

                        </div>


                        {{-- PREVIEW NOTICE --}}
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
                                        d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />

                                    <circle cx="12" cy="12" r="3" />

                                </svg>

                            </div>

                            <div>

                                <p class="text-sm font-bold text-white">
                                    Preview Only
                                </p>

                                <p class="text-xs text-blue-200 mt-0.5">
                                    This shows how the form appears to the evaluator
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ASPECTS --}}

            @forelse($form->aspects as $aspect)

            <div class="mb-8">

                {{-- ASPECT HEADER --}}
                <div class="bg-blue-900 rounded-t-2xl px-6 py-4 text-white">

                    <p class="text-xs uppercase tracking-wider text-blue-200 font-semibold">
                        Aspect {{ $aspect->aspect_code }}
                    </p>

                    <h2 class="text-lg font-bold mt-1">
                        {{ $aspect->aspect_name }}
                    </h2>

                </div>


                {{-- TUMS --}}
                @foreach($aspect->tums as $tums)

                @php
                $allPoints = $tums->tt->flatMap(fn($tt) => $tt->points)->values();
                $totalPoints = $allPoints->count();
                @endphp


                <div class="bg-white border-x border-b border-slate-200 last:rounded-b-2xl overflow-hidden">

                    {{-- TUMS HEADER --}}
                    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">

                        <div class="flex items-center justify-between gap-4">

                            <div>

                                <p class="text-xs font-bold uppercase tracking-wider text-blue-700">
                                    TUMS {{ $tums->tums_code }}
                                </p>

                                <p class="font-bold text-black mt-1">
                                    {{ $tums->tums_name }}
                                </p>

                            </div>


                            <span class="inline-flex items-center justify-center px-4 py-2 rounded-full border border-blue-200 bg-blue-50 text-blue-700 text-sm font-semibold whitespace-nowrap">
                                Wajaran: {{ number_format($tums->wajaran, 2) }}
                            </span>

                        </div>

                    </div>


                    {{-- EVALUATION TABLE --}}
                    <div class="overflow-x-auto">

                        <table class="w-full table-fixed text-sm">

                            <thead>

                                <tr class="bg-slate-100 text-slate-600 uppercase text-xs">

                                    <th class="w-14 px-4 py-3 text-center">
                                        No
                                    </th>

                                    <th class="w-[42%] px-5 py-3 text-left">
                                        Tahap Tindakan (TT)
                                    </th>

                                    <th class="w-28 px-5 py-3 text-center">
                                        Skor
                                    </th>

                                    <th class="px-5 py-3 text-left">
                                        Rubrik Tahap Kualiti (RTK)
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-slate-200">

                                @forelse($allPoints as $point)

                                <tr class="align-top">

                                    <td class="px-4 py-4 text-center text-black">
                                        {{ $loop->iteration }}.
                                    </td>


                                    <td class="px-5 py-4 border-l border-slate-200 text-black leading-6">
                                        {{ $point->point_text }}
                                    </td>


                                    {{-- SCORE PREVIEW --}}
                                    <td class="px-5 py-4 border-l border-slate-200 text-center">

                                        <input
                                            type="text"
                                            disabled
                                            maxlength="1"
                                            placeholder="-"
                                            class="w-20 rounded-xl border-slate-300 bg-slate-50 text-center text-black">

                                    </td>


                                    {{-- ONE RTK SET PER TUMS --}}
                                    @if($loop->first)

                                    <td
                                        rowspan="{{ $totalPoints }}"
                                        class="px-5 py-4 border-l border-slate-200 align-top">

                                        <div class="space-y-3">

                                            @foreach([4, 3, 2, 1, 0] as $score)

                                            @php
                                            $rubric = $tums->rubrics->firstWhere('score', $score);
                                            @endphp

                                            <div class="flex items-start gap-3">

                                                <span class="inline-flex items-center justify-center min-w-[60px] px-2 py-2 rounded-lg bg-blue-500 text-white text-xs font-bold whitespace-nowrap">
                                                    RTK {{ $score }}
                                                </span>


                                                <div class="flex-1 px-3 py-2 rounded-xl border border-violet-200 bg-white text-xs text-black leading-5">
                                                    {{ $rubric?->description ?? '-' }}
                                                </div>

                                            </div>

                                            @endforeach

                                        </div>

                                    </td>

                                    @endif

                                </tr>

                                @empty

                                <tr>

                                    <td colspan="4" class="px-5 py-10 text-center text-slate-400">
                                        No Tahap Tindakan added
                                    </td>

                                </tr>

                                @endforelse

                            </tbody>


                            {{-- CALCULATION PREVIEW --}}
                            <tfoot class="bg-blue-50/70 text-sm">

                                <tr class="border-t border-blue-200">

                                    <td></td>

                                    <td class="px-5 py-2.5 text-left text-slate-900 border-l border-blue-200">
                                        Bilangan Tindakan / Jumlah Skor Kualiti
                                    </td>

                                    <td class="px-3 py-2.5 text-center text-slate-900 border-l border-blue-200">
                                        0
                                    </td>

                                    <td class="p-0 border-l border-blue-200">

                                        <div class="grid grid-cols-2 h-full">

                                            <div class="px-3 py-2.5 text-center text-slate-900 border-r border-blue-200">
                                                0
                                            </div>

                                            <div></div>

                                        </div>

                                    </td>

                                </tr>


                                <tr class="border-t border-blue-200">

                                    <td></td>

                                    <td class="px-5 py-2.5 text-left text-slate-900 border-l border-blue-200">
                                        Skor Tahap Tindakan / Min Skor Tahap Kualiti
                                    </td>

                                    <td class="px-3 py-2.5 text-center text-slate-900 border-l border-blue-200">
                                        0
                                    </td>

                                    <td class="p-0 border-l border-blue-200">

                                        <div class="grid grid-cols-2 h-full">

                                            <div class="px-3 py-2.5 text-center text-slate-900 border-r border-blue-200">
                                                0.00
                                            </div>

                                            <div></div>

                                        </div>

                                    </td>

                                </tr>


                                <tr class="border-t border-blue-200">

                                    <td></td>

                                    <td class="px-5 py-2.5 text-left text-slate-900 border-l border-blue-200">
                                        Peratus Skor Tahap Tindakan / Peratus Skor Tahap Kualiti
                                    </td>

                                    <td class="px-3 py-2.5 text-center text-slate-900 border-l border-blue-200">
                                        0.00
                                    </td>

                                    <td class="p-0 border-l border-blue-200">

                                        <div class="grid grid-cols-2 h-full">

                                            <div class="px-3 py-2.5 text-center text-slate-900 border-r border-blue-200">
                                                0.00
                                            </div>

                                            <div></div>

                                        </div>

                                    </td>

                                </tr>


                                <tr class="border-t border-blue-200">

                                    <td></td>

                                    <td colspan="2" class="px-5 py-2.5 text-left font-semibold text-slate-900 border-l border-blue-200">
                                        Peratus TUMS
                                    </td>

                                    <td class="p-0 border-l border-blue-200">

                                        <div class="grid grid-cols-2 h-full">

                                            <div class="px-3 py-2.5 text-center bg-blue-600 text-white font-bold">
                                                0.00
                                            </div>

                                            <div></div>

                                        </div>

                                    </td>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

                @endforeach

            </div>

            @empty

            <div class="bg-white rounded-3xl shadow-lg py-12 text-center text-gray-400 mb-8">
                No form content added
            </div>

            @endforelse


            {{-- OBSERVATION SUMMARY --}}

            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-24">

                <div class="px-6 py-5 border-b border-slate-200">

                    <h2 class="text-lg font-bold text-slate-900">
                        Observation Summary
                    </h2>

                    <p class="text-sm text-slate-400 mt-1">
                        Overall result based on TUMS percentage and weight
                    </p>

                </div>


                <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,2.1fr)_minmax(330px,1fr)] gap-6 p-5">

                    {{-- SUMMARY TABLE --}}
                    <div class="overflow-hidden rounded-2xl border border-slate-200 self-start">

                        <table class="w-full table-fixed text-sm">

                            <thead>

                                <tr class="bg-blue-900 text-white uppercase text-xs">

                                    <th class="w-[10%] px-3 py-3 text-center border-r border-blue-700">
                                        Aspect
                                    </th>

                                    <th class="w-[32%] px-3 py-3 text-left border-r border-blue-700">
                                        Aspect Name
                                    </th>

                                    <th class="w-[13%] px-3 py-3 text-center border-r border-blue-700">
                                        TUMS
                                    </th>

                                    <th class="w-[14%] px-3 py-3 text-center border-r border-blue-700">
                                        Wajaran
                                    </th>

                                    <th class="w-[14%] px-3 py-3 text-center border-r border-blue-700">
                                        %
                                    </th>

                                    <th class="w-[17%] px-3 py-3 text-center">
                                        Skor
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-slate-200">

                                @forelse($form->aspects as $aspect)

                                @foreach($aspect->tums as $tums)

                                <tr>

                                    <td class="px-3 py-2.5 text-center text-slate-700 border-r border-slate-200">
                                        {{ $aspect->aspect_code }}
                                    </td>

                                    <td class="px-3 py-2.5 text-slate-700 border-r border-slate-200">
                                        {{ $aspect->aspect_name }}
                                    </td>

                                    <td class="px-3 py-2.5 text-center text-slate-700 border-r border-slate-200">
                                        {{ $tums->tums_code }}
                                    </td>

                                    <td class="px-3 py-2.5 text-center text-slate-700 border-r border-slate-200">
                                        {{ number_format($tums->wajaran, 2) }}
                                    </td>

                                    <td class="px-3 py-2.5 text-center text-slate-400 border-r border-slate-200">
                                        0.00
                                    </td>

                                    <td class="px-3 py-2.5 text-center text-slate-400">
                                        0.00
                                    </td>

                                </tr>

                                @endforeach

                                @empty

                                <tr>
                                    <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                                        No TUMS available
                                    </td>
                                </tr>

                                @endforelse

                            </tbody>


                            <tfoot>

                                <tr class="bg-blue-50 border-t-2 border-blue-200">

                                    <td colspan="5" class="px-3 py-3 text-right font-bold text-slate-900 border-r border-blue-200">
                                        JUMLAH
                                    </td>

                                    <td class="px-3 py-3 text-center bg-blue-600 text-white font-bold">
                                        0.00
                                    </td>

                                </tr>

                            </tfoot>

                        </table>

                    </div>


                    {{-- ACHIEVEMENT --}}
                    <div class="overflow-hidden rounded-2xl border border-slate-200 self-start">

                        <table class="w-full table-fixed text-sm">

                            <thead>

                                <tr class="bg-blue-900 text-white uppercase text-xs">

                                    <th class="w-[45%] px-3 py-3 text-left border-r border-blue-700">
                                        Taraf
                                    </th>

                                    <th class="w-[38%] px-3 py-3 text-center border-r border-blue-700">
                                        Skor
                                    </th>

                                    <th class="w-[17%] px-3 py-3 text-center">
                                        ✓
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-slate-200">

                                <tr>
                                    <td class="px-3 py-3 text-slate-700 border-r border-slate-200">Cemerlang</td>
                                    <td class="px-3 py-3 text-center text-slate-700 border-r border-slate-200">90 - 100</td>
                                    <td class="px-3 py-3 text-center"></td>
                                </tr>

                                <tr>
                                    <td class="px-3 py-3 text-slate-700 border-r border-slate-200">Baik</td>
                                    <td class="px-3 py-3 text-center text-slate-700 border-r border-slate-200">80 - 89.99</td>
                                    <td class="px-3 py-3 text-center"></td>
                                </tr>

                                <tr>
                                    <td class="px-3 py-3 text-slate-700 border-r border-slate-200">Sederhana</td>
                                    <td class="px-3 py-3 text-center text-slate-700 border-r border-slate-200">50 - 79.99</td>
                                    <td class="px-3 py-3 text-center"></td>
                                </tr>

                                <tr>
                                    <td class="px-3 py-3 text-slate-700 border-r border-slate-200">Lemah</td>
                                    <td class="px-3 py-3 text-center text-slate-700 border-r border-slate-200">20 - 49.99</td>
                                    <td class="px-3 py-3 text-center"></td>
                                </tr>

                                <tr>
                                    <td class="px-3 py-3 text-slate-700 border-r border-slate-200">Sangat Lemah</td>
                                    <td class="px-3 py-3 text-center text-slate-700 border-r border-slate-200">0 - 19.99</td>
                                    <td class="px-3 py-3 text-center"></td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- STICKY ACTION --}}

    <div class="fixed bottom-4 left-0 right-0 z-40 px-6 pointer-events-none">

        <div class="max-w-7xl mx-auto">

            <div class="bg-white/95 backdrop-blur-md border border-gray-200 shadow-xl rounded-2xl px-6 py-4 pointer-events-auto">

                <div class="flex items-center justify-between">

                    <a
                        href="{{ route('admin.pdpc.form') }}"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition">
                        Back
                    </a>

                    <a
                        href="{{ route('admin.pdpc.form.edit', $form) }}"
                        class="px-5 py-2.5 bg-amber-400 hover:bg-amber-500 text-amber-950 font-semibold text-sm rounded-xl transition">
                        Edit
                    </a>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>