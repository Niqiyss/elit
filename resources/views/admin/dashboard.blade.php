<x-app-layout>

    <div class="min-h-screen bg-slate-100 py-8 px-6">

        <div class="max-w-7xl mx-auto">

            {{-- Header --}}
            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                    <div>

                        <p class="text-xs uppercase tracking-[0.2em] font-bold text-violet-300">
                            Admin/Staff Dashboard
                        </p>

                        <h1 class="text-3xl font-extrabold text-white mt-2">
                            Welcome, {{ $admin->staffname }}
                        </h1>

                    </div>

                    <a
                        href="{{ route('admin.manage.form') }}"
                        class="px-5 py-2.5 bg-white text-violet-900 rounded-xl text-sm font-semibold shadow hover:bg-violet-50 transition">

                        Manage Forms

                    </a>

                </div>

            </div>


            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

                {{-- Total Forms --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-5 py-4">

                    <p class="text-sm font-semibold text-slate-900">
                        Total Forms
                    </p>

                    <p class="text-2xl font-bold text-slate-900 mt-1">
                        {{ $totalForms }}
                    </p>

                </div>


                {{-- Active Forms --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-5 py-4">

                    <p class="text-sm font-semibold text-slate-900">
                        Active Forms
                    </p>

                    <p class="text-2xl font-bold text-slate-900 mt-1">
                        {{ $activeForms }}
                    </p>

                </div>


                {{-- Total Records --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-5 py-4">

                    <p class="text-sm font-semibold text-slate-900">
                        Total Observation Records
                    </p>

                    <p class="text-2xl font-bold text-slate-900 mt-1">
                        {{ $totalRecords }}
                    </p>

                </div>

            </div>


            {{-- Observation Forms --}}
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-7 mb-8">

                <div class="mb-6">

                    <h2 class="text-xl font-bold text-slate-900">
                        Observation Forms
                    </h2>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    {{-- PRE Form --}}
                    <a
                        href="{{ route('admin.pre.form') }}"
                        class="group border border-slate-200 rounded-2xl p-5 hover:border-violet-400 hover:bg-violet-50/50 transition">

                        <div class="flex items-center justify-between gap-4">

                            <div class="min-w-0">

                                <p class="text-xs font-bold text-violet-700 uppercase">
                                    PRE
                                </p>

                                <h3 class="font-bold text-slate-900 mt-2 truncate">
                                    {{ $activePreForm->form_name ?? 'Not Created Yet' }}
                                </h3>

                                <p class="text-sm text-slate-900 mt-1">
                                    {{ $preFormCount }}
                                    {{ $preFormCount === 1 ? 'version' : 'versions' }}
                                </p>

                            </div>

                            <span class="text-xl font-semibold text-slate-900 group-hover:text-violet-700 transition">
                                →
                            </span>

                        </div>

                    </a>


                    {{-- PDPC Form --}}
                    <a
                        href="{{ route('admin.pdpc.form') }}"
                        class="group border border-slate-200 rounded-2xl p-5 hover:border-blue-400 hover:bg-blue-50/50 transition">

                        <div class="flex items-center justify-between gap-4">

                            <div class="min-w-0">

                                <p class="text-xs font-bold text-blue-700 uppercase">
                                    PDPC
                                </p>

                                <h3 class="font-bold text-slate-900 mt-2 truncate">
                                    {{ $activePdpcForm->form_name ?? 'Not Created Yet' }}
                                </h3>

                                <p class="text-sm text-slate-900 mt-1">
                                    {{ $pdpcFormCount }}
                                    {{ $pdpcFormCount === 1 ? 'version' : 'versions' }}
                                </p>

                            </div>

                            <span class="text-xl font-semibold text-slate-900 group-hover:text-blue-700 transition">
                                →
                            </span>

                        </div>

                    </a>


                    {{-- Feedback Form --}}
                    <a
                        href="{{ route('admin.post.form') }}"
                        class="group border border-slate-200 rounded-2xl p-5 hover:border-emerald-400 hover:bg-emerald-50/50 transition">

                        <div class="flex items-center justify-between gap-4">

                            <div class="min-w-0">

                                <p class="text-xs font-bold text-emerald-700 uppercase">
                                    Feedback
                                </p>

                                <h3 class="font-bold text-slate-900 mt-2 truncate">
                                    {{ $activePostForm->form_name ?? 'Not Created Yet' }}
                                </h3>

                                <p class="text-sm text-slate-900 mt-1">
                                    {{ $postFormCount }}
                                    {{ $postFormCount === 1 ? 'version' : 'versions' }}
                                </p>

                            </div>

                            <span class="text-xl font-semibold text-slate-900 group-hover:text-emerald-700 transition">
                                →
                            </span>

                        </div>

                    </a>

                </div>

            </div>


            {{-- Observation Audit --}}
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

                {{-- Audit Header --}}
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between gap-4">

                    <div>

                        <h2 class="text-xl font-bold text-slate-900">
                            Observation Audit
                        </h2>

                        <p class="text-sm text-slate-900 mt-1">
                            Latest submitted observation records
                        </p>

                    </div>

                    <a
                        href="{{ route('admin.audit.observation') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">

                        View Audit

                    </a>

                </div>


                {{-- Audit Table --}}
                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-slate-50 text-slate-900 uppercase text-xs">

                            <tr>

                                <th class="px-5 py-4 text-left font-semibold">
                                    No.
                                </th>

                                <th class="px-5 py-4 text-left font-semibold">
                                    Date
                                </th>

                                <th class="px-5 py-4 text-left font-semibold">
                                    Time
                                </th>

                                <th class="px-5 py-4 text-left font-semibold whitespace-nowrap">
                                    Observed By
                                </th>

                                <th class="px-5 py-4 text-left font-semibold">
                                    Role
                                </th>

                                <th class="px-5 py-4 text-left font-semibold whitespace-nowrap">
                                    Teacher Observed
                                </th>

                                <th class="px-5 py-4 text-left font-semibold">
                                    Form
                                </th>

                                <th class="px-5 py-4 text-left font-semibold">
                                    Stage
                                </th>

                                <th class="px-5 py-4 text-center font-semibold">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @forelse($recentAudits as $audit)

                            <tr class="hover:bg-violet-50/50 transition">

                                {{-- Number --}}
                                <td class="px-5 py-5 text-slate-900">

                                    {{ $loop->iteration }}

                                </td>


                                {{-- Date --}}
                                <td class="px-5 py-5 text-slate-900">

                                    {{ $audit->audit_date
                                ? \Carbon\Carbon::parse($audit->audit_date)->format('d/m/Y')
                                : '-' }}

                                </td>


                                {{-- Time --}}
                                <td class="px-5 py-5 text-slate-900">

                                    {{ $audit->audit_time
                                ? \Carbon\Carbon::parse($audit->audit_time)->format('h:i A')
                                : '-' }}

                                </td>


                                {{-- Observed By --}}
                                <td class="px-5 py-5">

                                    <p class="font-semibold text-slate-900 uppercase">
                                        {{ $audit->teacher_name ?? '-' }}
                                    </p>

                                </td>


                                {{-- Role --}}
                                <td class="px-5 py-5">

                                    @if($audit->role === 'Observer')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                        Observer
                                    </span>

                                    @elseif($audit->role === 'External Observer')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-violet-100 text-violet-700 text-xs font-semibold">
                                        External Observer
                                    </span>

                                    @else

                                    <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-900 text-xs font-semibold">
                                        {{ $audit->role ?? '-' }}
                                    </span>

                                    @endif

                                </td>


                                {{-- Teacher Observed --}}
                                <td class="px-5 py-5">

                                    <p class="font-semibold text-slate-900 uppercase">
                                        {{ $audit->gn_name ?? '-' }}
                                    </p>

                                </td>


                                {{-- Form --}}
                                <td class="px-5 py-5 text-slate-900">

                                    {{ $audit->form_name ?? '-' }}

                                </td>


                                {{-- Stage --}}
                                <td class="px-5 py-5">

                                    @if($audit->stage === 'PRE')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-sky-100 text-sky-700 text-xs font-semibold">
                                        PRE
                                    </span>

                                    @elseif($audit->stage === 'POST')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">
                                        POST
                                    </span>

                                    @elseif($audit->stage === 'EXTERNAL')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">
                                        EXTERNAL
                                    </span>

                                    @else

                                    <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-900 text-xs font-semibold">
                                        {{ $audit->stage ?? '-' }}
                                    </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="px-5 py-5 text-center">

                                    @if($audit->action === 'Submitted')

                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">

                                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>

                                        Submitted

                                    </span>

                                    @else

                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">

                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>

                                        {{ $audit->action ?? '-' }}

                                    </span>

                                    @endif

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-12 text-slate-900">

                                    No observation audit records found.

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