<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ Auth::guard('admin')->user()->staffname }}</h2>
    </x-slot>

    <div class="min-h-screen bg-slate-100 py-8 px-6">

        <div class="max-w-7xl mx-auto">

            {{-- Header --}}
            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    <div>

                        <h1 class="text-3xl font-extrabold text-white">{{ $form->form_name }}</h1>

                        @if($form->instruction)
                        <p class="text-violet-300 mt-2">{{ $form->instruction }}</p>
                        @endif

                    </div>

                    <div class="flex items-stretch gap-3">

                        <div class="min-w-[110px] bg-white/10 border border-white/10 rounded-2xl px-5 py-3">
                            <p class="text-xs uppercase tracking-wider text-violet-200 font-semibold">Version</p>
                            <p class="text-xl font-bold text-white mt-1">{{ $form->version }}</p>
                        </div>

                        <div class="bg-blue-400/10 border border-blue-300/20 rounded-2xl px-5 py-3 flex items-center gap-3">

                            <div class="w-9 h-9 rounded-xl bg-blue-300/20 flex items-center justify-center">

                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>

                            </div>

                            <div>
                                <p class="text-sm font-bold text-white">Preview Only</p>
                                <p class="text-xs text-blue-200 mt-0.5">This shows how the form appears to the observer</p>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Evaluation --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr class="bg-blue-900 text-white text-xs uppercase tracking-wider">
                                <th class="px-5 py-4 text-center w-16">No.</th>
                                <th class="px-5 py-4 text-left">Section / Criteria</th>
                                <th class="px-5 py-4 text-center w-72">Score</th>
                                <th class="px-5 py-4 text-left w-80">Comment</th>
                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-200">

                            @foreach($form->sections as $section)

                            @if($section->criteria->isNotEmpty())

                            <tr class="bg-blue-50">
                                <td class="px-5 py-4 text-center text-sm font-bold text-slate-700">{{ $loop->iteration }}</td>
                                <td colspan="3" class="px-5 py-4 text-sm font-bold text-slate-900">{{ $section->section_name }}</td>
                            </tr>

                            @foreach($section->criteria as $criteria)

                            @php $letter = chr(96 + $loop->iteration); @endphp

                            <tr>

                                <td class="px-5 py-5"></td>

                                <td class="px-5 py-5 text-sm text-slate-700 align-middle">
                                    <span class="font-semibold">{{ $letter }}.</span>
                                    {{ $criteria->criteria_label }}
                                </td>

                                <td class="px-5 py-5">

                                    <div class="flex items-center justify-center gap-4 flex-wrap">

                                        @for($score = $form->min_score; $score <= $form->max_score; $score++)

                                            <label class="flex items-center gap-1.5">
                                                <input type="radio" disabled class="text-blue-600 border-slate-300">
                                                <span class="text-sm font-semibold text-slate-600">{{ $score }}</span>
                                            </label>

                                            @endfor

                                    </div>

                                </td>

                                @if($loop->first)

                                <td rowspan="{{ $section->criteria->count() }}" class="px-5 py-5 align-middle border-l border-slate-100">
                                    <textarea rows="5" disabled placeholder="Enter comment..." class="w-full rounded-xl border-slate-300 bg-slate-50"></textarea>
                                </td>

                                @endif

                            </tr>

                            @endforeach

                            @endif

                            @endforeach

                        </tbody>

                        <tfoot>

                            <tr class="bg-blue-900 text-white">

                                <td colspan="2" class="px-5 py-3 text-right text-sm font-bold uppercase">Total</td>

                                <td class="px-5 py-3 text-center text-sm">
                                    <span class="font-bold">0</span>
                                    <span class="text-blue-200">/</span>
                                    <span class="font-semibold text-blue-100">{{ $maximumScore }}</span>
                                </td>

                                <td class="px-5 py-3 text-center text-sm">
                                    <span class="font-bold uppercase">Percentage :</span>
                                    <span class="ml-2 font-bold">0%</span>
                                </td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

            {{-- Other Comment --}}
            <div class="bg-white rounded-3xl shadow-lg p-6 mb-8">

                <h2 class="text-lg font-bold text-slate-900 mb-2">Other Comment</h2>

                <textarea rows="4" disabled placeholder="Enter comment..." class="w-full rounded-xl border-slate-300 bg-slate-50"></textarea>

            </div>

            {{-- Achievement Level --}}
            <div class="bg-white rounded-3xl shadow-lg px-6 py-5 mb-24">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    <h2 class="text-lg font-bold text-slate-900">Achievement Level</h2>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">

                        <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-3 text-center">
                            <p class="font-semibold text-slate-800">Weak</p>
                            <p class="text-sm text-slate-500 mt-1">0 - 39%</p>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-3 text-center">
                            <p class="font-semibold text-slate-800">Satisfactory</p>
                            <p class="text-sm text-slate-500 mt-1">40 - 59%</p>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-3 text-center">
                            <p class="font-semibold text-slate-800">Good</p>
                            <p class="text-sm text-slate-500 mt-1">60 - 79%</p>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-3 text-center">
                            <p class="font-semibold text-slate-800">Very Good</p>
                            <p class="text-sm text-slate-500 mt-1">80 - 89%</p>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-3 text-center">
                            <p class="font-semibold text-slate-800">Excellent</p>
                            <p class="text-sm text-slate-500 mt-1">90 - 100%</p>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Sticky Action --}}
    <div class="fixed bottom-4 left-0 right-0 z-40 px-6 pointer-events-none">

        <div class="max-w-7xl mx-auto">

            <div class="bg-white/95 backdrop-blur-md border border-gray-200 shadow-xl rounded-2xl px-6 py-4 pointer-events-auto">

                <div class="flex items-center justify-between">

                    <a href="{{ route('admin.pre.form') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl">Back</a>

                    <a href="{{ route('admin.pre.form.edit', $form) }}" class="px-5 py-2.5 bg-amber-400 hover:bg-amber-500 text-amber-950 font-semibold text-sm rounded-xl">Edit</a>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>