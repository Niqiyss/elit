<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('admin')->user()->staffname }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6 space-y-8">

            {{-- Header --}}
            <div class="relative bg-gradient-to-br
                        from-slate-900 via-violet-950 to-purple-900
                        rounded-3xl p-8 shadow-xl overflow-hidden">

                <div class="absolute right-0 top-0
                            translate-x-10 -translate-y-10
                            w-72 h-72 bg-purple-500/10
                            rounded-full blur-3xl">
                </div>

                <div class="relative z-10">

                    <div class="flex flex-col
                                md:flex-row
                                md:items-start
                                md:justify-between
                                gap-5">

                        <div>

                            <h1 class="text-3xl font-extrabold text-white mt-2">
                                {{ $pdpcForm->form_name }}
                            </h1>

                            <p class="text-violet-200 mt-2">
                                Version {{ $pdpcForm->version_no }}
                            </p>

                            @if($pdpcForm->instruction)

                                <p class="text-violet-300 mt-3 max-w-4xl">
                                    {{ $pdpcForm->instruction }}
                                </p>

                            @endif

                        </div>

                        <div class="flex-shrink-0">

                            <span class="inline-flex
                                         items-center
                                         justify-center
                                         px-5 py-2
                                         rounded-full
                                         bg-emerald-500
                                         text-white
                                         text-sm
                                         font-semibold
                                         whitespace-nowrap">

                                {{ $pdpcForm->status }}

                            </span>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Success --}}
            @if(session('success'))

                <div class="px-5 py-4
                            bg-green-100
                            border border-green-200
                            text-green-700
                            rounded-xl">

                    {{ session('success') }}

                </div>

            @endif

            {{-- Error --}}
            @if(session('error'))

                <div class="px-5 py-4
                            bg-red-100
                            border border-red-200
                            text-red-700
                            rounded-xl">

                    {{ session('error') }}

                </div>

            @endif

            {{-- Form Structure --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                <div class="px-7 py-5">

                    <div class="flex flex-col
                                md:flex-row
                                md:items-center
                                md:justify-between
                                gap-4">

                        <div>

                            <h2 class="text-xl font-bold text-slate-900">
                                Form Structure
                            </h2>

                            <p class="text-sm text-slate-400 mt-1">
                                Review all Aspect, TUMS, Tahap Tindakan and RTK rubric.
                            </p>

                        </div>

                        <div class="flex items-center gap-3">

                            <a
                                href="{{ route(
                                    'admin.pdpc.form.edit',
                                    $pdpcForm
                                ) }}"
                                class="px-5 py-2.5
                                       bg-blue-700
                                       hover:bg-blue-800
                                       text-white
                                       text-sm
                                       font-semibold
                                       rounded-xl
                                       transition">

                                Edit

                            </a>

                            <a
                                href="{{ route('admin.manage.form') }}"
                                class="px-5 py-2.5
                                       bg-slate-100
                                       hover:bg-slate-200
                                       text-slate-600
                                       text-sm
                                       font-semibold
                                       rounded-xl
                                       transition">

                                Back

                            </a>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Aspects --}}
            @foreach($pdpcForm->aspects as $aspect)

                <section class="space-y-5">

                    {{-- Aspect Header --}}
                    <div class="bg-blue-900
                                rounded-2xl
                                px-6 py-4
                                text-white">

                        <p class="text-sm
                                  font-semibold
                                  uppercase
                                  tracking-wider
                                  text-blue-200">

                            Aspect {{ $aspect->aspect_code }}

                        </p>

                        <h2 class="text-lg font-bold mt-1">
                            {{ $aspect->aspect_name }}
                        </h2>

                    </div>

                    {{-- TUMS Cards --}}
                    @foreach($aspect->tums as $tums)

                        <div class="bg-white
                                    rounded-2xl
                                    shadow-lg
                                    overflow-hidden">

                            {{-- TUMS Header --}}
                            <div class="px-6 py-4
                                        border-b
                                        border-slate-200
                                        bg-slate-50">

                                <div class="flex
                                            items-center
                                            justify-between
                                            gap-4">

                                    <div class="flex-1">

                                        <p class="text-sm
                                                  font-bold
                                                  uppercase
                                                  tracking-wider
                                                  text-blue-700">

                                            TUMS {{ $tums->tums_code }}

                                        </p>

                                        <p class="text-base
                                                  font-bold
                                                  text-black
                                                  mt-1">

                                            {{ $tums->tums_name }}

                                        </p>

                                    </div>

                                    <div class="flex-shrink-0">

                                        <span class="inline-flex
                                                     items-center
                                                     justify-center
                                                     px-4 py-2
                                                     rounded-full
                                                     border
                                                     border-blue-200
                                                     bg-blue-50
                                                     text-blue-700
                                                     text-sm
                                                     font-semibold
                                                     whitespace-nowrap">

                                            Wajaran:
                                            {{ number_format($tums->wajaran, 2) }}

                                        </span>

                                    </div>

                                </div>

                            </div>

                            {{-- TT Table --}}
                            <div class="overflow-x-auto">

                                <table class="w-full table-fixed text-sm">

                                    <thead>

                                        <tr class="bg-slate-100
                                                   text-slate-600
                                                   uppercase
                                                   text-xs
                                                   tracking-wider">

                                            <th class="w-16
                                                       px-4 py-3
                                                       text-center
                                                       font-semibold">

                                                No

                                            </th>

                                            <th class="w-[42%]
                                                       px-5 py-3
                                                       text-left
                                                       font-semibold">

                                                Tahap Tindakan (TT)

                                            </th>

                                            <th class="px-5 py-3
                                                       text-left
                                                       font-semibold">

                                                Rubrik Tahap Kualiti (RTK)

                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody class="divide-y divide-slate-200">

                                        @foreach($tums->tt as $tt)

                                            <tr class="align-top">

                                                {{-- TT Number --}}
                                                <td class="px-4 py-5
                                                           text-center
                                                           text-black">

                                                    {{ $loop->iteration }}

                                                </td>

                                                {{-- TT Points --}}
                                                <td class="px-5 py-5
                                                           border-l
                                                           border-slate-200">

                                                    <div class="space-y-2">

                                                        @foreach($tt->points as $point)

                                                            <div class="flex gap-3 leading-6">

                                                                <span class="font-semibold
                                                                             text-black
                                                                             flex-shrink-0">

                                                                    {{ $loop->iteration }}.

                                                                </span>

                                                                <span class="text-black">

                                                                    {{ $point->point_text }}

                                                                </span>

                                                            </div>

                                                        @endforeach

                                                    </div>

                                                </td>

                                                {{-- One RTK Rubric Per TUMS --}}
                                                @if($loop->first)

                                                    <td
                                                        rowspan="{{ $tums->tt->count() }}"
                                                        class="px-5 py-5
                                                               border-l
                                                               border-slate-200
                                                               align-top">

                                                        <div class="space-y-3">

                                                            @foreach([4, 3, 2, 1, 0] as $score)

                                                                @php($rubric = $tums->rubrics->firstWhere('score', $score))

                                                                <div class="flex items-start gap-3">

                                                                    <div class="w-16 flex-shrink-0">

                                                                        <span class="w-full
                                                                                     inline-flex
                                                                                     items-center
                                                                                     justify-center
                                                                                     px-2 py-2
                                                                                     rounded-lg
                                                                                     bg-blue-500
                                                                                     text-white
                                                                                     text-xs
                                                                                     font-bold
                                                                                     whitespace-nowrap">

                                                                            RTK {{ $score }}

                                                                        </span>

                                                                    </div>

                                                                    <div class="flex-1
                                                                                min-w-0
                                                                                px-4 py-3
                                                                                rounded-xl
                                                                                border
                                                                                border-violet-200
                                                                                bg-white
                                                                                text-black
                                                                                leading-6">

                                                                        {{ $rubric?->description ?? '-' }}

                                                                    </div>

                                                                </div>

                                                            @endforeach

                                                        </div>

                                                    </td>

                                                @endif

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    @endforeach

                </section>

            @endforeach

            {{-- Delete Form --}}
            <div class="flex justify-center pt-4 pb-6">

                <form
                    method="POST"
                    action="{{ route(
                        'admin.pdpc.form.destroy',
                        $pdpcForm
                    ) }}"
                    onsubmit="return confirm(
                        'Are you sure you want to delete this PDPC form?'
                    );">

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="inline-flex
                               items-center
                               gap-2
                               px-5 py-2.5
                               bg-red-100
                               hover:bg-red-200
                               text-red-700
                               text-sm
                               font-semibold
                               rounded-xl
                               transition">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0 1
                                   16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7
                                   m5 4v6m4-6v6
                                   m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3
                                   M4 7h16" />

                        </svg>

                        Delete Form

                    </button>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>