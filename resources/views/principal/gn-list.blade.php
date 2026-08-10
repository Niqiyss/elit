<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('principal')->user()->principal_name }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">

            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900
                        rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10
                            w-72 h-72 bg-purple-500/10 rounded-full blur-3xl">
                </div>

                <div class="relative z-10 flex justify-between items-center">

                    <div>

                        <h1 class="text-3xl font-extrabold text-white">
                            New Teacher List
                        </h1>

                        <p class="text-violet-300 mt-2">
                            List of new teachers at your school
                        </p>

                    </div>

                    <div class="bg-white/10 backdrop-blur-sm
                                border border-white/10
                                rounded-2xl px-6 py-4 min-w-[150px]">

                        <p class="text-sm text-violet-200">
                            Total Teachers
                        </p>

                        <p class="text-3xl font-bold text-white mt-1">
                            {{ $totalTeachers }}
                        </p>

                    </div>

                </div>

            </div>

            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                <div class="px-8 py-6 border-b border-gray-100">

                    <div class="flex justify-end">

                        <form method="GET"
                            action="{{ route('principal.gn.list') }}"
                            class="flex items-center gap-3">

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search teacher..."
                                class="w-80 rounded-xl border-gray-300
                                focus:border-purple-500
                                focus:ring-purple-500">


                            <button
                                type="submit"
                                class="bg-blue-700 hover:bg-blue-800
                                text-white p-3 rounded-xl
                                shadow transition">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-4.35-4.35
                                        m1.85-5.15a7 7 0 11-14 0
                                        7 7 0 0114 0z" />

                                </svg>

                            </button>


                            @if(request('search'))

                            <a href="{{ route('principal.gn.list') }}"
                                class="px-4 py-3 rounded-xl
                           border border-gray-300
                           text-gray-600
                           hover:bg-gray-100 transition">
                                Clear
                            </a>
                            @endif

                        </form>

                    </div>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-slate-50 text-gray-500 uppercase text-xs">

                            <tr>

                                <th class="px-6 py-4 text-left font-semibold">
                                    No
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Teacher Name
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Email
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Phone Number
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Appointed Date
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

                            @forelse($guruNew as $guru)

                            <tr class="hover:bg-violet-50/50 transition">

                                <td class="px-6 py-5 text-gray-600">

                                    {{ $guruNew->firstItem() + $loop->index }}

                                </td>


                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-3">

                                        <div class="w-10 h-10 rounded-full
                                                        border-2 border-violet-500
                                                        flex items-center justify-center
                                                        text-violet-700 font-bold">

                                            {{ strtoupper(substr($guru->gn_name, 0, 1)) }}

                                        </div>


                                        <div>

                                            <p class="font-bold text-gray-800">
                                                {{ $guru->gn_name }}
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                <td class="px-6 py-5 text-gray-600">
                                    {{ $guru->email ?? '-' }}
                                </td>


                                <td class="px-6 py-5 text-gray-600">
                                    {{ $guru->phone_number ?? '-' }}
                                </td>


                                <td class="px-6 py-5 text-gray-600">

                                    {{ $guru->appointed_date
                                            ? \Carbon\Carbon::parse($guru->appointed_date)->format('d M Y')
                                            : '-' }}

                                </td>


                                <td class="px-6 py-5 text-center">

                                    @if($guru->current_status === 'Active')

                                    <span class="px-3 py-1 rounded-full
                                                         text-xs font-semibold
                                                         bg-green-100 text-green-700">

                                        Active

                                    </span>

                                    @elseif($guru->current_status === 'Complete')

                                    <span class="px-3 py-1 rounded-full
                                                         text-xs font-semibold
                                                         bg-blue-100 text-blue-700">

                                        Complete

                                    </span>

                                    @else

                                    <span class="px-3 py-1 rounded-full
                                                         text-xs font-semibold
                                                         bg-red-100 text-red-700">

                                        Inactive

                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-5 text-center">

                                    <a href="#"
                                        class="inline-flex items-center justify-center
                                        px-4 py-2 bg-purple-700
                                        hover:bg-purple-800
                                        text-white text-sm font-semibold
                                        rounded-lg shadow-sm transition">
                                        View
                                    </a>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td colspan="6"
                                    class="text-center py-12 text-gray-500">
                                    No new teachers found for your school
                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                @if($guruNew->hasPages())

                <div class="px-8 py-6 border-t border-gray-100">

                    {{ $guruNew->links() }}

                </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>