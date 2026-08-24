<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('admin')->user()->staffname }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">

            {{-- HEADER --}}
            <div class="relative bg-gradient-to-br
                        from-slate-900 via-violet-950 to-purple-900
                        rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10
                            w-72 h-72 bg-purple-500/10 rounded-full blur-3xl">
                </div>

                <div class="relative z-10">

                    <h1 class="text-3xl font-extrabold text-white">
                        Manage Pre-Observation Form
                    </h1>

                    <p class="text-violet-300 mt-2">
                        Manage form information, sections and criteria
                    </p>

                </div>

            </div>


            {{-- SUCCESS --}}
            @if(session('success'))

                <div class="mb-6 px-5 py-4
                            bg-green-100 border border-green-200
                            text-green-700 rounded-xl">

                    {{ session('success') }}

                </div>

            @endif


            {{-- ERROR --}}
            @if(session('error'))

                <div class="mb-6 px-5 py-4
                            bg-red-100 border border-red-200
                            text-red-700 rounded-xl">

                    {{ session('error') }}

                </div>

            @endif


            {{-- CREATE FORM --}}
            @if(!$form)

                <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                    <div class="px-6 py-4 border-b border-gray-100">

                        <h2 class="text-lg font-bold text-gray-800">
                            Create Pre-Observation Form
                        </h2>

                        <p class="text-sm text-gray-400 mt-1">
                            Create the form before adding sections and criteria
                        </p>

                    </div>


                    <div class="px-6 py-5">

                        <form
                            method="POST"
                            action="{{ route('admin.pre.form.store') }}">

                            @csrf


                            <div class="grid grid-cols-1
                                        md:grid-cols-[1fr_180px_1fr]
                                        gap-4">

                                {{-- FORM NAME --}}
                                <div>

                                    <label class="block text-sm
                                                  text-gray-700
                                                  font-semibold mb-2">
                                        Form Name
                                    </label>

                                    <input
                                        type="text"
                                        name="form_name"
                                        value="{{ old('form_name') }}"
                                        required
                                        placeholder="E.g. Pre-Observation Form"
                                        class="w-full rounded-xl
                                               border-gray-300
                                               focus:border-purple-500
                                               focus:ring-purple-500">

                                </div>


                                {{-- STATUS --}}
                                <div>

                                    <label class="block text-sm
                                                  text-gray-700
                                                  font-semibold mb-2">
                                        Status
                                    </label>

                                    <select
                                        name="status"
                                        required
                                        class="w-full rounded-xl
                                               border-gray-300
                                               focus:border-purple-500
                                               focus:ring-purple-500">

                                        <option value="Active">
                                            Active
                                        </option>

                                        <option value="Inactive">
                                            Inactive
                                        </option>

                                    </select>

                                </div>


                                {{-- INSTRUCTION --}}
                                <div>

                                    <label class="block text-sm
                                                  text-gray-700
                                                  font-semibold mb-2">
                                        Instruction
                                    </label>

                                    <input
                                        type="text"
                                        name="instruction"
                                        value="{{ old('instruction') }}"
                                        placeholder="Enter instruction"
                                        class="w-full rounded-xl
                                               border-gray-300
                                               focus:border-purple-500
                                               focus:ring-purple-500">

                                </div>

                            </div>


                            <div class="flex justify-end gap-3 mt-5">

                                <a
                                    href="{{ route('admin.manage.form') }}"
                                    class="px-5 py-2
                                           bg-gray-200 hover:bg-gray-300
                                           text-gray-700 font-semibold text-sm
                                           rounded-xl transition">

                                    Back

                                </a>

                                <button
                                    type="submit"
                                    class="px-5 py-2
                                           bg-blue-700 hover:bg-blue-800
                                           text-white font-semibold text-sm
                                           rounded-xl shadow transition">

                                    Create Form

                                </button>

                            </div>

                        </form>

                    </div>

                </div>


            @else


                {{-- FORM INFORMATION --}}
                <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">

                    <div class="px-6 py-4 border-b border-gray-100">

                        <h2 class="text-lg font-bold text-gray-800">
                            Form Information
                        </h2>

                        <p class="text-sm text-gray-400 mt-1">
                            Update the main information displayed on the form
                        </p>

                    </div>


                    <div class="px-6 py-5">

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.pre.form.update',
                                $form->formID
                            ) }}">

                            @csrf
                            @method('PUT')


                            <div class="grid grid-cols-1
                                        md:grid-cols-[1fr_180px_1fr]
                                        gap-4">

                                {{-- FORM NAME --}}
                                <div>

                                    <label class="block text-sm
                                                  text-gray-700
                                                  font-semibold mb-2">
                                        Form Name
                                    </label>

                                    <input
                                        type="text"
                                        name="form_name"
                                        value="{{ old(
                                            'form_name',
                                            $form->form_name
                                        ) }}"
                                        required
                                        class="w-full rounded-xl
                                               border-gray-300">

                                </div>


                                {{-- STATUS --}}
                                <div>

                                    <label class="block text-sm
                                                  text-gray-700
                                                  font-semibold mb-2">
                                        Status
                                    </label>

                                    <select
                                        name="status"
                                        required
                                        class="w-full rounded-xl
                                               border-gray-300">

                                        <option
                                            value="Active"
                                            {{ $form->status === 'Active'
                                                ? 'selected'
                                                : '' }}>
                                            Active
                                        </option>

                                        <option
                                            value="Inactive"
                                            {{ $form->status === 'Inactive'
                                                ? 'selected'
                                                : '' }}>
                                            Inactive
                                        </option>

                                    </select>

                                </div>


                                {{-- INSTRUCTION --}}
                                <div>

                                    <label class="block text-sm
                                                  text-gray-700
                                                  font-semibold mb-2">
                                        Instruction
                                    </label>

                                    <input
                                        type="text"
                                        name="instruction"
                                        value="{{ old(
                                            'instruction',
                                            $form->instruction
                                        ) }}"
                                        class="w-full rounded-xl
                                               border-gray-300">

                                </div>

                            </div>


                            <div class="flex justify-end gap-3 mt-5">

                                <a
                                    href="{{ route('admin.manage.form') }}"
                                    class="px-5 py-2
                                           bg-gray-200 hover:bg-gray-300
                                           text-gray-700
                                           font-semibold text-sm
                                           rounded-xl transition">

                                    Back

                                </a>

                                <button
                                    type="submit"
                                    class="px-5 py-2
                                           bg-blue-700 hover:bg-blue-800
                                           text-white
                                           font-semibold text-sm
                                           rounded-xl shadow transition">

                                    Save

                                </button>

                            </div>

                        </form>

                    </div>

                </div>



                {{-- FORM CONTENT --}}
                <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">

                    <div class="px-6 py-5 border-b border-gray-100">

                        <h2 class="text-xl font-bold text-gray-800">
                            Form Content
                        </h2>

                        <p class="text-sm text-gray-400 mt-1">
                            Manage sections and criteria displayed in the Pre-Observation form
                        </p>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">

                            <thead class="bg-slate-50
                                          text-gray-500
                                          uppercase text-xs">

                                <tr>

                                    <th class="px-6 py-4 text-left w-24">
                                        No
                                    </th>

                                    <th class="px-6 py-4 text-left">
                                        Section / Criteria
                                    </th>

                                    <th class="px-6 py-4 text-center w-40">
                                        Status
                                    </th>

                                    <th class="px-6 py-4 text-center w-48">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                @forelse($form->sections as $section)

                                    @php
                                        $sectionNo = $loop->iteration;
                                    @endphp

                                    {{-- SECTION ROW --}}
                                    <tr class="bg-violet-50">

                                        <td class="px-6 py-5
                                                   font-bold text-violet-700">

                                            {{ $sectionNo }}

                                        </td>


                                        <td class="px-6 py-5">

                                            <p class="font-bold text-gray-900">
                                                {{ $section->section_name }}
                                            </p>

                                            <p class="text-xs text-gray-400 mt-1">
                                                Section
                                            </p>

                                        </td>


                                        <td class="px-6 py-5 text-center">

                                            <span class="text-gray-400">
                                                —
                                            </span>

                                        </td>


                                        <td class="px-6 py-5 text-center">

                                            <div class="inline-flex items-center gap-2">

                                                <button
                                                    type="button"
                                                    data-section-id="{{ $section->sectionID }}"
                                                    onclick="toggleSectionEdit(this)"
                                                    class="px-4 py-2
                                                           bg-blue-100
                                                           text-blue-700
                                                           hover:bg-blue-200
                                                           rounded-xl
                                                           text-sm
                                                           font-semibold">

                                                    Edit

                                                </button>


                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.pre.section.delete',
                                                        $section->sectionID
                                                    ) }}">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        onclick="return confirm(
                                                            'Delete this section and its criteria?'
                                                        )"
                                                        class="inline-flex
                                                               items-center
                                                               justify-center
                                                               w-10 h-10
                                                               bg-red-500
                                                               hover:bg-red-600
                                                               text-white
                                                               rounded-xl">

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
                                                                d="M3 6h18M8 6V4h8v2m-9 0
                                                                   1 14h8l1-14
                                                                   M10 10v6m4-6v6" />

                                                        </svg>

                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>


                                    {{-- SECTION EDIT --}}
                                    <tr
                                        id="section-edit-{{ $section->sectionID }}"
                                        class="hidden bg-slate-50">

                                        <td colspan="4" class="p-6">

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.pre.section.update',
                                                    $section->sectionID
                                                ) }}">

                                                @csrf
                                                @method('PUT')


                                                <div class="flex
                                                            flex-col
                                                            md:flex-row
                                                            gap-4
                                                            items-end">

                                                    <div class="flex-1">

                                                        <label class="block
                                                                      text-sm
                                                                      font-semibold
                                                                      mb-2">
                                                            Section Name
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="section_name"
                                                            value="{{ $section->section_name }}"
                                                            required
                                                            class="w-full
                                                                   rounded-xl
                                                                   border-gray-300">

                                                    </div>


                                                    <button
                                                        type="submit"
                                                        class="px-5 py-2
                                                               bg-blue-700
                                                               hover:bg-blue-800
                                                               text-white
                                                               rounded-xl
                                                               text-sm
                                                               font-semibold">

                                                        Update Section

                                                    </button>

                                                </div>

                                            </form>

                                        </td>

                                    </tr>



                                    {{-- CRITERIA ROWS --}}
                                    @foreach($section->criteria as $criteria)

                                        <tr class="hover:bg-slate-50 transition">

                                            <td class="px-6 py-5 text-gray-500">

                                                {{ $sectionNo }}.{{ $loop->iteration }}

                                            </td>


                                            <td class="px-6 py-5">

                                                <div class="flex items-start gap-3">

                                                    <span class="mt-2
                                                                 w-2 h-2
                                                                 rounded-full
                                                                 bg-violet-300
                                                                 flex-shrink-0">
                                                    </span>

                                                    <p class="text-gray-800">
                                                        {{ $criteria->criteria_label }}
                                                    </p>

                                                </div>

                                            </td>


                                            <td class="px-6 py-5 text-center">

                                                @if($criteria->status === 'Active')

                                                    <span class="px-3 py-1
                                                                 rounded-full
                                                                 bg-green-100
                                                                 text-green-700
                                                                 text-xs
                                                                 font-semibold">

                                                        Active

                                                    </span>

                                                @else

                                                    <span class="px-3 py-1
                                                                 rounded-full
                                                                 bg-gray-100
                                                                 text-gray-600
                                                                 text-xs
                                                                 font-semibold">

                                                        Inactive

                                                    </span>

                                                @endif

                                            </td>


                                            <td class="px-6 py-5 text-center">

                                                <div class="inline-flex items-center gap-2">

                                                    <button
                                                        type="button"
                                                        data-criteria-id="{{ $criteria->criteriaID }}"
                                                        onclick="toggleCriteriaEdit(this)"
                                                        class="px-4 py-2
                                                               bg-blue-100
                                                               text-blue-700
                                                               hover:bg-blue-200
                                                               rounded-xl
                                                               text-sm
                                                               font-semibold">

                                                        Edit

                                                    </button>


                                                    <form
                                                        method="POST"
                                                        action="{{ route(
                                                            'admin.pre.criteria.delete',
                                                            $criteria->criteriaID
                                                        ) }}">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            onclick="return confirm(
                                                                'Delete this criteria?'
                                                            )"
                                                            class="inline-flex
                                                                   items-center
                                                                   justify-center
                                                                   w-10 h-10
                                                                   bg-red-500
                                                                   hover:bg-red-600
                                                                   text-white
                                                                   rounded-xl">

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
                                                                    d="M3 6h18M8 6V4h8v2m-9 0
                                                                       1 14h8l1-14
                                                                       M10 10v6m4-6v6" />

                                                            </svg>

                                                        </button>

                                                    </form>

                                                </div>

                                            </td>

                                        </tr>


                                        {{-- CRITERIA EDIT --}}
                                        <tr
                                            id="criteria-edit-{{ $criteria->criteriaID }}"
                                            class="hidden bg-slate-50">

                                            <td colspan="4" class="p-6">

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.pre.criteria.update',
                                                        $criteria->criteriaID
                                                    ) }}">

                                                    @csrf
                                                    @method('PUT')


                                                    <div class="grid
                                                                grid-cols-1
                                                                md:grid-cols-[1fr_180px]
                                                                gap-4">

                                                        <div>

                                                            <label class="block
                                                                          text-sm
                                                                          font-semibold
                                                                          mb-2">
                                                                Criteria
                                                            </label>

                                                            <input
                                                                type="text"
                                                                name="criteria_label"
                                                                value="{{ $criteria->criteria_label }}"
                                                                required
                                                                class="w-full
                                                                       rounded-xl
                                                                       border-gray-300">

                                                        </div>


                                                        <div>

                                                            <label class="block
                                                                          text-sm
                                                                          font-semibold
                                                                          mb-2">
                                                                Status
                                                            </label>

                                                            <select
                                                                name="status"
                                                                required
                                                                class="w-full
                                                                       rounded-xl
                                                                       border-gray-300">

                                                                <option
                                                                    value="Active"
                                                                    {{ $criteria->status === 'Active'
                                                                        ? 'selected'
                                                                        : '' }}>
                                                                    Active
                                                                </option>

                                                                <option
                                                                    value="Inactive"
                                                                    {{ $criteria->status === 'Inactive'
                                                                        ? 'selected'
                                                                        : '' }}>
                                                                    Inactive
                                                                </option>

                                                            </select>

                                                        </div>

                                                    </div>


                                                    <div class="flex justify-end mt-5">

                                                        <button
                                                            type="submit"
                                                            class="px-5 py-2
                                                                   bg-blue-700
                                                                   hover:bg-blue-800
                                                                   text-white
                                                                   rounded-xl
                                                                   text-sm
                                                                   font-semibold">

                                                            Update Criteria

                                                        </button>

                                                    </div>

                                                </form>

                                            </td>

                                        </tr>

                                    @endforeach



                                    {{-- ADD CRITERIA --}}
                                    <tr>

                                        <td class="px-6 py-4"></td>

                                        <td colspan="3" class="px-6 py-4">

                                            <details>

                                                <summary
                                                    class="cursor-pointer
                                                           inline-flex items-center
                                                           text-blue-700
                                                           hover:text-blue-800
                                                           font-semibold text-sm">

                                                    + Add Criteria

                                                </summary>


                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.pre.criteria.store'
                                                    ) }}"
                                                    class="mt-5">

                                                    @csrf


                                                    <input
                                                        type="hidden"
                                                        name="sectionID"
                                                        value="{{ $section->sectionID }}">


                                                    <div class="grid
                                                                grid-cols-1
                                                                md:grid-cols-[1fr_180px]
                                                                gap-4">

                                                        <div>

                                                            <label class="block
                                                                          text-sm
                                                                          font-semibold
                                                                          mb-2">
                                                                Criteria
                                                            </label>

                                                            <input
                                                                type="text"
                                                                name="criteria_label"
                                                                required
                                                                placeholder="Enter criteria"
                                                                class="w-full
                                                                       rounded-xl
                                                                       border-gray-300">

                                                        </div>


                                                        <div>

                                                            <label class="block
                                                                          text-sm
                                                                          font-semibold
                                                                          mb-2">
                                                                Status
                                                            </label>

                                                            <select
                                                                name="status"
                                                                required
                                                                class="w-full
                                                                       rounded-xl
                                                                       border-gray-300">

                                                                <option value="Active">
                                                                    Active
                                                                </option>

                                                                <option value="Inactive">
                                                                    Inactive
                                                                </option>

                                                            </select>

                                                        </div>

                                                    </div>


                                                    <div class="flex justify-end mt-5">

                                                        <button
                                                            type="submit"
                                                            class="px-5 py-2
                                                                   bg-blue-700
                                                                   hover:bg-blue-800
                                                                   text-white
                                                                   rounded-xl
                                                                   text-sm
                                                                   font-semibold">

                                                            Add Criteria

                                                        </button>

                                                    </div>

                                                </form>

                                            </details>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="4"
                                            class="py-12
                                                   text-center
                                                   text-gray-400">

                                            No sections have been created

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>



                {{-- ADD NEW SECTION --}}
                <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                    <div class="px-6 py-4 border-b border-gray-100">

                        <h2 class="text-lg font-bold text-gray-800">
                            Add New Section
                        </h2>

                        <p class="text-sm text-gray-400 mt-1">
                            New sections are added to the bottom of the form
                        </p>

                    </div>


                    <div class="px-6 py-5">

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.pre.section.store'
                            ) }}">

                            @csrf


                            <input
                                type="hidden"
                                name="formID"
                                value="{{ $form->formID }}">


                            <div class="flex
                                        flex-col
                                        md:flex-row
                                        gap-4
                                        items-end">

                                <div class="flex-1">

                                    <label class="block
                                                  text-sm
                                                  text-gray-700
                                                  font-semibold mb-2">
                                        Section Name
                                    </label>

                                    <input
                                        type="text"
                                        name="section_name"
                                        required
                                        placeholder="Enter section name"
                                        class="w-full
                                               rounded-xl
                                               border-gray-300">

                                </div>


                                <button
                                    type="submit"
                                    class="px-5 py-2
                                           bg-blue-700
                                           hover:bg-blue-800
                                           text-white
                                           rounded-xl
                                           text-sm
                                           font-semibold">

                                    Add Section

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            @endif

        </div>

    </div>


    <script>

        function toggleSectionEdit(button)
        {
            const sectionID =
                button.dataset.sectionId;

            const row =
                document.getElementById(
                    'section-edit-' + sectionID
                );

            row.classList.toggle('hidden');
        }


        function toggleCriteriaEdit(button)
        {
            const criteriaID =
                button.dataset.criteriaId;

            const row =
                document.getElementById(
                    'criteria-edit-' + criteriaID
                );

            row.classList.toggle('hidden');
        }

    </script>

</x-app-layout>