<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('hr')->user()->hrname }}
        </h2>

    </x-slot>


    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">

            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <h1 class="text-3xl font-extrabold text-white">
                        New Teacher List
                    </h1>

                    <p class="text-violet-300 mt-2">
                        Manage new teacher accounts here
                    </p>

                </div>

            </div>


            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 px-8 py-6 border-b border-gray-100">

                    <div class="flex items-center gap-3">

                        <form method="GET"
                            action="{{ route('hr.gurunew.index') }}"
                            class="flex items-center gap-3">

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search teacher..."
                                class="w-80 rounded-2xl border border-gray-300 px-5 py-3 text-sm
                                       focus:ring-2 focus:ring-violet-500
                                       focus:border-violet-500
                                       focus:outline-none">

                            <button
                                type="submit"
                                class="bg-violet-700 hover:bg-violet-800 text-white p-3 rounded-xl shadow transition">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />

                                </svg>

                            </button>

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
                                    Teacher
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Email
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    School
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse($guruNews as $guru)

                            <tr class="hover:bg-violet-50/50 transition">

                                <td class="px-6 py-5 text-gray-600">
                                    {{ $loop->iteration }}
                                </td>


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

                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ $guru->gn_id }}
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                <td class="px-6 py-5 text-gray-600">
                                    {{ $guru->email }}
                                </td>


                                <td class="px-6 py-5 text-gray-600 max-w-xs">
                                    {{ $guru->school?->school_name ?? '-' }}
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


                                <td class="px-6 py-5 text-center">
                                    @if($guru->current_status !== 'Complete')

                                    <button
                                        type="button"
                                        onclick="openModal('{{ $guru->gn_id }}')"
                                        class="bg-blue-600 hover:bg-vlue-700 text-white px-5 py-2 rounded-xl font-semibold text-sm shadow transition">
                                        Manage
                                    </button>

                                    @else

                                    <span class="text-gray-400">
                                        -
                                    </span>

                                    @endif

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td colspan="6"
                                    class="text-center py-12 text-gray-400">
                                    No new teachers found
                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                @if(method_exists($guruNews, 'links'))

                <div class="px-8 py-6 border-t border-gray-100">

                    {{ $guruNews->links() }}

                </div>

                @endif

            </div>

        </div>

    </div>


    {{-- MODALS --}}
    @foreach($guruNews as $guru)

    <div id="modal-{{ $guru->gn_id }}"
        class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 px-6">

        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl p-8 max-h-[90vh] overflow-y-auto">

            <div class="flex justify-between items-center mb-7">

                <div>

                    <h2 class="text-2xl font-bold text-gray-800">
                        Manage Teacher
                    </h2>

                    <p class="text-sm text-gray-400 mt-1">
                        View and manage new teacher details
                    </p>

                </div>

                <button
                    type="button"
                    onclick="closeModal('{{ $guru->gn_id }}')"
                    class="text-2xl text-gray-400 hover:text-red-500 transition">

                    ×

                </button>

            </div>


            <form method="POST"
                action="{{ route('hr.gurunew.update', $guru->gn_id) }}">

                @csrf
                @method('PUT')


                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">


                    {{-- LEFT COLUMN --}}
                    <div class="space-y-5">

                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                IC Number
                            </label>

                            <input
                                type="text"
                                value="{{ $guru->ic_number }}"
                                readonly
                                class="w-full bg-gray-100 border border-gray-300 rounded-xl px-4 py-3">

                        </div>


                        <div>

                            <label for="phone_number_{{ $guru->gn_id }}"
                                class="block text-sm font-semibold text-gray-700 mb-2">
                                Phone Number
                            </label>

                            <input
                                type="text"
                                id="phone_number_{{ $guru->gn_id }}"
                                name="phone_number"
                                value="{{ $guru->phone_number }}"
                                required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3
                                       focus:border-violet-500 focus:ring-violet-500">

                        </div>

                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Gender
                            </label>

                            <input
                                type="text"
                                value="{{ $guru->gender }}"
                                readonly
                                class="w-full bg-gray-100 border border-gray-300 rounded-xl px-4 py-3">

                        </div>


                        <div>

                            <label for="appointed_date_{{ $guru->gn_id }}"
                                class="block text-sm font-semibold text-gray-700 mb-2">
                                Appointed Date
                            </label>

                            <input
                                type="date"
                                id="appointed_date_{{ $guru->gn_id }}"
                                name="appointed_date"
                                value="{{ $guru->appointed_date }}"
                                required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3
                                       focus:border-violet-500 focus:ring-violet-500">

                        </div>

                    </div>


                    {{-- RIGHT COLUMN --}}
                    <div class="space-y-5">

                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Teacher Name
                            </label>

                            <input
                                type="text"
                                value="{{ $guru->gn_name }}"
                                readonly
                                class="w-full bg-gray-100 border border-gray-300 rounded-xl px-4 py-3">

                        </div>


                        <div>

                            <label for="email_{{ $guru->gn_id }}"
                                class="block text-sm font-semibold text-gray-700 mb-2">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email_{{ $guru->gn_id }}"
                                name="email"
                                value="{{ $guru->email }}"
                                required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3
                                       focus:border-violet-500 focus:ring-violet-500">

                        </div>


                        <div>

                            <label for="schoolID_{{ $guru->gn_id }}"
                                class="block text-sm font-semibold text-gray-700 mb-2">
                                School
                            </label>

                            <select
                                id="schoolID_{{ $guru->gn_id }}"
                                name="schoolID"
                                required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3
                                       focus:border-violet-500 focus:ring-violet-500">

                                @foreach($schools as $school)

                                <option
                                    value="{{ $school->schoolID }}"
                                    {{ $guru->schoolID == $school->schoolID ? 'selected' : '' }}>

                                    {{ $school->school_name }}

                                </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- STATUS --}}
                        <div class="mt-6">

                            <label for="current_status_{{ $guru->gn_id }}"
                                class="block text-sm font-semibold text-gray-700 mb-2">
                                Current Status
                            </label>

                            <select
                                id="current_status_{{ $guru->gn_id }}"
                                name="current_status"
                                required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3
                               focus:border-violet-500 focus:ring-violet-500">

                                <option value="Inactive"
                                    {{ $guru->current_status === 'Inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>

                                <option value="Active"
                                    {{ $guru->current_status === 'Active' ? 'selected' : '' }}>
                                    Active
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                <div class="flex justify-center gap-3 mt-8">

                    <button
                        type="button"
                        onclick="closeModal('{{ $guru->gn_id }}')"
                        class="px-5 py-2 bg-gray-200 hover:bg-gray-300
                               text-gray-700 rounded-xl font-semibold transition">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="px-5 py-2 bg-blue-700 hover:bg-blue-800
                               text-white rounded-xl font-semibold shadow transition">
                        Save
                    </button>

                </div>

            </form>

        </div>

    </div>

    @endforeach


    <script>
        function openModal(id) {

            const modal = document.getElementById('modal-' + id);

            if (modal) {

                modal.classList.remove('hidden');
                modal.classList.add('flex');

            }

        }


        function closeModal(id) {

            const modal = document.getElementById('modal-' + id);

            if (modal) {

                modal.classList.remove('flex');
                modal.classList.add('hidden');

            }

        }
    </script>

</x-app-layout>