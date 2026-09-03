<x-app-layout>

    <div class="min-h-screen bg-slate-100 py-8 px-6">

        <div class="max-w-7xl mx-auto">

            {{-- Messages --}}
            @if(session('success'))
            <div class="mb-6 px-5 py-4 bg-green-100 border border-green-200 text-green-700 rounded-xl">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 px-5 py-4 bg-red-100 border border-red-200 text-red-700 rounded-xl">
                {{ session('error') }}
            </div>
            @endif


            {{-- Header --}}
            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <h1 class="text-3xl font-extrabold text-white">
                        Manage Evaluation
                    </h1>

                    <p class="text-violet-300 mt-2">
                        Manage observation and feedback forms
                    </p>

                    <div class="border-t border-white/20 my-6"></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-violet-300 mb-1">
                                Teacher
                            </p>

                            <p class="text-lg font-bold text-white uppercase">
                                {{ $guruNew->gn_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-violet-300 mb-1">
                                School
                            </p>

                            <p class="text-lg font-bold text-white">
                                {{ $guruNew->school?->school_name ?? '-' }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Normal Observer --}}
            @if($isObserver)

            {{-- PRE Stage --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">

                <div class="px-8 py-6 border-b border-slate-100">

                    <h2 class="text-xl font-bold text-slate-900">
                        Stage: Pre-Observation
                    </h2>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr class="bg-slate-50 text-black text-xs uppercase tracking-wider">

                                <th class="px-6 py-4 text-left font-bold">
                                    Form
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

                        <tbody>

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-6">

                                    <p class="font-bold text-slate-900">
                                        {{ $preForm?->form_name ?? 'Pre-Observation Form' }}
                                    </p>

                                </td>

                                <td class="px-6 py-6 text-center text-slate-600">

                                    @if($preResponse?->observation_date)

                                    {{ \Carbon\Carbon::parse($preResponse->observation_date)->format('d/m/Y') }}

                                    @else

                                    -

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center font-semibold text-slate-800">

                                    @if($preResponse && $preResponse->percentage !== null)

                                    {{ number_format($preResponse->percentage, 2) }}%

                                    @else

                                    -

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if($preResponse?->achievement_level)

                                    <span class="inline-flex px-3 py-1 rounded-full bg-violet-100 text-violet-700 text-xs font-bold">
                                        {{ $preResponse->achievement_level }}
                                    </span>

                                    @else

                                    <span class="text-slate-400">
                                        -
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if(!$preResponse)

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                        Pending
                                    </span>

                                    @elseif($preResponse->status === 'Draft')

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        Draft
                                    </span>

                                    @else

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                        Completed
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if(!$preResponse)

                                    <a href="{{ route('observer.pre.create', $guruNew->gn_id) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition">
                                        Add
                                    </a>

                                    @elseif($preResponse->status === 'Draft')

                                    <a href="{{ route('observer.pre.edit', $preResponse->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-yellow-200 hover:bg-yellow-300 text-yellow-700 rounded-xl font-semibold transition">
                                        Edit
                                    </a>

                                    @else

                                    <a href="{{ route('observer.pre.view', $preResponse->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-sky-200 hover:bg-sky-300 text-sky-700 rounded-xl font-semibold transition">
                                        View
                                    </a>

                                    @endif

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- POST Stage --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">

                <div class="px-8 py-6 border-b border-slate-100">

                    <h2 class="text-xl font-bold text-slate-900">
                        Stage: Post-Observation
                    </h2>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr class="bg-slate-50 text-black text-xs uppercase tracking-wider">

                                <th class="px-6 py-4 text-left font-bold">
                                    Form
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

                            {{-- POST PDPC --}}
                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-6">

                                    <p class="font-bold text-slate-900">
                                        {{ $pdpcForm?->form_name ?? 'PDPC Observation Form' }}
                                    </p>

                                </td>

                                <td class="px-6 py-6 text-center text-slate-600">

                                    @if($pdpcPostResponse?->observation_date)

                                    {{ \Carbon\Carbon::parse($pdpcPostResponse->observation_date)->format('d/m/Y') }}

                                    @else

                                    -

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center font-semibold text-slate-800">

                                    @if($pdpcPostResponse && $pdpcPostResponse->percentage !== null)

                                    {{ number_format($pdpcPostResponse->percentage, 2) }}%

                                    @else

                                    -

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if($pdpcPostResponse?->achievement_level)

                                    <span class="inline-flex px-3 py-1 rounded-full bg-violet-100 text-violet-700 text-xs font-bold">
                                        {{ $pdpcPostResponse->achievement_level }}
                                    </span>

                                    @else

                                    <span class="text-slate-400">
                                        -
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if(!$pdpcPostResponse)

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                        Pending
                                    </span>

                                    @elseif($pdpcPostResponse->status === 'Draft')

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        Draft
                                    </span>

                                    @else

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                        Completed
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if(!$pdpcPostResponse)

                                    <a href="{{ route('observer.pdpc.create', $guruNew->gn_id) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition">
                                        Add
                                    </a>

                                    @elseif($pdpcPostResponse->status === 'Draft')

                                    <a href="{{ route('observer.pdpc.edit', $pdpcPostResponse->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-yellow-200 hover:bg-yellow-300 text-yellow-700 rounded-xl font-semibold transition">
                                        Edit
                                    </a>

                                    @else

                                    <a href="{{ route('observer.pdpc.view', $pdpcPostResponse->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-sky-200 hover:bg-sky-300 text-sky-700 rounded-xl font-semibold transition">
                                        View
                                    </a>

                                    @endif

                                </td>

                            </tr>


                            {{-- POST Feedback --}}
                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-6">

                                    <p class="font-bold text-slate-900">
                                        {{ $postForm?->form_name ?? 'Feedback Observation Form' }}
                                    </p>

                                </td>

                                <td class="px-6 py-6 text-center text-slate-600">

                                    @if($feedbackResponse?->observation_date)

                                    {{ \Carbon\Carbon::parse($feedbackResponse->observation_date)->format('d/m/Y') }}

                                    @else

                                    -

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center text-slate-400">
                                    -
                                </td>

                                <td class="px-6 py-6 text-center text-slate-400">
                                    -
                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if(!$feedbackResponse)

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                        Pending
                                    </span>

                                    @elseif($feedbackResponse->status === 'Draft')

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        Draft
                                    </span>

                                    @else

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                        Completed
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if(!$feedbackResponse)

                                    <a href="{{ route('observer.post.create', $guruNew->gn_id) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition">
                                        Add
                                    </a>

                                    @elseif($feedbackResponse->status === 'Draft')

                                    <a href="{{ route('observer.post.edit', $feedbackResponse->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-yellow-200 hover:bg-yellow-300 text-yellow-700 rounded-xl font-semibold transition">
                                        Edit
                                    </a>

                                    @else

                                    <a href="{{ route('observer.post.view', $feedbackResponse->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-sky-200 hover:bg-sky-300 text-sky-700 rounded-xl font-semibold transition">
                                        View
                                    </a>

                                    @endif

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

            @endif


            {{-- External Observer --}}
            @if($isExternal && !$isObserver)

            {{-- Current EXTERNAL Stage --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">

                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">

                    <div>

                        <h2 class="text-xl font-bold text-slate-900">
                            Stage: External-Observation
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Current external evaluation
                        </p>

                    </div>

                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-violet-100 text-violet-700 text-xs font-bold">
                        Attempt {{ $externalAttemptNo ?? 1 }}
                    </span>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr class="bg-slate-50 text-black text-xs uppercase tracking-wider">

                                <th class="px-6 py-4 text-left font-bold">
                                    Form
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

                            {{-- External PDPC --}}
                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-6">

                                    <p class="font-bold text-slate-900">
                                        {{ $pdpcForm?->form_name ?? 'PDPC Observation Form' }}
                                    </p>

                                </td>

                                <td class="px-6 py-6 text-center text-slate-600">

                                    @if($latestExternalResponse?->observation_date)

                                    {{ \Carbon\Carbon::parse($latestExternalResponse->observation_date)->format('d/m/Y') }}

                                    @else

                                    -

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center font-semibold text-slate-800">

                                    @if($latestExternalResponse && $latestExternalResponse->percentage !== null)

                                    {{ number_format($latestExternalResponse->percentage, 2) }}%

                                    @else

                                    -

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if($latestExternalResponse?->achievement_level)

                                    <span class="inline-flex px-3 py-1 rounded-full bg-violet-100 text-violet-700 text-xs font-bold">
                                        {{ $latestExternalResponse->achievement_level }}
                                    </span>

                                    @else

                                    <span class="text-slate-400">
                                        -
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if(!$latestExternalResponse)

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                        Pending
                                    </span>

                                    @elseif($latestExternalResponse->status === 'Draft')

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        Draft
                                    </span>

                                    @elseif($latestExternalResponse->result === 'REPEAT')

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                        Repeat
                                    </span>

                                    @else

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                        Completed
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if(!$latestExternalResponse)

                                    <a href="{{ route('external.pdpc.create', $guruNew->gn_id) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition">
                                        Add
                                    </a>

                                    @elseif($latestExternalResponse->status === 'Draft')

                                    <a href="{{ route('external.pdpc.edit', $latestExternalResponse->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-yellow-200 hover:bg-yellow-300 text-yellow-700 rounded-xl font-semibold transition">
                                        Edit
                                    </a>

                                    @elseif($latestExternalResponse->result === 'REPEAT')

                                    <div class="flex items-center justify-center gap-2">

                                        <a href="{{ route('external.pdpc.view', $latestExternalResponse->responseID) }}"
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-sky-200 hover:bg-sky-300 text-sky-700 rounded-xl font-semibold transition">
                                            View
                                        </a>

                                        @if($feedbackResponse && $feedbackResponse->status === 'Submitted')

                                        <a href="{{ route('external.pdpc.create', $guruNew->gn_id) }}"
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-violet-600 hover:bg-violet-700 text-white rounded-xl font-semibold transition">
                                            Add New
                                        </a>

                                        @endif

                                    </div>

                                    @else

                                    <a href="{{ route('external.pdpc.view', $latestExternalResponse->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-sky-200 hover:bg-sky-300 text-sky-700 rounded-xl font-semibold transition">
                                        View
                                    </a>

                                    @endif

                                </td>

                            </tr>


                            {{-- External Feedback --}}
                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-6">

                                    <p class="font-bold text-slate-900">
                                        {{ $postForm?->form_name ?? 'Feedback Observation Form' }}
                                    </p>

                                </td>

                                <td class="px-6 py-6 text-center text-slate-600">

                                    @if($feedbackResponse?->observation_date)

                                    {{ \Carbon\Carbon::parse($feedbackResponse->observation_date)->format('d/m/Y') }}

                                    @else

                                    -

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center text-slate-400">
                                    -
                                </td>

                                <td class="px-6 py-6 text-center text-slate-400">
                                    -
                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if(!$feedbackResponse)

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                        Pending
                                    </span>

                                    @elseif($feedbackResponse->status === 'Draft')

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        Draft
                                    </span>

                                    @else

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                        Completed
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if(!$feedbackResponse)

                                    <a href="{{ route('external.post.create', $guruNew->gn_id) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition">
                                        Add
                                    </a>

                                    @elseif($feedbackResponse->status === 'Draft')

                                    <a href="{{ route('external.post.edit', $feedbackResponse->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-yellow-200 hover:bg-yellow-300 text-yellow-700 rounded-xl font-semibold transition">
                                        Edit
                                    </a>

                                    @else

                                    <a href="{{ route('external.post.view', $feedbackResponse->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-sky-200 hover:bg-sky-300 text-sky-700 rounded-xl font-semibold transition">
                                        View
                                    </a>

                                    @endif

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- External Evaluation History --}}
            @if($externalHistory->isNotEmpty())

            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">

                <div class="px-8 py-6 border-b border-slate-100">

                    <h2 class="text-xl font-bold text-slate-900">
                        Evaluation History
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Previous submitted external evaluation attempts
                    </p>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr class="bg-slate-50 text-black text-xs uppercase tracking-wider">

                                <th class="px-6 py-4 text-center font-bold">
                                    Attempt
                                </th>

                                <th class="px-6 py-4 text-center font-bold">
                                    Date
                                </th>

                                <th class="px-6 py-4 text-left font-bold">
                                    Evaluator
                                </th>

                                <th class="px-6 py-4 text-center font-bold">
                                    Score
                                </th>

                                <th class="px-6 py-4 text-center font-bold">
                                    Achievement Level
                                </th>

                                <th class="px-6 py-4 text-center font-bold">
                                    Result
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

                                <td class="px-6 py-6 text-center text-slate-600">

                                    {{ $history->observation_date
                                                    ? \Carbon\Carbon::parse($history->observation_date)->format('d/m/Y')
                                                    : '-' }}

                                </td>

                                <td class="px-6 py-6">

                                    <p class="font-semibold text-slate-800">
                                        {{ $history->evaluator_name ?? '-' }}
                                    </p>

                                </td>

                                <td class="px-6 py-6 text-center font-semibold text-slate-800">

                                    {{ $history->percentage !== null
                                                    ? number_format($history->percentage, 2).'%'
                                                    : '-' }}

                                </td>

                                <td class="px-6 py-6 text-center">

                                    @if($history->achievement_level)

                                    <span class="inline-flex px-3 py-1 rounded-full bg-violet-100 text-violet-700 text-xs font-bold">
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

                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                        Repeat
                                    </span>

                                    @elseif($history->result === 'PASS')

                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                        Pass
                                    </span>

                                    @else

                                    <span class="text-slate-400">
                                        -
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-6 text-center">

                                    <a href="{{ route('external.pdpc.view', $history->responseID) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-sky-200 hover:bg-sky-300 text-sky-700 rounded-xl font-semibold transition">
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

            @endif


            {{-- Back --}}
            <div class="flex justify-center mt-10">

                <a href="{{ $isObserver
                    ? route('observer.list.evaluate')
                    : route('external.list.evaluate') }}"
                    class="px-6 py-3 bg-slate-500 hover:bg-slate-600 text-white font-semibold rounded-xl transition">

                    Back

                </a>

            </div>

        </div>

    </div>

</x-app-layout>