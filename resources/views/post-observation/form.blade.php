<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('teacher')->user()->teacher_name }}
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
                        {{ $form->form_name }}
                    </h1>

                    @if($form->instruction)

                    <p class="text-violet-300 mt-2">
                        {{ $form->instruction }}
                    </p>

                    @endif

                </div>

            </div>


            @if(session('success'))

            <div class="mb-6 px-5 py-4
                            bg-green-100 border border-green-200
                            text-green-700 rounded-xl">
                {{ session('success') }}
            </div>

            @endif

            <form
                method="POST"
                action="{{ $role === 'observer'
                    ? route('observer.post.store', $gn_id)
                    : route('external.post.store', $gn_id) }}">

                @csrf

                <div class="bg-white rounded-3xl shadow-lg p-8 mb-8">

                    <h2 class="text-xl font-bold text-gray-800 mb-6">
                        Observation Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                Teacher
                            </label>

                            <input
                                type="text"
                                value="{{ $guru->gn_name }}"
                                readonly
                                class="w-full rounded-xl border-gray-300 bg-gray-100">
                        </div>


                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                Class
                            </label>

                            <input
                                type="text"
                                name="class_name"
                                value="{{ old('class_name') }}"
                                required
                                class="w-full rounded-xl border-gray-300
                       focus:border-purple-500
                       focus:ring-purple-500">
                        </div>


                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                Date
                            </label>

                            <input
                                type="date"
                                name="observation_date"
                                value="{{ old('observation_date') }}"
                                required
                                class="w-full rounded-xl border-gray-300
                       focus:border-purple-500
                       focus:ring-purple-500">
                        </div>


                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-semibold mb-2">
                                Subject
                            </label>

                            <input
                                type="text"
                                name="subject_name"
                                value="{{ old('subject_name') }}"
                                required
                                class="w-full rounded-xl border-gray-300
                       focus:border-purple-500
                       focus:ring-purple-500">
                        </div>


                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                Time
                            </label>

                            <input
                                type="time"
                                name="observation_time"
                                value="{{ old('observation_time') }}"
                                required
                                class="w-full rounded-xl border-gray-300
                       focus:border-purple-500
                       focus:ring-purple-500">
                        </div>


                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-semibold mb-2">
                                Observer
                            </label>

                            <input
                                type="text"
                                value="{{ Auth::guard('teacher')->user()->teacher_name }}"
                                readonly
                                class="w-full rounded-xl border-gray-300 bg-gray-100">
                        </div>


                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                School
                            </label>

                            <input
                                type="text"
                                value="{{ $guru->school?->school_name ?? '-' }}"
                                readonly
                                class="w-full rounded-xl border-gray-300 bg-gray-100">
                        </div>

                    </div>

                </div>

                <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">

                    <div class="p-8">

                        @foreach($form->sections as $section)

                        @php
                        $displayFields = $section->fields
                        ->where('field_type', 'display')
                        ->values();

                        $inputFields = $section->fields
                        ->where('field_type', '!=', 'display')
                        ->values();
                        @endphp


                        @if($displayFields->isNotEmpty())

                        <div class="overflow-x-auto mb-6">

                            <table class="w-full border-collapse text-sm">

                                <tbody>

                                    <tr>

                                        <th class="border border-gray-400
                                           bg-amber-400
                                           px-4 py-2
                                           text-center
                                           font-bold
                                           text-gray-900">

                                            {{ $section->section_name }}

                                        </th>

                                        @foreach($displayFields->take(3) as $field)

                                        <td class="border border-gray-400
                                               px-4 py-2
                                               text-gray-800
                                               font-medium">

                                            {{ $field->field_label }}

                                        </td>

                                        @endforeach

                                    </tr>

                                    <tr>

                                        @foreach($displayFields->slice(3, 4) as $field)

                                        <td class="border border-gray-400
                                               px-4 py-2
                                               text-gray-800
                                               font-medium">

                                            {{ $field->field_label }}

                                        </td>

                                        @endforeach

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                        @endif


                        @if($inputFields->isNotEmpty())

                        <div class="mb-6 last:mb-0">

                            <div class="mb-3">

                                <h2 class="text-lg font-bold text-blue-800">
                                    {{ $section->section_name }}
                                </h2>

                            </div>


                            <div class="space-y-4">

                                @foreach($inputFields as $field)

                                @if($field->field_type === 'textarea')

                                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-start">

                                    <div class="md:col-span-1">

                                        <label class="block text-gray-700
                                                      font-semibold pt-3">

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
                                            class="w-full rounded-xl
                                                   border-gray-300
                                                   focus:border-purple-500
                                                   focus:ring-purple-500">{{ old(
                                                        'answers.' . $field->fieldID
                                                    ) }}</textarea>

                                    </div>

                                </div>

                                @elseif($field->field_type === 'number')

                                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center">

                                    <label class="md:col-span-1
                                                  text-gray-700
                                                  font-semibold">

                                        {{ $field->field_label }}

                                    </label>

                                    <input
                                        type="number"
                                        name="answers[{{ $field->fieldID }}]"
                                        value="{{ old(
                                            'answers.' . $field->fieldID
                                        ) }}"
                                        class="md:col-span-5
                                               w-full rounded-xl
                                               border-gray-300">

                                </div>

                                @elseif($field->field_type === 'date')

                                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center">

                                    <label class="md:col-span-1
                                                  text-gray-700
                                                  font-semibold">

                                        {{ $field->field_label }}

                                    </label>

                                    <input
                                        type="date"
                                        name="answers[{{ $field->fieldID }}]"
                                        value="{{ old(
                                            'answers.' . $field->fieldID
                                        ) }}"
                                        class="md:col-span-5
                                               w-full rounded-xl
                                               border-gray-300">

                                </div>

                                @elseif($field->field_type === 'time')

                                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center">

                                    <label class="md:col-span-1
                                                  text-gray-700
                                                  font-semibold">

                                        {{ $field->field_label }}

                                    </label>

                                    <input
                                        type="time"
                                        name="answers[{{ $field->fieldID }}]"
                                        value="{{ old(
                                            'answers.' . $field->fieldID
                                        ) }}"
                                        class="md:col-span-5
                                               w-full rounded-xl
                                               border-gray-300">

                                </div>

                                @else

                                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center">

                                    <label class="md:col-span-1
                                                  text-gray-700
                                                  font-semibold">

                                        {{ $field->field_label }}

                                        @if($field->is_required)
                                        <span class="text-red-500">*</span>
                                        @endif

                                    </label>

                                    <input
                                        type="text"
                                        name="answers[{{ $field->fieldID }}]"
                                        value="{{ old(
                                            'answers.' . $field->fieldID
                                        ) }}"
                                        class="md:col-span-5
                                               w-full rounded-xl
                                               border-gray-300">

                                </div>

                                @endif

                                @endforeach

                            </div>

                        </div>

                        @endif

                        @endforeach

                    </div>

                </div>


                <div class="flex justify-center items-center gap-4">

                    <a
                    href="#"
                    class="px-6 py-3
                    bg-gray-200 hover:bg-gray-300
                    text-black-700 font-semibold
                    rounded-xl transition">
                        Back
                    </a>

                    <button
                        type="submit"
                        name="submit_action"
                        value="Draft"
                        class="px-6 py-3
                               bg-yellow-200 hover:bg-yellow-300
                               text-white-700 font-semibold
                               rounded-xl transition">

                        Save Draft

                    </button>


                    <button
                        type="submit"
                        name="submit_action"
                        value="Submitted"
                        class="px-6 py-3
                               bg-blue-700 hover:bg-blue-800
                               text-white font-semibold
                               rounded-xl shadow transition">

                        Submit

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>