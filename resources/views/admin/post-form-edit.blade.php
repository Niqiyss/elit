<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('admin')->user()->staffname }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">

            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl px-8 py-6 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    <div>

                        <h1 class="text-3xl font-extrabold text-white">
                            {{ $form->form_name }}
                        </h1>

                        <p class="text-violet-300 mt-2">
                            Manage form information, sections and fields
                        </p>

                    </div>

                    <div class="flex items-stretch gap-3">

                        <div class="min-w-[110px] bg-white/10 border border-white/10 rounded-2xl px-5 py-3">

                            <p class="text-xs uppercase tracking-wider text-violet-200 font-semibold">
                                Version
                            </p>

                            <p class="text-xl font-bold text-white mt-1">
                                {{ $form->version }}
                            </p>

                        </div>

                        {{-- USED --}}
                        @if($formUsed)

                        <div class="bg-amber-400/10 border border-amber-300/20 rounded-2xl px-5 py-3 flex items-center gap-3">

                            <div class="w-9 h-9 rounded-xl bg-amber-300/20 flex items-center justify-center">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-amber-200"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />

                                </svg>

                            </div>

                            <div>

                                <p class="text-sm font-bold text-white">
                                    Form in used
                                </p>

                                <p class="text-xs text-amber-200 mt-0.5">
                                    Only section/field name can be change
                                </p>

                            </div>

                        </div>

                        @else

                        <div class="bg-blue-400/10 border border-blue-300/20 rounded-2xl px-5 py-3 flex items-center gap-3">

                            <div class="w-9 h-9 rounded-xl bg-blue-300/20 flex items-center justify-center">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-blue-200"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 13l4 4L19 7" />

                                </svg>

                            </div>

                            <div>

                                <p class="text-sm font-bold text-white">
                                    Current Form
                                </p>

                                <p class="text-xs text-blue-200 mt-0.5">
                                    Form content can be change
                                </p>

                            </div>

                        </div>

                        @endif

                    </div>

                </div>

            </div>

            @if(session('success'))
            <div class="mb-6 px-5 py-4 bg-green-100 border border-green-200 text-green-700 rounded-xl">
                {{ session('success') }}
            </div>
            @endif


            @if(session('error'))
            <div class="mb-6 px-5 py-4 bg-red-100 border border-red-200 text-red-700 rounded-xl">
                {{ session('error') }}
            </div>
            @endif


            @if($errors->any())

            <div class="mb-6 px-5 py-4 bg-red-100 border border-red-200 text-red-700 rounded-xl">

                <ul class="list-disc list-inside text-sm">

                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

            @endif

            @php
            $sectionCount = $form->sections->count();

            $fieldCount = $form->sections->sum(function ($section) {
            return $section->fields->count();
            });

            $inputCount = $form->sections->sum(function ($section) {
            return $section->fields->where('field_type', '!=', 'display')->count();
            });
            @endphp

            {{-- FORM INFO --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden mb-8">

                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">

                    <div>

                        <h2 class="text-lg font-bold text-gray-900">
                            Form Information
                        </h2>

                        <p class="text-sm text-gray-400 mt-1">
                            Update form name and instruction
                        </p>

                    </div>

                    <div class="hidden md:flex items-center gap-5 text-sm">

                        <div>
                            <span class="text-gray-400">Sections</span>
                            <span class="font-bold text-gray-800 ml-1">
                                {{ $sectionCount }}
                            </span>
                        </div>

                        <div>
                            <span class="text-gray-400">Fields</span>
                            <span class="font-bold text-gray-800 ml-1">
                                {{ $fieldCount }}
                            </span>
                        </div>

                    </div>

                </div>

                <div class="p-6">

                    <form
                        id="formInformation"
                        method="POST"
                        action="{{ route('admin.post.form.update', $form->formID) }}">

                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Form Name
                                </label>

                                <input
                                    type="text"
                                    name="form_name"
                                    value="{{ old('form_name', $form->form_name) }}"
                                    required
                                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                            </div>

                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Instruction
                                </label>

                                <input
                                    type="text"
                                    name="instruction"
                                    value="{{ old('instruction', $form->instruction) }}"
                                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                            </div>

                        </div>

                    </form>

                </div>

            </div>

            {{-- SECTIONS --}}
            <div class="space-y-6 mb-8">

                @forelse($form->sections as $section)

                <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">

                    <div class="bg-blue-900 px-6 py-4">

                        <div class="flex items-center justify-between gap-4">

                            <div>

                                <p class="text-xs uppercase tracking-wider text-blue-200 font-semibold">
                                    Section {{ $loop->iteration }}
                                </p>

                                <h2 class="text-lg font-bold text-white mt-1">
                                    {{ $section->section_name }}
                                </h2>

                            </div>

                            <div class="flex items-center gap-2">

                                <button
                                    type="button"
                                    data-section-id="{{ $section->sectionID }}"
                                    onclick="toggleSectionEdit(this)"
                                    class="px-4 py-2 bg-amber-400 hover:bg-amber-500 text-gray-900 text-sm font-semibold rounded-xl transition">

                                    Edit

                                </button>

                                @if(!$formUsed)

                                <form
                                    method="POST"
                                    action="{{ route('admin.post.section.delete', $section->sectionID) }}">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        onclick="return confirm('Delete this section and all its fields?')"
                                        title="Delete Section"
                                        class="w-10 h-10 inline-flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-xl transition">

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
                                                d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5" />

                                        </svg>

                                    </button>

                                </form>

                                @endif

                            </div>

                        </div>

                    </div>

                    <div
                        id="section-edit-{{ $section->sectionID }}"
                        class="hidden p-5 bg-blue-50 border-b border-blue-100">

                        <form
                            method="POST"
                            action="{{ route('admin.post.section.update', $section->sectionID) }}">

                            @csrf
                            @method('PUT')

                            <div class="flex flex-col md:flex-row gap-4 items-end">

                                <div class="flex-1">

                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Section Name
                                    </label>

                                    <input
                                        type="text"
                                        name="section_name"
                                        value="{{ $section->section_name }}"
                                        required
                                        class="w-full rounded-xl border-gray-300">

                                    @if($formUsed)

                                    <p class="text-xs text-amber-600 mt-2">
                                        Wording correction only.
                                    </p>

                                    @endif

                                </div>

                                <button
                                    type="submit"
                                    class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl">

                                    Update

                                </button>

                            </div>

                        </form>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">

                            <thead class="bg-slate-50 text-gray-900 uppercase text-xs">

                                <tr>
                                    <th class="px-5 py-4 text-left w-20">No</th>
                                    <th class="px-5 py-4 text-left">Field</th>
                                    <th class="px-5 py-4 text-center w-32">Type</th>
                                    <th class="px-5 py-4 text-center w-28">Required</th>
                                    <th class="px-5 py-4 text-center w-44">Action</th>
                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                @forelse($section->fields as $field)

                                <tr class="hover:bg-slate-50 transition">

                                    <td class="px-5 py-4 text-gray-500">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-5 py-4">

                                        <p class="font-semibold text-gray-800">
                                            {{ $field->field_label }}
                                        </p>

                                        @if($field->options->isNotEmpty())

                                        <div class="flex flex-wrap gap-2 mt-2">

                                            @foreach($field->options as $option)

                                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs">
                                                {{ $option->option_label }}
                                            </span>

                                            @endforeach

                                        </div>

                                        @endif

                                    </td>

                                    <td class="px-5 py-4 text-center">

                                        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold">
                                            {{ ucfirst($field->field_type) }}
                                        </span>

                                    </td>

                                    <td class="px-5 py-4 text-center">

                                        @if($field->field_type === 'display')

                                        <span class="text-gray-400">—</span>

                                        @elseif($field->is_required)

                                        <span class="font-semibold text-green-700">
                                            Yes
                                        </span>

                                        @else

                                        <span class="text-gray-500">
                                            No
                                        </span>

                                        @endif

                                    </td>

                                    <td class="px-5 py-4 text-center">

                                        <div class="inline-flex items-center gap-2">

                                            <button
                                                type="button"
                                                data-field-id="{{ $field->fieldID }}"
                                                onclick="toggleFieldEdit(this)"
                                                class="px-4 py-2 bg-amber-400 hover:bg-amber-500 text-gray-900 rounded-xl text-sm font-semibold transition">

                                                Edit

                                            </button>

                                            @if(!$formUsed)

                                            <form
                                                method="POST"
                                                action="{{ route('admin.post.field.delete', $field->fieldID) }}">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    onclick="return confirm('Delete this field?')"
                                                    title="Delete Field"
                                                    class="w-10 h-10 inline-flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-xl transition">

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
                                                            d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5" />

                                                    </svg>

                                                </button>

                                            </form>

                                            @endif

                                        </div>

                                    </td>

                                </tr>


                                <tr
                                    id="field-edit-{{ $field->fieldID }}"
                                    class="hidden bg-slate-50">

                                    <td colspan="5" class="p-5">

                                        <form
                                            method="POST"
                                            action="{{ route('admin.post.field.update', $field->fieldID) }}">

                                            @csrf
                                            @method('PUT')

                                            @if($formUsed)


                                            <div class="flex flex-col md:flex-row gap-4 items-end">

                                                <div class="flex-1">

                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Field
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="field_label"
                                                        value="{{ $field->field_label }}"
                                                        required
                                                        class="w-full rounded-xl border-gray-300">

                                                    <p class="text-xs text-amber-600 mt-2">
                                                        Wording correction only
                                                    </p>

                                                </div>

                                                <button
                                                    type="submit"
                                                    class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white rounded-xl text-sm font-semibold">

                                                    Update

                                                </button>

                                            </div>

                                            @else


                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                                <div class="md:col-span-2">

                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Field
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="field_label"
                                                        value="{{ $field->field_label }}"
                                                        required
                                                        class="w-full rounded-xl border-gray-300">

                                                </div>

                                                <div>

                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Type
                                                    </label>

                                                    <select
                                                        name="field_type"
                                                        required
                                                        data-options-target="edit-options-{{ $field->fieldID }}"
                                                        onchange="toggleOptions(this)"
                                                        class="w-full rounded-xl border-gray-300">

                                                        <option value="display" {{ $field->field_type === 'display' ? 'selected' : '' }}>
                                                            Display
                                                        </option>

                                                        <option value="text" {{ $field->field_type === 'text' ? 'selected' : '' }}>
                                                            Text
                                                        </option>

                                                        <option value="textarea" {{ $field->field_type === 'textarea' ? 'selected' : '' }}>
                                                            Textarea
                                                        </option>

                                                        <option value="checkbox" {{ $field->field_type === 'checkbox' ? 'selected' : '' }}>
                                                            Checkbox
                                                        </option>

                                                        <option value="radio" {{ $field->field_type === 'radio' ? 'selected' : '' }}>
                                                            Radio
                                                        </option>

                                                    </select>

                                                </div>

                                                <div>

                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Required
                                                    </label>

                                                    <select
                                                        name="is_required"
                                                        class="w-full rounded-xl border-gray-300">

                                                        <option value="1" {{ $field->is_required ? 'selected' : '' }}>
                                                            Yes
                                                        </option>

                                                        <option value="0" {{ !$field->is_required ? 'selected' : '' }}>
                                                            No
                                                        </option>

                                                    </select>

                                                </div>


                                                <div
                                                    id="edit-options-{{ $field->fieldID }}"
                                                    class="md:col-span-2 {{ in_array($field->field_type, ['checkbox', 'radio']) ? '' : 'hidden' }}">

                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Options
                                                    </label>

                                                    <div
                                                        id="edit-option-list-{{ $field->fieldID }}"
                                                        class="space-y-2">

                                                        @forelse($field->options as $option)

                                                        <div class="flex items-center gap-2">

                                                            <input
                                                                type="text"
                                                                name="options[]"
                                                                value="{{ $option->option_label }}"
                                                                class="flex-1 rounded-xl border-gray-300">

                                                            <button
                                                                type="button"
                                                                onclick="removeOption(this)"
                                                                class="w-10 h-10 bg-red-100 hover:bg-red-200 text-red-600 rounded-xl">

                                                                ×

                                                            </button>

                                                        </div>

                                                        @empty

                                                        <div class="flex items-center gap-2">

                                                            <input
                                                                type="text"
                                                                name="options[]"
                                                                placeholder="Option"
                                                                class="flex-1 rounded-xl border-gray-300">

                                                            <button
                                                                type="button"
                                                                onclick="removeOption(this)"
                                                                class="w-10 h-10 bg-red-100 hover:bg-red-200 text-red-600 rounded-xl">

                                                                ×

                                                            </button>

                                                        </div>

                                                        @endforelse

                                                    </div>

                                                    <button
                                                        type="button"
                                                        data-option-list="edit-option-list-{{ $field->fieldID }}"
                                                        onclick="addOption(this)"
                                                        class="mt-3 text-sm text-blue-700 font-semibold">

                                                        + Add Option

                                                    </button>

                                                </div>

                                            </div>

                                            <div class="flex justify-end mt-5">

                                                <button
                                                    type="submit"
                                                    class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white rounded-xl text-sm font-semibold">

                                                    Update

                                                </button>

                                            </div>

                                            @endif

                                        </form>

                                    </td>

                                </tr>

                                @empty

                                <tr>

                                    <td colspan="5" class="py-10 text-center text-gray-400">
                                        No fields added
                                    </td>

                                </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    @if(!$formUsed)

                    <div class="px-6 py-5 border-t border-gray-100">

                        <details>

                            <summary class="cursor-pointer inline-flex items-center gap-2 text-blue-700 hover:text-blue-800 font-semibold text-sm">

                                <span class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center">
                                    +
                                </span>

                                Add Field

                            </summary>

                            <form
                                method="POST"
                                action="{{ route('admin.post.field.store') }}"
                                class="mt-5">

                                @csrf

                                <input
                                    type="hidden"
                                    name="sectionID"
                                    value="{{ $section->sectionID }}">

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                    <div class="md:col-span-2">

                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Field
                                        </label>

                                        <input
                                            type="text"
                                            name="field_label"
                                            required
                                            placeholder="Enter field label"
                                            class="w-full rounded-xl border-gray-300">

                                    </div>

                                    <div>

                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Type
                                        </label>

                                        <select
                                            name="field_type"
                                            required
                                            data-options-target="add-options-{{ $section->sectionID }}"
                                            onchange="toggleOptions(this)"
                                            class="w-full rounded-xl border-gray-300">

                                            <option value="display">Display</option>
                                            <option value="text">Text</option>
                                            <option value="textarea">Textarea</option>
                                            <option value="checkbox">Checkbox</option>
                                            <option value="radio">Radio</option>

                                        </select>

                                    </div>

                                    <div>

                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Required
                                        </label>

                                        <select
                                            name="is_required"
                                            class="w-full rounded-xl border-gray-300">

                                            <option value="0">No</option>
                                            <option value="1">Yes</option>

                                        </select>

                                    </div>


                                    <div
                                        id="add-options-{{ $section->sectionID }}"
                                        class="hidden md:col-span-2">

                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Options
                                        </label>

                                        <div
                                            id="add-option-list-{{ $section->sectionID }}"
                                            class="space-y-2">

                                            <div class="flex items-center gap-2">

                                                <input
                                                    type="text"
                                                    name="options[]"
                                                    placeholder="Option"
                                                    class="flex-1 rounded-xl border-gray-300">

                                                <button
                                                    type="button"
                                                    onclick="removeOption(this)"
                                                    class="w-10 h-10 bg-red-100 hover:bg-red-200 text-red-600 rounded-xl">

                                                    ×

                                                </button>

                                            </div>

                                        </div>

                                        <button
                                            type="button"
                                            data-option-list="add-option-list-{{ $section->sectionID }}"
                                            onclick="addOption(this)"
                                            class="mt-3 text-sm text-blue-700 font-semibold">

                                            + Add Option

                                        </button>

                                    </div>

                                </div>

                                <div class="flex justify-end mt-5">

                                    <button
                                        type="submit"
                                        class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white rounded-xl text-sm font-semibold">

                                        Add

                                    </button>

                                </div>

                            </form>

                        </details>

                    </div>

                    @endif

                </div>

                @empty

                <div class="bg-white rounded-3xl shadow-sm border border-gray-200 py-12 text-center text-gray-400">
                    No sections added
                </div>

                @endforelse

            </div>


            @if(!$formUsed)

            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden mb-28">

                <div class="px-6 py-4 border-b border-gray-100">

                    <h2 class="text-lg font-bold text-gray-900">
                        Add New Section
                    </h2>

                    <p class="text-sm text-gray-400 mt-1">
                        Add another section to this form version
                    </p>

                </div>

                <div class="p-6">

                    <form method="POST" action="{{ route('admin.post.section.store') }}">

                        @csrf

                        <input
                            type="hidden"
                            name="formID"
                            value="{{ $form->formID }}">

                        <div class="flex flex-col md:flex-row gap-4 items-end">

                            <div class="flex-1">

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Section Name
                                </label>

                                <input
                                    type="text"
                                    name="section_name"
                                    required
                                    placeholder="Enter section name"
                                    class="w-full rounded-xl border-gray-300">

                            </div>

                            <button
                                type="submit"
                                class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl">

                                Add

                            </button>

                        </div>

                    </form>

                </div>

            </div>

            @else

            <div class="mb-24"></div>

            @endif

        </div>
    </div>


    <div class="fixed bottom-4 left-0 right-0 z-40 px-6 pointer-events-none">

        <div class="max-w-7xl mx-auto">

            <div class="bg-white/95 backdrop-blur-md border border-gray-200 shadow-xl rounded-2xl px-6 py-4 pointer-events-auto">

                <div class="flex items-center justify-between gap-3">

                    <a
                        href="{{ route('admin.post.form') }}"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition">

                        Back

                    </a>

                    <div class="flex items-center gap-3">

                        <a
                            href="{{ route('admin.post.form.show', $form->formID) }}"
                            class="px-5 py-2.5 bg-sky-100 hover:bg-sky-200 text-sky-800 font-semibold text-sm rounded-xl transition">

                            Preview

                        </a>

                        <button
                            type="submit"
                            form="formInformation"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-200 text-white font-semibold text-sm rounded-xl transition">

                            Save

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>
        function toggleSectionEdit(button) {
            const row = document.getElementById('section-edit-' + button.dataset.sectionId);
            if (row) row.classList.toggle('hidden');
        }

        function toggleFieldEdit(button) {
            const row = document.getElementById('field-edit-' + button.dataset.fieldId);
            if (row) row.classList.toggle('hidden');
        }

        function toggleOptions(select) {
            const element = document.getElementById(select.dataset.optionsTarget);

            if (!element) return;

            if (select.value === 'checkbox' || select.value === 'radio') {
                element.classList.remove('hidden');
            } else {
                element.classList.add('hidden');
            }
        }

        function addOption(button) {
            const list = document.getElementById(button.dataset.optionList);

            if (!list) return;

            const row = document.createElement('div');
            row.className = 'flex items-center gap-2';

            row.innerHTML = `
                <input
                    type="text"
                    name="options[]"
                    placeholder="Option"
                    class="flex-1 rounded-xl border-gray-300">

                <button
                    type="button"
                    onclick="removeOption(this)"
                    class="w-10 h-10 bg-red-100 hover:bg-red-200 text-red-600 rounded-xl">
                    ×
                </button>
            `;

            list.appendChild(row);
        }

        function removeOption(button) {
            const row = button.parentElement;
            if (row) row.remove();
        }
    </script>

</x-app-layout>