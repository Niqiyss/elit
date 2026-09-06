<x-app-layout>

    <div class="min-h-screen bg-slate-100 py-8 px-6">

        <div class="max-w-7xl mx-auto">

            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">
                    <h1 class="text-3xl font-extrabold text-white">
                        {{ $form->form_name }}
                    </h1>

                    @if($form->instruction)
                    <p class="text-violet-300 mt-2">
                        {{ $form->instruction }}
                    </p>
                    @endif
                </div>

            </div>


            {{-- Messages --}}
            @if(session('success'))
            <div class="mb-6 px-5 py-4 bg-green-100 border border-green-200 text-green-700 rounded-xl">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 px-5 py-4 bg-red-100 border border-red-200 text-red-700 rounded-xl">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <form
                method="POST"
                action="{{ $role === 'observer'
                    ? route('observer.post.store', $gn_id)
                    : route('external.post.store', $gn_id) }}">

                @csrf

                {{-- Exact form version --}}
                <input type="hidden" name="formID" value="{{ $form->formID }}">


                <div class="bg-white rounded-3xl shadow-lg px-6 py-5 mb-8">

                    <h2 class="text-lg font-bold text-slate-900 mb-4">
                        Maklumat Penyeliaan
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pb-4 mb-4 border-b border-slate-200">

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">
                                Nama Guru
                            </p>

                            <p class="font-bold text-slate-800 uppercase">
                                {{ $guru->gn_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">
                                Sekolah
                            </p>

                            <p class="font-bold text-slate-800">
                                {{ $guru->school?->school_name ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">
                                Penyelia
                            </p>

                            <p class="font-bold text-slate-800 uppercase">
                                {{ Auth::guard('teacher')->user()->teacher_name }}
                            </p>
                        </div>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

                        <div>
                            <label
                                for="class_name"
                                class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Kelas
                            </label>

                            <input
                                type="text"
                                id="class_name"
                                name="class_name"
                                value="{{ old('class_name') }}"
                                class="w-full rounded-xl border-slate-300 py-2 focus:border-purple-500 focus:ring-purple-500">

                            @error('class_name')
                            <p class="text-red-500 text-xs mt-1">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>


                        <div>
                            <label
                                for="subject_name"
                                class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Subjek
                            </label>

                            <input
                                type="text"
                                id="subject_name"
                                name="subject_name"
                                value="{{ old('subject_name') }}"
                                class="w-full rounded-xl border-slate-300 py-2 focus:border-purple-500 focus:ring-purple-500">

                            @error('subject_name')
                            <p class="text-red-500 text-xs mt-1">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>


                        <div>
                            <label
                                for="observation_date"
                                class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Tarikh
                            </label>

                            <input
                                type="date"
                                id="observation_date"
                                name="observation_date"
                                value="{{ old('observation_date') }}"
                                class="w-full rounded-xl border-slate-300 py-2 focus:border-purple-500 focus:ring-purple-500">

                            @error('observation_date')
                            <p class="text-red-500 text-xs mt-1">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>


                        <div>
                            <label
                                for="observation_time"
                                class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Masa
                            </label>

                            <input
                                type="time"
                                id="observation_time"
                                name="observation_time"
                                value="{{ old('observation_time') }}"
                                class="w-full rounded-xl border-slate-300 py-2 focus:border-purple-500 focus:ring-purple-500">

                            @error('observation_time')
                            <p class="text-red-500 text-xs mt-1">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                    </div>

                </div>


                {{-- Dynamic Sections --}}
                @foreach($form->sections as $section)

                @php
                $displayFields = $section->fields->where('field_type', 'display')->values();
                $inputFields = $section->fields->where('field_type', '!=', 'display')->values();
                @endphp


                {{-- Display Fields --}}
                @if($displayFields->isNotEmpty())

                <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-6">

                    <div class="bg-blue-900 px-6 py-3">
                        <h2 class="text-sm font-bold text-white uppercase tracking-wide">
                            {{ $section->section_name }}
                        </h2>
                    </div>

                    <div class="overflow-x-auto">

                        <table class="w-full table-fixed text-sm">

                            <tbody>

                                @foreach($displayFields->chunk(4) as $row)

                                <tr>

                                    @foreach($row as $field)

                                    <td class="border-r border-b border-slate-200 px-5 py-3 text-slate-700 font-medium align-middle {{
                                                        $loop->last && $row->count() === 4
                                                            ? 'border-r-0'
                                                            : ''
                                                    }}">
                                        {{ $field->field_label }}
                                    </td>

                                    @endforeach


                                    {{-- Fill Empty Columns --}}
                                    @if($row->count() < 4)

                                        @for($i=$row->count(); $i < 4; $i++)

                                            <td class="border-r border-b border-slate-200 px-5 py-3 {{ $i === 3 ? 'border-r-0' : '' }}">
                                            </td>

                                            @endfor

                                            @endif

                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

                @endif


                {{-- Input Fields --}}
                @if($inputFields->isNotEmpty())

                <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">

                    <div class="bg-blue-900 px-6 py-3">
                        <h2 class="text-sm font-bold text-white uppercase tracking-wide">
                            {{ $section->section_name }}
                        </h2>
                    </div>


                    <div class="divide-y divide-slate-200">

                        @foreach($inputFields as $field)


                        {{-- Textarea --}}
                        @if($field->field_type === 'textarea')

                        <div class="px-6 py-4">

                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-start">

                                <div class="md:col-span-1">

                                    <label class="block text-sm text-slate-700 font-semibold pt-2">
                                        {{ $field->field_label }}

                                        @if($field->is_required)
                                        <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                </div>


                                <div class="md:col-span-5">

                                    <textarea
                                        name="answers[{{ $field->fieldID }}]"
                                        rows="3"
                                        class="{{ $field->is_required ? 'post-required-field' : '' }} w-full rounded-xl border-slate-300 focus:border-purple-500 focus:ring-purple-500">{{ old('answers.' . $field->fieldID) }}</textarea>

                                    @error('answers.' . $field->fieldID)
                                    <p class="text-red-500 text-xs mt-1">
                                        {{ $message }}
                                    </p>
                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- Checkbox --}}
                        @elseif($field->field_type === 'checkbox')

                        <div class="px-6 py-4">

                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-start">

                                <div class="md:col-span-1">

                                    <label class="block text-sm text-slate-700 font-semibold">
                                        {{ $field->field_label }}

                                        @if($field->is_required)
                                        <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                </div>


                                <div class="md:col-span-5">

                                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2">

                                        @foreach($field->options as $option)

                                        <label class="inline-flex items-center gap-2 cursor-pointer">

                                            <input
                                                type="checkbox"
                                                name="answers[{{ $field->fieldID }}][]"
                                                value="{{ $option->option_label }}"
                                                {{ in_array($option->option_label, old('answers.' . $field->fieldID, [])) ? 'checked' : '' }}
                                                class="{{ $field->is_required ? 'post-required-field' : '' }} rounded border-slate-300 text-blue-600 focus:ring-blue-500">

                                            <span class="text-sm text-slate-700">
                                                {{ $option->option_label }}
                                            </span>

                                        </label>

                                        @endforeach

                                    </div>

                                    @error('answers.' . $field->fieldID)
                                    <p class="text-red-500 text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- Radio --}}
                        @elseif($field->field_type === 'radio')

                        <div class="px-6 py-4">

                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-start">

                                <div class="md:col-span-1">

                                    <label class="block text-sm text-slate-700 font-semibold">
                                        {{ $field->field_label }}

                                        @if($field->is_required)
                                        <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                </div>


                                <div class="md:col-span-5">

                                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2">

                                        @foreach($field->options as $option)

                                        <label class="inline-flex items-center gap-2 cursor-pointer">

                                            <input
                                                type="radio"
                                                name="answers[{{ $field->fieldID }}]"
                                                value="{{ $option->option_label }}"
                                                {{ old('answers.' . $field->fieldID) === $option->option_label ? 'checked' : '' }}
                                                class="{{ $field->is_required ? 'post-required-field' : '' }} border-slate-300 text-blue-600 focus:ring-blue-500">

                                            <span class="text-sm text-slate-700">
                                                {{ $option->option_label }}
                                            </span>

                                        </label>

                                        @endforeach

                                    </div>

                                    @error('answers.' . $field->fieldID)
                                    <p class="text-red-500 text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- Text --}}
                        @else

                        <div class="px-6 py-4">

                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center">

                                <label class="md:col-span-1 text-sm text-slate-700 font-semibold">
                                    {{ $field->field_label }}

                                    @if($field->is_required)
                                    <span class="text-red-500">*</span>
                                    @endif
                                </label>


                                <div class="md:col-span-5">

                                    <input
                                        type="text"
                                        name="answers[{{ $field->fieldID }}]"
                                        value="{{ old('answers.' . $field->fieldID) }}"
                                        class="{{ $field->is_required ? 'post-required-field' : '' }} w-full rounded-xl border-slate-300 py-2 focus:border-purple-500 focus:ring-purple-500">

                                    @error('answers.' . $field->fieldID)
                                    <p class="text-red-500 text-xs mt-1">
                                        {{ $message }}
                                    </p>
                                    @enderror

                                </div>

                            </div>

                        </div>

                        @endif

                        @endforeach

                    </div>

                </div>

                @endif

                @endforeach


                {{-- Action Bar --}}
                <div class="sticky bottom-4 z-40 mt-8">

                    <div class="bg-white/95 backdrop-blur-sm border border-slate-200 shadow-xl rounded-2xl px-6 py-4">

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <div>
                                <p class="text-sm font-semibold text-slate-700">
                                    Save your progress to continue later
                                </p>
                            </div>


                            <div class="flex items-center gap-3 flex-shrink-0">

                                <a
                                    href="{{ route(
                                        $role === 'observer'
                                            ? 'observer.manage'
                                            : 'external.manage',
                                        $gn_id
                                    ) }}"
                                    class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm rounded-xl transition">
                                    Back
                                </a>

                                <button
                                    type="submit"
                                    name="submit_action"
                                    value="Draft"
                                    class="px-5 py-2 bg-white hover:bg-green-50 border border-green-400 text-slate-900 font-semibold text-sm rounded-xl transition">
                                    Save
                                </button>

                                <button
                                    type="submit"
                                    id="submitButton"
                                    name="submit_action"
                                    value="Submitted"
                                    disabled
                                    class="px-5 py-2 bg-slate-200 text-slate-400 font-semibold text-sm rounded-xl cursor-not-allowed transition">
                                    Submit
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const classInput = document.getElementById('class_name');
            const subjectInput = document.getElementById('subject_name');
            const dateInput = document.getElementById('observation_date');
            const timeInput = document.getElementById('observation_time');
            const submitButton = document.getElementById('submitButton');
            const requiredFields = document.querySelectorAll('.post-required-field');

            // Check whether all required fields are completed
            function updateSubmitButton() {

                const informationComplete =
                    classInput.value.trim() !== '' &&
                    subjectInput.value.trim() !== '' &&
                    dateInput.value !== '' &&
                    timeInput.value !== '';

                let dynamicFieldsComplete = true;
                const checkedGroups = new Set();

                requiredFields.forEach(function(field) {

                    // Check radio and checkbox groups
                    if (field.type === 'radio' || field.type === 'checkbox') {

                        if (checkedGroups.has(field.name)) return;

                        checkedGroups.add(field.name);

                        let groupComplete = false;

                        requiredFields.forEach(function(groupField) {
                            if (
                                groupField.name === field.name &&
                                groupField.checked
                            ) {
                                groupComplete = true;
                            }
                        });

                        if (!groupComplete) {
                            dynamicFieldsComplete = false;
                        }

                        return;
                    }

                    // Check text and textarea fields
                    if (field.value.trim() === '') {
                        dynamicFieldsComplete = false;
                    }
                });

                const formComplete =
                    informationComplete &&
                    dynamicFieldsComplete;

                // Enable or disable Submit
                if (formComplete) {

                    submitButton.disabled = false;

                    submitButton.className =
                        'px-5 py-2 bg-blue-700 hover:bg-blue-800 text-white font-semibold text-sm rounded-xl shadow-sm cursor-pointer transition';

                } else {

                    submitButton.disabled = true;

                    submitButton.className =
                        'px-5 py-2 bg-slate-200 text-slate-400 font-semibold text-sm rounded-xl cursor-not-allowed transition';
                }
            }

            // Listen to observation information
            classInput.addEventListener('input', updateSubmitButton);
            subjectInput.addEventListener('input', updateSubmitButton);
            dateInput.addEventListener('change', updateSubmitButton);
            timeInput.addEventListener('change', updateSubmitButton);

            // Listen to required dynamic fields
            requiredFields.forEach(function(field) {

                field.addEventListener(
                    field.type === 'radio' || field.type === 'checkbox' ?
                    'change' :
                    'input',
                    updateSubmitButton
                );
            });

            // Check form on initial load
            updateSubmitButton();

        });
    </script>

</x-app-layout>