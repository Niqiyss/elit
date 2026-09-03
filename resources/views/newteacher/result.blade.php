<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('new_teacher')->user()->gn_name }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-slate-100 py-8 px-6">

        <div class="max-w-7xl mx-auto">

            {{-- Header --}}
            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">
                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">
                    <h1 class="text-3xl font-extrabold text-white">Evaluation Result</h1>
                    <p class="text-violet-300 mt-2">View your evaluation results</p>
                </div>
            </div>


            {{-- Latest Evaluation Results --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">

                <div class="px-8 py-6 border-b border-slate-100">
                    <h2 class="text-xl font-bold text-slate-900">Latest Evaluation Results</h2>
                </div>

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr class="bg-slate-50 text-black text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 text-left font-bold">Stage</th>
                                <th class="px-6 py-4 text-left font-bold">Evaluation</th>
                                <th class="px-6 py-4 text-center font-bold">Date</th>
                                <th class="px-6 py-4 text-center font-bold">Score</th>
                                <th class="px-6 py-4 text-center font-bold">Achievement Level</th>
                                <th class="px-6 py-4 text-center font-bold">Status</th>
                                <th class="px-6 py-4 text-center font-bold">Action</th>
                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            {{-- PRE --}}
                            @if($pre)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-6 font-bold text-slate-900">PRE</td>

                                <td class="px-6 py-6">
                                    <p class="font-semibold text-slate-900">{{ $pre->form?->form_name ?? '-' }}</p>
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

                                    <span class="text-slate-400">-</span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        {{ $pre->status === 'Submitted' ? 'Completed' : 'Incomplete' }}
                                    </span>

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <a
                                        href="{{ route('new_teacher.result.pre', $pre->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">

                                        View

                                    </a>

                                </td>

                            </tr>

                            @endif


                            {{-- POST PDPC --}}
                            @if($postPdpc)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-6 font-bold text-slate-900">POST</td>

                                <td class="px-6 py-6">
                                    <p class="font-semibold text-slate-900">{{ $postPdpc->form?->form_name ?? '-' }}</p>
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

                                    <span class="text-slate-400">-</span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        {{ $postPdpc->status === 'Submitted' ? 'Completed' : 'Incomplete' }}
                                    </span>

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <a
                                        href="{{ route('new_teacher.result.pdpc', $postPdpc->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">

                                        View

                                    </a>

                                </td>

                            </tr>

                            @endif


                            {{-- POST Feedback --}}
                            @if($postFeedback)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-6 font-bold text-slate-900">POST</td>

                                <td class="px-6 py-6">
                                    <p class="font-semibold text-slate-900">{{ $postFeedback->form?->form_name ?? '-' }}</p>
                                </td>

                                <td class="px-6 py-6 text-center text-slate-600">
                                    {{ $postFeedback->observation_date ? \Carbon\Carbon::parse($postFeedback->observation_date)->format('d/m/Y') : '-' }}
                                </td>

                                <td class="px-6 py-6 text-center text-slate-400">-</td>

                                <td class="px-6 py-6 text-center text-slate-400">-</td>

                                <td class="px-6 py-6 text-center">

                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        {{ $postFeedback->status === 'Submitted' ? 'Completed' : 'Incomplete' }}
                                    </span>

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <a
                                        href="{{ route('new_teacher.result.post', $postFeedback->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">

                                        View

                                    </a>

                                </td>

                            </tr>

                            @endif


                            {{-- EXTERNAL PDPC --}}
                            @if($externalPdpc)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-6 font-bold text-slate-900">EXTERNAL</td>

                                <td class="px-6 py-6">

                                    <p class="font-semibold text-slate-900">
                                        {{ $externalPdpc->form?->form_name ?? '-' }}
                                    </p>

                                    <p class="text-xs text-slate-600 mt-1">
                                        Attempt {{ $externalPdpc->attempt_no }}
                                    </p>

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

                                    <span class="text-slate-400">-</span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if($externalPdpc->result === 'REPEAT')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                        Repeat
                                    </span>

                                    @else

                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        {{ $externalPdpc->status === 'Submitted' ? 'Completed' : 'Incomplete' }}
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <a
                                        href="{{ route('new_teacher.result.pdpc', $externalPdpc->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">

                                        View

                                    </a>

                                </td>

                            </tr>

                            @endif


                            {{-- EXTERNAL Feedback --}}
                            @if($externalFeedback)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-6 font-bold text-slate-900">EXTERNAL</td>

                                <td class="px-6 py-6">

                                    <p class="font-semibold text-slate-900">
                                        {{ $externalFeedback->form?->form_name ?? '-' }}
                                    </p>

                                    <p class="text-xs text-slate-400 mt-1">
                                        Attempt {{ $externalFeedback->attempt_no }}
                                    </p>

                                </td>

                                <td class="px-6 py-6 text-center text-slate-600">
                                    {{ $externalFeedback->observation_date ? \Carbon\Carbon::parse($externalFeedback->observation_date)->format('d/m/Y') : '-' }}
                                </td>

                                <td class="px-6 py-6 text-center text-slate-400">-</td>

                                <td class="px-6 py-6 text-center text-slate-400">-</td>

                                <td class="px-6 py-6 text-center">

                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        {{ $externalFeedback->status === 'Submitted' ? 'Completed' : 'Incomplete' }}
                                    </span>

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <a
                                        href="{{ route('new_teacher.result.post', $externalFeedback->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">

                                        View

                                    </a>

                                </td>

                            </tr>

                            @endif


                            {{-- No Results --}}
                            @if(!$pre && !$postPdpc && !$postFeedback && !$externalPdpc && !$externalFeedback)

                            <tr>

                                <td colspan="7" class="px-8 py-14 text-center">

                                    <p class="font-semibold text-slate-600">
                                        No evaluation results available.
                                    </p>

                                    <p class="text-sm text-slate-400 mt-1">
                                        Submitted evaluation results will appear here.
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

            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                <div class="px-8 py-6 border-b border-slate-100">
                    <h2 class="text-xl font-bold text-slate-900">Previous External Results</h2>
                </div>


                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr class="bg-slate-50 text-black text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 text-center font-bold">Attempt</th>
                                <th class="px-6 py-4 text-left font-bold">Evaluation</th>
                                <th class="px-6 py-4 text-center font-bold">Date</th>
                                <th class="px-6 py-4 text-center font-bold">Score</th>
                                <th class="px-6 py-4 text-center font-bold">Achievement Level</th>
                                <th class="px-6 py-4 text-center font-bold">Status</th>
                                <th class="px-6 py-4 text-center font-bold">Action</th>
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
                                        {{ $history->form?->form_name ?? '-' }}
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

                                    <span class="text-slate-400">-</span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if($history->result === 'REPEAT')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                        Repeat
                                    </span>

                                    @else

                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        {{ $history->status === 'Submitted' ? 'Completed' : 'Incomplete' }}
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <a
                                        href="{{ route('new_teacher.result.pdpc', $history->responseID) }}"
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

        </div>

    </div>

</x-app-layout>