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

                            <label class="block text-sm text-gray-700 font-semibold mb-2">
                                Status
                            </label>

                            <select
                                name="status"
                                required
                                class="w-48 rounded-xl border-gray-300">

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

                    </div>


                    <div class="flex justify-center items-center gap-4 mt-10">

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


            @else

            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-6">

                <div class="px-6 py-4 border-b border-gray-100">

                    <h2 class="text-lg font-bold text-gray-800">
                        Form Information
                    </h2>

                    <p class="text-sm text-gray-400 mt-1">
                        Update the main information displayed on the form
                    </p>

                </div>

                <div class="px-6 py-5">

                    <form method="POST"
                        action="{{ route('admin.post.form.update', $form->formID) }}">

                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-[1fr_180px_1fr] gap-4">

                            {{-- FORM NAME --}}
                            <div>

                                <label class="block text-sm text-gray-700 font-semibold mb-2">
                                    Form Name
                                </label>

                                <input
                                    type="text"
                                    name="form_name"
                                    value="{{ old('form_name', $form->form_name) }}"
                                    required
                                    class="w-full rounded-xl border-gray-300">

                            </div>

                            {{-- STATUS --}}
                            <div>

                                <label class="block text-sm text-gray-700 font-semibold mb-2">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    required
                                    class="w-full rounded-xl border-gray-300">

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

                            {{-- INSTRUCTION --}}
                            <div>

                                <label class="block text-sm text-gray-700 font-semibold mb-2">
                                    Instruction
                                </label>

                                <input
                                    type="text"
                                    name="instruction"
                                    value="{{ old('instruction', $form->instruction) }}"
                                    class="w-full rounded-xl border-gray-300">

                            </div>

                        </div>

                        <div class="flex justify-end items-center gap-3 mt-5">

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
                                Save
                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <div class="mb-8">

                <div class="mb-5 flex items-end justify-between">

                    <div>

                        <h2 class="text-xl font-bold text-gray-800">
                            Form Sections
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Drag sections and fields to change their display order
                        </p>

                    </div>

                    <div class="flex items-center gap-2 text-sm text-gray-500">

                        <span class="font-bold text-gray-700">
                            ☰
                        </span>
                        Drag to reorder
                    </div>

                </div>

                <div id="section-list">

                    @forelse($form->sections as $section)

                    <div
                        class="section-item bg-white rounded-3xl
                                       shadow-lg overflow-hidden mb-6"
                        data-section-id="{{ $section->sectionID }}">

                        <div class="px-8 py-5 border-b border-gray-100
                                            flex items-center justify-between">

                            <div class="flex items-center gap-4">

                                <button
                                    type="button"
                                    draggable="true"
                                    class="section-drag-handle
                                                   cursor-grab active:cursor-grabbing
                                                   text-gray-400 hover:text-purple-700
                                                   text-2xl font-bold select-none"
                                    title="Drag section">
                                    ☰
                                </button>

                                <div>

                                    <p class="section-number
                                                      text-xs font-bold uppercase
                                                      tracking-wider text-purple-600">
                                        Section {{ $section->display_order }}
                                    </p>

                                    <h3 class="text-xl font-bold text-gray-800 mt-1">
                                        {{ $section->section_name }}
                                    </h3>

                                </div>

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

                                <form method="POST" action="{{ route('admin.post.section.delete', $section->sectionID) }}">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        onclick="return confirm(
                                                    'Delete this section and all its fields?'
                                                )"
                                        class="inline-flex items-center justify-center
                                                       w-10 h-10
                                                       bg-red-500 hover:bg-red-600
                                                       text-white rounded-xl transition">

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

                            <form
                                method="POST"
                                action="{{ route(
                                            'admin.post.section.update',
                                            $section->sectionID
                                        ) }}">

                                @csrf
                                @method('PUT')


                                <label
                                    class="block text-sm font-semibold
                                                   text-gray-700 mb-2">
                                    Section Name
                                </label>

                                <input
                                    type="text"
                                    name="section_name"
                                    value="{{ $section->section_name }}"
                                    required
                                    class="w-full rounded-xl border-gray-300">

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
                                                      text-gray-500
                                                      uppercase text-xs">

                                    <tr>
                                        <th class="px-4 py-4 text-center w-16">
                                        </th>

                                        <th class="px-6 py-4 text-left"> No </th>
                                        <th class="px-6 py-4 text-left"> Field Label </th>
                                        <th class="px-6 py-4 text-center"> Type </th>
                                        <th class="px-6 py-4 text-center"> Required </th>
                                        <th class="px-6 py-4 text-center"> Status </th>
                                        <th class="px-6 py-4 text-center"> Action </th>
                                    </tr>

                                </thead>

                                <tbody
                                    class="field-list divide-y divide-gray-100"
                                    data-section-id="{{ $section->sectionID }}">

                                    @forelse($section->fields as $field)

                                    <tr
                                        class="field-item hover:bg-violet-50/50 transition"
                                        data-field-id="{{ $field->fieldID }}">

                                        <td class="px-4 py-5 text-center">

                                            <button
                                                type="button"
                                                draggable="true"
                                                class="field-drag-handle
                                                                   cursor-grab
                                                                   active:cursor-grabbing
                                                                   text-gray-400
                                                                   hover:text-purple-700
                                                                   text-xl font-bold
                                                                   select-none"
                                                title="Drag field">
                                                ☰
                                            </button>

                                        </td>


                                        <td class="field-number px-6 py-5 text-gray-600">
                                            {{ $field->display_order }}
                                        </td>

                                        <td class="px-6 py-5
                                                               font-semibold text-gray-800">

                                            {{ $field->field_label }}


                                            @if(
                                            in_array(
                                            $field->field_type,
                                            ['checkbox', 'radio']
                                            )
                                            )

                                            @if($field->options->isNotEmpty())

                                            <div class="mt-2 space-y-1">

                                                @foreach($field->options as $option)

                                                <p class="text-xs
                                                                                  font-normal
                                                                                  text-gray-400">

                                                    {{ $loop->iteration }}.
                                                    {{ $option->option_label }}

                                                </p>

                                                @endforeach

                                            </div>

                                            @endif

                                            @endif

                                        </td>


                                        <td class="px-6 py-5 text-center">

                                            @if($field->field_type === 'textarea')

                                            Text Area

                                            @elseif($field->field_type === 'radio')

                                            Radio Button

                                            @else

                                            {{ ucfirst($field->field_type) }}

                                            @endif

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

                                            <div class="inline-flex items-center gap-2">

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


                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                                    'admin.post.field.delete',
                                                                    $field->fieldID
                                                                ) }}">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        onclick="return confirm(
                                                                        'Delete this field?'
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

                                        <td colspan="7" class="p-6">

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                                'admin.post.field.update',
                                                                $field->fieldID
                                                            ) }}"
                                                class="field-form">

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
                                                            class="field-type
                                                                               w-full rounded-xl
                                                                               border-gray-300">

                                                            <option
                                                                value="display"
                                                                {{ $field->field_type === 'display'
                                                                                ? 'selected'
                                                                                : '' }}>
                                                                Display
                                                            </option>

                                                            <option
                                                                value="text"
                                                                {{ $field->field_type === 'text'
                                                                                ? 'selected'
                                                                                : '' }}>
                                                                Text
                                                            </option>

                                                            <option
                                                                value="textarea"
                                                                {{ $field->field_type === 'textarea'
                                                                                ? 'selected'
                                                                                : '' }}>
                                                                Text Area
                                                            </option>

                                                            <option
                                                                value="checkbox"
                                                                {{ $field->field_type === 'checkbox'
                                                                                ? 'selected'
                                                                                : '' }}>
                                                                Checkbox
                                                            </option>

                                                            <option
                                                                value="radio"
                                                                {{ $field->field_type === 'radio'
                                                                                ? 'selected'
                                                                                : '' }}>
                                                                Radio Button
                                                            </option>

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
                                                            class="required-select
                                                                               w-full rounded-xl
                                                                               border-gray-300">

                                                            <option
                                                                value="1"
                                                                {{ $field->is_required
                                                                                ? 'selected'
                                                                                : '' }}>

                                                                Yes

                                                            </option>

                                                            <option
                                                                value="0"
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

                                                            <option
                                                                value="Active"
                                                                {{ $field->status === 'Active'
                                                                                ? 'selected'
                                                                                : '' }}>

                                                                Active

                                                            </option>

                                                            <option
                                                                value="Inactive"
                                                                {{ $field->status === 'Inactive'
                                                                                ? 'selected'
                                                                                : '' }}>

                                                                Inactive

                                                            </option>

                                                        </select>

                                                    </div>

                                                </div>



                                                <div
                                                    class="options-area mt-6
                                                                       {{ in_array(
                                                                            $field->field_type,
                                                                            ['checkbox', 'radio']
                                                                        )
                                                                            ? ''
                                                                            : 'hidden' }}">

                                                    <div class="flex items-center
                                                                            justify-between mb-3">

                                                        <div>

                                                            <h4 class="font-semibold text-gray-800">
                                                                Options
                                                            </h4>

                                                            <p class="text-xs text-gray-400 mt-1">
                                                                Add the choices displayed for this field
                                                            </p>

                                                        </div>


                                                        <button
                                                            type="button"
                                                            onclick="addOption(this)"
                                                            class="px-4 py-2
                                                                               bg-purple-100
                                                                               hover:bg-purple-200
                                                                               text-purple-700
                                                                               rounded-xl
                                                                               text-sm
                                                                               font-semibold">

                                                            + Add Option

                                                        </button>

                                                    </div>


                                                    <div class="option-list space-y-3">

                                                        @forelse($field->options as $option)

                                                        <div class="option-row
                                                                                    flex items-center gap-3">

                                                            <input
                                                                type="text"
                                                                name="options[]"
                                                                value="{{ $option->option_label }}"
                                                                placeholder="Enter option"
                                                                class="flex-1 rounded-xl
                                                                                       border-gray-300">

                                                            <button
                                                                type="button"
                                                                onclick="removeOption(this)"
                                                                class="px-3 py-2
                                                                                       bg-red-100
                                                                                       hover:bg-red-200
                                                                                       text-red-700
                                                                                       rounded-xl
                                                                                       text-sm font-semibold">

                                                                Remove

                                                            </button>

                                                        </div>

                                                        @empty

                                                        <div class="option-row
                                                                                    flex items-center gap-3">

                                                            <input
                                                                type="text"
                                                                name="options[]"
                                                                placeholder="Enter option"
                                                                class="flex-1 rounded-xl
                                                                                       border-gray-300">

                                                            <button
                                                                type="button"
                                                                onclick="removeOption(this)"
                                                                class="px-3 py-2
                                                                                       bg-red-100
                                                                                       hover:bg-red-200
                                                                                       text-red-700
                                                                                       rounded-xl
                                                                                       text-sm font-semibold">

                                                                Remove

                                                            </button>

                                                        </div>

                                                        @endforelse

                                                    </div>

                                                </div>


                                                <div class="flex justify-end mt-5">

                                                    <button
                                                        type="submit"
                                                        class="px-4 py-2
                                                                           bg-blue-700
                                                                           hover:bg-blue-800
                                                                           text-white rounded-xl
                                                                           text-sm font-semibold">

                                                        Update Field

                                                    </button>

                                                </div>

                                            </form>

                                        </td>

                                    </tr>


                                    @empty

                                    <tr>

                                        <td
                                            colspan="7"
                                            class="text-center py-8 text-gray-400">

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


                                <form
                                    method="POST"
                                    action="{{ route('admin.post.field.store') }}"
                                    class="field-form mt-6">

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
                                                class="field-type
                                                               w-full rounded-xl
                                                               border-gray-300">

                                                <option value="display">
                                                    Display
                                                </option>

                                                <option value="text">
                                                    Text
                                                </option>

                                                <option value="textarea">
                                                    Text Area
                                                </option>

                                                <option value="checkbox">
                                                    Checkbox
                                                </option>

                                                <option value="radio">
                                                    Radio Button
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
                                                required
                                                class="required-select
                                                               w-full rounded-xl
                                                               border-gray-300">

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



                                    <div class="options-area hidden mt-6">

                                        <div class="flex items-center
                                                            justify-between mb-3">

                                            <div>

                                                <h4 class="font-semibold text-gray-800">
                                                    Options
                                                </h4>

                                                <p class="text-xs text-gray-400 mt-1">
                                                    Add the choices displayed for this field
                                                </p>

                                            </div>


                                            <button
                                                type="button"
                                                onclick="addOption(this)"
                                                class="px-4 py-2
                                                               bg-purple-100
                                                               hover:bg-purple-200
                                                               text-purple-700
                                                               rounded-xl
                                                               text-sm font-semibold">

                                                + Add Option

                                            </button>

                                        </div>


                                        <div class="option-list space-y-3">

                                            <div class="option-row
                                                                flex items-center gap-3">

                                                <input
                                                    type="text"
                                                    name="options[]"
                                                    placeholder="Enter option"
                                                    class="flex-1 rounded-xl
                                                                   border-gray-300">

                                                <button
                                                    type="button"
                                                    onclick="removeOption(this)"
                                                    class="px-3 py-2
                                                                   bg-red-100
                                                                   hover:bg-red-200
                                                                   text-red-700
                                                                   rounded-xl
                                                                   text-sm font-semibold">

                                                    Remove

                                                </button>

                                            </div>

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

            </div>



            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                <div class="px-8 py-6 border-b border-gray-100">

                    <h2 class="text-xl font-bold text-gray-800">
                        Add New Section
                    </h2>

                    <p class="text-sm text-gray-400 mt-1">
                        New sections are automatically added to the bottom
                    </p>

                </div>


                <div class="p-8">

                    <form
                        method="POST"
                        action="{{ route('admin.post.section.store') }}">

                        @csrf

                        <input
                            type="hidden"
                            name="formID"
                            value="{{ $form->formID }}">


                        <label
                            for="section_name"
                            class="block text-gray-700 font-semibold mb-2">

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

        function handleFieldType(select) {
            const form = select.closest('.field-form');

            if (!form) {
                return;
            }

            const requiredSelect = form.querySelector('.required-select');

            const optionsArea = form.querySelector('.options-area');

            if (select.value === 'display') {
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

            if (
                select.value === 'checkbox' ||
                select.value === 'radio'
            ) {
                optionsArea.classList.remove('hidden');
            } else {
                optionsArea.classList.add('hidden');
            }
        }


        function addOption(button) {
            const optionsArea =
                button.closest('.options-area');

            const optionList =
                optionsArea.querySelector('.option-list');

            const row = document.createElement('div');

            row.className =
                'option-row flex items-center gap-3';

            row.innerHTML = `
                <input
                    type="text"
                    name="options[]"
                    placeholder="Enter option"
                    class="flex-1 rounded-xl border-gray-300">

                <button
                    type="button"
                    onclick="removeOption(this)"
                    class="px-3 py-2
                           bg-red-100 hover:bg-red-200
                           text-red-700 rounded-xl
                           text-sm font-semibold">
                    Remove
                </button>
            `;

            optionList.appendChild(row);
        }


        function removeOption(button) {
            const row =
                button.closest('.option-row');

            const optionList =
                row.closest('.option-list');

            if (
                optionList.querySelectorAll(
                    '.option-row'
                ).length === 1
            ) {
                const input =
                    row.querySelector('input');

                input.value = '';

                return;
            }

            row.remove();
        }


        function updateSectionNumbers() {
            document
                .querySelectorAll(
                    '#section-list .section-item'
                )
                .forEach(function(item, index) {

                    const number =
                        item.querySelector(
                            '.section-number'
                        );

                    if (number) {
                        number.textContent =
                            'Section ' + (index + 1);
                    }
                });
        }


        function updateFieldNumbers(container) {
            container
                .querySelectorAll(
                    '.field-item'
                )
                .forEach(function(item, index) {

                    const number =
                        item.querySelector(
                            '.field-number'
                        );

                    if (number) {
                        number.textContent =
                            index + 1;
                    }
                });
        }


        async function saveSectionOrder() {
            const sections = Array.from(
                document.querySelectorAll(
                    '#section-list .section-item'
                )
            ).map(function(item) {
                return item.dataset.sectionId;
            });

            const response = await fetch(
                "{{ route('admin.post.sections.reorder') }}", {
                    method: 'PUT',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },

                    body: JSON.stringify({
                        sections: sections
                    })
                }
            );

            if (!response.ok) {
                alert(
                    'Unable to update section order.'
                );
            }
        }


        async function saveFieldOrder(container) {
            const fields = Array.from(
                container.querySelectorAll(
                    '.field-item'
                )
            ).map(function(item) {
                return item.dataset.fieldId;
            });

            if (fields.length === 0) {
                return;
            }

            const response = await fetch(
                "{{ route('admin.post.fields.reorder') }}", {
                    method: 'PUT',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },

                    body: JSON.stringify({
                        fields: fields
                    })
                }
            );

            if (!response.ok) {
                alert(
                    'Unable to update field order.'
                );
            }
        }


        document.addEventListener(
            'DOMContentLoaded',
            function() {

                document
                    .querySelectorAll(
                        '.field-type'
                    )
                    .forEach(function(select) {
                        handleFieldType(select);
                    });


                const sectionList =
                    document.getElementById(
                        'section-list'
                    );

                let draggedSection = null;


                document
                    .querySelectorAll(
                        '.section-drag-handle'
                    )
                    .forEach(function(handle) {

                        handle.addEventListener(
                            'dragstart',
                            function(event) {

                                draggedSection =
                                    handle.closest(
                                        '.section-item'
                                    );

                                draggedSection.classList.add(
                                    'opacity-50'
                                );

                                event.dataTransfer.effectAllowed =
                                    'move';
                            }
                        );


                        handle.addEventListener(
                            'dragend',
                            async function() {

                                if (draggedSection) {
                                    draggedSection.classList.remove(
                                        'opacity-50'
                                    );
                                }

                                draggedSection = null;

                                updateSectionNumbers();

                                await saveSectionOrder();
                            }
                        );
                    });


                if (sectionList) {

                    sectionList.addEventListener(
                        'dragover',
                        function(event) {

                            if (!draggedSection) {
                                return;
                            }

                            event.preventDefault();

                            const afterElement =
                                getDragAfterElement(
                                    sectionList,
                                    event.clientY,
                                    '.section-item',
                                    draggedSection
                                );

                            if (afterElement == null) {
                                sectionList.appendChild(
                                    draggedSection
                                );
                            } else {
                                sectionList.insertBefore(
                                    draggedSection,
                                    afterElement
                                );
                            }
                        }
                    );
                }



                document
                    .querySelectorAll(
                        '.field-list'
                    )
                    .forEach(function(fieldList) {

                        let draggedField = null;


                        fieldList
                            .querySelectorAll(
                                '.field-drag-handle'
                            )
                            .forEach(function(handle) {

                                handle.addEventListener(
                                    'dragstart',
                                    function(event) {

                                        draggedField =
                                            handle.closest(
                                                '.field-item'
                                            );

                                        draggedField.classList.add(
                                            'opacity-50'
                                        );

                                        event.dataTransfer.effectAllowed =
                                            'move';
                                    }
                                );


                                handle.addEventListener(
                                    'dragend',
                                    async function() {

                                        if (draggedField) {
                                            draggedField.classList.remove(
                                                'opacity-50'
                                            );
                                        }

                                        draggedField = null;

                                        updateFieldNumbers(
                                            fieldList
                                        );

                                        await saveFieldOrder(
                                            fieldList
                                        );
                                    }
                                );
                            });


                        fieldList.addEventListener(
                            'dragover',
                            function(event) {

                                if (!draggedField) {
                                    return;
                                }

                                event.preventDefault();

                                const afterElement =
                                    getDragAfterElement(
                                        fieldList,
                                        event.clientY,
                                        '.field-item',
                                        draggedField
                                    );

                                if (afterElement == null) {
                                    fieldList.appendChild(
                                        draggedField
                                    );
                                } else {
                                    fieldList.insertBefore(
                                        draggedField,
                                        afterElement
                                    );
                                }
                            }
                        );
                    });

            }
        );

        function getDragAfterElement(
            container,
            y,
            selector,
            dragging
        ) {
            const elements = [
                ...container.querySelectorAll(
                    selector
                )
            ].filter(function(element) {
                return element !== dragging;
            });

            return elements.reduce(
                function(closest, child) {

                    const box =
                        child.getBoundingClientRect();

                    const offset =
                        y - box.top -
                        box.height / 2;

                    if (
                        offset < 0 &&
                        offset > closest.offset
                    ) {
                        return {
                            offset: offset,
                            element: child
                        };
                    }

                    return closest;
                }, {
                    offset: Number.NEGATIVE_INFINITY,
                    element: null
                }
            ).element;
        }
    </script>

</x-app-layout>