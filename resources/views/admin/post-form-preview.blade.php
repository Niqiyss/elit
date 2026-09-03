<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('admin')->user()->staffname }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-slate-100 py-8 px-6">

        <div class="max-w-7xl mx-auto">

            {{-- Header --}}
            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    <div>

                        <h1 class="text-3xl font-extrabold text-white">
                            {{ $form->form_name }}
                        </h1>

                        @if($form->instruction)
                            <p class="text-violet-300 mt-2">
                                {{ $form->instruction }}
                            </p>
                        @endif

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
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />

                                    <circle cx="12" cy="12" r="3" />

                                </svg>

                            </div>

                            <div>

                                <p class="text-sm font-bold text-white">
                                    Preview Only
                                </p>

                                <p class="text-xs text-blue-200 mt-0.5">
                                    This shows how the form appears to the evaluator
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            
            {{-- Dynamic sections --}}
            @foreach($form->sections as $section)

                @php
                    $displayFields = $section->fields
                        ->where('field_type', 'display')
                        ->values();

                    $inputFields = $section->fields
                        ->where('field_type', '!=', 'display')
                        ->values();
                @endphp

                {{-- Display fields --}}
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

                                                <td class="border-r border-b border-slate-200 px-5 py-3 text-slate-700 font-medium align-middle {{ $loop->last && $row->count() === 4 ? 'border-r-0' : '' }}">

                                                    {{ $field->field_label }}

                                                </td>

                                            @endforeach

                                            @if($row->count() < 4)

                                                @for($i = $row->count(); $i < 4; $i++)

                                                    <td class="border-r border-b border-slate-200 px-5 py-3 {{ $i === 3 ? 'border-r-0' : '' }}"></td>

                                                @endfor

                                            @endif

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                @endif

                {{-- Input fields --}}
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
                                                    rows="3"
                                                    disabled
                                                    class="w-full rounded-xl border-slate-300 bg-slate-50"></textarea>

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

                                                        <label class="inline-flex items-center gap-2">

                                                            <input
                                                                type="checkbox"
                                                                disabled
                                                                class="rounded border-slate-300 text-blue-600">

                                                            <span class="text-sm text-slate-700">
                                                                {{ $option->option_label }}
                                                            </span>

                                                        </label>

                                                    @endforeach

                                                </div>

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

                                                        <label class="inline-flex items-center gap-2">

                                                            <input
                                                                type="radio"
                                                                disabled
                                                                class="border-slate-300 text-blue-600">

                                                            <span class="text-sm text-slate-700">
                                                                {{ $option->option_label }}
                                                            </span>

                                                        </label>

                                                    @endforeach

                                                </div>

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
                                                    disabled
                                                    class="w-full rounded-xl border-slate-300 py-2 bg-slate-50">

                                            </div>

                                        </div>

                                    </div>

                                @endif

                            @endforeach

                        </div>

                    </div>

                @endif

            @endforeach

            <div class="mb-24"></div>

        </div>

    </div>

    {{-- Sticky action bar --}}
    <div class="fixed bottom-4 left-0 right-0 z-40 px-6 pointer-events-none">

        <div class="max-w-7xl mx-auto">

            <div class="bg-white/95 backdrop-blur-sm border border-slate-200 shadow-xl rounded-2xl px-6 py-4 pointer-events-auto">

                <div class="flex items-center justify-between">

                    <a
                        href="{{ route('admin.post.form') }}"
                        class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm rounded-xl transition">

                        Back

                    </a>

                    <a
                        href="{{ route('admin.post.form.edit', $form) }}"
                        class="px-5 py-2 bg-amber-400 hover:bg-amber-500 text-amber-950 font-semibold text-sm rounded-xl transition">

                        Edit

                    </a>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>