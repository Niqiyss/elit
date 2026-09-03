<x-app-layout>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">


            {{-- HEADER --}}
            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-violet-300 mb-3">
                        External Observer Dashboard
                    </p>

                    <h1 class="text-3xl font-extrabold text-white">
                        Welcome, {{ Auth::guard('teacher')->user()->teacher_name }}
                    </h1>

                </div>

            </div>


            {{-- SUMMARY CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

                {{-- ASSIGNED --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">

                    <p class="text-sm font-semibold text-gray-900">
                        Assigned Teachers
                    </p>

                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $totalAssigned }}
                    </p>

                </div>


                {{-- ONGOING --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">

                    <p class="text-sm font-semibold text-gray-900">
                        Ongoing Observation
                    </p>

                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $ongoingCount }}
                    </p>

                </div>


                {{-- COMPLETED --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">

                    <p class="text-sm font-semibold text-gray-900">
                        Completed Observation
                    </p>

                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $completedCount }}
                    </p>

                </div>


                {{-- REPEAT --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">

                    <p class="text-sm font-semibold text-gray-900">
                        Repeat Required
                    </p>

                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $repeatCount }}
                    </p>

                </div>

            </div>


            {{-- RECENT EVALUATIONS --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">

                <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between gap-4">

                    <div>

                        <h2 class="text-xl font-bold text-gray-800">
                            Recent Observation
                        </h2>

                        <p class="text-sm text-gray-400 mt-1">
                            Latest external observation progress
                        </p>

                    </div>


                    <a
                        href="{{ route('external.list.evaluate') }}"
                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">

                        View All

                    </a>

                </div>


                {{-- TABLE --}}
                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-slate-50 text-gray-900 uppercase text-xs">

                            <tr>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Teacher
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    School
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Completion
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Progress
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Result
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse($recentEvaluations as $assignment)

                            <tr class="hover:bg-violet-50/50 transition">


                                {{-- TEACHER --}}
                                <td class="px-6 py-5">

                                    <p class="font-bold text-gray-800 uppercase">
                                        {{ $assignment->gn_name }}
                                    </p>

                                </td>


                                {{-- SCHOOL --}}
                                <td class="px-6 py-5 text-gray-600">
                                    {{ $assignment->school_name ?? '-' }}
                                </td>


                                {{-- COMPLETION --}}
                                <td class="px-6 py-5">

                                    <div class="flex justify-center gap-6">


                                        {{-- PDPC --}}
                                        <div class="text-center">

                                            <span class="block mx-auto w-3 h-3 rounded-full {{ $assignment->pdpc_status === 'Completed' ? 'bg-emerald-500' : ($assignment->pdpc_status === 'Draft' ? 'bg-amber-400' : 'bg-slate-300') }}"></span>

                                            <span class="block text-[10px] text-gray-600 mt-1">
                                                PDPC
                                            </span>

                                        </div>


                                        {{-- FEEDBACK --}}
                                        <div class="text-center">

                                            <span class="block mx-auto w-3 h-3 rounded-full {{ $assignment->feedback_status === 'Completed' ? 'bg-emerald-500' : ($assignment->feedback_status === 'Draft' ? 'bg-amber-400' : 'bg-slate-300') }}"></span>

                                            <span class="block text-[10px] text-gray-600 mt-1">
                                                FEEDBACK
                                            </span>

                                        </div>

                                    </div>

                                </td>


                                {{-- PROGRESS --}}
                                <td class="px-6 py-5">

                                    <div class="w-28 mx-auto">

                                        <div class="flex justify-between text-xs text-gray-600 mb-2">

                                            <span>
                                                {{ $assignment->completed_count }}/{{ $assignment->total_forms }}
                                            </span>

                                            <span>
                                                {{ $assignment->progress }}%
                                            </span>

                                        </div>


                                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">

                                            @if($assignment->progress >= 100)

                                            @if($assignment->is_repeat)

                                            <div class="h-full w-full rounded-full bg-red-500"></div>

                                            @else

                                            <div class="h-full w-full rounded-full bg-blue-600"></div>

                                            @endif


                                            @elseif($assignment->progress >= 50)

                                            @if($assignment->is_repeat)

                                            <div class="h-full w-1/2 rounded-full bg-red-500"></div>

                                            @else

                                            <div class="h-full w-1/2 rounded-full bg-blue-600"></div>

                                            @endif

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- RESULT --}}
                                <td class="px-6 py-5 text-center">

                                    @if($assignment->result === 'REPEAT')

                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">

                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>

                                        REPEAT

                                    </span>


                                    @elseif($assignment->result === 'PASS')

                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">

                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>

                                        PASS

                                    </span>


                                    @else

                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold">

                                        <span class="w-2 h-2 rounded-full bg-gray-400"></span>

                                        PENDING

                                    </span>

                                    @endif

                                </td>


                                {{-- ACTION --}}
                                <td class="px-6 py-5 text-center">

                                    <a
                                        href="{{ route('external.manage', $assignment->gn_id) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">

                                        Manage

                                    </a>

                                </td>

                            </tr>


                            @empty

                            <tr>

                                <td colspan="6" class="py-12 text-center">

                                    <p class="font-semibold text-gray-700">
                                        No evaluations found
                                    </p>

                                    <p class="text-sm text-gray-400 mt-1">
                                        No evaluation assignments are available
                                    </p>

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- PDPC EVALUATION RESULTS --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">


                {{-- HEADER --}}
                <div class="px-8 py-6 border-b border-gray-100">

                    <h2 class="text-xl font-bold text-gray-800">
                        PDPC Observation Results
                    </h2>

                </div>


                {{-- SEARCH & FILTER --}}
                <div class="px-8 py-6 border-b border-gray-100">

                    <form method="GET" action="{{ route('external.dashboard') }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-12 gap-4 items-end">


                            {{-- TEACHER --}}
                            <div class="xl:col-span-4">

                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">
                                    Teacher
                                </label>

                                <input
                                    type="text"
                                    name="search"
                                    value="{{ $search }}"
                                    placeholder="Search teacher..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500">

                            </div>


                            {{-- MONTH --}}
                            <div class="xl:col-span-2">

                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">
                                    Month
                                </label>

                                <select
                                    name="month"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500">

                                    <option value="all">
                                        All Months
                                    </option>

                                    @foreach([
                                    1 => 'January',
                                    2 => 'February',
                                    3 => 'March',
                                    4 => 'April',
                                    5 => 'May',
                                    6 => 'June',
                                    7 => 'July',
                                    8 => 'August',
                                    9 => 'September',
                                    10 => 'October',
                                    11 => 'November',
                                    12 => 'December'
                                    ] as $monthNumber => $monthName)

                                    <option
                                        value="{{ $monthNumber }}"
                                        {{ (string) $month === (string) $monthNumber ? 'selected' : '' }}>

                                        {{ $monthName }}

                                    </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- YEAR --}}
                            <div class="xl:col-span-2">

                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">
                                    Year
                                </label>

                                <select
                                    name="year"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500">

                                    <option value="all">
                                        All Years
                                    </option>

                                    @foreach($years as $availableYear)

                                    <option
                                        value="{{ $availableYear }}"
                                        {{ (string) $year === (string) $availableYear ? 'selected' : '' }}>

                                        {{ $availableYear }}

                                    </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- LEVEL --}}
                            <div class="xl:col-span-2">

                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">
                                    Level
                                </label>

                                <select
                                    name="level"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500">

                                    <option value="all" {{ $level === 'all' ? 'selected' : '' }}>
                                        All Levels
                                    </option>

                                    <option value="Very Weak" {{ $level === 'Very Weak' ? 'selected' : '' }}>
                                        Very Weak
                                    </option>

                                    <option value="Weak" {{ $level === 'Weak' ? 'selected' : '' }}>
                                        Weak
                                    </option>

                                    <option value="Satisfactory" {{ $level === 'Satisfactory' ? 'selected' : '' }}>
                                        Satisfactory
                                    </option>

                                    <option value="Good" {{ $level === 'Good' ? 'selected' : '' }}>
                                        Good
                                    </option>

                                    <option value="Excellent" {{ $level === 'Excellent' ? 'selected' : '' }}>
                                        Excellent
                                    </option>

                                </select>

                            </div>


                            {{-- BUTTONS --}}
                            <div class="xl:col-span-2 flex gap-2">

                                <button
                                    type="submit"
                                    class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">

                                    Filter

                                </button>

                                <a
                                    href="{{ route('external.dashboard') }}"
                                    class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold transition">

                                    Reset

                                </a>

                            </div>

                        </div>

                    </form>

                </div>


                {{-- RESULTS TABLE --}}
                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-slate-50 text-gray-900 uppercase text-xs">

                            <tr>

                                <th class="px-6 py-4 text-center font-semibold">
                                    No.
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Date
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Teacher
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Class
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Subject
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Score
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Achievement Level
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse($pdpcResults as $result)

                            <tr class="hover:bg-violet-50/50 transition">


                                {{-- NUMBER --}}
                                <td class="px-6 py-5 text-center text-gray-500">
                                    {{ $pdpcResults->firstItem() + $loop->index }}
                                </td>


                                {{-- DATE --}}
                                <td class="px-6 py-5 text-center text-gray-600">

                                    {{ $result->observation_date
                                        ? \Carbon\Carbon::parse($result->observation_date)->format('d/m/Y')
                                        : '-' }}

                                </td>


                                {{-- TEACHER --}}
                                <td class="px-6 py-5">

                                    <p class="font-bold text-gray-800 uppercase">
                                        {{ $result->gn_name }}
                                    </p>

                                    @if(($result->attempt_no ?? 1) > 1)

                                    <p class="text-xs text-blue-500 mt-1">
                                        Attempt {{ $result->attempt_no }}
                                    </p>

                                    @endif

                                </td>


                                {{-- CLASS --}}
                                <td class="px-6 py-5 text-center text-gray-600">
                                    {{ $result->class_name ?? '-' }}
                                </td>


                                {{-- SUBJECT --}}
                                <td class="px-6 py-5 text-gray-600">
                                    {{ $result->subject_name ?? '-' }}
                                </td>


                                {{-- SCORE --}}
                                <td class="px-6 py-5 text-center">

                                    <span class="font-bold text-gray-800">
                                        {{ $result->percentage !== null ? number_format($result->percentage, 2) . '%' : '-' }}
                                    </span>

                                </td>


                                {{-- LEVEL --}}
                                <td class="px-6 py-5 text-center">

                                    @if($result->achievement_level === 'Excellent')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">
                                        Excellent
                                    </span>


                                    @elseif($result->achievement_level === 'Good')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                        Good
                                    </span>


                                    @elseif($result->achievement_level === 'Satisfactory')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                        Satisfactory
                                    </span>


                                    @elseif($result->achievement_level === 'Weak')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-semibold">
                                        Weak
                                    </span>


                                    @elseif($result->achievement_level === 'Very Weak')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                        Very Weak
                                    </span>


                                    @else

                                    <span class="text-gray-400">
                                        -
                                    </span>

                                    @endif

                                </td>

                            </tr>


                            @empty

                            <tr>

                                <td colspan="7" class="px-6 py-14 text-center">

                                    <p class="font-semibold text-gray-700">
                                        No PDPC results found
                                    </p>

                                    <p class="text-sm text-gray-400 mt-1">
                                        Submitted PDPC observations will appear here
                                    </p>

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}
                @if($pdpcResults->hasPages())

                <div class="px-8 py-5 border-t border-gray-100">
                    {{ $pdpcResults->links() }}
                </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>