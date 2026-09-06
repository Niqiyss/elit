<x-app-layout>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">

            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <p class="text-xs uppercase tracking-[0.2em] font-bold text-violet-300">
                        Observer Dashboard
                    </p>

                    <h1 class="text-3xl font-extrabold text-white mt-2">
                        Welcome, {{ Auth::guard('teacher')->user()->teacher_name }}
                    </h1>

                </div>

            </div>


            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">

                    <p class="text-sm font-semibold text-gray-900">
                        Assigned Teachers
                    </p>

                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $totalAssigned }}
                    </p>

                </div>


                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">

                    <p class="text-sm font-semibold text-gray-900">
                        Ongoing Observation
                    </p>

                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $ongoingCount }}
                    </p>

                </div>


                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">

                    <p class="text-sm font-semibold text-gray-900">
                        Draft Observation
                    </p>

                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $draftCount }}
                    </p>

                </div>


                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">

                    <p class="text-sm font-semibold text-gray-900">
                        Completed Observation
                    </p>

                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $completedCount }}
                    </p>

                </div>

            </div>

            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between gap-4">

                    <div>

                        <h2 class="text-xl font-bold text-gray-800">
                            Recent Observation
                        </h2>

                        <p class="text-sm text-gray-400 mt-1">
                            Latest assigned observation progress
                        </p>

                    </div>


                    <a
                        href="{{ route('observer.list.evaluate') }}"
                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">

                        View All

                    </a>

                </div>


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
                                    Status
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse($recentEvaluations as $assignment)

                            <tr class="hover:bg-violet-50/50 transition">


                                <td class="px-6 py-5">

                                    <p class="font-bold text-gray-800 uppercase">
                                        {{ $assignment->gn_name }}
                                    </p>

                                </td>


                                <td class="px-6 py-5 text-gray-700">
                                    {{ $assignment->school_name ?? '-' }}
                                </td>


                                <td class="px-6 py-5">

                                    <div class="flex items-center justify-center gap-5">


                                        <div class="flex flex-col items-center gap-1">

                                            <span class="w-3 h-3 rounded-full {{ $assignment->pre_status === 'Completed' ? 'bg-emerald-500' : ($assignment->pre_status === 'Draft' ? 'bg-amber-400' : 'bg-slate-300') }}"></span>

                                            <span class="text-[10px] font-semibold text-gray-600">
                                                PRE
                                            </span>

                                        </div>


                                        <div class="flex flex-col items-center gap-1">

                                            <span class="w-3 h-3 rounded-full {{ $assignment->pdpc_status === 'Completed' ? 'bg-emerald-500' : ($assignment->pdpc_status === 'Draft' ? 'bg-amber-400' : 'bg-slate-300') }}"></span>

                                            <span class="text-[10px] font-semibold text-gray-700">
                                                PDPC
                                            </span>

                                        </div>


                                        <div class="flex flex-col items-center gap-1">

                                            <span class="w-3 h-3 rounded-full {{ $assignment->feedback_status === 'Completed' ? 'bg-emerald-500' : ($assignment->feedback_status === 'Draft' ? 'bg-amber-400' : 'bg-slate-300') }}"></span>

                                            <span class="text-[10px] font-semibold text-gray-700">
                                                FEEDBACK
                                            </span>

                                        </div>

                                    </div>

                                </td>


                                <td class="px-6 py-5">

                                    <div class="max-w-[150px] mx-auto">

                                        <div class="flex justify-between text-xs text-gray-600 mb-2">

                                            <span>
                                                {{ $assignment->completed_count }}/3
                                            </span>

                                            <span>
                                                {{ $assignment->progress }}%
                                            </span>

                                        </div>


                                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">

                                            <div
                                                class="h-full bg-blue-600 rounded-full"
                                                style="width: <?= $assignment->progress ?? 0 ?>%;">
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                <td class="px-6 py-5 text-center">

                                    @if($assignment->is_completed)

                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">

                                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                        Completed
                                    </span>


                                    @elseif($assignment->has_draft)

                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">

                                        <span class="w-2 h-2 bg-amber-400 rounded-full"></span>
                                        Draft
                                    </span>


                                    @else

                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">

                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        Ongoing
                                    </span>

                                    @endif

                                </td>


                                <td class="px-6 py-5 text-center">

                                    <a
                                        href="{{ route('observer.manage', $assignment->gn_id) }}"
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
                                        No evaluations are currently available
                                    </p>

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mt-8">

                <div class="px-8 py-6 border-b border-gray-100">

                    <h2 class="text-xl font-bold text-gray-800">
                        Pre Observation Results
                    </h2>

                </div>


                <div class="px-8 py-6 border-b border-gray-100">

                    <form method="GET" action="{{ route('observer.dashboard') }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-12 gap-4 items-end">

                            <div class="xl:col-span-4">

                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">
                                    Teacher
                                </label>

                                <input
                                    type="text"
                                    name="pre_search"
                                    value="{{ $preSearch }}"
                                    placeholder="Search teacher..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500">

                            </div>


                            <div class="xl:col-span-2">

                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">
                                    Month
                                </label>

                                <select
                                    name="pre_month"
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
                                        {{ (string) $preMonth === (string) $monthNumber ? 'selected' : '' }}>

                                        {{ $monthName }}

                                    </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="xl:col-span-2">

                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">
                                    Year
                                </label>

                                <select
                                    name="pre_year"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500">

                                    <option value="all">
                                        All Years
                                    </option>

                                    @foreach($preYears as $availableYear)

                                    <option
                                        value="{{ $availableYear }}"
                                        {{ (string) $preYear === (string) $availableYear ? 'selected' : '' }}>

                                        {{ $availableYear }}

                                    </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="xl:col-span-2">

                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">
                                    Level
                                </label>

                                <select
                                    name="pre_level"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500">

                                    <option value="all" {{ $preLevel === 'all' ? 'selected' : '' }}>
                                        All Levels
                                    </option>

                                    <option value="Weak" {{ $preLevel === 'Weak' ? 'selected' : '' }}>
                                        Weak
                                    </option>

                                    <option value="Satisfactory" {{ $preLevel === 'Satisfactory' ? 'selected' : '' }}>
                                        Satisfactory
                                    </option>

                                    <option value="Good" {{ $preLevel === 'Good' ? 'selected' : '' }}>
                                        Good
                                    </option>

                                    <option value="Very Good" {{ $preLevel === 'Very Good' ? 'selected' : '' }}>
                                        Very Good
                                    </option>

                                    <option value="Excellent" {{ $preLevel === 'Excellent' ? 'selected' : '' }}>
                                        Excellent
                                    </option>

                                </select>

                            </div>


                            <div class="xl:col-span-2 flex gap-2">

                                <button
                                    type="submit"
                                    class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition">
                                    Filter
                                </button>

                                <a
                                    href="{{ route('observer.dashboard') }}"
                                    class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">

                                    Reset

                                </a>

                            </div>

                        </div>

                    </form>

                </div>


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

                            @forelse($preResults as $result)

                            <tr class="hover:bg-violet-50/50 transition">


                                <td class="px-6 py-5 text-center text-gray-500">
                                    {{ $preResults->firstItem() + $loop->index }}
                                </td>


                                <td class="px-6 py-5 text-center text-gray-600">

                                    {{ $result->observation_date
                            ? \Carbon\Carbon::parse($result->observation_date)->format('d/m/Y')
                            : '-' }}

                                </td>


                                <td class="px-6 py-5">

                                    <p class="font-bold text-gray-800 uppercase">
                                        {{ $result->gn_name }}
                                    </p>

                                </td>


                                <td class="px-6 py-5 text-center text-gray-600">
                                    {{ $result->class_name ?? '-' }}
                                </td>


                                <td class="px-6 py-5 text-gray-600">
                                    {{ $result->subject_name ?? '-' }}
                                </td>


                                <td class="px-6 py-5 text-center">

                                    <span class="font-bold text-gray-800">
                                        {{ $result->percentage !== null ? number_format($result->percentage, 2) . '%' : '-' }}
                                    </span>

                                </td>


                                <td class="px-6 py-5 text-center">

                                    @if($result->achievement_level === 'Excellent')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">
                                        Excellent
                                    </span>


                                    @elseif($result->achievement_level === 'Very Good')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-semibold">
                                        Very Good
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

                                    <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                        Weak
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
                                        No PRE observation results found
                                    </p>

                                    <p class="text-sm text-gray-400 mt-1">
                                        Submitted PRE observations will appear here
                                    </p>

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                @if($preResults->hasPages())

                <div class="px-8 py-5 border-t border-gray-100">
                    {{ $preResults->links() }}
                </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>