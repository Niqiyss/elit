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
                            List New Teacher 
                        </h1>

                        <p class="text-violet-300 mt-2">
                            List of new teachers at your school
                        </p>

                    </div>

                    <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-2xl px-6 py-4 min-w-[150px]">

                        <p class="text-sm text-white">
                            Total Teacher
                        </p>

                        <p class="text-3xl font-bold text-white mt-1">
                            {{ $totalTeachers }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- Teacher List --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                {{-- Search --}}
                <div class="px-8 py-6 border-b border-gray-100">

                    <div class="flex justify-end">

                        <form
                            method="GET"
                            action="{{ route('principal.gn.list') }}"
                            class="flex items-center gap-3">

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search teacher..."
                                class="w-80 rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500">

                            <button
                                type="submit"
                                class="bg-blue-700 hover:bg-blue-800 text-white p-3 rounded-xl shadow transition">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />

                                </svg>

                            </button>

                            @if(request('search'))

                            <a
                                href="{{ route('principal.gn.list') }}"
                                class="px-3 py-2 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
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

                                {{-- No --}}
                                <td class="px-6 py-5 text-gray-600">
                                    {{ $guruNew->firstItem() + $loop->index }}
                                </td>


                                {{-- Teacher --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-3">

                                        <div class="w-10 h-10 rounded-full border-2 border-blue-500 flex items-center justify-center text-blue-700 font-bold">
                                            {{ strtoupper(substr($guru->gn_name, 0, 1)) }}
                                        </div>

                                        <p class="font-bold text-gray-800">
                                            {{ $guru->gn_name }}
                                        </p>

                                    </div>

                                </td>


                                {{-- Email --}}
                                <td class="px-6 py-5 text-gray-600">
                                    {{ $guru->email ?? '-' }}
                                </td>


                                {{-- Phone --}}
                                <td class="px-6 py-5 text-gray-600">
                                    {{ $guru->phone_number ?? '-' }}
                                </td>


                                {{-- Appointed Date --}}
                                <td class="px-6 py-5 text-gray-600">

                                    {{ $guru->appointed_date
                                            ? \Carbon\Carbon::parse($guru->appointed_date)->format('d M Y')
                                            : '-' }}

                                </td>


                                {{-- Status --}}
                                <td class="px-6 py-5 text-center">

                                    @if($guru->current_status === 'Active')

                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        Active
                                    </span>

                                    @elseif($guru->current_status === 'Complete')

                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        Complete
                                    </span>

                                    @else

                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        Inactive
                                    </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="px-6 py-5 text-center">

                                    <button
                                        type="button"
                                        onclick="openTeacherModal(this)"
                                        data-teacher="{{ json_encode([
                                                'ic_number' => $guru->ic_number,
                                                'gn_name' => $guru->gn_name,
                                                'phone_number' => $guru->phone_number,
                                                'email' => $guru->email,
                                                'marital_status' => $guru->marital_status,
                                                'gender' => $guru->gender,
                                                'address' => $guru->address,
                                                'race' => $guru->race,
                                                'appointed_date' => $guru->appointed_date
                                                    ? \Carbon\Carbon::parse($guru->appointed_date)->format('d M Y')
                                                    : '-',
                                                'current_status' => $guru->current_status,
                                                'school_name' => $guru->school_name,
                                            ]) }}"
                                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-lg shadow-sm transition">

                                        View

                                    </button>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-12 text-gray-500">

                                    No teachers found..

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($guruNew->hasPages())

                <div class="px-8 py-6 border-t border-gray-100">
                    {{ $guruNew->links() }}
                </div>

                @endif

            </div>

        </div>

    </div>


    {{-- Teacher Detail Modal --}}
    <div
        id="teacherModal"
        class="hidden fixed inset-0 z-[9999]">

        {{-- Overlay --}}
        <div
            class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
            onclick="closeTeacherModal()">
        </div>

        {{-- Modal --}}
        <div class="relative min-h-screen flex items-center justify-center p-6">

            <div class="relative bg-white w-full max-w-5xl rounded-3xl shadow-2xl overflow-hidden">

                {{-- Header --}}
                <div class="px-9 pt-8 pb-6 border-b border-gray-100">

                    <div class="flex items-start justify-between">

                        <div>

                            <h2 class="text-2xl font-bold text-slate-800">
                                Teacher Details
                            </h2>

                            <p class="text-sm text-slate-600 mt-1">
                                View new teacher information
                            </p>

                        </div>

                        <button
                            type="button"
                            onclick="closeTeacherModal()"
                            class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12" />

                            </svg>

                        </button>

                    </div>

                </div>


                {{-- Content --}}
                <div class="px-9 py-8">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-5">

                        {{-- LEFT COLUMN --}}
                        <div class="space-y-5">

                            {{-- School --}}
                            <div>

                                <p class="text-sm font-semibold text-slate-500 mb-1">
                                    School
                                </p>

                                <p
                                    id="modalSchoolDetail"
                                    class="text-base font-semibold text-slate-900">
                                </p>

                            </div>


                            {{-- IC Number --}}
                            <div>

                                <p class="text-sm font-semibold text-slate-500 mb-1">
                                    IC Number
                                </p>

                                <p
                                    id="modalIC"
                                    class="text-base font-semibold text-slate-900">
                                </p>

                            </div>


                            {{-- Full Name --}}
                            <div>

                                <p class="text-sm font-semibold text-slate-500 mb-1">
                                    Full Name
                                </p>

                                <p
                                    id="modalTeacherName"
                                    class="text-base font-semibold text-slate-900">
                                </p>

                            </div>


                            {{-- Email --}}
                            <div>

                                <p class="text-sm font-semibold text-slate-500 mb-1">
                                    Email
                                </p>

                                <p
                                    id="modalEmail"
                                    class="text-base font-semibold text-slate-900 break-all">
                                </p>

                            </div>


                            {{-- Phone Number --}}
                            <div>

                                <p class="text-sm font-semibold text-slate-500 mb-1">
                                    Phone Number
                                </p>

                                <p
                                    id="modalPhone"
                                    class="text-base font-semibold text-slate-900">
                                </p>

                            </div>

                        </div>


                        {{-- RIGHT COLUMN --}}
                        <div class="space-y-5">

                            {{-- Appointed Date --}}
                            <div>

                                <p class="text-sm font-semibold text-slate-500 mb-1">
                                    Appointed Date
                                </p>

                                <p
                                    id="modalAppointedDate"
                                    class="text-base font-semibold text-slate-900">
                                </p>

                            </div>


                            {{-- Current Status --}}
                            <div>

                                <p class="text-sm font-semibold text-slate-500 mb-2">
                                    Current Status
                                </p>

                                <span
                                    id="modalStatus"
                                    class="inline-flex px-3 py-1 rounded-full text-xs font-semibold">
                                </span>

                            </div>


                            {{-- Marital Status --}}
                            <div>

                                <p class="text-sm font-semibold text-slate-500 mb-1">
                                    Marital Status
                                </p>

                                <p
                                    id="modalMarital"
                                    class="text-base font-semibold text-slate-900">
                                </p>

                            </div>


                            {{-- Race --}}
                            <div>

                                <p class="text-sm font-semibold text-slate-500 mb-1">
                                    Race
                                </p>

                                <p
                                    id="modalRace"
                                    class="text-base font-semibold text-slate-900">
                                </p>

                            </div>

                            {{-- Gender --}}
                            <div>

                                <p class="text-sm font-semibold text-slate-500 mb-1">
                                    Gender
                                </p>

                                <p
                                    id="modalGender"
                                    class="text-base font-semibold text-slate-900">
                                </p>

                            </div>
                            

                        </div>


                        {{-- Address --}}
                        <div class="md:col-span-2 pt-3">

                            <div class="border-t border-slate-100 pt-5">

                                <p class="text-sm font-semibold text-slate-500 mb-1">
                                    Address
                                </p>

                                <p
                                    id="modalAddress"
                                    class="text-base font-semibold text-slate-900 leading-6">
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <script>
        // Open teacher modal
        function openTeacherModal(button) {

            const teacher = JSON.parse(
                button.dataset.teacher
            );

            document.getElementById('modalIC').textContent =
                teacher.ic_number || '-';

            document.getElementById('modalTeacherName').textContent =
                teacher.gn_name || '-';

            document.getElementById('modalPhone').textContent =
                teacher.phone_number || '-';

            document.getElementById('modalEmail').textContent =
                teacher.email || '-';

            document.getElementById('modalMarital').textContent =
                teacher.marital_status || '-';

            document.getElementById('modalGender').textContent =
                teacher.gender || '-';

            document.getElementById('modalRace').textContent =
                teacher.race || '-';

            document.getElementById('modalSchoolDetail').textContent =
                teacher.school_name || '-';

            document.getElementById('modalAppointedDate').textContent =
                teacher.appointed_date || '-';

            document.getElementById('modalAddress').textContent =
                teacher.address || '-';


            const status =
                document.getElementById('modalStatus');

            status.textContent =
                teacher.current_status || '-';

            status.className =
                'inline-flex px-3 py-1 rounded-full text-xs font-semibold';


            if (teacher.current_status === 'Active') {

                status.classList.add(
                    'bg-green-100',
                    'text-green-700'
                );

            } else if (teacher.current_status === 'Complete') {

                status.classList.add(
                    'bg-blue-100',
                    'text-blue-700'
                );

            } else {

                status.classList.add(
                    'bg-red-100',
                    'text-red-700'
                );
            }


            document.getElementById(
                'teacherModal'
            ).classList.remove('hidden');

            document.body.classList.add(
                'overflow-hidden'
            );
        }


        // Close teacher modal
        function closeTeacherModal() {

            document.getElementById(
                'teacherModal'
            ).classList.add('hidden');

            document.body.classList.remove(
                'overflow-hidden'
            );
        }


        // Close modal using Escape
        document.addEventListener(
            'keydown',
            function(event) {

                if (event.key === 'Escape') {
                    closeTeacherModal();
                }
            }
        );
    </script>

</x-app-layout>