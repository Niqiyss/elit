<x-app-layout>

    <div class="min-h-screen bg-slate-100 py-8 px-6">

        <div class="max-w-7xl mx-auto">

            <div class="relative
                        bg-gradient-to-br
                        from-slate-900
                        via-violet-950
                        to-purple-900
                        rounded-3xl
                        p-8
                        shadow-xl
                        overflow-hidden
                        mb-8">

                <div class="absolute
                            right-0
                            top-0
                            translate-x-10
                            -translate-y-10
                            w-72
                            h-72
                            bg-purple-500/10
                            rounded-full
                            blur-3xl">
                </div>

                <div class="relative z-10">

                    <p class="text-xs
                              font-bold
                              uppercase
                              tracking-[0.25em]
                              text-violet-300
                              mb-3">

                        External Observer Dashboard

                    </p>

                    <h1 class="text-3xl font-extrabold text-white">

                        Welcome,
                        {{ Auth::guard('teacher')->user()->teacher_name }}

                    </h1>


                </div>

            </div>


            <div class="grid
                        grid-cols-1
                        md:grid-cols-2
                        xl:grid-cols-4
                        gap-6
                        mb-8">


                <div class="bg-white
                            rounded-2xl
                            shadow-md
                            p-6">

                    <div class="flex
                                items-center
                                justify-between">

                        <div>

                            <p class="text-sm
                                      font-semibold
                                      text-slate-500">

                                Assigned Teachers

                            </p>

                            <p class="text-3xl
                                      font-bold
                                      text-slate-900
                                      mt-2">

                                {{ $totalAssigned }}

                            </p>

                        </div>

                        <div class="w-12
                                    h-12
                                    rounded-xl
                                    bg-blue-100
                                    text-blue-600
                                    flex
                                    items-center
                                    justify-center">

                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-10a4 4 0 100-8 4 4 0 000 8zm11 10v-2a4 4 0 00-3-3.87">
                                </path>

                            </svg>

                        </div>

                    </div>

                </div>


                {{-- Ongoing Evaluations --}}

                <div class="bg-white
                            rounded-2xl
                            shadow-md
                            p-6">

                    <div class="flex
                                items-center
                                justify-between">

                        <div>

                            <p class="text-sm
                                      font-semibold
                                      text-slate-500">

                                Ongoing Evaluations

                            </p>

                            <p class="text-3xl
                                      font-bold
                                      text-slate-900
                                      mt-2">

                                {{ $ongoingCount }}

                            </p>

                        </div>

                        <div class="w-12
                                    h-12
                                    rounded-xl
                                    bg-blue-100
                                    text-blue-600
                                    flex
                                    items-center
                                    justify-center">

                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24">

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="9">
                                </circle>

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 7v5l3 2">
                                </path>

                            </svg>

                        </div>

                    </div>

                </div>


                {{-- Completed Evaluations --}}

                <div class="bg-white
                            rounded-2xl
                            shadow-md
                            p-6">

                    <div class="flex
                                items-center
                                justify-between">

                        <div>

                            <p class="text-sm
                                      font-semibold
                                      text-slate-500">

                                Completed Evaluations

                            </p>

                            <p class="text-3xl
                                      font-bold
                                      text-slate-900
                                      mt-2">

                                {{ $completedCount }}

                            </p>

                        </div>

                        <div class="w-12
                                    h-12
                                    rounded-xl
                                    bg-emerald-100
                                    text-emerald-600
                                    flex
                                    items-center
                                    justify-center">

                            <svg
                                class="w-6 h-6"
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


                {{-- Repeat Required --}}

                <div class="bg-white
                            rounded-2xl
                            shadow-md
                            p-6">

                    <div class="flex
                                items-center
                                justify-between">

                        <div>

                            <p class="text-sm
                                      font-semibold
                                      text-slate-500">

                                Repeat Required

                            </p>

                            <p class="text-3xl
                                      font-bold
                                      text-slate-900
                                      mt-2">

                                {{ $repeatCount }}

                            </p>

                        </div>

                        <div class="w-12
                                    h-12
                                    rounded-xl
                                    bg-red-100
                                    text-red-500
                                    flex
                                    items-center
                                    justify-center">

                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4 4v6h6M20 20v-6h-6M5.5 15A7 7 0 0018 17M18.5 9A7 7 0 006 7">
                                </path>

                            </svg>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================================== --}}
            {{-- RECENT EVALUATIONS --}}
            {{-- ====================================================== --}}

            <div class="bg-white
                        rounded-3xl
                        shadow-lg
                        overflow-hidden">


                {{-- Header --}}

                <div class="px-8
                            py-6
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

                            Latest external observation progress

                        </p>

                    </div>


                    <a
                        href="{{ route('external.list.evaluate') }}"
                        class="bg-blue-600
                               hover:bg-blue-700
                               text-white
                               px-5
                               py-2.5
                               rounded-xl
                               font-semibold
                               transition">

                        View All

                    </a>

                </div>


                {{-- Table --}}

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
                                    Result
                                </th>

                                <th class="px-8 py-4 text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @forelse($recentEvaluations as $assignment)

                            <tr class="hover:bg-slate-50 transition">


                                {{-- ================================== --}}
                                {{-- TEACHER --}}
                                {{-- ================================== --}}

                                <td class="px-8 py-6">

                                    <p class="font-bold
                                              text-slate-900
                                              uppercase">

                                        {{ $assignment->gn_name }}

                                    </p>

                                </td>


                                {{-- ================================== --}}
                                {{-- SCHOOL --}}
                                {{-- ================================== --}}

                                <td class="px-8
                                           py-6
                                           text-slate-600">

                                    {{ $assignment->school_name ?? '-' }}

                                </td>


                                {{-- ================================== --}}
                                {{-- COMPLETION --}}
                                {{-- ================================== --}}

                                <td class="px-8 py-6">

                                    <div class="flex
                                                justify-center
                                                gap-6">


                                        {{-- PDPC --}}

                                        <div class="text-center">

                                            <span
                                                class="block
                                                       mx-auto
                                                       w-3
                                                       h-3
                                                       rounded-full
                                                       {{ $assignment->pdpc_status === 'Completed'
                                                            ? 'bg-emerald-500'
                                                            : ($assignment->pdpc_status === 'Draft'
                                                                ? 'bg-amber-400'
                                                                : 'bg-slate-300') }}">
                                            </span>

                                            <span class="block
                                                         text-[10px]
                                                         text-slate-400
                                                         mt-1">

                                                PDPC

                                            </span>

                                        </div>


                                        {{-- Feedback --}}

                                        <div class="text-center">

                                            <span
                                                class="block
                                                       mx-auto
                                                       w-3
                                                       h-3
                                                       rounded-full
                                                       {{ $assignment->feedback_status === 'Completed'
                                                            ? 'bg-emerald-500'
                                                            : ($assignment->feedback_status === 'Draft'
                                                                ? 'bg-amber-400'
                                                                : 'bg-slate-300') }}">
                                            </span>

                                            <span class="block
                                                         text-[10px]
                                                         text-slate-400
                                                         mt-1">

                                                FEEDBACK

                                            </span>

                                        </div>

                                    </div>

                                </td>


                                {{-- ================================== --}}
                                {{-- PROGRESS --}}
                                {{-- ================================== --}}

                                <td class="px-8 py-6">

                                    <div class="w-28 mx-auto">


                                        <div class="flex
                                                    justify-between
                                                    text-xs
                                                    text-slate-500
                                                    mb-2">

                                            <span>

                                                {{ $assignment->completed_count }}
                                                /
                                                {{ $assignment->total_forms }}

                                            </span>

                                            <span>

                                                {{ $assignment->progress }}%

                                            </span>

                                        </div>


                                        <div class="w-full
                                                    h-2
                                                    bg-slate-200
                                                    rounded-full
                                                    overflow-hidden">


                                            {{-- 100% --}}

                                            @if($assignment->progress >= 100)

                                                @if($assignment->is_repeat)

                                                    <div class="h-full
                                                                w-full
                                                                rounded-full
                                                                bg-red-500">
                                                    </div>

                                                @else

                                                    <div class="h-full
                                                                w-full
                                                                rounded-full
                                                                bg-blue-600">
                                                    </div>

                                                @endif


                                            {{-- 50% --}}

                                            @elseif($assignment->progress >= 50)

                                                @if($assignment->is_repeat)

                                                    <div class="h-full
                                                                w-1/2
                                                                rounded-full
                                                                bg-red-500">
                                                    </div>

                                                @else

                                                    <div class="h-full
                                                                w-1/2
                                                                rounded-full
                                                                bg-blue-600">
                                                    </div>

                                                @endif

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- ================================== --}}
                                {{-- RESULT --}}
                                {{-- ================================== --}}

                                <td class="px-8 py-6 text-center">


                                    {{-- REPEAT --}}

                                    @if($assignment->result === 'REPEAT')

                                        <span class="inline-flex
                                                     items-center
                                                     gap-2
                                                     px-3
                                                     py-1.5
                                                     rounded-full
                                                     bg-red-100
                                                     text-red-700
                                                     text-xs
                                                     font-bold">

                                            <span class="w-2
                                                         h-2
                                                         rounded-full
                                                         bg-red-500">
                                            </span>

                                            REPEAT

                                        </span>


                                    {{-- PASS --}}

                                    @elseif($assignment->result === 'PASS')

                                        <span class="inline-flex
                                                     items-center
                                                     gap-2
                                                     px-3
                                                     py-1.5
                                                     rounded-full
                                                     bg-emerald-100
                                                     text-emerald-700
                                                     text-xs
                                                     font-bold">

                                            <span class="w-2
                                                         h-2
                                                         rounded-full
                                                         bg-emerald-500">
                                            </span>

                                            PASS

                                        </span>


                                    {{-- PENDING --}}

                                    @else

                                        <span class="inline-flex
                                                     items-center
                                                     gap-2
                                                     px-3
                                                     py-1.5
                                                     rounded-full
                                                     bg-slate-100
                                                     text-slate-600
                                                     text-xs
                                                     font-bold">

                                            <span class="w-2
                                                         h-2
                                                         rounded-full
                                                         bg-slate-400">
                                            </span>

                                            PENDING

                                        </span>

                                    @endif

                                </td>


                                {{-- ================================== --}}
                                {{-- ACTION --}}
                                {{-- ================================== --}}

                                <td class="px-8 py-6 text-center">

                                    <a
                                        href="{{ route(
                                            'external.manage',
                                            $assignment->gn_id
                                        ) }}"
                                        class="inline-flex
                                               items-center
                                               justify-center
                                               bg-blue-600
                                               hover:bg-blue-700
                                               text-white
                                               px-5
                                               py-2.5
                                               rounded-xl
                                               font-semibold
                                               transition
                                               shadow-sm">

                                        Manage

                                    </a>

                                </td>

                            </tr>


                            @empty

                            {{-- ====================================== --}}
                            {{-- EMPTY --}}
                            {{-- ====================================== --}}

                            <tr>

                                <td
                                    colspan="6"
                                    class="py-16 text-center">

                                    <div class="flex
                                                flex-col
                                                items-center">

                                        <div class="w-16
                                                    h-16
                                                    rounded-full
                                                    bg-slate-100
                                                    flex
                                                    items-center
                                                    justify-center
                                                    mb-4">

                                            <svg
                                                class="w-8
                                                       h-8
                                                       text-slate-400"
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


                                        <h3 class="font-bold
                                                   text-slate-700">

                                            No Evaluation Found

                                        </h3>


                                        <p class="text-slate-400
                                                  text-sm
                                                  mt-1">

                                            There are currently no evaluation assignments.

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