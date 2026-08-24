<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('admin')->user()->staffname }}
        </h2>
    </x-slot>


    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">

            {{-- Header --}}
            <div class="relative bg-gradient-to-br
                        from-slate-900 via-violet-950 to-purple-900
                        rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0
                            translate-x-10 -translate-y-10
                            w-72 h-72 bg-purple-500/10
                            rounded-full blur-3xl">
                </div>

                <div class="relative z-10">

                    <h1 class="text-3xl font-extrabold text-white">
                        Observation Audit
                    </h1>

                    <p class="text-violet-300 mt-2">
                        View submitted observation records
                    </p>

                </div>

            </div>


            {{-- Search and filter --}}
            <div class="bg-white rounded-3xl shadow-lg p-6 mb-8">

                <h2 class="text-lg font-bold text-slate-900 mb-5">
                    Search & Filter
                </h2>

                <form
                    method="GET"
                    action="{{ route('admin.audit.observation') }}"
                    class="grid grid-cols-1
                           md:grid-cols-2
                           lg:grid-cols-12
                           gap-4 items-end">

                    <div class="lg:col-span-5">

                        <label class="block text-xs font-semibold
                                      uppercase tracking-wider
                                      text-slate-500 mb-2">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search observer or teacher..."
                            class="w-full rounded-xl
                                   border-slate-300
                                   text-sm
                                   focus:border-purple-500
                                   focus:ring-purple-500">

                    </div>


                    <div class="lg:col-span-2">

                        <label class="block text-xs font-semibold
                                      uppercase tracking-wider
                                      text-slate-500 mb-2">
                            Role
                        </label>

                        <select
                            name="role"
                            class="w-full rounded-xl
                                   border-slate-300
                                   text-sm
                                   focus:border-purple-500
                                   focus:ring-purple-500">

                            <option value="">
                                All Roles
                            </option>

                            <option
                                value="Observer"
                                {{ request('role') === 'Observer' ? 'selected' : '' }}>
                                Observer
                            </option>

                            <option
                                value="External Observer"
                                {{ request('role') === 'External Observer' ? 'selected' : '' }}>
                                External Observer
                            </option>

                        </select>

                    </div>


                    <div class="lg:col-span-2">

                        <label class="block text-xs font-semibold
                                      uppercase tracking-wider
                                      text-slate-500 mb-2">
                            Stage
                        </label>

                        <select
                            name="stage"
                            class="w-full rounded-xl
                                   border-slate-300
                                   text-sm
                                   focus:border-purple-500
                                   focus:ring-purple-500">

                            <option value="">
                                All Stages
                            </option>

                            <option
                                value="PRE"
                                {{ request('stage') === 'PRE' ? 'selected' : '' }}>
                                PRE
                            </option>

                            <option
                                value="EXTERNAL"
                                {{ request('stage') === 'EXTERNAL' ? 'selected' : '' }}>
                                EXTERNAL
                            </option>

                            <option
                                value="POST"
                                {{ request('stage') === 'POST' ? 'selected' : '' }}>
                                POST
                            </option>

                        </select>

                    </div>


                    <div class="lg:col-span-2">

                        <label class="block text-xs font-semibold
                                      uppercase tracking-wider
                                      text-slate-500 mb-2">
                            Date
                        </label>

                        <input
                            type="date"
                            name="date"
                            value="{{ request('date') }}"
                            class="w-full rounded-xl
                                   border-slate-300
                                   text-sm
                                   focus:border-purple-500
                                   focus:ring-purple-500">

                    </div>


                    <div class="lg:col-span-1">

                        <button
                            type="submit"
                            class="w-full px-5 py-2.5
                                   bg-purple-600
                                   hover:bg-purple-700
                                   text-white
                                   text-sm font-semibold
                                   rounded-xl
                                   transition">

                            Filter

                        </button>

                    </div>


                    @if(
                    request('search') ||
                    request('role') ||
                    request('stage') ||
                    request('date')
                    )

                    <div class="lg:col-span-12 flex justify-end">

                        <a
                            href="{{ route('admin.audit.observation') }}"
                            class="text-sm font-semibold
           text-red-600
           hover:text-red-700">

                            Clear Filters

                        </a>

                    </div>

                    @endif

                </form>

            </div>


            {{-- Audit history --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                <div class="px-8 py-6 border-b border-gray-100">

                    <h2 class="text-xl font-bold text-gray-800">
                        Observation Submission History
                    </h2>

                    <p class="text-sm text-gray-400 mt-1">
                        Records submitted by observers and external observers
                    </p>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-slate-50 text-gray-500 uppercase text-xs">

                            <tr>

                                <th class="px-6 py-4 text-left font-semibold">
                                    No
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Date
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Time
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Observed By
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Role
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Teacher Observed
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Stage
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Form
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse($audits as $audit)

                            <tr class="hover:bg-violet-50/50 transition">

                                {{-- Number --}}
                                <td class="px-6 py-5 text-gray-600">

                                    {{
                                        ($audits->currentPage() - 1)
                                        * $audits->perPage()
                                        + $loop->iteration
                                    }}

                                </td>


                                {{-- Date --}}
                                <td class="px-6 py-5 text-gray-700">

                                    {{
                                        $audit->audit_date
                                            ? $audit->audit_date->format('d/m/Y')
                                            : '-'
                                    }}

                                </td>


                                {{-- Time --}}
                                <td class="px-6 py-5 text-gray-700">

                                    {{
                                        $audit->audit_time
                                            ? \Carbon\Carbon::parse(
                                                $audit->audit_time
                                            )->format('h:i A')
                                            : '-'
                                    }}

                                </td>


                                {{-- Observed by --}}
                                <td class="px-6 py-5">

                                    <p class="font-semibold text-gray-800 uppercase">

                                        {{
                                            $audit->teacher?->teacher_name
                                            ?? '-'
                                        }}

                                    </p>

                                </td>


                                {{-- Role --}}
                                <td class="px-6 py-5">

                                    @if($audit->role === 'Observer')

                                    <span class="inline-flex
                                                 px-3 py-1
                                                 rounded-full
                                                 bg-blue-100
                                                 text-blue-700
                                                 text-xs
                                                 font-semibold">

                                        Observer

                                    </span>

                                    @else

                                    <span class="inline-flex
                                                 px-3 py-1
                                                 rounded-full
                                                 bg-violet-100
                                                 text-violet-700
                                                 text-xs
                                                 font-semibold">

                                        External Observer

                                    </span>

                                    @endif

                                </td>


                                {{-- Teacher observed --}}
                                <td class="px-6 py-5">

                                    <p class="font-semibold text-gray-800 uppercase">

                                        {{
                                            $audit->guruNew?->gn_name
                                            ?? '-'
                                        }}

                                    </p>

                                </td>


                                {{-- Stage --}}
                                <td class="px-6 py-5">

                                    @if($audit->stage === 'PRE')

                                    <span class="inline-flex
                                                 px-3 py-1
                                                 rounded-full
                                                 bg-sky-100
                                                 text-sky-700
                                                 text-xs
                                                 font-semibold">

                                        PRE

                                    </span>

                                    @elseif($audit->stage === 'POST')

                                    <span class="inline-flex
                                                 px-3 py-1
                                                 rounded-full
                                                 bg-emerald-100
                                                 text-emerald-700
                                                 text-xs
                                                 font-semibold">

                                        POST

                                    </span>

                                    @else

                                    <span class="inline-flex
                                                 px-3 py-1
                                                 rounded-full
                                                 bg-amber-100
                                                 text-amber-700
                                                 text-xs
                                                 font-semibold">

                                        EXTERNAL

                                    </span>

                                    @endif

                                </td>


                                {{-- Form --}}
                                <td class="px-6 py-5 text-gray-700">

                                    {{ $audit->form_name }}

                                </td>


                                {{-- Action --}}
                                <td class="px-6 py-5 text-center">

                                    <span class="inline-flex
                                                 items-center
                                                 gap-2
                                                 px-3 py-1
                                                 rounded-full
                                                 bg-emerald-100
                                                 text-emerald-700
                                                 text-xs
                                                 font-semibold">

                                        <span class="w-2 h-2
                                                     bg-emerald-500
                                                     rounded-full">
                                        </span>

                                        {{ $audit->action }}

                                    </span>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-12 text-gray-400">

                                    No observation audit records found.

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($audits->hasPages())

                <div class="px-8 py-5 border-t border-gray-100">

                    {{ $audits->links() }}

                </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>