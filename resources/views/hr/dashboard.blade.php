<x-app-layout>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">

            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900
                        rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10
                            w-72 h-72 bg-purple-500/10 rounded-full blur-3xl">
                </div>


                <div class="relative z-10
                            flex flex-col md:flex-row
                            md:items-center md:justify-between gap-6">


                    <div>

                        <p class="text-violet-300 text-sm font-semibold mb-2">
                            Welcome back,
                        </p>

                        <h1 class="text-3xl font-extrabold text-white">
                            {{ Auth::guard('hr')->user()->hrname }}
                        </h1>

                    </div>

                    <div class="flex flex-wrap gap-3">

                        <a
                            href="{{ route('hr.gurunew.create') }}"
                            class="px-5 py-3 bg-white text-violet-900
                                   rounded-xl font-semibold shadow
                                   hover:bg-violet-50 transition">

                            Register New Teacher

                        </a>

                        <a
                            href="{{ route('hr.gurunew.index') }}"
                            class="px-5 py-3 bg-violet-700/60
                                   border border-violet-400/30
                                   text-white rounded-xl font-semibold
                                   hover:bg-violet-700 transition">

                            Manage Teachers

                        </a>

                    </div>

                </div>

            </div>

            {{-- STATISTIC CARDS --}}

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                {{-- TOTAL --}}
                <div class="bg-white rounded-3xl shadow-lg p-6
                            border border-gray-100">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-semibold text-gray-400">
                                Total Teachers
                            </p>

                            <p class="text-3xl font-extrabold text-gray-800 mt-2">
                                {{ $totalTeachers }}
                            </p>

                        </div>


                        <div class="w-14 h-14 rounded-2xl
                                    bg-violet-100
                                    flex items-center justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7 text-violet-700"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857
                                       M17 20H7
                                       m10 0v-2c0-.656-.126-1.283-.356-1.857
                                       M7 20H2v-2a3 3 0 015.356-1.857
                                       M7 20v-2c0-.656.126-1.283.356-1.857
                                       m0 0a5.002 5.002 0 019.288 0
                                       M15 7a3 3 0 11-6 0
                                       3 3 0 016 0z" />

                            </svg>

                        </div>

                    </div>

                </div>


                {{-- ACTIVE --}}
                <div class="bg-white rounded-3xl shadow-lg p-6
                            border border-gray-100">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-semibold text-gray-400">
                                Active
                            </p>

                            <p class="text-3xl font-extrabold text-green-600 mt-2">
                                {{ $activeTeachers }}
                            </p>

                        </div>


                        <div class="w-14 h-14 rounded-2xl
                                    bg-green-100
                                    flex items-center justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7 text-green-700"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4
                                       m6 2a9 9 0 11-18 0
                                       9 9 0 0118 0z" />

                            </svg>

                        </div>

                    </div>

                </div>


                {{-- INACTIVE --}}
                <div class="bg-white rounded-3xl shadow-lg p-6
                            border border-gray-100">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-semibold text-gray-400">
                                Inactive
                            </p>

                            <p class="text-3xl font-extrabold text-red-600 mt-2">
                                {{ $inactiveTeachers }}
                            </p>

                        </div>


                        <div class="w-14 h-14 rounded-2xl
                                    bg-red-100
                                    flex items-center justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7 text-red-700"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M18.364 5.636
                                       l-12.728 12.728
                                       M5.636 5.636
                                       l12.728 12.728" />

                            </svg>

                        </div>

                    </div>

                </div>


                {{-- COMPLETE --}}
                <div class="bg-white rounded-3xl shadow-lg p-6
                            border border-gray-100">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-semibold text-gray-400">
                                Complete
                            </p>

                            <p class="text-3xl font-extrabold text-blue-600 mt-2">
                                {{ $completeTeachers }}
                            </p>

                        </div>


                        <div class="w-14 h-14 rounded-2xl
                                    bg-blue-100
                                    flex items-center justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7 text-blue-700"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4
                                       m5.618-4.016
                                       A11.955 11.955 0 0112 2.944
                                       a11.955 11.955 0 01-8.618 3.04
                                       A12.02 12.02 0 003 9
                                       c0 5.591 3.824 10.29 9 11.622
                                       5.176-1.332 9-6.03 9-11.622
                                       0-1.042-.133-2.052-.382-3.016z" />

                            </svg>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- SEARCH & FILTER --}}
            {{-- ========================================================= --}}

            <div class="bg-white rounded-3xl shadow-lg p-7 mb-8">

                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    Search & Filter
                </h2>


                <form
                    method="GET"
                    action="{{ route('hr.dashboard') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-4 items-end">


                    {{-- SEARCH --}}
                    <div class="lg:col-span-4">

                        <label
                            for="search"
                            class="block text-xs font-bold uppercase
                       tracking-wide text-gray-500 mb-2">

                            Search

                        </label>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search teacher, ID or email..."
                            class="w-full rounded-xl border-gray-300
                       focus:border-violet-500
                       focus:ring-violet-500">

                    </div>


                    {{-- SCHOOL --}}
                    <div class="lg:col-span-3">

                        <label
                            for="schoolID"
                            class="block text-xs font-bold uppercase
                       tracking-wide text-gray-500 mb-2">

                            School

                        </label>

                        <select
                            id="schoolID"
                            name="schoolID"
                            class="w-full rounded-xl border-gray-300
                       focus:border-violet-500
                       focus:ring-violet-500">

                            <option value="">
                                All Schools
                            </option>

                            @foreach($schools as $school)

                            <option
                                value="{{ $school->schoolID }}"
                                {{ request('schoolID') == $school->schoolID ? 'selected' : '' }}>

                                {{ $school->school_name }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- STATUS --}}
                    <div class="lg:col-span-2">

                        <label
                            for="status"
                            class="block text-xs font-bold uppercase
                       tracking-wide text-gray-500 mb-2">

                            Status

                        </label>

                        <select
                            id="status"
                            name="status"
                            class="w-full rounded-xl border-gray-300
                       focus:border-violet-500
                       focus:ring-violet-500">

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="Active"
                                {{ request('status') === 'Active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option
                                value="Inactive"
                                {{ request('status') === 'Inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                            <option
                                value="Complete"
                                {{ request('status') === 'Complete' ? 'selected' : '' }}>
                                Complete
                            </option>

                        </select>

                    </div>


                    {{-- DATE --}}
                    <div class="lg:col-span-2">

                        <label
                            for="appointed_date"
                            class="block text-xs font-bold uppercase
                       tracking-wide text-gray-500 mb-2">

                            Appointed Date

                        </label>

                        <input
                            type="date"
                            id="appointed_date"
                            name="appointed_date"
                            value="{{ request('appointed_date') }}"
                            class="w-full rounded-xl border-gray-300
                       focus:border-violet-500
                       focus:ring-violet-500">

                    </div>


                    {{-- FILTER BUTTON --}}
                    <div class="lg:col-span-1">

                        <button
                            type="submit"
                            class="w-full px-5 py-2.5
                       bg-blue-600 hover:bg-blue-700
                       text-white font-semibold
                       rounded-xl shadow transition">

                            Filter

                        </button>

                    </div>


                    {{-- RESET --}}
                    @if(
                    request('search') ||
                    request('schoolID') ||
                    request('status') ||
                    request('appointed_date')
                    )

                    <div class="lg:col-span-12">

                        <a
                            href="{{ route('hr.dashboard') }}"
                            class="text-sm font-semibold
                           text-red-500
                           hover:text-red-600 transition">

                            Clear Filters

                        </a>

                    </div>

                    @endif

                </form>

            </div>

            {{-- RECENT NEW TEACHERS --}}

            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                <div class="px-8 py-6 border-b border-gray-100
                            flex items-center justify-between">

                    <div>

                        <h2 class="text-xl font-bold text-gray-800">
                            Recent New Teachers
                        </h2>

                        <p class="text-sm text-gray-400 mt-1">
                            Latest registered teacher accounts
                        </p>

                    </div>


                    <a
                        href="{{ route('hr.gurunew.index') }}"
                        class="text-sm font-semibold text-blue-700
                               hover:text-blue-900 transition">

                        View All →

                    </a>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">


                        <thead class="bg-slate-50 text-gray-500 uppercase text-xs">

                            <tr>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Teacher
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    School
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Appointed Date
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Status
                                </th>

                            </tr>

                        </thead>



                        <tbody class="divide-y divide-gray-100">

                            @forelse($recentTeachers as $guru)

                            <tr class="hover:bg-violet-50/50 transition">

                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-4">

                                        <div class="w-11 h-11 rounded-full
                                                    bg-blue-100
                                                    flex items-center justify-center
                                                    border-2 border-blue-500">

                                            <span class="text-blue-700 font-bold text-sm">

                                                {{ strtoupper(substr($guru->gn_name, 0, 1)) }}

                                            </span>

                                        </div>


                                        <div>

                                            <p class="font-bold text-gray-800 uppercase">
                                                {{ $guru->gn_name }}
                                            </p>

                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ $guru->gn_id }}
                                            </p>

                                        </div>

                                    </div>

                                </td>

                                <td class="px-6 py-5 text-gray-600">

                                    {{ $guru->school?->school_name ?? '-' }}

                                </td>


                                <td class="px-6 py-5 text-gray-600">

                                    @if($guru->appointed_date)

                                    {{ \Carbon\Carbon::parse($guru->appointed_date)->format('d M Y') }}

                                    @else

                                    -

                                    @endif

                                </td>

                                <td class="px-6 py-5">

                                    @if($guru->current_status === 'Active')

                                    <span class="inline-flex items-center
                                                     px-3 py-1 rounded-full
                                                     text-xs font-semibold
                                                     bg-green-100 text-green-700">

                                        Active

                                    </span>


                                    @elseif($guru->current_status === 'Complete')

                                    <span class="inline-flex items-center
                                                     px-3 py-1 rounded-full
                                                     text-xs font-semibold
                                                     bg-blue-100 text-blue-700">

                                        Complete

                                    </span>


                                    @else

                                    <span class="inline-flex items-center
                                                     px-3 py-1 rounded-full
                                                     text-xs font-semibold
                                                     bg-red-100 text-red-700">

                                        Inactive

                                    </span>

                                    @endif

                                </td>

                            </tr>


                            @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center py-12 text-gray-400">

                                    No new teachers found

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