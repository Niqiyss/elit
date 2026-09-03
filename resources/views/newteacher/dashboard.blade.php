<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('new_teacher')->user()->gn_name }}
        </h2>
    </x-slot>


    <div class="min-h-screen bg-slate-100 py-8 px-6">

        <div class="max-w-7xl mx-auto">


            {{-- Header --}}
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 p-7 shadow-xl mb-7">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 rounded-full bg-purple-500/10 blur-3xl"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                    {{-- Welcome --}}
                    <div>

                        <p class="text-xs font-bold uppercase tracking-widest text-violet-300 mb-2">
                            New Teacher Dashboard
                        </p>

                        <h1 class="text-3xl font-extrabold text-white">
                            Welcome, {{ $guru->gn_name }}
                        </h1>

                    </div>


                    {{-- Evaluation Progress --}}
                    <div class="w-full lg:w-[320px] bg-white/10 border border-white/10 rounded-2xl px-5 py-4 backdrop-blur-sm">

                        <div class="flex items-center justify-between gap-4">

                            <div>

                                <p class="text-xs font-bold uppercase tracking-wider text-white">
                                    Evaluation Progress
                                </p>

                                <p class="text-2xl font-extrabold text-white mt-1">
                                    {{ $submittedForms }} of {{ $totalForms }}
                                </p>

                                <p class="text-xs text-violet-200 mt-1">
                                    Forms completed
                                </p>

                            </div>


                            {{-- Compact visual --}}
                            <div class="flex items-center gap-1.5">

                                @for($i = 1; $i <= $totalForms; $i++)

                                    <div class="w-3 h-3 rounded-full
                            {{
                                $i <= $submittedForms
                                    ? 'bg-emerald-400'
                                    : 'bg-white/20'
                            }}">
                            </div>

                            @endfor

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- Evaluation Timeline --}}
        <div class="bg-white
                        rounded-3xl
                        border border-slate-100
                        shadow-lg
                        overflow-hidden">

            {{-- Header --}}
            <div class="px-7 py-5
                            border-b border-slate-100
                            flex flex-col sm:flex-row
                            sm:items-center
                            sm:justify-between
                            gap-4">

                <div>

                    <h2 class="text-xl font-bold text-slate-900">
                        Evaluation Progress
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        PRE → EXTERNAL → POST
                    </p>

                </div>


                <a
                    href="{{ route('new_teacher.result') }}"
                    class="inline-flex items-center justify-center
                               px-4 py-2
                               bg-blue-600 hover:bg-blue-700
                               text-white text-sm font-semibold
                               rounded-xl transition">

                    View Results

                </a>

            </div>



            <div class="px-6 md:px-8 py-7">

                {{-- Horizontal Timeline --}}
                <div class="relative mb-8">

                    <div class="hidden md:block
                                    absolute top-5
                                    left-[16.5%] right-[16.5%]
                                    h-1
                                    bg-slate-200
                                    rounded-full">
                    </div>


                    <div class="relative grid grid-cols-1 md:grid-cols-3 gap-5">

                        {{-- PRE --}}
                        <div class="relative">

                            <div class="flex md:flex-col md:items-center gap-4">

                                <div class="relative z-10 flex-shrink-0">

                                    <div class="w-11 h-11
                                                    rounded-full
                                                    border-4 border-white
                                                    shadow
                                                    flex items-center justify-center
                                                    font-bold
                                                    {{
                                                        $preStatus === 'completed'
                                                            ? 'bg-emerald-500 text-white'
                                                            : 'bg-slate-300 text-white'
                                                    }}">

                                        @if($preStatus === 'completed')
                                        ✓
                                        @else
                                        1
                                        @endif

                                    </div>

                                </div>


                                <div class="md:text-center">

                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                        Stage 1
                                    </p>

                                    <h3 class="font-bold text-slate-900 mt-1">
                                        Pre-Observation
                                    </h3>

                                </div>

                            </div>

                        </div>


                        {{-- EXTERNAL --}}
                        <div class="relative">

                            <div class="flex md:flex-col md:items-center gap-4">

                                <div class="relative z-10 flex-shrink-0">

                                    <div class="w-11 h-11
                                                    rounded-full
                                                    border-4 border-white
                                                    shadow
                                                    flex items-center justify-center
                                                    font-bold
                                                    {{
                                                        $externalStatus === 'completed'
                                                            ? 'bg-emerald-500 text-white'
                                                            : (
                                                                $externalStatus === 'repeat'
                                                                    ? 'bg-red-500 text-white'
                                                                    : (
                                                                        $externalStatus === 'in_progress'
                                                                            ? 'bg-blue-500 text-white'
                                                                            : 'bg-slate-300 text-white'
                                                                    )
                                                            )
                                                    }}">

                                        @if($externalStatus === 'completed')
                                        ✓
                                        @elseif($externalStatus === 'repeat')
                                        ↻
                                        @else
                                        2
                                        @endif

                                    </div>

                                </div>


                                <div class="md:text-center">

                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                        Stage 2
                                    </p>

                                    <h3 class="font-bold text-slate-900 mt-1">
                                        External Observation
                                    </h3>

                                </div>

                            </div>

                        </div>


                        {{-- POST --}}
                        <div class="relative">

                            <div class="flex md:flex-col md:items-center gap-4">

                                <div class="relative z-10 flex-shrink-0">

                                    <div class="w-11 h-11
                                                    rounded-full
                                                    border-4 border-white
                                                    shadow
                                                    flex items-center justify-center
                                                    font-bold
                                                    {{
                                                        $postStatus === 'completed'
                                                            ? 'bg-emerald-500 text-white'
                                                            : (
                                                                $postStatus === 'in_progress'
                                                                    ? 'bg-blue-500 text-white'
                                                                    : 'bg-slate-300 text-white'
                                                            )
                                                    }}">

                                        @if($postStatus === 'completed')
                                        ✓
                                        @else
                                        3
                                        @endif

                                    </div>

                                </div>


                                <div class="md:text-center">

                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                        Stage 3
                                    </p>

                                    <h3 class="font-bold text-slate-900 mt-1">
                                        Post-Observation
                                    </h3>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- Stage Cards --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">


                    {{-- PRE --}}
                    <div class="border border-slate-200 rounded-2xl p-5">

                        <div class="flex items-center justify-between gap-3 mb-4">

                            <h3 class="font-bold text-slate-900">
                                Pre-Observation
                            </h3>


                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{
                                        $preStatus === 'completed'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-slate-100 text-slate-500'
                                    }}">

                                {{
                                        $preStatus === 'completed'
                                            ? 'Completed'
                                            : 'Pending'
                                    }}

                            </span>

                        </div>


                        <div class="flex items-center justify-between gap-4
                                        bg-slate-50
                                        rounded-xl
                                        px-4 py-3">

                            <span class="text-sm text-slate-700">
                                {{ $pre?->form?->form_name ?? 'Pre-Observation Form' }}
                            </span>


                            @if($pre?->percentage !== null)

                            <span class="text-sm font-bold text-slate-800">
                                {{ number_format((float) $pre->percentage, 2) }}%
                            </span>

                            @elseif(!$pre)

                            <span class="text-xs font-bold text-slate-400">
                                Pending
                            </span>

                            @endif

                        </div>

                    </div>



                    {{-- EXTERNAL --}}
                    <div class="border rounded-2xl p-5
                            {{
                                $externalStatus === 'repeat'
                                    ? 'border-red-200 bg-red-50/50'
                                    : 'border-slate-200'
                            }}">

                        <div class="flex items-center justify-between gap-3 mb-4">

                            <h3 class="font-bold text-slate-900">
                                External Observation
                            </h3>


                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{
                                        $externalStatus === 'completed'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : (
                                                $externalStatus === 'repeat'
                                                    ? 'bg-red-100 text-red-700'
                                                    : (
                                                        $externalStatus === 'in_progress'
                                                            ? 'bg-blue-100 text-blue-700'
                                                            : 'bg-slate-100 text-slate-500'
                                                    )
                                            )
                                    }}">

                                @if($externalStatus === 'completed')
                                Completed
                                @elseif($externalStatus === 'repeat')
                                Repeat Required
                                @elseif($externalStatus === 'in_progress')
                                In Progress
                                @else
                                Pending
                                @endif

                            </span>

                        </div>


                        {{-- Current PDPC --}}
                        <div class="flex items-center justify-between gap-4
                                        bg-white
                                        border border-slate-200
                                        rounded-xl
                                        px-4 py-3">

                            <span class="text-sm text-slate-700">
                                {{ $externalPdpc?->form?->form_name ?? 'PDPC Observation Form' }}
                            </span>


                            @if($externalPdpc?->percentage !== null)

                            <span class="text-sm font-bold text-slate-800">
                                {{ number_format((float) $externalPdpc->percentage, 2) }}%
                            </span>

                            @elseif(!$externalPdpc)

                            <span class="text-xs font-bold text-slate-400">
                                Pending
                            </span>

                            @endif

                        </div>


                        {{-- Current Feedback --}}
                        <div class="flex items-center justify-between gap-4
                                        bg-white
                                        border border-slate-200
                                        rounded-xl
                                        px-4 py-3 mt-2">

                            <span class="text-sm text-slate-700">
                                {{ $externalFeedback?->form?->form_name ?? 'Feedback Form' }}
                            </span>


                            @if(!$externalFeedback)

                            <span class="text-xs font-bold text-slate-400">
                                Pending
                            </span>

                            @endif

                        </div>


                        {{-- Previous Evaluations --}}
                        @if($externalAttempts->count() > 1)

                        <div class="mt-4 pt-4 border-t border-slate-200">

                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                                Previous Evaluation
                            </p>


                            <div class="space-y-2">

                                @foreach($externalAttempts->skip(1) as $previous)

                                <div class="flex items-center justify-between gap-4
                                                        bg-white
                                                        border border-slate-200
                                                        rounded-xl
                                                        px-4 py-3">

                                    <span class="text-sm font-semibold text-slate-700">
                                        Score
                                    </span>


                                    <div class="flex items-center gap-3">

                                        @if($previous->percentage !== null)

                                        <span class="text-sm font-bold text-slate-800">
                                            {{ number_format((float) $previous->percentage, 2) }}%
                                        </span>

                                        @endif


                                        @if($previous->result)

                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold
                                                            {{
                                                                $previous->result === 'PASS'
                                                                    ? 'bg-emerald-100 text-emerald-700'
                                                                    : 'bg-red-100 text-red-700'
                                                            }}">

                                            {{ $previous->result }}

                                        </span>

                                        @endif

                                    </div>

                                </div>

                                @endforeach

                            </div>

                        </div>

                        @endif

                    </div>



                    {{-- POST --}}
                    <div class="border border-slate-200 rounded-2xl p-5">

                        <div class="flex items-center justify-between gap-3 mb-4">

                            <h3 class="font-bold text-slate-900">
                                Post-Observation
                            </h3>


                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{
                                        $postStatus === 'completed'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : (
                                                $postStatus === 'in_progress'
                                                    ? 'bg-blue-100 text-blue-700'
                                                    : 'bg-slate-100 text-slate-500'
                                            )
                                    }}">

                                @if($postStatus === 'completed')
                                Completed
                                @elseif($postStatus === 'in_progress')
                                In Progress
                                @else
                                Pending
                                @endif

                            </span>

                        </div>


                        {{-- POST PDPC --}}
                        <div class="flex items-center justify-between gap-4
                                        bg-slate-50
                                        rounded-xl
                                        px-4 py-3">

                            <span class="text-sm text-slate-700">
                                {{ $postPdpc?->form?->form_name ?? 'PDPC Observation Form' }}
                            </span>


                            @if($postPdpc?->percentage !== null)

                            <span class="text-sm font-bold text-slate-800">
                                {{ number_format((float) $postPdpc->percentage, 2) }}%
                            </span>

                            @elseif(!$postPdpc)

                            <span class="text-xs font-bold text-slate-400">
                                Pending
                            </span>

                            @endif

                        </div>


                        {{-- POST Feedback --}}
                        <div class="flex items-center justify-between gap-4
                                        bg-slate-50
                                        rounded-xl
                                        px-4 py-3 mt-2">

                            <span class="text-sm text-slate-700">
                                {{ $postFeedback?->form?->form_name ?? 'Feedback Observation Form' }}
                            </span>


                            @if(!$postFeedback)

                            <span class="text-xs font-bold text-slate-400">
                                Pending
                            </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    </div>

</x-app-layout>