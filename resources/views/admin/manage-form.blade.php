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
                        Manage Form
                    </h1>

                    <p class="text-violet-300 mt-2">
                        Manage observation form templates
                    </p>

                </div>

            </div>

            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                <div class="px-8 py-6 border-b border-gray-100">

                    <h2 class="text-xl font-bold text-gray-800">
                        Observation Forms
                    </h2>

                    <p class="text-sm text-gray-400 mt-1">
                        Select a form to manage its content
                    </p>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-slate-50 text-gray-500 uppercase text-xs">

                            <tr>

                                <th class="px-6 py-4 text-left font-semibold">
                                    No
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Form
                                </th>

                                <th class="px-6 py-4 text-left font-semibold">
                                    Description
                                </th>

                                <th class="px-6 py-4 text-center font-semibold">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            <tr class="hover:bg-violet-50/50 transition">

                                <td class="px-6 py-5 text-gray-600">
                                    1
                                </td>

                                <td class="px-6 py-5">

                                    <p class="font-bold text-gray-800">
                                        Pre Observation Form
                                    </p>

                                </td>

                                <td class="px-6 py-5 text-gray-600">
                                    Form used for pre stage evaluation
                                </td>

                                <td class="px-6 py-5 text-center">

                                    <button
                                        type="button"
                                        disabled
                                        class="bg-gray-300 text-gray-500
                                               px-5 py-2 rounded-xl
                                               font-semibold text-sm
                                               cursor-not-allowed">

                                        Manage

                                    </button>

                                </td>

                            </tr>


                            <tr class="hover:bg-violet-50/50 transition">

                                <td class="px-6 py-5 text-gray-600">
                                    2
                                </td>

                                <td class="px-6 py-5">

                                    <p class="font-bold text-gray-800">
                                        External Observation Form
                                    </p>

                                </td>

                                <td class="px-6 py-5 text-gray-600">
                                    Form used for pre and post stage evaluation
                                </td>

                                <td class="px-6 py-5 text-center">

                                    <button
                                        type="button"
                                        disabled
                                        class="bg-gray-300 text-gray-500
                                               px-5 py-2 rounded-xl
                                               font-semibold text-sm
                                               cursor-not-allowed">

                                        Manage

                                    </button>

                                </td>

                            </tr>

                            <tr class="hover:bg-violet-50/50 transition">

                                <td class="px-6 py-5 text-gray-600">
                                    3
                                </td>

                                <td class="px-6 py-5">

                                    <p class="font-bold text-gray-800">
                                        Feedback Observation Form
                                    </p>

                                </td>

                                <td class="px-6 py-5 text-gray-600">
                                    Form used for post stage evaluation
                                </td>

                                <td class="px-6 py-5 text-center">

                                    <a href="{{ route('admin.post.form') }}"
                                        class="inline-block bg-blue-700
                                               hover:bg-blue-800
                                               text-white px-5 py-2
                                               rounded-xl font-semibold
                                               text-sm shadow transition">

                                        Manage

                                    </a>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mt-8">

                <div class="px-8 py-6 border-b border-gray-100">

                    <h2 class="text-xl font-bold text-gray-800">
                        Evaluation Documents
                    </h2>

                    <p class="text-sm text-gray-400 mt-1">
                        Upload PDF or Excel forms for observers to download
                    </p>

                </div>

                <div class="p-8 border-b border-gray-100">

                    <form
                        method="POST"
                        action="{{ route('admin.evaluation.doc.store') }}"
                        enctype="multipart/form-data">

                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>

                                <label
                                    for="form_name"
                                    class="block text-gray-700 text-sm font-semibold mb-2">
                                    Form Name
                                </label>

                                <input
                                    type="text"
                                    id="form_name"
                                    name="form_name"
                                    value="{{ old('form_name') }}"
                                    placeholder="E.g. Borang Pencerapan"
                                    required
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
                                    for="file"
                                    class="block text-gray-700 text-sm font-semibold mb-2">
                                    Upload File
                                </label>

                                <input
                                    type="file"
                                    id="file"
                                    name="file"
                                    accept=".pdf,.xls,.xlsx"
                                    required
                                    class="w-full rounded-xl
                                           border border-gray-300
                                           px-4 py-2 bg-white">

                                <p class="text-xs text-gray-400 mt-2">
                                    PDF, XLS or XLSX only. Maximum 10MB.
                                </p>

                                @error('file')
                                <p class="text-red-500 text-sm mt-1">
                                    {{ $message }}
                                </p>
                                @enderror

                            </div>

                            <div class="md:col-span-2">

                                <label
                                    for="description"
                                    class="block text-gray-700 text-sm font-semibold mb-2">
                                    Description
                                </label>

                                <input
                                    type="text"
                                    id="description"
                                    name="description"
                                    value="{{ old('description') }}"
                                    placeholder="Enter form description"
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-purple-500
                                           focus:ring-purple-500">

                                @error('description')
                                <p class="text-red-500 text-sm mt-1">
                                    {{ $message }}
                                </p>
                                @enderror

                            </div>

                        </div>


                        <div class="flex justify-end mt-6">

                            <button
                                type="submit"
                                class="inline-flex items-center gap-2
                                px-5 py-2
                                bg-blue-700
                                hover:bg-blue-800
                                text-white font-semibold text-sm
                                rounded-xl shadow transition">

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
                                        d="M12 16V4m0 0L8 8m4-4l4 4M5 20h14" />

                                </svg>

                                Upload

                            </button>


                        </div>

                    </form>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-slate-50 text-gray-500 uppercase text-xs">

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

                                {{-- NUMBER --}}
                                <td class="px-6 py-5 text-gray-600">
                                    {{ $loop->iteration }}
                                </td>


                                {{-- FORM NAME --}}
                                <td class="px-6 py-5">

                                    <p class="font-bold text-gray-800">
                                        {{ $document->form_name }}
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $document->file_name }}
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

                                    <form
                                        method="POST"
                                        action="{{ route(
                                                'admin.evaluation.doc.delete',
                                                $document->doc_id
                                            ) }}">

                                        @csrf
                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            onclick="return confirm('Delete this file?')"
                                            title="Delete"
                                            class="inline-flex items-center justify-center
                                            w-10 h-10
                                            bg-red-500
                                            hover:bg-red-600
                                            text-white
                                            rounded-xl
                                            shadow
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
                                                    d="M3 6h18M8 6V4h8v2m-9 0 1 14h8l1-14M10 10v6m4-6v6" />

                                            </svg>

                                        </button>

                                    </form>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-10 text-gray-400">

                                    No evaluation documents uploaded

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