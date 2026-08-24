<x-app-layout>

    <div class="min-h-screen bg-slate-100 py-8 px-6">

        <div class="max-w-7xl mx-auto">


            {{-- Header --}}
            <div class="relative bg-gradient-to-br
                        from-slate-900
                        via-violet-950
                        to-purple-900
                        rounded-3xl
                        p-8
                        shadow-xl
                        overflow-hidden
                        mb-8">

                <div class="absolute right-0 top-0
                            translate-x-10
                            -translate-y-10
                            w-72 h-72
                            bg-purple-500/10
                            rounded-full
                            blur-3xl">
                </div>


                <div class="relative z-10">

                    <p class="text-xs
                              uppercase
                              tracking-[0.2em]
                              font-bold
                              text-violet-300">

                        Observer Dashboard

                    </p>

                    <h1 class="text-3xl
                               font-extrabold
                               text-white
                               mt-2">

                        Welcome,
                        {{ Auth::guard('teacher')->user()->teacher_name }}

                    </h1>

                </div>

            </div>


            {{-- Statistics --}}
            <div class="grid grid-cols-1
                        sm:grid-cols-2
                        lg:grid-cols-4
                        gap-6
                        mb-8">


                <div class="bg-white
                            rounded-2xl
                            shadow-md
                            p-6">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm
                                      font-semibold
                                      text-slate-500">

                                Assigned Teachers

                            </p>

                            <p class="text-3xl
                                      font-extrabold
                                      text-slate-900
                                      mt-2">

                                {{ $totalAssigned }}

                            </p>

                        </div>


                        <div class="w-12 h-12
                                    bg-blue-100
                                    rounded-xl
                                    flex
                                    items-center
                                    justify-center">

                            <svg
                                class="w-6 h-6 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m3-4a4 4 0 100-8 4 4 0 000 8z">
                                </path>

                            </svg>

                        </div>

                    </div>

                </div>


                <div class="bg-white
                            rounded-2xl
                            shadow-md
                            p-6">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm
                                      font-semibold
                                      text-slate-500">

                                Ongoing Evaluations

                            </p>

                            <p class="text-3xl
                                      font-extrabold
                                      text-slate-900
                                      mt-2">

                                {{ $ongoingCount }}

                            </p>

                        </div>


                        <div class="w-12 h-12
                                    bg-blue-100
                                    rounded-xl
                                    flex
                                    items-center
                                    justify-center">

                            <svg
                                class="w-6 h-6 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>

                            </svg>

                        </div>

                    </div>

                </div>


                <div class="bg-white
                            rounded-2xl
                            shadow-md
                            p-6">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm
                                      font-semibold
                                      text-slate-500">

                                Draft Evaluations

                            </p>

                            <p class="text-3xl
                                      font-extrabold
                                      text-slate-900
                                      mt-2">

                                {{ $draftCount }}

                            </p>

                        </div>


                        <div class="w-12 h-12
                                    bg-amber-100
                                    rounded-xl
                                    flex
                                    items-center
                                    justify-center">

                            <svg
                                class="w-6 h-6 text-amber-600"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>

                            </svg>

                        </div>

                    </div>

                </div>


                <div class="bg-white
                            rounded-2xl
                            shadow-md
                            p-6">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm
                                      font-semibold
                                      text-slate-500">

                                Completed Evaluations

                            </p>

                            <p class="text-3xl
                                      font-extrabold
                                      text-slate-900
                                      mt-2">

                                {{ $completedCount }}

                            </p>

                        </div>


                        <div class="w-12 h-12
                                    bg-emerald-100
                                    rounded-xl
                                    flex
                                    items-center
                                    justify-center">

                            <svg
                                class="w-6 h-6 text-emerald-600"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 13l4 4L19 7">
                                </path>

                            </svg>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Recent Evaluation --}}
            <div class="bg-white
                        rounded-3xl
                        shadow-lg
                        overflow-hidden">

                <div class="px-8 py-6
                            border-b
                            border-slate-100
                            flex
                            items-center
                            justify-between">

                    <div>

                        <h2 class="text-xl
                                   font-bold
                                   text-slate-900">

                            Recent Evaluations

                        </h2>

                        <p class="text-sm
                                  text-slate-500
                                  mt-1">

                            Latest assigned teacher evaluation progress

                        </p>

                    </div>


                    <a
                        href="{{ route(
                            'observer.list.evaluate'
                        ) }}"
                        class="px-4 py-2
                               bg-blue-600
                               hover:bg-blue-700
                               text-white
                               text-sm
                               font-semibold
                               rounded-xl
                               transition">

                        View All

                    </a>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr class="bg-slate-50
                                       text-slate-500
                                       text-xs
                                       uppercase
                                       tracking-wider">

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
                                    Progress
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

                            @forelse($recentEvaluations as $assignment)

                            <tr class="hover:bg-slate-50 transition">


                                <td class="px-8 py-6">

                                    <p class="font-bold
                                                  text-slate-900
                                                  uppercase">

                                        {{ $assignment->gn_name }}

                                    </p>

                                </td>


                                <td class="px-8 py-6 text-slate-600">

                                    {{
                                            $assignment->school_name
                                            ?? '-'
                                        }}

                                </td>


                                <td class="px-8 py-6">

                                    <div class="flex
                                                    items-center
                                                    justify-center
                                                    gap-5">

                                        {{-- PRE --}}
                                        <div class="flex
                                                        flex-col
                                                        items-center
                                                        gap-1">

                                            <span
                                                class="w-3 h-3 rounded-full
                                                    {{
                                                        $assignment->pre_status === 'Completed'
                                                            ? 'bg-emerald-500'
                                                            : (
                                                                $assignment->pre_status === 'Draft'
                                                                    ? 'bg-amber-400'
                                                                    : 'bg-slate-300'
                                                            )
                                                    }}">
                                            </span>

                                            <span class="text-[10px]
                                                             font-semibold
                                                             text-slate-400">

                                                PRE

                                            </span>

                                        </div>


                                        {{-- PDPC --}}
                                        <div class="flex
                                                        flex-col
                                                        items-center
                                                        gap-1">

                                            <span
                                                class="w-3 h-3 rounded-full
                                                    {{
                                                        $assignment->pdpc_status === 'Completed'
                                                            ? 'bg-emerald-500'
                                                            : (
                                                                $assignment->pdpc_status === 'Draft'
                                                                    ? 'bg-amber-400'
                                                                    : 'bg-slate-300'
                                                            )
                                                    }}">
                                            </span>

                                            <span class="text-[10px]
                                                             font-semibold
                                                             text-slate-400">

                                                PDPC

                                            </span>

                                        </div>


                                        {{-- Feedback --}}
                                        <div class="flex
                                                        flex-col
                                                        items-center
                                                        gap-1">

                                            <span
                                                class="w-3 h-3 rounded-full
                                                    {{
                                                        $assignment->feedback_status === 'Completed'
                                                            ? 'bg-emerald-500'
                                                            : (
                                                                $assignment->feedback_status === 'Draft'
                                                                    ? 'bg-amber-400'
                                                                    : 'bg-slate-300'
                                                            )
                                                    }}">
                                            </span>

                                            <span class="text-[10px]
                                                             font-semibold
                                                             text-slate-400">

                                                FEEDBACK

                                            </span>

                                        </div>

                                    </div>

                                </td>


                                <td class="px-8 py-6">

                                    <div class="max-w-[170px] mx-auto">

                                        <div class="flex
                                                        justify-between
                                                        text-xs
                                                        text-slate-500
                                                        mb-2">

                                            <span>

                                                {{
                                                        $assignment->completed_count
                                                    }}/3

                                            </span>

                                            <span>

                                                {{
                                                        $assignment->progress
                                                    }}%

                                            </span>

                                        </div>


                                        <div class="w-full 
                                            h-2 
                                            bg-slate-200 
                                            rounded-full 
                                            overflow-hidden">

                                            <div
                                                class="h-full rounded-full bg-blue-600"
                                                style="width: <?= $assignment->progress ?? 0 ?>%;">
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                <td class="px-8 py-6 text-center">

                                    @if($assignment->is_completed)

                                    <span class="inline-flex
                                                         items-center
                                                         gap-2
                                                         px-3 py-1.5
                                                         rounded-full
                                                         bg-emerald-100
                                                         text-emerald-700
                                                         text-xs
                                                         font-bold">

                                        <span class="w-2 h-2
                                                             bg-emerald-500
                                                             rounded-full">
                                        </span>

                                        Completed

                                    </span>


                                    @elseif($assignment->has_draft)

                                    <span class="inline-flex
                                                         items-center
                                                         gap-2
                                                         px-3 py-1.5
                                                         rounded-full
                                                         bg-amber-100
                                                         text-amber-700
                                                         text-xs
                                                         font-bold">

                                        <span class="w-2 h-2
                                                             bg-amber-400
                                                             rounded-full">
                                        </span>

                                        Draft

                                    </span>


                                    @else

                                    <span class="inline-flex
                                                         items-center
                                                         gap-2
                                                         px-3 py-1.5
                                                         rounded-full
                                                         bg-blue-100
                                                         text-blue-700
                                                         text-xs
                                                         font-bold">

                                        <span class="w-2 h-2
                                                             bg-blue-500
                                                             rounded-full">
                                        </span>

                                        Ongoing

                                    </span>

                                    @endif

                                </td>


                                <td class="px-8 py-6 text-center">

                                    <a
                                        href="{{ route(
                                                'observer.manage',
                                                $assignment->gn_id
                                            ) }}"
                                        class="inline-flex
                                                   items-center
                                                   justify-center
                                                   px-4 py-2
                                                   bg-blue-600
                                                   hover:bg-blue-700
                                                   text-white
                                                   rounded-xl
                                                   font-semibold
                                                   transition">

                                        Manage

                                    </a>

                                </td>

                            </tr>


                            @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="py-16 text-center">

                                    <div class="flex flex-col items-center">

                                        <div class="w-16 h-16
                                                        rounded-full
                                                        bg-slate-100
                                                        flex
                                                        items-center
                                                        justify-center
                                                        mb-4">

                                            <svg
                                                class="w-8 h-8 text-slate-400"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>

                                            </svg>

                                        </div>

                                        <h3 class="font-bold text-slate-700">
                                            No Evaluation Found
                                        </h3>

                                        <p class="text-slate-400 text-sm mt-1">
                                            There are currently no assigned evaluations.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>