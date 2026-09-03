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
                        Download Observation Forms
                    </h1>

                    <p class="text-violet-300 mt-2">
                        You can download the observation forms below
                    </p>

                </div>

            </div>

            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                <div class="px-8 py-6 border-b border-gray-100">

                    <h2 class="text-xl font-bold text-gray-800">
                        Observation Forms
                    </h2>

                    <p class="text-sm text-gray-400 mt-1">
                        Download the observation form
                    </p>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-slate-50 text-gray-900 uppercase text-xs">

                            <tr>

                                <th class="px-6 py-4 text-left font-semibold">
                                    No
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Form Name
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Description
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Type
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse($documents as $document)

                            <tr class="hover:bg-violet-50/50 transition">

                                <td class="px-6 py-5 text-gray-600">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-5">

                                    <p class="font-bold text-gray-800">
                                        {{ $document->form_name }}
                                    </p>

                                </td>

                                <td class="px-6 py-5 text-gray-600">

                                    {{ $document->description ?? '-' }}

                                </td>

                                <td class="px-6 py-5 text-center">

                                    @if($document->file_type === 'PDF')

                                    <span class="px-3 py-1 rounded-full
                                                         text-xs font-semibold
                                                         bg-red-100 text-red-700">
                                        PDF
                                    </span>

                                    @else

                                    <span class="px-3 py-1 rounded-full
                                                         text-xs font-semibold
                                                         bg-green-100 text-green-700">
                                        EXCEL
                                    </span>

                                    @endif

                                </td>

                                <td class="px-6 py-5 text-center">

                                    @if($role === 'observer')

                                    <a
                                        href="{{ route(
                                                    'evaluation.doc.download',
                                                    $document->doc_id
                                                ) }}"
                                        class="inline-flex items-center gap-2
                                        bg-blue-700 
                                        text-white px-5 py-2
                                        rounded-xl font-semibold
                                        text-sm">

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
                                                d="M12 4v12m0 0l-4-4m4 4l4-4M5 20h14" />

                                        </svg>
                                        Download
                                    </a>

                                    @else

                                    <a
                                        href="{{ route(
                                                    'evaluation.doc.download',
                                                    $document->doc_id
                                                ) }}"
                                        class="inline-flex items-center gap-2
                                        bg-blue-700
                                        text-white px-5 py-2
                                        rounded-xl font-semibold
                                        text-sm">

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
                                                d="M12 4v12m0 0l-4-4m4 4l4-4M5 20h14" />

                                        </svg>
                                        Download
                                    </a>

                                    @endif

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-10 text-gray-400">
                                    No evaluation documents available
                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>