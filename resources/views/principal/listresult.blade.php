<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('principal')->user()->principal_name }}
        </h2>
    </x-slot>


    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">


            {{-- Header --}}
            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex justify-between items-center">

                    <div>

                        <h1 class="text-3xl font-extrabold text-white">
                            Evaluation Results
                        </h1>

                        <p class="text-violet-300 mt-2">
                            Monitor the evaluation progress of new teacher
                        </p>

                    </div>


                    {{-- Active Teachers --}}
                    <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-2xl px-6 py-4 min-w-[150px]">

                        <p class="text-sm text-white">
                            Active Teachers
                        </p>

                        <p class="text-3xl font-bold text-white mt-1">
                            {{ $totalTeachers }}
                        </p>

                    </div>

                </div>

            </div>



            {{-- Result List --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">


                {{-- Search & Filter --}}
                <div class="px-8 py-6 border-b border-gray-100">

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                        <div>

                            <h2 class="text-xl font-bold text-gray-800">
                                Teacher Evaluation Overview
                            </h2>

                        </div>


                        <form
                            method="GET"
                            action="{{ route('principal.result') }}"
                            class="flex flex-col sm:flex-row items-center gap-3">


                            {{-- Search --}}
                            <div class="relative w-full sm:w-auto">

                                <svg
                                    class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />

                                </svg>


                                <input
                                    type="text"
                                    name="search"
                                    value="{{ $search }}"
                                    placeholder="Search teacher..."
                                    class="w-full sm:w-64 pl-10 pr-4 py-2.5 rounded-xl border-gray-300 text-sm focus:border-purple-500 focus:ring-purple-500">

                            </div>



                            {{-- Status --}}
                            <select
                                name="status"
                                onchange="this.form.submit()"
                                class="w-full sm:w-auto min-w-[170px] rounded-xl border-gray-300 px-4 py-2.5 text-sm text-gray-700 focus:border-purple-500 focus:ring-purple-500">

                                <option
                                    value="all"
                                    {{ $status === 'all' ? 'selected' : '' }}>
                                    All Status
                                </option>

                                <option
                                    value="Pending"
                                    {{ $status === 'Pending' ? 'selected' : '' }}>
                                    Pending
                                </option>

                                <option
                                    value="In Progress"
                                    {{ $status === 'In Progress' ? 'selected' : '' }}>
                                    In Progress
                                </option>

                                <option
                                    value="Completed"
                                    {{ $status === 'Completed' ? 'selected' : '' }}>
                                    Completed
                                </option>

                                <option
                                    value="Repeat Required"
                                    {{ $status === 'Repeat Required' ? 'selected' : '' }}>
                                    Repeat Required
                                </option>

                            </select>



                            {{-- Search Button --}}
                            <button
                                type="submit"
                                class="w-full sm:w-auto px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl shadow-sm transition">

                                Search

                            </button>


                            {{-- Clear --}}
                            @if(request('search') || (request('status') && request('status') !== 'all'))

                            <a
                                href="{{ route('principal.result') }}"
                                class="w-full sm:w-auto text-center px-4 py-2 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 text-sm font-semibold transition">

                                Clear

                            </a>

                            @endif

                        </form>

                    </div>

                </div>



                {{-- Table --}}
                <div class="overflow-x-auto">

                    <table class="w-full text-sm">


                        <thead class="bg-slate-50 text-gray-900 uppercase text-xs">

                            <tr>

                                <th class="px-6 py-4 text-left font-semibold">
                                    No
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Teacher Name
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Pre-Observation
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    External Observation
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Post-Observation
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Overall Status
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Action
                                </th>

                            </tr>

                        </thead>



                        <tbody class="divide-y divide-gray-100">

                            @forelse($teachers as $teacher)

                            <tr class="hover:bg-violet-50/50 transition">


                                {{-- Number --}}
                                <td class="px-6 py-5 text-gray-600">

                                    {{ $teachers->firstItem() + $loop->index }}

                                </td>



                                {{-- Teacher --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-3">

                                        <div class="w-10 h-10 rounded-full border-2 border-blue-500 flex items-center justify-center text-blue-700 font-bold shrink-0">

                                            {{ strtoupper(substr($teacher->gn_name, 0, 1)) }}

                                        </div>

                                        <p class="font-bold text-gray-800">

                                            {{ $teacher->gn_name }}

                                        </p>

                                    </div>

                                </td>



                                {{-- PRE --}}
                                <td class="px-6 py-5">

                                    <div class="w-28">

                                        <div class="flex items-center justify-between mb-2">

                                            <span class="text-sm font-semibold text-gray-700">

                                                {{ $teacher->pre_completed }}/{{ $teacher->pre_total }}

                                            </span>

                                            <span class="text-xs font-medium text-gray-500">

                                                {{ $teacher->pre_progress }}%

                                            </span>

                                        </div>


                                        {{-- PRE --}}
                                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div
                                                class="h-full bg-blue-600 rounded-full"
                                                style="width: <?= $teacher->pre_progress ?? 0 ?>%;">
                                            </div>
                                        </div>

                                    </div>

                                </td>



                                {{-- EXTERNAL --}}
                                <td class="px-6 py-5">

                                    <div class="w-28">

                                        <div class="flex items-center justify-between mb-2">

                                            <span class="text-sm font-semibold text-gray-700">

                                                {{ $teacher->external_completed }}/{{ $teacher->external_total }}

                                            </span>

                                            <span class="text-xs font-medium text-gray-500">

                                                {{ $teacher->external_progress }}%

                                            </span>

                                        </div>


                                        {{-- EXTERNAL --}}
                                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div
                                                class="h-full bg-blue-600 rounded-full"
                                                style="width: <?= $teacher->external_progress ?? 0 ?>%;">
                                            </div>
                                        </div>

                                    </div>

                                </td>



                                {{-- POST --}}
                                <td class="px-6 py-5">

                                    <div class="w-28">

                                        <div class="flex items-center justify-between mb-2">

                                            <span class="text-sm font-semibold text-gray-700">

                                                {{ $teacher->post_completed }}/{{ $teacher->post_total }}

                                            </span>

                                            <span class="text-xs font-medium text-gray-500">

                                                {{ $teacher->post_progress }}%

                                            </span>

                                        </div>


                                        {{-- POST --}}
                                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div
                                                class="h-full bg-blue-600 rounded-full"
                                                style="width: <?= $teacher->post_progress ?? 0 ?>%;">
                                            </div>
                                        </div>

                                    </div>

                                </td>



                                {{-- Overall Status --}}
                                <td class="px-6 py-5 text-center">

                                    @if($teacher->evaluation_status === 'Completed')

                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        Completed
                                    </span>


                                    @elseif($teacher->evaluation_status === 'Repeat Required')

                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        Repeat Required
                                    </span>


                                    @elseif($teacher->evaluation_status === 'In Progress')

                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        In Progress
                                    </span>


                                    @else

                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                        Pending
                                    </span>

                                    @endif

                                </td>



                                {{-- Action --}}
                                <td class="px-6 py-5 text-center">

                                    <a
                                        href="{{ route('principal.result.show', $teacher->gn_id) }}"
                                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-lg shadow-sm transition">

                                        View

                                    </a>

                                </td>

                            </tr>


                            @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-12 text-gray-500">

                                    No evaluation results found

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>



                {{-- Pagination --}}
                @if($teachers->hasPages())

                <div class="px-8 py-6 border-t border-gray-100">

                    {{ $teachers->links() }}

                </div>

                @endif


            </div>

        </div>

    </div>

</x-app-layout>