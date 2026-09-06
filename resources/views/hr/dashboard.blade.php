<x-app-layout>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">

            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                    <div>
                        <p class="text-violet-300 text-sm font-semibold mb-2">Welcome back,</p>

                        <h1 class="text-3xl font-extrabold text-white">
                            {{ Auth::guard('hr')->user()->hrname }}
                        </h1>
                    </div>

                    <div class="flex flex-wrap gap-2">

                        <a
                            href="{{ route('hr.gurunew.create') }}"
                            class="px-4 py-2 bg-white text-violet-900 rounded-lg text-sm font-semibold shadow hover:bg-violet-50 transition">
                            Register New Teacher
                        </a>

                        <a
                            href="{{ route('hr.gurunew.index') }}"
                            class="px-4 py-2 bg-violet-700/60 border border-violet-400/30 text-white rounded-lg text-sm font-semibold hover:bg-violet-700 transition">
                            Manage Teachers
                        </a>

                    </div>

                </div>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">
                    <p class="text-sm font-semibold text-gray-900">Total Teachers</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalTeachers }}</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">
                    <p class="text-sm font-semibold text-gray-900">Active</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $activeTeachers }}</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">
                    <p class="text-sm font-semibold text-gray-900">Inactive</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $inactiveTeachers }}</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">
                    <p class="text-sm font-semibold text-gray-900">Complete</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $completeTeachers }}</p>
                </div>

            </div>


            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between gap-4">

                    <div>

                        <h2 class="text-xl font-bold text-gray-800">
                            Latest New Teachers
                        </h2>

                        <p class="text-sm text-gray-400 mt-1">
                            Latest registered teacher accounts
                        </p>

                    </div>


                    <a
                        href="{{ route('hr.gurunew.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
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

                                        <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center border-2 border-blue-500">

                                            <span class="text-blue-700 font-bold text-sm">
                                                {{ strtoupper(substr($guru->gn_name, 0, 1)) }}
                                            </span>

                                        </div>

                                        <div>

                                            <p class="font-bold text-gray-800 uppercase">
                                                {{ $guru->gn_name }}
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

                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        Active
                                    </span>

                                    @elseif($guru->current_status === 'Complete')

                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        Complete
                                    </span>

                                    @else

                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
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