<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('admin')->user()->staffname }}
        </h2>

    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">

            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900
                        rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10
                            w-72 h-72 bg-purple-500/10 rounded-full blur-3xl">
                </div>

                <div class="relative z-10">

                    <h1 class="text-3xl font-extrabold text-white">
                        Manage Feedback Observation Form
                    </h1>

                    <p class="text-violet-300 mt-2">
                        Manage form information, sections and fields
                    </p>

                </div>

            </div>

            @if(session('success'))
            <div class="mb-6 px-5 py-4
                bg-green-100 border border-green-200
                text-green-700 rounded-xl">

                {{ session('success') }}
            </div>
            @endif

            {{-- form not exist yet --}}
            @if(!$form)

            <div class="bg-white rounded-3xl shadow-lg p-8">

                <div class="mb-6">

                    <h2 class="text-xl font-bold text-gray-800">
                        Create Form
                    </h2>

                    <p class="text-sm text-gray-400 mt-1">
                        Create the feedback observation form before adding sections and fields
                    </p>

                </div>

                <form method="POST" action="{{ route('admin.post.form.store') }}">
                    @csrf

                    <div class="space-y-6">

                        <div>

                            <label
                                for="form_name"
                                class="block text-gray-700 font-semibold mb-2">
                                Form Name
                            </label>

                            <input
                                type="text"
                                id="form_name"
                                name="form_name"
                                value="{{ old('form_name') }}"
                                required
                                placeholder="E.g. Teacher Observation Feedback Form"
                                class="w-full rounded-xl border-gray-300
                                focus:border-purple-500
                                focus:ring-purple-500">

                            @error('form_name')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                            @enderror

                        </div>

                        <div>

                            <label
                                for="instruction"
                                class="block text-gray-700 font-semibold mb-2">
                                Instruction
                            </label>

                            <textarea
                                id="instruction"
                                name="instruction"
                                rows="3"
                                placeholder="Enter instructions displayed on the form"
                                class="w-full rounded-xl border-gray-300
                                focus:border-purple-500
                                focus:ring-purple-500">{{ old('instruction') }}</textarea>
                        </div>

                        <div>

                            <label
                                for="status"
                                class="block text-gray-700 font-semibold mb-2">
                                Status
                            </label>

                            <select
                                id="status"
                                name="status"
                                required
                                class="w-full rounded-xl border-gray-300
                                focus:border-purple-500
                                focus:ring-purple-500">

                                <option value="Active"
                                    {{ old('status') === 'Active' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="Inactive"
                                    {{ old('status') === 'Inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="flex justify-center items-center gap-4 mt-10">

                        <a href="{{ route('admin.manage.form') }}"
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


            @else

            {{-- FORM INFORMATION --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">

                <div class="px-8 py-6 border-b border-gray-100">

                    <h2 class="text-xl font-bold text-gray-800">
                        Form Information
                    </h2>

                    <p class="text-sm text-gray-400 mt-1">
                        Update the main information displayed on the form
                    </p>

                </div>


                <div class="p-8">

                    <form method="POST" action="{{ route( 'admin.post.form.update',$form->formID) }}">

                        @csrf
                        @method('PUT')


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>

                                <label
                                    for="form_name"
                                    class="block text-gray-700 font-semibold mb-2">
                                    Form Name
                                </label>

                                <input
                                    type="text"
                                    id="form_name"
                                    name="form_name"
                                    value="{{ old(
                                        'form_name',
                                        $form->form_name
                                    ) }}"
                                    required
                                    class="w-full rounded-xl border-gray-300
                                    focus:border-purple-500
                                    focus:ring-purple-500">
                            </div>

                            <div>

                                <label
                                    for="status"
                                    class="block text-gray-700 font-semibold mb-2">
                                    Status
                                </label>

                                <select
                                    id="status"
                                    name="status"
                                    required
                                    class="w-full rounded-xl border-gray-300
                                    focus:border-purple-500
                                    focus:ring-purple-500">

                                    <option value="Active"
                                        {{ $form->status === 'Active' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="Inactive"
                                        {{ $form->status === 'Inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                </select>

                            </div>

                            <div class="md:col-span-2">

                                <label
                                    for="instruction"
                                    class="block text-gray-700 font-semibold mb-2">
                                    Instruction
                                </label>

                                <textarea
                                    id="instruction"
                                    name="instruction"
                                    rows="3"
                                    class="w-full rounded-xl border-gray-300
                                    focus:border-purple-500
                                    focus:ring-purple-500">{{ old(
                                        'instruction',
                                        $form->instruction
                                    ) }}</textarea>
                            </div>

                        </div>


                        <div class="flex justify-center items-center gap-4 mt-10">

                        <a href="{{ route('admin.manage.form') }}"
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
                                Save
                            </button>

                    </div>

                    </form>

                </div>

            </div>

            {{-- FORM SECTIONS --}}
            <div class="mb-8">

                <div class="mb-5">

                    <h2 class="text-xl font-bold text-gray-800">
                        Form Sections
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Manage the sections and fields displayed on the form
                    </p>

                </div>

                @forelse($form->sections as $section)

                <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-6">

                    <div class="px-8 py-5 border-b border-gray-100
                        flex items-center justify-between">

                        <div>

                            <p class="text-xs font-bold uppercase
                                tracking-wider text-purple-600">
                                Section {{ $section->display_order }}
                            </p>

                            <h3 class="text-xl font-bold text-gray-800 mt-1">
                                {{ $section->section_name }}
                            </h3>

                        </div>

                        <div class="flex items-center gap-3">

                            <button
                                type="button"
                                data-section-id="{{ $section->sectionID }}"
                                onclick="toggleSectionEdit(this)"
                                class="px-4 py-2
                                bg-blue-100 text-blue-700
                                hover:bg-blue-200
                                rounded-xl font-semibold
                                text-sm transition">

                                Edit
                            </button>

                            <form method="POST" action="{{ route( 'admin.post.section.delete', $section->sectionID ) }}">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    onclick="return confirm( 'Delete this section and all its fields?' )"
                                    class="inline-flex items-center justify-center
                                    w-10 h-10
                                    bg-red-500 hover:bg-red-600
                                    text-white rounded-xl
                                    transition">

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
                                        1 14h8l1-14M10 10v6m4-6v6" />

                                    </svg>

                                </button>

                            </form>

                        </div>

                    </div>

                    <div
                        id="section-edit-{{ $section->sectionID }}"
                        class="hidden p-6 bg-slate-50 border-b border-gray-100">

                        <form method="POST" action="{{ route( 'admin.post.section.update', $section->sectionID ) }}">

                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                <div class="md:col-span-2">

                                    <label class="block text-sm
                                        font-semibold text-gray-700 mb-2">

                                        Section Name

                                    </label>

                                    <input
                                        type="text"
                                        name="section_name"
                                        value="{{ $section->section_name }}"
                                        required
                                        class="w-full rounded-xl border-gray-300">

                                </div>


                                <div>

                                    <label class="block text-sm
                                            font-semibold text-gray-700 mb-2">

                                        Display Order

                                    </label>

                                    <input
                                        type="number"
                                        name="display_order"
                                        value="{{ $section->display_order }}"
                                        min="1"
                                        required
                                        class="w-full rounded-xl border-gray-300">

                                </div>

                            </div>


                            <div class="flex justify-end mt-4">

                                <button
                                    type="submit"
                                    class="px-4 py-2
                                    bg-blue-700 hover:bg-blue-800
                                    text-white rounded-xl
                                    text-sm font-semibold">

                                    Update Section

                                </button>

                            </div>

                        </form>

                    </div>

                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">

                            <thead class="bg-slate-50
                                    text-gray-500 uppercase text-xs">

                                <tr>

                                    <th class="px-6 py-4 text-left">
                                        No
                                    </th>

                                    <th class="px-6 py-4 text-left">
                                        Field Label
                                    </th>

                                    <th class="px-6 py-4 text-center">
                                        Type
                                    </th>

                                    <th class="px-6 py-4 text-center">
                                        Required
                                    </th>

                                    <th class="px-6 py-4 text-center">
                                        Status
                                    </th>

                                    <th class="px-6 py-4 text-center">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                @forelse($section->fields as $field)

                                <tr class="hover:bg-violet-50/50 transition">

                                    <td class="px-6 py-5 text-gray-600">
                                        {{ $field->display_order }}
                                    </td>


                                    <td class="px-6 py-5
                                            font-semibold text-gray-800">
                                        {{ $field->field_label }}
                                    </td>


                                    <td class="px-6 py-5 text-center">
                                        {{ ucfirst($field->field_type) }}
                                    </td>


                                    <td class="px-6 py-5 text-center">

                                        {{ $field->is_required
                                        ? 'Yes'
                                        : 'No' }}

                                    </td>


                                    <td class="px-6 py-5 text-center">

                                        @if($field->status === 'Active')

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

                                        <div class="inline-flex
                                            items-center gap-2">

                                            <button
                                                type="button"
                                                data-field-id="{{ $field->fieldID }}"
                                                onclick="toggleFieldEdit(this)"
                                                class="px-4 py-2
                                                bg-blue-100
                                                text-blue-700
                                                hover:bg-blue-200
                                                rounded-xl
                                                text-sm
                                                font-semibold">

                                                Edit
                                            </button>


                                            <form method="POST" action="{{ route( 'admin.post.field.delete', $field->fieldID) }}">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    onclick="return confirm('Delete this field?')"
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
                                                            d="M3 6h18M8 6V4h8v2
                                                        m-9 0 1 14h8l1-14
                                                        M10 10v6m4-6v6" />

                                                    </svg>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                                <tr
                                    id="field-edit-{{ $field->fieldID }}"
                                    class="hidden bg-slate-50">

                                    <td colspan="6" class="p-6">

                                        <form method="POST" action="{{ route( 'admin.post.field.update', $field->fieldID) }}">

                                            @csrf
                                            @method('PUT')

                                            <div class="grid
                                                grid-cols-1
                                                md:grid-cols-2
                                                lg:grid-cols-5
                                                gap-4">

                                                <div class="lg:col-span-2">

                                                    <label class="block
                                                            text-sm
                                                            font-semibold
                                                            mb-2">
                                                        Field Label
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="field_label"
                                                        value="{{ $field->field_label }}"
                                                        required
                                                        class="w-full rounded-xl
                                                        border-gray-300">

                                                </div>


                                                <div>

                                                    <label class="block
                                                            text-sm
                                                            font-semibold
                                                            mb-2">
                                                        Type
                                                    </label>

                                                    <select
                                                        name="field_type"
                                                        onchange="handleFieldType(this)"
                                                        required
                                                        class="w-full rounded-xl border-gray-300">

                                                        @foreach([
                                                        'display',
                                                        'text',
                                                        'textarea',
                                                        'number',
                                                        'date',
                                                        'time'
                                                        ] as $type)

                                                        <option
                                                            value="{{ $type }}"
                                                            {{ $field->field_type === $type
                                                                                ? 'selected'
                                                                                : '' }}>

                                                            {{ ucfirst($type) }}

                                                        </option>

                                                        @endforeach

                                                    </select>

                                                </div>


                                                <div>

                                                    <label class="block
                                                            text-sm
                                                            font-semibold
                                                            mb-2">
                                                        Required
                                                    </label>

                                                    <select
                                                        name="is_required"
                                                        required
                                                        class="required-select w-full rounded-xl border-gray-300">

                                                        <option value="1"
                                                            {{ $field->is_required
                                                            ? 'selected'
                                                            : '' }}>
                                                            Yes
                                                        </option>

                                                        <option value="0"
                                                            {{ !$field->is_required
                                                            ? 'selected'
                                                            : '' }}>
                                                            No
                                                        </option>

                                                    </select>

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
                                                        class="w-full rounded-xl
                                                            border-gray-300">

                                                        <option value="Active"
                                                            {{ $field->status === 'Active'
                                                            ? 'selected'
                                                            : '' }}>
                                                            Active
                                                        </option>

                                                        <option value="Inactive"
                                                            {{ $field->status === 'Inactive'
                                                            ? 'selected'
                                                            : '' }}>
                                                            Inactive
                                                        </option>

                                                    </select>

                                                </div>

                                            </div>


                                            <div class="mt-4 w-40">

                                                <label class="block
                                                    text-sm
                                                    font-semibold
                                                    mb-2">
                                                    Display Order
                                                </label>

                                                <input
                                                    type="number"
                                                    name="display_order"
                                                    value="{{ $field->display_order }}"
                                                    min="1"
                                                    required
                                                    class="w-full rounded-xl
                                                    border-gray-300">

                                            </div>


                                            <div class="flex justify-end mt-4">

                                                <button
                                                    type="submit"
                                                    class="px-4 py-2
                                                    bg-blue-700
                                                    hover:bg-blue-800
                                                    text-white
                                                    rounded-xl
                                                    text-sm
                                                    font-semibold">

                                                    Update Field

                                                </button>

                                            </div>

                                        </form>

                                    </td>

                                </tr>

                                @empty

                                <tr>

                                    <td colspan="6"
                                        class="text-center py-8
                                        text-gray-400">

                                        No fields added to this section

                                    </td>

                                </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    <div class="p-6 border-t border-gray-100">

                        <details>

                            <summary
                                class="cursor-pointer inline-flex
                                items-center gap-2
                                text-blue-700 font-semibold">
                                + Add Field
                            </summary>


                            <form method="POST" action="{{ route( 'admin.post.field.store' ) }}" class="mt-6">

                                @csrf

                                <input
                                    type="hidden"
                                    name="sectionID"
                                    value="{{ $section->sectionID }}">


                                <div class="grid
                                    grid-cols-1
                                    md:grid-cols-2
                                    lg:grid-cols-5
                                    gap-4">

                                    <div class="lg:col-span-2">

                                        <label class="block text-sm
                                            font-semibold mb-2">
                                            Field Label
                                        </label>

                                        <input
                                            type="text"
                                            name="field_label"
                                            required
                                            placeholder="Enter field label"
                                            class="w-full rounded-xl
                                            border-gray-300">

                                    </div>


                                    <div>

                                        <label class="block text-sm
                                            font-semibold mb-2">
                                            Field Type
                                        </label>

                                        <select
                                            name="field_type"
                                            onchange="handleFieldType(this)"
                                            required
                                            class="w-full rounded-xl border-gray-300">

                                            <option value="display">
                                                Display
                                            </option>

                                            <option value="text">
                                                Text
                                            </option>

                                            <option value="textarea">
                                                Text Area
                                            </option>

                                            <option value="number">
                                                Number
                                            </option>

                                            <option value="date">
                                                Date
                                            </option>

                                            <option value="time">
                                                Time
                                            </option>

                                        </select>

                                    </div>


                                    <div>

                                        <label class="block text-sm
                                            font-semibold mb-2">
                                            Required
                                        </label>

                                        <select
                                            name="is_required"
                                            class="required-select w-full rounded-xl border-gray-300"
                                            required>

                                            <option value="1">
                                                Yes
                                            </option>

                                            <option value="0">
                                                No
                                            </option>

                                        </select>

                                    </div>


                                    <div>

                                        <label class="block text-sm
                                                font-semibold mb-2">
                                            Status
                                        </label>

                                        <select
                                            name="status"
                                            required
                                            class="w-full rounded-xl
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


                                <div class="mt-4 w-40">

                                    <label class="block text-sm
                                        font-semibold mb-2">
                                        Display Order
                                    </label>

                                    <input
                                        type="number"
                                        name="display_order"
                                        min="1"
                                        value="{{ $section->fields->count() + 1 }}"
                                        required
                                        class="w-full rounded-xl
                                        border-gray-300">

                                </div>


                                <div class="flex justify-end mt-5">

                                    <button
                                        type="submit"
                                        class="px-5 py-2
                                        bg-blue-700
                                        hover:bg-blue-800
                                        text-white
                                        rounded-xl
                                        text-sm font-semibold">

                                        Add Field

                                    </button>

                                </div>

                            </form>

                        </details>

                    </div>

                </div>

                @empty

                <div class="bg-white rounded-3xl shadow-lg
                    text-center py-12 text-gray-400">
                    No sections have been created
                </div>

                @endforelse

            </div>

            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                <div class="px-8 py-6 border-b border-gray-100">

                    <h2 class="text-xl font-bold text-gray-800">
                        Add New Section
                    </h2>

                    <p class="text-sm text-gray-400 mt-1">
                        Create another section for this form
                    </p>

                </div>

                <div class="p-8">

                    <form method="POST" action="{{ route('admin.post.section.store') }}">

                        @csrf

                        <input type="hidden" name="formID" value="{{ $form->formID }}">


                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <div class="md:col-span-2">

                                <label
                                    for="section_name"
                                    class="block text-gray-700
                                    font-semibold mb-2">

                                    Section Name

                                </label>

                                <input
                                    type="text"
                                    id="section_name"
                                    name="section_name"
                                    required
                                    placeholder="Enter section name"
                                    class="w-full rounded-xl border-gray-300
                                    focus:border-purple-500
                                    focus:ring-purple-500">

                            </div>


                            <div>

                                <label
                                    for="section_order"
                                    class="block text-gray-700
                                               font-semibold mb-2">
                                    Display Order
                                </label>

                                <input
                                    type="number"
                                    id="section_order"
                                    name="display_order"
                                    min="1"
                                    value="{{ $form->sections->count() + 1 }}"
                                    required
                                    class="w-full rounded-xl border-gray-300
                                    focus:border-purple-500
                                    focus:ring-purple-500">

                            </div>

                        </div>

                        <div class="flex justify-end mt-6">

                            <button
                                type="submit"
                                class="px-5 py-2
                                bg-blue-700 hover:bg-blue-800
                                text-white font-semibold
                                text-sm rounded-xl shadow transition">
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
        function toggleSectionEdit(button) {
            const sectionID = button.dataset.sectionId;

            const element = document.getElementById(
                'section-edit-' + sectionID
            );

            element.classList.toggle('hidden');
        }

        function toggleFieldEdit(button) {
            const fieldID = button.dataset.fieldId;

            const element = document.getElementById(
                'field-edit-' + fieldID
            );

            element.classList.toggle('hidden');
        }

        function handleFieldType(fieldTypeSelect) {
            const container = fieldTypeSelect.closest('form');

            const requiredSelect =
                container.querySelector('.required-select');

            if (!requiredSelect) {
                return;
            }

            if (fieldTypeSelect.value === 'display') {
                requiredSelect.value = '0';

                requiredSelect.classList.add(
                    'bg-gray-100',
                    'pointer-events-none'
                );
            } else {
                requiredSelect.classList.remove(
                    'bg-gray-100',
                    'pointer-events-none'
                );
            }
        }

        document.addEventListener(
            'DOMContentLoaded',
            function() {
                document
                    .querySelectorAll(
                        'select[name="field_type"]'
                    )
                    .forEach(function(select) {
                        handleFieldType(select);
                    });
            }
        );
    </script>

</x-app-layout>