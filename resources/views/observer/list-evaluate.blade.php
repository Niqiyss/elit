<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('teacher')->user()->teacher_name }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-slate-100 py-8 px-6">

        <div class="max-w-7xl mx-auto">

            {{-- Header --}}
            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <h1 class="text-3xl font-extrabold text-white">
                        Evaluation List
                    </h1>

                    <p class="text-violet-300 mt-2">
                        Manage and monitor assigned teacher evaluations
                    </p>

                </div>

            </div>


            {{-- Tabs --}}
            <div class="flex flex-wrap gap-3 mb-8">

                <a
                    href="{{ route($listRoute, ['status' => 'active']) }}"
                    class="{{ $status === 'active' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 border border-slate-200' }} px-5 py-2.5 rounded-xl font-semibold shadow-sm transition">
                    Latest Evaluation
                </a>

                <a
                    href="{{ route($listRoute, ['status' => 'completed']) }}"
                    class="{{ $status === 'completed' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-700 border border-slate-200' }} px-5 py-2.5 rounded-xl font-semibold shadow-sm transition">
                    Completed Evaluation
                </a>

                @if(!$isObserver)

                <a
                    href="{{ route($listRoute, ['status' => 'repeat']) }}"
                    class="{{ $status === 'repeat' ? 'bg-red-500 text-white' : 'bg-white text-slate-700 border border-slate-200' }} px-5 py-2.5 rounded-xl font-semibold shadow-sm transition">
                    Repeat Evaluation
                </a>

                @endif

            </div>


            {{-- Main Card --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                <div class="px-8 py-6 border-b border-slate-100">

                    <h2 class="text-xl font-bold text-slate-900">

                        @if($status === 'completed')

                        Completed Evaluation List

                        @elseif(!$isObserver && $status === 'repeat')

                        Repeat Evaluation List

                        @else

                        Latest Evaluation List

                        @endif

                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Click Manage to access evaluation forms
                    </p>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr class="bg-slate-50 text-slate-900 text-xs uppercase tracking-wider">

                                <th class="px-8 py-4 text-left">
                                    No
                                </th>

                                <th class="px-8 py-4 text-left">
                                    Teacher
                                </th>

                                <th class="px-8 py-4 text-left">
                                    School
                                </th>

                                <th class="px-8 py-4 text-center">
                                    Completion
                                </th>

                                <th class="px-8 py-4 text-center">
                                    Status
                                </th>

                                <th class="px-8 py-4 text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @forelse($assignments as $assignment)

                            <tr class="hover:bg-slate-50 transition">

                                {{-- No --}}
                                <td class="px-8 py-6 text-slate-500">
                                    {{ $assignments->firstItem() + $loop->index }}
                                </td>


                                {{-- Teacher --}}
                                <td class="px-8 py-6">

                                    <p class="font-bold text-slate-900 uppercase">
                                        {{ $assignment->gn_name }}
                                    </p>

                                    {{-- External Attempt --}}
                                    @if(!$isObserver && isset($assignment->attempt_no))

                                    <p class="text-xs text-slate-600 mt-1">
                                        Attempt {{ $assignment->attempt_no }}
                                    </p>

                                    @endif

                                </td>


                                {{-- School --}}
                                <td class="px-8 py-6 text-slate-600">
                                    {{ $assignment->school_name ?? '-' }}
                                </td>


                                {{-- Completion --}}
                                <td class="px-8 py-6">

                                    @if($isObserver)

                                    {{-- OBSERVER --}}
                                    <div class="flex items-center justify-center gap-5">

                                        {{-- PRE --}}
                                        <div class="flex flex-col items-center gap-1.5">

                                            <span
                                                title="Pre-Observation Form"
                                                class="w-3 h-3 rounded-full {{
                                                            $assignment->pre_status === 'Completed'
                                                                ? 'bg-emerald-500'
                                                                : (
                                                                    $assignment->pre_status === 'Draft'
                                                                        ? 'bg-amber-400'
                                                                        : 'bg-slate-300'
                                                                )
                                                        }}">
                                            </span>

                                            <span class="text-[10px] font-semibold text-slate-600 uppercase">
                                                PRE
                                            </span>

                                        </div>


                                        {{-- PDPC --}}
                                        <div class="flex flex-col items-center gap-1.5">

                                            <span
                                                title="PDPC Observation Form"
                                                class="w-3 h-3 rounded-full {{
                                                            $assignment->pdpc_status === 'Completed'
                                                                ? 'bg-emerald-500'
                                                                : (
                                                                    $assignment->pdpc_status === 'Draft'
                                                                        ? 'bg-amber-400'
                                                                        : 'bg-slate-300'
                                                                )
                                                        }}">
                                            </span>

                                            <span class="text-[10px] font-semibold text-slate-600 uppercase">
                                                PDPC
                                            </span>

                                        </div>


                                        {{-- Feedback --}}
                                        <div class="flex flex-col items-center gap-1.5">

                                            <span
                                                title="Feedback Observation Form"
                                                class="w-3 h-3 rounded-full {{
                                                            $assignment->feedback_status === 'Completed'
                                                                ? 'bg-emerald-500'
                                                                : (
                                                                    $assignment->feedback_status === 'Draft'
                                                                        ? 'bg-amber-400'
                                                                        : 'bg-slate-300'
                                                                )
                                                        }}">
                                            </span>

                                            <span class="text-[10px] font-semibold text-slate-600 uppercase">
                                                FEEDBACK
                                            </span>

                                        </div>

                                    </div>

                                    @else

                                    {{-- EXTERNAL --}}
                                    <div class="flex flex-col items-center gap-2">

                                        @if(isset($assignment->attempt_no))

                                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-500 text-[10px] font-bold uppercase">
                                            Attempt {{ $assignment->attempt_no }}
                                        </span>

                                        @endif


                                        <div class="flex items-center justify-center gap-6">

                                            {{-- PDPC --}}
                                            <div class="flex flex-col items-center gap-1.5">

                                                <span
                                                    title="PDPC Observation Form"
                                                    class="w-3 h-3 rounded-full {{
                                                                $assignment->is_repeat
                                                                    ? 'bg-red-500'
                                                                    : (
                                                                        $assignment->pdpc_status === 'Completed'
                                                                            ? 'bg-emerald-500'
                                                                            : (
                                                                                $assignment->pdpc_status === 'Draft'
                                                                                    ? 'bg-amber-400'
                                                                                    : 'bg-slate-300'
                                                                            )
                                                                    )
                                                            }}">
                                                </span>

                                                <span class="text-[10px] font-semibold text-slate-600 uppercase">
                                                    PDPC
                                                </span>

                                            </div>


                                            {{-- Feedback --}}
                                            <div class="flex flex-col items-center gap-1.5">

                                                <span
                                                    title="Feedback Observation Form"
                                                    class="w-3 h-3 rounded-full {{
                                                                $assignment->feedback_status === 'Completed'
                                                                    ? 'bg-emerald-500'
                                                                    : (
                                                                        $assignment->feedback_status === 'Draft'
                                                                            ? 'bg-amber-400'
                                                                            : 'bg-slate-300'
                                                                    )
                                                            }}">
                                                </span>

                                                <span class="text-[10px] font-semibold text-slate-600 uppercase">
                                                    FEEDBACK
                                                </span>

                                            </div>

                                        </div>

                                    </div>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td class="px-8 py-6 text-center">

                                    @if(
                                    !$isObserver &&
                                    $assignment->is_repeat
                                    )

                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-red-100 text-red-700 text-xs font-bold">

                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>

                                        Repeat Required

                                    </span>

                                    @elseif($assignment->is_fully_completed)

                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">

                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>

                                        {{ $assignment->completed_count }}/{{ $assignment->total_forms }}
                                        Completed

                                    </span>

                                    @elseif($assignment->has_draft)

                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">

                                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>

                                        {{ $assignment->completed_count }}/{{ $assignment->total_forms }}
                                        Completed

                                    </span>

                                    @else

                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">

                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>

                                        {{ $assignment->completed_count }}/{{ $assignment->total_forms }}
                                        Completed

                                    </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="px-8 py-6 text-center">

                                    <a
                                        href="{{ route(
                                                $isObserver
                                                    ? 'observer.manage'
                                                    : 'external.manage',
                                                $assignment->gn_id
                                            ) }}"
                                        class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-xl font-semibold transition shadow-md">

                                        Manage

                                    </a>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td colspan="6" class="py-16 text-center">

                                    <div class="flex flex-col items-center">

                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">

                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>

                                        </div>

                                        <h3 class="font-bold text-slate-700">
                                            No Evaluation Found
                                        </h3>

                                        <p class="text-slate-400 text-sm mt-1">

                                            @if(!$isObserver && $status === 'repeat')

                                            There are currently no repeat evaluations.

                                            @elseif($status === 'completed')

                                            There are currently no completed evaluations.

                                            @else

                                            There are currently no active evaluation assignments.

                                            @endif

                                        </p>

                                    </div>

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>


                    {{-- Pagination --}}
                    @if($assignments->hasPages())

                    <div class="px-6 py-4 border-t border-slate-100 flex justify-end">

                        {{ $assignments->appends(request()->query())->links() }}

                    </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>