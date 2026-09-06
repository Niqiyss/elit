<x-app-layout>

    <div class="min-h-screen bg-slate-100 py-8 px-6">

        <div class="max-w-7xl mx-auto">

            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <h1 class="text-3xl font-extrabold text-white">
                        Evaluation Result
                    </h1>

                    <p class="text-violet-300 mt-2">
                        View teacher evaluation results
                    </p>


                    <div class="border-t border-white/20 mt-5 pt-2">

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-violet-300">
                                    Teacher Name
                                </p>

                                <p class="text-base font-bold text-white mt-1">
                                    {{ $guru->gn_name }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-violet-300">
                                    Status
                                </p>

                                <p class="text-base font-bold text-white mt-1">
                                    {{ $guru->current_status }}
                                </p>
                            </div>

                            
                            <div>

                                <p class="text-xs font-bold uppercase tracking-wider text-violet-300">
                                    Overall Status
                                </p>

                                <div class="mt-1">

                                    @if($evaluationStatus === 'Completed')

                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        Completed
                                    </span>

                                    @elseif($evaluationStatus === 'Repeat Required')

                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                        Repeat Required
                                    </span>

                                    @elseif($evaluationStatus === 'In Progress')

                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                        In Progress
                                    </span>

                                    @else

                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">
                                        Pending
                                    </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Evaluation Progress --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">

                <div class="grid grid-cols-2 md:grid-cols-5 divide-y md:divide-y-0 md:divide-x divide-slate-100">


                    {{-- PRE --}}
                    <div class="px-6 py-5">

                        <p class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-3">
                            PRE
                        </p>

                        @if($preCompleted === $preTotal)

                        <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                            {{ $preCompleted }}/{{ $preTotal }}
                        </span>

                        @elseif($preCompleted > 0)

                        <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                            {{ $preCompleted }}/{{ $preTotal }}
                        </span>

                        @else

                        <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold">
                            {{ $preCompleted }}/{{ $preTotal }}
                        </span>

                        @endif

                    </div>


                    {{-- EXTERNAL --}}
                    <div class="px-6 py-5">

                        <p class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-3">
                            External
                        </p>

                        @if($externalCompleted === $externalTotal && $externalPdpc?->result === 'PASS')

                        <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                            {{ $externalCompleted }}/{{ $externalTotal }}
                        </span>

                        @elseif($externalPdpc?->result === 'REPEAT')

                        <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                            {{ $externalCompleted }}/{{ $externalTotal }}
                        </span>

                        @elseif($externalCompleted > 0)

                        <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                            {{ $externalCompleted }}/{{ $externalTotal }}
                        </span>

                        @else

                        <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold">
                            {{ $externalCompleted }}/{{ $externalTotal }}
                        </span>

                        @endif

                    </div>


                    {{-- POST --}}
                    <div class="px-6 py-5">

                        <p class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-3">
                            POST
                        </p>

                        @if($postCompleted === $postTotal)

                        <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                            {{ $postCompleted }}/{{ $postTotal }}
                        </span>

                        @elseif($postCompleted > 0)

                        <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                            {{ $postCompleted }}/{{ $postTotal }}
                        </span>

                        @else

                        <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold">
                            {{ $postCompleted }}/{{ $postTotal }}
                        </span>

                        @endif

                    </div>


                    {{-- PROGRESS --}}
                    <div class="px-6 py-5 md:col-span-1">

                        <p class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-3">
                            Progress
                        </p>


                        <div class="flex items-center gap-3">

                            <div class="flex-1 h-2 bg-slate-200 rounded-full overflow-hidden">

                                <div
                                    class="h-full rounded-full {{ $overallProgress === 100 ? 'bg-emerald-500' : 'bg-blue-600' }}"
                                    @style([ 'width: ' . $overallProgress . '%'
                                    ])>
                                </div>

                            </div>


                            <span class="text-sm font-bold text-slate-900">
                                {{ $overallProgress }}%
                            </span>

                        </div>


                        <p class="text-xs text-slate-500 mt-2">
                            {{ $completedForms }}/{{ $totalForms }} forms completed
                        </p>

                    </div>


                    {{-- STATUS --}}
                    <div class="px-6 py-5">

                        <p class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-3">
                            Status
                        </p>


                        @if($evaluationStatus === 'Completed')

                        <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                            Completed
                        </span>

                        @elseif($evaluationStatus === 'Repeat Required')

                        <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                            Repeat Required
                        </span>

                        @elseif($evaluationStatus === 'In Progress')

                        <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                            In Progress
                        </span>

                        @else

                        <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold">
                            Pending
                        </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Latest Evaluation Results --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">

                <div class="px-8 py-6 border-b border-slate-100">

                    <h2 class="text-xl font-bold text-slate-900">
                        Latest Evaluation Results
                    </h2>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr class="bg-slate-50 text-black text-xs uppercase tracking-wider">

                                <th class="px-6 py-4 text-left font-bold">
                                    Stage
                                </th>

                                <th class="px-6 py-4 text-left font-bold">
                                    Evaluation
                                </th>

                                <th class="px-6 py-4 text-center font-bold">
                                    Date
                                </th>

                                <th class="px-6 py-4 text-center font-bold">
                                    Score
                                </th>

                                <th class="px-6 py-4 text-center font-bold">
                                    Achievement Level
                                </th>

                                <th class="px-6 py-4 text-center font-bold">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-center font-bold">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            {{-- PRE --}}
                            @if($pre)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-6 font-bold text-slate-900">
                                    PRE
                                </td>

                                <td class="px-6 py-6">
                                    <p class="font-semibold text-slate-900">
                                        {{ $pre->form_name ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-6 py-6 text-center text-slate-600">
                                    {{ $pre->observation_date ? \Carbon\Carbon::parse($pre->observation_date)->format('d/m/Y') : '-' }}
                                </td>

                                <td class="px-6 py-6 text-center font-semibold text-slate-800">
                                    {{ $pre->percentage !== null ? number_format($pre->percentage, 2).'%' : '-' }}
                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if($pre->achievement_level)

                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        {{ $pre->achievement_level }}
                                    </span>

                                    @else

                                    <span class="text-slate-400">
                                        -
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        Completed
                                    </span>

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <a
                                        href="{{ route('principal.result.pre', $pre->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">

                                        View

                                    </a>

                                </td>

                            </tr>

                            @endif


                            {{-- POST PDPC --}}
                            @if($postPdpc)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-6 font-bold text-slate-900">
                                    POST
                                </td>

                                <td class="px-6 py-6">
                                    <p class="font-semibold text-slate-900">
                                        {{ $postPdpc->form_name ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-6 py-6 text-center text-slate-600">
                                    {{ $postPdpc->observation_date ? \Carbon\Carbon::parse($postPdpc->observation_date)->format('d/m/Y') : '-' }}
                                </td>

                                <td class="px-6 py-6 text-center font-semibold text-slate-800">
                                    {{ $postPdpc->percentage !== null ? number_format($postPdpc->percentage, 2).'%' : '-' }}
                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if($postPdpc->achievement_level)

                                    <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                        {{ $postPdpc->achievement_level }}
                                    </span>

                                    @else

                                    <span class="text-slate-400">
                                        -
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        Completed
                                    </span>

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <a
                                        href="{{ route('principal.result.pdpc', $postPdpc->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">

                                        View

                                    </a>

                                </td>

                            </tr>

                            @endif


                            {{-- POST Feedback --}}
                            @if($postFeedback)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-6 font-bold text-slate-900">
                                    POST
                                </td>

                                <td class="px-6 py-6">
                                    <p class="font-semibold text-slate-900">
                                        {{ $postFeedback->form_name ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-6 py-6 text-center text-slate-600">
                                    {{ $postFeedback->observation_date ? \Carbon\Carbon::parse($postFeedback->observation_date)->format('d/m/Y') : '-' }}
                                </td>

                                <td class="px-6 py-6 text-center text-slate-400">
                                    -
                                </td>

                                <td class="px-6 py-6 text-center text-slate-400">
                                    -
                                </td>

                                <td class="px-6 py-6 text-center">

                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        Completed
                                    </span>

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <a
                                        href="{{ route('principal.result.post', $postFeedback->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">

                                        View

                                    </a>

                                </td>

                            </tr>

                            @endif


                            {{-- EXTERNAL PDPC --}}
                            @if($externalPdpc)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-6 font-bold text-slate-900">
                                    EXTERNAL
                                </td>

                                <td class="px-6 py-6">

                                    <p class="font-semibold text-slate-900">
                                        {{ $externalPdpc->form_name ?? '-' }}
                                    </p>

                                    @if(($externalPdpc->attempt_no ?? 1) > 1)

                                    <p class="text-xs text-slate-600 mt-1">
                                        Attempt {{ $externalPdpc->attempt_no }}
                                    </p>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center text-slate-600">
                                    {{ $externalPdpc->observation_date ? \Carbon\Carbon::parse($externalPdpc->observation_date)->format('d/m/Y') : '-' }}
                                </td>

                                <td class="px-6 py-6 text-center font-semibold text-slate-800">
                                    {{ $externalPdpc->percentage !== null ? number_format($externalPdpc->percentage, 2).'%' : '-' }}
                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if($externalPdpc->achievement_level)

                                    <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                        {{ $externalPdpc->achievement_level }}
                                    </span>

                                    @else

                                    <span class="text-slate-400">
                                        -
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if($externalPdpc->result === 'REPEAT')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                        Repeat
                                    </span>

                                    @else

                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        Completed
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <a
                                        href="{{ route('principal.result.pdpc', $externalPdpc->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">

                                        View

                                    </a>

                                </td>

                            </tr>

                            @endif


                            {{-- EXTERNAL Feedback --}}
                            @if($externalFeedback)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-6 font-bold text-slate-900">
                                    EXTERNAL
                                </td>

                                <td class="px-6 py-6">

                                    <p class="font-semibold text-slate-900">
                                        {{ $externalFeedback->form_name ?? '-' }}
                                    </p>

                                    @if(($externalFeedback->attempt_no ?? 1) > 1)

                                    <p class="text-xs text-slate-400 mt-1">
                                        Attempt {{ $externalFeedback->attempt_no }}
                                    </p>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center text-slate-600">
                                    {{ $externalFeedback->observation_date ? \Carbon\Carbon::parse($externalFeedback->observation_date)->format('d/m/Y') : '-' }}
                                </td>

                                <td class="px-6 py-6 text-center text-slate-400">
                                    -
                                </td>

                                <td class="px-6 py-6 text-center text-slate-400">
                                    -
                                </td>

                                <td class="px-6 py-6 text-center">

                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        Completed
                                    </span>

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <a
                                        href="{{ route('principal.result.post', $externalFeedback->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">

                                        View

                                    </a>

                                </td>

                            </tr>

                            @endif


                            {{-- Empty --}}
                            @if(!$pre && !$postPdpc && !$postFeedback && !$externalPdpc && !$externalFeedback)

                            <tr>

                                <td colspan="7" class="px-8 py-14 text-center">

                                    <p class="font-semibold text-slate-600">
                                        No evaluation results available
                                    </p>

                                    <p class="text-sm text-slate-400 mt-1">
                                        Submitted evaluation results will appear here
                                    </p>

                                </td>

                            </tr>

                            @endif

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- Previous External Attempts --}}
            @if($externalHistory->isNotEmpty())

            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">

                <div class="px-8 py-6 border-b border-slate-100">

                    <h2 class="text-xl font-bold text-slate-900">
                        Previous External Results
                    </h2>
                </div>


                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr class="bg-slate-50 text-black text-xs uppercase tracking-wider">

                                <th class="px-6 py-4 text-center font-bold">
                                    Attempt
                                </th>

                                <th class="px-6 py-4 text-left font-bold">
                                    Evaluation
                                </th>

                                <th class="px-6 py-4 text-center font-bold">
                                    Date
                                </th>

                                <th class="px-6 py-4 text-center font-bold">
                                    Score
                                </th>

                                <th class="px-6 py-4 text-center font-bold">
                                    Achievement Level
                                </th>

                                <th class="px-6 py-4 text-center font-bold">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-center font-bold">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @foreach($externalHistory as $history)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-6 text-center">

                                    <span class="inline-flex px-3 py-1 rounded-full bg-violet-100 text-violet-700 text-xs font-bold">
                                        Attempt {{ $history->attempt_no }}
                                    </span>

                                </td>

                                <td class="px-6 py-6">

                                    <p class="font-semibold text-slate-900">
                                        {{ $history->form_name ?? '-' }}
                                    </p>

                                </td>

                                <td class="px-6 py-6 text-center text-slate-600">
                                    {{ $history->observation_date ? \Carbon\Carbon::parse($history->observation_date)->format('d/m/Y') : '-' }}
                                </td>

                                <td class="px-6 py-6 text-center font-semibold text-slate-800">
                                    {{ $history->percentage !== null ? number_format($history->percentage, 2).'%' : '-' }}
                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if($history->achievement_level)

                                    <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                        {{ $history->achievement_level }}
                                    </span>

                                    @else

                                    <span class="text-slate-400">
                                        -
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if($history->result === 'REPEAT')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                        Repeat
                                    </span>

                                    @else

                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        Completed
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <a
                                        href="{{ route('principal.result.pdpc', $history->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">

                                        View

                                    </a>

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

            @endif


            {{-- Back Button --}}
            <div class="mt-8 flex justify-center">
                <a href="{{ route('principal.result') }}" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                    Back
                </a>
            </div>

        </div>

    </div>

</x-app-layout>